<?php
class activity
{
	public function index(){
		
		$root['program_title'] = "活动专区";
		$root['activity_turn'] = 1;
		
		
		$user =  $GLOBALS['user_info'];
		$root['session_id'] = es_session::id();
		$user_id  = intval($user['id']);
		//判断用户是否登录
		if($user_id == 0){
			//$GLOBALS['tmpl']->assign("is_login",false);
			$root['response_code'] = 0;
			$root['show_err'] ="未登录";
			$root['user_login_status'] = 0;
		}else{
			//$GLOBALS['tmpl']->assign("is_login",true);
			$root['user_login_status'] = 1;
			$root['response_code'] = 1;
		}
		//查询表中是否有数据
		$sql = "select * from ".DB_PREFIX."activity_lottery where user_id = {$user_id}";
		$user_lottery = $GLOBALS['db']->getAll($sql);
		if(count($user_lottery)>0){
			foreach($user_lottery as $item){
				$things = $item['things'];
				$lottery_time = $item['lottery_time'];
			}
			$root['things'] = explode("/",$things);
			$root['lottery_time'] = explode("/",$lottery_time);
			
			$root['new_things'] = $root['things'][count($root['things'])-1];
			$root['is_l'] = 1;//有过抽奖
		}else{
			$root['is_l'] = 0;//没有抽奖过
		}
		
		output($root);		
	}
}
?>