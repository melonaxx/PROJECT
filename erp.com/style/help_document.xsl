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
<!-- <script type="text/javascript" charset="UTF-8" src="/js_encode/jquery.lazyload.mini.js"></script> -->
<script type="text/javascript" charset="UTF-8" src="/js_encode/jquery.smallslider.js"></script>
<!-- <script type="text/javascript" charset="UTF-8" src="/js_encode/jquery.masonry.min.js"></script> -->
<script type="text/javascript" charset="UTF-8" src="/js_encode/bootstrap.min.js"></script>
<!-- <script type="text/javascript" charset="UTF-8" src="/js_encode/jquery.area.three.js"></script> -->
<script type="text/javascript" charset="UTF-8" src="/js_encode/jquery.form.js"></script>
<!-- <script type="text/javascript" charset="UTF-8" src="/js_encode/jquery.validate.js"></script> -->

<style>
	.modal-body {
		width:100%;
		float:left;
	}
	.dropdown {
		text-align:left;
		width:100%;
		float:left;
	}
	.dropdown_header{
		float:left;
	}
	.dropdown_body {
		text-align:left;
		width:100%;
		float:left;
		display:none;
		margin-left:20px;
	}
</style>
</head>
<body style="padding-top:0px;">

	<div style="height: 60px;width: 100%;background-color: #ffffff;box-shadow: 0 2px 3px 0 rgba(0,0,0,.05);text-align: center">
		<div style="width:1200px;height:60px;margin-left: auto;margin-right: auto;">
			<span style=" cursor: pointer;display: inline-block;color: #383838;float:left;font-size: 16px;line-height: 60px;">账户中心</span>
			<span style="font-size:12px;float:right;margin-top:22px;">
				<xsl:value-of select='/html/Body/staff_info/staff_nick' />
			</span>
			<span style="font-size:12px;float:right;margin-top:22px;margin-right:30px;">
				<a href="/home/index.php">返回米欢ERP</a>
			</span>
		</div>
		<div  class="mainBody" style="width:1200px;height:300px;margin:15px auto 0px;">
			<div style="width:260px;height:300px;float:left;border:1px solid red;">
				<div class="modal-body" >
					<div class="dropdown">
						<span style="margin-left:10px;">系统应用流程</span><br/>
						<span style="margin-left:10px;">使用功能详解</span>
					</div>
					<xsl:for-each select='/html/Body/caidan/ul/li'>
						<div class="dropdown">
							<div class="dropdown_header" >
								<span class="car_left"><span class="caret"></span></span><xsl:value-of select='value' />
							</div>
							<xsl:for-each select='dd/dl'>
								<div class="dropdown_body">
									<xsl:value-of select='text' />
								</div>
							</xsl:for-each>
						</div>
					</xsl:for-each>
				</div>
			</div>
			<div style='width:920px;height:300px;float:left;margin-left:20px;border:1px solid red;'>
				一、新用户指导
					1.创建仓库
					2.添加账户
					3.添加供应商
					4.设置
			</div>
		</div>
	</div>
</body>
<script>
$(function(){
	$(".car_left").click(function(){
		$('.dropdown_body').css('display','none');
		$(this).parents(".dropdown").find(".dropdown_body").slideToggle();
	});
})
</script>
</html>
</xsl:template>

</xsl:stylesheet>
