<?xml version="1.0" encoding="utf-8" ?>
<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<!-- UTF8 编码 -->

<xsl:include href="/style/header.xsl" />

<xsl:template name="text">

<script>
<![CDATA[
function check_register()
{
	getObject("error_company").innerHTML	= "";
	getObject("error_contact").innerHTML	= "";
	getObject("error_email").innerHTML		= "";
	getObject("error_admin").innerHTML		= "";
	getObject("error_password").innerHTML	= "";

	var c	= trim(getObject("company_name").value);
	var d	= trim(getObject("contact_name").value);
	var n	= trim(getObject("admin_name").value);
	var m	= trim(getObject("email").value);
	var p1	= getObject("pw1").value;
	var p2	= getObject("pw2").value;

	getObject("company_name").value	= c;
	getObject("contact_name").value	= d;
	getObject("admin_name").value	= n;
	getObject("email").value		= m;

	var reg	= /^([a-zA-Z0-9_-])+@([a-zA-Z0-9_-])+((\.[a-zA-Z0-9_-]{2,3}){1,2})$/;
	if(c.length < 4)
	{
		getObject("error_company").innerHTML	= "请输入公司名称！";
		getObject("company_name").focus();
		return false;
	}
	if(d.length < 2)
	{
		getObject("error_contact").innerHTML	= "请输入联系人！";
		getObject("contact_name").focus();
		return false;
	}
	<xsl:if test="/html/Body/span/email/@need = 'Y'">
	if(!reg.test(m))
	{
		getObject("error_email").innerHTML		= "请输入E-mail！";
		getObject("email").focus();
		return false;
	}
	</xsl:if>
	if(n.length < 3 || n.length > 20)
	{
		getObject("error_admin").innerHTML		= "管理员帐号太短，请设置4-20位的帐户名！";
		getObject("admin_name").focus();
		return false;
	}
	if(p1.length < 5)
	{
		getObject("error_password").innerHTML	= "密码太短，请设置6-20位的密码";
		getObject("pw1").focus();
		return false;
	}
	if(p1 != p2)
	{
		alert("请确认密码！");
		getObject("pw2").focus();
		return false;
	}
}
]]>
</script>
<iframe width='0' height='0' frameborder='0' id='ajax_login' name='ajax_login' src='/style/empty.html'></iframe>

<br/><br/><br/>

<center>

<b><xsl:value-of select="/html/Body/span/welcome"/>欢迎注册使用米欢企业ERP，请认真填写以下信息！</b><br/><br/>

<form method="post" target="ajax_login" class="form-horizontal" autocomplete="off" action="/login/register_erp.php" onsubmit="return check_register()">
<table align="center" cellpadding="0" cellspacing="0">
<tr>
	<th width="500"></th>
	<th width="200"></th>
</tr>
<tr height="50">
	<td>
		<label for="company_name" class="col-sm-3 control-label">公司名称：</label>
		<div class="col-sm-9">
			<xsl:element name="INPUT">
				<xsl:attribute name="id">company_name</xsl:attribute>
				<xsl:attribute name="name">company_name</xsl:attribute>
				<xsl:attribute name="class">form-control</xsl:attribute>
				<xsl:attribute name="maxlength">40</xsl:attribute>
				<xsl:attribute name="value"><xsl:value-of select="/html/Body/span/company_name"/></xsl:attribute>
			</xsl:element>
		</div>
	</td>
	<td><font color='red'>*</font> <font color="red" id="error_company"></font></td>
</tr>
<tr>
	<td>
		<div class="col-sm-3"></div>
		<div class="col-sm-9">
			<font color='#666666'>请认真填写，注册后不得修改!</font>
		</div>
		<br/><br/>
	</td>
</tr>
<tr height="50">
	<td>
		<label for="address" class="col-sm-3 control-label">公司地址：</label>
		<div class="col-sm-9">
			<xsl:element name="INPUT">
				<xsl:attribute name="id">address</xsl:attribute>
				<xsl:attribute name="name">address</xsl:attribute>
				<xsl:attribute name="class">form-control</xsl:attribute>
				<xsl:attribute name="maxlength">60</xsl:attribute>
				<xsl:attribute name="value"><xsl:value-of select="/html/Body/span/address"/></xsl:attribute>
			</xsl:element>
		</div>
	</td>
	<td></td>
