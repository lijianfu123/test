<?php

function ShowMemberInfosSina($user_id,$acquiring_url){	


			$weibopay = new Weibopay();
			$request=array();
			$request["version"]=sinapay_version;
			$request["request_time"]=date("YmdHis");
			$request["partner_id"]=sinapay_partner_id;
			$request["_input_charset"]=sinapay_input_charset;
			$request["sign_type"]="RSA";
			$request["service"]="show_member_infos_sina";
			$request["identity_id"]=to_guid_string($user_id);
			$request["identity_type"]="UID";
			ksort($request);
			
			$request["sign"]=urlencode($weibopay->getSignMsg($request,$request["sign_type"]));
			//print_r($request); 
			
			$html.="<form name='chaxun' id='chaxun' method='post'>";
			
			foreach ($request as $key => $val)
			{	
				$html.="<input type='hidden' name='".$key."' value='".$val."'/>";
				
			}
			
			$html.= "</form><script type = 'text/javascript'>";
			$html.="document.chaxun.action = '".$acquiring_url."';";
			$html.="document.chaxun.submit();";
			$html.="</script>";
			$html.='</body></html>';
			echo $html;
			exit();
}
?>