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
    <title>创建托管代收交易测试页面</title>
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
    <h1>创建托管代收交易测试页面</h1>
    <fieldset>
        <legend>介绍</legend>
        <div>本页面仅在测试环境使用，可根据提示输入参数测试创建会员接口。demo使用时需要开启phpcurl扩展和openssl！，
            <h2>测试注意事项：</h2>

            <h3 style="color: red">支付方式选择online_bank，银行编码使用SINAPAY 跳收银台进行付款测试限额1-4999
                <br>，严格按照限额测试，超过限额交易不处理，不会异步通知结果。
            </h3>
        </div>
    </fieldset>
    <br> <br>
    <fieldset>
        <legend> 调用参数</legend>
        <input style="height: 40px; width: 150px;"
               type="button" value="创建托管代收交易" id="test_weibopay">&nbsp;<input
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
                            value="create_hosting_collect_trade"> <br></td>
                    <td style="vertical-align: top; width: 25%;">接口名称 非空
                        create_hosting_collect_trade创建托管代收交易
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align: top; width: 25%;">交易订单号： <br>
                    </td>
                    <td style="vertical-align: top; width: 50%"><input
                            style="width: 100%;" id="out_trade_no" name="out_trade_no"
                            value="<?php echo date("YmdHis") ?>"> <br></td>
                    <td style="vertical-align: top; width: 25%;">商户网站交易订单号，商户内部保证唯一
                        String(32) 非空 20131105154925
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align: top; width: 25%;">交易码： <br>
                    </td>
                    <td style="vertical-align: top; width: 50%"><select
                            id="out_trade_code" name="out_trade_code">
                            <option value="1001">1001 代收投资金</option>
                            <option value="1002">1002 代收还款金</option>
                        </select></td>
                    <td style="vertical-align: top; width: 25%;">1001 代收投资金|1002
                        代收还款金 <br>
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align: top; width: 25%;">摘要： <br>
                    </td>
                    <td style="vertical-align: top; width: 50%"><input
                            style="width: 100%;" id="summary" name="summary" value="test"> <br></td>
                    <td style="vertical-align: top; width: 25%;">交易内容摘要 非空 房贷还款</td>
                </tr>
                <tr>
                    <td style="vertical-align: top; width: 25%;">交易关闭时间： <br>
                    </td>
                    <td style="vertical-align: top; width: 50%"><input
                            style="width: 100%;" id="trade_close_time"
                            name="trade_close_time" value="30m"> <br></td>
                    <td style="vertical-align: top; width: 25%;">设置未付款交易的超时时间，一旦超时，该笔交易就会自动被关闭。
                        取值范围：1m～15d。 m-分钟，h-小时，d-天 不接受小数点，如1.5d，可转换为36h。
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align: top; width: 25%;">支付失败后是否可以再次支付： <br>
                    </td>
                    <td style="vertical-align: top; width: 50%"><input
                            style="width: 100%;" id="can_repay_on_failed"
                            name="can_repay_on_failed" value="N"> <br></td>
                    <td style="vertical-align: top; width: 25%;">支付失败后，是否可以重复发起支付
                        取值范围：Y、N(忽略大小写) Y：可以再次支付 N：不能再次支付 默认值为Y
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align: top; width: 25%;">付款用户ID： <br>
                    </td>
                    <td style="vertical-align: top; width: 50%"><input
                            style="width: 100%;" id="payer_id" name="payer_id" value=""> <br>
                    </td>
                    <td style="vertical-align: top; width: 25%;">商户系统用户id(字母或数字)</td>
                </tr>
                <tr>
                    <td style="vertical-align: top; width: 25%;">标识类型： <br>
                    </td>
                    <td style="vertical-align: top; width: 50%"><input
                            style="width: 100%;" id="payer_identity_type"
                            name="payer_identity_type" value="UID"> <br></td>
                    <td style="vertical-align: top; width: 25%;">ID的类型，包括UID、MOBILE、EMAIL
                        非空 UID 商户用户id MOBILE 钱包绑定手机号 EMAIL 钱包绑定邮箱
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align: top; width: 25%;">代收金额： <br>
                    </td>
                    <td style="vertical-align: top; width: 50%"><input
                            style="width: 100%;" id="amount"
                            name="amount" value="1"> <br></td>
                    <td style="vertical-align: top; width: 25%;">金额，支付方式中需要金额</td>
                </tr>
                <tr>
                    <td style="vertical-align: top; width: 25%;">商户标的号： <br>
                    </td>
                    <td style="vertical-align: top; width: 50%"><input
                            style="width: 100%;" id="goods_id"
                            name="goods_id" value=""> <br></td>
                    <td style="vertical-align: top; width: 25%;">对应“标的录入”接口中的标的号，用来关联此笔代收和标的</td>
                </tr>
                <tr>
                    <td style="vertical-align: top; width: 25%;">代收交易类型： <br>
                    </td>
                    <td style="vertical-align: top; width: 50%"><input
                            style="width: 100%;" id="collect_trade_type"
                            name="collect_trade_type" value=""> <br></td>
                    <td style="vertical-align: top; width: 25%;">1、代收冻结交易传pre_auth
                        ，其它场景不传该参数。
                        2、代收冻结用户的账户余额；比如：用户的投资操作，商户想先冻结用户存钱罐的份额（代收冻结），保证用户的存钱罐余额在冻结期间仍然享受货基收益；
                    </td>
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
                        <td style="vertical-align: top; width: 25%;">DEBIT 借记）</td>
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
        //签名
        var sign = $("#sign").val();
        if (typeof (sign) != "undefined" && sign) {
            params += '&sign=' + sign;
        }
        //交易订单号
        var out_trade_no = $("#out_trade_no").val();
        if (typeof (out_trade_no) != "undefined" && out_trade_no) {
            params += '&out_trade_no=' + out_trade_no;
        } else {
            $("#error").html("交易订单号");
            return false;
        }
        //交易关闭时间
        var trade_close_time = $("#trade_close_time").val();
        if (typeof (trade_close_time) != "undefined" && trade_close_time) {
            params += '&trade_close_time=' + trade_close_time;
        } else {
            $("#error").html("请输入交易关闭时间");
            return false;
        }
        //支付失败后是否可以再次支付
        var can_repay_on_failed = $("#can_repay_on_failed").val();
        if (typeof (can_repay_on_failed) != "undefined" && can_repay_on_failed) {
            params += '&can_repay_on_failed=' + can_repay_on_failed;
        } else {
            $("#error").html("请输入是否允许失败后再付款");
            return false;
        }
        //交易码
        var out_trade_code = $("#out_trade_code").val();
        if (typeof (out_trade_code) != "undefined" && out_trade_code) {
            params += '&out_trade_code=' + out_trade_code;
        } else {
            $("#error").html("请输入交易码");
            return false;
        }
        //摘要
        var summary = $("#summary").val();
        if (typeof (summary) != "undefined" && summary) {
            params += '&summary=' + summary;
        } else {
            $("#error").html("请输入摘要");
            return false;
        }
        //付款用户ID
        var payer_id = $("#payer_id").val();
        if (typeof (payer_id) != "undefined" && payer_id) {
            params += '&payer_id=' + payer_id;
        } else {
            $("#error").html("请输入付款用户id");
            return false;
        }
        //标识类型
        var payer_identity_type = $("#payer_identity_type").val();
        if (typeof (payer_identity_type) != "undefined" && payer_identity_type) {
            params += '&payer_identity_type=' + payer_identity_type;
        } else {
            $("#error").html("请输入用户标示类型");
            return false;
        }
        //交易类型
        var payer_identity_type = $("#payer_identity_type").val();
        if (typeof (payer_identity_type) != "undefined" && payer_identity_type) {
            params += '&payer_identity_type=' + payer_identity_type;
        }
        //代收金额 demo定义参数
        var amount = $("#amount").val();
        if (typeof (amount) != "undefined" && amount) {
            params += '&amount=' + amount;
        } else {
            $("#error").html("请输入代收金额");
            return false;
        }
        //交易类型
        var collect_trade_type = $("#collect_trade_type").val();
        if (typeof (collect_trade_type) != "undefined" && collect_trade_type) {
            params += '&collect_trade_type=' + collect_trade_type;
        }
        var goods_id = $("#goods_id").val();
        if (typeof (goods_id) != "undefined" && goods_id) {
            params += '&goods_id=' + goods_id;
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
            if (online_bank_bankid != "SINAPAY") {
                $("#error").html("跳收银台网银卡银行（银行编号）必须为SINAPAY");
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
