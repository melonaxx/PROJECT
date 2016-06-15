<?xml version="1.0" encoding="utf-8" ?>
<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<!-- UTF8 编码 -->

<xsl:include href="/style/header.xsl" />

<xsl:template name="text">

<div class="mainBody">
	<ul id="myTab" class="nav nav-tabs">
		<li class="tab-pane"><a href="/product/product_specifications_properties.php">规格管理</a></li>
		<li class="tab-pane"><a href="/product/product_attribute.php">属性</a></li>
		<li class="tab-pane active"><a href="/product/product_unit_setting.php">单位设置</a></li>
	</ul>
	<br/>
	<div class="table_operate_block">
		<input class="btn btn-default btn-sm btn_margin" onclick="MessageBox('product_add_unit.php', '添加单位名称', 240, 75); return false" type="button" value="添加" />
	</div>
	<table class="table table-bordered custom ttbale table-hover">
	<tr>
		<th class="table_th_number">序号</th>
		<th class="table_th_operate_2">操作</th>
		<th class="table_th_great">单位名称</th>
	</tr>
	<xsl:for-each select="/html/Body/ul/li">
	<tr>
		<td class="center"><xsl:value-of select="position()"/></td>
		<td class="center">
			<xsl:element name="A">
				<xsl:attribute name="href">#</xsl:attribute>
				<xsl:attribute name="onclick">MessageBox('/product/product_edit_unit.php?id=<xsl:value-of select="id"/>', '修改单位名称', 240, 75); return false</xsl:attribute>
				修改
			</xsl:element>
			&#160;
			<xsl:element name="A">
				<!-- <xsl:attribute name="href">/product/product_delete_unit.php?id=<xsl:value-of select="id"/></xsl:attribute>
				<xsl:attribute name="onclick">cc=confirm("确定要删除该单位名称吗？"); if(!cc) return false</xsl:attribute>
				删除 -->
				<xsl:attribute name="href">#</xsl:attribute>
				<xsl:attribute name="onclick">MessageBox('/product/product_delete_unit.php?id=<xsl:value-of select="id"/>', '删除单位名称', 240, 75); return false</xsl:attribute>
				删除
			</xsl:element>
		</td>
		<td class="Property_Name"><xsl:value-of select="name"/></td>
	</tr>
	</xsl:for-each>
	<xsl:if test="/html/Body/ul/@total = '0'">
	<tr>
		<td colspan="4">
			暂无内容，
			<xsl:element name="A">
				<xsl:attribute name="onclick">MessageBox('product_add_unit.php','添加单位名称', 240, 75); return false</xsl:attribute>
				<xsl:attribute name="href">#</xsl:attribute>
				点这里添加单位名称
			</xsl:element>
		</td>
	</tr>
	</xsl:if>
	</table>
</div>
</xsl:template>

</xsl:stylesheet>


