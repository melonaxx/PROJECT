<?xml version="1.0" encoding="utf-8" ?>
<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<!-- UTF8 编码 -->

<xsl:include href="/style/header.xsl" />

<xsl:template name="text">

<script type="text/javascript" src="/js_encode/product_list.js"></script>
<script type="text/javascript">
	var sheng = '<xsl:value-of select="/html/Body/sheng" />';
	var shi = '<xsl:value-of select="/html/Body/shi" />';
	var xian = '<xsl:value-of select="/html/Body/xian" />';
	
	$(function(){
		<!-- 这是线上订单和线下订单的查询 -->
		var sell_state = '<xsl:value-of select="/html/Body/sell_state" />';
		if(sell_state == "All"){
			$('.butt .butt_name').html("全部");
			$('.butt input[name=sell_state]').val("All");
		}else if(sell_state == "Onsale"){
			$('.butt .butt_name').html("在售");
			$('.butt input[name=sell_state]').val("Onsale");
		}else if(sell_state == "Soldout"){
			$('.butt .butt_name').html("下架");
			$('.butt input[name=sell_state]').val("Soldout");
		}else if(sell_state == "Stop"){
			$('.butt .butt_name').html("停产");
			$('.butt input[name=sell_state]').val("Stop");
		}else if(sell_state == "Stockout"){
			$('.butt .butt_name').html("缺货");
			$('.butt input[name=sell_state]').val("Stockout");
		}

		$('.dropdown-menu .sell_state').click(function(){
			var v = $(this).text();
			if(v == "全部"){
				$('.butt .butt_name').html(v);
				$('.butt input[name=sell_state]').val("All");
			}else if(v == "在售"){
				$('.butt .butt_name').html(v);
				$('.butt input[name=sell_state]').val("Onsale");
			}else if(v == "下架"){
				$('.butt .butt_name').html(v);
				$('.butt input[name=sell_state]').val("Soldout");
			}else if(v == "停产"){
				$('.butt .butt_name').html(v);
				$('.butt input[name=sell_state]').val("Stop");
			}else if(v == "缺货"){
				$('.butt .butt_name').html(v);
				$('.butt input[name=sell_state]').val("Stockout");
			}
		})
	})
</script>
<div class="modal fade" id="confirm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
				<h4 class="modal-title" id="myModalLabel">提示</h4>
			</div>
			<div class="modal-body">
	  			您确定要删除<span class="number">1</span>条数据吗?(删除商品将会清空对应仓库,未处理完订单中的的商品不能删除)
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default btn-sm ok" data-dismiss="modal">确定</button>
				<button type="button" class="btn btn-default btn-sm cancel" data-dismiss="modal">取消</button>
			</div>
		</div>
	</div>
</div>