</tr>
<tr height="50">
	<td>
		<label class="col-sm-3 control-label">联系人：</label>
		<div class="col-sm-9">
			<xsl:element name="INPUT">
				<xsl:attribute name="id">contact_name</xsl:attribute>
				<xsl:attribute name="name">contact_name</xsl:attribute>
				<xsl:attribute name="class">form-control</xsl:attribute>
				<xsl:attribute name="maxlength">20</xsl:attribute>
				<xsl:attribute name="value"><xsl:value-of select="/html/Body/span/contact_name"/></xsl:attribute>
			</xsl:element>
		</div>
	</td>
	<td><font color='red'>*</font> <font color="red" id="error_contact"></font></td>
</tr>
<tr height="50">
	<td>
		<label class="col-sm-3 control-label">E-Mail：</label>
		<div class="col-sm-9">
			<xsl:element name="INPUT">
				<xsl:attribute name="id">email</xsl:attribute>
				<xsl:attribute name="name">email</xsl:attribute>
				<xsl:attribute name="class">form-control</xsl:attribute>
				<xsl:attribute name="value"><xsl:value-of select="/html/Body/span/email"/></xsl:attribute>
			</xsl:element>
		</div>
	</td>
	<td><xsl:if test="/html/Body/span/email/@need = 'Y'"><font color='red'>*</font> </xsl:if><font color="red" id="error_email"></font></td>
</tr>
<tr height="50">
	<td>
		<label class="col-sm-3 control-label">电话：</label>
		<div class="col-sm-9">
			<xsl:element name="INPUT">
				<xsl:attribute name="name">telphone</xsl:attribute>
				<xsl:attribute name="class">form-control</xsl:attribute>
				<xsl:attribute name="value"><xsl:value-of select="/html/Body/span/telphone"/></xsl:attribute>
			</xsl:element>
		</div>
	</td>
	<td></td>
</tr>
<tr height="50">
	<td>
		<label class="col-sm-3 control-label">手机：</label>
		<div class="col-sm-9">
			<xsl:element name="INPUT">
				<xsl:attribute name="name">mobile</xsl:attribute>
				<xsl:attribute name="class">form-control</xsl:attribute>
				<xsl:attribute name="value"><xsl:value-of select="/html/Body/span/mobile"/></xsl:attribute>
			</xsl:element>
		</div>
	</td>
	<td></td>
</tr>
<tr height="50">
	<td>
		<label class="col-sm-3 control-label">QQ/旺旺：</label>
		<div class="col-sm-9">
			<xsl:element name="INPUT">
				<xsl:attribute name="name">qq</xsl:attribute>
				<xsl:attribute name="class">form-control</xsl:attribute>
				<xsl:attribute name="value"><xsl:value-of select="/html/Body/span/qq"/></xsl:attribute>
			</xsl:element>
		</div>
	</td>
	<td></td>
</tr>
<tr height="50">
	<td>
		<label class="col-sm-3 control-label">管理员帐号：</label>
		<div class="col-sm-9">
			<xsl:element name="INPUT">
				<xsl:attribute name="id">admin_name</xsl:attribute>
				<xsl:attribute name="name">admin_name</xsl:attribute>
				<xsl:attribute name="class">form-control</xsl:attribute>
				<xsl:attribute name="maxlength">20</xsl:attribute>
				<xsl:attribute name="value"><xsl:value-of select="/html/Body/span/admin_name"/></xsl:attribute>
			</xsl:element>
		</div>
	</td>
	<td><font color='red'>*</font> <font color="red" id="error_admin"></font></td>
</tr>
<tr height="50">
	<td>
		<label class="col-sm-3 control-label">管理员密码：</label>
		<div class="col-sm-9"><input class="form-control" id="pw1" type="password" name="passwd" maxlength="40" /></div>
	</td>
	<td><font color='red'>*</font> <font color="red" id="error_password"></font></td>
</tr>
<tr height="50">
	<td>
		<label class="col-sm-3 control-label">确认密码：</label>
		<div class="col-sm-9"><input class="form-control" id="pw2" type="password" maxlength="40" /></div>
	</td>
	<td></td>
</tr>
<tr height="50">
	<td>
		<label class="col-sm-3 control-label"></label>
		<div class="col-sm-9">
			<input type="submit" class="btn btn-primary" value="申请注册"/>
			&#160;&#160;&#160;&#160;&#160;
			<input type="button" class="btn btn-default" value="返回" onclick="top.location.href='/'" />
		</div>
	</td>
</tr>
</table>

</form>

</center>


</xsl:template>

</xsl:stylesheet>
