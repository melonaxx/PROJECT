<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<xsl:output method="html" encoding="UTF-8"/>
<!-- UTF8 编码 -->

<xsl:include href="/style/unlogin.xsl" />

<xsl:template match="/html/Body" name="empty"></xsl:template>

<xsl:template match="/html/head" name="main">

<html>
<head>
<meta http-equiv="X-UA-Compatible" content="IE=edge" />
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta http-equiv="imagetoolbar" content="no" />
<title><xsl:value-of select="Title" /></title>

<xsl:element name="LINK">
	<xsl:attribute name="type">text/css</xsl:attribute><xsl:attribute name="rel">stylesheet</xsl:attribute>
	<xsl:attribute name="href">/style/bootstrap.min.css?ver=<xsl:value-of select="/html/@version"/></xsl:attribute>
</xsl:element>

<xsl:element name="LINK">
	<xsl:attribute name="type">text/css</xsl:attribute><xsl:attribute name="rel">stylesheet</xsl:attribute>
	<xsl:attribute name="href">/style/style.css?ver=<xsl:value-of select="/html/@version"/></xsl:attribute>
</xsl:element>

<xsl:element name="link">
	<xsl:attribute name="rel">Shortcut Icon</xsl:attribute>
	<xsl:attribute name="href">
		<xsl:if test="/html/head/link/@rel = 'Shortcut Icon'"><xsl:value-of select="/html/head/link/@href"/></xsl:if>
		<xsl:if test="string-length(/html/head/link/@rel) = 0">/favicon.ico</xsl:if>
	</xsl:attribute>
</xsl:element>

<xsl:element name="SCRIPT">
	<xsl:attribute name="charset">UTF-8</xsl:attribute>
	<xsl:attribute name="src">/js_encode/function.js?ver=<xsl:value-of select="/html/@version"/></xsl:attribute>
</xsl:element>
<script type="text/javascript" charset="UTF-8" src="/js_encode/jquery-1.9.1.min.js"></script>
<script type="text/javascript" charset="UTF-8" src="/js_encode/jquery.lazyload.mini.js"></script>
<script type="text/javascript" charset="UTF-8" src="/js_encode/jquery.smallslider.js"></script>
<script type="text/javascript" charset="UTF-8" src="/js_encode/jquery.masonry.min.js"></script>
<script type="text/javascript" charset="UTF-8" src="/js_encode/bootstrap.min.js"></script>
<script type="text/javascript" charset="UTF-8" src="/js_encode/jquery.area.three.js"></script>
<script type="text/javascript" charset="UTF-8" src="/js_encode/jquery.form.js"></script>
<script type="text/javascript" charset="UTF-8" src="/js_encode/jquery.validate.js"></script>


</head>
<body style="width:120px;margin-top:0;">
	
	<div style="width:100%;float:left;">
		<div style="width:100%;">
			<div style="width:100%;height:30px;float:left;"><span style="float:left;font-size:16px;">我的邀请</span></div>
			<div style="width:150px;height:100px;float:left;border:1px solid #ccc;"></div>
			<div style="width:150px;height:100px;float:left;border:1px solid #ccc;margin-left:40px;"></div>
		</div>
		<div style="width:100%;height:60px;float:left;"><span style="float:left;margin-top:30px;font-size:16px;">通过链接邀请伙伴</span></div>
		<div style="width:100%;height:40px;float:left;border:1px solid #ccc;padding-top:10px;">
			<span style="float:left;">www.imihuan.com</span>
		</div>
		<div style="width:100%;height:60px;float:left;border-bottom:2px solid #000;">
			<span style="float:left;margin-top:30px;font-size:16px;">我的邀请</span>
		</div>
		<div style="width:100%;height:100px;float:left;border-bottom:2px solid #ccc;">
			<div style="width:200px;height;100px;float:left;padding-top:40px;font-size:14px;">2015年11月23日<br/>17:15</div>
			<div style="width:400px;height:100px;float:left;padding-top:40px;font-size:14px;">香瓜子科技有限公司</div>
			<div style="width:400px;height:100px;float:left;padding-top:40px;font-size:14px;">按订单付费</div>
			<div style="width:200px;height:100px;float:left;padding-top:40px;font-size:14px;">300</div>
		</div>
		<div style="width:100%;height:100px;float:left;border-bottom:2px solid #ccc;">
			<div style="width:200px;height;100px;float:left;padding-top:40px;font-size:14px;">2015年11月23日<br/>17:15</div>
			<div style="width:400px;height:100px;float:left;padding-top:40px;font-size:14px;">香瓜子科技有限公司</div>
			<div style="width:400px;height:100px;float:left;padding-top:40px;font-size:14px;">按订单付费</div>
			<div style="width:200px;height:100px;float:left;padding-top:40px;font-size:14px;">300</div>
		</div>
	</div>

</body>
</html>
</xsl:template>

</xsl:stylesheet>