<div class="mainBody">
	<form class="form-inline" method="get" action="">
		<div class="table_operate_block">
			<div class="form-group float_right margin0">
				<div class="input-group">
					<xsl:element name="INPUT">
						<xsl:attribute name="type">text</xsl:attribute>
						<xsl:attribute name="class">form-control input-sm input_search</xsl:attribute>
						<xsl:attribute name="name">find</xsl:attribute>
						<xsl:attribute name="placeholder">输入商品名称</xsl:attribute>
						<xsl:attribute name="value"><xsl:value-of select="/html/Body/find"/></xsl:attribute>
					</xsl:element>
					<span class="input-group-btn">
						<button class="btn btn-default btn-sm" type="submit">搜索</button>
					</span>
				</div>
			</div>
			<a href="/product/product_add.php"><input class="btn btn-default btn-sm btn_margin" type="button" value="添加" /></a>
			<input class="btn btn-default btn-sm supplierDelete" type="button" value="删除" />
			<!-- <input class="btn btn-default btn-sm sale" style="margin-left:800px;" type="button" value="在售" /> -->
		</div>
		<div class="input-group" style="position:relative;margin-top:-42px;margin-left:800px;">
		商品状态：
		<button type="button" class="btn btn-default btn-sm butt" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="width:78px;">
		<span class="butt_name">在售</span>
		<xsl:element name='INPUT'>
			<xsl:attribute name="type">hidden</xsl:attribute>
			<xsl:attribute name="name">sales_status</xsl:attribute>
			<xsl:attribute name="value"><xsl:value-of select="/html/Body/sell_state"/></xsl:attribute>
		</xsl:element>
		<span class="caret"></span></button>
		<ul class="dropdown-menu dropdown-menu-left">
			<li><a href="/product/product_list.php?sales_status=All" class="sell_state">全部</a></li>
			<li><a href="/product/product_list.php" class="sell_state">在售</a></li>
			<li><a href="/product/product_list.php?sales_status=Soldout" class="sales_status">下架</a></li>
			<li><a href="/product/product_list.php?sales_status=Stop" class="sales_status">停产</a></li>
			<li><a href="/product/product_list.php?sales_status=Stockout" class="sales_status">缺货</a></li>
		</ul>
	</div>
	</form>
	<table style="width:1200px" class="table table-bordered table-hover supplierMsg">
		<tr>
			<th class="table_th_number">序号</th>
			<th class="table_th_checkbox center"><input type="checkbox" name="select_all" /></th>
			<th width="48" class="center">操作</th>
			<th width="100" height="100" class="table_th_number center">图片</th>
			<th width="420">名称</th>
			<th width="210">规格</th>
			<th width="100">品牌</th>
			<th width="100">分类</th>
			<th width="100">售价</th>
		</tr>
		<xsl:for-each select="/html/Body/supplierMsg/ul/li">
			<tr>
				<td class="center"><xsl:value-of select="position()"/></td>
				<td class="center"><input type="checkbox" name="select_one" /></td>
				<td class="center">
					<!-- <xsl:element name="A">
						<xsl:attribute name="href">/product/product_edit.php?id=<xsl:value-of select="id" /></xsl:attribute>
						<xsl:attribute name="class">table_a_operate</xsl:attribute>
						<xsl:text>修改</xsl:text>
					</xsl:element> -->
					<xsl:element name="A">
						<xsl:attribute name="href">#</xsl:attribute>
						<xsl:attribute name="class">delete</xsl:attribute>
						<xsl:attribute name="id"><xsl:value-of select="id" /></xsl:attribute>
						<xsl:text>删除</xsl:text>
					</xsl:element>
				</td>
				<td style="text-align:center">
					<xsl:element name="IMG">
						<xsl:attribute name="src"><xsl:value-of select="image"/></xsl:attribute>
						<xsl:attribute name="width">20</xsl:attribute>
						<xsl:attribute name="height">20</xsl:attribute>
						<xsl:attribute name="class">images center</xsl:attribute>
					</xsl:element>
					<xsl:element name="IMG">
						<xsl:attribute name="src"><xsl:value-of select="image"/></xsl:attribute>
						<xsl:attribute name="width">200</xsl:attribute>
						<xsl:attribute name="height">200</xsl:attribute>
						<xsl:attribute name="style">display:none;position:absolute</xsl:attribute>
						<xsl:attribute name="class">bigimg center</xsl:attribute>
					</xsl:element>
				</td>
				<td>
					<xsl:element name="A">
						<xsl:attribute name="href">/product/product_look_see.php?id=<xsl:value-of select="id"/></xsl:attribute>
						<xsl:value-of select="name" />
					</xsl:element>
				</td>
				<td>
					<xsl:value-of select="format_1" /><xsl:value-of select="value_1" />
					<xsl:value-of select="format_2" /><xsl:value-of select="value_2" />
					<xsl:value-of select="format_3" /><xsl:value-of select="value_3" />
					<xsl:value-of select="format_4" /><xsl:value-of select="value_4" />
					<xsl:value-of select="format_5" /><xsl:value-of select="value_5" />
				</td>
				<td><xsl:value-of select="brand_id" /></td>
				<td><xsl:value-of select="category_id" /></td>
				<td><span style="float:left">￥</span><xsl:value-of select="price_display" /></td>

			</tr>
		</xsl:for-each>
	</table>
	<xsl:call-template name="page"></xsl:call-template>
</div>
</xsl:template>

</xsl:stylesheet>
