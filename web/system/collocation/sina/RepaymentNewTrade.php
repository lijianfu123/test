<?php
	
	/**
	 * 还款
	 * @param deal $deal  标的数据
	 * @param array $repaylist  还款列表
	 * @param int $deal_repay_id  还款计划ID
	 * @param int $MerCode  商户ID
	 * @param string $cert_md5 
	 * @param string $post_url
	 * @return string
	 */
	
	function RepaymentNewTrade($deal, $repaylist, $deal_repay_id, $acquiring_url){
		
		$pS2SUrl= SITE_DOMAIN.APP_ROOT."/sina/Notifyurl.php";//s2s方式返回	
		$pWebUrl= SITE_DOMAIN.APP_ROOT."/index.php?ctl=collocation&act=response&class_name=Sina&class_act=RepaymentNewTrade&id=".$deal_id."&from=".$_REQUEST['from'];//web方式返
		
		$weibopay = new Weibopay();
		$data = array();
		$data['user_id'] = $deal['user_id'];//借款人
		$data['action_type'] = 4;//请求类型，投标为1，满标为2，流标为3，还标为4
		$data['sinapay_partner_id'] = sinapay_partner_id;
		$data['out_trade_no'] = build_order_no();
		$data['goods_id'] = $deal['id'];
		$data['goods_name'] = $deal['sub_name'];
		$data['brw_id'] = $deal['user_id'];//借款人
		$data['out_trade_code'] = "1002";
		$data['summary'] = "还款代收";
		$data['payer_id'] = to_guid_string((int)$data['user_id']);
		$data['payer_identity_type'] = "UID";
		$data['payer_ip'] = $_SERVER["REMOTE_ADDR"];
		$fee = 0;
		foreach($repaylist as $k=>$v){
			//平台收取：借款者 的管理费 + 管理逾期罚息
			$fee = $fee + $v['repay_manage_money'] + $v['repay_manage_impose_money'];
			//投资者获取的，费用 [宝付会自动扣除 $detail['fee'] 部分，所以最终获得的收入为：$v['month_repay_money'] + $v['impose_money'] - $v['manage_money'] - $v['manage_interest_money']
			$detail['amount'] =$detail['amount']+ round($v['month_repay_money'] + $v['impose_money'],2);
		
			//平台收取：投资者 的投资金额管理费 + 利息管理费
			//$detail['fee'] = $detail['fee']+round($v['manage_money']+$v['manage_interest_money'],2);
		
		}
		$jine=sprintf("%.2f",$detail['amount']+$fee);
		//$data['pay_method'] = "online_bank^".$data['load_amount']."^SINAPAY,DEBIT,C";
		$data['pay_method'] = "online_bank^".$jine."^SINAPAY,DEBIT,C";
		$data['deal_repay_id'] = $deal_repay_id;
		
		$data['repay_start_time'] = 0;// 开始还款日期
		$data['load_amount'] = 0;// 记录投标金额
		$data['bids_msg'] = '';//流标原因
		$data['deal_repay_id'] = $deal_repay_id;//还款计划ID
		$data['request_time'] = date("YmdHis");
		$data['create_time'] = time();
		$GLOBALS['db']->autoExecute(DB_PREFIX."sina_business",$data,'INSERT');
		$id = $GLOBALS['db']->insert_id();
		
		//收款
		$request=array();
		$request["version"]=sinapay_version;
		$request["request_time"]=$data['request_time'];
		$request["partner_id"]=$data['sinapay_partner_id'];
		$request["_input_charset"]=sinapay_input_charset;
		$request["sign"]="";
		$request["sign_type"]="RSA";
		$request["notify_url"]=$pS2SUrl;
		$request["return_url"]=$pWebUrl;
		$request['memo']='RepaymentNewTrade,'.intval(strim($_REQUEST['l_key']));
		$request["service"]="create_hosting_collect_trade";
		$request["out_trade_no"]=$data['out_trade_no'];
		$request["out_trade_code"]=$data['out_trade_code'];
		//$request["goods_id"]=$data['goods_id'];
		$request["payer_id"]=$data['payer_id'];
		$request["payer_identity_type"]=$data['payer_identity_type'];
		$request["payer_ip"]=$data['payer_ip'];
		$request["pay_method"]=$data['pay_method'];
		$request["summary"]=$data['summary'];
		ksort($request);
		$request["sign"]=urlencode($weibopay->getSignMsg($request,$request["sign_type"]));
		$GLOBALS['db']->autoExecute(DB_PREFIX."sina_business",$request,'UPDATE',"id=".$id."");
		//print_r($request);
		//exit;  
		//$tijiao = $weibopay->createcurl_data($request); // 调用createcurl_data创建模拟表单需要的数据
		
		

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
			
				$GLOBALS['db']->autoExecute(DB_PREFIX."sina_business",$splitdata,'UPDATE',"id=".$id."");
				showIpsInfo("操作成功",SITE_DOMAIN.APP_ROOT."/member.php?ctl=uc_deal&act=quick_refund&id=".$data['goods_id']);
				
	
			}else{
				showErr($splitdata["response_message"],0);
			}
		}else{
		
			showErr($splitdata["response_message"],0);
		
		}
		*/
	}
	
	//还款回调
	function RepaymentNewTradeCallBack($str3Req){
		$pS2SUrl= SITE_DOMAIN.APP_ROOT."/sina/Notifyurl.php";//s2s方式返回	
		$weibopay = new Weibopay();
		if ($str3Req['trade_status'] == 'TRADE_FINISHED'){
			$out_trade_no = $str3Req["outer_trade_no"];
			$where = " out_trade_no = '".$out_trade_no."'";
			$data['orig_outer_trade_no'] = build_order_no();
			$sql = "update ".DB_PREFIX."sina_business set orig_outer_trade_no='".$data['orig_outer_trade_no']."' where is_callback = 0 and ".$where;
			$GLOBALS['db']->query($sql);
			
			$request=array();
			$request["version"]=sinapay_version;
			$request["request_time"]=date("YmdHis");
			$request["partner_id"]=sinapay_partner_id;
			$request["_input_charset"]=sinapay_input_charset;
			$request["sign"]="";
			$request["sign_type"]="RSA";
			$request["notify_url"]=$pS2SUrl;
			$request['memo']='RepaymentNewTradeOk';
			$request["service"]="create_batch_hosting_pay_trade";
			$request["out_pay_no"]=$data['orig_outer_trade_no'];
			$request["out_trade_code"]="2002";
			require_once APP_ROOT_PATH."app/Lib/deal_func.php";
			$sina_business = array();
			$sina_business = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."sina_business where".$where);
			
			
			$deal = get_deal((int)$sina_business['goods_id']);// 	
			$result = get_deal_user_load_list($deal,0, intval($_REQUEST['l_key']) , -1,0,1, 1,"0,99999");	
			$repaylist=$result["item"];
			$fee = 0;
			foreach($repaylist as $k=>$v){
				//平台收取：借款者 的管理费 + 管理逾期罚息
				$fee = $fee + $v['repay_manage_money'] + $v['repay_manage_impose_money'];
					
				//==============================投资者获取的，费用===================================
				$detail = array();
				$detail['pid'] = $sina_business["id"];
				$detail['deal_load_repay_id'] = $v['id'];
				$detail['repay_manage_impose_money'] = $v['repay_manage_impose_money'];//平台收取 借款者 的管理费逾期罚息
				$detail['impose_money'] = $v['impose_money'];//投资者收取 借款者 的逾期罚息			
				$detail['repay_status'] = intval($v['status']) - 1;//还款状态
				$detail['true_repay_time'] = TIME_UTC;//还款时间
				
				
				//投资人会员编号
				if ($v['t_user_id']){
					//债权转让后,还款时，转给：承接者, 在债权转让后需要更新 fanwe_deal_load_repay.t_user_id 数据值
					$detail['user_id'] = $v['t_user_id'];
				}else{
					$detail['user_id'] = $v['user_id'];
				}
			
				//投资者获取的，费用 [宝付会自动扣除 $detail['fee'] 部分，所以最终获得的收入为：$v['month_repay_money'] + $v['impose_money'] - $v['manage_money'] - $v['manage_interest_money']
				$detail['amount'] = round($v['month_repay_money'] + $v['impose_money'],2);
			
				//平台收取：投资者 的投资金额管理费 + 利息管理费
				$detail['fee'] = round($v['manage_money']+$v['manage_interest_money'],2);
					
					
				$GLOBALS['db']->autoExecute(DB_PREFIX."sina_business_detail",$detail,'INSERT');
				$details[] = $detail;
				/*
				$sms_content = "尊敬的".app_conf("SHOP_TITLE")."用户".$GLOBALS['user_info']['user_name']."，您的借款“".$v['name']."”第".$_REQUEST['l_key']."期".$sms_ext_str."还款".$detail['amount']."元，感谢您的关注和支持。";
						
				$msg_data['dest'] = $GLOBALS['user_info']['mobile'];
				$msg_data['send_type'] = 0;
				$msg_data['title'] = "还款短信通知";
				$msg_data['content'] = $sms_content;
				$msg_data['send_time'] = 0;
				$msg_data['is_send'] = 0;
				$msg_data['create_time'] = time();
				$msg_data['user_id'] = $GLOBALS['user_info']['id'];
				$msg_data['is_html'] = 0;
				$GLOBALS['db']->autoExecute(DB_PREFIX."deal_msg_list",$msg_data); //插入	
			    */
			}
			$actions="";
			foreach($details as $k=>$v){
				$actions.="$".build_order_no().NoRand()."~".to_guid_string((int)$v['user_id'])."~UID~BASIC~".$v['amount']."~~还款代付~~~";
			}
			$request["trade_list"]=ltrim($actions, "$");
			$request["notify_method"]="batch_notify";
			ksort($request);
			
			$request["sign"]=urlencode($weibopay->getSignMsg($request,$request["sign_type"]));
			if (is_debug == 1){
				$acquiring_url="https://testgate.pay.sina.com.cn/mas/gateway.do";
			}else{
				$acquiring_url= "https://gate.pay.sina.com.cn/mas/gateway.do";
			}
			
			//$tijiao = $weibopay->createcurl_data($request); // 调用createcurl_data创建模拟表单需要的数据
			$result = post2($acquiring_url,$request); // 使用模拟表单提交进行数据提交
			$result = urldecode ($result);
			$splitdata = array ();
			$splitdata = json_decode($result,true);
			$sign_type = $splitdata ['sign_type'];
			ksort($splitdata); 
			//print_r($splitdata);
			
		
		}
		echo 'SUCCESS';
	}	
	//还款回调
	function RepaymentNewTradeOkCallBack($str3Req){
		
		if ($str3Req['batch_status'] == 'FINISHED'){
			$orig_outer_trade_no = $str3Req["outer_batch_no"];
			$where = " orig_outer_trade_no = '".$orig_outer_trade_no."'";
			$sql = "update ".DB_PREFIX."sina_business set is_callback = 1,create_time = ".TIME_UTC." where is_callback = 0 and ".$where;
			$GLOBALS['db']->query($sql);
			if ($GLOBALS['db']->affected_rows()){	
				$ddd["trade_status"]=$str3Req['batch_status'];
			 	$GLOBALS['db']->autoExecute(DB_PREFIX."sina_business",$ddd,'UPDATE',$where);
				$ipsdata = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."sina_business where ".$where);
				$deal_id = intval($ipsdata['goods_id']);
				$deal_repay_id = intval($ipsdata['deal_repay_id']);
				$sql = "select * from ".DB_PREFIX."sina_business_detail where deal_load_repay_id > 0 and pid = ".$ipsdata['id'];
					$list = $GLOBALS['db']->getAll($sql);
					foreach($list as $k=>$v){
						$load_repay = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal_load_repay where id = ".$v['deal_load_repay_id']);
							
						//repay_status,repay_manage_impose_money							
				
						$detail = array();
						$detail['repay_manage_impose_money'] = $v["repay_manage_impose_money"];//平台收取 借款者 的管理费逾期罚息
						$detail['impose_money'] = $v["impose_money"];//投资者收取 借款者 的逾期罚息
						$detail['status'] = $v["repay_status"];//还款状态
						$detail['true_repay_time'] = $v["true_repay_time"];//还款时间
						$detail['true_repay_date'] = to_date($v["true_repay_time"]);
							
							
						$detail['has_repay'] = 1;//0未收到还款，1已收到还款
						$detail['true_manage_money'] = $load_repay['manage_money'];
						$detail['true_manage_interest_money'] = $load_repay["manage_interest_money"];
						$detail['true_repay_manage_money'] = $load_repay["repay_manage_money"];
						$detail['true_repay_money'] =$load_repay["repay_money"];
						$detail['true_self_money'] = $load_repay['self_money'];
						$detail['true_interest_money'] =  $load_repay['interest_money'];
				
						$GLOBALS['db']->autoExecute(DB_PREFIX."deal_load_repay",$detail,'UPDATE'," has_repay = 0 and id = ".intval($v['deal_load_repay_id']));
						
						//普通会员邀请返利
						get_referrals($v['deal_load_repay_id']);	
	
						
					}
					//更新用户回款计划
					require_once APP_ROOT_PATH."app/Lib/deal.php";
					syn_deal_repay_status($deal_id,$deal_repay_id);
					

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
	
	function caozuohuankuan(){
		/*$pS2SUrl= SITE_DOMAIN.APP_ROOT."/sina/Notifyurl.php";//s2s方式返回	
		$weibopay = new Weibopay();
		$out_trade_no = "2015112752545555"; 
			$where = " out_trade_no = '".$out_trade_no."'";
			$data['orig_outer_trade_no'] = build_order_no();
			$sql = "update ".DB_PREFIX."sina_business set orig_outer_trade_no='".$data['orig_outer_trade_no']."' where is_callback = 0 and ".$where;
			$GLOBALS['db']->query($sql);
		$request=array();
			$request["version"]=sinapay_version;
			$request["request_time"]=date("YmdHis");
			$request["partner_id"]=sinapay_partner_id;
			$request["_input_charset"]=sinapay_input_charset;
			$request["sign"]="";
			$request["sign_type"]="RSA";
			$request["notify_url"]=$pS2SUrl;
			$request['memo']='RepaymentNewTradeOk';
			$request["service"]="create_batch_hosting_pay_trade";
			$request["out_pay_no"]=$data['orig_outer_trade_no'];
			$request["out_trade_code"]="2002";
			require_once APP_ROOT_PATH."app/Lib/deal_func.php";
			$sina_business = array();
			$sina_business = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."sina_business where".$where);
			
			
			$deal = get_deal(127);// 	
			$result = get_deal_user_load_list($deal,0, intval($_REQUEST['l_key']) , -1,0,1, 1,"0,99999");	
			$repaylist=$result["item"];
			$fee = 0;
			foreach($repaylist as $k=>$v){
				//平台收取：借款者 的管理费 + 管理逾期罚息
				$fee = $fee + $v['repay_manage_money'] + $v['repay_manage_impose_money'];
					
				//==============================投资者获取的，费用===================================
				$detail = array();
				$detail['pid'] = $sina_business["id"];
				$detail['deal_load_repay_id'] = $v['id'];
				$detail['repay_manage_impose_money'] = $v['repay_manage_impose_money'];//平台收取 借款者 的管理费逾期罚息
				$detail['impose_money'] = $v['impose_money'];//投资者收取 借款者 的逾期罚息			
				$detail['repay_status'] = intval($v['status']) - 1;//还款状态
				$detail['true_repay_time'] = TIME_UTC;//还款时间
				
				
				//投资人会员编号
				if ($v['t_user_id']){
					//债权转让后,还款时，转给：承接者, 在债权转让后需要更新 fanwe_deal_load_repay.t_user_id 数据值
					$detail['user_id'] = $v['t_user_id'];
				}else{
					$detail['user_id'] = $v['user_id'];
				}
			
				//投资者获取的，费用 [宝付会自动扣除 $detail['fee'] 部分，所以最终获得的收入为：$v['month_repay_money'] + $v['impose_money'] - $v['manage_money'] - $v['manage_interest_money']
				$detail['amount'] = round($v['month_repay_money'] + $v['impose_money'],2);
			
				//平台收取：投资者 的投资金额管理费 + 利息管理费
				$detail['fee'] = round($v['manage_money']+$v['manage_interest_money'],2);
					
					
				$GLOBALS['db']->autoExecute(DB_PREFIX."sina_business_detail",$detail,'INSERT');
				$details[] = $detail;
			    
			}
			$actions="";
			foreach($details as $k=>$v){
				$actions.="$".build_order_no().NoRand()."~".to_guid_string((int)$v['user_id'])."~UID~SAVING_POT~".$v['amount']."~~还款代付~~~";
			}
			$request["trade_list"]=ltrim($actions, "$");
			$request["notify_method"]="batch_notify";
			ksort($request);
			
			$request["sign"]=urlencode($weibopay->getSignMsg($request,$request["sign_type"]));
			if (is_debug == 1){
				$acquiring_url="https://testgate.pay.sina.com.cn/mas/gateway.do";
			}else{
				$acquiring_url= "https://gate.pay.sina.com.cn/mas/gateway.do";
			}
			//print_r($request);
			$result = post2($acquiring_url,$request); // 使用模拟表单提交进行数据提交
			$result = urldecode ($result);
			$splitdata = array ();
			$splitdata = json_decode($result,true);
			$sign_type = $splitdata ['sign_type'];
			ksort($splitdata); 
			echo 'SUCCESS'; */
	
	}
	
?>
