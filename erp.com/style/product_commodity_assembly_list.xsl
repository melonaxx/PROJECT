<?xml version="1.0" encoding="utf-8" ?>
<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<!-- UTF8 编码 -->
<xsl:include href="/style/header.xsl" />
<xsl:template name="text">
<script type="text/javascript" src="/js_encode/product_commodity_assembly_list.js"></script>
<div class="mainBody">
	<ul id="myTab" class="nav nav-tabs nav_tabs_margin">
	   <li><a href="/product/product_commodity_assembly.php">组合商品</a></li>
	   <li class="active"><a href="/product/product_commodity_assembly_list.php">组合商品列表</a></li>
	</ul>
	<div class="table_operate_blocks">
		<form method="post" class="form-inline" action="product_commodity_assembly_list.php">
			<button class="btn btn-default btn-sm btn_margin delete" type="button">删除</button>
			<div class="btn-group btn_margin">
				<button type="button" class="form-control input-sm" data-toggle="dropdown">批量操作<span class="caret"></span></button>
				<ul class="dropdown-menu" role="menu">
					<li><a style="font-size:12px;" class="edit_brand" href="javascript:;">修改分类</a></li>
					<li><a style="font-size:12px;" class="edit_category" href="javascript:;">修改品牌</a></li>
				</ul>
			</div>
			<button class="btn btn-default btn-sm  float_right" type="reset">清空</button>
			<button class="btn btn-default btn-sm btn_margin float_right" type="submit">查询</button>
			<div class="form-group float_right form_small_block">
				<label>商品分类：</label>
				<select class="form-control input-sm" name="classification">
					<option></option>
					<xsl:for-each select="/html/Body/categoryList/ul/li">
						<xsl:element name="OPTION">
							<xsl:attribute name="value"><xsl:value-of select="@id" /></xsl:attribute>
							<xsl:value-of select="." />
						</xsl:element>
					</xsl:for-each>
				</select>
			</div>
			<div class="form-group float_right form_small_block">
				<label>商品名称：</label>
				<xsl:element name="INPUT">
					<xsl:attribute name="type">text</xsl:attribute>
					<xsl:attribute name="class">form-control input-sm</xsl:attribute>
					<xsl:attribute name="name">name</xsl:attribute>
				</xsl:element>
			</div>
		</form>
	</div>
	<table width="2000" class="table tab-sel table-bordered table-hover">
		<tr>
			<th class="table_th_number">序号</th>
			<th class="table_th_checkbox center"><input name="select_all" type="checkbox"/></th>
			<th class="table_th_operate_1">操作</th>
			<th width="200">商品名称</th>
			<th width="60">子商品</th>
			<th width="150">零售价</th>
			<th width="150">分类</th>
			<th width="200">品牌</th>
			<th width="500">备注</th>
		</tr>
		<xsl:for-each select="/html/Body/productinfo/ul/li">
			<tr>
				<td class="center"><xsl:value-of select="position()"/></td>
				<td class="center">
					<xsl:element name="INPUT">
						<xsl:attribute name="name">select_one</xsl:attribute>
						<xsl:attribute name="id"><xsl:value-of select="id"/></xsl:attribute>
						<xsl:attribute name="type">checkbox</xsl:attribute>
					</xsl:element>
				</td>
				<td class="center">
					<xsl:element name="A">
						<xsl:attribute name="href">/product/product_modified_combination_goods.php?id=<xsl:value-of select="id"/></xsl:attribute>
						<xsl:text>修改</xsl:text>
					</xsl:element>
				</td>
				<td>
					<xsl:element name="A">
						<xsl:attribute name="href">/product/product_modified_combination_look.php?id=<xsl:value-of select="id"/></xsl:attribute>
						<xsl:attribute name="style">color:#eda93a</xsl:attribute>
						<xsl:value-of select="name"/>
					</xsl:element>
				</td>
				<td>
					<xsl:element name="A">
						<xsl:attribute name="href">javascript:;</xsl:attribute>
						<xsl:attribute name="onclick">MessageBox('/product/product_look_sum.php?id=<xsl:value-of select="id"/>', '子商品', 700, 200)</xsl:attribute>
						<xsl:text>查看</xsl:text>
					</xsl:element>
				</td>
				<td><xsl:value-of select="price_display"/></td>
				<td><xsl:value-of select="category_id"/></td>
				<td><xsl:value-of select="brand_id"/></td>
				<td><xsl:value-of select="content"/></td>
			</tr>
		</xsl:for-each>
	</table>
	<xsl:call-template name="page"></xsl:call-template>
</div>
</xsl:template>
</xsl:stylesheet>
