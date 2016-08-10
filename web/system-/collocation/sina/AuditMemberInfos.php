<?php

	function AuditMemberInfos($user_id,$member_url){
		$weibopay = new Weibopay();
		$user = array();
		$user = $GLOBALS['db']->getRow("select * from ".DB_PREFIX."user where id = ".$user_id);
		
		$path="upload/".$user_id."/";
		$zip = new ZipArchive;
		if ($zip->open($path.$user_id.'jujube.zip', ZipArchive::OVERWRITE) === TRUE){
			$zip->addFile($path."yyzz.jpg",$user_id."jujube/yyzz.jpg");
			$zip->addFile($path."zzjgz.jpg",$user_id."jujube/zzjgz.jpg");
			$zip->addFile($path."swdjz.jpg",$user_id."jujube/swdjz.jpg");
			$zip->addFile($path."jsxkz.jpg",$user_id."jujube/jsxkz.jpg");
			$zip->addFile($path."frzjz.jpg",$user_id."jujube/frzjz.jpg");
			$zip->addFile($path."frzjf.jpg",$user_id."jujube/frzjf.jpg");
		}
		$zip->close();

		$is_upload=sftp_upload(substr(dirname(__FILE__),0,-23)."/".$path.$user_id."jujube.zip",$user_id.'jujube.zip');
		if($is_upload){
			//$weibopay->write_log("上传资质文件成功！".$_POST["file_data"]);
		
			
		
		$request=array();
		$request["service"]="audit_member_infos";
		$request["version"]=sinapay_version;
		$request["request_time"]=date("YmdHis");
		$request["partner_id"]=sinapay_partner_id;
		$request["_input_charset"]=sinapay_input_charset;
		$request["sign"]="";
		$request["sign_type"]="RSA";
		$request["audit_order_no"]=build_order_no();
		$request["audit_order_no"]=build_order_no();
		$request["identity_id"]=to_guid_string($user_id);
		$request["identity_type"]="UID";
		$request["member_type"]="2";
		$request["company_name"]=$user["enterpriseName"];
		$request["address"]=$user["enterpriseaddresso"];
		$request["license_no"]=$weibopay->Rsa_encrypt($user["businessLicense"],sinapay_rsa_public__key);
		$request["license_address"]=$user["license_address"];
		$request["license_expire_date"]=$user["license_expire_date"];
		$request["business_scope"]=$user["business_scope"];
		$request["telephone"]=$weibopay->Rsa_encrypt($user["telephone"],sinapay_rsa_public__key);
		$request["email"]=$weibopay->Rsa_encrypt($user["email"],sinapay_rsa_public__key);
		$request["organization_no"]=$weibopay->Rsa_encrypt($user["orgNo"],sinapay_rsa_public__key);
		$request["summary"]=$user["brief"];
		$request["legal_person"]=$weibopay->Rsa_encrypt($user["real_name"],sinapay_rsa_public__key);
		$request["cert_no"]=$weibopay->Rsa_encrypt($user["idno"],sinapay_rsa_public__key);
		$request["cert_type"]="IC";
		$request["legal_person_phone"]=$weibopay->Rsa_encrypt($user["mobile"],sinapay_rsa_public__key);
		$request["bank_code"]=$user["bank_code"];
		$request["bank_account_no"]=$weibopay->Rsa_encrypt($user["bank_account_no"],sinapay_rsa_public__key);
		$request["card_type"]="DEBIT";
		$request["card_attribute"]="B";
		$request["province"]=$user["province"];
		$request["city"]=$user["city"];
		$request["bank_branch"]=$user["bank_branch"];
		$request["fileName"]=$user_id."jujube.zip";
		$request["digest"]=md5_file(substr(dirname(__FILE__),0,-23)."/".$path.$user_id."jujube.zip");
		$request["digestType"]="MD5";
		
	 	 
		ksort($request);//对签名参数据排序
		$request["sign"]=$weibopay->getSignMsg($request,$request["sign_type"]);
		
		$tijiao = $weibopay->createcurl_data($request); // 调用createcurl_data创建模拟表单需要的数据
		$result = $weibopay->curlPost($member_url,$tijiao); // 使用模拟表单提交进行数据提交
		$result = urldecode ($result);
		$splitdata = array ();
		$splitdata = json_decode($result,true);
		$sign_type = $splitdata ['sign_type'];
		ksort($splitdata); 
		
		print_r($splitdata);
		if ($weibopay->checkSignMsg ($splitdata,$sign_type)) {
			if ($splitdata["response_code"] == 'APPLY_SUCCESS') { // 成功
			
				showIpsInfo("提交成功，等待审核结果！",SITE_DOMAIN.APP_ROOT);
			
			
			} else { 
			    showErr($splitdata["response_message"],0);
				exit;
			}
		} else {
			showIpsInfo("sing error");
		}
		
		}else
		{
			showErr("上传资质文件失败！",0);
			exit;
		}
		
	}
	function sftp_upload($file,$filename) {
		$strServer = sinapay_sftp_address;
		$strServerPort = sinapay_sftp_port;
		$strServerUsername = sinapay_sftp_Username;
		$strServerprivatekey = sinapay_sftp_privatekey;
		$strServerpublickey = sinapay_sftp_publickey;
		$resConnection = ssh2_connect ( $strServer, $strServerPort );
		if (ssh2_auth_pubkey_file ( $resConnection, $strServerUsername, $strServerpublickey, $strServerprivatekey )) {
			$resSFTP = ssh2_sftp ( $resConnection );
			file_put_contents ( "ssh2.sftp://{$resSFTP}/upload/".$filename, $file);
			if (! copy ( $file, "ssh2.sftp://{$resSFTP}/upload/$filename" )) {
				return false;
			}
		}
		return true;
	}
?>