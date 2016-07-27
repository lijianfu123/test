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
    <title>标的录入(新)</title>
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
    <h1>标的录入测试页面</h1>
    <fieldset>
        <legend> 介绍</legend>
        <div>本页面仅在测试环境使用，可根据提示输入参数测试标的录入接口接口。demo使用时需要开启phpcurl扩展和openssl！</div>
    </fieldset>
    <div>
        <h2>测试注意事项：</h2>

        <h3 style="color: red">
        </h3>
    </div>
    <fieldset>
        <legend> 调用参数</legend>
        <input style="height: 40px; width: 150px;"
               type="button" value="标的录入" id="test_weibopay">&nbsp;<input
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
                        <br>
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align: top; width: 25%;">服务名称： <br>
                    </td>
                    <td style="vertical-align: top; width: 50%"><input
                            style="width: 100%;" id="service" name="service"
                            value="create_bid_info"> <br></td>
                    <td style="vertical-align: top; width: 25%;">接口名称 非空
                        create_bid_info标的录入(新)
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align: top; width: 25%;">接口版本： <br>
                    </td>
                    <td style="vertical-align: top; width: 50%"><input
                            style="width: 100%;" id="version" name="version"
                            value="<?php echo sinapay_version ?>"> <br></td>
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
                            value="<?php echo sinapay_partner_id ?>"> <br></td>
                    <td style="vertical-align: top; width: 25%;">签约合作方的钱包唯一用户号，非空参数
                        例如:2000001159940003
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align: top; width: 25%;">参数编码字符集： <br>
                    </td>
                    <td style="vertical-align: top; width: 50%"><input
                            style="width: 100%;" id="_input_charset" name="_input_charset"
                            value="<?php echo sinapay_input_charset ?>"> <br></td>
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
                    <td style="vertical-align: top; width: 25%;">签名方式支持RSA,非空
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align: top; width: 25%;">异步回调地址： <br>
                    </td>
                    <td style="vertical-align: top; width: 50%"><input
                            style="width: 100%;" id="notify_url" name="notify_url"
                            value="<?php echo sinapay_notify_url?>"> <br></td>
                    <td style="vertical-align: top; width: 25%;">标的结果异步接收地址
                    </td>
                </tr>
                <!--业务参数-->
                <td colspan="3"
                    style="text-align: center; color: green; font-weight: 700">业务参数必填项<br>
                    <br>
                </td>
                <tr>
                    <td style="vertical-align: top; width: 25%;">商户标的号： <br>
                    </td>
                    <td style="vertical-align: top; width: 50%"><input
                            style="width: 100%;" id="out_bid_no" name="out_bid_no"
                            value="<?php echo date("YmdHis") ?>"> <br></td>
                    <td style="vertical-align: top; width: 25%;">商户网站标的号，商户标的相关数据唯一索引</td>
                </tr>
                <tr>
                    <td style="vertical-align: top; width: 25%;">网站名称/平台名称： <br>
                    </td>
                    <td style="vertical-align: top; width: 50%"><input
                            style="width: 100%;" id="web_site_name" name="web_site_name"
                            value="<?php echo date("YmdHis") . '测试标的' ?>">
                        <br></td>
                    <td style="vertical-align: top; width: 25%;">网站名称/平台名称</td>
                </tr>
                <tr>
                    <td style="vertical-align: top; width: 25%;">标的名称： <br>
                    </td>
                    <td style="vertical-align: top; width: 50%"><input
                            style="width: 100%;" id="bid_name" name="bid_name"
                            value="<?php echo date("YmdHis") . '测试标的' ?>"> <br></td>
                    <td style="vertical-align: top; width: 25%;">标的名称</td>
                </tr>
                <tr>
                    <td style="vertical-align: top; width: 25%;">标的类型： <br>
                    </td>
                    <td style="vertical-align: top; width: 50%">
                        <select id="bid_type" name="bid_type">
                            <option value="CREDIT">信用</option>
                            <option value="MORTGAGE">抵押</option>
                            <option value="ASSIGNMENT_DEBT">债权转让</option>
                            <option value="OTHER">其他</option>
                        </select>
                        <br></td>
                    <td style="vertical-align: top; width: 25%;">还款期限 yyyyMMddHHmmss</td>
                </tr>

                <tr>
                    <td style="vertical-align: top; width: 25%;">发标金额： <br>
                    </td>
                    <td style="vertical-align: top; width: 50%"><input
                            style="width: 100%;" id="bid_amount" name="bid_amount" value="100"> <br></td>
                    <td style="vertical-align: top; width: 25%;">发标金额</td>
                </tr>
                <tr>
                    <td style="vertical-align: top; width: 25%;">年化收益率： <br>
                    </td>
                    <td style="vertical-align: top; width: 50%"><input
                            style="width: 100%;" id="bid_year_rate" name="bid_year_rate" value="1"> <br></td>
                    <td style="vertical-align: top; width: 25%;">年化收益率（%）</td>
                </tr>
                <tr>
                    <td style="vertical-align: top; width: 25%;">借款期限： <br>
                    </td>
                    <td style="vertical-align: top; width: 50%"><input
                            style="width: 100%;" id="bid_duration" name="bid_duration" value="20"> <br></td>
                    <td style="vertical-align: top; width: 25%;">借款期限，精确到天</td>
                </tr>
                <tr>
                    <td style="vertical-align: top; width: 25%;">还款方式： <br>
                    </td>
                    <td style="vertical-align: top; width: 50%">
                        <select id="repay_type" name="repay_type">
                            <option value="REPAY_CAPITAL_WITH_INTEREST">一次还本付息</option>
                            <option value="AVERAGE_CAPITAL">等额本金</option>
                            <option value="AVERAGE_CAPITAL_PLUS_INTERES">等额本息</option>
                            <option value="SCHEDULED_INTEREST_PAYMENTS_DUE">按期付息到期还本</option>
                            <option value="OTHER">其他</option>
                        </select>
                        <br></td>
                    <td style="vertical-align: top; width: 25%;">参考附录: 还款方式</td>
                </tr>
                <tr>
                    <td style="vertical-align: top; width: 25%;">标的开始时间： <br>
                    </td>
                    <td style="vertical-align: top; width: 50%"><input
                            style="width: 100%;" id="begin_date" name="begin_date" value="<?php echo date("YmdHis") ?>">
                        <br></td>
                    <td style="vertical-align: top; width: 25%;">标的开始时间</td>
                </tr>
                <tr>
                    <td style="vertical-align: top; width: 25%;">还款期限： <br>
                    </td>
                    <td style="vertical-align: top; width: 50%"><input
                            style="width: 100%;" id="term" name="term" value="<?php echo date("YmdHis") ?>"> <br></td>
                    <td style="vertical-align: top; width: 25%;">还款期限</td>
                </tr>
                <tr>
                    <td style="vertical-align: top; width: 25%;">担保方式： <br>
                    </td>
                    <td style="vertical-align: top; width: 50%">
                        <input
                            style="width: 100%;" id="guarantee_method" name="guarantee_method"
                            value="<?php echo date("YmdHis") ?>">
                        <br></td>
                    <td style="vertical-align: top; width: 25%;">企业担保
                        Xx保险担保
                        银行担保
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align: top; width: 25%;">借款人信息列表： <br>
                    </td>
                    <td style="vertical-align: top; width: 50%">
                        <input
                            style="width: 100%;" id="borrower_info_list" name="borrower_info_list" value="">
                        <br></td>
                    <td style="vertical-align: top; width: 25%;">借款人信息列表，参考借款人信息,参数之间用“~”分割，条目之间用“$”分割。
                        标的借款人信息列表不能超过10条。
                        恒丰商户目前只支持1条借款人信息。0254237911~UID~100~买房1~18300000001~01012345678~上海~10~100~本科~已婚~上海街道~a@126.com~summary~1^a|b^d$19870131~UID~100~买房2~18300000001~01012345678~上海~10~100~本科~已婚~上海街道~a@126.com~summary~1^a|b^d

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
        var service = $("#service").val();
        if (typeof (service) != "undefined" && service) {
            params += 'service=' + service;
        } else {
            $("#error").html("请输入服务名称");
            return false;
        }
        var version = $("#version").val();
        if (typeof (version) != "undefined" && version) {
            params += '&version=' + version;
        } else {
            $("#error").html("请输入接口版本");
            return false;
        }
        var request_time = $("#request_time").val();
        if (typeof (request_time) != "undefined" && request_time) {
            params += '&request_time=' + request_time;
        } else {
            $("#error").html("请输入请求时间");
            return false;
        }
        var partner_id = $("#partner_id").val();
        if (typeof (partner_id) != "undefined" && partner_id) {
            params += '&partner_id=' + partner_id;
        } else {
            $("#error").html("合作者身份ID");
            return false;
        }
        var _input_charset = $("#_input_charset").val();

        if (typeof (_input_charset) != "undefined" && _input_charset) {
            params += '&_input_charset=' + _input_charset;
        } else {
            $("#error").html("请输入参数编码字符集");
            return false;
        }
        var sign_type = $("#sign_type").val();
        if (typeof (sign_type) != "undefined" && sign_type == "RSA" || sign_type == "MD5") {
            params += '&sign_type=' + sign_type;
        } else {
            $("#error").html("签名方式有误或签名为空");
            return false;
        }
        var notify_url = $("#notify_url").val();
        if (typeof (notify_url) != "undefined") {
            params += '&notify_url=' + notify_url;
        } else {
            $("#error").html("异步回调地址不能为空");
            return false;
        }
        var sign = $("#sign").val();
        if (typeof (sign) != "undefined" && sign) {
            params += '&sign=' + sign;
        }
        /*****************业务参数**************/
        //商户标的号
        var out_bid_no = $("#out_bid_no").val();
        if (typeof (out_bid_no) != "undefined" && out_bid_no) {
            params += '&out_bid_no=' + out_bid_no;
        } else {
            $("#error").html("请输入商户标的号");
            return false;
        }
        var web_site_name = $("#web_site_name").val();
        if (typeof (web_site_name) != "undefined" && web_site_name) {
            params += '&web_site_name=' + web_site_name;
        } else {
            $("#error").html("请输入网站名称/平台名称");
            return false;
        }
        var bid_name = $("#bid_name").val();
        if (typeof (bid_name) != "undefined" && bid_name) {
            params += '&bid_name=' + bid_name;
        } else {
            $("#error").html("请输入标的名称");
            return false;
        }
        var bid_type = $("#bid_type").val();
        if (typeof (bid_type) != "undefined" && bid_type) {
            params += '&bid_type=' + bid_type;
        } else {
            $("#error").html("请输入标的类型");
            return false;
        }
        var bid_amount = $("#bid_amount").val();
        if (typeof (bid_amount) != "undefined" && bid_amount) {
            params += '&bid_amount=' + bid_amount;
        } else {
            $("#error").html("请输入发标金额");
            return false;
        }
        var bid_year_rate = $("#bid_year_rate").val();
        if (typeof (bid_year_rate) != "undefined" && bid_year_rate) {
            params += '&bid_year_rate=' + bid_year_rate;
        } else {
            $("#error").html("请输入年华收益率");
            return false;
        }
        var bid_duration = $("#bid_duration").val();
        if (typeof (bid_duration) != "undefined" && bid_duration) {
            params += '&bid_duration=' + bid_duration;
        } else {
            $("#error").html("请输入借款期限");
            return false;
        }
        var repay_type = $("#repay_type").val();
        if (typeof (repay_type) != "undefined" && repay_type) {
            params += '&repay_type=' + repay_type;
        } else {
            $("#error").html("请输入还款方式");
            return false;
        }
        var begin_date = $("#begin_date").val();
        if (typeof (begin_date) != "undefined" && begin_date) {
            params += '&begin_date=' + begin_date;
        } else {
            $("#error").html("请输入标的开始时间");
            return false;
        }
        var term = $("#term").val();
        if (typeof (term) != "undefined" && term) {
            params += '&term=' + term;
        } else {
            $("#error").html("请输入还款期限");
            return false;
        }
        var guarantee_method = $("#guarantee_method").val();
        if (typeof (guarantee_method) != "undefined" && guarantee_method) {
            params += '&guarantee_method=' + guarantee_method;
        } else {
            $("#error").html("请输入担保方式");
            return false;
        }
        var borrower_info_list = $("#borrower_info_list").val();
        if (typeof (borrower_info_list) != "undefined" && borrower_info_list) {
            params += '&borrower_info_list=' + borrower_info_list;
        } else {
            $("#error").html("请输入借款人信息列表");
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
