<?php
/**
 * 微博钱包 api 部分接口封装
 */
 
class Weibopay {
	/**
	 * getSignMsg 计算前面
	 * 
	 * @param array $pay_params
	 *        	计算前面数据
	 * @param string $sign_type
	 *        	签名类型
	 * @return string $signMsg 返回密文
	 */
	function getSignMsg($pay_params = array(), $sign_type) {
		$params_str = "";
		$signMsg = "";
		
		foreach ( $pay_params as $key => $val ) {
			if ($key != "sign" && $key != "sign_type" && $key != "sign_version" && isset ( $val ) && @$val != "") {
				$params_str .= $key . "=" . $val . "&";
			}
		}
		$params_str = substr ( $params_str, 0, - 1 );
		switch (@$sign_type) {
			case 'RSA' :
				self::write_log("RSA参与签名运算数据".$params_str);
				$priv_key = file_get_contents ( sinapay_rsa_sign_private_key );
				$pkeyid = openssl_pkey_get_private ( $priv_key );
				openssl_sign ( $params_str, $signMsg, $pkeyid, OPENSSL_ALGO_SHA1 );
				openssl_free_key ( $pkeyid );
				$signMsg = base64_encode ( $signMsg );
				self::write_log("RSA计算得出签名值：".$signMsg);
				break;
			case 'MD5' :
			default :
				$params_str = $params_str . @sinapay_md5_key;
				self::write_log("MD5参与签名运算数据".$params_str);
				$signMsg = strtolower ( md5 ( $params_str ) );
				self::write_log("MD5计算得出签名值：".$signMsg);
				break;
		}
		return $signMsg;
	}
	/**
	 * 通过公钥进行rsa加密
	 * 
	 * @param type $name
	 *        	Descriptiondata
	 *        	$data 需要进行rsa公钥加密的数据 必传
	 *        	$pu_key 加密所使用的公钥 必传
	 * @return 加密好的密文
	 */
	function Rsa_encrypt($data, $public_key) {
		$encrypted = "";
		$cert = file_get_contents ( $public_key );
		$pu_key = openssl_pkey_get_public ( $cert ); // 这个函数可用来判断公钥是否是可用的
		openssl_public_encrypt ( $data, $encrypted, $pu_key ); // 公钥加密
		$encrypted = base64_encode ( $encrypted ); // 进行编码
		return $encrypted;
	}
	/**
	 * 生成form表单使用的url
	 * @param unknown $pay_url
	 * @param unknown $pay_params
	 * @return string
	 */
	function createRequestUrl_Jump($pay_url,$pay_params=array())
	{
		$params_str = "";
		foreach($pay_params as $key=>$val){
			if(isset($val) && !is_null($val) && @$val!="")
			{
				$params_str .= "&".$key."=".urlencode(trim($val));
			}
		}
		if($params_str)
		{
			$params_str=substr($params_str,1);
		}
		return $pay_url."?".$params_str;
	}
		/**
	 * [createcurl_data 拼接模拟提交数据]
	 *
	 * @param array $pay_params
	 * @return string url格式字符串
	 */
	function createcurl_data($pay_params = array()) {
		$params_str = "";
		foreach ($pay_params as $key => $val ) {
			if (isset ( $val ) && ! is_null ( $val ) && @$val != "") {
				$params_str .= "&" . $key . "=" . urlencode(urlencode ( trim ( $val ) ) );
			}
		}
		if ($params_str) {
			$params_str = substr ($params_str, 1 );
		}
		return $params_str;
	}
	/**
	 * checkSignMsg 回调签名验证
	 * 
	 * @param array $pay_params        	
	 * @param string $sign_type        	
	 * @return boolean
	 */
	function checkSignMsg($pay_params = array(), $sign_type) {
		$params_str = "";
		$signMsg = "";
		$return = false;
		foreach ( $pay_params as $key => $val ) {
			if ($key != "sign" && $key != "sign_type" && $key != "sign_version" && ! is_null ( $val ) && @$val != "") {
				$params_str .= "&" . $key . "=" . $val;
			}
		}
		if ($params_str) {
			$params_str = substr ( $params_str, 1 );
		}
		switch (@$sign_type) {
			case 'RSA' :
				$cert = file_get_contents ( sinapay_rsa_sign_public_key );
				$pubkeyid = openssl_pkey_get_public ( $cert );
				$ok = openssl_verify ( $params_str, base64_decode ( $pay_params ['sign'] ), $cert, OPENSSL_ALGO_SHA1 );
				$return = $ok == 1 ? true : false;
				openssl_free_key ( $pubkeyid );
				break;
			case 'MD5' :
			default :
				$params_str = $params_str . sinapay_md5_key;
				$signMsg = strtolower ( md5 ( $params_str ) );
				$return = (@$signMsg == @strtolower ( $pay_params ['sign'] )) ? true : false;
				break;
		}
		return $return;
	}
	/**
	 * [curlPost 模拟表单提交]
	 * 
	 * @param string $url        	
	 * @param string $data        	
	 * @return string $data
	 */
	function curlPost($url, $data) {
		$ch = curl_init ();
		curl_setopt ( $ch, CURLOPT_URL, $url );
		curl_setopt ( $ch, CURLOPT_POST, 1 );
		curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
		curl_setopt ( $ch, CURLOPT_POSTFIELDS, $data );
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYHOST, 0 );
		curl_setopt ( $ch, CURLOPT_SSL_VERIFYPEER, 0 );
		$data = curl_exec ( $ch );
		curl_close ( $ch );
		return $data;
	}
		/**
	 * 日志记录
	 *
	 * @param unknown $msg
	 * @return boolean
	 */
	function write_log($msg) {
		if(sinapay_debug_status){
		$result=error_log( date ( "[YmdHis]" ) ."\t" . $msg . "\r\n", 3, '../'. date ( "Y-m-d" ) . '.log' );
			return $result;
		}else
		{
			return false;
		}

	}
}


function to_guid_string($mix)
{
    if(is_object($mix) && function_exists('spl_object_hash')) {
        return spl_object_hash($mix);
    }elseif(is_resource($mix)){
        $mix = get_resource_type($mix).strval($mix);
    }else{
        $mix = serialize($mix);
    }
    return md5($mix);
}
function to_guid_strings($mix)
{
    if(is_object($mix) && function_exists('spl_object_hash')) {
        return spl_object_hash($mix);
    }elseif(is_resource($mix)){
        $mix = get_resource_type($mix).strval($mix);
    }else{
        $mix = serialize($mix);
    }
    return md5($mix);
}
function NoRand($begin=100,$end=999,$limit=1){ 
	$rand_array=range($begin,$end); 
	shuffle($rand_array);//调用现成的数组随机排列函数
	$hhh=array_slice($rand_array,0,$limit); 
	return $hhh[0];//截取前$limit个 
} 

function build_order_no(){
	return date('Ymd').substr(implode(NULL, array_map('ord', str_split(substr(uniqid(), 7, 13), 1))), 0, 8);
}

?>