<?php if ($_REQUEST['is_ajax'] != 1): ?>
<?php echo $this->fetch('./inc/header.html'); ?>	
<?php
    $this->_var['hide_back'] = 1;
	
	$this->_var['back_url'] = wap_url("index","init#index");
	$this->_var['back_page'] = "#init";
	$this->_var['back_epage'] = $_REQUEST['epage']=="" ? "#init" : "#".$_REQUEST['epage'];
?>
<div class="page" id='<?php echo $this->_var['data']['act']; ?>'>
<?php echo $this->fetch('./inc/title.html'); ?>
<div class="content register_login_content">
<?php endif; ?>	
<!-- 这里是页面内容区 -->
<div class="register_login" id="mb_register">
		<div class="bg_fff register_login_pd">
			<div class="bb1 bor height w_b">
				 <div class="name">姓名</div>
				 <input class="r_l_input w_b_f_1" id="real_name" name="real_name" type="text" placeholder="输入真实姓名"/>		     
			</div>
			<div class="bb1 bor height w_b">
				 <div class="name">身份证</div>
				 <input class="r_l_input w_b_f_1" id="idno" name="idno" type="text" placeholder="输入身份证号码"/>				 
			</div>
		</div>
		<div class="register_login_pd">
			<input class="ui-button_login r_l_but" type="submit" name="commit" id="idno_submit" value="确定"/>
		</div>	
</div>
<?php if ($_REQUEST['is_ajax'] != 1): ?>
<?php echo $this->fetch('./inc/footer.html'); ?>
<?php endif; ?>




