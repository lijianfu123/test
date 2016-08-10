<?php
/**
 * Created by PhpStorm.
 * User: donghui
 * Date: 2016/8/6
 * Time: 16:53
 */
function get_bank_info($user_id,$url)
{
    $weibopay = new Weibopay();
    $bind_bank_card=array();
    $requests=array();

    $requests["version"]=sinapay_version;
    $requests["request_time"]= date("YmdHis");
    $requests["partner_id"]=sinapay_partner_id;
    $requests["_input_charset"]=sinapay_input_charset;
    $requests["sign"]="";
    $requests["sign_type"]="RSA";
    $requests["service"]="query_bank_card";
    $requests["identity_id"]= to_guid_string((int)$user_id);
    $requests["identity_type"]='UID';
    ksort($requests);
    $requests["sign"]=$weibopay->getSignMsg($requests,$requests["sign_type"]);
    $tijiaos = $weibopay->createcurl_data($requests); // 调用createcurl_data创建模拟表单需要的数据
    $results = $weibopay->curlPost('https://gate.pay.sina.com.cn/mgs/gateway.do',$tijiaos); // 使用模拟表单提交进行数据提交
    $results = urldecode ($results);
    $splitdatas = array ();
    $splitdatas = json_decode($results,true);
    $sign_types = $splitdatas ['sign_type'];
    ksort($splitdatas);
    $arr=array();
    $bank = explode("|",$splitdatas['card_list']);


    foreach($bank as $key=>$val){
    $arr[$key] =  explode("^",$val);
    }
    $echostr = '';

    foreach($arr as $val){
        if($val[1] != ''){
        $echostr .= "


	            <div class='item_bank add_bank'>
	                <div class='tip'>
	                 <img src='https://www.haioujinfu.com/app/Tpl/new/images/bank/".$val[1].".gif'  >
	                 <p><font size='1' >储蓄卡</font></p>	                
	                 <p><font size='2' color='steelblue'>".$val[3]."   ".$val[2]."</font></p>
					 
	                </div>
                          <a onclick='return confirm(\"你确定要解绑此银行卡吗 ? \")' href='/index.php?ctl=collocation&act=UnbindingBankCard&card_id=$val[0]&user_id=$user_id'><div class='tip' style='cursor:pointer;'>解绑</div></a>
	            </div>

		";
        }
    }
    if($echostr == ''){
        echo 1;
    }else{
        echo $echostr;
    }

}