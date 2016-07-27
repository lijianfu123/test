<?php

		/**
		 * 解绑银行卡
		 * @param unknown_type $user_id
		 * @param unknown_type $card_id
		 */
		function UnbindingBankCard($user_id,$card_id,$member_url){
			$weibopay = new Weibopay();
			$request=array();
			$request["service"]="unbinding_bank_card";
			$request["version"]=sinapay_version;
			$request["request_time"]=date("YmdHis");
			$request["partner_id"]=sinapay_partner_id;
			$request["_input_charset"]=sinapay_input_charset;
			$request["sign"]="";
			$request["sign_type"]="RSA";
			$request['identity_id'] = to_guid_string($user_id);
			$request['identity_type'] = "UID";
			$request["card_id"]=$card_id;
			ksort($request);//对签名参数据排序
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
					$GLOBALS['db']->query("delete from ".DB_PREFIX."sina_bind_bank_card where user_id =".$user_id." and card_id='".$card_id."'");	
					showIpsInfo("解绑成功",SITE_DOMAIN.APP_ROOT."/index.php");		
				}else{
					showErr($splitdata["response_message"],0);
				}
			
			}
		}

?>
