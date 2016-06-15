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

<script>
function getObject(objName)
{
	if(document.getElementById)
	{
		return eval('document.getElementById("' + objName + '")');
	}
	else if(document.layers)
	{
		return eval("document.layers['" + objName +"']");
	}
	else
	{
		return eval('document.all.' + objName);
	}
}
function trim(str)
{
	return (str.replace(/(\s+)$/g, '')).replace(/^\s+/g, '');
}
function click_checkbox(chk_id, box_id)
{
	if(getObject(chk_id).value == "Y")
	{
		getObject(chk_id).value	= "N";
		getObject(box_id).className	= "checkbox";
	}
	else
	{
		getObject(chk_id).value	= "Y";
		getObject(box_id).className	= "checkbox_ok";
	}
}
var http_referer	= "<xsl:value-of select="/html/head/em/@program"/>";
var site_channel	= new Array();
var site_program	= new Array();

</script>
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
<script type="text/javascript" src="https://g.alicdn.com/sj/securesdk/0.0.3/securesdk_v2.js" id="J_secure_sdk_v2" data-appkey="23283073 "></script>
<script type="text/javascript">
$(document).ready(function(){
	$("img.ready").lazyload({placeholder : "/images/nophoto.gif",effect : "fadeIn"});
});
function MessageBox(url, title, width, height)
{
	if(url == "")
	{
		return false;
	}
	$("#MessageBody").attr("src", url);
	if(width > 0)
	{
		$("#MessageWidth").css("width", width + "px");
	}
	if(height > 0)
	{
		$("#MessageBody").css("height", height + "px");
	}
	if(title != "")
	{
		$("#MessageTitle").html(title);
	}
	$('#MessageBox').modal({show:true, backdrop:'static'});
}
function login_erp()
{
	MessageBox('/login/login_erp.php', '欢迎登录企业ERP系统', 500, 350)
}
//窗口滚动条设置出现在菜单以下部分
$(document).ready(function(){
	D_height = $('#neirong').height();
	var height = window.innerHeight-65;
	var K_height = window.innerHeight-100;
	if(D_height &lt; K_height){
		$('#neirong').height(K_height);
	}
	$('body').css('overflow-y','hidden');
	$('body').css('overflow-x','auto');
	$('#scroll_page').css('overflow-x','hidden');
	$('#scroll_page').height(height);
});
$(window).resize(function(){
	var height = window.innerHeight-65;
	var K_height = window.innerHeight-100;
	if(D_height &lt; K_height){
		$('#neirong').height(K_height);
	}
	$('body').css('overflow-y','hidden');
	$('body').css('overflow-x','auto');
	$('#scroll_page').css('overflow-x','hidden');
	$('#scroll_page').height(height);
})
</script>

</head>
<body style="margin-top:0">
<div id='none'><iframe width='0' height='0' frameborder='0' id='deal' name='deal' src='/none.php'></iframe></div>


