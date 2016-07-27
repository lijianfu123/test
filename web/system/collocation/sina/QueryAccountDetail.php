<?php

function QueryAccountDetail($user_id,$member_url){
	//require APP_ROOT_PATH.'app/Lib/page.php';
	if(empty($_REQUEST["start_time"])){
		$_REQUEST["start_time"]=date("Ym01000000");
	}
	if(empty($_REQUEST["end_time"])){
		$_REQUEST["end_time"]=date("YmdHis");
	}
	if(empty($_REQUEST["p"])){
		$_REQUEST["p"]=1;
	}
	
	$weibopay = new Weibopay();
	$request=array();
	$request["service"]="query_account_details";
	$request["version"]=sinapay_version;
	$request["request_time"]=date("YmdHis");
	$request["partner_id"]=sinapay_partner_id;
	$request["_input_charset"]=sinapay_input_charset;
	
	$request["sign"]="";
	$request["sign_type"]="RSA";
	$request["identity_id"]=to_guid_string($user_id);
	$request["identity_type"]="UID";
	$request["account_type"]="SAVING_POT";
	$request["start_time"]=date("YmdHis",strtotime($_REQUEST["start_time"]));
	$request["end_time"]=date("YmdHis",strtotime($_REQUEST["end_time"]));
	$request["page_no"]=$_REQUEST["p"];
	$request["page_size"]=30; 
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
			if((int)$result["total_item"]>0){
				$riri=explode("|",$splitdata["detail_list"]);
				
				 
				$result["detail_list"]=array();
				foreach($riri as $key=>$val){
					$ririss=array();
					$ririss=explode("^",$val);
					$result["detail_list"][$key][0]=$ririss[0];
					$result["detail_list"][$key][1]=date("Y-m-d H:i:s",strtotime($ririss[1]));
					$result["detail_list"][$key][2]=$ririss[2];
					$result["detail_list"][$key][3]=$ririss[3]; 
					$result["detail_list"][$key][4]=$ririss[4];
					$result["detail_list"][$key][5]=$ririss[5];
				}
			}
	}else{
		$splitdata["response_code"]="sign_err";
		$splitdata["response_message"]="签名错误！";
		$result = $splitdata;
	}
	
	$GLOBALS['tmpl']->assign("start_time",date("Y-m-d H:i:s",strtotime($_REQUEST["start_time"])));
	$GLOBALS['tmpl']->assign("end_time",date("Y-m-d H:i:s",strtotime($_REQUEST["end_time"])));
	//$page = new Page((int)$result["total_item"]);   //初始化分页对象 
	//$p  =  $page->show();
	$GLOBALS['tmpl']->assign('pages',$p);
	$GLOBALS['tmpl']->assign('user_id',$user_id);
	$GLOBALS['tmpl']->assign("result",$result); 
	
		$GLOBALS['tmpl']->assign("inc_file","inc/uc/QueryAccountDetail.html");
		$GLOBALS['tmpl']->display("page/uc.html");
}
?>