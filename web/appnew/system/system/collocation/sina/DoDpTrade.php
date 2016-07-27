<?php



	/**
	 * 充值
	 * @param int $user_id
	 * @param float $pTrdAmt 充值金额
	 * @param string $pTrdBnkCode 银行编号
	 * @param unknown_type $acquiring_url
	 * @return string
	 */
	function DoDpTrade($user_id,$pTrdAmt,$pTrdBnkCode,$acquiring_url){	
		require_once(APP_ROOT_PATH.'system/collocation/sina/BankListArray.php');
		
		
		$pWebUrl= SITE_DOMAIN.APP_ROOT."/index.php?ctl=collocation&act=response&class_name=Sina&class_act=DoDpTrade&from=".$_REQUEST['from'];//web方式返
		
		$pS2SUrl= SITE_DOMAIN.APP_ROOT."/sina/Notifyurl.php";//s2s方式返回		
		
		$weibopay = new Weibopay();
		
		if($pTrdAmt<=0){
			if($_REQUEST["from"]=='wap'){
				echo '<script>alert("请输入充值金额");history.go(-1);</script>';
				exit;
			}else{
				showErr("请输入充值金额");
			}
		} 
		
		
		if($pTrdBnkCode=='binding_pay'){
			$bind_bank_card = $GLOBALS['db']->getAll("select * from ".DB_PREFIX."sina_bind_bank_card where user_id = ".$user_id." and is_verified='Y' and is_callback=1");
			if(!$bind_bank_card){
				showErr("您当前没有绑定过任何银行卡，请先去绑定银行卡",0,SITE_DOMAIN.APP_ROOT."/index.php?ctl=collocation&act=BindBankCard&user_id=".$user_id);
			}else{
					$user = array();
					$user = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = ".$user_id);
					$data = array();
					$data['user_id'] = $user_id;
					$data['sinapay_partner_id'] = sinapay_partner_id;
					$data['identity_id'] = to_guid_string($user_id);
					$data['identity_type'] = "UID";
					$data['out_trade_no'] = build_order_no();
					$data['out_advance_no'] = build_order_no();
					$data['account_type'] = "BASIC";
					$data['amount'] = sprintf("%.2f",$pTrdAmt);
					$data['user_fee'] = 0; 
					$data['fee_taken_on'] = fee_type;
					$data['recharge_type'] =2;
					$data['payer_ip'] = $_SERVER["REMOTE_ADDR"];
					$data['request_time'] = date("YmdHis");
					$data['create_time'] = time();
					
					if (isset($_POST['submit'])){
						$data['pay_method'] = "".$pTrdBnkCode."^".$data['amount']."^".$_POST["card_id"]."";
						$GLOBALS['db']->autoExecute(DB_PREFIX."sina_recharge",$data,'INSERT');
						$id = $GLOBALS['db']->insert_id();
						$_POST["pay_method"]=$data['pay_method'];
						unset($_POST["submit"]); 
						unset($_POST["binding_pay"]); 
						ksort($_POST);
						$_POST["sign"]=$weibopay->getSignMsg($_POST,$_POST["sign_type"]);
						$GLOBALS['db']->autoExecute(DB_PREFIX."sina_recharge",$_POST,'UPDATE',"id=".$id."");
						$tijiao = $weibopay->createcurl_data($_POST); // 调用createcurl_data创建模拟表单需要的数据
					$result = $weibopay->curlPost($acquiring_url,$tijiao); // 使用模拟表单提交进行数据提交
					$result = urldecode ($result);
					$splitdata = array ();
					$splitdata = json_decode($result,true);
					$sign_type = $splitdata ['sign_type'];
					ksort($splitdata); 
					if ($weibopay->checkSignMsg ($splitdata,$sign_type)) {
						if ($splitdata["response_code"] == 'APPLY_SUCCESS') { // 成功
							//$splitdata["is_callback"]=1;
							$GLOBALS['db']->autoExecute(DB_PREFIX."sina_recharge",$splitdata,'UPDATE',"id=".$id."");
							showIpsInfo("短信已经发送到您的手机，请注意查收",SITE_DOMAIN.APP_ROOT."/index.php?ctl=collocation&act=DoDpTradeAdvance&id=".$data['out_advance_no']."&user_id=".$data['user_id']."&ticket=".$splitdata["ticket"]."");
							showIpsInfo("操作成功,稍后查看状态！",SITE_DOMAIN.APP_ROOT."/member.php?ctl=uc_ips&act=recharge");
						
						}else{
							showErr($splitdata["response_message"],0);
						}
					}else{
					
						showErr($splitdata["response_message"],0);
					
					}
						/*
						$html.="<form name='sinapay_checkout' id='sinapay_checkout' method='post'>";
			
						foreach ($_POST as $key => $val)
						{	
							$html.="<input type='hidden' name='".$key."' value='".$val."'/>";
							
						}
						
						$html.= "</form>";
						
					
						$html.= "<script type = 'text/javascript'>";
						$html.="document.sinapay_checkout.action = '".$acquiring_url."';";
						$html.="document.sinapay_checkout.submit();";
						$html.="</script>";
						$html.='</body></html>';
						echo $html;
						exit();
						*/
						
						
						
						
						
						
					}else{
						//print_r($bind_bank_card);
						foreach($bind_bank_card as $key=>$val){
							
							
							/*$Bank=$Bank.'<li  style=" width:200px; list-style:none;float:left;">
							<input name="card_id" id="'.$val["card_id"].'" type="radio" value="'.$val["card_id"].'" />
							
							<label class="icon-box limited-coupon" for="'.$val["card_id"].'">'.$BankList[$val["bank_code"]]."<br>".$val["bank_account_no"].'</label>
							
				 <br><a href="'.SITE_DOMAIN.APP_ROOT."/index.php?ctl=collocation&act=UnbindingBankCard&user_id=".$user_id.'&card_id='.$val["card_id"].'">解绑</a></li>';*/
						
						} 
						
						$GLOBALS['tmpl']->assign("bind_bank_card",$bind_bank_card);
						$user = array();
						$user = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = ".$user_id);
						$request=array();
						$request["version"]=sinapay_version;
						$request["request_time"]=$data['request_time'];
						$request["partner_id"]=$data['sinapay_partner_id'];
						$request["_input_charset"]=sinapay_input_charset;
						$request["sign"]="";
				
						$request["sign_type"]="RSA";
						$request["notify_url"]=$pS2SUrl;
						$request["service"]="create_hosting_deposit";
						$request["out_trade_no"]=$data['out_trade_no'];
						$request["identity_id"]=$data['identity_id'];
						$request["identity_type"]=$data['identity_type'];
						$request["account_type"]=$data['account_type'];
						$request["amount"]=$data['amount'];	
						$request["user_fee"]=$data['user_fee'];	
						$request["payer_ip"]=$data['payer_ip'];		
						$request['pay_method']=$data['pay_method'];
						$request['memo']='DoDpTrade';
						$GLOBALS['tmpl']->assign("request",$request);
						//print_r($user);
						$GLOBALS['tmpl']->assign("data",$user);
						$GLOBALS['tmpl']->assign("amount",$data['amount']);
						
						
						if($_REQUEST["from"]=='wap'){
							$GLOBALS['tmpl']->display("page/bangkachongzhiwap.html"); 
						}else{
							$GLOBALS['tmpl']->display("page/bangkachongzhi.html"); 
							
						}
					}
					  
					
					
			}
			
		}else{	

		$user = array();
		$user = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = ".$user_id);
			$data = array();
			$data['user_id'] = $user_id;
			$data['sinapay_partner_id'] = sinapay_partner_id;
			$data['identity_id'] = to_guid_string($user_id);
			$data['identity_type'] = "UID";
			$data['out_trade_no'] = build_order_no();
			$data['account_type'] = "BASIC";
			$data['amount'] = sprintf("%.2f",$pTrdAmt);
			$data['user_fee'] = 0; 
			
			$data['fee_taken_on'] = fee_type;
			$data['recharge_type'] =1;
			
			$data['payer_ip'] = $_SERVER["REMOTE_ADDR"];
			//$data['pay_method'] = "".$pTrdBnkCode."^".$data['amount']."^".$_POST["Bank"].",DEBIT,C";
			$data['request_time'] = date("YmdHis");
			$data['create_time'] = time();
		
			
			//file_put_contents("data.txt",$_POST['submit']);
		if (isset($_POST['submit'])){
			
			
			
			
			$data['pay_method'] = "online_bank^".$data['amount']."^".$_POST["Bank"].",DEBIT,".$_POST["kashuxing"]."";
			
			$GLOBALS['db']->autoExecute(DB_PREFIX."sina_recharge",$data,'INSERT');
			$id = $GLOBALS['db']->insert_id();
			$_POST["pay_method"]=$data['pay_method'];
		
			unset($_POST["kashuxing"]); 
			unset($_POST["Bank"]); 
			unset($_POST["submit"]); 
			ksort($_POST);
			
			$_POST["sign"]=UrlEncode($weibopay->getSignMsg($_POST,$_POST["sign_type"]));
			//print_r($_POST);
			$GLOBALS['db']->autoExecute(DB_PREFIX."sina_recharge",$_POST,'UPDATE',"id=".$id."");
			$html = '<html><head><meta http-equiv="content-type" content="text/html; charset=UTF-8" /></head><body>';
			$html.="<form name='sinapay_checkout' id='sinapay_checkout' method='post'>";
			
			foreach ($_POST as $key => $val)
			{	
				$html.="<input type='hidden' name='".$key."' value='".$val."'/>";
				
			} 
			
			$html.= "</form><script type = 'text/javascript'>";
			$html.="document.sinapay_checkout.action = '".$acquiring_url."';";
			$html.="document.sinapay_checkout.submit();";
			$html.="</script>";
			$html.='</body></html>';
			
			
			echo $html;
			exit();
		}
		
			$request=array();
			$request["version"]=sinapay_version;
			$request["request_time"]=$data['request_time'];
			$request["partner_id"]=$data['sinapay_partner_id'];
			$request["_input_charset"]=sinapay_input_charset;
			$request["sign_type"]="RSA";
			$request["notify_url"]=$pS2SUrl;
			$request["return_url"]=$pWebUrl;
			$request["service"]="create_hosting_deposit";
			$request["out_trade_no"]=$data['out_trade_no'];
			$request["identity_id"]=$data['identity_id'];
			$request["identity_type"]=$data['identity_type'];
			$request["account_type"]=$data['account_type'];
			$request["amount"]=$data['amount'];	
			$request["user_fee"]=$data['user_fee'];	
			$request["payer_ip"]=$data['payer_ip'];		
			$request['pay_method']=$data['pay_method'];
			$GLOBALS['tmpl']->assign("request",$request);
		//$html = '';
		
		    $user_info["uid"]=$user_id;
		    $GLOBALS['tmpl']->assign("data",$user_info);
		    
		    $GLOBALS['tmpl']->display("page/zaixianchongzhi.html"); 
		//return $html;
		}
	}
	
	
	function DoDpTradeCallBack($str3Req){
		$out_trade_no = $str3Req["outer_trade_no"];
		$where = " out_trade_no = '".$out_trade_no."'";
		$sql = "update ".DB_PREFIX."sina_recharge set is_callback = 1,create_time = ".TIME_UTC." where is_callback = 0 and ".$where;
		
		$GLOBALS['db']->query($sql);
		if ($GLOBALS['db']->affected_rows()){		
 			$str3Req["response_code"]="APPLY_SUCCESS";
			$str3Req["response_message"]="提交成功";
			$GLOBALS['db']->autoExecute(DB_PREFIX."sina_recharge",$str3Req,'UPDATE',$where);
				
					
		}else{
			echo $str3Req["error_code"];
		}
		echo 'SUCCESS';
	}

?>