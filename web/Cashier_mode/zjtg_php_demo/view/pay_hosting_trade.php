<?php
    @include_once(dirname(__File__) . "/../config/conf.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <meta name="generator"
          content="HTML Tidy for Mac OS X (vers 31 October 2006 - Apple Inc. build 15.6), see www.w3.org">
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <script type="text/javascript" src="../js/jquery-1.7.2.min.js"></script>
    <title>创建托管交易支付接口测试页面</title>
    <style>
        input {
            height: 30px;
        }

        body {
            background-color: #FFFFFF;
            color: #333333;
            font-family: "Helvetica Neue", Helvetica, Arial, sans-serif;
            font-size: 12px;
            line-height: 18px;
            margin: 0;
        }
    </style>
</head>
<body>
<div id="mainContainer">
    <h1>创建托管交易支付接口测试页面</h1>
    <fieldset>
        <legend>介绍</legend>
        <div>本页面仅在测试环境使用，可根据提示输入参数测试创建会员接口。demo使用时需要开启phpcurl扩展和openssl！，
            <h2>测试注意事项：</h2>

            <h3 style="color: red">测试限额 basic账户限额1-4999,支付方式统一采用online_bank，跳收银台后选择支付方式进行付款。
                <br>，严格按照限额测试，超过限额交易不处理，不会异步通知结果。
            </h3>
        </div>
    </fieldset>
    <br> <br>
    <fieldset>
        <legend> 调用参数</legend>
        <input style="height: 40px; width: 150px;"
               type="button" value="创建托管交易支付" id="test_weibopay">&nbsp;<input
            style="height: 40px; width: 150px;" type="button" value="刷新"
            id="reset"><br/>

        <div id="error" style="text-align: center; color: red"></div>
        <br/>

        <form id="request_form" name="request_form">
            <table style="text-align: left; width: 100%; font-size: 12px;"
                   border="0" cellpadding="2" cellspacing="2">
                <tbody>
                <tr>
                    <td colspan="3"
                        style="text-align: center; color: green; font-weight: 700">基本参数必填项<br>
                        <br></td>
                </tr>
                <tr>
                    <td style="vertical-align: top; width: 25%;">接口版本： <br>
                    </td>
                    <td style="vertical-align: top; width: 50%"><input
                            style="width: 100%;" id="version" name="version"
                            value="<?php echo sinapay_version; ?>"> <br></td>
                    <td style="vertical-align: top; width: 25%;">接口版本 非空 1.0</td>
                </tr>
                <tr>
                    <td style="vertical-align: top; width: 25%;">请求时间： <br>
                    </td>
                    <td style="vertical-align: top; width: 50%"><input
                            style="width: 100%;" id="request_time" name="request_time"
                            value="<?php echo date("YmdHis") ?>"> <br></td>
                    <td style="vertical-align: top; width: 25%;">发起请求时间，格式yyyyMMddhhmmss
                        非空 20140101120401
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align: top; width: 25%;">合作者身份ID： <br>
                    </td>
                    <td style="vertical-align: top; width: 50%"><input
                            style="width: 100%;" id="partner_id" name="partner_id"
                            value="<?php echo sinapay_partner_id; ?>"> <br></td>
                    <td style="vertical-align: top; width: 25%;">签约合作方的钱包唯一用户号，非空参数
                        例如:2000001159940003
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align: top; width: 25%;">参数编码字符集： <br>
                    </td>
                    <td style="vertical-align: top; width: 50%"><input
                            style="width: 100%;" id="_input_charset" name="_input_charset"
                            value="<?php echo sinapay_input_charset; ?>"> <br></td>
                    <td style="vertical-align: top; width: 25%;">商户网站使用的编码格式，如utf-8、gbk、gb2312等。非空
                        UTF-8
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align: top; width: 25%;">签名方式： <br>
                    </td>
                    <td style="vertical-align: top; width: 50%"><select
                            name="sign_type" id="sign_type">
                            <option value="RSA">RSA</option>
                        </select></td>
                    <td style="vertical-align: top; width: 25%;">签名方式支持RSA、
                        非空
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align: top; width: 25%;">系统异步回调通知地址： <br>
                    </td>
                    <td style="vertical-align: top; width: 50%"><input
                            style="width: 100%;" id="notify_url" name="notify_url"
                            value="<?php echo sinapay_notify_url; ?>"> <br></td>
                    <td style="vertical-align: top; width: 25%;">钱包处理发生状态变迁后异步通知结果，响应结果为“success”，全部小写</td>
                </tr>
                <tr>
                    <td style="vertical-align: top; width: 25%;">页面跳转同步返回页面路径： <br>
                    </td>
                    <td style="vertical-align: top; width: 50%"><input
                            style="width: 100%;" id="return_url" name="return_url"
                            value="<?php echo sinapay_return_url; ?>"> <br></td>
                    <td style="vertical-align: top; width: 25%;">钱包处理完请求后，当前页面自动跳转到商户网站里指定页面的http路径。</td>
                </tr>

                <!--业务参数-->
                <tr>
                    <td colspan="3"
                        style="text-align: center; color: green; font-weight: 700">业务参数必填项<br>
                        <br></td>
                </tr>
                <tr>
                    <td style="vertical-align: top; width: 25%;">服务名称： <br>
                    </td>
                    <td style="vertical-align: top; width: 50%"><input
                            style="width: 100%;" id="service" name="service"
                            value="pay_hosting_trade"> <br></td>
                    <td style="vertical-align: top; width: 25%;">接口名称 非空
                        pay_hosting_trade创建托管交易支付接口
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align: top; width: 25%;">交易订单号： <br>
                    </td>
                    <td style="vertical-align: top; width: 50%"><input
                            style="width: 100%;" id="out_pay_no" name="out_pay_no"
                            value="<?php echo date("YmdHis") ?>"> <br></td>
                    <td style="vertical-align: top; width: 25%;">商户网站交易订单号，商户内部保证唯一
                        String(32) 非空 20131105154925
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align: top; width: 25%;">商户网站唯一交易订单号集合： <br>
                    </td>
                    <td style="vertical-align: top; width: 50%"><input
                            style="width: 100%;" id="outer_trade_no_list" name="outer_trade_no_list"
                            value=""> <br></td>
                    <td style="vertical-align: top; width: 25%;">钱包合作商户网站唯一订单号（确保在合作伙伴系统中唯一）。总数不超过十笔</td>
                </tr>
                <tr>
                    <td style="vertical-align: top; width: 25%;">交易支付金额： <br>
                    </td>
                    <td style="vertical-align: top; width: 50%"><input
                            style="width: 100%;" id="amount" name="amount"
                            value=""> <br></td>
                    <td style="vertical-align: top; width: 25%;">demo实现需求，定义的金额参数</td>
                </tr>
                <tr>
                    <td style="vertical-align: top; width: 25%;">支付方式： <br>
                    </td>
                    <td style="vertical-align: top; width: 50%"><select
                            id="pay_method" name="pay_method"
                            onchange="payMethodChange(this)">
                            <option value="online_bank">online_bank</option>
                        </select><br></td>
                    <td style="vertical-align: top; width: 25%;">取值范围：
                        online_bank(网银支付)</br>
                        balance(余额支付)
                        测试网银支付：online_bank^260.00^SINAPAY,DEBIT,C</br>
                    </td>
                </tr>
						<textarea style="color: red; font: menu;" name="result"
                                  id="result" cols="50" rows="10"></textarea>
                <tr>
                    <td colspan="3" style="text-align: center"><br> <br> <br></td>
                </tr>
                </tbody>
            </table>
            <!-- 支付方式扩展参数 -->
            <!-- 网银 -->
            <div id="online_bank" name="payMethodType" style="display:block;">
                <table style="text-align: left; width: 100%; font-size: 12px;"
                       border="0" cellpadding="2" cellspacing="2">
                    <tr>
                        <td style="vertical-align: top; width: 25%;">银行编码： <br/>
                        </td>
                        <td style="vertical-align: top; width: 50%"><input
                                style="width: 100%;" id="online_bank_bankid" name="online_bank_bankid" value="SINAPAY"/>
                            <br/></td>
                        <td style="vertical-align: top; width: 25%;">见附录 6.16 银行机构列表</td>
                    </tr>
                    <tr>
                        <td style="vertical-align: top; width: 25%;">卡类型： <br/>
                        </td>
                        <td style="vertical-align: top; width: 50%"><input
                                style="width: 100%;" id="online_bank_card_type" name="online_bank_card_type"
                                value="DEBIT"/> <br/></td>
                        <td style="vertical-align: top; width: 25%;">DEBIT 借记|CREDIT 贷记（信用卡）</td>
                    </tr>
                    <tr>
                        <td style="vertical-align: top; width: 25%;">卡属性： <br/>
                        </td>
                        <td style="vertical-align: top; width: 50%"><input
                                style="width: 100%;" id="online_bank_card_attribute" name="online_bank_card_attribute"
                                value="C"/> <br/></td>
                        <td style="vertical-align: top; width: 25%;">C 对私|B 对公</td>
                    </tr>
                </table>
            </div>
            <!-- 余额支付 -->
            <div id="balance" name="payMethodType" style="display:none">
                <table style="text-align: left; width: 100%; font-size: 12px;"
                       border="0" cellpadding="2" cellspacing="2">
                    <tr>
                        <td style="vertical-align: top; width: 25%;">账户类型： <br/>
                        </td>
                        <td style="vertical-align: top; width: 50%">
                            <!--账户类型 -->
                            <select id="account_type" name="account_type">
                                <option value="BASIC">BASIC</option>
                                <option value="ENSURE">ENSURE</option>
                                <option value="RESERVE">RESERVE</option>
                                <option value="SAVING_POT">SAVING_POT</option>
                            </select>
                            <br/>
                        </td>
                        <td style="vertical-align: top; width: 25%;">账户类型</td>
                    </tr>
                </table>
            </div>

            <!-- 支付方式扩展结束 -->
            <br/> <br/>

    </fieldset>
    </form>
    <form id="weibopya_send"></form>
</div>
<script>
    $("#test_weibopay").click(function () {
            var p = checkForm();
            if (p) {
                $.post('../controller/controller.php', p, function (data) {
                    document.write(data);
                });
            }
        }
    );
    $("#reset").click(function () {
        location.reload();
    });
    function checkForm() {
        $("#error").html("");
        var params = '';
        //服务名称
        var service = $("#service").val();
        if (typeof (service) != "undefined" && service) {
            params += 'service=' + service;
        } else {
            $("#error").html("请输入服务名称");
            return false;
        }
        //接口版本
        var version = $("#version").val();
        if (typeof (version) != "undefined" && version) {
            params += '&version=' + version;
        } else {
            $("#error").html("请输入接口版本");
            return false;
        }
        //请求时间
        var request_time = $("#request_time").val();
        if (typeof (request_time) != "undefined" && request_time) {
            params += '&request_time=' + request_time;
        } else {
            $("#error").html("请输入请求时间");
            return false;
        }
        var return_url = $("#return_url").val();
        if (typeof (return_url) != "undefined" && return_url) {
            params += '&return_url=' + return_url;
        } else {
            $("#error").html("请输入网银返回地址");
            return false;
        }
        var notify_url = $("#notify_url").val();
        if (typeof (notify_url) != "undefined" && notify_url) {
            params += '&notify_url=' + notify_url;
        } else {
            $("#error").html("请输入异步回调地址");
            return false;
        }
        //合作者身份ID
        var partner_id = $("#partner_id").val();
        if (typeof (partner_id) != "undefined" && partner_id) {
            params += '&partner_id=' + partner_id;
        } else {
            $("#error").html("合作者身份ID");
            return false;
        }
        //字符编码
        var _input_charset = $("#_input_charset").val();

        if (typeof (_input_charset) != "undefined" && _input_charset) {
            params += '&_input_charset=' + _input_charset;
        } else {
            $("#error").html("请输入参数编码字符集");
            return false;
        }
        //签名类型
        var sign_type = $("#sign_type").val();
        if (typeof (sign_type) != "undefined" && sign_type) {
            params += '&sign_type=' + sign_type;
        } else {
            $("#error").html("请输入签名方式");
            return false;
        }
        //交易订单号
        var out_pay_no = $("#out_pay_no").val();
        if (typeof (out_pay_no) != "undefined" && out_pay_no) {
            params += '&out_pay_no=' + out_pay_no;
        } else {
            $("#error").html("支付请求号");
            return false;
        }
        //商户网站唯一交易订单号集合
        var outer_trade_no_list = $("#outer_trade_no_list").val();
        if (typeof (outer_trade_no_list) != "undefined" && outer_trade_no_list) {
            params += '&outer_trade_no_list=' + outer_trade_no_list;
        } else {
            $("#error").html("请输入商户网站唯一交易订单号集合");
            return false;
        }
        //托管交易支付金额
        var amount = $("#amount").val();
        if (typeof (amount) != "undefined" && amount) {
            params += '&amount=' + amount;
        } else {
            $("#error").html("请输入交易支付金额");
            return false;
        }
        var pay_method = $("#pay_method").val();
        params += '&pay_method=' + pay_method;
        //网银付款
        if (pay_method == "online_bank") {
            var online_bank_bankid = $("#online_bank_bankid").val();
            if (typeof (online_bank_bankid) != "undefined" && online_bank_bankid) {
                params += '&online_bank_bankid=' + online_bank_bankid;
            } else {
                $("#error").html("请输网银付款银行编码");
                return false;
            }
            if (online_bank_bankid != "TESTBANK") {
                $("#error").html("测试环境中网银卡银行（银行编号）必须为TESTBANK");
                return false;
            }
            var online_bank_card_type = $("#online_bank_card_type").val();
            if (typeof (online_bank_card_type) != "undefined" && online_bank_card_type) {
                params += '&online_bank_card_type=' + online_bank_card_type;
            } else {
                $("#error").html("请输网银付款卡类型");
                return false;
            }
            var online_bank_card_attribute = $("#online_bank_card_attribute").val();
            if (typeof (online_bank_card_attribute) != "undefined" && online_bank_card_attribute) {
                params += '&online_bank_card_attribute=' + online_bank_card_attribute;
            } else {
                $("#error").html("请输网银付款卡属性");
                return false;
            }
        } else if (pay_method == "balance") {
            //余额支付
            var account_type = $("#account_type").val();//账户类型
            if (typeof (account_type) != "undefined" && account_type) {
                params += '&account_type=' + account_type;
            } else {
                $("#error").html("请选择账户类型");
                return false;
            }
        }

        return params;
    }

    function payMethodChange(obj) {
        $("div[name=payMethodType]").hide();
        var id = $(obj).val();
        $("#" + id).show();
    }
</script>
<br>
<br>
<br>
</body>
</html>
