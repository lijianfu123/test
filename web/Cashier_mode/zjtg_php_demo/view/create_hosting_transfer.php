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
    <title>转账接口测试页面</title>
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
    <h1>转账接口测试页面</h1>
    <fieldset>
        <legend>介绍</legend>
    </fieldset>
    <div>本页面仅在测试环境使用，可根据提示输入参数测试创建会员接口。demo使用时需要开启phpcurl扩展和openssl！，
        <h2>测试注意事项：</h2>

        <h3 style="color: red">网银支付联调环境仅支持TESTBANK ，快捷和绑定支付仅支持存钱罐账户的托管充值交易，测试限额 basic账户限额1-4999，SAVING_POT：1-4999
            <br>，严格按照限额测试，超过限额交易不处理，不会异步通知结果。
        </h3>
    </div>
    <fieldset>
        <legend>调用参数</legend>
        <input style="height: 40px; width: 150px;"
               type="button" value="转账接口" id="test_weibopay">&nbsp;<input
            style="height: 40px; width: 150px;" type="button" value="刷新"
            id="reset"> <br/>

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
                            style="width: 100%;" id="version" name="version" value="<?php echo sinapay_version ?>"> <br>
                    </td>
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
                    <td style="vertical-align: top; width: 50%">
                        <select id="sign_type" name="sign_type">
                            <option value="RSA">RSA</option>
                        </select>
                    </td>
                    <td style="vertical-align: top; width: 25%;">签名方式支持RSA、
                        非空
                    </td>
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
                            value="create_hosting_transfer"> <br></td>
                    <td style="vertical-align: top; width: 25%;">接口名称 非空
                        create_hosting_transfer 转账接口
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align: top; width: 25%;">交易订单号： <br>
                    </td>
                    <td style="vertical-align: top; width: 50%"><input
                            style="width: 100%;" id="out_trade_no" name="out_trade_no"
                            value="<?php echo date("YmdHis") ?>"> <br></td>
                    <td style="vertical-align: top; width: 25%;">商户网站交易订单号，商户内部保证唯一</td>
                </tr>
                <tr>
                    <td style="vertical-align: top; width: 25%;">付款人标识： <br>
                    </td>
                    <td style="vertical-align: top; width: 50%"><input
                            style="width: 100%;" id="payer_identity_id" name="payer_identity_id"
                            value=""> <br></td>
                    <td style="vertical-align: top; width: 25%;">商户系统用户id(字母或数字)。</td>
                </tr>
                <tr>
                    <td style="vertical-align: top; width: 25%;">付款人标识类型： <br>
                    </td>
                    <td style="vertical-align: top; width: 50%"><input
                            style="width: 100%;" id="payer_identity_type" name="payer_identity_type"
                            value="UID"> <br></td>
                    <td style="vertical-align: top; width: 25%;">ID的类型，包括UID、MOBILE、EMAIL</td>
                </tr>
                <tr>
                    <td style="vertical-align: top; width: 25%;">付款人账户类型： <br>
                    </td>
                    <td style="vertical-align: top; width: 50%">
                        <select id="payer_account_type" name="payer_account_type">
                            <option value="BASIC">BASIC</option>
                        </select>
                        <br></td>
                    <td style="vertical-align: top; width: 25%;">付款人账户类型，仅支持 BASIS</td>
                </tr>
                <tr>
                    <td style="vertical-align: top; width: 25%;">收款人标识： <br>
                    </td>
                    <td style="vertical-align: top; width: 50%"><input
                            style="width: 100%;" id="payee_identity_id" name="payee_identity_id"
                            value=""> <br></td>
                    <td style="vertical-align: top; width: 25%;">商户系统用户ID、钱包系统会员ID。</td>
                </tr>
                <tr>
                    <td style="vertical-align: top; width: 25%;">收款人标识类型： <br>
                    </td>
                    <td style="vertical-align: top; width: 50%"><input
                            style="width: 100%;" id="payee_identity_type" name="payee_identity_type"
                            value="UID"> <br></td>
                    <td style="vertical-align: top; width: 25%;">ID的类型，包括UID、MOBILE、EMAIL。</td>
                </tr>
                <tr>
                    <td style="vertical-align: top; width: 25%;">收款人账户类型： <br>
                    </td>
                    <td style="vertical-align: top; width: 50%">
                        <select id="payee_account_type" name="payee_account_type">
                            <option value="SAVING_POT">SAVING_POT</option>
                        </select>
                        <br></td>
                    <td style="vertical-align: top; width: 25%;">账户类型目前仅支持SAVING_POT。</td>
                </tr>
                <tr>
                    <td style="vertical-align: top; width: 25%;">转账金额： <br>
                    </td>
                    <td style="vertical-align: top; width: 50%"><input
                            style="width: 100%;" id="amount" name="amount"
                            value=""> <br></td>
                    <td style="vertical-align: top; width: 25%;">金额
                    </td>
                </tr>

                <tr>
                    <td style="vertical-align: top; width: 25%;">摘要： <br>
                    </td>
                    <td style="vertical-align: top; width: 50%"><input
                            style="width: 100%;" id="summary" name="summary"
                            value="demotest"> <br></td>
                    <td style="vertical-align: top; width: 25%;">转账内容摘要
                    </td>
                </tr>
						<textarea style="color: red; font: menu;" name="result"
                                  id="result" cols="50" rows="10"></textarea>
                <tr>
                    <td colspan="3" style="text-align: center"><br> <br> <br></td>
                </tr>
                </tbody>
            </table>
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
                $("#result").val(data);
            });
        }
    });
    $("#reset").click(function () {
        location.reload();
    });
    function checkForm() {
        $("#error").html("");
        var params = '';
        /**********基本参数************/
        //接口版本
        var version = $("#version").val();
        if (typeof (version) != "undefined" && version) {
            params += 'version=' + version;
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

        //签名方式
        var sign_type = $("#sign_type").val();
        if (typeof (sign_type) != "undefined" && sign_type) {
            params += '&sign_type=' + sign_type;
        } else {
            $("#error").html("请输入签名方式");
            return false;
        }

        /**********业务参数************/
        //服务名称
        var service = $("#service").val();
        if (typeof (service) != "undefined" && service) {
            params += '&service=' + service;
        } else {
            $("#error").html("请输入服务名称");
            return false;
        }
        //交易订单号
        var out_trade_no = $("#out_trade_no").val();
        if (typeof (out_trade_no) != "undefined" && out_trade_no) {
            params += '&out_trade_no=' + out_trade_no;
        } else {
            $("#error").html("请输入交易订单号");
            return false;
        }
        //付款人标识
        var payer_identity_id = $("#payer_identity_id").val();
        if (typeof (payer_identity_id) != "undefined" && payer_identity_id) {
            params += '&payer_identity_id=' + payer_identity_id;
        } else {
            $("#error").html("请输入商户网站交易订单号");
            return false;
        }
        //付款人标识类型
        var payer_identity_type = $("#payer_identity_type").val();
        if (typeof (payer_identity_type) != "undefined" && payer_identity_type) {
            params += '&payer_identity_type=' + payer_identity_type;
        } else {
            $("#error").html("请输入付款人标示类型");
            return false;
        }
        //付款人账户类型
        var payer_account_type = $("#payer_account_type").val();
        if (typeof (payer_account_type) != "undefined" && payer_account_type) {
            params += '&payer_account_type=' + payer_account_type;
        } else {
            $("#error").html("请输入付款人账户类型");
            return false;
        }
        //收款人标识
        var payee_identity_id = $("#payee_identity_id").val();
        if (typeof (payee_identity_id) != "undefined" && payee_identity_id) {
            params += '&payee_identity_id=' + payee_identity_id;
        } else {
            $("#error").html("请输入收款人标示");
            return false;
        }
        //收款人标识类型
        var payee_identity_type = $("#payee_identity_type").val();
        if (typeof (payee_identity_type) != "undefined" && payee_identity_type) {
            params += '&payee_identity_type=' + payee_identity_type;
        } else {
            $("#error").html("请输入收款人标识类型");
            return false;
        }
        //收款人账户类型
        var payee_account_type = $("#payee_account_type").val();
        if (typeof (payee_account_type) != "undefined" && payee_account_type) {
            params += '&payee_account_type=' + payee_account_type;
        } else {
            $("#error").html("请输入收款人账户类型");
            return false;
        }
        //转账金额
        var amount = $("#amount").val();
        if (typeof (amount) != "undefined" && amount) {
            params += '&amount=' + amount;
        } else {
            $("#error").html("请输入转账金额");
            return false;
        }
        //摘要
        var summary = $("#summary").val();
        if (typeof (summary) != "undefined" && summary) {
            params += '&summary=' + summary;
        }
        return params;
    }

</script>
<br>
<br>
<br>
</body>
</html>
