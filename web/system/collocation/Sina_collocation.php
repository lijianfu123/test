<?php



$payment_lang = array(

		'name'	=>	'新浪资金托管',

		'sinapay_partner_id'	=>	'商户号',

		'sinapay_md5_key'		=>	'商户KEY秘钥',

		'fee_type'		=>	'谁付充值手续费',

		'fee_type_1'		=>	'平台支付',

		'fee_type_2'		=>	'用户支付',

		'is_debug'		=>	'测试帐户',

		'is_debug_0'		=>	'否',

		'is_debug_1'		=>	'是',		

);





$config = array(

		'sinapay_partner_id'	=>	array(

				'INPUT_TYPE'	=>	'0'

		),

		'sinapay_md5_key'	=>	array(

				'INPUT_TYPE'	=>	'0'

		),

		'fee_type'	=>	array(

				'INPUT_TYPE'	=>	'1',

				'VALUES'	=>	array(1,2),

		), 

		'is_debug'	=>	array(

				'INPUT_TYPE'	=>	'1',

				'VALUES'	=>	array(0,1),

		),		

);



/* 模块的基本信息 */

if (isset($read_modules) && $read_modules == TRUE)

{

	$module['class_name']    = 'Sina';



	/* 名称 */

	$module['name']    = $payment_lang['name'];



	/* 配送 */

	$module['config'] = $config;



	$module['lang'] = $payment_lang;

	 

	/* 插件作者的官方网站 */

	$module['reg_url'] = 'http://www.xxx.com';



	return $module;

}

ini_set("display_errors", "On");

error_reporting(E_ALL || ~E_NOTICE);

 

require_once(APP_ROOT_PATH.'system/collocation/sina/conf.php');

require_once(APP_ROOT_PATH.'system/collocation/sina/weibopay.class.php');

require_once(APP_ROOT_PATH.'system/libs/collocation.php');

class Sina_collocation implements collocation {

	

		private $payment_lang = array(

			'GO_TO_PAY'	=>	'前往%s支付',

		);

		

		/*会员网关：

		生产环境：https://gate.pay.sina.com.cn/mgs/gateway.do

		联调环境：https://testgate.pay.sina.com.cn/mgs/gateway.do

		收单网关：

		生产环境：错误！超链接引用无效。

		联调环境：https://testgate.pay.sina.com.cn/mas/gateway.do

		*/

		//会员网关

		private $member_url="";

		//收单网关

		private $acquiring_url= "";

		

		function __construct(){

			

			if (is_debug == 1){

				$this->member_url="https://testgate.pay.sina.com.cn/mgs/gateway.do";

				$this->acquiring_url="https://testgate.pay.sina.com.cn/mas/gateway.do";

			}else{

				$this->member_url="https://gate.pay.sina.com.cn/mgs/gateway.do";

				$this->acquiring_url= "https://gate.pay.sina.com.cn/mas/gateway.do";

			}

		} 



		/**

		 * 创建新帐户

		 * @param int $user_id

		 * @param int $user_type 0:普通用户fanwe_user.id;1:担保用户



fanwe_deal_agency.id

		 * @param unknown_type $member_url

		 * @return string

		 */

		function CreateNewAcct($user_id,$user_type){

			

			require_once(APP_ROOT_PATH.'system/collocation/sina/CreateNewAcct.php');

			return CreateNewAcct($user_id,$user_type,$this->member_url);

			

		}

		

		/**

		 * 设置实名信息

		 * @param int $user_id

		 * @param unknown_type $member_url

		 * @return string

		 */

		function SetRealName($user_id){

			

			require_once(APP_ROOT_PATH.'system/collocation/sina/SetRealName.php');

			return SetRealName($user_id,$this->member_url);

			

		}

		

		/**

		 * 绑定认证信息

		 * @param int $user_id

		 * @param unknown_type $verify_type MOBILE手机 EMAIL邮箱

		 * @param unknown_type $member_url

		 * @return string

		 */

