<?php
// +----------------------------------------------------------------------
// | Yuemor 越陌p2p借贷系统
// +----------------------------------------------------------------------
// | Copyright (c) 2015 http://www.yuemor.com All rights reserved.
// +----------------------------------------------------------------------
// | 
// +----------------------------------------------------------------------
require APP_ROOT_PATH.'app/Lib/crowd_func.php';
class crowd_listModule extends SiteBaseModule
{
	
	public function index(){
		
		 
		$GLOBALS['tmpl']->display("page/crowd_list.html");
	}
 	
}
?>
