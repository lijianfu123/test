<script>
var jQuery_New = $.noConflict(true);
var is_l = jQuery_New('#lottery_show').attr('index');
if(is_l == 0){
	jQuery_New('#lottery_show').hide();	
}else{
	jQuery_New('#lottery_show').show();	
}

var turnplate={
		restaraunts:[],				//大转盘奖品名称
		colors:[],					//大转盘奖品区块对应背景颜色
		outsideRadius:192,			//大转盘外圆的半径
		textRadius:155,				//大转盘奖品位置距离圆心的距离
		insideRadius:68,			//大转盘内圆的半径
		startAngle:0,				//开始角度
		
		bRotate:false				//false:停止;ture:旋转
};
//jquery验证手机号码 
function checkSubmitMobil() { 
	var inp = jQuery_New('.inp').val();
	if (inp == "") { 
		alert("手机号码不能为空！"); 
		jQuery_New('.inp').focus(); 
		return false; 
	} 
	 var reg = /(1[3-9]\d{9}$)/;
	if (!inp.match(reg)) { 
		alert("手机号码格式不正确！");  
		jQuery_New('.inp').focus(); 
		return false; 
	} 
	//手机格式正确后判定该号码是否有效
	/* var ajaxurl = APP_ROOT+"/index.php?ctl=ajax&act=get_phone_effect"; */
	var ajaxurl = WAP_PATH + '/index.php?ctl=get_phone_effect';
	/* var query = new Object(); */	
	var query = newObject();			
	query.post_type = "json";
	query.phone = $.trim(inp);
	jQuery_New.ajax({ 
		url: ajaxurl,
		data:query,
		type: "POST",
		dataType: "json",
		success: function(result){
			console.log(result);
			console.log(result.effect);
			if(result.effect){
				if(result.effect==1)
				{//有效
					 alert('你输入的手机号有效,可以抽奖');
					//登陆用户绑定手机卡号
					
				}
				else if(result.effect == 0)
				{//失效	
					alert('你输入的手机号已失效');
				}else{//无效
					alert('你输入的手机号无效');
				}
			}
			 
		}
	});	
	return true; 
}; 

jQuery_New('.tj').click(function(){
	checkSubmitMobil();	
});

jQuery_New(document).ready(function(){
	//动态添加大转盘的奖品与奖品区域背景颜色
	turnplate.restaraunts = ["28元", "38元", "48元","50元","8元 ", "18元"];
	turnplate.colors = ["#FFF4D6", "#FFFFFF", "#FFF4D6", "#FFFFFF","#FFF4D6", "#FFFFFF"];
	var rotateTimeOut = function (){
		jQuery_New('#wheelcanvas').rotate({
			angle:0,
			animateTo:2160,
			duration:8000,
			callback:function (){
				alert('网络超时，请检查您的网络设置！');
			}
		});
	};

	//旋转转盘 item:奖品位置; txt：提示语;
	var rotateFn = function (item, txt){
		/* var angles = item * (360 / turnplate.restaraunts.length) - (360 / (turnplate.restaraunts.length*2));
		if(angles<270){
			angles = 270 - angles; 
		}else{
			angles = 360 - angles + 270;
		} */
		/* 将数据范围处理 */
		
	    var ajaxurl = WAP_PATH + '/index.php?ctl=activity_turn';
		var query = newObject();			
		query.post_type = "json";
		jQuery_New.ajax({
			url:ajaxurl,
			data:query,
			type:"POST",
			dataType:"json",
			success:function(data){
			 console.log(data.sql); 
				if(data.user_login_status == 0){
					var con = confirm('你还未登录，是否登录？'); 
                    if(con){ 
                        window.location.href = WAP_PATH + '/index.php?ctl=login'; 
                    }
				}else{
					if(data.lottery_status == 0){
						alert('你的抽奖资格已用尽');
					}else if(data.lottery_status == 1){
						var res = data.result;
				    	  res = jQuery_New.parseJSON(res);
				    	  angles = res.angle;
				    	  var p = res.prize; //奖项 
						jQuery_New('#wheelcanvas').stopRotate();
						jQuery_New('#wheelcanvas').rotate({
							angle:0,
							animateTo:angles+3600,
							duration:8000,
							callback:function (){
								if(p == '谢谢参与'){
									alert(p);
									 jQuery_New('#lottery_res').html('抽奖结果：'+p);
									/*jQuery_New.('#lottery_res').show(); */
								}else{
									alert('恭喜获得'+p+'红包');
									jQuery_New('#lottery_res').html('抽奖结果：恭喜你抽到'+p+'红包');
								}
								jQuery_New('#lottery_show').show();	
								turnplate.bRotate = !turnplate.bRotate;
							}
						});
					}else if(data.lottery_status == 2){
						alert('您还未输入手机号码，请输入');
					}else{
						alert('你输入的手机号已失效');
					}
					
				}
				
			}
			,error:function(){
				alert('抽奖暂停');
			}
		}); 
		
	};

	jQuery_New('.pointer').click(function (){
		if(turnplate.bRotate)return;
		turnplate.bRotate = !turnplate.bRotate;
		//获取随机数(奖品个数范围内)
		var item = rnd(1,turnplate.restaraunts.length);
		//奖品数量等于10,指针落在对应奖品区域的中心角度[252, 216, 180, 144, 108, 72, 36, 360, 324, 288]
		rotateFn(item, turnplate.restaraunts[item-1]);
		/* switch (item) {
			case 1:
				rotateFn(252, turnplate.restaraunts[0]);
				break;
			case 2:
				rotateFn(216, turnplate.restaraunts[1]);
				break;
			case 3:
				rotateFn(180, turnplate.restaraunts[2]);
				break;
			case 4:
				rotateFn(144, turnplate.restaraunts[3]);
				break;
			case 5:
				rotateFn(108, turnplate.restaraunts[4]);
				break;
			case 6:
				rotateFn(72, turnplate.restaraunts[5]);
				break;
			case 7:
				rotateFn(36, turnplate.restaraunts[6]);
				break;
			case 8:
				rotateFn(360, turnplate.restaraunts[7]);
				break;
			case 9:
				rotateFn(324, turnplate.restaraunts[8]);
				break;
			case 10:
				rotateFn(288, turnplate.restaraunts[9]);
				break;
		} */
		console.log(item);
	});
});

