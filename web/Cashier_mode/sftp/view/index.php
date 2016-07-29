<?php
    @include_once(dirname(__File__) . "/../config/conf.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <meta name="generator"
          content="HTML Tidy for Mac OS X (vers 31 October 2006 - Apple Inc. build 15.6), see www.w3.org">
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <script type="text/javascript" src="jquery-1.7.2.min.js"></script>
    <title>sftp对账测试页面</title>
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
    <h1>请求审核企业会员资质测试页面</h1>
    <fieldset>
        <legend> 介绍</legend>
        <div>本页面仅在测试环境使用，可根据提示输入参数测试请求企业会员资质接口。demo使用时需要开启phpcurl扩展和openssl！</div>
    </fieldset>
    <br> <br>
    <fieldset>
        <legend> 调用参数</legend>
        <input style="height: 40px; width: 150px;" type="button" value="sftp对账"
               id="getSignMsg"/> <input
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
                        style="text-align: center; color: green; font-weight: 700">必选填项<br>
                        <br>
                    </td>
                </tr>

                <tr>
                    <td style="vertical-align: top; width: 25%;">对账开关： <br>
                    </td>
                    <td style="vertical-align: top; width: 50%"><select
                            id="servicestatus" name="servicestatus">
                            <option value="start">开始下载对账文件</option>
                            <option value="stop">结束下载对账文件</option>
                        </select></td>
                    <td style="vertical-align: top; width: 25%;">签名方式支持RSA、MD5。建议使用MD5,非空
                    </td>
                </tr>
						<textarea style="color: red; font: menu;" name="result"
                                  id="result" cols="50" rows="10"></textarea>
                <tr>
                    <td colspan="3" style="text-align: center"><br> <br> <br></td>
                </tr>
                </tbody>
            </table>
            <br/>
            <br/>

    </fieldset>
    </form>
    <form id="weibopya_send"></form>
</div>
<script>
    $("#getSignMsg").click(function () {
        var p = checkForm();
        if (p) {
            $.post('../controller/ftptools.php', p, function (data) {
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
        var servicestatus = $("#servicestatus").val();
        if (typeof (servicestatus) != "undefined" && servicestatus) {
            params += 'servicestatus=' + servicestatus;
        } else {
            $("#error").html("请选择操作类型");
            return false;
        }
        return params;
    }

</script>
<br>
<br>
<br>
</body>
</html>
