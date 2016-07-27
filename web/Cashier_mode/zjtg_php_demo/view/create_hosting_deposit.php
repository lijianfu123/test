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
    <title>托管充值交易测试页面</title>
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
    <h1>托管充值交易测试页面</h1>
    <fieldset>
        <legend>介绍</legend>
        <div>本页面仅在测试环境使用，可根据提示输入参数测试创建会员接口。demo使用时需要开启phpcurl扩展和openssl！，
            <h2>测试注意事项：</h2>

            <h3 style="color: red">网银支付联调环境仅支持TESTBANK ，快捷和绑定支付仅支持存钱罐账户的托管充值交易，测试环境中限额 basic账户限额1-4999，SAVING_POT：1-4999
                <br>，严格按照限额测试，超过限额交易不处理，不会异步通知结果。
            </h3>
        </div>
    </fieldset>
    <br> <br>
    <fieldset>
        <legend> 调用参数</legend>
        <input style="height: 40px; width: 150px;"
               type="button" value="托管充值" id="test_weibopay">&nbsp;<input
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
                            id="sign_type" name="sign_type">
                            <option value="RSA">RSA</option>
                        </select></td>
                    <td style="vertical-align: top; width: 25%;">签名方式支持RSA、MD5。建议使用MD5
                        非空
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align: top; width: 25%;">系统异步回调通知地址： <br>
                    </td>
                    <td style="vertical-align: top; width: 50%"><input
                            style="width: 100%;" id="notify_url" name="notify_url"
                            value="http://10.65.106.26/create_hosting_deposit/controller/notifyurl.php"> <br></td>
                    <td style="vertical-align: top; width: 25%;">钱包处理发生状态变迁后异步通知结果，响应结果为“success”，全部小写</td>
                </tr>
                <tr>
                    <td style="vertical-align: top; width: 25%;">页面跳转同步返回页面路径： <br>
                    </td>
                    <td style="vertical-align: top; width: 50%"><input
                            style="width: 100%;" id="return_url" name="return_url"
                            value="https://pay.sina.com.cn/zjtg"> <br></td>
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
                            value="create_hosting_deposit"> <br></td>
                    <td style="vertical-align: top; width: 25%;">接口名称 非空
                        create_hosting_deposit托管交易充值
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
                    <td style="vertical-align: top; width: 25%;">用户标识信息： <br>
                    </td>
                    <td style="vertical-align: top; width: 50%"><input
                            style="width: 100%;" id="identity_id" name="identity_id"
                            value=""> <br></td>
                    <td style="vertical-align: top; width: 25%;">商户系统用户id(字母或数字)<br>
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align: top; width: 25%;">用户标识类型： <br>
                    </td>
                    <td style="vertical-align: top; width: 50%"><input
                            style="width: 100%;" id="identity_type" name="identity_type"
                            value="UID"> <br></td>
                    <td style="vertical-align: top; width: 25%;">ID的类型，包括UID、MOBILE、EMAIL<br>
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align: top; width: 25%;">账户类型： <br>
                    </td>
                    <td style="vertical-align: top; width: 50%"><select
                            name="account_type" id="account_type">
                            <option value="BASIC">BASIC</option>
                            <option value="SAVING_POT">SAVING_POT</option>
                        </select></td>
                    <td style="vertical-align: top; width: 25%;">账户类型，BASIC 基本户
                        ENSURE 保证金户 SAVING_POT 存钱罐 <br>
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align: top; width: 25%;">金额： <br>
                    </td>
                    <td style="vertical-align: top; width: 50%"><input
                            style="width: 100%;" id="amount" name="amount" value="1.00"> <br></td>
                    <td style="vertical-align: top; width: 25%;">单位为：RMB
                        Yuan。取值范围为[0.01,1000000.00]，精确到小数点后两位。<br>
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align: top; width: 25%;">用户手续费： <br>
                    </td>
                    <td style="vertical-align: top; width: 50%"><input
                            style="width: 100%;" id="user_fee" name="user_fee" value="0"> <br></td>
                    <td style="vertical-align: top; width: 25%;">用户承担的手续费金额。可空<br>
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align: top; width: 25%;">支付方式： <br/>
                    </td>
                    <td style="vertical-align: top; width: 50%"><select
                            id="pay_method" name="pay_method"
                            onchange="payMethodChange(this)">
                            <option value="online_bank">online_bank</option>
                        </select> <br/></td>
                    <td style="vertical-align: top; width: 25%;">取值范围： online_bank 收银台模式仅支持online_bank
                        (网银支付)
                        格式：支付方式^金额^扩展|支付方式^金额^扩展。扩展信息内容以“，”f分割，针对不同支付方式的扩展定义见附录。
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
            <!-- 网银 收银台模式统一采用-->
            <div id="online_bank" name="payMethodType" style="display: block;">
                <table style="text-align: left; width: 100%; font-size: 12px;"
                       border="0" cellpadding="2" cellspacing="2">
                    <tr>
                        <td style="vertical-align: top; width: 25%;">银行编码： <br/>
                        </td>
                        <td style="vertical-align: top; width: 50%"><input
                                style="width: 100%;" id="online_bank_bankid"
                                name="online_bank_bankid" value="SINAPAY"/> <br/></td>
                        <td style="vertical-align: top; width: 25%;">见附录 6.16 银行机构列表</td>
                    </tr>
                    <tr>
                        <td style="vertical-align: top; width: 25%;">卡类型： <br/>
                        </td>
                        <td style="vertical-align: top; width: 50%"><input
                                style="width: 100%;" id="online_bank_card_type"
                                name="online_bank_card_type" value="DEBIT"/> <br/></td>
                        <td style="vertical-align: top; width: 25%;">DEBIT 借记|CREDIT
                            贷记（信用卡）
                        </td>
                    </tr>
                    <tr>
                        <td style="vertical-align: top; width: 25%;">卡属性： <br/>
                        </td>
                        <td style="vertical-align: top; width: 50%"><input
                                style="width: 100%;" id="online_bank_card_attribute"
                                name="online_bank_card_attribute" value="C"/> <br/></td>
                        <td style="vertical-align: top; width: 25%;">C 对私|B 对公</td>
                    </tr>
                </table>
            </div>
            <tr>
                <td colspan="3" style="text-align: center"><br/> <br/> <br/></td>
            </tr>
        </form>
        </table>
        <br/>
        <br/>

    </fieldset>
    <script>
        $("#test_weibopay").click(function () {
            var p = checkForm();
            if (p) {
                $.post('../controller/controller.php', p, function (data) {
                        document.write(data);
                    }
                );
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
            //签名
            var sign = $("#sign").val();
            if (typeof (sign) != "undefined" && sign) {
                params += '&sign=' + sign;
            }
            //签名方式
            var sign_type = $("#sign_type").val();
            if (typeof (sign_type) != "undefined" && sign_type) {
                params += '&sign_type=' + sign_type;
            } else {
                $("#error").html("请输入签名方式");
                return false;
            }
            //异步回调地址
            var notify_url = $("#notify_url").val();
            if (typeof (notify_url) != "undefined" && notify_url) {
                params += '&notify_url=' + notify_url;
            } else {
                $("#error").html("请输入异步回调地址");
                return false;
            }
            //网银返回地址
            var return_url = $("#return_url").val();
            if (typeof (return_url) != "undefined" && return_url) {
                params += '&return_url=' + return_url;
            } else {
                $("#error").html("请输入网银返回地址");
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
            var identity_id = $("#identity_id").val();
            if (typeof (identity_id) != "undefined" && identity_id) {
                params += '&identity_id=' + identity_id;
            } else {
                $("#error").html("请输入用户标识信息");
                return false;
            }
            var identity_type = $("#identity_type").val();
            if (typeof (identity_type) != "undefined" && identity_type) {
                params += '&identity_type=' + identity_type;
            } else {
                $("#error").html("请输入用户标识类型");
                return false;
            }
            var account_type = $("#account_type").val();
            if (typeof (account_type) != "undefined" && account_type) {
                params += '&account_type=' + account_type;
            } else {
                $("#error").html("请输入账户类型");
                return false;
            }

            var amount = $("#amount").val();
            if (typeof (amount) != "undefined" && amount) {
                params += '&amount=' + amount;
            } else {
                $("#error").html("请输入充值金额");
                return false;
            }
            //手续费可以为空
            var user_fee = $("#user_fee").val();
            if (typeof (user_fee) != "undefined" && user_fee) {
                params += '&user_fee=' + user_fee;
            }
            var pay_method = $("#pay_method").val();
            params += '&pay_method=' + pay_method;
            //网银付款
            if (pay_method == "online_bank") {
                //银行编码，demo实现所需参数
                var online_bank_bankid = $("#online_bank_bankid").val();
                if (typeof (online_bank_bankid) != "undefined" && online_bank_bankid) {
                    params += '&online_bank_bankid=' + online_bank_bankid;
                } else {
                    $("#error").html("请输网银付款银行编码");
                    return false;
                }
                //银行卡类型,demo实现所需参数
                var online_bank_card_type = $("#online_bank_card_type").val();
                if (typeof (online_bank_card_type) != "undefined" && online_bank_card_type) {
                    params += '&online_bank_card_type=' + online_bank_card_type;
                } else {
                    $("#error").html("请输网银付款卡类型");
                    return false;
                }
                //银行卡属性，demo实现所需参数
                var online_bank_card_attribute = $("#online_bank_card_attribute").val();
                if (typeof (online_bank_card_attribute) != "undefined" && online_bank_card_attribute) {
                    params += '&online_bank_card_attribute=' + online_bank_card_attribute;
                } else {
                    $("#error").html("请输网银付款卡属性");
                    return false;
                }
            }
            return params;
        }

        function payMethodChange(obj) {
            $("div[name=payMethodType]").hide();
            var id = $(obj).val();
            $("#" + id).show();
            if (id == "offline_pay") {
                $("#version").val("1.1");
            }
        }
    </script>
    <br>
    <br>
    <br>
</body>
</html>
