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
<!-- <link rel="stylesheet" type="text/css" href="./css/activity_turn.css" /> -->
<style>
	/* 大转盘样式 */
.banner{display:block;width:95%;margin-left:auto;margin-right:auto;margin-bottom: 20px;}
.banner .turnplate{display:block;width:100%;position:relative;}
.banner .turnplate canvas.item{width:100%;}
.banner .turnplate img.pointer{position:absolute;width:31.5%;height:42.5%;left:34.6%;top:23%;}
.header_bg_01{
		height: 1368px;
    background: url(./images/activity_turn/activity_01.jpg) no-repeat center;
        background-color: #C81F4A;
        background-image: url("./images/activity_turn/activity_01.jpg");
        background-repeat: no-repeat;
        background-attachment: scroll;
        background-position: center center;
        background-clip: border-box;
        background-origin: padding-box;
        background-size: auto auto;
	}
	.he{width:100%;height:10px; background-color: #C81F4A;}
	/* .act_wid{width:640px;height:100%;margin-left:auto;margin-right:auto;} */
	.act_turn1{width:188px;height:200px;margin-left:80px;float:left;}
	.act_turn2{width:200px;height:200px;float:left;}
	.tj{width:130px;height:30px;margin-left:10px;background-color:#F1F1F1;
		border-radius:10px 10px 10px 10px;
			cursor:pointer; 
			margin-top:10px;
		}
	.inp{
		width:160px;height:30px;
		border: 1px solid #CDC28D;
		background-color: #F2F9FD; 
		padding-top: 4px; 
		font-family: Arial, Helvetica, sans-serif; 
		font-size: 15px;
		padding-left: 10px;
		
	}
</style>
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
<div class="header_bg_01">
	<!-- <div class="act_wid"> -->
		<div style="height:180px;width:100%;"></div>
		<div class="act_turn1">
			<div id="activity_turn" >
				<img src="./images/activity_turn/1.png" id="shan-img" style="display:none;" />
				<img src="./images/activity_turn/2.png" id="sorry-img" style="display:none;" />
				<div class="banner">
					<div class="turnplate" style="background-image:url(./images/activity_turn/turnplate-bg.png);background-size:100% 100%;">
						<canvas class="item" id="wheelcanvas" width="422px" height="422px"></canvas>
						<img class="pointer" src="./images/activity_turn/turnplate-pointer.png"/>
					</div>
				</div>
			</div>
			<div class="act_turn2">
			<input type="phone" placeholder="请输入抽奖手机号码" class="inp"><br>
			<button type="button" class="tj" >提交</button>
			<div id="lottery_show" index="<?php echo $this->_var['data']['is_l']; ?>" style="width:230px;margin:0 auto;">
				<div id="lottery_res" style="width:230px;color:#FAFF03;">最新抽奖结果：恭喜你抽到<?php echo $this->_var['data']['new_things']; ?>红包</div>
			</div>
		</div>
		</div>
		
	<!-- </div> -->
</div>
</div>

<!-- 代码 结束 -->
<div class="integral_container">

</div>	
<script type='text/javascript' src='./js/activity_turn/jquery-1.8.3.min.js' charset='utf-8'></script>
<script type='text/javascript' src='./js/activity_turn/awardRotate.js' charset='utf-8'></script>
<!-- <?php echo $this->fetch('./activity_turn_js.html'); ?> -->
<?php echo $this->fetch('./inc/footer.html'); ?>
<?php endif; ?>
