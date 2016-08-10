<?php if (!defined('THINK_PATH')) exit();?>

<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Transitional//EN' 'http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd'>
<html>
<head>
<title></title>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=7" />
<link rel="stylesheet" type="text/css" href="__TMPL__Common/style/style.css" />
<script type="text/javascript" src="__TMPL__Common/js/check_dog.js"></script>
<script type="text/javascript" src="__TMPL__Common/js/IA300ClientJavascript.js"></script>
<script type="text/javascript">
 	var VAR_MODULE = "<?php echo conf("VAR_MODULE");?>";
	var VAR_ACTION = "<?php echo conf("VAR_ACTION");?>";
	var MODULE_NAME	=	'<?php echo MODULE_NAME; ?>';
	var ACTION_NAME	=	'<?php echo ACTION_NAME; ?>';
	var ROOT = '__APP__';
	var ROOT_PATH = '<?php echo APP_ROOT; ?>';
	var CURRENT_URL = '<?php echo trim($_SERVER['REQUEST_URI']);?>';
	var INPUT_KEY_PLEASE = "<?php echo L("INPUT_KEY_PLEASE");?>";
	var TMPL = '__TMPL__';
	var APP_ROOT = '<?php echo APP_ROOT; ?>';
	var FILE_UPLOAD_URL = ROOT   + "?m=file&a=do_upload";
	var EMOT_URL = '<?php echo APP_ROOT; ?>/public/emoticons/';
	var MAX_FILE_SIZE = "<?php echo (app_conf("MAX_IMAGE_SIZE")/1000000)."MB"; ?>";
	var LOGINOUT_URL = '<?php echo u("Public/do_loginout");?>';
	var WEB_SESSION_ID = '<?php echo es_session::id(); ?>';
	CHECK_DOG_HASH = '<?php $adm_session = es_session::get(md5(conf("AUTH_KEY"))); echo $adm_session["adm_dog_key"]; ?>';
	var IS_WATER_MARK = <?php echo app_conf("IS_WATER_MARK");?>;
	function check_dog_sender_fun()
	{
		window.clearInterval(check_dog_sender);
		check_dog2();
	}
	var check_dog_sender = window.setInterval("check_dog_sender_fun()",5000);
</script>
<script type="text/javascript" src="__TMPL__Common/js/jquery.js"></script>
<script type="text/javascript" src="__TMPL__Common/js/jquery.timer.js"></script>
<script type="text/javascript" src="__ROOT__/public/runtime/admin/lang.js"></script>
<script type='text/javascript'  src='__ROOT__/admin/public/kindeditor/kindeditor.js'></script>
<script type='text/javascript'  src='__ROOT__/admin/public/kindeditor/lang/zh_CN.js'></script>
<script type="text/javascript" src="__TMPL__Common/js/script.js"></script>
</head>
<body onLoad="javascript:DogPageLoad();">
<div id="info"></div>

<script type="text/javascript">
$(document).ready(function(){
	$("#typeSelect").bind("change",function(){

		$(".type").hide();
		$(".type_" + $(this).val()).show();

	});
	
	$("#pageSelect").bind("change",function(){
		
		//alert($("#typeSelect").val());
		
		if ($(this).val() == "top"){
			$(".type_top").show();
			$("#data_label").html("文章ID号:");
		}else{
			$(".type_top").hide();
			$("#data_label").html("贷款ID号:");
			
			$("#typeSelect").val("1");
			$(".type").hide();
			$(".type_" + $("#typeSelect").val()).show();
		}		
					
	});	
	
	$(".type").hide();
	$(".type_" + $("#typeSelect").val()).show();
	
	if ($("#pageSelect").val() == "top"){
		$(".type_top").show();
		$("#data_label").html("文章ID号:");
	}else{
		$(".type_top").hide();
		$("#data_label").html("贷款ID号:");
	}
});

