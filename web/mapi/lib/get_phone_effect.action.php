<?php
class get_phone_effect{
	public function index()
	{	
		$user =  $GLOBALS['user_info'];
		
		$user_id  = intval($user['id']);
		$phone = $GLOBALS['request']['phone'];
		$root['phone']  = $phone;
		$sql = "select * from ".DB_PREFIX."activity_phone where phone = {$phone}";
		$user_phone = $GLOBALS['db']->getAll($sql);
		if(count($user_phone)>0){
			foreach ($user_phone as $ph){
				$effect = $ph['effect'];
				if($effect ==1){
					$sql = 'UPDATE '.DB_PREFIX."activity_phone SET user_id={$user_id} WHERE phone= {$phone}";
					$GLOBALS['db']->query($sql);
				}
			}
		}else{
			$effect = 2;//无效
		}
		$root['effect'] = $effect;
		output($root);		
	}
}
?>