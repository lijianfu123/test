<?php

function GetBankList($member_url){ 
	$result = array ();
	$result ['pErrCode'] = '0000';
	$result ['pErrMsg'] = '';
	$BankList = array();
	//$BankList[] = array('name'=>'新浪资金托管','sub_name'=>'绑卡充值','id'=>'binding_pay');
	//if($_REQUEST["from"]!='wap'){
	$BankList[] = array('name'=>'新浪资金托管','sub_name'=>'收银台','id'=>'online_bank');
	//}
	$result ['BankList'] = $BankList;
	return $result;
} 
?>
