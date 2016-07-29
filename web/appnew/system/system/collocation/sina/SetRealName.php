<?php
/**
	 * 设置实名信息
	 * @param int $user_id
	 * @param unknown_type $member_url
	 * @return string
	 */
	function SetRealName($user_id,$member_url){
		$weibopay = new Weibopay();
		$user = array();
		$user = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = ".$user_id);
		
		$data = array();
		$data['user_id'] = $user_id;
		$data['sinapay_partner_id'] = sinapay_partner_id;
		$data['identity_id'] = to_guid_string($user_id);
		$data['identity_type'] = "UID";
		$data['real_name'] = $weibopay->Rsa_encrypt($user['real_name'],sinapay_rsa_public__key);
		$data['cert_type'] = "IC";
		$data['cert_no'] = $weibopay->Rsa_encrypt($user['idno'],sinapay_rsa_public__key);;
		$data['mobile'] = $user['mobile'];
		$data['email'] = $user['email'];
		$GLOBALS['db']->autoExecute(DB_PREFIX."sina_bind_state",$data,'UPDATE',"user_id=".$data['user_id']."");
		
		$request=array();
		$request["service"]="set_real_name";
		$request["version"]=sinapay_version;
		$request["request_time"]=date("YmdHis");
		$request["partner_id"]=$data['sinapay_partner_id'];
		$request["_input_charset"]=sinapay_input_charset;
		
		$request["sign"]="";
		$request["sign_type"]="RSA";
		$request["identity_id"]=$data['identity_id'];
		$request["identity_type"]=$data['identity_type'];
		$request["real_name"]=$data['real_name'];
		$request["cert_type"]=$data['cert_type'];
		$request["cert_no"]=$data['cert_no'];

		ksort($request);//对签名参数据排序
		//print_r($request);
		
		$request["sign"]=$weibopay->getSignMsg($request,$request["sign_type"]);
		
		$tijiao = $weibopay->createcurl_data($request); // 调用createcurl_data创建模拟表单需要的数据
		$result = $weibopay->curlPost($member_url,$tijiao); // 使用模拟表单提交进行数据提交
		$result = urldecode ($result);
		$splitdata = array ();
		$splitdata = json_decode($result,true);
		$sign_type = $splitdata ['sign_type'];
		ksort($splitdata); 

		if ($weibopay->checkSignMsg ($splitdata,$sign_type)) {
			
		
			file_put_contents("data.txt",$splitdata["response_code"]);   
			
	
			if ($splitdata["response_code"] == 'APPLY_SUCCESS') { // 成功
				$splitdata["is_callback"]=1;
				//print_r($splitdata);
				$GLOBALS['db']->autoExecute(DB_PREFIX."sina_bind_state",$splitdata,'UPDATE',"user_id=".$data['user_id']."");
				
				//来来1号店
				$GLOBALS['db']->query("update ".DB_PREFIX."user set idcardpassed = 1 where id =". $data['user_id'] );
				
				showIpsInfo("正在认证您的信息",SITE_DOMAIN.APP_ROOT."/index.php?ctl=collocation&act=BindingVerify&user_id=".$data['user_id']."&verify_type=EMAIL");
				
			} else { // 失败
				/* showErr($splitdata["response_message"],0,SITE_DOMAIN.APP_ROOT."/index.php?ctl=user&act=steptwo");
				exit; */
				
				
				if($splitdata["response_code"] == "DUPLICATE_VERIFY"){
				
					$GLOBALS['db']->autoExecute(DB_PREFIX."sina_bind_state",$splitdata,'UPDATE',"user_id=".$data['user_id']."");
					showIpsInfo("正在认证您的信息",SITE_DOMAIN.APP_ROOT."/index.php?ctl=collocation&act=BindingVerify&user_id=".$data['user_id']."&verify_type=EMAIL");
				}else{
					
					if(isMobile()){
						
						showErr($splitdata["response_message"],0,SITE_DOMAIN.APP_ROOT."/wap/member.php?ctl=uc_center");
					}
					
					showErr($splitdata["response_message"],0,SITE_DOMAIN.APP_ROOT."/member.php?ctl=uc_account&act=security");
					
				}
				
			//showErr($splitdata["response_message"],0,SITE_DOMAIN.APP_ROOT."/index.php?ctl=user&act=steptwo");
				exit;
			}

		
		} else {
			showIpsInfo("sing error");
		}
		
		
	}
?>