		function BindingVerify($user_id,$verify_type){

			

			require_once(APP_ROOT_PATH.'system/collocation/sina/BindingVerify.php');

			return BindingVerify($user_id,$verify_type,$this->member_url);

			

		}

		

		/**

		 * 绑定认证信息

		 * @param int $user_id

		 * @param unknown_type $verify_type MOBILE手机 EMAIL邮箱

		 * @param unknown_type $member_url

		 * @return string

		 */

		function AuditMemberInfos($user_id){

			

			require_once(APP_ROOT_PATH.'system/collocation/sina/AuditMemberInfos.php');

			return AuditMemberInfos($user_id,$this->member_url);

			

		}

		

		function QueryAuditResult($user_id){

			

			require_once(APP_ROOT_PATH.'system/collocation/sina/QueryAuditResult.php');

			return QueryAuditResult($user_id,$this->member_url);

			

		}

		/**

		 * 标的登记 及 流标

		 * @param int $deal_id

		 * @param int $pOperationType 标的操作类型，1：新增，2：结束 “新增”代表新增标的，“结束”代表标的正常还清、丌 需要再还款戒者标的流标等情况。标的“结束”后，投资 人投标冻结金额、担保方保证金、借款人保证金均自劢解 冻

		 * @param int $status; 0:新增; 1:标的正常结束; 2:流标结束

		 * @param string $status_msg 主要是status_msg=2时记录的，流标原因

		 */

		function RegisterSubject($deal_id,$pOperationType,$status, $status_msg){

			if ($pOperationType == 1){

				$data = array();		

				$data['ips_bill_no'] = $deal_id;

				$data['mer_bill_no'] = $deal_id;

				$GLOBALS['db']->autoExecute(DB_PREFIX."deal",$data,'UPDATE',"id=".$deal_id);

				

				showIpsInfo('同步成功',SITE_DOMAIN.APP_ROOT);

			}else if ($pOperationType == 2 && $status == 2){

				require_once(APP_ROOT_PATH.'system/collocation/sina/DoBids.php');

				return DoBids($deal_id,$status_msg,$this->acquiring_url);

				

			}else if ($pOperationType == 2 && $status == 1){

				//本地解冻:借款保证金,担保保证金0

				$sql = "update ".DB_PREFIX."deal set ips_over = 1 ,un_real_freezen_amt = real_freezen_amt,un_guarantor_real_freezen_amt = guarantor_real_freezen_amt where id = ".$deal_id;

				$GLOBALS['db']->query($sql);	

				//http://p2p.fanwe.net/m.php?m=Deal&a=index&

				$url = SITE_DOMAIN.APP_ROOT.'/m.php?m=Deal&a=index';

				showSuccess('操作成功',0,$url);

			}

		}	

		

		

		/**

		 * 投标

		 * @param int $user_id  用户ID

		 * @param int $deal_id  标的ID

		 * @param float $pAuthAmt 投资金额

		 * @return string

		 */

		function RegisterCreditor($user_id,$deal_id,$pAuthAmt){

			

			require_once(APP_ROOT_PATH.'system/collocation/sina/RegisterCreditor.php');

			

			return RegisterCreditor($user_id,$deal_id,$pAuthAmt,$this->acquiring_url);

			

			

		}	

	

		/**

		 * 登记债权转让

		 * @param int $transfer_id  转让id

		 * @param int $t_user_id  受让用户ID



		 * @param string $post_url

		 * @return string

		 */

		function RegisterCretansfer($transfer_id,$t_user_id){

			

		}

		

