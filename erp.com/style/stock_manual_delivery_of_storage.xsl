<?xml version="1.0" encoding="utf-8" ?>
<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<!-- UTF8 编码 -->

<xsl:include href="/style/header.xsl" />

<xsl:template name="text">
<script type="text/javascript" src="/js_encode/stock_warehouse_storage.js"></script>
<div class="mainBody">
	<ul id="myTab" class="nav nav-tabs nav_tabs_margin">
	   <li><a href="/stock/stock_warehouse_storage.php">手动出库入库</a></li>
	   <li class="active"><a href="/stock/stock_manual_delivery_of_storage.php">出库入库记录</a></li>
	</ul>
	<div class="table_operate_block form-inline">
		<form method="post" action="/stock/stock_manual_delivery_of_storage.php">
			<div class="form-group" style="margin:0; float:right;">
				<button class="btn btn-default btn-sm" type="submit">查询</button>
				<!-- <input class="btn btn-default btn-sm" type="button" name="clear" value="清空" /> -->
			</div>
			<!-- <div class="form-group form_small_block float_right">
				<label>商品：</label>
				<xsl:element name="INPUT">
					<xsl:attribute name="type">text</xsl:attribute>
					<xsl:attribute name="name">product</xsl:attribute>
					<xsl:attribute name="class">form-control input-sm</xsl:attribute>
					<xsl:attribute name="placeholder">输入商品名称或编码</xsl:attribute>
				</xsl:element>
			</div> -->
			<div class="form-group form_small_block float_right">
				<label>仓库：</label>
				<select class="form-control input-sm" name="store">
					<option value="0"></option>
					<xsl:for-each select="/html/Body/store_ware/ul/li">
						<xsl:element name="OPTION">
						   <xsl:attribute name="value"><xsl:value-of select="value"/></xsl:attribute>
						   <xsl:if test="value=/html/Body/store">
								<xsl:attribute name="selected">selected</xsl:attribute>
						   </xsl:if>
						   <xsl:value-of select="text"/>
						</xsl:element>
					</xsl:for-each>
				</select>
			</div>
		</form>
	</div>
	<div>
		<table class="table tab-sel table-bordered table-hover">
			<tr>
				<th class="table_th_number">序号</th>
				<th width="42">操作</th>
				<th width="140">时间</th>
				<th width="200">仓库</th>
				<th width="200">数量</th>
				<th width="50">类型</th>
				<th width="350">备注</th>
				<th width="200">操作人</th>
				<!--<th>财务</th>
				<th>金额</th>
				<th>账户</th>
				<th>状态</th>-->
			</tr>
			<xsl:for-each select="/html/Body/storeOperation/ul/li">
			<tr>
				<td class="center"><xsl:value-of select="position()"/></td>
				<td><xsl:element name="A">
						<xsl:attribute name="href">javascript:;</xsl:attribute>
						<xsl:attribute name="onclick">MessageBox("/stock/stock_look_record.php?id=<xsl:value-of select='id'/>",'查看记录', 600, 280)</xsl:attribute>
						<xsl:text>查看</xsl:text>
					</xsl:element>
				</td>
				<td><xsl:value-of select="action_date"/></td>
				<td><xsl:value-of select="store_id"/></td>
				<td><xsl:value-of select="total"/></td>
				<td><xsl:value-of select="type"/></td>
				<td><xsl:value-of select="body"/></td>
				<td><xsl:value-of select="staff_id"/></td>
				<!--<td>收款</td>
				<td>￥200.00</td>
				<td>支付宝01</td>
				<td>未入账</td>-->
			</tr>
			</xsl:for-each>
		</table>
		<xsl:call-template name="page"></xsl:call-template>
	</div>
</div>
</xsl:template>

</xsl:stylesheet>