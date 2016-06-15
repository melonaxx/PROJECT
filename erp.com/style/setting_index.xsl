<?xml version="1.0" encoding="utf-8" ?>
<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<!-- UTF8 编码 -->

<xsl:include href="/style/header.xsl" />

<xsl:template name="text">

	<div id="html_body">
		<form method="post" action="/setting/index.php">
		<input type="hidden" name="company_setting" value="1"/>
		<xsl:if test="/html/head/em/@company_id = '0'">
			<b>欢迎注册使用电子商务ERP，请认真填写以下信息！</b>
		</xsl:if>
		<xsl:if test="/html/head/em/@company_id != '0'">
			<b>
			<xsl:if test="/html/Body/span/@permit = 'Y'">编辑</xsl:if>
			<xsl:if test="/html/Body/span/@permit = 'N'">查看</xsl:if>
			公司信息
			</b>
		</xsl:if>
		<br/><br/>
		<table cellpadding="0" cellspacing="0">
		<tr height="40">
			<td width="120" align="right">公司名称：</td>
			<td width="20"><font color="red">*</font></td>
			<td width="500">
				<xsl:if test="/html/head/em/@company_id = '-1'">
					<input class="register_box" name="company_name" />
					<font color='#666666'>&#160;&#160;&#160; 请认真填写，注册后不得修改!</font>
				</xsl:if>
				<xsl:if test="/html/head/em/@company_id != '-1'">
					<b><xsl:value-of select="/html/Body/span/name"/></b>
				</xsl:if>
			</td>
		</tr>
		<tr height="40">
			<td align="right">公司地址：</td>
			<td></td>
			<td>
				<xsl:element name="INPUT">
					<xsl:attribute name="class">register_box</xsl:attribute>
					<xsl:attribute name="name">address</xsl:attribute>
					<xsl:attribute name="value"><xsl:value-of select="/html/Body/span/address"/></xsl:attribute>
					<xsl:if test="/html/Body/span/@permit = 'N'"><xsl:attribute name="readonly">true</xsl:attribute></xsl:if>
				</xsl:element>
			</td>
		</tr>
		<tr height="40">
			<td align="right">联系人：</td>
			<td><font color="red">*</font></td>
			<td>
				<xsl:element name="INPUT">
					<xsl:attribute name="class">register_box</xsl:attribute>
					<xsl:attribute name="name">contact_name</xsl:attribute>
					<xsl:attribute name="value"><xsl:value-of select="/html/Body/span/contact_name"/></xsl:attribute>
					<xsl:if test="/html/Body/span/@permit = 'N'"><xsl:attribute name="readonly">true</xsl:attribute></xsl:if>
				</xsl:element>
			</td>
		</tr>
		<tr height="40">
			<td align="right">电话：</td>
			<td></td>
			<td>
				<xsl:element name="INPUT">
					<xsl:attribute name="class">register_box</xsl:attribute>
					<xsl:attribute name="name">telphone</xsl:attribute>
					<xsl:attribute name="value"><xsl:value-of select="/html/Body/span/telphone"/></xsl:attribute>
					<xsl:if test="/html/Body/span/@permit = 'N'"><xsl:attribute name="readonly">true</xsl:attribute></xsl:if>
				</xsl:element>
			</td>
		</tr>
		<tr height="40">
			<td align="right">手机：</td>
			<td><font color="red">*</font></td>
			<td>
				<xsl:element name="INPUT">
					<xsl:attribute name="class">register_box</xsl:attribute>
					<xsl:attribute name="name">mobile</xsl:attribute>
					<xsl:attribute name="value"><xsl:value-of select="/html/Body/span/mobile"/></xsl:attribute>
					<xsl:if test="/html/Body/span/@permit = 'N'"><xsl:attribute name="readonly">true</xsl:attribute></xsl:if>
				</xsl:element>
			</td>
		</tr>
		<tr height="40">
			<td align="right">QQ/旺旺：</td>
			<td></td>
			<td>
				<xsl:element name="INPUT">
					<xsl:attribute name="class">register_box</xsl:attribute>
					<xsl:attribute name="name">qq</xsl:attribute>
					<xsl:attribute name="value"><xsl:value-of select="/html/Body/span/qq"/></xsl:attribute>
					<xsl:if test="/html/Body/span/@permit = 'N'"><xsl:attribute name="readonly">true</xsl:attribute></xsl:if>
				</xsl:element>
			</td>
		</tr>
		<tr height="40">
			<td></td>
			<td></td>
			<td><input type="submit" value="下一步"/></td>
		</tr>
		</table>
		</form>

	</div>

</xsl:template>

</xsl:stylesheet>
