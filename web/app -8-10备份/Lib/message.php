<?php
// +----------------------------------------------------------------------
// | Yuemor 越陌p2p借贷系统
// +----------------------------------------------------------------------
// | Copyright (c) 2015 http://www.yuemor.com All rights reserved.
// +----------------------------------------------------------------------
// | 
// +----------------------------------------------------------------------


function get_message_list($limit,$where='',$is_top = true)
{
	$sql = "select * from ".DB_PREFIX."message where 1=1 ";
	
	if($is_top){
		$sql_count = "select count(*) from ".DB_PREFIX."message where 1=1";
		$sql .= " and pid=0 ";
		$sql_count .=  " and pid=0 ";
	}
	
	if($where!='')
	{
		$sql .= " and ".$where;
		$sql_count .=  " and ".$where;
	}
	
	$sql.=" order by create_time desc ";
	
	if($is_top){
		$sql.=" limit ".$limit;
		$count = $GLOBALS['db']->getOne($sql_count);
	}

	$list = $GLOBALS['db']->getAll($sql);
	
	return array('list'=>$list,'count'=>$count);
}

?>