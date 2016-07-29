<?php
// +----------------------------------------------------------------------
// | Fanwe 方维p2p借贷系统
// +----------------------------------------------------------------------
// | Copyright (c) 2011 http://www.fanwe.com All rights reserved.
// +----------------------------------------------------------------------
// | Author: 云淡风轻(88522820@qq.com)
// +----------------------------------------------------------------------


require APP_ROOT_PATH.'app/Lib/deal.php';
class anquanModule extends SiteBaseModule
{
	public function index()
	{
		
		
		$GLOBALS['tmpl']->display("anquan.html",$cache_id);
	}
}	
?>