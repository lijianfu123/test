<?php if ($_REQUEST['is_ajax'] != 1): ?>
<?php echo $this->fetch('./inc/header.html'); ?>	
<div class="page" id='<?php echo $this->_var['data']['act']; ?>'>
<?php
	$this->_var['back_url'] = wap_url("index","init#index");
	$this->_var['back_page'] = "#init";
	$this->_var['back_epage'] = $_REQUEST['epage']=="" ? "#init" : "#".$_REQUEST['epage'];
?>
<?php echo $this->fetch('./inc/title.html'); ?>
<div class="content">
<!--注册页面-->
	<?php endif; ?>


<div class="register_login" id="mb_register">	
		<div class="bg_fff register_login_pd">
			<div class="bb1 bor height w_b">
				 <div class="name">用 户 名</div>
				 <input class="r_l_input w_b_f_1" id="user_name" name="user_name" type="text" placeholder="请输入您的用户名">
			</div>
			<div class="bb1 bor height w_b">
				 <div class="name">输入密码</div>
				 <input class="r_l_input w_b_f_1" id="user_pwd" name="user_pwd" type="password" placeholder="请输入您的密码">
			</div>
			<div class="bb1 bor height w_b">
				 <div class="name">确认密码</div>
				 <input class="r_l_input w_b_f_1" id="user_pwd_confirm" name="user_pwd_confirm" type="password" placeholder="请再次输入您的密码">
			</div>
			<div class="bb1 bor height w_b">
				 <div class="name">手 机 号</div>
				 <input class="r_l_input w_b_f_1" id="phone" name="phone" type="text" placeholder="请输入您的手机号码">
			</div>
			<div class="bb1 bor height w_b">
				 <div class="name">验 证 码</div>
				 <input class="r_l_input w_b_f_1" id="mobile_code" name="mobile_code" type="text" placeholder="请输入验证码">
				 <div class="ui-button_getcode_parent" style="  width:6rem;"><input class="ui-button_getcode " id="getcode" type="button" value="获取验证码"></div>
			</div>
			<div class=" bor height w_b <?php if ($this->_var['data']['wap_referer']): ?>hide<?php endif; ?>">
				 <div class="name">推 荐 人</div>
				  <input class="r_l_input w_b_f_1" id="referer" name="referer" type="text"  value="<?php echo $this->_var['data']['wap_referer']; ?>" placeholder="请输入推荐人手机号/用户名">
			</div>
		</div>
		<div class="register_login_pd">
			<input class="ui-button_login r_l_but" type="submit" name="commit" id="signup-submit" value="注册">
		</div>		
</div>
<?php if ($_REQUEST['is_ajax'] != 1): ?>
<?php echo $this->fetch('./inc/footer.html'); ?>
<?php endif; ?>








