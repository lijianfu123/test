<?php



$tijiao = createcurl_data($_POST); // 调用createcurl_data创建模拟表单需要的数据



/* 

 $myfile = fopen("newfile.txt", "w") or die("Unable to open file!");

$txt = $tijiao;

fwrite($myfile, $txt);

fclose($myfile);



*/

$arr = explode(",",$_POST["memo"]);

if($arr[0]=='RepaymentNewTrade'){ 

$result = curlPost("http://www.haioujinfu.com/index.php?ctl=collocation&act=notify&class_name=Sina&class_act=".$arr[0]."&l_key=".$arr[1]."",$tijiao);

}else{

$result = curlPost("http://www.haioujinfu.com/index.php?ctl=collocation&act=notify&class_name=Sina&class_act=".$arr[0],$tijiao);

}

echo $result;



 $myfile = fopen($arr[0].".txt", "w") or die("Unable to open file!");

$txt = $result.$arr[1];

fwrite($myfile, $txt);

fclose($myfile);



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

?>