		/**

		 * 账户余额查询(WS) 

		 * @param int $user_id

		 * @param int $user_type 0:普通用户fanwe_user.id;1:担保用户fanwe_deal_agency.id

		 * @param unknown_type $member_url

		 * @return

		 * 			pMerCode 6 “平台”账号 否 由IPS颁发的商户号

					pErrCode 4 返回状态 否 0000成功； 9999失败；

					pErrMsg 100 返回信息 否 状态0000：成功 除此乊外：反馈实际原因

					pIpsAcctNo 30 IPS账户号 否 查询时提交

					pBalance 10 可用余额 否 带正负符号，带小数点，最多保留两位小数

					pLock 10 冻结余额 否 带正负符号，带小数点，最多保留两位小数

					pNeedstl 10 未结算余额 否 带正负符号，带小数点，最多保留两位小数

		 */

		function QueryForAccBalance($user_id,$user_type){

			

			require_once(APP_ROOT_PATH.'system/collocation/sina/QueryForAccBalance.php');

			//echo APP_ROOT_PATH.'system/collocation/Sina/QueryForAccBalance.php';

			return QueryForAccBalance($user_id,$user_type,$this->member_url);			

		}

		

		

		/** 

		 * 解冻保证金

		 * @param int $deal_id 标的号

		 * @param int $pUnfreezenType 解冻类型 否 1#解冻借款方；2#解冻担保方

		 * @param float $money 解冻金额;默认为0时，则解冻所有未解冻的金额

		 * @return string

		 */

		function GuaranteeUnfreeze($deal_id,$pUnfreezenType, $money){

			

		}

		

		

		function ShowMemberInfosSina($user_id){

			

			require_once(APP_ROOT_PATH.'system/collocation/Sina/ShowMemberInfosSina.php');

			return ShowMemberInfosSina($user_id,$this->member_url);	

		}	

		function QueryAccountDetail($user_id){

			

			require_once(APP_ROOT_PATH.'system/collocation/Sina/QueryAccountDetail.php');

			return QueryAccountDetail($user_id,$this->member_url);	

		}	

		

		/**

		 * 充值

		 * @param int $user_id

		 * @param int $user_type 0:普通用户fanwe_user.id;1:担保用户fanwe_deal_agency.id

		 * @param float $pTrdAmt 充值金额

		 * @param string $pTrdBnkCode 银行编号

		 * @param unknown_type $MerCode

		 * @param unknown_type $cert_md5

		 * @param unknown_type $post_url

		 * @return string

		 */

		function DoDpTrade($user_id,$user_type,$pTrdAmt,$pTrdBnkCode){	

			require_once(APP_ROOT_PATH.'system/collocation/sina/DoDpTrade.php');

			return DoDpTrade($user_id,$pTrdAmt,$pTrdBnkCode,$this->acquiring_url);

		}

		

		function DoDpTradeAdvance($user_id,$ticket,$id){	

			require_once(APP_ROOT_PATH.'system/collocation/sina/DoDpTradeAdvance.php');

			return DoDpTradeAdvance($user_id,$ticket,$id,$this->acquiring_url);

		}

		

		

		/**

		 * 绑定银行卡

		 * @param unknown_type $user_id

		 */

		function BindBankCard($user_id){

			require_once(APP_ROOT_PATH.'system/collocation/sina/BindBankCard.php');

			return BindBankCard($user_id,$this->member_url);

		}

	/***
	 * 查询绑定银行卡信息
	 */
	function get_bank_info($user_id){

		require_once(APP_ROOT_PATH.'system/collocation/sina/get_bank_info.php');

		return get_bank_info($user_id,$this->member_url);

	}

		/**

		 * 绑定银行卡推进

		 * @param unknown_type $user_id

		 * @param unknown_type $ticket

		 */

		function BindBankCardAdvance($user_id,$ticket,$id){

			require_once(APP_ROOT_PATH.'system/collocation/sina/BindBankCardAdvance.php');

			return BindBankCardAdvance($user_id,$ticket,$id,$this->member_url);

		}	

		

		/**

		 * 解绑银行卡

		 * @param unknown_type $user_id

		 * @param unknown_type $card_id

		 */

