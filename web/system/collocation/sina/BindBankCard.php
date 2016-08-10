<?php

function BindBankCard($user_id,$member_url){
	require_once(APP_ROOT_PATH.'system/collocation/sina/BankListArray.php');
	$weibopay = new Weibopay();
	$user = array();
	$user = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = ".$user_id);
	$data = array();
	$data['user_id'] = $user_id;
	$data['card_id'] = "0";
	$data['sinapay_partner_id'] = sinapay_partner_id;
	$data['identity_id'] = to_guid_string($user_id);
	$data['identity_type'] = "UID";
	$data['request_no'] = build_order_no();
	$data['account_name'] = $user["real_name"];
	$data['card_type'] = "DEBIT";
	$data['verify_mode'] = "SIGN";
	$data['request_time'] = date("YmdHis");
	$data['create_time'] = time();

	if (isset($_POST['submit'])){
		$data['card_attribute'] = $_POST["card_attribute"];
		$data['bank_code'] = $_POST["bank_code"];
		$data['bank_account_no'] = $_POST["bank_account_no"];
		$data['province'] = $_POST["province"];
		$data['city'] = $_POST["city"];
		$data['phone_no'] = $_POST["phone_no"];
		$GLOBALS['db']->autoExecute(DB_PREFIX."sina_bind_bank_card",$data,'INSERT');
		$id = $GLOBALS['db']->insert_id();
		$_POST["memo"]=$id;
		$_POST["account_name"]= $weibopay->Rsa_encrypt($_POST['account_name'],sinapay_rsa_public__key);
		$_POST["phone_no"]= $weibopay->Rsa_encrypt($_POST['phone_no'],sinapay_rsa_public__key);
		$_POST["bank_account_no"]= $weibopay->Rsa_encrypt($_POST['bank_account_no'],sinapay_rsa_public__key);
		unset($_POST["submit"]);
		unset($_POST['user_id']);
		ksort($_POST);
		$_POST["sign"]=$weibopay->getSignMsg($_POST,$_POST["sign_type"]);

		$tijiao = $weibopay->createcurl_data($_POST); // 调用createcurl_data创建模拟表单需要的数据
		
		$result = $weibopay->curlPost($member_url,$tijiao); // 使用模拟表单提交进行数据提交
		$result = urldecode ($result);

		$splitdata = array ();
		$splitdata = json_decode($result,true);

		$sign_type = $splitdata ['sign_type'];
		ksort($splitdata); 

		if ($weibopay->checkSignMsg ($splitdata,$sign_type)) {
				if ($splitdata["response_code"] == 'APPLY_SUCCESS') { // 成功
				$splitdata["is_callback"]=1;
				$GLOBALS['db']->autoExecute(DB_PREFIX."sina_bind_bank_card",$splitdata,'UPDATE',"id=".$splitdata["memo"]."");
					$echo = array();
					$echo['response_code'] = $splitdata["response_code"];
					$echo['url'] = SITE_DOMAIN.APP_ROOT."/index.php?ctl=collocation&act=BindBankCardAdvance&id=".$splitdata["memo"]."&user_id=".$data['user_id']."&ticket=".$splitdata["ticket"]."";
					echo json_encode($echo);//json形式ajax返回DH20160810修改
					//showIpsInfo("已发送短信至您的手机,请输入验证码！",SITE_DOMAIN.APP_ROOT."/index.php?ctl=collocation&act=BindBankCardAdvance&id=".$splitdata["memo"]."&user_id=".$data['user_id']."&ticket=".$splitdata["ticket"]."");
				}else{
					$echo = array();
					$echo['response_code'] = $splitdata["response_code"];
					$echo['response_message'] = $splitdata["response_message"];
					echo json_encode($echo);//json形式ajax返回DH20160810修改
					//showErr($splitdata["response_message"],0);
				}
	
		
		}
		
		
		
		exit();
	}

	
	$request=array();
	$request["version"]=sinapay_version;
	$request["request_time"]= $data['request_time'];
	$request["partner_id"]=$data['sinapay_partner_id'];
	$request["_input_charset"]=sinapay_input_charset;
	$request["sign_type"]="RSA";
	$request["service"]="binding_bank_card";
	$request["request_no"]=$data['request_no'];
	$request["identity_id"]=$data['identity_id'];
	$request["identity_type"]=$data['identity_type'];
	$request["account_name"]=$data['account_name'];
	$request["card_type"]=$data['card_type'];
	$request["card_attribute"]=$data['card_attribute'];
	$request["verify_mode"]=$data['verify_mode'];


	$GLOBALS['tmpl']->assign("BankList",$BankList);
	$GLOBALS['tmpl']->assign("request",$request);
	$GLOBALS['tmpl']->assign("data",$user);
	if($_REQUEST["from"]=='wap'){
		$GLOBALS['tmpl']->display("page/bangkawap.html"); 
	}else{
		$GLOBALS['tmpl']->display("page/bangka.html"); 
	}
}

?>