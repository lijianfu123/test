<?php
/******************
 *设计:李建府
 *说明:此算法需要一个预计抽奖人数，
 *第一天的中奖概率将根据预计中奖人数判断，接下来的几天奖根据前一天的实际抽奖人数进行人数的预判
 *每天中奖奖品的数量是固定的，防止奖品一次性抽完。
 ******************/
class activity_turn{
	public function index($dzpid)
	{	
		//抽奖条件，已经注册登录，能抽奖一次
		
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
			$phone = intval($GLOBALS['request']['phone']);
			// 		"28元", "38元", "谢谢参与", "48元", "58元", "68元", "8元 ", "18元"
			$prize_arr = array(
					'0' => array('id'=>1,'min'=>1,'max'=>44,'prize'=>'68元','v'=>1),
					'1' => array('id'=>2,'min'=>46,'max'=>89,'prize'=>'58元','v'=>2),
					'2' => array('id'=>3,'min'=>91,'max'=>134,'prize'=>'48元','v'=>3),
					'3' => array('id'=>4,'min'=>181,'max'=>224,'prize'=>'38元','v'=>4),
					'4' => array('id'=>5,'min'=>226,'max'=>269,'prize'=>'28元','v'=>90),
					'5' => array('id'=>6,'min'=>271,'max'=>314,'prize'=>'18元','v'=>400),
					'6' => array('id'=>7,'min'=>316,'max'=>359,'prize'=>'8元','v'=>1500),
					'7' => array('id'=>8,'min'=>136,'max'=>179,'prize'=>'6元','v'=>8000),
			);
			//关于中奖概率算法
			function getRand($proArr) {
				$result = '';
					
				//概率数组的总概率精度
				$proSum = array_sum($proArr);
					
				//概率数组循环
				foreach ($proArr as $key => $proCur) {
					$randNum = mt_rand(1, $proSum);
					if ($randNum <= $proCur) {
						$result = $key;
						break;
					} else {
						$proSum -= $proCur;
					}
				}
				unset ($proArr);
					
				return $result;
			}
			$phone = 18268252716;
			//查询activity_phone表中是否手机号，有可以抽奖，无（提示无抽奖资格）
			$sql = "select * from ".DB_PREFIX."activity_phone where phone = {$phone}";
			$user_phone = $GLOBALS['db']->getAll($sql);
			foreach ($user_phone as $ph){
				$effect = $ph['effect'];
			}
			$root[user_phone] = count($user_phone);
			if(count($user_phone)>0){
				if($effect == 1){
					$lottery_status = 1;//有抽奖资格
					
					//查询表中是否有数据，有抽奖后修改数据，无（抽奖后添加数据）
					$sql = "select * from ".DB_PREFIX."activity_lottery where user_id = {$user_id}";
					$user_lottery = $GLOBALS['db']->getAll($sql);
					if(count($user_lottery)>0){
						foreach($user_lottery as $item){
							$num = $item['num'];
							$things = $item['things'];
							$things_id = $item['things_id'];
							$lottery_time = $item['lottery_time'];
						}
					}
						
					// 			$root['user_lottery'] =$num;
					// 			$root['user_id'] = $user_id;
					if(count($user_lottery)==0 || $num>0){
						$lottery_status = 1;//有抽奖次数
						//函数getRand()会根据数组中设置的几率计算出符合条件的id，我们可以接着调用getRand()。
						foreach ($prize_arr as $key => $val) {
							$arr[$val['id']] = $val['v'];
						}
					
						$rid = getRand($arr); //根据概率获取奖项id
					
						$res = $prize_arr[$rid-1]; //中奖项
						$min = $res['min'];
						$max = $res['max'];
						// 		if($res['id']==7){ //谢谢参与
						// 			$i = mt_rand(0,5);
						// 			$result['angle'] = mt_rand($min[$i],$max[$i]);
						// 		}else{
						$result['angle'] = mt_rand($min,$max); //随机生成一个角度
						// 		}
						$result['prize'] = $res['prize'];
							
						// 		$root['success'] = 1;
						$root['result'] =  json_encode($result);
						// 		}else{
						// 			$root['success'] = 0;
						// 		}
						$now_time = date('Y-m-d H:i:s',time());
						if(count($user_lottery)==0){
							//将用户抽奖数据加入数据表中
							$sql = 'insert into '.DB_PREFIX."activity_lottery(user_id,things,things_id,num,lottery_time) values('{$user_id}','{$res['prize']}','{$rid}','0','{$now_time}')";
							//将用户抽奖信息数组写入数据库
							// 					$root['sql'] = $sql;
							$GLOBALS['db']->query($sql);
							$sql = 'UPDATE '.DB_PREFIX."activity_phone SET effect=0 WHERE phone= {$phone} ";
							$GLOBALS['db']->query($sql);
	// 						$root['sql'] = $sql;
						}else{
						//判断抽奖次数
							$things = $things.'/'.$res['prize'];
							$things_id = $things_id.'/'.$rid;
							$lottery_time = $lottery_time.'/'.$now_time;
							$now_num = $num -1;
							$root['now_num'] = $now_num;
							if($num>0){
							$sql = 'update '.DB_PREFIX."activity_lottery set things ='{$things}',things_id='{$things_id}',num='{$now_num}',lottery_time='{$lottery_time}' where user_id ='{$user_id}'";
							// 						$root['sql'] = $sql;
							$GLOBALS['db']->query($sql);
							}
							}
							}else{
							$lottery_status = 0;//无抽奖次数
					}
				}else{
					$lottery_status = 3;//手机号已失效
				}
			}else{
				$lottery_status = 2;//手机输错，没有这个手机号
			}
			
			$root['lottery_status'] = $lottery_status;
		}
		
		output($root);		
	}
}
?>