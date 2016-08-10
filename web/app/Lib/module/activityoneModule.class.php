<?php
require APP_ROOT_PATH.'app/Lib/uc.php';
class activityoneModule extends SiteBaseModule
{
	public function index()
	{
		$user =  $GLOBALS['user_info'];
		$user_id  = intval($user['id']);
		//查询表中是否有数据
		$sql = "select * from ".DB_PREFIX."activity_lottery where user_id = {$user_id}";
		$user_lottery = $GLOBALS['db']->getAll($sql);
		if(count($user_lottery)>0){
			foreach($user_lottery as $item){
				$things = $item['things'];
				$lottery_time = $item['lottery_time'];
			}
			
			$things = explode("/",$things);
			$lottery_time = explode("/",$lottery_time);
				
			$new_things =$things[count($things)-1];
			$is_l = 1;//有过抽奖
			
			$GLOBALS['tmpl']->assign("things",$things);
			$GLOBALS['tmpl']->assign("lottery_time",$lottery_time);
			$GLOBALS['tmpl']->assign("new_things",$new_things);
			
		}else{
			$is_l= 0;//没有抽奖过
		}
		$GLOBALS['tmpl']->assign("is_l",$is_l);
		
		$GLOBALS['tmpl']->display("activityone.html",$cache_id);
	}
}	
?>