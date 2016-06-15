<?xml version="1.0" encoding="utf-8" ?>
<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<!-- UTF8 编码 -->

<xsl:include href="/style/header.xsl" />

<xsl:template name="text">
<script type="text/javascript" src="/js_encode/jquery.twbsPagination.min.js"></script>
<div class="modal fade" id="confirm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
				<h4 class="modal-title" id="myModalLabel">提示</h4>
			</div>
			<div class="modal-body">
	  			您确定要删除<span class="number">1</span>条数据吗？
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default btn-sm ok" data-dismiss="modal">确定</button>
				<button type="button" class="btn btn-default btn-sm cancel" data-dismiss="modal">取消</button>
			</div>
		</div>
	</div>
</div>
<div class="mainBody">
	
	<!-- <ul id="myTab" class="nav nav-tabs">
		<xsl:element name="LI">
			<xsl:attribute name="class">tab-pane</xsl:attribute>
			<a href="/product/product_specifications_properties.php">规格管理</a>
		</xsl:element>
		<xsl:element name="LI">
			<xsl:attribute name="class">tab-pane</xsl:attribute>
			<a href="/product/product_attribute.php">属性</a>
		</xsl:element>
		<xsl:element name="LI">
			<xsl:attribute name="class">tab-pane  active</xsl:attribute>
			<a href="/crm/crm_compan_sales.php">渠道设置</a>
		</xsl:element>
	</ul> -->
	<div class="table_operate_block">
		<h4></h4>
		<input class="btn btn-default btn-sm btn_margin" onclick="MessageBox('crm_add_sales.php', '添加渠道', 240, 75); return false" type="button" value="添加" />
	</div>
	<table class="table table-bordered table-hover">
		<tr>
			<th class="table_th_number">序号</th>
			<th class="table_th_operate_2">操作</th>
			<th style="width:663px">渠道名称</th>
			<th style="width:200px">订单总数</th>
			<th style="width:200px">订单总额</th>
		</tr>
		<xsl:for-each select="/html/Body/sales/ul/li">
			<tr>
				<td class="center"><xsl:value-of select="position()"/></td>
				<td class="center">
				<xsl:element name="A">
					<xsl:attribute name="href">javascript:;</xsl:attribute>
					<xsl:attribute name="class">table_a_operate</xsl:attribute>
					<xsl:attribute name="onclick">MessageBox('/crm/crm_edit_sales.php?id=<xsl:value-of select="id"/>', '修改渠道', 240, 75)</xsl:attribute>
					<xsl:text>修改</xsl:text>
				</xsl:element>
				<xsl:element name="A">
					<xsl:attribute name="href">/crm/crm_delete_sales.php?id=<xsl:value-of select="id"/></xsl:attribute>
					<xsl:attribute name="class">table_a_operate customersDelete delete</xsl:attribute>
					<xsl:text>删除 </xsl:text>
				</xsl:element>
				</td>
				<td class="Unit_Name"><xsl:value-of select="name"/></td>
				<td class="Unit_Name"><xsl:value-of select="order_total"/></td>
				<td class="Unit_Name"><xsl:value-of select="money_total"/></td>
			</tr>
		</xsl:for-each>
	</table>
	<xsl:call-template name="page"></xsl:call-template>
</div>
<script>
	
	//单个删除
	$('.table .delete').click(function () {
		$('#confirm').modal('show');
		var thisHref = $(this).attr('href');
		$('#confirm .ok').click(function () {
			location.href = thisHref;
		});
		return false;
	});
</script>
</xsl:template>

</xsl:stylesheet>