		function UnbindingBankCard($user_id,$card_id){

			require_once(APP_ROOT_PATH.'system/collocation/sina/UnbindingBankCard.php');

			return UnbindingBankCard($user_id,$card_id,$this->member_url);

		}

		

		

		

		/**

		 * 用户提现

		 * @param int $user_id

		 * @param int $user_type 0:普通用户fanwe_user.id;1:担保用户fanwe_deal_agency.id

		 * @param float $pTrdAmt 提现金额

		 * @return string

		 */

		function DoDwTrade($user_id,$user_type,$pTrdAmt){

			require_once(APP_ROOT_PATH.'system/collocation/sina/DoDwTrade.php');

			return DoDwTrade($user_id,$pTrdAmt,$this->acquiring_url);

		}

		

		

		

		function SmtFundAgentBuy($user_id){

			require_once(APP_ROOT_PATH.'system/collocation/sina/SmtFundAgentBuy.php');

			return SmtFundAgentBuy($user_id,$this->member_url); 

		}

		/**

		 * 商户端获取银行列表查询(WS) 

		 * @param int $MerCode

		 * @param unknown_type $cert_md5

		 * @param unknown_type $post_url

		 * @return  

		 * 		  pMerCode 6 “平台”账号 否 由IPS颁发的商户号

		 * 		  pErrCode 4 返回状态 否 0000成功； 9999失败；

		 * 		  pErrMsg 100 返回信息 否 状态0000：成功 除此乊外：反馈实际原因 

		 * 		  pBankList 银行名称|银行卡别名|银行卡编号#银行名称|银行卡别名|银行卡编号

		 * 		  BankList[] = array('name'=>银行名称,'sub_name'=>银行卡别名,'id'=>银行卡编号);

		 */

		function GetBankList(){ 

			require_once(APP_ROOT_PATH.'system/collocation/sina/GetBankList.php');		

		    return GetBankList($this->member_url);

			

		}

		

		/**

		 * 登记担保方

		 * @param int $deal_id

		 * @param unknown_type $MerCode

		 * @param unknown_type $cert_md5

		 * @param unknown_type $post_url

		 * @return string

		 */

		function RegisterGuarantor($deal_id){

				

		}	

		

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

		function RepaymentNewTrade($deal, $repaylist, $deal_repay_id){

			require_once(APP_ROOT_PATH.'system/collocation/sina/RepaymentNewTrade.php');

			return RepaymentNewTrade($deal,$repaylist,$deal_repay_id, $this->acquiring_url);

		}

		

		/**

		 * 转帐

		 * @param int $pTransferType;//转账类型  否  转账类型  1：投资（报文提交关系，转出方：转入方=N：1），  2：代偿（报文提交关系，转出方：转入方=1：N），  3：代偿还款（报文提交关系，转出方：转入方=1：1），  4：债权转让（报文提交关系，转出方：转入方=1：1），  5：结算担保收益（报文提交关系，转出方：转入方=1： 1）

		 * @param int $deal_id  标的id

		 * @param string $ref_data 逗号分割的,代偿，代偿还款列表; 债权转让: id; 结算担保收益:金额，如果为0,则取fanwe_deal.guarantor_pro_fit_amt ;

		 * @return string

		 */

		function Transfer($pTransferType, $deal_id, $ref_data){

			if ($pTransferType == 1){

				//满标放款

				require_once(APP_ROOT_PATH.'system/collocation/sina/DoLoans.php');		

				return DoLoans($deal_id,$ref_data, $this->acquiring_url);

			}

		}

		

		//(显式回调)

