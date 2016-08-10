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
<img src="../about_01.png"  width="100%" />
<img src="../about_02.png"  width="100%" />
<img src="../about_03.png"  width="100%" />
<img src="../about_04.png"  width="100%" />
<div class="integral_container">

</div>	

 
<?php echo $this->fetch('./inc/footer.html'); ?>
<?php endif; ?>
