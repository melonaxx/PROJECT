<?xml version="1.0" encoding="utf-8" ?>
<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<!-- UTF8 编码 -->
<xsl:include href="/style/header.xsl" />
<xsl:template name="text">
<div class="mainBody">
	<div class="table_operate_block">
		<button class="btn btn-default btn-sm" onclick="MessageBox('/finance/finance_add_account.php', '添加账户', 480, 260)" type="submit">添加账户</button>
	</div>
	<table class="table table-bordered table-hover">
		<tr>
			<th class="center" width="50px">序号</th>
			<th class="center" width="90px">操作</th>
			<th width="180px">账户名称</th>
			<th width="180px">账户号码</th>
			<th width="180px">账户余额</th>
			<th width="340px">备注</th>
			<th width="180px">创建时间</th>
		</tr>
		<xsl:for-each select="/html/Body/finance/ul/li">
			<tr>
				<td class="center"><xsl:value-of select="num"/></td>
				<td>
					<xsl:element name="A">
						<xsl:attribute name="class">table_a_operate</xsl:attribute>
						<xsl:attribute name="href">javascript:;</xsl:attribute>
						<xsl:attribute name="onclick">MessageBox('/finance/finance_edit_account.php?id=<xsl:value-of select="id" />', '修改账户', 480, 260)</xsl:attribute>
						<xsl:text>修改</xsl:text>
					</xsl:element>
					<xsl:if test="default = 'N'">
						<xsl:element name="SPAN">
							<xsl:attribute name="style">cursor:pointer</xsl:attribute>
							<xsl:attribute name="class">table_a_operate</xsl:attribute>
							<xsl:attribute name="onclick">MessageBox('/finance/finance_delete_account.php?id=<xsl:value-of select="id" />', '删除收入科目', 320, 90)</xsl:attribute>
							删除
						</xsl:element>
					</xsl:if>
					<xsl:if test="default = 'Y'">
						默认
					</xsl:if>
				</td>
				<td><xsl:value-of select="name"/></td>
				<td><xsl:value-of select="number"/></td>
				<td>￥<xsl:value-of select="balance"/></td>
				<td><xsl:value-of select="body"/></td>
				<td><xsl:value-of select="action_date"/></td>
			</tr>
		</xsl:for-each>
	</table>
	<xsl:call-template name="page"></xsl:call-template>
</div>
</xsl:template>

</xsl:stylesheet>