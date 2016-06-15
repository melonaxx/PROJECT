<?xml version="1.0" encoding="utf-8" ?>
<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<!-- UTF8 编码 -->

<xsl:include href="/style/header.xsl" />

<xsl:template name="text">

<div class="mainBody">
	<div class="table_operate_block">
		<button class="btn btn-default btn-sm" onclick="MessageBox('/setting/setting_add_shop.php', '添加店铺', 400, 120)" type="submit">添加店铺</button>
	</div>
	<table class="table table-bordered table-hover" width="700">
		<tr>
			<th width="50">序号</th>
			<th width="80">操作</th>
			<th width="150">销售平台</th>
			<th width="250">店铺名称</th>
			<th width="150">财务账户</th>
			<th width="200">授权时间</th>
		</tr>
		<xsl:for-each select="/html/Body/ul/li">
		<tr>
			<td class="center"><xsl:value-of select="position()"/></td>
			<td>
				<xsl:element name="A">
					<xsl:attribute name="href">#</xsl:attribute>
					<xsl:attribute name="onclick">MessageBox('/setting/setting_edit_shop.php?id=<xsl:value-of select="@id"/>', '修改店铺', 400, 200); return false</xsl:attribute>
					修改
				</xsl:element>
				&#160;
				<xsl:if test="type != '系统用户'">
					<xsl:element name="A">
						<xsl:attribute name="href">/setting/setting_delete_shop.php?id=<xsl:value-of select="@id"/></xsl:attribute>
						<xsl:attribute name="onclick">cc=confirm("确定要删除该店铺吗？"); if(!cc) return false</xsl:attribute>
						删除
					</xsl:element>
				</xsl:if>
			</td>
			<td><xsl:value-of select="type"/></td>
			<td><xsl:value-of select="name"/></td>
			<td><xsl:value-of select="bank"/></td>
			<td><xsl:value-of select="date"/></td>
		</tr>
		</xsl:for-each>
		<xsl:if test="/html/Body/ul/@total = '0'">
			<tr><td colspan="5"></td></tr>
		</xsl:if>
	</table>
</div>

</xsl:template>

</xsl:stylesheet>