function rnd(n, m){
	var random = Math.floor(Math.random()*(m-n+1)+n);
	return random;
	
}


//页面所有元素加载完毕后执行drawRouletteWheel()方法对转盘进行渲染
window.onload=function(){
	drawRouletteWheel();
};

function drawRouletteWheel() {    
  var canvas = document.getElementById("wheelcanvas");    
  if (canvas.getContext) {
	  //根据奖品个数计算圆周角度
	  var arc = Math.PI / (turnplate.restaraunts.length/2);
	  var ctx = canvas.getContext("2d");
	  //在给定矩形内清空一个矩形
	  ctx.clearRect(0,0,422,422);
	  //strokeStyle 属性设置或返回用于笔触的颜色、渐变或模式  
	  ctx.strokeStyle = "#FFBE04";
	  //font 属性设置或返回画布上文本内容的当前字体属性
	  ctx.font = '16px Microsoft YaHei';      
	  for(var i = 0; i < turnplate.restaraunts.length; i++) {       
		  var angle = turnplate.startAngle + i * arc;
		  ctx.fillStyle = turnplate.colors[i];
		  ctx.beginPath();
		  //arc(x,y,r,起始角,结束角,绘制方向) 方法创建弧/曲线（用于创建圆或部分圆）    
		  ctx.arc(211, 211, turnplate.outsideRadius, angle, angle + arc, false);    
		  ctx.arc(211, 211, turnplate.insideRadius, angle + arc, angle, true);
		  ctx.stroke();  
		  ctx.fill();
		  //锁画布(为了保存之前的画布状态)
		  ctx.save();   
		  
		  //----绘制奖品开始----
		  ctx.fillStyle = "#E5302F";
		  var text = turnplate.restaraunts[i];
		  var line_height = 17;
		  //translate方法重新映射画布上的 (0,0) 位置
		  ctx.translate(211 + Math.cos(angle + arc / 2) * turnplate.textRadius, 211 + Math.sin(angle + arc / 2) * turnplate.textRadius);
		  
		  //rotate方法旋转当前的绘图
		  ctx.rotate(angle + arc / 2 + Math.PI / 2);
		  
		  /** 下面代码根据奖品类型、奖品名称长度渲染不同效果，如字体、颜色、图片效果。(具体根据实际情况改变) **/
		  if(text.indexOf("M")>0){//流量包
			  var texts = text.split("M");
			  for(var j = 0; j<texts.length; j++){
				  ctx.font = j == 0?'bold 20px Microsoft YaHei':'16px Microsoft YaHei';
				  if(j == 0){
					  ctx.fillText(texts[j]+"M", -ctx.measureText(texts[j]+"M").width / 2, j * line_height);
				  }else{
					  ctx.fillText(texts[j], -ctx.measureText(texts[j]).width / 2, j * line_height);
				  }
			  }
		  }else if(text.indexOf("M") == -1 && text.length>6){//奖品名称长度超过一定范围 
			  text = text.substring(0,6)+"||"+text.substring(6);
			  var texts = text.split("||");
			  for(var j = 0; j<texts.length; j++){
				  ctx.fillText(texts[j], -ctx.measureText(texts[j]).width / 2, j * line_height);
			  }
		  }else{
			  //在画布上绘制填色的文本。文本的默认颜色是黑色
			  //measureText()方法返回包含一个对象，该对象包含以像素计的指定字体宽度
			  ctx.fillText(text, -ctx.measureText(text).width / 2, 0);
		  }
		  
		  //添加对应图标
		  if(text.indexOf("闪币")>0){
			  var img= document.getElementById("shan-img");
			  img.onload=function(){  
				  ctx.drawImage(img,-15,10);      
			  }; 
			  ctx.drawImage(img,-15,10);  
		  }else if(text.indexOf("谢谢参与")>=0){
			  var img= document.getElementById("sorry-img");
			  img.onload=function(){  
				  ctx.drawImage(img,-15,10);      
			  };  
			  ctx.drawImage(img,-15,10);  
		  }
		  //把当前画布返回（调整）到上一个save()状态之前 
		  ctx.restore();
		  //----绘制奖品结束----
	  }     
  } 
} 
</script>
