<?xml version="1.0" encoding="utf-8" ?>
<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<!-- UTF8 编码 -->

<xsl:include href="/style/header.xsl" />

<xsl:template name="text">

	<div id="html_body"><xsl:value-of select="/html/Body/content" disable-output-escaping="yes"/></div>
	<xsl:if test="system-property('xsl:vendor')='Transformiix'">
	<script language="javascript">
	var el = document.getElementById("html_body");
	el.innerHTML = el.textContent;
	</script>
	</xsl:if>
	<xsl:if test="/html/Body/img/@src != ''">
		<br/><br/>设计稿图片:<br/><br/>
		<xsl:element name="IMG">
			<xsl:attribute name="src">/design/<xsl:value-of select="/html/Body/img/@src"/></xsl:attribute>
		</xsl:element>
	</xsl:if>

</xsl:template>

</xsl:stylesheet>
