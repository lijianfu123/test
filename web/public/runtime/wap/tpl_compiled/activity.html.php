<?php if ($_REQUEST['is_ajax'] != 1): ?>
<?php echo $this->fetch('./inc/header.html'); ?>	
<div class="page" id='<?php echo $this->_var['data']['act']; ?>'>
<?php
	$this->_var['back_url'] = wap_url("index","init#index");
	$this->_var['back_page'] = "#init";
    $this->_var['back_epage'] = $_REQUEST['epage']=="" ? "#init" : "#".$_REQUEST['epage'];
?>
<?php echo $this->fetch('./inc/title.html'); ?>
<div class="content infinite-scroll" now_page="1" data-distance="<?php echo $this->_var['data']['rs_count']; ?>"  all_page="<?php echo $this->_var['data']['page']['page_total']; ?>" ajaxurl="<?php
echo parse_wap_url_tag("u:index|about#index|"."".""); 
?>">
<!-- 这里是页面内容区 -->
<link rel="stylesheet" type="text/css" href="./css/activity_turn.css" />
<SCRIPT   LANGUAGE="JavaScript">       
function   fresh()  
{  
if(location.href.indexOf("&reload=true")<0)
   {
    location.href+="&reload=true";  
   }  
}  
setTimeout("fresh()",50);
</SCRIPT>
<!-- <br> -->
<div id="activity_turn" style="background:#e62d2d;width:100%;">
	<img src="./images/activity_turn/1.png" id="shan-img" style="display:none;" />
	<img src="./images/activity_turn/2.png" id="sorry-img" style="display:none;" />
	<div class="banner">
		<div class="turnplate" style="background-image:url(./images/activity_turn/turnplate-bg.png);background-size:100% 100%;">
			<canvas class="item" id="wheelcanvas" width="422px" height="422px"></canvas>
			<img class="pointer" src="./images/activity_turn/turnplate-pointer.png"/>
		</div>
	</div>
	<input type="phone" placeholder="请输入抽奖手机号码">
	<input id="phone" type="button" value="提交">
	<div style="height:200px;color:#FFBE04;">
		<div id="lottery_show" index="<?php echo $this->_var['data']['is_l']; ?>" style="width:230px;margin:0 auto;">
			<div id="lottery_res" style="width:230px;">最新抽奖结果：恭喜你抽到<?php echo $this->_var['data']['new_things']; ?>红包</div>
			<?php if ($this->_var['data']['is_l'] == 1): ?>
				<div>您的抽奖结果：</div>
				<div style="float:left;width:230px;">
					<div style="float:left;">
						<?php $_from = $this->_var['data']['things']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'thing');if (count($_from)):
    foreach ($_from AS $this->_var['thing']):
?>
							<div><?php echo $this->_var['thing']; ?></div>
						<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
					</div>
					<div style="float:left;margin-left:10px;">
						<?php $_from = $this->_var['data']['lottery_time']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'lottery_time');if (count($_from)):
    foreach ($_from AS $this->_var['lottery_time']):
?>
							<div><?php echo $this->_var['lottery_time']; ?></div>
						<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
					</div>
				</div>
			<?php endif; ?>
			<div style="float:left;width:230px;">
				<div>发送方式:</div>
				<div>1.完成注册及新浪托管并且开通</div>
				<div>2.填写邀请码</div>
				<div>3.将注册手机号码通过微信消息发送</div>
			</div>
		</div>
	</div>
</div>

<!-- 代码 结束 -->
<div class="integral_container">

</div>	

<?php echo $this->fetch('./inc/footer.html'); ?>
<?php endif; ?>
