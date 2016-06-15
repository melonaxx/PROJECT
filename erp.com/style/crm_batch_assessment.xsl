<?xml version="1.0" encoding="utf-8" ?>
<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<!-- UTF8 编码 -->
<xsl:include href="/style/header.xsl" />
<xsl:template name="text">
	<!-- 时间插件 -->
	<script type="text/javascript" src="/js_encode/bootstrap-datetimepicker.min.js"></script>
	<script type="text/javascript" src="/js_encode/bootstrap-datetimepicker.zh-CN.js"></script>
	<link rel="stylesheet" href="/style/bootstrap-datetimepicker.min.css"></link>

	<style type="text/css">
		.menu_one{
			height:45px;
			margin-bottom: 10px;
			border-top: 2px solid #D7EEF9;
			background: #EEF8FD;
			line-height: 43px;
			color: #7F7F7F;
		}
		.box_two{
			height:80px;
			border:1px solid #eaeaea;
			margin-bottom:10px;
		}
		.box_two1{
			height:122px;
			border:1px solid #eaeaea;
			margin-bottom:20px;
			margin-top:20px;
		}
		.shop_one{
			height:78px;
			width:368px;
			background:#f6f6f6;
			border-right:1px solid #eaeaea;
		}
		.shop_one1{
			height:120px;
			width:180px;
			<!-- background:#f6f6f6; -->
			border-right:1px solid #eaeaea;
			vertical-align: inherit;
			text-align:center;
		}
		.parameter1{
			width:200px;
			height:120px;
			margin-left:180px;
			margin-top:-118px;
			border-right:1px solid #eaeaea;
			vertical-align: inherit;
			text-align:center;
		}
		.evaluation1{
			width:150px;
			height:120px;
			margin-left:390px;
			border-right:1px solid #eaeaea;
			margin-top:-118px;
			vertical-align: inherit;
			text-align:center;
		}
		.reason1{
			width:500px;
			height:120px;
			border-right:1px solid #eaeaea;
			margin-top:-118px;
			margin-left:540px;
			vertical-align: inherit;
			text-align:center;
		}
		.operation1{
			width:160px;
			height:120px;
			margin-left:1040px;
			margin-top:-118px;
			vertical-align: inherit;
			text-align:center;
		}
		.evaluation1 input{
			margin-top:5px;
		}
		.commodity_introduction{
			height:73px;
			width:240px;
			margin-left:90px;
			margin-top:-100px;
		}
		.img_one{
			width:60px;
			height:60px;
		}
		.parameter{
			width:200px;
			height:78px;
			margin-left:368px;
			margin-top:-78px;
			border-right:1px solid #eaeaea;
		}
		.evaluation{
			width:300px;
			height:78px;
			margin-left:568px;
			border-right:1px solid #eaeaea;
			margin-top:-78px;
		}
		
		.reason{
			width:252px;
			height:78px;
			border-right:1px solid #eaeaea;
			margin-top:-78px;
			margin-left:868px;
		}
		
		.operation{
			width:80px;
			height:78px;
			margin-left:1120px;
			margin-top:-78px;
		}
		

		.search input,select{
			margin-right:20px
		}
	</style>
	<div class="mainBody">
		<ul id="myTab" class="nav nav-tabs nav_tabs_margin">
			<li><a href="/crm/crm_differential_assessment_management.php" >查看评价</a></li>
			<li class="active"><a href="#" data-toggle="tab">批量评价</a></li>
			<li><a href="/crm/crm_assessment_state.php" >评价统计</a></li>
		</ul>
		<div id="myTabContent" class="tab-content">
			
			<div style="margin-left:20px" class="search">
				<input class="btn btn-default btn-sm btn_margin float_left select_all" style="margin-bottom:10px;" type="button" value="本页全选" />
				<span style="" class="float_left margin_home_7">买家昵称：</span>
				<input type="text" title="" data-placement="bottom" data-toggle="tooltip" placeholder="" style="width:120px;" class="form-control float_left" name="user_name" />
				<span style="" class="float_left margin_home_7">交易创建时间开始：</span>
				<input type="text" title="开始时间不能晚于结束时间" data-placement="bottom" data-toggle="tooltip" placeholder="" style="width:120px;margin-right:2px" class="form-control float_left" name="begin_date" />
				<span style="" class="float_left margin_home_7">交易创建时间结束：</span>
				<input type="text" title="结束时间不能早于开始时间" data-placement="bottom" data-toggle="tooltip" placeholder="" style="width:120px;" class="form-control float_left" name="end_date" />
				<span class="float_left margin_home_7">评价状态：</span>
				<select class="form-control input-sm float_left" style="width:160px;" name="RateResult">
					<option selected="true" value="neutral">卖家未评</option>
					<option value="bad">卖家未评，买家已评</option>
					<option value="good">买家未评</option>
					<option value="good">买家未评，卖家已评</option>
					<option value="all">全部</option>
				</select>
		
				<input class="btn btn-default btn-sm btn_margin " style="margin-bottom:10px;margin-left:20px" type="button" value="搜索评价" />
			</div>
			<div class="box_two1">
				<div class="shop_one1">
					<select class="form-control input-sm float_left" style="width:70px; margin-left:50px;margin-top:40px" name="RateResult">
						<option value="all">全部</option>
						<option value="neutral">中评</option>
						<option value="bad">差评</option>
						<option value="good">好评</option>
					</select>
				</div>
				<div class="parameter1">
					<textarea name="" id="" cols="30" rows="5"></textarea>
				</div>
				<div class="evaluation1">
        			<p><input class="btn btn-default btn-sm btn_margin" type="button" id="switch" value="切换模板" onclick="javascript:switchtemp()"/></p>
        			<p><input class="btn btn-default btn-sm btn_margin" type="button" id="save" value="存为模板" onclick="javascript:savetemp()"/></p>
        			<p><input class="btn btn-default btn-sm btn_margin" type="submit" value="评价本页"/></p>       
				</div>
				<div class="reason1">
			        <h3>一键评价</h3>
			        <p>说明："一键评价"可评价所有符合条件订单，“评价本页”只能评价本页内选定的订单</p>
			        <p>
			      		<span>交易成功时间开始</span>   <input type="text" name="start_finished" id="start_finished" value="2015-11-10 19:42:41" onclick="WdatePicker()"/>
			      	</p>
			      	<p>
			      		<span>交易成功时间结束</span>
			     	 	<input type="text" name="end_finished" id="end_finished" value="2015-11-25 19:42:41" onclick="WdatePicker()"/> 
			     	</p>
			        <p>
			        	<input type="button" id="batchall" value="一键评价" onclick="javascript:rateall()" style="color:#ffffff;background-color:#77a51d;"/>
			        </p> 
			        <p><input type="hidden" id="ratestatus" value="RATE_UNSELLER"/></p>
				</div>
				<div class="operation1">
					<h3>操作记录</h3>
					<input type="button" style="margin-top:25px" name="button" class="btn btn-default btn-sm btn_margin" value="查看" id="record" onclick="javascript:window.open( './checkrecord.php',   '_blank ')" />
				</div>
			</div>

		<div class="menu_one">
			<span style="line-height:41px;"><span style="margin-left:20px;">姓名：李先生</span><span style="margin-left:30px;">手机：18212311232</span><span style="margin-left:30px;">支付宝：18212311232</span><span style="margin-left:30px;">订单号：1821231123211232</span><span style="margin-left:30px;">买家：一个人</span></span>
		</div>
		<div class="box_two">
			<div class="shop_one">
				<input style="margin-top:30px;margin-left:20px;" name="select_one" type="checkbox"/>
				<div class="img_one"><img style="margin-top:-15px;margin-left:50px;" src="/images/"/></div>
				<div class="commodity_introduction">
					<p style="color:#36c">Apple/苹果/ iPone 6s Plus 苹果6SPlus手机 5.5 三网/港版/国行现货</p>
					颜色：玫瑰金色；内存：128GB<br/>商品编码：827489
				</div>
			</div>
			<div class="parameter">
				<p style="margin-left:30px;line-height:80px;width:169px;">价格：100<span style="margin-left:16px;">数量：1</span></p>
			</div>
			<div class="evaluation">
				<div style="margin-left:15px;padding-top:15px;"><p><font color="red">差评</font> 2015-10-11 11:00 剩<font color="red">3</font>天<font color="red">6</font>时<font color="red">29</font>分可修改</p><p>手机非常难用，软件下载不了，客服不理人，差</p>评。</div>
			</div>
			<div class="reason">
				<div style="margin-left:15px;padding-top:15px;"><p>备注：客户要退款，不会用。</p>2015-04-12 16:00 售后刘</div>
			</div>
			<div class="operation">
				<div style="padding-top:15px;margin-left:15px"><font color="#36c" onclick="">同步</font><br/><font color="#36c">添加备注</font><br/><font color="#36c">完成</font></div>
			</div>
		</div>
	</div>
	</div>
	<xsl:call-template name="page"></xsl:call-template>
	<script type="text/javascript">
		$(".select_all").click(function(){
			var value = $(this).val();
			if(value == '本页全选'){
				$(this).val("取消全选");
				$("input[name='select_one']").prop("checked",true);
			}else{
				$(this).val("本页全选");
				$("input[name='select_one']").prop("checked",false);
			}
		})

		// 获取时间
		$( "input[name='begin_date'],input[name='end_date']" ).datetimepicker({
			language:'zh-CN',
			format:'yyyy-mm-dd hh:ii',
			autoclose:true,
			minView:0,
		});

			//提交事件
			$('.margin_top').submit(function() {
				$('input[name=begin_date]').trigger('blur');
				$('input[name=end_date]').trigger('blur');
				var endtime = $('input[name=end_date]').val();
		        var starttime = $('input[name=begin_date]').val();
		        //判断会议开始时间不能晚于结束时间
				if(starttime>endtime){
					$('input[name=begin_date]').addClass('border_color');
					$('input[name=begin_date]').tooltip('show');
					$('input[name=end_date]').addClass('border_color');
					$('input[name=end_date]').tooltip('show');
					return false;
				}else{
					$('input[name=begin_date]').removeClass('border_color');
					$('input[name=begin_date]').tooltip('hide');
					$('input[name=end_date]').removeClass('border_color');
					$('input[name=end_date]').tooltip('hide');
					return true;
				};
			});

	</script>
</xsl:template>
</xsl:stylesheet>