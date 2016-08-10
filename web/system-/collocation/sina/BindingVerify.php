<?php
/**
	 * 绑定认证信息
	 * @param int $user_id
	 * @param unknown_type $member_url
	 * @param unknown_type $verify_type MOBILE手机 EMAIL邮箱
	 * @return string
	 */ 
	function BindingVerify($user_id,$verify_type,$member_url){
		$weibopay = new Weibopay();
		$user = array();
		$user = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = ".$user_id);
		
		$data = array();
		$data['user_id'] = $user_id;
		$data['sinapay_partner_id'] = sinapay_partner_id;
		$data['identity_id'] = to_guid_string($user_id);
		$data['identity_type'] = "UID";
		$data['mobile'] = $user['mobile'];
		$data['email'] = $user['email'];
		$GLOBALS['db']->autoExecute(DB_PREFIX."sina_bind_state",$data,'UPDATE',"user_id=".$data['user_id']."");
	
		
		$request=array();
		$request["service"]="binding_verify";
		$request["version"]=sinapay_version;
		$request["request_time"]=date("YmdHis");
		$request["partner_id"]=$data['sinapay_partner_id'];
		$request["_input_charset"]=sinapay_input_charset;
		
		$request["sign"]="";
		$request["sign_type"]="RSA";
		$request["identity_id"]=$data['identity_id'];
		$request["identity_type"]=$data['identity_type'];
		$request["verify_type"]=$verify_type;
		if($request["verify_type"]=='MOBILE'){
			$request["verify_entity"]=$weibopay->Rsa_encrypt($data['mobile'],sinapay_rsa_public__key);
		}else if($request["verify_type"]=='EMAIL'){
			$request["verify_entity"]=$weibopay->Rsa_encrypt($data['email'],sinapay_rsa_public__key);
		}
		ksort($request);//对签名参数据排序
		$request["sign"]=$weibopay->getSignMsg($request,$request["sign_type"]);
		//print_r($request);
		//exit;
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
				$GLOBALS['db']->autoExecute(DB_PREFIX."sina_bind_state",$splitdata,'UPDATE',"user_id=".$data['user_id']."");
			 	
				if($request["verify_type"]=='MOBILE'){
					$GLOBALS['db']->autoExecute(DB_PREFIX."user",array('ips_acct_no'=>$data['identity_id']),'UPDATE',"id=".$data['user_id']."");
					 
					
					if (isMobile()){
						showIpsInfo($splitdata["response_message"],SITE_DOMAIN.APP_ROOT."/wap/member.php?ctl=uc_center");
						
					}else{
						showIpsInfo($splitdata["response_message"],SITE_DOMAIN.APP_ROOT);
					}
					
				}else if($request["verify_type"]=='EMAIL'){
					showIpsInfo("正在认证中",SITE_DOMAIN.APP_ROOT."/index.php?ctl=collocation&act=BindingVerify&user_id=".$data['user_id']."&verify_type=MOBILE");
	 			}
			} else { 
			    	if($splitdata["response_code"]="DUPLICATE_VERIFY"){
						$GLOBALS['db']->autoExecute(DB_PREFIX."sina_bind_state",$splitdata,'UPDATE',"user_id=".$data['user_id']."");
						if($request["verify_type"]=='MOBILE'){
							$GLOBALS['db']->autoExecute(DB_PREFIX."user",array('ips_acct_no'=>$data['identity_id']),'UPDATE',"id=".$data['user_id']."");
							showIpsInfo($splitdata["response_message"],SITE_DOMAIN.APP_ROOT);
							
						}else if($request["verify_type"]=='EMAIL'){
							showIpsInfo("正在认证中",SITE_DOMAIN.APP_ROOT."/index.php?ctl=collocation&act=BindingVerify&user_id=".$data['user_id']."&verify_type=MOBILE");
						}
					
					}else{
						showErr($splitdata["response_message"],0);
					}
				
		
			    // 失败
				//print_r($splitdata);
				//$GLOBALS['db']->autoExecute(DB_PREFIX."sina_bind_state",$splitdata,'UPDATE',"user_id=".$data['user_id']."");
				
				
				
				showErr($splitdata["response_message"],0);
				exit;
			}
		} else {
			showIpsInfo("sing error");
		}
		
		
	}
?>