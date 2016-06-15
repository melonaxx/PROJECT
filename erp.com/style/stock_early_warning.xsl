<?xml version="1.0" encoding="utf-8" ?>
<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<!-- UTF8 编码 -->

<xsl:include href="/style/header.xsl" />

<xsl:template name="text">
<style type="text/css">
	.mainBody {
		position: relative;
	}
	body{
        font-family: STHeiti,Microsoft Yahei, "Helvetica Neue", Helvetica, Arial, sans-serif;
        overflow-x: hidden;
        }
	.scanning {
			width: 390px;
	        height: 40px;
	        cursor: pointer;
	        margin: 60px auto 0px;
		}
	.find1 {
			width:228px;
			height:40px;
			float:left;
			border:1px solid #38f;
		}
	.find2 {
			width:188px;
			height:38px;
			float:left;
			border:0px;
			padding-left:15px;
			line-height:38px;
			font-size:14px;
		}
	.button {
            width: 160px;
            height: 40px;
            border: 0px;
            background-color: #38f;
            cursor: pointer;
            color: #ffffff;
            text-align: center;
            float:left;
    	}
    .button:hover{
        	background-color: #317ef2;
    	}
	.stock {
	        display 	: none;
	        background 	: #fff;
	        font-size 	: 14px;
	        margin-left : 405px;
			width 		: 228px;
	        position   	: absolute;
	        border     	: 1px solid #38f;
		}
	.stock ul li {
			height:26px;
	        padding-left: 15px;
			line-height:26px;
		}
	.stock ul li:hover {
			background-color:#38f;
			color:#fff;
		}
</style>
<script>
	$(function(){
		$("body").css("width", $(window).width());
		//定义仓库下拉框位置变量
		var div_x1 = '';
		var div_y1 = '';
		var div_x2 = '';
		var div_y2 = '';
		var div_h = '';
		//获取鼠标位置
		var x = '';
		var y = '';
		$(document).mousemove(function(e){
		  	x = e.pageX;
		  	y = e.pageY;
		});

		//点击下拉按钮事件 ，显示下拉，并获取位置xy轴值
		$('.find2').next().click(function(){
			$('.stock').css('display','block');
			div_y1 = $('.scanning').offset().top;
			div_x1 = $('.scanning').offset().left;
			div_h  = $('.stock').height();
			div_x2 = div_x1 + 228;
			div_y2 = div_y1 + div_h + 40;
		})
		//点击显示框事件，显示下拉，并获取位置xy轴值
		$('.find2').click(function(){
			$('.stock').css('display','block');
			div_y1 = $('.scanning').offset().top;
			div_x1 = $('.scanning').offset().left;
			div_h  = $('.stock').height();
			div_x2 = div_x1 + 228;
			div_y2 = div_y1 + div_h + 40;
		})
		//选择仓库后，显示选择的仓库名，下拉列表隐藏
		$('.stock li').click(function(){
			var v = $(this).find("input[name='store_id[]']").val();
			var text = $(this).text();
			$('.scanning .find1').find("input[name=id]").prop('value',v);
			$('.scanning .find2').text(text);
			$('.stock').css('display','none');
		})
		//点击下拉框以外的位置时下拉框隐藏
		$(document).click(function(){
			if(parseInt(div_x1)>parseInt(x) || parseInt(x) > parseInt(div_x2) || parseInt(div_y1) > parseInt(y) || parseInt(y) > parseInt(div_y2)){
				$('.stock').css('display','none');
			}
		})

		$('.setup').click(function(){
			var ware = $(this).parents('tr').find('input[name="store_id"]').val();
			var setup = $(this).parents('tr').find('input[name="product_id"]').val();
			MessageBox('/stock/stock_warning_setup.php?ware='+ware+' &amp; setup='+setup, '设置', 430, 130); return false;
		})
	})
</script>

<script type="text/javascript" src="/js_encode/bootstrap-datetimepicker.min.js"></script>
<div class="mainBody">
	<div style="width:100%;height:30px;" >
		<a href="/stock/stock_warning.php">
			<span style="float:right;">预警设置</span>
		</a>
	</div>
	<form action="/stock/stock_early_warning.php" method="get">
	<div class="scanning">
		<div class='find1'>
			<xsl:element name="INPUT">
				<xsl:attribute name="type">hidden</xsl:attribute>
				<xsl:attribute name="name">id</xsl:attribute>
				<xsl:attribute name="value">
					<xsl:value-of select="/html/Body/id"/>
				</xsl:attribute>
			</xsl:element>
			<span  class='find2'><xsl:value-of select="/html/Body/name"/></span>
			<span style="width;40px;height:38px;float:left;">
				<img src="/images/icon_select.png" style="margin-left:13px;margin-top:11px;" />
			</span>
		</div>

		<button class="button" type="submit" >
			<div style="width: 90px;margin: auto;">
	        <img src="/images/sousuo.svg" style="width:16px;height:38px;float:left;" />
	        <div style="font-size: 15px;line-height: 38px;letter-spacing: 2px;display: block;float: left;margin-left: 6px;">开始扫描</div>
	    	</div>
	    </button>
	</div>
	</form>
	<div class="stock" style="border-top:0px;">
		<ul>
			<xsl:for-each select='/html/Body/store_id/ul/li'>
			<li>
				<xsl:element name="INPUT">
					<xsl:attribute name="type">hidden</xsl:attribute>
					<xsl:attribute name="name">store_id[]</xsl:attribute>
					<xsl:attribute name="value"><xsl:value-of select="id"/></xsl:attribute>
				</xsl:element>
				<xsl:value-of select="name"/>
			</li>
			</xsl:for-each>
		</ul>
	</div>
