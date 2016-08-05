<?php

	/**
	 * 创建新帐户
	 * @param int $user_id
	 * @param int $user_type 0:普通用户fanwe_user.id;1:担保用户fanwe_deal_agency.id
	 * @param unknown_type $post_url
	 * @return string
	 */
	function CreateNewAcct($user_id,$user_type,$member_url){
		
		$user = array();
		$user =get_user_info("*","is_delete=0 and id=".$user_id);
		file_put_contents("data2.txt",$user);
		$data = array();
		$data['user_id'] = $user_id;
		$data['sinapay_partner_id'] = sinapay_partner_id;
		$data['identity_id'] = to_guid_string($user_id);
		$data['identity_type'] = "UID";
		if($user_type==0){
			$data['member_type'] = 1;
		}else if($user_type==1){
			$data['member_type'] = 2;
		}
		$data['real_name'] = $user['real_name'];
		$data['cert_type'] = "IC";
		$data['cert_no'] = $user['idno'];
		$data['mobile'] = $user['mobile'];
		$data['email'] = $user['email'];
		$data['request_time'] = date("YmdHis");
		$data['create_time'] = time();

		$tuoguan = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."sina_bind_state where user_id=".$data['user_id']."");
		if(!$tuoguan){
			$GLOBALS['db']->autoExecute(DB_PREFIX."sina_bind_state",$data,'INSERT');
			$id = $GLOBALS['db']->insert_id();
		}else{
			$id=$tuoguan["id"];
			$GLOBALS['db']->autoExecute(DB_PREFIX."sina_bind_state",$data,'UPDATE',"id=".$id."");
		}
		
		$request=array();
		$request["service"]="create_activate_member";
		$request["version"]=sinapay_version;
		$request["request_time"]=$data['request_time'];
		$request["partner_id"]=$data['sinapay_partner_id'];
		$request["_input_charset"]=sinapay_input_charset;
		
		$request["sign"]="";
		$request["sign_type"]="RSA";
		$request["identity_id"]=$data['identity_id'];
		$request["identity_type"]=$data['identity_type'];
		$request["member_type"]=$data['member_type'];
		
		ksort($request);//对签名参数据排序
		$weibopay = new Weibopay();
		$request["sign"]=$weibopay->getSignMsg($request,$request["sign_type"]);
		
		$tijiao = $weibopay->createcurl_data($request); // 调用createcurl_data创建模拟表单需要的数据
		$result = $weibopay->curlPost($member_url,$tijiao); // 使用模拟表单提交进行数据提交
		$result = urldecode ($result);
		$splitdata = array ();
		$splitdata = json_decode($result,true);
		$sign_type = $splitdata ['sign_type'];
		ksort($splitdata); 
		if ($weibopay->checkSignMsg ($splitdata,$sign_type)) {
			if ($splitdata["response_code"] == 'APPLY_SUCCESS') { // 成功
				$splitdata["is_callback"]=1;
				//print_r($splitdata);
				$GLOBALS['db']->autoExecute(DB_PREFIX."sina_bind_state",$splitdata,'UPDATE',"id=".$id."");
				
				if($user["user_type"]=="0" || $user["user_type"]==0){
						showIpsInfo("实名认证中",SITE_DOMAIN.APP_ROOT."/index.php?ctl=collocation&act=SetRealName&user_id=".$data['user_id']);
					}else{
						$GLOBALS['db']->autoExecute(DB_PREFIX."user",array('ips_acct_no'=>$data['identity_id']),'UPDATE',"id=".$data['user_id']."");
						showIpsInfo("下一步提交企业信息审核",SITE_DOMAIN.APP_ROOT."/index.php?ctl=collocation&act=AuditMemberInfos&user_id=".$data['user_id']."");
					}
				
				
			} else { // 失败response_code
				//print_r($splitdata);
				if($splitdata["response_code"]="DUPLICATE_IDENTITY_ID"){
					
					
					if($user["user_type"]=="0" || $user["user_type"]==0){
						$GLOBALS['db']->autoExecute(DB_PREFIX."sina_bind_state",$splitdata,'UPDATE',"id=".$id."");
						showIpsInfo("下一步实名认证",SITE_DOMAIN.APP_ROOT."/index.php?ctl=collocation&act=SetRealName&user_id=".$data['user_id']);
					}else{
						showIpsInfo("下一步提交企业信息审核",SITE_DOMAIN.APP_ROOT."/index.php?ctl=collocation&act=AuditMemberInfos&user_id=".$data['user_id']."");
					}
				
				}else{
				showErr($splitdata["response_message"],0);
				}
				//$GLOBALS['db']->autoExecute(DB_PREFIX."user",array('ips_acct_no'=>$data['identity_id']),'UPDATE',"id=".$data['user_id']."");
				showErr($splitdata["response_message"],0);
			}
		} else {
			showIpsInfo("sing error");
		}
	}
?>