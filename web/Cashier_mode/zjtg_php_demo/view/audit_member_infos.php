<?php
    @include_once(dirname(__File__) . "/../config/conf.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
    <meta name="generator"
          content="HTML Tidy for Mac OS X (vers 31 October 2006 - Apple Inc. build 15.6), see www.w3.org">
    <meta http-equiv="content-type" content="text/html; charset=utf-8">
    <script src="../js/jquery-1.7.2.min.js"></script>
    <script src="../js/vendor/jquery.ui.widget.js"></script>
    <script src="../js/jquery.iframe-transport.js"></script>
    <script src="../js/jquery.fileupload.js"></script>
    <title>请求审核企业会员资质测试页面</title>
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
        <input style="height: 40px; width: 150px;"
               type="button" value="请求审核会员资质" id="test_weibopay">&nbsp;<input
            style="height: 40px; width: 150px;" type="button" value="刷新"
            id="reset"><br/>

        <div id="error" style="text-align: center; color: red"></div>
        <br/>

        <form id="request_form" name="request_form" enctype="multipart/form-data">
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
                            value="audit_member_infos"> <br></td>
                    <td style="vertical-align: top; width: 25%;">接口名称 非空
                        audit_member_infos请求审核企业会员资质
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
                    <td style="vertical-align: top; width: 25%;">系统异步回调通知地址： <br>
                    </td>
                    <td style="vertical-align: top; width: 50%"><input
                            style="width: 100%;" id="notify_url" name="notify_url" value="http://139.196.46.136/"> <br>
                    </td>
                    <td style="vertical-align: top; width: 25%;">钱包处理发生状态变迁后异步通知结果，响应结果为“success”，全部小写</td>
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
                <!--业务参数-->
                <td colspan="3"
                    style="text-align: center; color: green; font-weight: 700">业务参数必填项<br>
                    <br>
                </td>
                <tr>
                    <td style="vertical-align: top; width: 25%;">请求审核号： <br>
                    </td>
                    <td style="vertical-align: top; width: 50%"><input
                            style="width: 100%;" id="audit_order_no" name="audit_order_no"
                            value="<?php echo date("YmdHis") ?>"> <br></td>
                    <td style="vertical-align: top; width: 25%;">商户网站交易订单号，商户内部保证唯一</td>
                </tr>
                <tr>
                    <td style="vertical-align: top; width: 25%;">用户标识信息： <br>
                    </td>
                    <td style="vertical-align: top; width: 50%"><input
                            style="width: 100%;" id="identity_id" name="identity_id"
                            value=""> <br></td>
                    <td style="vertical-align: top; width: 25%;">商户系统用户id(字母或数字)</td>
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
                    <td style="vertical-align: top; width: 25%;">会员类型： <br>
                    </td>
                    <td style="vertical-align: top; width: 50%"><input
                            style="width: 100%;" id="member_type" name="member_type"
                            value="1"> <br></td>
                    <td style="vertical-align: top; width: 25%;">会员类型，详见附录，默认企业，且只支持企业 1<br>
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align: top; width: 25%;">公司名称： <br>
                    </td>
                    <td style="vertical-align: top; width: 50%"><input
                            style="width: 100%;" id="company_name" name="company_name"
                            value="sina支付"> <br></td>
                    <td style="vertical-align: top; width: 25%;">公司名称全称，以便审核通过<br>
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align: top; width: 25%;">企业网址： <br>
                    </td>
                    <td style="vertical-align: top; width: 50%"><input
                            style="width: 100%;" id="website" name="website" value="https://pay.sina.com.cn/zjtg"> <br>
                    </td>
                    <td style="vertical-align: top; width: 25%;">企业网址<br>
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align: top; width: 25%;">企业地址： <br>
                    </td>
                    <td style="vertical-align: top; width: 50%"><input
                            style="width: 100%;" id="address" name="address" value="sina支付"> <br></td>
                    <td style="vertical-align: top; width: 25%;">企业地址<br>
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align: top; width: 25%;">执照号： <br>
                    </td>
                    <td style="vertical-align: top; width: 50%"><input
                            style="width: 100%;" id="license_no" name="license_no" value="<?php echo date("Ymdhsi") ?>">
                        <br></td>
                    <td style="vertical-align: top; width: 25%;">密文，使用新浪支付RSA公钥加密。明文长度：50<br>
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align: top; width: 25%;">营业执照所在地： <br>
                    </td>
                    <td style="vertical-align: top; width: 50%"><input
                            style="width: 100%;" id="license_address" name="license_address"
                            value="上海"> <br></td>
                    <td style="vertical-align: top; width: 25%;">营业执照所在地<br>
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align: top; width: 25%;">执照过期日（营业期限）： <br>
                    </td>
                    <td style="vertical-align: top; width: 50%"><input
                            style="width: 100%;" id="license_expire_date"
                            name="license_expire_date" value="<?php echo date("Ymd") ?>"> <br></td>
                    <td style="vertical-align: top; width: 25%;">格式为“YYYYMMDD”<br>
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align: top; width: 25%;">营业范围： <br>
                    </td>
                    <td style="vertical-align: top; width: 50%"><input
                            style="width: 100%;" id="business_scope" name="business_scope"
                            value="测试"> <br></td>
                    <td style="vertical-align: top; width: 25%;">营业范围<br>
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align: top; width: 25%;">联系电话： <br>
                    </td>
                    <td style="vertical-align: top; width: 50%"><input
                            style="width: 100%;" id="telephone" name="telephone" value=""> <br></td>
                    <td style="vertical-align: top; width: 25%;">密文，使用新浪支付RSA公钥加密。明文长度：50<br>
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align: top; width: 25%;">联系Email： <br>
                    </td>
                    <td style="vertical-align: top; width: 50%"><input
                            style="width: 100%;" id="email" name="email" value="ms@weibopay.com"> <br></td>
                    <td style="vertical-align: top; width: 25%;">密文，使用新浪支付RSA公钥加密。明文长度：50<br>
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align: top; width: 25%;">组织机构代码： <br>
                    </td>
                    <td style="vertical-align: top; width: 50%"><input
                            style="width: 100%;" id="organization_no" name="organization_no"
                            value="<?php echo date("Ymdhsi") ?>"> <br></td>
                    <td style="vertical-align: top; width: 25%;">密文，使用新浪支付RSA公钥加密。明文长度：50<br>
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align: top; width: 25%;">企业简介： <br>
                    </td>
                    <td style="vertical-align: top; width: 50%"><input
                            style="width: 100%;" id="summary" name="summary" value="接口测试企业介绍"> <br></td>
                    <td style="vertical-align: top; width: 25%;">企业简介<br>
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align: top; width: 25%;">企业法人： <br>
                    </td>
                    <td style="vertical-align: top; width: 50%"><input
                            style="width: 100%;" id="legal_person" name="legal_person"
                            value="sina支付"> <br></td>
                    <td style="vertical-align: top; width: 25%;">企业法人<br>
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align: top; width: 25%;">法人证件号码： <br>
                    </td>
                    <td style="vertical-align: top; width: 50%"><input
                            style="width: 100%;" id="cert_no" name="cert_no" value="<?php echo date("Ymdhsi") ?>"> <br>
                    </td>
                    <td style="vertical-align: top; width: 25%;">法人证件号码<br>
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align: top; width: 25%;">证件类型： <br>
                    </td>
                    <td style="vertical-align: top; width: 50%"><input
                            style="width: 100%;" id="cert_type" name="cert_type" value="IC"> <br></td>
                    <td style="vertical-align: top; width: 25%;">见附录，目前只支持身份证<br>
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align: top; width: 25%;">法人手机号码： <br>
                    </td>
                    <td style="vertical-align: top; width: 50%"><input
                            style="width: 100%;" id="legal_person_phone"
                            name="legal_person_phone" value=""> <br></td>
                    <td style="vertical-align: top; width: 25%;">密文，使用新浪支付RSA公钥加密。明文长度：50<br>
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align: top; width: 25%;">银行编号： <br>
                    </td>
                    <td style="vertical-align: top; width: 50%"><input
                            style="width: 100%;" id="bank_code" name="bank_code" value="ICBC"> <br></td>
                    <td style="vertical-align: top; width: 25%;">见附录<br>
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align: top; width: 25%;">银行卡号： <br>
                    </td>
                    <td style="vertical-align: top; width: 50%"><input
                            style="width: 100%;" id="bank_account_no" name="bank_account_no"
                            value=""> <br></td>
                    <td style="vertical-align: top; width: 25%;">密文，使用新浪支付RSA公钥加密。明文长度：30<br>
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align: top; width: 25%;">卡类型： <br>
                    </td>
                    <td style="vertical-align: top; width: 50%"><input
                            style="width: 100%;" id="card_type" name="card_type" value="DEBIT"> <br></td>
                    <td style="vertical-align: top; width: 25%;">见附录 仅支持借记卡，默认为借记卡<br>
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align: top; width: 25%;">卡属性： <br>
                    </td>
                    <td style="vertical-align: top; width: 50%"><input
                            style="width: 100%;" id="card_attribute" name="card_attribute"
                            value="B"> <br></td>
                    <td style="vertical-align: top; width: 25%;">见附录 仅支持对公，默认为对公<br>
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align: top; width: 25%;">开户行省份： <br>
                    </td>
                    <td style="vertical-align: top; width: 50%"><input
                            style="width: 100%;" id="province" name="province" value="上海"> <br></td>
                    <td style="vertical-align: top; width: 25%;">开户行省份<br>
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align: top; width: 25%;">开户行城市： <br>
                    </td>
                    <td style="vertical-align: top; width: 50%"><input
                            style="width: 100%;" id="city" name="city" value="上海"> <br></td>
                    <td style="vertical-align: top; width: 25%;">开户行城市<br>
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align: top; width: 25%;">支行名称： <br>
                    </td>
                    <td style="vertical-align: top; width: 50%"><input
                            style="width: 100%;" id="bank_branch" name="bank_branch"
                            value="金沙江路支行"> <br></td>
                    <td style="vertical-align: top; width: 25%;">银行支行名称<br>
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align: top; width: 25%;">文件名称： <br>
                    </td>
                    <td style="vertical-align: top; width: 50%"><input
                            style="width: 100%;" id="fileName" name="fileName" value=""> <br></td>
                    <td style="vertical-align: top; width: 25%;">文件格式: *.zip
                        且文件名只能由数字或字母组成<br>
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align: top; width: 25%;">资质文件压缩包： <br>
                    </td>
                    <td style="vertical-align: top; width: 50%"><input id="fileupload" type="file" name="files[]"
                                                                       data-url="server/php/" multiple> <br></td>
                    <td style="vertical-align: top; width: 25%;">需要上传的资质文件，填写资质文件绝对路径<br>
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align: top; width: 25%;">文件摘要算法： <br>
                    </td>
                    <td style="vertical-align: top; width: 50%"><input
                            style="width: 100%;" id="digestType" name="digestType"
                            value="MD5"><br></td>
                    <td style="vertical-align: top; width: 25%;">见附录，目前只支持MD5，默认为MD5<br>
                    </td>
                </tr>
                <tr>
                    <td style="vertical-align: top; width: 25%;">是否上传： <br>
                    </td>
                    <td style="vertical-align: top; width: 50%">
                        <select id="update_status" name="update_status">
                            <option value="Y">Y</option>
                            <option value="N">N</option>
                        </select>
                        <br></td>
                    <td style="vertical-align: top; width: 25%;">demo实现需求参数，用于测试的时候传过一次资质后，后面不传直接用已近传过得资质做接口测试使用。<br>
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
    $(function () {
        $('#fileupload').fileupload({
            dataType: 'json',
            done: function (e, data) {
                $.each(data.result.files, function (index, file) {
                    $('<p/>').text(file.name).appendTo(document.body);
                    //将上传到服务端的文件名称返回到前段
                    $("#fileName").val(file.name);
                });
            }
        });
    });
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
        var update_status = $("#update_status").val();
        if (typeof (update_status) != "undefined" && update_status) {
            params += '&update_status=' + update_status;
        } else {
            $("#error").html("是否上传不能为空");
            return false;
        }

        var audit_order_no = $("#audit_order_no").val();
        if (typeof (audit_order_no) != "undefined" && audit_order_no) {
            params += '&audit_order_no=' + audit_order_no;
        } else {
            $("#error").html("请输入请求审核号");
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
        var member_type = $("#member_type").val();
        if (typeof (member_type) != "undefined" && member_type) {
            params += '&member_type=' + member_type;
        } else {
            $("#error").html("请选择会员类型");
            return false;
        }
        var company_name = $("#company_name").val();
        if (typeof (company_name) != "undefined" && company_name) {
            params += '&company_name=' + company_name;
        } else {
            $("#error").html("请输入公司名称");
            return false;
        }
        var website = $("#website").val();
        if (typeof (website) != "undefined" && website) {
            params += '&website=' + website;
        } else {
            $("#error").html("请输入企业网址");
            return false;
        }
        var address = $("#address").val();
        if (typeof (address) != "undefined" && address) {
            params += '&address=' + address;
        } else {
            $("#error").html("请输入企业地址");
            return false;
        }
        var license_no = $("#license_no").val();
        if (typeof (license_no) != "undefined" && license_no) {
            params += '&license_no=' + license_no;
        } else {
            $("#error").html("请输入执照号");
            return false;
        }
        var license_address = $("#license_address").val();
        if (typeof (license_address) != "undefined" && license_address) {
            params += '&license_address=' + license_address;
        } else {
            $("#error").html("请输入营业执照所在地");
            return false;
        }
        var license_expire_date = $("#license_expire_date").val();
        if (typeof (license_expire_date) != "undefined" && license_expire_date) {
            params += '&license_expire_date=' + license_expire_date;
        } else {
            $("#error").html("请输入执照过期日（营业期限）");
            return false;
        }
        var business_scope = $("#business_scope").val();
        if (typeof (business_scope) != "undefined" && business_scope) {
            params += '&business_scope=' + business_scope;
        } else {
            $("#error").html("请输入营业范围");
            return false;
        }
        var telephone = $("#telephone").val();
        if (typeof (telephone) != "undefined" && telephone) {
            params += '&telephone=' + telephone;
        } else {
            $("#error").html("请输入联系电话");
            return false;
        }
        var email = $("#email").val();
        if (typeof (email) != "undefined" && email) {
            params += '&email=' + email;
        } else {
            $("#error").html("请输入联系电话");
            return false;
        }
        var email = $("#email").val();
        if (typeof (email) != "undefined" && email) {
            params += '&email=' + email;
        } else {
            $("#error").html("请输入联系Email");
            return false;
        }
        var organization_no = $("#organization_no").val();
        if (typeof (organization_no) != "undefined" && organization_no) {
            params += '&organization_no=' + organization_no;
        } else {
            $("#error").html("请输入组织机构代码");
            return false;
        }
        var summary = $("#summary").val();
        if (typeof (summary) != "undefined" && summary) {
            params += '&summary=' + summary;
        } else {
            $("#error").html("请输入企业简介");
            return false;
        }
        var legal_person = $("#legal_person").val();
        if (typeof (legal_person) != "undefined" && legal_person) {
            params += '&legal_person=' + legal_person;
        } else {
            $("#error").html("请输入企业法人");
            return false;
        }
        var cert_no = $("#cert_no").val();
        if (typeof (cert_no) != "undefined" && cert_no) {
            params += '&cert_no=' + cert_no;
        } else {
            $("#error").html("请输入法人证件号码");
            return false;
        }
        var cert_type = $("#cert_type").val();
        if (typeof (cert_type) != "undefined" && cert_type) {
            params += '&cert_type=' + cert_type;
        } else {
            $("#error").html("请输入证件类型");
            return false;
        }
        var legal_person_phone = $("#legal_person_phone").val();
        if (typeof (legal_person_phone) != "undefined" && legal_person_phone) {
            params += '&legal_person_phone=' + legal_person_phone;
        } else {
            $("#error").html("请输入法人手机号码");
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
            $("#error").html("请输入卡类型");
            return false;
        }
        var card_attribute = $("#card_attribute").val();
        if (typeof (card_attribute) != "undefined" && card_attribute) {
            params += '&card_attribute=' + card_attribute;
        } else {
            $("#error").html("请输入卡类型");
            return false;
        }
        var province = $("#province").val();
        if (typeof (province) != "undefined" && province) {
            params += '&province=' + province;
        } else {
            $("#error").html("请输入开户行省份");
            return false;
        }
        var city = $("#city").val();
        if (typeof (city) != "undefined" && city) {
            params += '&city=' + city;
        } else {
            $("#error").html("请输入开户行城市");
            return false;
        }
        var bank_branch = $("#bank_branch").val();
        if (typeof (bank_branch) != "undefined" && bank_branch) {
            params += '&bank_branch=' + bank_branch;
        } else {
            $("#error").html("请输入支行名称");
            return false;
        }
        var fileName = $("#fileName").val();
        if (typeof (fileName) != "undefined" && fileName) {
            params += '&fileName=' + fileName;
        } else {
            $("#error").html("请输入文件名称");
            return false;
        }
        var digest = $("#digest").val();
        if (typeof (digest) != "undefined" && digest) {
            params += '&digest=' + digest;
        }
        var digestType = $("#digestType").val();
        if (typeof (digestType) != "undefined" && digestType) {
            params += '&digestType=' + digestType;
        } else {
            $("#error").html("请输入文件摘要算法");
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