		function response($request,$class_act){

			if($class_act=='DoDpTrade'){

				$pErrMsg = '充值完成';

				if ($request['from'] == 'app'){

					showIpsInfo($pErrMsg);

				}else if ($request['from'] == 'wap'){

					showIpsInfo($pErrMsg,SITE_DOMAIN.APP_ROOT.'/wap/index.php?ctl=uc_center');

				}else{

					showIpsInfo($pErrMsg,SITE_DOMAIN.APP_ROOT."/member.php?ctl=uc_center");

				}				

			}elseif($class_act=='RegisterCreditor'){

				$pErrMsg = '支付成功';

				if ($request['from'] == 'app'){

					showIpsInfo($pErrMsg);

				}else if ($request['from'] == 'wap'){

					showIpsInfo($pErrMsg,SITE_DOMAIN.APP_ROOT.'/wap/index.php?ctl=uc_center');

				}else{

					showIpsInfo($pErrMsg,SITE_DOMAIN.APP_ROOT."/index.php?ctl=deal&id=".$request['id']);

				}				

			}elseif($class_act=='DoDwTrade'){
				$pErrMsg = '提现成功';
				if ($request['from'] == 'app'){
					showIpsInfo($pErrMsg);
				}else if ($request['from'] == 'wap'){
					showIpsInfo($pErrMsg,SITE_DOMAIN.APP_ROOT.'/wap/index.php?ctl=uc_center');
				}else{
					showIpsInfo($pErrMsg,SITE_DOMAIN.APP_ROOT."/member.php?ctl=uc_ips&act=transfer");
				}				

			}elseif($class_act=='RepaymentNewTrade'){
				$pErrMsg = '还款成功';
				if ($request['from'] == 'app'){
					showIpsInfo($pErrMsg);
				}else if ($request['from'] == 'wap'){
					showIpsInfo($pErrMsg,SITE_DOMAIN.APP_ROOT.'/wap/index.php?ctl=uc_center');
				}else{
					showIpsInfo($pErrMsg,SITE_DOMAIN.APP_ROOT."/member.php?ctl=uc_deal&act=refund");
				}				

			}

			

		

		}

		//(后台回调)

		function notify($request,$class_act){

			 

			if($class_act=='DoDpTrade'){

				require_once(APP_ROOT_PATH.'system/collocation/sina/DoDpTrade.php');				

				DoDpTradeCallBack($_POST);				

			}else if($class_act=='DoDwTrade'){

				require_once(APP_ROOT_PATH.'system/collocation/sina/DoDwTrade.php');				

				DoDwTradeCallBack($_POST);				

			}else if($class_act=='RegisterCreditor'){

				require_once(APP_ROOT_PATH.'system/collocation/sina/RegisterCreditor.php');				

				RegisterCreditorCallBack($_POST);				

			}else if ($class_act == 'DoBids'){

				//流标

				require_once(APP_ROOT_PATH.'system/collocation/sina/DoBids.php');

				DoBidsCallBack($_POST);

			}else if ($class_act == 'DoLoans'){

				//满标放款

				require_once(APP_ROOT_PATH.'system/collocation/sina/DoLoans.php');

				DoLoansCallBack($_POST);

			}else if ($class_act == 'RepaymentNewTrade'){

				//满标放款代收

				require_once(APP_ROOT_PATH.'system/collocation/sina/RepaymentNewTrade.php');

				RepaymentNewTradeCallBack($_POST);

			}else if ($class_act == 'RepaymentNewTradeOk'){

				//满标放款代付

				require_once(APP_ROOT_PATH.'system/collocation/sina/RepaymentNewTrade.php');

				RepaymentNewTradeOkCallBack($_POST);

			}else if ($class_act == 'DoDpTradeAdvance'){

				//支付推进

				require_once(APP_ROOT_PATH.'system/collocation/sina/DoDpTradeAdvance.php');

				DoDpTradeAdvanceCallBack($_POST);

			}

			else if ($class_act == 'caozuohuankuan'){

				//支付推进

				require_once(APP_ROOT_PATH.'system/collocation/sina/RepaymentNewTrade.php');

				caozuohuankuan($_POST);

			}

			

		}

}

?>