<style>
#login_nav .dropdown > a {color:#ffffff; font-size:15px;}
#login_nav .dropdown:hover {background:#2B2B2B}
#login_nav .open > a:focus {background:#393B3D}
#login_nav .dropdown-menu {min-width:120px; background:#2B2B2B}
#login_nav .dropdown-menu a {font-size:14px; color:#ffffff; height:30px; line-height:25px;}
#login_nav .dropdown-menu > li > a:hover {background:#393B3D}
#login_nav .dropdown-menu > .active > a {background:#393B3D}
.navbar-default .navbar-nav > .active > a, .navbar-default .navbar-nav > .active > a:focus, .navbar-default .navbar-nav > .active > a:hover {background:#2B2B2B}
#login_nav .dropdown-menu {border:none;}
<!-- 设置出现滚动条时内容不向左跳动 -->
#beat_page {padding-left: calc(100vw - 100%);}
</style>

<div class=" navbar-default" role="navigation" style="background:#393B3D;position: fixed;top:0;left:0;width:100%;z-index:10000;">
	<a href="/home/index.php"><img src="https://img.alicdn.com/imgextra/i3/2456424883/TB21dYpipXXXXcvXXXXXXXXXXXX_!!2456424883.png" style="height:26px;margin:12px 40px 0 40px;float:left;" alt=""/></a>
	<div class="main_page">
		<div class="navbar-header">
			<button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
				<span class="sr-only">米欢ERP</span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</button>

		</div>
		<div id="login_nav" class="navbar-collapse collapse" style="padding-left: 0;">
			<ul id="login_menu" class="nav navbar-nav">
				<li class="dropdown">
					<a href="/home/index.php">首页</a>
				</li>

			</ul>
			<ul class="nav navbar-nav navbar-right">
				<xsl:if test="/html/head/em/@login_name != 'none'">
				<li class="dropdown">
					<a href="#"  data-toggle="dropdown"><xsl:value-of select="/html/head/em/@login_name"/></a>
					<ul class="dropdown-menu">
						<li><a href='/setting/setting_person_center.php' target="_blank">个人中心</a></li>
						<!-- <li><a href='/setting/setting_account_manage.php' target="_blank">账号设置</a></li> -->
						<li><a href='' target="_blank">账号设置</a></li>
						<li><a href='/setting/help_document.php' target="_blank">帮助文档</a></li>
						<!-- <li><a href='/setting/setting_renew_upgrade.php' target="_blank">续费升级</a></li> -->
						<li><a href='' target="_blank">续费升级</a></li>
						<li><div class="divider_home"></div></li>
						<!-- <li><a href='http://www.imihuan.com'>退出</a></li> -->
						<li><a href='/logoff.php'>退出</a></li>
					</ul>
				</li>
				</xsl:if>
				<xsl:if test="/html/head/em/@login_name = 'none'">
					<li class="dropdown"><a href='/login/login_erp.php' onclick='login_erp(); return false'>点这里登录</a></li>
				</xsl:if>
			</ul>
		</div>
	</div>
</div>

<xsl:element name="SCRIPT">
	<xsl:attribute name="charset">UTF-8</xsl:attribute>
	<xsl:attribute name="src">/style/login_menu.js?ver=<xsl:value-of select="/html/@version"/>&amp;login=<xsl:value-of select="/html/head/em/@login_name"/></xsl:attribute>
</xsl:element>
<!-- 此div设置滚动条出现在菜单以下部分 -->
<div id='scroll_page' style="overflow-y:auto;">
	<div id='beat_page'>
		<div id='neirong'>
			<div id="main_page">
				<div id="location_dir"><xsl:value-of select="/html/Body/dir" disable-output-escaping="yes" /></div>
				<xsl:if test="system-property('xsl:vendor')='Transformiix'">
					<script language="javascript">
					var location_dir = document.getElementById("location_dir");
					location_dir.innerHTML = location_dir.textContent;
					</script>
				</xsl:if>
				<xsl:if test="/html/head/em/@permit != '1'">
					<xsl:call-template name="unlogin"></xsl:call-template>
				</xsl:if>
			</div>


			<div class="main_page">
			<xsl:if test="/html/head/em/@permit = '1'">
				<xsl:call-template name="text"></xsl:call-template>
			</xsl:if>
			</div>
		</div>
		<div class="clear"></div>
		<div class="clear"></div>
		<div class="clear"></div>
		<div class="site_footer" style='background:#fff;color:#aaa;'>
			[C] 2015 杭州米欢软件有限公司 版权所有
			京ICP备09092621号-8
			<div class="clear"></div>
		</div>
	</div>
</div>

<div class="modal fade" id="MessageBox">
	<div class="modal-dialog" id="MessageWidth">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				<h4 class="modal-title" id="MessageTitle">系统消息</h4>
			</div>
			<div class="modal-body">
				<iframe id="MessageBody" width="100%" height="100" frameborder="0" src="about:blank"></iframe>
			</div>
		</div>
	</div>
</div>

</body>
</html>

</xsl:template>

<xsl:template name="icon">
	<xsl:param name="x"/><xsl:param name="y"/>
	<xsl:element name="IMG">
		<xsl:attribute name="src">/images/space.gif</xsl:attribute><xsl:attribute name="class">icon</xsl:attribute>
		<xsl:attribute name="style">font-size:1px; background-position:-<xsl:value-of select="$x"/>px -<xsl:value-of select="$y"/>px</xsl:attribute>
	</xsl:element>
</xsl:template>

<xsl:template name="page">

<xsl:element name="FORM">
	<xsl:attribute name="id">form_page_info</xsl:attribute>
	<xsl:attribute name="action"><xsl:value-of select="/html/head/em/@program"/></xsl:attribute>
	<xsl:attribute name="class">form-inline</xsl:attribute>
	<xsl:attribute name="method">get</xsl:attribute>

	<input type="hidden" id="_page_size_" value="0"/>
	<xsl:for-each select="/html/Body/page_info/param">
		<xsl:element name="INPUT">
			<xsl:attribute name="type">hidden</xsl:attribute>
			<xsl:attribute name="name"><xsl:value-of select="@name"/></xsl:attribute>
			<xsl:attribute name="value"><xsl:value-of select="."/></xsl:attribute>
		</xsl:element>
	</xsl:for-each>

	<div class="erp_page">
		<ul>
			<li>
				<div class="form-group">
					<label>每页：</label>
					<select class="form-control input-sm page_size" onchange="getObject('_page_size_').name='_page_size_'; getObject('_page_size_').value=this.value; getObject('form_page_info').submit()">
						<xsl:if test="not(/html/Body/page_info/size)"><option>10</option></xsl:if>
						<xsl:for-each select="/html/Body/page_info/size">
							<xsl:element name="OPTION">
								<xsl:attribute name="value"><xsl:value-of select="."/></xsl:attribute>
								<xsl:if test="@current='Y'"><xsl:attribute name="selected">true</xsl:attribute></xsl:if>
								<xsl:value-of select="."/>
							</xsl:element>
						</xsl:for-each>
					</select>
				</div>
			</li>
			<li>
				<xsl:element name="A">
					<xsl:attribute name="class">first_page</xsl:attribute>
					<xsl:attribute name="href"><xsl:value-of select="/html/head/em/@program"/>?page=1<xsl:for-each select="/html/Body/page_info/param">&amp;<xsl:value-of select="@name"/>=<xsl:value-of select="."/></xsl:for-each></xsl:attribute>
					首页
				</xsl:element>
			</li>
			<li>
				<xsl:element name="A">
					<xsl:attribute name="class">prev_page</xsl:attribute>
					<xsl:attribute name="href"><xsl:value-of select="/html/head/em/@program"/>?page=<xsl:value-of select="/html/Body/page_info/prev"/><xsl:for-each select="/html/Body/page_info/param">&amp;<xsl:value-of select="@name"/>=<xsl:value-of select="."/></xsl:for-each></xsl:attribute>
					上一页
				</xsl:element>
			</li>
			<li>
				第
				<xsl:element name="INPUT">
					<xsl:attribute name="type">text</xsl:attribute>
					<xsl:attribute name="name">page</xsl:attribute>
					<xsl:attribute name="class">form-control input-sm current_page</xsl:attribute>
					<xsl:attribute name="value">
						<xsl:value-of select="/html/Body/page_info/page"/>
						<xsl:if test="not(/html/Body/page_info/page)">1</xsl:if>
					</xsl:attribute>
				</xsl:element>
				页
				(
				共 <xsl:value-of select="/html/Body/page_info/max_page" /><xsl:if test="not(/html/Body/page_info/max_page)">1</xsl:if> 页,
				<xsl:value-of select="/html/Body/page_info/total" /><xsl:if test="not(/html/Body/page_info/total)">1</xsl:if> 条
				)
			</li>
			<li>
				<xsl:element name="A">
					<xsl:attribute name="class">next_page</xsl:attribute>
					<xsl:attribute name="href"><xsl:value-of select="/html/head/em/@program"/>?page=<xsl:value-of select="/html/Body/page_info/next"/><xsl:for-each select="/html/Body/page_info/param">&amp;<xsl:value-of select="@name"/>=<xsl:value-of select="."/></xsl:for-each></xsl:attribute>
					下一页
				</xsl:element>
			</li>
			<li>
				<xsl:element name="A">
					<xsl:attribute name="class">last_page</xsl:attribute>
					<xsl:attribute name="href"><xsl:value-of select="/html/head/em/@program"/>?page=<xsl:value-of select="/html/Body/page_info/max_page"/><xsl:for-each select="/html/Body/page_info/param">&amp;<xsl:value-of select="@name"/>=<xsl:value-of select="."/></xsl:for-each></xsl:attribute>
					末页
				</xsl:element>
			</li>
		</ul>
	</div>
</xsl:element>

</xsl:template>


</xsl:stylesheet>
