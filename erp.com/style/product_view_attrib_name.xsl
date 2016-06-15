<?xml version="1.0" encoding="utf-8" ?>
<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<!-- UTF8 编码 -->

<xsl:include href="/style/header.xsl" />

<xsl:template name="text">

<div class="mainBody">
	<ul id="myTab" class="nav nav-tabs">
		<li class="tab-pane"><a href="/product/product_specifications_properties.php">规格管理</a></li>
		<li class="tab-pane active"><a href="/product/product_attribute.php">属性</a></li>
		<li class="tab-pane"><a href="/product/product_unit_setting.php">单位设置</a></li>
	</ul>
	<div class="table_operate_block">
		<br/>
		<xsl:element name="INPUT">
			<xsl:attribute name="class">btn btn-default btn-sm btn_margin</xsl:attribute>
			<xsl:attribute name="onclick">MessageBox('product_add_attrib_value.php?attrib_id=<xsl:value-of select="/html/Body/ul/@id"/>','添加属性值', 240, 75)</xsl:attribute>
			<xsl:attribute name="type">button</xsl:attribute>
			<xsl:attribute name="value">添加</xsl:attribute>
		</xsl:element>
		<span style="position:absolute;top:161px;">属性名称：<xsl:value-of select="/html/Body/ul/@name"/></span>
	</div>
	<table class="table table-bordered norms ttbale table-hover">
	<tr>
		<th class="table_th_number">序号</th>
		<th class="table_th_operate_2 center">操作</th>
		<th width="1100">属性值</th>
	</tr>
	<xsl:for-each select="/html/Body/ul/li">
	<tr>
		<td class="center"><xsl:value-of select="position()"/></td>
		<td class="center">
			<xsl:element name="A">
				<xsl:attribute name="href">#</xsl:attribute>
				<xsl:attribute name="onclick">MessageBox('/product/product_edit_attrib_value.php?id=<xsl:value-of select="id"/>', '修改属性值', 240, 75); return false</xsl:attribute>
				<xsl:text>修改</xsl:text>
			</xsl:element>
			&#160;
			<xsl:element name="A">
				<xsl:attribute name="href">/product/product_delete_attrib_value.php?id=<xsl:value-of select="id"/></xsl:attribute>
				<xsl:attribute name="onclick">cc=confirm("确定要删除该属性吗？"); if(!cc) return false</xsl:attribute>
				<xsl:text>删除</xsl:text>
			</xsl:element>
		</td>
		<td class="amend"><xsl:value-of select="name"/></td>
	</tr>
	</xsl:for-each>
	<xsl:if test="/html/Body/ul/@total = '0'">
	<tr>
		<td colspan="3">
			暂无内容，
			<xsl:element name="A">
				<xsl:attribute name="onclick">MessageBox('product_add_attrib_value.php?attrib_id=<xsl:value-of select="/html/Body/ul/@id"/>','添加属性值', 240, 75); return false</xsl:attribute>
				<xsl:attribute name="href">#</xsl:attribute>
				点这里添加属性值
			</xsl:element>
		</td>
	</tr>
	</xsl:if>
	</table>
</div>
<script type="text/javascript">
</script>
</xsl:template>

</xsl:stylesheet>
