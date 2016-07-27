<?php

	/**
	 * 流标
	 * @param unknown_type $deal_id	 
	 * @param unknown_type $bids_msg 流标原因
	 * @return string
	 */
	function DoBids($deal_id,$bids_msg,$acquiring_url){
		$pS2SUrl= SITE_DOMAIN.APP_ROOT."/sina/Notifyurl.php";//s2s方式返回	
		$weibopay = new Weibopay();

		$deal = array();
		$deal = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."deal where id = ".$deal_id);
		
		
		
		$r_load_list = $GLOBALS['db']->getAll("SELECT id,user_id,money FROM ".DB_PREFIX."deal_load WHERE is_repay=0 AND deal_id=$deal_id");
		foreach($r_load_list as $k=>$v){	
			$detail = array();
			$detail['pid'] = $id;
			$detail['deal_load_id'] = $v['id'];
			$detail['user_id'] = $v['user_id'];
			$detail['amount'] = $v['money'];
			$GLOBALS['db']->autoExecute(DB_PREFIX."sina_business_detail",$detail,'INSERT');
		}		
		
		$business = $GLOBALS['db']->getAll("SELECT * FROM ".DB_PREFIX."sina_business WHERE is_callback=1 AND goods_id=$deal_id");
		foreach($business as $key=>$val){	
			$data = array();
			$data['action_type'] = 3;//请求类型，投标为1，满标为2，流标为3，还标为4
			$data['sinapay_partner_id'] = sinapay_partner_id;
			$data['out_trade_no'] = build_order_no();
			$data["orig_outer_trade_no"]=$val['out_trade_no'];
			$data['goods_id'] = $deal_id;
			$data['goods_name'] = $deal['sub_name'];
			$data['bids_msg'] =$bids_msg;
			$data['summary'] = "流标";
			$data['fee'] = 0; //手续费(涉及到满标、还款接口)
			$data['load_user_id'] = 0;// 记录：投标人
			$data['load_amount'] = 0;// 记录：投标金额
			$data['brw_id'] = intval($deal['user_id']);//借款人
			$data['load_user_id'] = 0;// 记录：投标人
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
			$request['memo']='DoBids';
			$request["service"]="create_hosting_refund";
			$request["out_trade_no"]=$data['out_trade_no'];
			$request["orig_outer_trade_no"]=$data["orig_outer_trade_no"];
			$request["refund_amount"]=$val['load_amount'];
			$request["summary"]=$data['summary'];
			ksort($request);
			$request["sign"]=$weibopay->getSignMsg($request,$request["sign_type"]);
			$GLOBALS['db']->autoExecute(DB_PREFIX."sina_business",$request,'UPDATE',"id=".$id."");
			$tijiao = $weibopay->createcurl_data($request); // 调用createcurl_data创建模拟表单需要的数据
			$result = $weibopay->curlPost($acquiring_url,$tijiao); // 使用模拟表单提交进行数据提交
			$result = urldecode ($result);
			$splitdata = array ();
			$splitdata = json_decode($result,true);
			$sign_type = $splitdata ['sign_type'];
			ksort($splitdata); 
			if ($weibopay->checkSignMsg ($splitdata,$sign_type)) {
			if ($splitdata["response_code"] == 'APPLY_SUCCESS') { // 成功
					$GLOBALS['db']->autoExecute(DB_PREFIX."sina_business",$splitdata,'UPDATE',"id=".$id."");
				}else{
					$GLOBALS['db']->autoExecute(DB_PREFIX."sina_business",$splitdata,'UPDATE',"id=".$id."");
				}
			}else{
			
				$GLOBALS['db']->autoExecute(DB_PREFIX."sina_business",$splitdata,'UPDATE',"id=".$id."");
			
			}
			
		}
		showIpsInfo("操作成功",SITE_DOMAIN.APP_ROOT."/m.php?m=Deal&a=index&");
	}
	function DoBidsCallBack($str3Req){
	
		$out_trade_no = $str3Req["outer_trade_no"];
		$where = " out_trade_no = '".$out_trade_no."'";
		$sql = "update ".DB_PREFIX."sina_business set is_callback = 1,create_time = ".TIME_UTC." where is_callback = 0 and ".$where;
		if ($str3Req['refund_status'] == 'SUCCESS'){
			$GLOBALS['db']->query($sql);
			if ($GLOBALS['db']->affected_rows()){		
	 
				$GLOBALS['db']->autoExecute(DB_PREFIX."sina_business",$str3Req,'UPDATE',$where);
				
				$ipsdata = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."sina_business where ".$where);
				
				
				$deal_id = (int)$ipsdata['goods_id'];
								
				
				require_once APP_ROOT_PATH.'app/Lib/common.php';
				$result = do_received($deal_id,1,$ipsdata['bids_msg']);
				
				//本地解冻:借款保证金,担保保证金0
				$GLOBALS['db']->query("update ".DB_PREFIX."deal set ips_over = 1 ,un_real_freezen_amt = real_freezen_amt,un_guarantor_real_freezen_amt = guarantor_real_freezen_amt where id = ".$deal_id);
						
			}else{
				echo $str3Req["error_code"];
			}
			echo 'SUCCESS';
		}
		
	
	}
?>