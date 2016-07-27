<?php
// +----------------------------------------------------------------------
// | Yuemor 越陌p2p借贷系统
// +----------------------------------------------------------------------
// | Copyright (c) 2015 http://www.yuemor.com All rights reserved.
// +----------------------------------------------------------------------
// | 
// +----------------------------------------------------------------------

class licai_bid
{
	public function index(){
		
		$root = get_baseroot();
		
		$user =  $GLOBALS['user_info'];
		$root['session_id'] = es_session::id();
		$user_id  = intval($user['id']);
		if ($user_id >0){
			$root['user_login_status'] = 1;
			
			$id = intval($GLOBALS['request']['id']);
			$ajax = intval($GLOBALS['request']['ajax']);
			$money =  floatval($GLOBALS['request']['money']);
			$paypassword = trim($GLOBALS['request']['paypassword']);
			require_once APP_ROOT_PATH.'system/libs/licai.php';
			$result = licai_bid($id,$money,$paypassword);
			if($result['status']==0){
				$root['response_code'] = 0;
				$root['show_err'] = $result['info'];
			}
			else{
				$root['response_code'] = 1;
				$root['show_err'] = $result['info'];
			}
		}else{
			$root['response_code'] = 0;
			$root['show_err'] ="未登录";
			$root['user_login_status'] = 0;
		}
		output($root);		
	}
}
?>
