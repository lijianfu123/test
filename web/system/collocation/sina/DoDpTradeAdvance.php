<?php


 
	
	function DoDpTradeAdvance($user_id,$ticket,$id,$acquiring_url){	
		$pS2SUrl= SITE_DOMAIN.APP_ROOT."/sina/Notifyurl.php";//s2s方式返回	
		$weibopay = new Weibopay();
		if (isset($_POST['submit'])){
			$request=array();
			$request["version"]=sinapay_version;
			$request["request_time"]= date("YmdHis");
			$request["partner_id"]=sinapay_partner_id;
			$request["_input_charset"]=sinapay_input_charset;
			$request["notify_url"]=$pS2SUrl;
			$request["sign_type"]="RSA";
			$request["service"]="advance_hosting_pay";
			$request["memo"]="DoDpTradeAdvance";
			$request["out_advance_no"]=$id;
			$request["ticket"]=$ticket;
			$request["validate_code"]=$_POST['validate_code'];
			ksort($request);//对签名参数据排序
			$request["sign"]=urlencode($weibopay->getSignMsg($request,$request["sign_type"]));
		//	$tijiao = $weibopay->createcurl_data($request); // 调用createcurl_data创建模拟表单需要的数据
			$result = post2($acquiring_url,$request); // 使用模拟表单提交进行数据提交
			$result = urldecode ($result);
			$splitdata = array ();
			$splitdata = json_decode($result,true);
			$sign_type = $splitdata ['sign_type'];
			ksort($splitdata); 
			if ($weibopay->checkSignMsg ($splitdata,$sign_type)) {
				//print_r($splitdata);
				//echo $id;
				if ($splitdata["response_code"] == 'APPLY_SUCCESS') { // 成功
				$GLOBALS['db']->autoExecute(DB_PREFIX."sina_bind_bank_card",array('card_id'=>$splitdata["card_id"],'is_verified'=>$splitdata["is_verified"]),'UPDATE',"id=".$splitdata["memo"]."");
				
				showIpsInfo("充值成功",SITE_DOMAIN.APP_ROOT."/member.php?ctl=uc_ips&act=recharge");
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
<title>填写验证码</title>
</head>
<style>
	.bg_1 {
	float:left;
	width:70%;
	height:300px;
   	background-image:url(http://www.haiou123.com/app/Tpl/new/images/aqyz.png);
	}
	.bg_2 {
	width:330px;
	height:100px;
   	background-image:url(http://www.haiou123.com/app/Tpl/new/images/bdlogo.png);
   	
	}
	.container1{
	float:left;
	height:300px; 
	width:30%;
	background-image:url(http://www.haiou123.com/app/Tpl/new/images/bdaqyz.png);
	}
</style>
<body >
<div class="bg_2"></div>
<div style="height:40px;"></div>
<div>
	<div>
		<div class="bg_1"></div>
		<div class="container1">  
				<form name="form1" id="form1" method="post"  target="_self" style="margin-top:90px;">	
				<h3>海鸥金服安全短信验证</h3>			
				<div  style="margin:auto;text-align: center;"></div>
					<input placeholder="请输入您的验证码" type="text" id="valid_code" name="valid_code" style="letter-spacing:3px;width:250px;height:40px;">
				<br>
					<button name="submit" type="submit" id="contact-submit" style="width:250px;height:30px;margin-top:20px;">提交</button>
			</form>
		</div>
	</div>
</div>

</body>
</html>';
				
				
				return $html;
		}
	
	}
	
	
	function DoDpTradeAdvanceCallBack($str3Req){
		$out_trade_no = $str3Req["out_advance_no"];
		$where = " out_advance_no = '".$out_trade_no."'";
		$sql = "update ".DB_PREFIX."sina_recharge set is_callback = 1,create_time = ".TIME_UTC." where is_callback = 0 and ".$where;
		
		$GLOBALS['db']->query($sql);
		if ($GLOBALS['db']->affected_rows()){		
 
			$GLOBALS['db']->autoExecute(DB_PREFIX."sina_recharge",$str3Req,'UPDATE',$where);
				
					
		}else{
			echo $str3Req["error_code"];
		}
		//print_r($str3Req);
		echo 'SUCCESS';
	}
	function post2($url, $data){//file_get_content
        $postdata = http_build_query($data);
        $opts = array('http' =>
		  array(

			  'method'  => 'POST',

			  'header'  => 'Content-type: application/x-www-form-urlencoded',

			  'content' => $postdata

		  )
        );
        $context = stream_context_create($opts);
        $result = file_get_contents($url, false, $context);
        return $result;
    }
?>