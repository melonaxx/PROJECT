<?xml version="1.0" encoding="utf-8" ?>
<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<!-- UTF8 编码 -->

<xsl:include href="/style/header.xsl" />

<xsl:template name="text">
<script type="text/javascript" src="/js_encode/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript" src="/js_encode/bootstrap-datetimepicker.zh-CN.js"></script>
<script type="text/javascript" src="/js_encode/stock_inventory_allocation.js"></script>
<link rel="stylesheet" href="/style/bootstrap-datetimepicker.min.css"></link>
<div class="mainBody">
	<ul class="nav nav-tabs nav_tabs_margin">
		<li class="active"><a href="/stock/stock_inventory_allocation.php">调拨单</a></li>
		<li><a href="/stock/stock_allocation_list.php">调拨单列表</a></li>
	</ul>
	<form class="form-inline" method="post" action="/stock/stock_inventory_allocation.php">
		<div class="allocationMsg">
			<div class="form-group">
				<label>日期：</label>
				<xsl:element name="INPUT">
					<xsl:attribute name="type">text</xsl:attribute>
					<xsl:attribute name="name">date</xsl:attribute>
					<xsl:attribute name="value"><xsl:value-of select="/html/Body/date"/></xsl:attribute>
					<xsl:attribute name="placeholder">默认显示当日</xsl:attribute>
					<xsl:attribute name="class">form-control input-sm</xsl:attribute>
				</xsl:element>
			</div>
			<div class="form-group">
				<label>发货仓库：</label>
				<select name="delivery_warehouse" class="deliverywarehouse form-control input-sm">
					<!-- <xsl:attribute name="data-toggle">tooltip</xsl:attribute>
					<xsl:attribute name="data-placement">bottom</xsl:attribute>
					<xsl:attribute name="title">必选</xsl:attribute> -->
					<option value='' style='display:none'>请选择发货仓库</option>
					<xsl:for-each select="/html/Body/store_info/ul/li">
						<xsl:element name="OPTION">
						   <xsl:attribute name="value"><xsl:value-of select="value"/></xsl:attribute>
						   <xsl:value-of select="text"/>
						</xsl:element>
					</xsl:for-each>
				</select>
			</div>
			<div class="form-group">
				<label>收货仓库：</label>
				<select name="warehouse_receipt" class="warehousereceipt form-control input-sm">
					<!-- <xsl:attribute name="data-toggle">tooltip</xsl:attribute>
					<xsl:attribute name="data-placement">bottom</xsl:attribute>
					<xsl:attribute name="title">必选</xsl:attribute> -->
					<option value='' style='display:none;'>请选择收货仓库</option>
					<xsl:for-each select="/html/Body/ware/ul/li">
						<xsl:element name="OPTION">
						   <xsl:attribute name="value"><xsl:value-of select="value"/></xsl:attribute>
						   <xsl:value-of select="text"/>
						</xsl:element>
					</xsl:for-each>
				</select>
			</div>
		</div>
		<div class="goodsMsg">
			<h4>商品信息</h4>
			<div class="table_operate_block">
				<input class="btn btn-default btn-sm btn_margin goodsAdd" type="button" value="添加" />
				<input class="btn btn-default btn-sm goodsDelete" type="button" value="删除" />
			</div>
			<table width="1180" class="table table-bordered table-hover">
				<tr>
					<th class="table_th_number">序号</th>
					<th class="table_th_checkbox center"><input type="checkbox" name="select_all" /></th>
					<th width="150">搜索</th>
					<th width="300">商品名称与规格</th>
					<th width="150">数量</th>
					<th width="500">备注</th>
				</tr>
				<tr>
					<td class="center">1</td>
					<td class="center"><input type="checkbox" name="select_one" /></td>
					<td>
						<xsl:element name="INPUT">
							<xsl:attribute name="type">text</xsl:attribute>
							<xsl:attribute name="class">form-control input-sm form_no_border find</xsl:attribute>
						</xsl:element>
					</td>
					<td>
						<select name="product_id[]" class="form-control input-sm form_no_border name">
							<xsl:for-each select="/html/Body/status/ul/li">
								<xsl:element name="OPTION">
								   <xsl:attribute name="value"><xsl:value-of select="value"/></xsl:attribute>
								   <xsl:value-of select="text"/>
								</xsl:element>
							</xsl:for-each>
						</select>
					</td>
					<td>
						<xsl:element name="INPUT">
							<xsl:attribute name="type">text</xsl:attribute>
							<xsl:attribute name="name">total[]</xsl:attribute>
							<xsl:attribute name="class">form-control input-sm form_no_border</xsl:attribute>
						</xsl:element>
					</td>
					<td>
						<xsl:element name="INPUT">
							<xsl:attribute name="type">text</xsl:attribute>
							<xsl:attribute name="name">body[]</xsl:attribute>
							<xsl:attribute name="class">form-control input-sm form_no_border</xsl:attribute>
						</xsl:element>
					</td>
				</tr>
			</table>
		</div>
		<p>
			<input class="btn btn-default btn-sm btn_margin" name="send" type="submit" value="提交" />
			<input class="btn btn-default btn-sm" type="reset" value="重置" />
		</p>
	</form>
</div>
</xsl:template>

</xsl:stylesheet>