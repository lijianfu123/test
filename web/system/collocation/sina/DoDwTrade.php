<?php

	/**

	 * 用户提现

	 * @param int $user_id

	 * @param int $user_type 0:普通用户fanwe_user.id;1:担保用户fanwe_deal_agency.id

	 * @param float $pTrdAmt 提现金额

	 * @return string

	 */

	function DoDwTrade($user_id,$pTrdAmt,$acquiring_url){
		require_once(APP_ROOT_PATH.'system/collocation/sina/BankListArray.php');
		$pS2SUrl= SITE_DOMAIN.APP_ROOT."/sina/Notifyurl.php";//s2s方式返回	
		$pWebUrl= SITE_DOMAIN.APP_ROOT."/index.php?ctl=collocation&act=response&class_name=Sina&class_act=DoDwTrade&from=".$_REQUEST['from'];//web方式返
		$weibopay = new Weibopay();
		$bind_bank_card = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."sina_bind_bank_card where user_id = ".$user_id." and is_verified='Y' and is_callback=1");
		//if($_GET["user_type"]!=0){
			if(!$bind_bank_card){
				showErr("您当前没有绑定过任何银行卡，请先去绑定银行卡",0,SITE_DOMAIN.APP_ROOT."/index.php?ctl=collocation&act=BindBankCard&user_id=".$user_id);
				exit;
			}

		//}
		$fee = getCarryFee($pTrdAmt,$user);
		$user = array();
		$user = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = ".$user_id);
		$data = array();
		$data['user_id'] = $user_id;
		$data['sinapay_partner_id'] = sinapay_partner_id;
		$data['identity_id'] = to_guid_string($user_id);
		$data['identity_type'] = "UID";
		$data['out_trade_no'] = build_order_no();
		$data['account_type'] = "SAVING_POT";
		$data['amount'] = sprintf("%.2f",$pTrdAmt);

		$data['user_fee'] = sprintf("%.2f",$fee); 

		$data['fee_taken_on'] = fee_type;

		$data['summary'] = "余额提现";

		$data['request_time'] = date("YmdHis");

		$data['create_time'] = time();


		if (isset($_POST['submit'])){
			$data['card_id'] = $_POST["card_id"];
			$GLOBALS['db']->autoExecute(DB_PREFIX."sina_fo_charge",$data,'INSERT');
		
			$id = $GLOBALS['db']->insert_id();
			unset($_POST["submit"]); 
			//$_POST['request_time'] = date("YmdHis");
			ksort($_POST);
			
			$_POST["sign"]=$weibopay->getSignMsg($_POST,$_POST["sign_type"]);
			//print_r($_POST);
			$GLOBALS['db']->autoExecute(DB_PREFIX."sina_fo_charge",array('amount'=>sprintf("%.2f",$pTrdAmt)),'UPDATE',"id=".$id."");
			/*$html = '<html><head><meta http-equiv="content-type" content="text/html; charset=UTF-8" /></head><body>';
			$html.="<form name='sinapay_checkout' id='sinapay_checkout' method='post'>";
				
			foreach ($_POST as $key => $val)
			{
			    $html.="<input type='hidden' name='".$key."' value='".$val."'/>";
			
			}
				
			$html.= "<input type=\"submit\" value=\"提交\" name=\"submit\" /></form><script type = 'text/javascript'>";
			$html.="document.sinapay_checkout.action = '".$acquiring_url."';";
			//$html.="document.sinapay_checkout.submit();";
			$html.="</script>";
			$html.='</body></html>';
				
				
			echo $html;
			exit();
			*/

			$tijiao = $weibopay->createcurl_data($_POST); // 调用createcurl_data创建模拟表单需要的数据
			$result = $weibopay->curlPost($acquiring_url,$tijiao); // 使用模拟表单提交进行数据提交
	
	
			$result = urldecode ($result);
			print_r($result);
			$splitdata = array ();
			$splitdata = json_decode($result,true);
			$sign_type = $splitdata ['sign_type'];
			ksort($splitdata); 
			
			if ($weibopay->checkSignMsg ($splitdata,$sign_type)) {
				if ($splitdata["response_code"] == 'APPLY_SUCCESS') { // 成功
					$splitdata["is_callback"]=1;
					$GLOBALS['db']->autoExecute(DB_PREFIX."sina_fo_charge",$splitdata,'UPDATE',"id=".$id."");
					showIpsInfo("操作成功,稍后查看状态！",SITE_DOMAIN.APP_ROOT."/member.php?ctl=uc_ips&act=transfer");

				
				}else{
					//showErr($splitdata["response_message"],0);
				}

			}else{
				//showErr($splitdata["response_message"],0);
			}
		}else{
			if($_GET["user_type"]==0){
				$bind_bank_card=array();
				$requests=array();
				
				$requests["version"]=sinapay_version;
				$requests["request_time"]=$data['request_time'];
				$requests["partner_id"]=$data['sinapay_partner_id'];
				$requests["_input_charset"]=sinapay_input_charset;
				$requests["sign"]="";
				$requests["sign_type"]="RSA";
				$requests["service"]="query_bank_card";
				$requests["identity_id"]=$data['identity_id'];
				$requests["identity_type"]=$data['identity_type'];


				ksort($requests);
				$requests["sign"]=$weibopay->getSignMsg($requests,$requests["sign_type"]);
				$tijiaos = $weibopay->createcurl_data($requests); // 调用createcurl_data创建模拟表单需要的数据
				$results = $weibopay->curlPost('https://testgate.pay.sina.com.cn/mgs/gateway.do',$tijiaos); // 使用模拟表单提交进行数据提交
				$results = urldecode ($results);
				$splitdatas = array ();
				$splitdatas = json_decode($results,true);
				$sign_types = $splitdatas ['sign_type'];
				ksort($splitdatas); 
				$arr=array();
				$arr = explode("^",$splitdatas["card_list"]);
				//print_r($splitdatas["card_list"]);
				$bind_bank_card[0]["card_id"]=$arr[0];
				$bind_bank_card[0]["bank_code"]=$arr[1];
				$bind_bank_card[0]["bank_account_no"]=$arr[2];
				$bind_bank_card[0]["account_name"]=$arr[3];
			} 
			$GLOBALS['tmpl']->assign("bind_bank_card",$bind_bank_card);
			//print_r($arr);
			$request=array();
			$request["version"]=sinapay_version;
			$request["request_time"]=$data['request_time'];
			$request["partner_id"]=$data['sinapay_partner_id'];
			$request["_input_charset"]=sinapay_input_charset;
			$request["sign"]="";
			$request["sign_type"]="RSA";
			$request["notify_url"]=$pS2SUrl;
			$request["return_url"]=$pWebUrl;
			$request["service"]="create_hosting_withdraw"; 
			//$request["withdraw_mode"]="CASHDESK";
			$request["out_trade_no"]=$data['out_trade_no'];
			$request["identity_id"]=$data['identity_id'];
			$request["identity_type"]=$data['identity_type'];
			$request["account_type"]=$data['account_type'];
			$request["amount"]=$data['amount'];
			$request["user_fee"]=$data['user_fee'];
			$request["summary"]=$data['summary'];
			$request['memo']='DoDwTrade';
			
			
			$GLOBALS['tmpl']->assign("request",$request);
			
			//if($_REQUEST["from"]=='wap'){
			//	$GLOBALS['tmpl']->display("page/tixianwap.html"); 
			//}else{
				$GLOBALS['tmpl']->display("page/tixian.html"); 
 
			//} 
		}



	}

	function DoDwTradeCallBack($str3Req){

		$out_trade_no = $str3Req["outer_trade_no"];

		$where = " out_trade_no = '".$out_trade_no."'";

		$GLOBALS['db']->autoExecute(DB_PREFIX."sina_fo_charge",$str3Req,'UPDATE',$where);

	

		echo 'SUCCESS';

	}

?>