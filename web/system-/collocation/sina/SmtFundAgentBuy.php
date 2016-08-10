<?php
/**
	 * 设置实名信息
	 * @param int $user_id
	 * @param unknown_type $member_url
	 * @return string
	 */
	function SmtFundAgentBuy($user_id,$member_url){
		$weibopay = new Weibopay();
		$user = array();
		$user = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = ".$user_id);
		
		$data = array();
		$data['user_id'] = $user_id;
		$data['sinapay_partner_id'] = sinapay_partner_id;
		$data['identity_id'] = to_guid_string($user_id);
		$data['identity_type'] = "UID";
		$data['agent_name'] = $weibopay->Rsa_encrypt($user['j_agent_name'],sinapay_rsa_public__key);
		$data['license_no'] = $weibopay->Rsa_encrypt($user['j_license_no'],sinapay_rsa_public__key);
		$data['license_type_code'] = $user['j_license_type_code'];
		$data['agent_mobile'] = $weibopay->Rsa_encrypt($user['j_agent_mobile'],sinapay_rsa_public__key);
		
		$request=array();
		$request["service"]="smt_fund_agent_buy";
		$request["version"]=sinapay_version;
		$request["request_time"]=date("YmdHis");
		$request["partner_id"]=$data['sinapay_partner_id'];
		$request["_input_charset"]=sinapay_input_charset;
		
		$request["sign"]="";
		$request["sign_type"]="RSA";
		$request["identity_id"]=$data['identity_id'];
		$request["identity_type"]=$data['identity_type'];
		$request["agent_name"]=$data['agent_name'];
		$request["license_no"]=$data['license_no'];
		$request["license_type_code"]=$data['license_type_code'];
		$request["agent_mobile"]=$data['agent_mobile'];
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
		
		//print_r($splitdata);
		if ($weibopay->checkSignMsg ($splitdata,$sign_type)) {
			if ($splitdata["response_code"] == 'APPLY_SUCCESS') { // 成功
				$splitdata["is_jingbanren"]=1;
				$GLOBALS['db']->autoExecute(DB_PREFIX."user",$splitdata,'UPDATE',"id=".$data['user_id']."");
				showIpsInfo("提交成功",SITE_DOMAIN.APP_ROOT."/member.php?ctl=uc_center");
				
				exit;
			}else{
					showErr($splitdata["response_message"],0,SITE_DOMAIN.APP_ROOT."/index.php?ctl=user&act=smt_fund_agent_buy");
			}
		} else {
			showIpsInfo("sing error");
		}
		
		
	}
?>