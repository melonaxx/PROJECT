<?xml version="1.0" encoding="utf-8" ?>
<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<!-- UTF8 编码 -->

<xsl:include href="/style/header.xsl" />

<xsl:template name="text">

<br/><br/><br/><br/>

<center>

<b>注册完成！请等待工作人员审核。
		<xsl:element name="A">
				<xsl:attribute name="target">_blank</xsl:attribute>
				<xsl:attribute name="href">http://amos.alicdn.com/msg.aw?v=2&amp;uid=hzmihuan&amp;site=cnalichn&amp;s=10&amp;charset=UTF-8</xsl:attribute>
				<xsl:attribute name="class">myMod</xsl:attribute>
				<img  alt="点击和我联系" border="0" src="http://amos.alicdn.com/online.aw?v=2&amp;uid=hzmihuan&amp;site=cnalichn&amp;s=10&amp;charset=UTF-8"/>
		</xsl:element>
</b><br/><br/>

<table align="center" cellpadding="0" cellspacing="0">
<tr>
	<th width="500"></th>
	<th width="200"></th>
</tr>
<tr height="50">
	<td>
		<div class="col-sm-3 control-label">公司名称：</div>
		<div class="col-sm-9"><xsl:value-of select="/html/Body/span/company_name"/></div>
	</td>
	<td></td>
</tr>
<tr height="50">
	<td>
		<div class="col-sm-3 control-label">公司地址：</div>
		<div class="col-sm-9"><xsl:value-of select="/html/Body/span/address"/></div>
	</td>
	<td></td>
</tr>
<tr height="50">
	<td>
		<div class="col-sm-3 control-label">联系人：</div>
		<div class="col-sm-9"><xsl:value-of select="/html/Body/span/contact_name"/></div>
	</td>
	<td></td>
</tr>
<tr height="50">
	<td>
		<div class="col-sm-3 control-label">E-Mail：</div>
		<div class="col-sm-9"><xsl:value-of select="/html/Body/span/email"/></div>
	</td>
	<td></td>
</tr>
<tr height="50">
	<td>
		<div class="col-sm-3 control-label">电话：</div>
		<div class="col-sm-9"><xsl:value-of select="/html/Body/span/telphone"/></div>
	</td>
	<td></td>
</tr>
<tr height="50">
	<td>
		<div class="col-sm-3 control-label">手机：</div>
		<div class="col-sm-9"><xsl:value-of select="/html/Body/span/mobile"/></div>
	</td>
	<td></td>
</tr>
<tr height="50">
	<td>
		<div class="col-sm-3 control-label">QQ/旺旺：</div>
		<div class="col-sm-9"><xsl:value-of select="/html/Body/span/qq"/></div>
	</td>
	<td></td>
</tr>
<tr height="50">
	<td>
		<div class="col-sm-3 control-label">管理员帐号：</div>
		<div class="col-sm-9"><xsl:value-of select="/html/Body/span/admin_name"/></div>
	</td>
	<td></td>
</tr>
</table>

</center>


</xsl:template>

</xsl:stylesheet>
