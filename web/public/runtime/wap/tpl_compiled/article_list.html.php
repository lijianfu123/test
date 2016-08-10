<?php if ($_REQUEST['is_ajax'] != 1): ?>
<?php echo $this->fetch('./inc/header.html'); ?>	
<div class="page" id='<?php echo $this->_var['data']['act']; ?>'>
<?php
	$this->_var['back_url'] = wap_url("index","init#index");
	$this->_var['back_page'] = "#init";
	$this->_var['back_epage'] = $_REQUEST['epage']=="" ? "#init" : "#".$_REQUEST['epage'];
?>
<?php echo $this->fetch('./inc/title.html'); ?>
<div class="content infinite-scroll pull-to-refresh-content article_list_content"  data-distance="<?php echo $this->_var['data']['rs_count']; ?>"  all_page="<?php echo $this->_var['data']['page']['page_total']; ?>" ajaxurl="<?php
echo parse_wap_url_tag("u:index|article_list#index|"."".""); 
?>">
<!-- 这里是页面内容区 -->
<!--文章列表-->
<div class="content-inner">
	<div class="list-block">
	   <ul>
<?php endif; ?>
<!-- 默认的下拉刷新层 -->
    <div class="pull-to-refresh-layer" all_page="<?php echo $this->_var['data']['page']['page_total']; ?>" >
        <div class="preloader"></div>
        <div class="pull-to-refresh-arrow"></div>
    </div>
			<?php $_from = $this->_var['data']['list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array'); }; $this->push_vars('', 'item');if (count($_from)):
    foreach ($_from AS $this->_var['item']):
?>
			<li>
	            <a href="#" onclick="RouterURL('<?php
echo parse_wap_url_tag("u:index|show_article|"."id=".$this->_var['item']['id']."".""); 
?>','#show_article',2);" class="item-link item-content">
	              <div class="item-inner w_b">
	                <div class="item-title w_b_f_1"><?php echo $this->_var['item']['title']; ?></div>
	              </div>
	            </a>
	         </li>
			<?php endforeach; endif; unset($_from); ?><?php $this->pop_vars();; ?>			 
<?php if ($_REQUEST['is_ajax'] != 1): ?>
		</ul>
	</div>
</div>
<?php echo $this->fetch('./inc/footer.html'); ?>
<?php endif; ?>




