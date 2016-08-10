<?php

//ini_set("display_errors", "On");

//error_reporting(E_ALL | E_STRICT);

$collocation_item = $GLOBALS['db']->getRow("select config from ".DB_PREFIX."collocation where class_name='Sina'");
$collocation_cfg = unserialize($collocation_item['config']);
/*
 * 主配置文件 这里只配置必选和部分选填参数参数即可
 */
/**
 * 接口版本，新浪支付接口文档参数
 */
define("sinapay_version", "1.0");//接口版本
/**
 * 商户号，由新浪支付提供
 */
define("sinapay_partner_id",$collocation_cfg["sinapay_partner_id"]);//商户号
/**
 * 商户MD5KEY秘钥，由商户提供
 */
define("sinapay_md5_key",$collocation_cfg["sinapay_md5_key"]);//MD5KEY
/**
 * 商户接口字符集，新浪支付接口文档参数
 */
define("sinapay_input_charset", "utf-8");//接口字符集编码
/**
 * 商户签名私钥，由商户自己生成
 */
define("sinapay_rsa_sign_private_key",dirname(__File__) ."/key/rsa_sign_private.pem");//签名私钥
/**
 * 商户验证签名公钥，由商户自己生成
 */
define("sinapay_rsa_sign_public_key",dirname ( __File__ )."/key/rsa_sign_public.pem");//验证签名公钥
/**
 * 新浪支付特殊参数加密，公钥，由新浪支付提供
 */
define("sinapay_rsa_public__key",dirname ( __File__ )."/key/rsa_public.pem");//加密公钥

define("sinapay_debug_status",true);//true 开启日志记录  false 关闭日志记录
/**
 * 是否调试
 */
define("is_debug",$collocation_cfg['is_debug']);//加密公钥

define("fee_type",$collocation_cfg['fee_type']);//手续费

/**
 * sftp参数配置
 */
//sftp地址
define("sinapay_sftp_address","180.153.89.72");
//sftp端口号
define("sinapay_sftp_port","50022");
//sftp登录名 
define("sinapay_sftp_Username",$collocation_cfg["sinapay_partner_id"]);
//sftp登录私钥
define("sinapay_sftp_privatekey",dirname ( __File__ )."/key/id_rsa.rsa");
//sftp登录公钥
define("sinapay_sftp_publickey",dirname ( __File__ )."/key/id_rsa.pub"); 
//sftp上传目录
define("sinapay_sftp_upload_directory",dirname ( __File__ )."/upload");

?>