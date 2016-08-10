<?php

function QueryAuditResult($user_id,$member_url){
	//require APP_ROOT_PATH.'app/Lib/page.php'
	
	$weibopay = new Weibopay();
	$request=array();
	$request["service"]="query_audit_result";
	$request["version"]=sinapay_version;
	$request["request_time"]=date("YmdHis");
	$request["partner_id"]=sinapay_partner_id;
	$request["_input_charset"]=sinapay_input_charset;
	
	$request["sign"]="";
	$request["sign_type"]="RSA";
	$request["identity_id"]=to_guid_string($user_id);
	$request["identity_type"]="UID";
	ksort($request);
		
	$request["sign"]=$weibopay->getSignMsg($request,$request["sign_type"]);
	
	$tijiao = $weibopay->createcurl_data($request);
	$result = $weibopay->curlPost($member_url,$tijiao);
	$result = urldecode ($result);
	$splitdata = array ();
	$splitdata = json_decode($result,true);
	$sign_type = $splitdata ['sign_type'];
	ksort($splitdata); 
	if ($weibopay->checkSignMsg ($splitdata,$sign_type)) {
			$result = $splitdata;
			print_r($result);
			
	}else{
		$splitdata["response_code"]="sign_err";
		$splitdata["response_message"]="签名错误！";
		$result = $splitdata;
	}
	
	//$GLOBALS['tmpl']->assign("start_time",date("Y-m-d H:i:s",strtotime($_REQUEST["start_time"])));
	//$GLOBALS['tmpl']->assign("end_time",date("Y-m-d H:i:s",strtotime($_REQUEST["end_time"])));
	//$page = new Page((int)$result["total_item"]);   //初始化分页对象 
	//$p  =  $page->show();
	//$GLOBALS['tmpl']->assign('pages',$p);
	//$GLOBALS['tmpl']->assign('user_id',$user_id);
	//$GLOBALS['tmpl']->assign("result",$result); 
	
		//$GLOBALS['tmpl']->assign("inc_file","inc/uc/QueryAccountDetail.html");
		//$GLOBALS['tmpl']->display("page/uc.html");
}
?>