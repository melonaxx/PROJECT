<?xml version="1.0" encoding="utf-8" ?>
<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<!-- UTF8 编码 -->

<xsl:include href="/style/header.xsl" />

<xsl:template name="text">
<script type="text/javascript" src="/js_encode/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript" src="/js_encode/bootstrap-datetimepicker.zh-CN.js"></script>
<script type="text/javascript" src="/js_encode/LodopFuncs.js"></script>
<link rel="stylesheet" href="/style/bootstrap-datetimepicker.min.css"></link>
<!-- 控件安装提示 -->
<div id="confirm" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none;width:268px;margin:60px auto">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
			<h4 class="modal-title" id="myModalLabel">提示</h4>
		</div>
		<div class="modal-body" style="margin-left:20px"></div>
		<div class="modal-footer"><button type="button" class="btn btn-default btn-sm ok" data-dismiss="modal">确定</button></div>
	</div>
</div>
<div class="mainBody">
	<ul class="nav nav-tabs nav_tabs_margin">
		<li><a href="/stock/stock_inventory_allocation.php">调拨单</a></li>
		<li class="active"><a href="/stock/stock_allocation_list.php">调拨单列表</a></li>
	</ul>
	<form class="form-inline" action="/stock/stock_allocation_list.php" method="post">
		<div class="table_operate_block">
			<div class="form-group" style="margin:0; float:right;">
				<input type="submit" class="btn btn-default btn-sm btn_margin" value="查询" />
				<input type="button" class="btn btn-default btn-sm" name="clear" value="清空" />
			</div>
			<div class="allocationListMsg">
				<div class="form-group form_small_block float_right">
					<label>到：</label>
					<xsl:element name="INPUT">
						<xsl:attribute name="type">text</xsl:attribute>
						<xsl:attribute name="name">date_end</xsl:attribute>
						<xsl:attribute name="class">form-control input-sm</xsl:attribute>
						<xsl:attribute name="value"><xsl:value-of select="/html/Body/date_end"/></xsl:attribute>
					</xsl:element>
				</div>
				<div class="form-group form_small_block float_right">
					<label>日期：</label>
					<xsl:element name="INPUT">
						<xsl:attribute name="type">text</xsl:attribute>
						<xsl:attribute name="name">date_start</xsl:attribute>
						<xsl:attribute name="class">form-control input-sm</xsl:attribute>
						<xsl:attribute name="value"><xsl:value-of select="/html/Body/date_start"/></xsl:attribute>
					</xsl:element>
				</div>
			</div>
			<!-- <div class="form-group form_small_block float_right">
				<label>商品：</label>
				<xsl:element name="INPUT">
					<xsl:attribute name="type">text</xsl:attribute>
					<xsl:attribute name="name">product</xsl:attribute>
					<xsl:attribute name="placeholder">商品名称或编码</xsl:attribute>
					<xsl:attribute name="class">form-control input-sm</xsl:attribute>
					<xsl:attribute name="value"><xsl:value-of select="product"/></xsl:attribute>
				</xsl:element>
			</div>
			<div class="form-group form_small_block float_right">
				<label>收货仓库：</label>
				<select class="form-control input-sm" name="warehousereceipt">
					<option value="0"></option>
					<xsl:for-each select="/html/Body/store_info/ul/li">
						<xsl:element name="OPTION">
						   <xsl:attribute name="value"><xsl:value-of select="value"/></xsl:attribute>
						   <xsl:value-of select="text"/>
						</xsl:element>
					</xsl:for-each>
				</select>
			</div>
			<div class="form-group form_small_block float_right">
				<label>发货仓库：</label>
				<select class="form-control input-sm" name="deliverywarehouse">
					<option value="0"></option>
					<xsl:for-each select="/html/Body/store_info_ware/ul/li">
						<xsl:element name="OPTION">
						   <xsl:attribute name="value"><xsl:value-of select="value"/></xsl:attribute>
						   <xsl:value-of select="text"/>
						</xsl:element>
					</xsl:for-each>
				</select>
			</div> -->
		</div>
	</form>
	<table width="1180" class="table table-bordered table-hover">
		<tr>
			<th class="table_th_number">序号</th>
			<th class="table_th_operate_2">操作</th>
			<th width="140">日期</th>
			<th width="150">发货仓库</th>
			<th width="150">收货仓库</th>
			<th width="150">数量</th>
			<th width="322">备注</th>
			<th width="150">操作人</th>
		</tr>
		<xsl:for-each select="/html/Body/storemove/ul/li">
			<tr>
				<td class="center"><xsl:value-of select="position()"/></td>
				<td class="center">
					<xsl:element name="SPAN">
						<xsl:attribute name="style">cursor:pointer</xsl:attribute>
						<xsl:attribute name="onclick">MessageBox('/stock/stock_single_list_details.php?id=<xsl:value-of select="id"/>', '详细', 600, 280)</xsl:attribute>
						<xsl:attribute name="class">table_a_operate</xsl:attribute>
						<xsl:text>详细</xsl:text>
					</xsl:element>
					<xsl:element name="A">
						<xsl:attribute name="href">javascript:;</xsl:attribute>
						<xsl:attribute name="class">doPrint</xsl:attribute>
						<xsl:attribute name="id"><xsl:value-of select="id"/></xsl:attribute>
						<xsl:text>打印</xsl:text>
					</xsl:element>
				</td>
				<td><xsl:value-of select="action_date"/></td>
				<td><xsl:value-of select="output_store_id"/></td>
				<td><xsl:value-of select="input_store_id"/></td>
				<td><xsl:value-of select="total"/></td>
				<td><xsl:value-of select="body"/></td>
				<td><xsl:value-of select="output_staff_id"/></td>
			</tr>
		</xsl:for-each>
	</table>
		<xsl:if test="/html/Body/storemove/ul/@total = '0'">
			<div class="imgs" style="margin:0 auto; width:100%;text-align:center">
			<div class="img">
				<img src="/images/empty.jpg"  alt=""/>
				<span>没有找到记录，请调整搜索条件。</span>
			</div>
		</div>
		</xsl:if>
	<xsl:call-template name="page"></xsl:call-template>
		<!-- <object  id="LODOP_OB" classid="clsid:2105C259-1E0C-4534-8141-A753534CB4CA" width="0" height="0">
	       <embed id="LODOP_EM" type="application/x-print-lodop" width="0" height="0"></embed>
		</object> -->