</div>
<div class="customersMsg" style="margin-top:36px;">
	<table width="1180" class="table table-bordered table-hover">
		<tr>
			<th class="table_th_number">序号</th>
			<th class="center" width="66">操作</th>
			<th class="center" width="46">图片</th>
			<th width="200">商品名称</th>
			<th width="182">规格</th>
			<th width="120">商品条码</th>
			<th width="90">实际</th>
			<th width="90">可用</th>
			<th width="90">锁定</th>
			<th width="90">在途</th>
			<th width="90">下限</th>
			<th width="90">上限</th>
		</tr>
		<xsl:for-each select="/html/Body/warning/ul/li">
			<tr>
				<td class="center"><xsl:value-of select="no"/></td>
				<td class="center">
					<a class="setup" href="" type="button">设置</a>
				</td>
				<td class="center">
					<xsl:element name="img">
						<xsl:attribute name="src"><xsl:value-of select="image"/></xsl:attribute>
						<xsl:attribute name="width">20</xsl:attribute>
						<xsl:attribute name="height">20</xsl:attribute>
						<xsl:attribute name="class">smallimg</xsl:attribute>
					</xsl:element>
					<xsl:element name="img">
						<xsl:attribute name="src"><xsl:value-of select="image"/></xsl:attribute>
						<xsl:attribute name="width">200</xsl:attribute>
						<xsl:attribute name="height">200</xsl:attribute>
						<xsl:attribute name="class">bigimg</xsl:attribute>
						<xsl:attribute name="style">display:none;position:absolute</xsl:attribute>
					</xsl:element>
				</td>
				<td><xsl:value-of select="name"/></td>
				<td><xsl:value-of select="format_1"/><xsl:value-of select="format_2"/><xsl:value-of select="format_3"/><xsl:value-of select="format_4"/><xsl:value-of select="format_5"/></td>
				<td><xsl:value-of select="bar_code"/></td>
				<td><xsl:value-of select="total_real"/></td>
				<td><xsl:value-of select="total_available"/></td>
				<td><xsl:value-of select="total_lock"/></td>
				<td><xsl:value-of select="total_way"/></td>
				<xsl:element name="td">
					<xsl:if test="warning = 'lower' ">
						<xsl:attribute name="style">color:red;</xsl:attribute>
					</xsl:if>
					<xsl:value-of select="lower"/>
				</xsl:element>
				<xsl:element name="td">
					<xsl:if test="warning = 'upper' ">
						<xsl:attribute name="style">color:red;</xsl:attribute>
					</xsl:if>
					<xsl:value-of select="upper"/>
				</xsl:element>
				<xsl:element name="INPUT">
					<xsl:attribute name="type">hidden</xsl:attribute>
					<xsl:attribute name="name">store_id</xsl:attribute>
					<xsl:attribute name="value"><xsl:value-of select="store_id"/></xsl:attribute>
				</xsl:element>
				<xsl:element name="INPUT">
					<xsl:attribute name="type">hidden</xsl:attribute>
					<xsl:attribute name="name">product_id</xsl:attribute>
					<xsl:attribute name="value"><xsl:value-of select="product_id"/></xsl:attribute>
				</xsl:element>
			</tr>
		</xsl:for-each>
	</table>
	<xsl:if test="/html/Body/warning/ul/@num = '0'">
		<div class="imgs" style="margin:0 auto; width:100%;text-align:center">
		<div class="img">
			<img src="/images/empty.jpg"  alt=""/>
			<span>没有找到记录，请调整搜索条件。</span>
		</div>
	</div>
	</xsl:if>
	<!-- <xsl:call-template name="page"></xsl:call-template> -->
</div>
	<script type="text/javascript">
		//鼠标指示显示大图
		$('.smallimg').hover(function(){
			var t = $(this).position().top - 90 + 'px';
			var l = $(this).position().left + 20 + 'px';
			$(this).next().css("display","block");
			$(this).next().css("top",t);
			$(this).next().css("left",l);
		},function(){
			$(this).next().css("display","none");
		})
	</script>
</xsl:template>

</xsl:stylesheet>