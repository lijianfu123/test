<?php
	
	/**
	 * 绑定银行卡推进
	 * @param unknown_type $user_id
	 * @param unknown_type $ticket
	 * @param unknown_type $id
	 * @param unknown_type $member_url
	 */
	function BindBankCardAdvance($user_id,$ticket,$id,$member_url){
		
		$weibopay = new Weibopay();
		if (isset($_POST['submit'])){
		$request=array();
		$request["version"]=sinapay_version;
		$request["request_time"]= date("YmdHis");
		$request["partner_id"]=sinapay_partner_id;
		$request["_input_charset"]=sinapay_input_charset;
		$request["sign_type"]="RSA";
		$request["service"]="binding_bank_card_advance";
		$request["memo"]=$id;
		$request["ticket"]=$ticket;
		$request["valid_code"]=$_POST['valid_code'];
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
			$GLOBALS['db']->autoExecute(DB_PREFIX."sina_bind_bank_card",array('card_id'=>$splitdata["card_id"],'is_verified'=>$splitdata["is_verified"]),'UPDATE',"id=".$splitdata["memo"]."");
			showIpsInfo("银行卡绑定成功",SITE_DOMAIN.APP_ROOT."/index.php");
			}else{
				showErr($splitdata["response_message"],0);
			}
		}
		
		
		}else{
		
		$html = '<html class="js cssanimations"><head lang="en">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">

<link href="app/Tpl/blue/bangka/duanx.css" rel="stylesheet" type="text/css" />
<title>填写验证码</title>
 
</head>
<style>

</style>
<body >
<div style="height:40px;"></div>
<div class="container" style="  max-width: 640px; margin:auto;">  
		<form name="form1" id="form1" method="post"  target="_self">	
		<h3>短信验证</h3>
		
		<div  style="margin:auto;text-align: center;"><img style="margin:auto;" src="app/Tpl/blue/bangka/duanxx.png"></div>
		<p style="color:#fff;font-size:18px;margin-bottom:20px;">请输入您收到的验证码</p>
		<fieldset>
			<input placeholder="请输入您的验证码" type="text" id="valid_code" name="valid_code" style="letter-spacing:3px;">
		</fieldset>

		<fieldset>
			<button name="submit" type="submit" id="contact-submit">提交</button>
		</fieldset>
	</form>
</div>
</body>
</html>';
		
		
		return $html;
		}
		
	}	
?>