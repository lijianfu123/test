<?php






function DoLoans($deal_id,$repay_start_time,$acquiring_url){
	
	$conf = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."conf where name='AUTH_KEY'");
	$navs = require_once APP_ROOT_PATH."system/admnav_cfg.php";
	$adm_session = es_session::get(md5($conf["value"]));
	$adm_id = intval($adm_session['adm_id']);
	if(empty($adm_id)){
		showErr("^^^^^^^^^^^^^^^^^虚虚虚虚虚！！！",0);
	}
	
	$pS2SUrl= SITE_DOMAIN.APP_ROOT."/sina/Notifyurl.php";//s2s方式返回	
	$weibopay = new Weibopay();
	$deal = array();
	$deal = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where id = ".$deal_id);
	$pLendFee = round(floatval($deal['services_fee']) / 100 * $deal['borrow_amount'],2);//借款人手续费

	$data = array();
	$data['action_type'] = 2;//请求类型，投标为1，满标为2，流标为3，还标为4
	$data['sinapay_partner_id'] = sinapay_partner_id;
	$data['out_trade_no'] = build_order_no();
	$data['out_trade_code'] = "2001";
	$data["account_type"]="BASIC";
	$data['goods_id'] = $deal_id;
	$data['amount'] = $deal['borrow_amount'];
	$data['goods_name'] = $deal['sub_name'];
	$data['user_id'] = $deal['user_id'];//借款人	
	$data['brw_id'] = $deal['user_id'];//借款人	
	$data['payer_id'] = to_guid_string((int)$data['brw_id']);
	$data['payer_identity_type'] = "UID";
	$data['user_fee'] =sprintf("%.2f",$pLendFee); //手续费(涉及到满标、还款接口
	$data['repay_start_time'] = $repay_start_time;
	$data['load_amount'] = 0;// 记录投标金额
	$data['request_time'] = date("YmdHis");
	$data['summary'] = "满标放款";
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
	$request['memo']='DoLoans';
	$request["service"]="create_single_hosting_pay_trade";
	$request["out_trade_no"]=$data['out_trade_no'];
	$request["out_trade_code"]=$data['out_trade_code'];
	$request["payee_identity_id"]=$data['payer_id'];
	$request["payee_identity_type"]=$data['payer_identity_type'];
	$request["account_type"]=$data["account_type"];
	$request["amount"]=sprintf("%.2f",$data['amount']-$data['user_fee']);
	//$request["split_list"]=$request["payee_identity_id"]."^".$request["payee_identity_type"]."^".$request["account_type"]."^".$request["partner_id"]."^UID^SAVING_POT^".$data['user_fee']."^借款手续费";
    $request["summary"]=$data['summary'];
	//$request["goods_id"]=$data['goods_id'];
	$request["notify_method"]="batch_notify";
	//print_r($request);
	ksort($request);
	$request["sign"]=urlencode($weibopay->getSignMsg($request,$request["sign_type"]));
	$GLOBALS['db']->autoExecute(DB_PREFIX."sina_business",$request,'UPDATE',"id=".$id."");
	//$tijiao = $weibopay->createcurl_data($request); // 调用createcurl_data创建模拟表单需要的数据
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
					showIpsInfo("操作成功",SITE_DOMAIN.APP_ROOT."/m.php?m=Deal&a=index&");	
			
			 
			}else{
				showErr($splitdata["response_message"],0);
			}
		}else{
			
			showErr($splitdata["response_message"],0);
		
		}
		
}		
		
	function DoLoansCallBack($str3Req){
		$out_trade_no = $str3Req["outer_trade_no"];
		$where = " out_trade_no = '".$out_trade_no."'";
		$sql = "update ".DB_PREFIX."sina_business set is_callback = 1,create_time = ".TIME_UTC." where is_callback = 0 and ".$where;
		
		$GLOBALS['db']->query($sql);
		if ($GLOBALS['db']->affected_rows()){
				$GLOBALS['db']->autoExecute(DB_PREFIX."sina_business",$str3Req,'UPDATE',$where);
				$ipsdata = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."sina_business where ".$where);
				$deal_id = (int)$ipsdata['goods_id'];
								
				$deal_load = array();
				$deal_load['is_has_loans'] = 1;//1#转账成功
				$where = " deal_id = ".$deal_id;
				$GLOBALS['db']->autoExecute(DB_PREFIX."deal_load",$deal_load,'UPDATE',$where);
				
				$count = $GLOBALS['db']->getOne("select count(*) from ".DB_PREFIX."deal_load where is_has_loans = 0 and deal_id = ".$deal_id);
				if ($count == 0){
					//已经全部放款完成,生成：还款计划以及回款计划;
					$repay_start_time = intval($ipsdata['repay_start_time']);
					require_once(APP_ROOT_PATH."app/Lib/common.php");
					return do_loans($deal_id,$repay_start_time,1);
				}			
					
		}else{
			echo $str3Req["error_code"];
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