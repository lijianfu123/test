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
    <title>绑定认证信息接口测试页面</title>
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
    <h1>绑定认证信息测试页面</h1>
    <fieldset>
        <legend> 介绍</legend>
        <div>本页面仅在测试环境使用，可根据提示输入参数测试创建会员接口。demo使用时需要开启phpcurl扩展和openssl！，
            <h2>测试注意事项：</h2>

            <h3 style="color: red">
                1，绑定认证信息接口需要客户提交一个认证手机号过来，此手机号目前是客户使用货币基金账户存钱罐时，以及设置支付密码收取验证码需要，建议客户在设置支付密码时在新浪填写，或者单独通过绑定认证信息接口进行绑定。
            </h3>
        </div>
    </fieldset>
    <br> <br>
    <fieldset>
        <legend> 调用参数</legend>
        <input style="height: 40px; width: 150px;"
               type="button" value="绑定认证信息" id="test_weibopay">&nbsp;<input
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
                    <td style="vertical-align: top; width: 50%">
                        <select id="sign_type" name="sign_type">
                            <option value="RSA">RSA</option>
                        </select>
                    </td>
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
                            value="binding_verify"> <br></td>
                    <td style="vertical-align: top; width: 25%;">接口名称 非空
                        binding_verify 绑定认证信息
                    </td>
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
                    <td style="vertical-align: top; width: 25%;">认证类型： <br>
                    </td>
                    <td style="vertical-align: top; width: 50%">
                        <select id="verify_type" name="verify_type">
                            <option value="MOBILE">MOBILE</option>
                            <option value="EMAIL">EMAIL</option>
                        </select>
                    </td>
                    <td style="vertical-align: top; width: 25%;">MOBILE 手机 EMAIL 邮箱<br>
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align: top; width: 25%;">认证内容：<br>
                    </td>
                    <td style="vertical-align: top; width: 50%"><input
                            style="width: 100%;" id="verify_entity" name="verify_entity"
                            value=""><br></td>
                    <td style="vertical-align: top; width: 25%;">密文，使用RSA 加密。明文长度：30<br>
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
        //认证类型
        var verify_type = $("#verify_type").val();
        if (typeof (verify_type) != "undefined" && verify_type) {
            params += '&verify_type=' + verify_type;
        } else {
            $("#error").html("请输入认证类型");
            return false;
        }
        //认证内容
        var verify_entity = $("#verify_entity").val();
        if (typeof (verify_entity) != "undefined" && verify_entity) {
            params += '&verify_entity=' + verify_entity;
        } else {
            $("#error").html("请输入认证内容");
            return false;
        }
        //用户标识信息
        var identity_id = $("#identity_id").val();
        if (typeof (identity_id) != "undefined" && identity_id) {
            params += '&identity_id=' + identity_id;
        }
        //用户标识类型
        var identity_type = $("#identity_type").val();
        if (typeof (identity_type) != "undefined" && identity_type) {
            params += '&identity_type=' + identity_type;
        }

        return params;
    }

</script>
<br>
<br>
<br>
</body>
</html>
