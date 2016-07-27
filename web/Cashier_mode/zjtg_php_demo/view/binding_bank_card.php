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
    <title>绑定银行卡接口测试页面</title>
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
    <style type="text/css">
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            margin: 0;
        }

        #main {
            height: 1800px;
            padding-top: 90px;
            text-align: center;
        }

        #fullbg {
            background-color: gray;
            left: 0;
            opacity: 0.5;
            position: absolute;
            top: 0;
            z-index: 3;
            filter: alpha(opacity=50);
            -moz-opacity: 0.5;
            -khtml-opacity: 0.5;
        }

        #dialog {
            background-color: #fff;
            border: 5px solid rgba(0, 0, 0, 0.4);
            height: 350px;
            left: 50%;
            margin: -200px 0 0 -200px;
            padding: 1px;
            position: fixed !important; /* 浮动对话框 */
            position: absolute;
            top: 50%;
            width: 350px;
            z-index: 5;
            border-radius: 5px;
            display: none;
        }

        #dialog p {
            margin: 0 0 12px;
            height: 24px;
            line-height: 24px;
            background: #CCCCCC;
        }

        #dialog p.close {
            text-align: right;
            padding-right: 10px;
        }

        #dialog p.close a {
            color: #fff;
            text-decoration: none;
        }
    </style>
    <script type="text/javascript">
        //显示灰色 jQuery 遮罩层
        function showBg() {
            var bh = $("body").height();
            var bw = $("body").width();
            $("#fullbg").css({
                height: bh,
                width: bw,
                display: "block"
            });
            $("#dialog").show();
        }
        //关闭灰色 jQuery 遮罩
        function closeBg() {
            $("#fullbg,#dialog").hide();
        }
    </script>
