<?php if ($_REQUEST['is_ajax'] != 1): ?>
<?php echo $this->fetch('./inc/header.html'); ?>	
<div class="page" id='<?php echo $this->_var['data']['act']; ?>'>
<?php
	$this->_var['back_url'] = wap_url("index","article_list#index");
	$this->_var['back_page'] = "#article_list";
	$this->_var['back_epage'] = $_REQUEST['epage']=="" ? "#article_list" : "#".$_REQUEST['epage'];
?>
<?php echo $this->fetch('./inc/title.html'); ?>
<div class="content show_article">
	<?php endif; ?>
<!-- 这里是页面内容区 -->
<div class="card-container">
    <div class="card">
        <div class="card-header"><?php echo $this->_var['data']['title']; ?></div>
        <div class="card-content">
            <div class="card-content-inner">
                	<?php echo $this->_var['data']['content']; ?>
            </div>
        </div>
    </div>
</div>
<?php if ($_REQUEST['is_ajax'] != 1): ?>
<?php echo $this->fetch('./inc/footer.html'); ?>
	<?php endif; ?>




