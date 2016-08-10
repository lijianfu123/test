<?php
	/**
	 * 投标
	 * @param int $user_id  用户ID
	 * @param int $deal_id  标的ID
	 * @param float $pAuthAmt 投资金额
	 * @return string
	 */
	function RegisterCreditor($user_id,$deal_id,$pAuthAmt,$acquiring_url){
		$pS2SUrl= SITE_DOMAIN.APP_ROOT."/sina/Notifyurl.php";//s2s方式返回	
		$pWebUrl= SITE_DOMAIN.APP_ROOT."/index.php?ctl=collocation&act=response&class_name=Sina&class_act=RegisterCreditor&id=".$deal_id."&from=".$_REQUEST['from'];//web方式返
		$weibopay = new Weibopay();
		$user = array();
		$user = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = ".$user_id);
		$deal = array();
		$deal = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where id = ".$deal_id);
		
		$data = array();
		$data['user_id'] = $user_id;
		$data['action_type'] = 1;//请求类型，投标为1，满标为2，流标为3，还标为4
		$data['sinapay_partner_id'] = sinapay_partner_id;
		$data['out_trade_no'] = build_order_no();
		$data['goods_id'] = $deal_id;
		$data['goods_name'] = $deal['sub_name'];
		$data['out_trade_code'] = "1001";
		$data['summary'] = "投资";
		$data['payer_id'] = to_guid_string($user_id);
		$data['payer_identity_type'] = "UID";
		$data['payer_ip'] = $_SERVER["REMOTE_ADDR"];
		$data['load_amount'] = sprintf("%.2f",$pAuthAmt);
		$data['pay_method'] = "online_bank^".$data['load_amount']."^SINAPAY,DEBIT,C";
		//$data['pay_method'] = "online_bank";
		$data['online_bank_bankid'] = "SINAPAY";
		$data['online_bank_card_type'] = "DEBIT";
		$data['online_bank_card_attribute'] = "C";
		
		$data['brw_id'] = intval($deal['user_id']);//借款人
		$data['user_fee'] = 0;
		$data['load_user_id'] = $user_id;// 记录：投标人
		$data['request_time'] = date("YmdHis");
		$data['create_time'] = time();
		$GLOBALS['db']->autoExecute(DB_PREFIX."sina_business",$data,'INSERT');
		$id = $GLOBALS['db']->insert_id();
		
		
		$request=array();
		$request["version"]=sinapay_version;
		$request["request_time"]=$data['request_time'];
		$request["partner_id"]=$data['sinapay_partner_id'];
		$request["_input_charset"]=sinapay_input_charset;
		$request["sign"]="";
		$request["sign_type"]="RSA";
		$request["notify_url"]=$pS2SUrl;
		$request["return_url"]=$pWebUrl;
		$request['memo']='RegisterCreditor';
		$request["service"]="create_hosting_collect_trade";
		$request["out_trade_no"]=$data['out_trade_no'];
		$request["out_trade_code"]=$data['out_trade_code'];
	//	$request["goods_id"]=$data['goods_id'];
		$request["payer_id"]=$data['payer_id'];
		$request["payer_identity_type"]=$data['payer_identity_type'];
		$request["payer_ip"]=$data['payer_ip'];
		$request["pay_method"]=$data['pay_method'];
		//$request['online_bank_bankid'] = $data['online_bank_bankid'];
		//$request['online_bank_card_type'] = $data['online_bank_card_type'];
		//$request['online_bank_card_attribute'] = $data['online_bank_card_attribute'];
		
		
		$request["summary"]=$data['summary'];
		ksort($request);
		$request["sign"]=urlencode($weibopay->getSignMsg($request,$request["sign_type"]));
		$GLOBALS['db']->autoExecute(DB_PREFIX."sina_business",$request,'UPDATE',"id=".$id."");
		
		
		
		$html = '<html><head><meta http-equiv="content-type" content="text/html; charset=UTF-8" /></head><body>';
		$html.="<form name='sinapay_checkout' id='sinapay_checkout' method='post'>";
			
		foreach ($request as $key => $val)
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
		//$tijiao = $weibopay->createcurl_data($request); // 调用createcurl_data创建模拟表单需要的数据
		/*
		$result = post2($acquiring_url,$request); // 使用模拟表单提交进行数据提交
		
		$result = urldecode ($result);
		
		$splitdata = array ();
		$splitdata = json_decode($result,true);
		$sign_type = $splitdata ['sign_type'];
		ksort($splitdata);
		//print_r($splitdata);
		
		
		if ($weibopay->checkSignMsg ($splitdata,$sign_type)) {
			if ($splitdata["response_code"] == 'APPLY_SUCCESS') { // 成功
					//$splitdata["is_callback"]=1;
					$GLOBALS['db']->autoExecute(DB_PREFIX."sina_business",$splitdata,'UPDATE',"id=".$id."");
					showIpsInfo("操作成功",url("index","deal",array("id"=>$deal_id)));	
			
			 
			}else{
				showErr($splitdata["response_message"],0);
			}
		}else{
		
			showErr($splitdata["response_message"],0);
		
		}
		*/
	}	
	function RegisterCreditorCallBack($str3Req){
		$out_trade_no = $str3Req["outer_trade_no"];
		$where = " out_trade_no = '".$out_trade_no."'";
		$sql = "update ".DB_PREFIX."sina_business set is_callback = 1,create_time = ".TIME_UTC." where is_callback = 0 and ".$where;
		
				if ($str3Req['trade_status'] == 'TRADE_FINISHED'){
					$GLOBALS['db']->query($sql);
					if ($GLOBALS['db']->affected_rows()){	
					
					  $GLOBALS['db']->autoExecute(DB_PREFIX."sina_business",$str3Req,'UPDATE',$where);
					
						$ipsdata = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."sina_business where ".$where);
						$user_id = intval($ipsdata['load_user_id']);
						$user = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = ".$user_id);
						
						$deal = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where id = ".(int)$ipsdata['cus_id']);
						
						$data['pMerBillNo'] = $out_trade_no;
						$data['pContractNo'] = $out_trade_no;
						$data['pP2PBillNo'] = $out_trade_no;
						$data['user_id'] = $user_id;
						$data['user_name'] = $user['user_name'];
						$data['deal_id'] = $ipsdata['goods_id'];
						$data['money'] = $ipsdata['load_amount'];
						
						$insertdata = return_deal_load_data($data,$user,$deal);
						
						$GLOBALS['db']->autoExecute(DB_PREFIX."deal_load",$insertdata,"INSERT");
						$load_id = $GLOBALS['db']->insert_id();
						if($load_id > 0){				
							require APP_ROOT_PATH.'app/Lib/deal_func.php';
							dobid2_ok($ipsdata['goods_id'], $user_id);	
							//return $ipsdata;			
						}
				
					} 
				}
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