</head>
<body>
<div id="mainContainer">
    <h1>绑定银行卡测试页面</h1>
    <fieldset>
        <legend> 介绍</legend>
        <div>本页面仅在测试环境使用，可根据提示输入参数测试创建会员接口。demo使用时需要开启phpcurl扩展和openssl！，
            <h2>测试注意事项：</h2>

            <h3 style="color: red">
                1，此接口用于用户绑定在新浪预留的银行卡信息接口。
            </h3>
        </div>
    </fieldset>
    <br> <br>
    <fieldset>
        <legend> 调用参数</legend>
        <input style="height: 40px; width: 150px;"
               type="button" value="绑定银行卡" id="test_weibopay">&nbsp;<input
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
                            name="sign_type" id="sign_type">
                            <option value="RSA">RSA</option>
                        </select></td>
                    <td style="vertical-align: top; width: 25%;">签名方式支持RSA
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
                            value="binding_bank_card"> <br></td>
                    <td style="vertical-align: top; width: 25%;">接口名称 非空
                        binding_bank_card绑定银行卡
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align: top; width: 25%;">绑卡请求号： <br>
                    </td>
                    <td style="vertical-align: top; width: 50%"><input
                            style="width: 100%;" id="request_no" name="request_no"
                            value="<?php echo date("YmdHis") ?>"> <br></td>
                    <td style="vertical-align: top; width: 25%;">绑定请求号</td>
                </tr>
                <tr>
                    <td style="vertical-align: top; width: 25%;">银行预留手机： <br>
                    </td>
                    <td style="vertical-align: top; width: 50%"><input
                            style="width: 100%;" id="phone_no" name="phone_no" value=""> <br></td>
                    <td style="vertical-align: top; width: 25%;">银行预留手机号</td>
                </tr>
                <tr>
                    <td style="vertical-align: top; width: 25%;">省份： <br>
                    </td>
                    <td style="vertical-align: top; width: 50%"><input
                            style="width: 100%;" id="province" name="province" value="上海"> <br></td>
                    <td style="vertical-align: top; width: 25%;">上海市，江苏省</td>
                </tr>
                <tr>
                    <td style="vertical-align: top; width: 25%;">城市： <br>
                    </td>
                    <td style="vertical-align: top; width: 50%"><input
                            style="width: 100%;" id="city" name="city" value="上海"> <br></td>
                    <td style="vertical-align: top; width: 25%;">上海市，南京市</td>
                </tr>
                <tr>
                    <td style="vertical-align: top; width: 25%;">认证方式：<br>
                    </td>
                    <td style="vertical-align: top; width: 50%"><input
                            style="width: 100%;" id="verify_mode" name="verify_mode"
                            value="SIGN"> <br></td>
                    <td style="vertical-align: top; width: 25%;">认证方式</td>
                </tr>
                <tr>
                    <td style="vertical-align: top; width: 25%;">用户标识信息： <br>
                    </td>
                    <td style="vertical-align: top; width: 50%"><input
                            style="width: 100%;" id="identity_id" name="identity_id"
                            value=""> <br></td>
                    <td style="vertical-align: top; width: 25%;">商户系统用户id(字母或数字)非空
                        2000011212 String(32)
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align: top; width: 25%;">用户标识类型： <br>
                    </td>
                    <td style="vertical-align: top; width: 50%"><input
                            style="width: 100%;" id="identity_type" name="identity_type"
                            value="UID"> <br></td>
                    <td style="vertical-align: top; width: 25%;">ID的类型，目前只包括UID 非空
                        UID String(16) <br>
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align: top; width: 25%;">银行编号： <br>
                    </td>
                    <td style="vertical-align: top; width: 50%"><input
                            style="width: 100%;" id="bank_code" name="bank_code"
                            value="ICBC"> <br></td>
                    <td style="vertical-align: top; width: 25%;">例如ICBC,CCB具体参考接口文档，附录
                        银行机构列表
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align: top; width: 25%;">银行卡号： <br>
                    </td>
                    <td style="vertical-align: top; width: 50%"><input
                            style="width: 100%;" id="bank_account_no" name="bank_account_no"
                            value=""> <br></td>
                    <td style="vertical-align: top; width: 25%;">密文，使用RSA 加密。明文长度：30
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align: top; width: 25%;">卡类型： <br>
                    </td>
                    <td style="vertical-align: top; width: 50%"><input
                            style="width: 100%;" id="card_type" name="card_type"
                            value="DEBIT"> <br></td>
                    <td style="vertical-align: top; width: 25%;">非空 DEBIT</td>
                </tr>
                <tr>
                    <td style="vertical-align: top; width: 25%;">卡属性： <br>
                    </td>
                    <td style="vertical-align: top; width: 50%"><input
                            style="width: 100%;" id="card_attribute" name="card_attribute"
                            value="C"> <br></td>
                    <td style="vertical-align: top; width: 25%;">C 对私 B 对公</td>
                </tr>
							<textarea style="color: red; font: menu;" name="result"
                                      id="result" cols="50" rows="10"></textarea>
                <tr>
                    <td colspan="3" style="text-align: center"><br> <br> <br></td>
                </tr>
                </tbody>
            </table>
            <br/> <br/>
        </form>
    </fieldset>
    <form>
        <div id="main">
            <div id="fullbg"></div>
            <div id="dialog">
                <p class="close">
                    <a href="#" onclick="closeBg();">关闭</a>
                </p>

                <div>
				<textarea style="color: red; font: menu;" name="resu"
                          id="resu" cols="50" rows="10"></textarea>

                    <p>验证码：</p>
                    <input type="text" name="valid_code" id="valid_code" value=""/>
                    <input type="hidden" name="ticket" id="ticket" value=""/>
                    <input type="hidden" name="services" id="services" value="binding_bank_card_advance"/>
                    <input
                        style="height: 20px; width: 80px;" type="button" value="提交"
                        id="advance">

                </div>
            </div>
        </div>

    </form>
