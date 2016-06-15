<?xml version="1.0" encoding="UTF-8" ?>
<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output method="html" encoding="UTF-8"/>
<!-- UTF8 编码 -->

<xsl:template name="unlogin">
<center>
	<br/><br/><br/><br/>
	<table width="480" align="center">
	<tr>
		<td>您没有权限访问此页面，可能有如下几个原因:</td>
	</tr>
	</table>
	<blockquote class="unlogin">
		1. 您还没有登录或注册，暂时不能使用此功能！<br/>
		2. 您没有权限操作本页面，请联系公司管理员开通权限！<br/>
		<xsl:if test="/html/head/em/@company_id = '0' and /html/head/em/@permit = '1'">
			3. 若您是第一次登录本站或者没有创建公司，<a href="/setting/index.php">请先创建公司</a>！<br/>
		</xsl:if>
		<xsl:if test="/html/head/em/@login_name = 'none'">
			<a href="/login/login_erp.php" onclick="login_erp(); return false">点这里进行登录</a>
		</xsl:if>
	</blockquote>
</center>
<br/><br/>

</xsl:template>

</xsl:stylesheet>