</script>
<div class="main">
<div class="main_title"><?php echo L("EDIT");?> <a href="<?php echo u("MAdv/index");?>" class="back_list"><?php echo L("BACK_LIST");?></a></div>
<div class="blank5"></div>
<form name="edit" action="__APP__" method="post" enctype="multipart/form-data">
<table class="form" cellpadding=0 cellspacing=0>
	<tr>
		<td colspan=2 class="topTd"></td>
	</tr>
	<tr>
		<td class="item_title"><?php echo L("NAME");?>:</td>
		<td class="item_input">
			<input type="text" class="textbox require" name="name" value="<?php echo ($vo["name"]); ?>"  />
		</td>
	</tr>

	<tr>
		<td class="item_title"><?php echo L("ADV_PAGE");?>:</td>
		<td class="item_input">
			<select name="page"  id='pageSelect'>
				<option value="top" <?php if($vo['page'] == 'top'): ?>selected="selected"<?php endif; ?>>首页广告</option>
				<option value="deal" <?php if($vo['page'] == 'deal'): ?>selected="selected"<?php endif; ?>>首页借款单</option>
			</select>
		</td>
	</tr>
	
	<tr class="type_top">
		<td class="item_title"><?php echo L("ADV_IMAGE");?>:</td>
		<td class="item_input">
			<span>
        <div style='float:left; height:35px; padding-top:1px;'>
			<input type='hidden' value='<?php echo ($vo["img"]); ?>' name='img' id='keimg_h_img_i' />
			<div class='buttonActive' style='margin-right:5px;'>
				<div class='buttonContent'>
					<button type='button' class='keimg ke-icon-upload_image' rel='img'>选择图片</button>
				</div>
			</div>
		</div>
		 <a href='<?php if($vo["img"] == ''): ?>./admin/Tpl/default/Common/images/no_pic.gif<?php else: ?><?php echo ($vo["img"]); ?><?php endif; ?>' target='_blank' id='keimg_a_img' ><img src='<?php if($vo["img"] == ''): ?>./admin/Tpl/default/Common/images/no_pic.gif<?php else: ?><?php echo ($vo["img"]); ?><?php endif; ?>' id='keimg_m_img' width=35 height=35 style='float:left; border:#ccc solid 1px; margin-left:5px;' /></a>
		 <div style='float:left; height:35px; padding-top:1px;'>
			 <div class='buttonActive'>
				<div class='buttonContent'>
					<img src='/admin/Tpl/default/Common/images/del.gif' style='<?php if($vo["img"] == ''): ?>display:none<?php endif; ?>; margin-left:10px; float:left; border:#ccc solid 1px; width:35px; height:35px; cursor:pointer;' class='keimg_d' rel='img' title='删除'>
				</div>
			</div>
		</div>
		</span>
			<span class='tip_span'>[<?php echo L("ADV_PAGE_TIPS");?>]</span>
		</td>
	</tr>
	
	<tr class="type_top">
		<td class="item_title"><?php echo L("ADV_TYPE");?>:</td>
		<td class="item_input">
			<select name="type" id='typeSelect'>
				<option value="1" <?php if($vo['type'] == 1): ?>selected="selected"<?php endif; ?>>文章ID号</option>
				<option value="2" <?php if($vo['type'] == 2): ?>selected="selected"<?php endif; ?>>URL连接</option>
			</select>
		</td>
	</tr>
	
	
	<tr class="type_2 type" <?php if($vo['type'] != 2): ?>style="display:none;"<?php endif; ?>>
		<td class="item_title"><?php echo L("URL");?>:</td>
		<td class="item_input">
			<input type="text" class="textbox " name="url" value="<?php echo ($vo["data"]); ?>" />
			<span class='tip_span'>需要带：http:// 格式的url连接地址</span>
		</td>
	</tr>
	
	
	<tr class="type_1 type" <?php if($vo['type'] != 1): ?>style="display:none;"<?php endif; ?>>
		<td id="data_label" class="item_title">数据ID:</td>
		<td class="item_input">
			<input type="text" class="textbox " name="data_id" value="<?php echo ($vo["data"]); ?>" />
		</td>
	</tr>
	<tr class="type_2 type" <?php if($vo['type'] != 2): ?>style="display:none;"<?php endif; ?>>
		<td class="item_title">使用外置浏览器:</td>
		<td class="item_input">
			<select name="open_url_type">
				<option value="0" <?php if($vo['open_url_type'] == 0): ?>selected="selected"<?php endif; ?>>否</option>
				<option value="1" <?php if($vo['open_url_type'] == 1): ?>selected="selected"<?php endif; ?>>是</option>
			</select>
		</td>
	</tr>
	<tr>
		<td class="item_title"><?php echo L("SORT");?>:</td>
		<td class="item_input">
			<input type="text" class="textbox " name="sort" value="<?php echo ($vo["sort"]); ?>"  />
		</td>
	</tr>	
	<tr>
		<td class="item_title"></td>
		<td class="item_input">
			<!--隐藏元素-->
			<input type="hidden" value="<?php echo ($vo["id"]); ?>" name="id" />
			<input type="hidden" name="<?php echo conf("VAR_MODULE");?>" value="MAdv" />
			<input type="hidden" name="<?php echo conf("VAR_ACTION");?>" value="update" />
			<!--隐藏元素-->
			<input type="submit" class="button" value="<?php echo L("EDIT");?>" />
			<input type="reset" class="button" value="<?php echo L("RESET");?>" />
		</td>
	</tr>
	<tr>
		<td colspan=2 class="bottomTd"></td>
	</tr>
</table>	 
</form>
</div>
</body>
</html>