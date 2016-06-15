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
		.shop_one{
			height:78px;
			width:368px;
			background:#f6f6f6;
			border-right:1px solid #eaeaea;
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
			<li ><a href="/crm/crm_differential_assessment_management.php" >查看评价</a></li>
			<li><a href="/crm/crm_batch_assessment.php">批量评价</a></li>
			<li class="active"><a href="#" data-toggle="tab">评价统计</a></li>
		</ul>
		
		<div id="myTabContent" class="tab-content">
			<div style="margin-left:20px" class="search">
				<span style="" class="float_left margin_home_7">开始时间：</span>
				<input type="text" style="width:150px;margin-right:2px" class="form-control float_left" name="begin_date" />
				<span style="" class="float_left margin_home_7">结束时间</span>
				<input type="text" title="结束时间不能早于开始时间" data-placement="bottom" data-toggle="tooltip" placeholder="" style="width:150px;" class="form-control float_left" name="end_date" />
				<input class="btn btn-default btn-sm btn_margin " style="margin-bottom:10px;margin-left:20px" type="button" value="搜索评价" />
			</div>
			<table class="table tab_select table-bordered table-hover" style="table-layout:fixed; overflow:hidden;">
				<tr>
					<th width="180">统计时间</th>
					<th width="100">中差评总数</th>
					<th width="100">中评数</th>
					<th width="100">差评数</th>
					<th width="100">修改用户总数</th>
					<th width="120">修改中评用户总数</th>
					<th width="120">修改差评用户总数</th>
					<th width="100">修改评价总数</th>
					<th width="100">中评修改总数</th>
					<th width="100">差评修改总数</th>
					<th width="80">好评率</th>
				</tr>
				<tr>
					<td>2014-11.23 22:10</td>
					<td>100</td>
					<td>30</td>
					<td>70</td>
					<td>55</td>
					<td>20</td>
					<td>35</td>
					<td>100</td>
					<td>40</td>
					<td>30</td>
					<td>10%</td>
				</tr>
				<tr>
					<td class="">合计</td>
					<td width=""></td>
					<td width=""></td>
					<td width=""></td>
					<td width=""></td>
					<td width=""></td>
					<td width=""></td>
					<td width=""></td>
					<td width=""></td>
					<td width=""></td>
					<td width=""></td>
				</tr>
			</table>
		
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