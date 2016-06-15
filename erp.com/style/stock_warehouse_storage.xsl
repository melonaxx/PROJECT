<?xml version="1.0" encoding="utf-8" ?>
<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<!-- UTF8 编码 -->

<xsl:include href="/style/header.xsl" />

<xsl:template name="text">
<script type="text/javascript" src="/js_encode/stock_warehouse_storage.js"></script>
<div class="mainBody">
	<ul id="myTab" class="nav nav-tabs nav_tabs_margin">
	   <li class="active"><a href="/stock/stock_warehouse.storage.php">手动出库入库</a></li>
	   <li><a href="/stock/stock_manual_delivery_of_storage.php">出库入库记录</a></li>
	</ul>
	<form class="form-inline" action="/stock/stock_warehouse_storage.php" method="post">
		<div class="warehouseMsg" style="clear:both;">
			<div class="form-group">
				<lable>出库入库：</lable>
				<select class="form-control input-sm warehouse" name="type" >
				<!-- <xsl:attribute name="data-toggle">tooltip</xsl:attribute>
					<xsl:attribute name="data-placement">bottom</xsl:attribute>
					<xsl:attribute name="title">必选</xsl:attribute> -->
					<option value='' style='display:none;'>请选择</option>
					<xsl:for-each select="/html/Body/storage/li">
						<xsl:element name="OPTION">
							<xsl:attribute name="value"><xsl:value-of select="value"/></xsl:attribute>
							<xsl:value-of select="text"/>
						</xsl:element>
					</xsl:for-each>
				</select>
			</div>
			<div class="form-group">
				<lable>选择仓库：</lable>
				<select class="form-control input-sm ware" name="store_id">
					<!-- <xsl:attribute name="data-toggle">tooltip</xsl:attribute>
					<xsl:attribute name="data-placement">bottom</xsl:attribute>
					<xsl:attribute name="title">必选</xsl:attribute> -->
					<option value='' style='display:none;'>请选择仓库</option>
					<xsl:for-each select="/html/Body/store/ul/li">
						<xsl:element name="OPTION">
							<xsl:attribute name="value"><xsl:value-of select="value"/></xsl:attribute>
							<xsl:value-of select="text"/>
						</xsl:element>
					</xsl:for-each>
				</select>
			</div>
			<h4>商品信息</h4>
			<p class="table_operate_block">
				<input class="btn btn-default btn-sm btn_margin usAdd" type="button" value="添加"/>
				<input class="btn btn-default btn-sm btn_margin delete" type="button" value="删除"/>
			</p>
			<div>
				<table width="1180" class="table table_hover tab_sel table-bordered table-hover">
					<tr>
						<th class="table_th_number">序号</th>
						<th class="table_th_number"><input name="select_all" type="checkbox"/></th>
						<th width="200">搜索</th>
						<th width="350">商品名称与规格</th>
						<th width="260">数量</th>
						<th width="300">备注</th>
					</tr>
					<tr>
						<td class="center">1</td>
						<td class="center"><input type="checkbox" name="select_one"/></td>
						<td>
							<xsl:element name="INPUT">
								<xsl:attribute name="type">text</xsl:attribute>
								<xsl:attribute name="class">form-control form_no_border input-sm find</xsl:attribute>
							</xsl:element>
						</td>
						<td>
							<select class="mind form_no_border form-control input-sm name" name="product_id[]">
								<xsl:for-each select="/html/Body/supplierMsg/type/ul/li">
									<xsl:element name="OPTION">
									</xsl:element>
								</xsl:for-each>
							</select>
						</td>

						<td>
							<xsl:element name="INPUT">
								<xsl:attribute name="type">text</xsl:attribute>
								<xsl:attribute name="name">total[]</xsl:attribute>
								<xsl:attribute name="class">form-control form_no_border input-sm</xsl:attribute>
							</xsl:element>
						</td>
						<td>
							<xsl:element name="INPUT">
								<xsl:attribute name="type">text</xsl:attribute>
								<xsl:attribute name="name">body[]</xsl:attribute>
								<xsl:attribute name="class">form-control form_no_border input-sm</xsl:attribute>
							</xsl:element>
						</td>
					</tr>
				</table>
			</div>
		</div>
		<p>
			<input class="btn btn-default btn-sm btn_margin" name="tijiao" type="submit" value="提交"/>
			<input class="btn btn-default btn-sm btn_margin" type="reset" value="重置"/>
		</p>
	</form>
</div>
</xsl:template>

</xsl:stylesheet>