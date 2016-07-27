<?php
// +----------------------------------------------------------------------
// | Yuemor 越陌p2p借贷系统
// +----------------------------------------------------------------------
// | Copyright (c) 2015 http://www.yuemor.com All rights reserved.
// +----------------------------------------------------------------------
// | 
// +----------------------------------------------------------------------

class WeekwinAction extends CommonAction{
	public function index()
	{
		$everwin = M("PeiziWeekwin")->find();
		$this->assign("everwin",$everwin);
		$this->display();
	}
	
	public function update_index()
	{
		
	}
	
}
?>