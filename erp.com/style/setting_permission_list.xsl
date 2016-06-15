<?xml version="1.0" encoding="utf-8" ?>
<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<!-- UTF8 编码 -->

<xsl:include href="/style/header.xsl" />

<xsl:template name="text">

<ul class="nav nav-tabs">
	<li class="tab-pane active"><a>权限管理</a></li>
</ul>
<br/>
<div class="table_operate_block">
	<a class="btn btn-default btn-sm" href="/setting/setting_add_permission.php">添加角色权限</a>
</div>

<div class="clear"></div>

<table class="table table-bordered table-hover">
<tr>
	<th width="80" class="center">序号</th>
	<th width="80" class="center">操作</th>
	<th width="250">角色名称</th>
	<th width="790">备注</th>
</tr>
<xsl:for-each select="/html/Body/ul/li">
	<tr>
		<td><center><xsl:value-of select="@no"/></center></td>
		<td style="text-align:center">
			<xsl:element name="A">
				<xsl:attribute name="target">_blank</xsl:attribute>
				<xsl:attribute name="href">/setting/setting_edit_permission.php?id=<xsl:value-of select="@id"/></xsl:attribute>
				修改
			</xsl:element>
		</td>
		<td><xsl:value-of select="name"/></td>
		<td><xsl:value-of select="text"/></td>
	</tr>
</xsl:for-each>
<xsl:if test="/html/Body/ul/@total = '0'">
<tr>
	<td colspan="5">
		暂无内容，
		<xsl:element name="A">
			<xsl:attribute name="href">/setting/setting_add_permission.php</xsl:attribute>
			点这里添加角色权限
		</xsl:element>
	</td>
</tr>
</xsl:if>
</table>
</xsl:template>

</xsl:stylesheet>
