<?php
 
function QueryForAccBalance($user_id,$user_type,$member_url="https://testgate.pay.sina.com.cn/mgs/gateway.do"){
	$weibopay = new Weibopay();
	$request=array();
	$request["service"]="query_balance";
	$request["version"]=sinapay_version;
	$request["request_time"]=date("YmdHis");
	$request["partner_id"]=sinapay_partner_id;
	$request["_input_charset"]=sinapay_input_charset;
	
	$request["sign"]="";
	$request["sign_type"]="RSA";
	$request["identity_id"]=to_guid_string($user_id);
	$request["identity_type"]="UID";
	$request["account_type"]="BASIC";

	ksort($request);//对签名参数据排序
		
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
		
		if ($splitdata["response_code"] == 'APPLY_SUCCESS') {
			
			$result = array();
			$ff=explode("^",$splitdata["bonus"]);
			$result['pErrCode'] = '0000';
			$result['pErrMsg'] = $splitdata["response_message"];
			$result['pIpsAcctNo'] = $user_id;
			$result['pBalance'] = $splitdata["available_balance"];
			$result['pLock'] = 0;
			$result['pNeedstl'] = 0;	
			$result['bonus'] = $ff[2];
		}else{
			$result = array();
			$result['pErrCode'] = 9999;
			$result['pErrMsg'] = '返回出错';
			$result['pIpsAcctNo'] = '';
			$result['pBalance'] = 0;
			$result['pLock'] = 0;
			$result['pNeedstl'] = 0;
		}
	}else{
		$result = array();
		$result['pErrCode'] = 9999;
		$result['pErrMsg'] = '返回出错';
		$result['pIpsAcctNo'] = '';
		$result['pBalance'] = 0;
		$result['pLock'] = 0;
		$result['pNeedstl'] = 0;
	}
	return $result;
}
?>