</div>
<script>
    $("#test_weibopay").click(function () {
        var p = checkForm();
        if (p) {
            $.post('../controller/controller.php', p, function (data) {
                if (data.ticket != "undefined" && data.ticket) {
                    showBg();
                    $("#ticket").val(data.ticket);
                } else {
                    $("#result").val(data.response_message);


                }
            }, 'json');
        }
    });
    $("#advance").click(function () {
        var p = check();
        if (p) {
            $.post('../controller/controller.php', p, function (data) {
                $("#resu").val(data);
            });
        }
    });
    $("#reset").click(function () {
        location.reload();
    });
    function check() {
        $("#error").html("");
        var params = '';
        //验证码
        var valid_code = $("#valid_code").val();
        if (typeof (valid_code) != "undefined" && valid_code) {
            params += 'valid_code=' + valid_code;
        } else {
            $("#error").html("请输入验证码");
            return false;
        }
        //验证码
        var ticket = $("#ticket").val();
        if (typeof (ticket) != "undefined" && ticket) {
            params += '&ticket=' + ticket;
        } else {
            $("#error").html("ticket不能为空");
            return false;
        }
        var service = $("#services").val();
        if (typeof (service) != "undefined" && service) {
            params += '&service=' + service;
        } else {
            $("#error").html("service不能为空");
            return false;
        }
        return params;
    }

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
        //业务参数
        var identity_type = $("#identity_type").val();
        if (typeof (identity_type) != "undefined" && identity_type) {
            params += '&identity_type=' + identity_type;
        } else {
            $("#error").html("请输入用户标识类型");
            return false;
        }
        var identity_id = $("#identity_id").val();
        if (typeof (identity_id) != "undefined" && identity_id) {
            params += '&identity_id=' + identity_id;
        } else {
            $("#error").html("请输入用户标识id");
            return false;
        }

        var bank_code = $("#bank_code").val();
        if (typeof (bank_code) != "undefined" && bank_code) {
            params += '&bank_code=' + bank_code;
        } else {
            $("#error").html("请输入银行编号");
            return false;
        }
        var bank_account_no = $("#bank_account_no").val();
        if (typeof (bank_account_no) != "undefined" && bank_account_no) {
            params += '&bank_account_no=' + bank_account_no;
        } else {
            $("#error").html("请输入银行卡号");
            return false;
        }
        var card_type = $("#card_type").val();
        if (typeof (card_type) != "undefined" && card_type) {
            params += '&card_type=' + card_type;
        } else {
            $("#error").html("请输入银行卡类型");
            return false;
        }
        var card_attribute = $("#card_attribute").val();
        if (typeof (card_attribute) != "undefined" && card_attribute) {
            params += '&card_attribute=' + card_attribute;
        } else {
            $("#error").html("请输入银行卡属性");
            return false;
        }
        /*绑卡请求号*/
        var request_no = $("#request_no").val();
        if (typeof (request_no) != "undefined" && request_no) {
            params += '&request_no=' + request_no;
        } else {
            $("#error").html("请输入绑卡请求号");
            return false;
        }
        /*银行预留手机*/
        var phone_no = $("#phone_no").val();
        if (typeof (phone_no) != "undefined" && phone_no) {
            params += '&phone_no=' + phone_no;
        }
        /*省份*/
        var province = $("#province").val();
        if (typeof (province) != "undefined" && province) {
            params += '&province=' + province;
        } else {
            $("#error").html("请输入银行卡所属省份");
            return false;
        }
        /*城市*/
        var city = $("#city").val();
        if (typeof (city) != "undefined" && city) {
            params += '&city=' + city;
        } else {
            $("#error").html("请输入银行卡所属城市");
            return false;
        }
        /*绑卡认证方式*/
        var verify_mode = $("#verify_mode").val();
        if (typeof (verify_mode) != "undefined" && verify_mode) {
            params += '&verify_mode=' + verify_mode;
        }
        return params;
    }
</script>
<br>
<br>
<br>
</body>
</html>
