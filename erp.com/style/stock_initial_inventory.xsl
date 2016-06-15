<?xml version="1.0" encoding="utf-8" ?>
<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<!-- UTF8 编码 -->

<xsl:include href="/style/header.xsl" />
<xsl:template name="text">
<script type="text/javascript" src="/js_encode/stock_initial_inventory.js"></script>
	<div class="mainBody">
		<div class="table_operate_block form-inline">
			<form action="stock_initial_inventory.php" method="post">
				<div class="form-group" style="margin:0; float:right;">
					<p>
						<button class="btn btn-default btn-sm btn_margin" type="submit">查询</button>
						<button class="btn btn-default btn-sm" type="reset">清空</button>
					</p>
				</div>
				<div class="form-group form_small_block float_right">
					<label>品牌：</label>
					<select name="brand" class="form-control input-sm">
						<option value="0"></option>
						<xsl:for-each select="/html/Body/brandList/ul/li">
								<xsl:element name="OPTION">
									<xsl:attribute name="value"><xsl:value-of select="@id" /></xsl:attribute>
									<xsl:if test="@id=/html/Body/brand"><xsl:attribute name="selected">selected</xsl:attribute></xsl:if>
									<xsl:value-of select="." />
								</xsl:element>
							</xsl:for-each>
					</select>
				</div>
				<div class="form-group form_small_block float_right">
					<label>分类：</label>
					<select name="classification" class="form-control input-sm">
						<option value="0"></option>
						<xsl:for-each select="/html/Body/product_category/ul/li">
							<xsl:element name="OPTION">
								<xsl:attribute name="value"><xsl:value-of select="value"/></xsl:attribute>
								<xsl:if test="value=/html/Body/classification"><xsl:attribute name="selected">selected</xsl:attribute></xsl:if>
								<xsl:value-of select="text"/>
							</xsl:element>
						</xsl:for-each>
					</select>
				</div>
				<div class="form-group form_small_block float_right">
					<label>商品：</label>
					<xsl:element name="INPUT">
						<xsl:attribute name="type">text</xsl:attribute>
						<xsl:attribute name="name">find</xsl:attribute>
						<xsl:attribute name="class">form-control input-sm</xsl:attribute>
						<xsl:attribute name="placeholder">输入商品名称或编码</xsl:attribute>
						<xsl:attribute name="value"><xsl:value-of select="/html/Body/find"/></xsl:attribute>
					</xsl:element>
				</div>
				<div class="form-group form_small_block float_right">
					<label>仓库：</label>
					<select name="ware" class="form-control input-sm">
						<xsl:for-each select="/html/Body/store_list/ul/li">
							<xsl:element name="OPTION">
								<xsl:attribute name="value"><xsl:value-of select="value"/></xsl:attribute>
								<xsl:if test="value=/html/Body/ware"><xsl:attribute name="selected">selected</xsl:attribute></xsl:if>
								<xsl:value-of select="text"/>
							</xsl:element>
						</xsl:for-each>
					</select>
				</div>
			</form>
		</div>
		<div>
			<form class="form-inline">
				<table class="table amaed table-bordered table-hover">
					<tr>
						<th class="table_th_number">序号</th>
						<th style="width:50px;">图片</th>
						<th style="width:200px;">商品名称</th>
						<th style="width:200px;">规格</th>
						<th style="width:200px;">商品编码</th>
						<th style="width:170px;">期初成本均价</th>
						<th style="width:70px;">位置数量</th>
						<th>期初数量</th>
					</tr>
					<xsl:for-each select="/html/Body/store/ul/li">
					<tr>
						<td class="center"><xsl:value-of select="num"/></td>
						<td><img src="./pic"/></td>
						<td><xsl:value-of select="name"/></td>
						<td><xsl:value-of select="format_1"/><xsl:value-of select="format_2"/><xsl:value-of select="format_3"/><xsl:value-of select="format_4"/><xsl:value-of select="format_5"/></td>
						<td><xsl:value-of select="number"/></td>
						<td><xsl:value-of select="cost"/></td>
						<td>
							<xsl:element name="A">
								<xsl:attribute name="href">javascript:;</xsl:attribute>
								<xsl:element name="SPAN">
									<xsl:attribute name="onclick">MessageBox('/stock/stock_num_setup.php?id=<xsl:value-of select="id" />', '设置数量',240, 120)</xsl:attribute>
									<xsl:text>设置</xsl:text>
								</xsl:element>
							</xsl:element>
						</td>
						<td><xsl:value-of select="total"/></td>
					</tr>  
					</xsl:for-each>
				</table>
			</form>
			<xsl:call-template name="page"></xsl:call-template>
		</div>
	</div>
</xsl:template>

</xsl:stylesheet>
