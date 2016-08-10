<?php echo $this->fetch('./inc/header.html'); ?>	
<div class="page" id='<?php echo $this->_var['data']['act']; ?>'>
<?php
	$this->_var['back_url'] = wap_url("index","init#index");
	$this->_var['back_page'] = "#init";
	$this->_var['back_epage'] = $_REQUEST['epage']=="" ? "#init" : "#".$_REQUEST['epage'];
?>
<?php echo $this->fetch('./inc/title.html'); ?>
<div class="content">
<!-- 这里是页面内容区 -->
<!--新手体验区-->

<div class="learn_activity_box">
	<?php if ($this->_var['data']['rrruid'] == 0 || $this->_var['data']['user_id'] > 0): ?>
	<div class="blank055"></div>
	<div class="choose">
		<table>
			<tr>
				<th <?php if ($this->_var['data']['status'] == 0): ?>class="y"<?php endif; ?>><a href="#" onclick="reloadpage('<?php
echo parse_wap_url_tag("u:index|learn_activity|"."status=0".""); 
?>','#<?php echo $this->_var['data']['act']; ?>','.learn_activity_box')">体验首页</a></th>
				<?php if ($this->_var['data']['user_id']): ?>
					<th <?php if ($this->_var['data']['status'] == 1): ?>class="y"<?php endif; ?>><a href="#" onclick="reloadpage('<?php
echo parse_wap_url_tag("u:index|learn_activity|"."status=1&rrruid=".$this->_var['data']['user_id']."".""); 
?>','#<?php echo $this->_var['data']['act']; ?>','.learn_activity_box')">体验账户</a></th>
				<?php else: ?>
					<th <?php if ($this->_var['data']['status'] == 1): ?>class="y"<?php endif; ?>><a href="#" onclick="RouterURL('<?php
echo parse_wap_url_tag("u:index|login|"."".""); 
?>','#login'<?php if ($this->_var['is_weixin']): ?>,1<?php endif; ?>)">体验账户</a></th>
				<?php endif; ?>
				<th <?php if ($this->_var['data']['status'] == 2): ?>class="y"<?php endif; ?>><a href="#" onclick="reloadpage('<?php
echo parse_wap_url_tag("u:index|learn_activity|"."status=2".""); 
?>','#<?php echo $this->_var['data']['act']; ?>','.learn_activity_box')">活动规则</a></th>
			</tr>
		</table>
	</div>
	<?php endif; ?>
	<div class="blank055"></div>
	
	<?php if ($this->_var['data']['status'] == 0): ?>
	<input id="money" type="hidden" value="<?php echo $this->_var['data']['learn_balance']; ?>" />
		<?php $_from = $this->_var['data']['learn_list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'learn');if (count($_from)):
    foreach ($_from AS $this->_var['learn']):
?>
	<div class="new_version">
		<ul>
			<li>
				<div class="bb1 toptitle">
					<?php if ($this->_var['learn']['status'] == 0): ?>
					<span class="state_ico">未开始</span>
					<span class="name"><?php echo $this->_var['learn']['name']; ?></span>
					<?php endif; ?>
					<?php if ($this->_var['learn']['status'] == 1): ?>
					<span class="state_ico">在售</span>
					<span class="name"><?php echo $this->_var['learn']['name']; ?></span>
					<?php endif; ?>
					<?php if ($this->_var['learn']['status'] == 2 || $this->_var['learn']['status'] == 3): ?>
					<span class="state_ico">已售完</span>
					<span class="name"><?php echo $this->_var['learn']['name']; ?></span>
					<?php endif; ?>
				</div>
				<div class="middlecon w_b">
					<div class="w_b_f_1 tc">
						<p class="name">预期年化收益率</p>
						<p class="con"><span class="ea544a"><?php echo $this->_var['learn']['rate']; ?></span>%</p>
					</div>
					<div class="w_b_f_1 tc">
						<p class="name">产品期限</p>
						<p class="con"><?php echo $this->_var['learn']['time_limit']; ?> 天</p>
					</div>
					<div class="w_b_f_1">
						<?php if ($this->_var['learn']['status'] == 0): ?>	
							<input type="button" class="sub_b sub_b_gray f_r" value="未开始" >
						<?php endif; ?>
						<?php if ($this->_var['learn']['status'] == 1): ?>	
							<?php if ($this->_var['data']['learn_balance'] > 0): ?>
							<input type="button" class="sub_b tz_link_btn" data-id="<?php echo $this->_var['learn']['id']; ?>" value="立即投资">
							<?php else: ?>
							<input type="button" class="sub_b sub_b_gray f_r" value="立即投资" >
							<?php endif; ?>
						<?php endif; ?>
	            		<?php if ($this->_var['learn']['status'] == 2 || $this->_var['learn']['status'] == 3): ?>	
						    <input type="button" class="sub_b sub_b_gray f_r" value="已结束" >
						<?php endif; ?>
					</div>
				</div>
			</li>
		</ul>
	</div>
	<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>
	<?php endif; ?>
	
	<?php if ($this->_var['data']['status'] == 1): ?>
	 	<?php if ($this->_var['data']['rrruid'] == 0 || $this->_var['data']['user_id'] > 0): ?>
	 	<div style="padding-left:10px;padding-right:10px;">
	 	<table width="100%">
			<tr bgcolor="#fff">
				<td width="130" style="padding-left:10px;height:35px;">邀请好友总数</td>
				<td align="left"><?php echo $this->_var['data']['referral_user']; ?> 个</td>
			</tr>
			<tr bgcolor="#fff">
				<td width="130" style="padding-left:10px;height:35px;">邀请获得体验金</td>
				<td align="left"><?php 
$k = array (
  'name' => 'format_price',
  'v' => $this->_var['data']['learn_invite'],
);
echo $k['name']($k['v']);
?></td>
			</tr>
			
		</table>
	 	</div>
		<?php endif; ?>
		<div class="blank055"></div>
		<div class="im refers" style="padding:10px;">
			<div>
				<h4>我的邀请二维码：</h4>
				<div class="blank5"></div>
				<div align="center"><img src="<?php echo $this->_var['data']['share_url_img']; ?>" width="300" /></div>
				<div class="blank"></div>
				<div style="background-color:#fff;padding-left:10px;padding-right:10px;">
					<?php echo $this->_var['data']['activity_info']['content']; ?>
				</div>
			</div>
		</div>
			
	<?php endif; ?>
	
	<?php if ($this->_var['data']['status'] == 2): ?>
	<div class="xstyconT main bg_fff">
    <div class="xstyconTH">
        <p class="xstyconTHIn"><?php echo $this->_var['data']['activity_info']['title']; ?></p>
    </div>
	<div class="article_info">
	     <?php echo $this->_var['data']['activity_info']['content']; ?>
		</div>
	</div>
		
	<?php endif; ?>
	
	<div class="blank15"></div>
</div>

<?php echo $this->fetch('./inc/footer.html'); ?>