</div>
<script type="text/javascript">
	$('.allocationListMsg input[name="date_start"],.allocationListMsg input[name="date_end"]').datetimepicker({
		language:'zh-CN',
		format:'yyyy-mm-dd',
		autoclose:true,
		minView:2,
	});
	$('input[name=clear]').click(function(){
		$('input[name=date_start]').val('');
		$('input[name=date_end]').val('');
	});

	$('.doPrint').click(function(){
		var id=$(this).attr('id');
		$.ajax({
			url: '/stock/stock_single_list_details.php',
			type: 'get',
			data: {action:'print',id:id}
		})
		.done(function(data) {
			var info = JSON.parse(data);
			var html = '&lt;p>日期：'+info.action_date+'&lt;/p>&lt;p>操作人：'+info.output_staff_id+'&lt;/p>&lt;p>发货仓库：'+info.output_store_id+'&lt;/p>&lt;p> 收货仓库：'+info.input_store_id+'&lt;/p>&lt;p>备注：'+info.body+'&lt;/p>&lt;br />&lt;p>商品名称：'+info.product_id+'&lt;/p>&lt;p>商品规格：'+info.format+'&lt;/p>&lt;p>商品数量：'+info.total+'&lt;/p>&lt;br/>';

			Print(html);
		})
	});

	function Print(html){
		var LODOP=getLodop(document.getElementById('LODOP_OB'),document.getElementById('LODOP_EM'));
		LODOP.PRINT_INIT();
		LODOP.ADD_PRINT_TEXT(40,0,'100%','30','库存调拨单');
		LODOP.SET_PRINT_STYLEA(0,"FontSize",16);
		LODOP.SET_PRINT_STYLEA(0,"Bold",1);
		LODOP.SET_PRINT_STYLEA(0,"Alignment",2);
		LODOP.SET_PRINT_PAGESIZE(3,2100,'','');
		LODOP.ADD_PRINT_HTM('90','80','90%','100%',html)
		<!-- LODOP.PRINT_DESIGN(); -->
		LODOP.PREVIEW();
	}
</script>
</xsl:template>

</xsl:stylesheet>