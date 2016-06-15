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
<!-- <script type="text/javascript" charset="UTF-8" src="/js_encode/jquery.smallslider.js"></script> -->
<!-- <script type="text/javascript" charset="UTF-8" src="/js_encode/jquery.masonry.min.js"></script> -->
<script type="text/javascript" charset="UTF-8" src="/js_encode/bootstrap.min.js"></script>
<!-- <script type="text/javascript" charset="UTF-8" src="/js_encode/jquery.area.three.js"></script> -->
<script type="text/javascript" charset="UTF-8" src="/js_encode/jquery.form.js"></script>
<!-- <script type="text/javascript" charset="UTF-8" src="/js_encode/jquery.validate.js"></script> -->
<style>
	.button {
	 	width: 68px;
   		height: 26px;
   		font-size: 12px;
   		border: 1px solid #cccccc;
   		margin: auto;
   		text-align: center;
   		cursor: pointer;
   		border-radius: 2px;
   		background :#f4f4f4;
	}
</style>
</head>
<body style="padding-top:0px;">
	<!-- 邀请 -->
	<div class="modal fade in" id="MessageBox" style="border:0px">
		<div class="modal-dialog" id="MessageWidth" style="width: 600px;border:0px;">
			<div class="modal-content" style="border:0px">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
					<font size="3px" class="modal-title" id="MessageTitle">邀请伙伴加入米欢</font>
				</div>
				<div class="modal-body" style="padding:0px">
					<iframe width="100%" height="100" frameborder="0" id="MessageBody" src="" style="height: 306px;border-radius:0 0 6px 6px"></iframe>
				</div>
			</div>
		</div>
	</div>
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
	</div>
	<div style="width:100%;height:360px;background-color:#f4f5fa;float:left;padding-top:20px;text-align: center">
		<div style="width:100%;height:120px;float:left;padding-top:30px;text-align:center;background:url('https://img.alicdn.com/imgextra/i1/85662775/TB2xKNThFXXXXXoXXXXXXXXXXXX_!!85662775.jpg')">
			<span style="font-size:24px;color:white;">
			<xsl:value-of select='/html/Body/staff_info/company_name' />
			</span><br/>
			<span style="font-size:12px;color:white;">账户名：
			<xsl:value-of select='/html/Body/staff_info/staff_name' />
			</span>
		</div>
		<div style="width:990px;margin-left: auto;margin-right: auto;color:#666;">
			<div style="width:470px;height:300px;float:left;">
				<div style="width:100%;height:60px;border-bottom:1px solid #d4d4d4;float:left;padding-top:20px;">
					<span style="height:40px;float:left;line-height:40px;font-size:16px;">帐户余额</span>
					<span style="height:40px;float:right;color:#999;line-height:40px;">单位：元</span>
				</div>
				<div style="width:100%;height:300px;float:left;">
					<div style="width:150px;height:150px;float:left;padding-top:20px;">
						<span style="font-size:14px;">可用余额</span><br/>
						<span style="font-size:14px;margin-top:20px;margin-bottom:20px;display:block;">1000</span>
						<span><button class="button tixian">提现</button></span>
					</div>
					<div style="width:150px;height:150px;float:left;margin-left:10px;padding-top:20px;">
						<span style="font-size:14px;">不可用余额 </span><br/>
						<span style="font-size:14px;margin-top:20px;display:block;">1233</span>
					</div>
				</div>
			</div>
			<div style="width:470px;height:300px;float:right;">
				<div style="width:100%;height:60px;float:left;padding-top:20px;border-bottom:1px solid #d4d4d4;">
					<span style="height:40px;float:left;line-height:40px;font-size:16px;">服务信息</span>
				</div>
				<!-- <div style="width:100%;border:1px solid #d4d4d4;float:left;"></div> -->
				<div style="width:100%;height:300px;float:left;">
					<div style="width:150px;height:150px;float:left;padding-top:20px;">
						<span style="font-size:14px;">版本</span> <br/>
						<span style="font-size:14px;margin-top:20px;display:block;">按订单收费</span>
					</div>
					<div style="width:150px;height:150px;float:left;margin-left:10px;padding-top:20px;">
						<span style="font-size:14px;">有效期 </span><br/>
						<span style="font-size:14px;margin-top:20px;display:block;">2015年11月23日</span>
					</div>
					<div style="width:150px;height:150px;float:left;margin-left:10px;padding-top:20px;">
						<span style="font-size:14px;">我的邀请</span><br/>
						<span style="font-size:14px;margin-top:20px;margin-bottom:20px;display:block;">123</span>
						<span>
							<button class="button invite" >邀请</button>
						</span>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div style="width:990px;margin-left: auto;margin-right: auto;">
		<div style="width:100%;margin-top:20px;float:left;">
			<span style="height:40px;float:left;line-height:40px;font-size:16px;color:#666;padding-bottom:42px">记录</span>
			<table style="width:100%;border-top:1px solid #d4d4d4;color:#666;">
				<tr style="width:100%;height:50px;border-bottom:1px solid #d4d4d4;">
					<td style="width:280px;">2015年11月28日</td>
					<td style="width:450px;">将阿斯兰多夫</td>
					<td style="width:260px;">+200元</td>
				</tr>
				<tr style="width:100%;height:50px;border-bottom:1px solid #d4d4d4;">
					<td>2015年11月28日</td>
					<td>将阿</td>
					<td>+200元</td>
				</tr>
			</table>
		</div>
	</div>
<script>
	$('.close').click(function(){
		$("#MessageBox").modal('hide');
		$('.modal-backdrop').remove(); 
	});
	
	$('.tixian').click(function(){
		$('#MessageBox').after('&lt;div class="modal-backdrop fade in">&lt;/div>');
	});
	
	$('.invite').click(function(){
		$('#MessageBox').after('&lt;div class="modal-backdrop fade in">&lt;/div>');
		$('#MessageBody').attr('src','/setting/setting_person_invite.php');
		$('#MessageBox').modal({backdrop: 'static', keyboard: false},'show');
	});

</script>
</body>
</html>
</xsl:template>

</xsl:stylesheet>
