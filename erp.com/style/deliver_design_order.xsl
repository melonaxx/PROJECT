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


<script type="text/javascript" charset="UTF-8" src="/js_encode/jquery-1.9.1.min.js"></script>
<script type="text/javascript" charset="UTF-8" src="/js_encode/bootstrap.min.js"></script>
<script type="text/javascript" charset="UTF-8" src="/js_encode/jquery.lazyload.mini.js"></script>
<script type="text/javascript" charset="UTF-8" src="/js_encode/jquery.smallslider.js"></script>
<script type="text/javascript" charset="UTF-8" src="/js_encode/jquery.masonry.min.js"></script>
<script type="text/javascript" charset="UTF-8" src="/js_encode/deliver_design_order.js"></script>
<script type="text/javascript" src="/js_encode/LodopFuncs.js"></script>


<style type="text/css">
	.box{
		width:870px;
		position:fixed;
		top:2px;
		<!-- height:300px; -->
		border:1px solid #ddd;
		border-radius:3px;
	}
	.smallbox{
		position:absolute;
		width:490px;
		<!-- height:300px; -->
		border:1px solid #ddd;
		left:875px;
		border-radius: 3px;
		<!-- margin-right:100px; -->
		margin:0px 0px;
	}
	.two a:visited{
		color:red;
	}
	table,tr,th,td{
		border:1px solid #ddd;
	}
	.btn-sm{margin-left:20px}
	
	.dropdown_header{height:30px;text-align:left;background:#eee;padding:0px 20px;line-height:30px;border:1px solid #aaa;border-radius: 3px;}
	.car_right,.car_left{width:25px;height:25px;margin-top:2px;border-radius: 3px;line-height: 1.5;padding: 4px 5px;}
	.car_right{float:right;display:block;}
	
	.car_right:hover,.car_left:hover{background:#bbb;border:1px solid lightblue;padding-right:10px:}
	.car_right .caret{margin-right:10px}
	.dropdown_body{line-height:40px;text-align:left;padding:0px 10px;display:}
	.dropdown_body_header{height:30px}
	.dropdown_body_body{line-height:28px;display:none}
	<!-- .dropdown_body_body span{width:88px;overflow:visible;margin-right:5px;display:inline-block} -->
	
</style>
</head>
<body style="margin-top:0;padding:0px">
<!-- 控件安装提示 -->
<div id="confirm" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none;width:268px;margin:65px auto">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
			<h4 class="modal-title" id="myModalLabel">提示</h4>
		</div>
		<div class="modal-body" style="margin-left:20px"></div>
		<div class="modal-footer"><button type="button" class="btn btn-default btn-sm ok" data-dismiss="modal">确定</button></div>
	</div>
</div>
<div class="mainBody" style="margin:2px 0px">
	<div id="myTabContent" class="tab-content">
		
		<!-- <div class="tab-pane fade in active" id="print_order"> -->
		<div class="smallbox">
			<div class="dropdown">
				<div class="dropdown_header" >
					<span class="title">选择打印模板</span>
			    	<span class="car_right"><span class="caret"></span></span>
				</div>
				<div class="dropdown_body">
					<a href="javascript:;" style="margin:0 50px 0 50px;" data-dismiss="modal"  class="btn btn-default btn-sm" id="order_template" >切换模板</a>
					<select name="order_template" class="form-control input-sm" style="display:none;" >
						<xsl:for-each select="/html/Body/order_template/item">
						<xsl:element name="OPTION">
							<xsl:attribute name="value"><xsl:value-of select="id"></xsl:value-of></xsl:attribute>
							<xsl:value-of select="name"></xsl:value-of>
						</xsl:element>
						</xsl:for-each>
					</select>
					<!-- <label class="margin_left_1">模板名称：</label> -->
					<xsl:element name="LABEL">
						<xsl:attribute name="value"><xsl:value-of select="/html/Body/page_info/template_id"></xsl:value-of></xsl:attribute>
						<xsl:attribute name="class">margin_left_1</xsl:attribute>
						<xsl:attribute name="id">template_id</xsl:attribute>
						模板名称：
					</xsl:element>
					<xsl:element name="INPUT">
						<xsl:attribute name="type">text</xsl:attribute>
						<xsl:attribute name="style">width:200px;display:inline;</xsl:attribute>
						<xsl:attribute name="class">form-control input-sm</xsl:attribute>
						<xsl:attribute name="value"><xsl:value-of select="/html/Body/page_info/template_name"></xsl:value-of></xsl:attribute>
						<xsl:attribute name="aria-describedby">sizing-addon2</xsl:attribute>
						<xsl:attribute name="id">template_name</xsl:attribute>
					</xsl:element>
					<xsl:element name="INPUT">
						<xsl:attribute name="type">hidden</xsl:attribute>
						<xsl:attribute name="value"><xsl:value-of select="/html/Body/page_info/template_image"></xsl:value-of></xsl:attribute>
						<xsl:attribute name="id">template_image</xsl:attribute>
					</xsl:element>

				</div>
			</div>
			<div class="dropdown">
				<div class="dropdown_header" >
					<span class="title">设计辅助配置-只在设计模板时使用</span>
			    	<span class="car_right"><span class="caret"></span></span>
				</div>
				<div class="dropdown_body">
					<label class="margin_left_1">预览张数：</label>
					<input type="text" style="width:60px;display:inline;" class="form-control input-sm" value="1" aria-describedby="sizing-addon2" id="preview_num"/>　
					<span>只在设计时使用！调整多张后，可用来测试连打效果</span>
					<br/>
					<label class="margin_left_1">明细条目：</label>
					<input type="text" style="width:60px;display:inline;" class="form-control input-sm" value="10" aria-describedby="sizing-addon2" id="every_page_num"/>　
					<span>只在设计时使用！可以测试明细条目是的自动换页效果</span>
				</div>
			</div>
		<!-- 	<div class="dropdown">
				<div class="dropdown_header" >
					<span class="title">上传图片-图片用作背景时不会打印，仅用于辅助调整打印项位置</span>
			    	<span class="car_right"><span class="caret"></span></span>
				</div>
				<div class="dropdown_body">
					<form enctype="multipart/form-data" method="post">
					<label class="margin_left_1">上传图片：</label>
					<input type="file" style="width:240px;display:inline;padding: 0 5px;" class="form-control input-sm" aria-describedby="sizing-addon2"  name="template_pic"/>
					<br/>
					<botton class="btn btn-default btn-sm" data-dismiss="modal">设为背景图片</botton>
					<botton class="btn btn-default btn-sm" onclick="upload_template_pic()" data-dismiss="modal">上传图片</botton>
					</form>
				</div>
			</div> -->

			<div class="dropdown">
				<div class="dropdown_header" >
					<span class="title">纸张信息-打印时按此处设置的纸张大小进行打印</span>
			    	<span class="car_right"><span class="caret"></span></span>
				</div>
				<div class="dropdown_body">
					
					<label class="margin_left_2">向下偏：</label>
					<xsl:element name="INPUT">
						<xsl:attribute name="type">text</xsl:attribute>
						<xsl:attribute name="style">width:50px;display:inline;padding: 0 5px;</xsl:attribute>
						<xsl:attribute name="class">form-control input-sm</xsl:attribute>
						<xsl:attribute name="value"><xsl:value-of select="/html/Body/page_info/page_top"></xsl:value-of></xsl:attribute>
						<xsl:attribute name="aria-describedby">sizing-addon2</xsl:attribute>
						<xsl:attribute name="id">page_top</xsl:attribute>
					</xsl:element>
				
				
					<label class="margin_left_1">向右偏：</label>
					<xsl:element name="INPUT">
						<xsl:attribute name="type">text</xsl:attribute>
						<xsl:attribute name="style">width:50px;display:inline;padding: 0 5px;</xsl:attribute>
						<xsl:attribute name="class">form-control input-sm</xsl:attribute>
						<xsl:attribute name="value"><xsl:value-of select="/html/Body/page_info/page_left"></xsl:value-of></xsl:attribute>
						<xsl:attribute name="aria-describedby">sizing-addon2</xsl:attribute>
						<xsl:attribute name="id">page_left</xsl:attribute>
					</xsl:element>
					（单位：mm）
					<br/>
					<label class="margin_left_2">纸张名：</label>
					<select style="width:160px;display:inline;padding: 0 5px;"  class="form-control input-sm" id="page_name">
						<option value="0">自定义</option>
						<option value="A0">A0</option>
						<option value="A1">A1</option>
						<option value="A2">A2</option>
						<option value="A3">A3</option>
						<option value="A4">A4</option>
						<option value="A5">A5</option>
						<option value="A6">A6</option>
						<option value="B4">B4</option>
						<option value="B5">B5</option>
						<option value="8K">8K</option>
						<option value="16K">16K</option>
					</select>

					<label class="margin_left_2">纸张宽：</label>
					<xsl:element name="INPUT">
						<xsl:attribute name="type">text</xsl:attribute>
						<xsl:attribute name="style">width:50px;display:inline;padding: 0 5px;</xsl:attribute>
						<xsl:attribute name="class">form-control input-sm</xsl:attribute>
						<xsl:attribute name="value"><xsl:value-of select="/html/Body/page_info/page_width"></xsl:value-of></xsl:attribute>
						<xsl:attribute name="aria-describedby">sizing-addon2</xsl:attribute>
						<xsl:attribute name="id">page_width</xsl:attribute>
					</xsl:element>
				
					<label class="margin_left_1">纸张高：</label>
					<xsl:element name="INPUT">
						<xsl:attribute name="type">text</xsl:attribute>
						<xsl:attribute name="style">width:50px;display:inline;padding: 0 5px;</xsl:attribute>
						<xsl:attribute name="class">form-control input-sm</xsl:attribute>
						<xsl:attribute name="value"><xsl:value-of select="/html/Body/page_info/page_height"></xsl:value-of></xsl:attribute>
						<xsl:attribute name="aria-describedby">sizing-addon2</xsl:attribute>
						<xsl:attribute name="id">page_height</xsl:attribute>
					</xsl:element>
					<br/>
					<!-- <label class="margin_left_2">打印机：</label>
					<select style="width:160px;display:inline;padding: 0 5px;"  class="form-control input-sm" name="printer"></select> -->
				
					
				
				
					<label class="margin_left_1">打印方向：</label>
					<select style="width:160px;display:inline;padding: 0 5px;"  class="form-control input-sm" id="print_orient">
						<xsl:element name="OPTION">
								<xsl:attribute name="value">1</xsl:attribute>
								<xsl:if test="1=/html/Body/page_info/print_orient">    <xsl:attribute name="selected">selected
									</xsl:attribute>
								</xsl:if>
								纵向打印，固定纸张
						</xsl:element>
						<xsl:element name="OPTION">
								<xsl:attribute name="value">2</xsl:attribute>
								<xsl:if test="2=/html/Body/page_info/print_orient">    <xsl:attribute name="selected">selected
									</xsl:attribute>
								</xsl:if>
								横向打印，固定纸张
						</xsl:element>
						<xsl:element name="OPTION">
								<xsl:attribute name="value">3</xsl:attribute>
								<xsl:if test="3=/html/Body/page_info/print_orient">    <xsl:attribute name="selected">selected
									</xsl:attribute>
								</xsl:if>
								纵向打印，宽度固定，高度按打印内容的高度自适应
						</xsl:element>

					</select>

					<label class="margin_left_1">边框设置：</label>
						<label style="margin-right:15px;line-height:25px;">
						<xsl:element name="INPUT">
								<xsl:attribute name="type">radio</xsl:attribute>
								<xsl:attribute name="value">1</xsl:attribute>
								<xsl:attribute name="name">border</xsl:attribute>
								<xsl:attribute name="style">margin:5px 3px</xsl:attribute>
								<xsl:if test="1 = /html/Body/page_info/border"><xsl:attribute name="checked">checked</xsl:attribute>
								</xsl:if>
						</xsl:element>
						有</label>
						<label style="margin-right:15px;line-height:25px;">
						<xsl:element name="INPUT">
								<xsl:attribute name="type">radio</xsl:attribute>
								<xsl:attribute name="value">0</xsl:attribute>
								<xsl:attribute name="name">border</xsl:attribute>
								<xsl:attribute name="style">margin:5px 3px</xsl:attribute>
								<xsl:if test="0 = /html/Body/page_info/border"><xsl:attribute name="checked">checked</xsl:attribute>
								</xsl:if>
						</xsl:element>
						无</label>
						
						

				</div>
			</div>
			<div class="dropdown">
				<div class="dropdown_header" >
					<span class="title">打印项-自动获取订单、店铺、买家信息等进行打印（自定义打印项除外）</span>
			    	<span class="car_right"><span class="caret"></span></span>
				</div>
				
				<div class="dropdown_body">
					<div class="dropdown_body_header">
			    		<span class="car_left"><span class="caret"></span></span>
						<span class="title">配货单标题</span>
					</div>
					<div class="dropdown_body_body">
						<xsl:for-each select="/html/Body/title/item">
						<span style="width:88px;overflow:visible;margin-right:5px;display:inline-block">
							<xsl:element name="INPUT">
								<xsl:attribute name="type">checkbox</xsl:attribute>
								<xsl:attribute name="style">width:8px;display:inline;margin: 2px 0px;</xsl:attribute>
								<xsl:attribute name="name"><xsl:value-of select="english" /></xsl:attribute>
								<xsl:attribute name="id_exist"><xsl:value-of select="id" /></xsl:attribute>
								<xsl:attribute name="item_top"><xsl:value-of select="item_top" /></xsl:attribute>
								<xsl:attribute name="item_left"><xsl:value-of select="item_left" /></xsl:attribute>
								<xsl:attribute name="item_width"><xsl:value-of select="item_width" /></xsl:attribute>
								<xsl:attribute name="item_height"><xsl:value-of select="item_height" /></xsl:attribute>
								<xsl:attribute name="id"><xsl:value-of select="item_id" /></xsl:attribute>
								<xsl:attribute name="item_font_size"><xsl:value-of select="item_font_size" /></xsl:attribute>
								<xsl:attribute name="item_font_bold"><xsl:value-of select="item_font_bold" /></xsl:attribute>
								<xsl:attribute name="item_type"><xsl:value-of select="item_type" /></xsl:attribute>
								<xsl:attribute name="english"><xsl:value-of select="english" /></xsl:attribute>
								<xsl:attribute name="class">form-control input-sm order_item</xsl:attribute>
								<xsl:attribute name="value"><xsl:value-of select="name" /></xsl:attribute>
								<xsl:attribute name="item_content"><xsl:value-of select="item_content" /></xsl:attribute>
							</xsl:element>
							<xsl:element name="LABEL">
								<xsl:attribute name="class">margin_left_0</xsl:attribute>
								<xsl:attribute name="for"><xsl:value-of select="item_id" /></xsl:attribute>
								<xsl:value-of select="name" />
							</xsl:element>
						</span>
						</xsl:for-each>
					</div>
				</div>
				<div class="dropdown_body">
					<div class="dropdown_body_header">
			    		<span class="car_left"><span class="caret"></span></span>
						<span class="title">配货单头部</span>
					</div>
					<div class="dropdown_body_body">
						
						<xsl:for-each select="/html/Body/order/item">
						<span style="width:88px;overflow:visible;margin-right:5px;display:inline-block">
							<xsl:element name="INPUT">
								<xsl:attribute name="type">checkbox</xsl:attribute>
								<xsl:attribute name="style">width:8px;display:inline;margin: 2px 0px;</xsl:attribute>
								<xsl:attribute name="id_exist"><xsl:value-of select="id" /></xsl:attribute>
								<xsl:attribute name="id"><xsl:value-of select="item_id" /></xsl:attribute>
								<xsl:attribute name="name"><xsl:value-of select="english" /></xsl:attribute>
								<xsl:attribute name="item_top"><xsl:value-of select="item_top" /></xsl:attribute>
								<xsl:attribute name="item_left"><xsl:value-of select="item_left" /></xsl:attribute>
								<xsl:attribute name="item_width"><xsl:value-of select="item_width" /></xsl:attribute>
								<xsl:attribute name="item_height"><xsl:value-of select="item_height" /></xsl:attribute>
								<xsl:attribute name="item_font_size"><xsl:value-of select="item_font_size" /></xsl:attribute>
								<xsl:attribute name="item_font_bold"><xsl:value-of select="item_font_bold" /></xsl:attribute>
								<xsl:attribute name="item_type"><xsl:value-of select="item_type" /></xsl:attribute>
								<xsl:attribute name="item_content"><xsl:value-of select="item_content" /></xsl:attribute>
								<xsl:attribute name="english"><xsl:value-of select="english" /></xsl:attribute>

								<xsl:attribute name="class">form-control input-sm order_item</xsl:attribute>
								<xsl:attribute name="value"><xsl:value-of select="name" /></xsl:attribute>
							</xsl:element>
							<xsl:element name="LABEL">
								<xsl:attribute name="class">margin_left_0</xsl:attribute>
								<xsl:attribute name="for"><xsl:value-of select="id" /></xsl:attribute>
								<xsl:value-of select="name" />
							</xsl:element>
						</span>
						</xsl:for-each>
						
						

					</div>
				</div>

				<!-- <div class="dropdown_body">
					<div class="dropdown_body_header">
			    		<span class="car_left"><span class="caret"></span></span>
						<span class="title">收货人</span>
					</div>
					<div class="dropdown_body_body">
						<span style="width:88px;overflow:visible;margin-right:5px;display:inline-block">
							<input type="checkbox" style="width:8px;display:inline;margin: 2px 0px;" class="form-control input-sm"  value="" aria-describedby="sizing-addon2" id="id"/>
							<label class="margin_left_0" for="id">买家昵称</label>
						</span>
						<span style="width:88px;overflow:visible;margin-right:5px;display:inline-block">
							<input type="checkbox" style="width:8px;display:inline;margin: 2px 0px;" class="form-control input-sm"  value="" aria-describedby="sizing-addon2" id="id"/>
							<label class="margin_left_0" for="id">姓名</label>
						</span>
						<span style="width:88px;overflow:visible;margin-right:5px;display:inline-block">
							<input type="checkbox" style="width:8px;display:inline;margin: 2px 0px;" class="form-control input-sm"  value="" aria-describedby="sizing-addon2" id="id"/>
							<label class="margin_left_0" for="id">固话</label>
						</span>
						<span style="width:88px;overflow:visible;margin-right:5px;display:inline-block">
							<input type="checkbox" style="width:8px;display:inline;margin: 2px 0px;" class="form-control input-sm"  value="" aria-describedby="sizing-addon2" id="id"/>
							<label class="margin_left_0" for="id">手机</label>
						</span>

						<span style="width:88px;overflow:visible;margin-right:5px;display:inline-block">
							<input type="checkbox" style="width:8px;display:inline;margin: 2px 0px;" class="form-control input-sm"  value="" aria-describedby="sizing-addon2" id="id"/>
							<label class="margin_left_0" for="id">目的地</label>
						</span>
						<br/>
						<span style="width:88px;overflow:visible;margin-right:5px;display:inline-block;">
							<input type="checkbox" style="width:8px;display:inline;margin: 2px 0px;" class="form-control input-sm"  value="" aria-describedby="sizing-addon2" id="id"/>
							<label class="margin_left_0" for="id">省份</label>
						</span>

						<span style="width:88px;overflow:visible;margin-right:5px;display:inline-block">
							<input type="checkbox" style="width:8px;display:inline;margin: 2px 0px;" class="form-control input-sm"  value="" aria-describedby="sizing-addon2" id="id"/>
							<label class="margin_left_0" for="id">市</label>
						</span>
						<span style="width:88px;overflow:visible;margin-right:5px;display:inline-block">
							<input type="checkbox" style="width:8px;display:inline;margin: 2px 0px;" class="form-control input-sm"  value="" aria-describedby="sizing-addon2" id="id"/>
							<label class="margin_left_0" for="id">县</label>
						</span>
						<span style="width:88px;overflow:visible;margin-right:5px;display:inline-block">
							<input type="checkbox" style="width:8px;display:inline;margin: 2px 0px;" class="form-control input-sm"  value="" aria-describedby="sizing-addon2" id="id"/>
							<label class="margin_left_0" for="id">邮编</label>
						</span>							

						<span style="width:88px;overflow:visible;margin-right:5px;display:inline-block">
							<input type="checkbox" style="width:8px;display:inline;margin: 2px 0px;" class="form-control input-sm"  value="" aria-describedby="sizing-addon2" id="id"/>
							<label class="margin_left_0" for="id">地址</label>
						</span>
						<span style="width:88px;overflow:visible;margin-right:5px;display:inline-block">
							<input type="checkbox" style="width:8px;display:inline;margin: 2px 0px;" class="form-control input-sm"  value="" aria-describedby="sizing-addon2" id="id"/>
							<label class="margin_left_0" for="id">买家留言</label>
						</span>
						<span style="width:88px;overflow:visible;margin-right:5px;display:inline-block">
							<input type="checkbox" style="width:8px;display:inline;margin: 2px 0px;" class="form-control input-sm"  value="" aria-describedby="sizing-addon2" id="id"/>
							<label class="margin_left_0" for="id">送货时间</label>
						</span>
						<span style="width:88px;overflow:visible;margin-right:5px;display:inline-block">
							<input type="checkbox" style="width:8px;display:inline;margin: 2px 0px;" class="form-control input-sm"  value="" aria-describedby="sizing-addon2" id="id"/>
							<label class="margin_left_0" for="id">发票信息</label>
						</span>
						<span style="width:88px;overflow:visible;margin-right:5px;display:inline-block">
							<input type="checkbox" style="width:8px;display:inline;margin: 2px 0px;" class="form-control input-sm"  value="" aria-describedby="sizing-addon2" id="id"/>
							<label class="margin_left_0" for="id">发票类型</label>
						</span>
						<span style="width:88px;overflow:visible;margin-right:5px;display:inline-block">
							<input type="checkbox" style="width:8px;display:inline;margin: 2px 0px;" class="form-control input-sm"  value="" aria-describedby="sizing-addon2" id="id"/>
							<label class="margin_left_0" for="id">发票抬头</label>
						</span>
						<span style="width:88px;overflow:visible;margin-right:5px;display:inline-block">
							<input type="checkbox" style="width:8px;display:inline;margin: 2px 0px;" class="form-control input-sm"  value="" aria-describedby="sizing-addon2" id="id"/>
							<label class="margin_left_0" for="id">发票内容</label>
						</span>
					</div>
				</div> -->
				<div class="dropdown_body">
					<div class="dropdown_body_header">
			    		<span class="car_left"><span class="caret"></span></span>
						<span class="title">商品明细</span>
					</div>
					<div class="dropdown_body_body">
					<xsl:for-each select="/html/Body/table/item">
						<span style="width:88px;overflow:visible;margin-right:5px;display:inline-block">
							<xsl:element name="INPUT">
								<xsl:attribute name="type">checkbox</xsl:attribute>
								<xsl:attribute name="style">width:8px;display:inline;margin: 2px 0px;</xsl:attribute>
								<xsl:attribute name="id_exist"><xsl:value-of select="id" /></xsl:attribute>
								<xsl:attribute name="name"><xsl:value-of select="english" /></xsl:attribute>
								<xsl:attribute name="item_top"><xsl:value-of select="item_top" /></xsl:attribute>
								<xsl:attribute name="item_left"><xsl:value-of select="item_left" /></xsl:attribute>
								<xsl:attribute name="item_width"><xsl:value-of select="item_width" /></xsl:attribute>
								<xsl:attribute name="item_height"><xsl:value-of select="item_height" /></xsl:attribute>
								<xsl:attribute name="item_font_size"><xsl:value-of select="item_font_size" /></xsl:attribute>
								<xsl:attribute name="item_font_bold"><xsl:value-of select="item_font_bold" /></xsl:attribute>
								<xsl:attribute name="id"><xsl:value-of select="item_id" /></xsl:attribute>
								<xsl:attribute name="item_type"><xsl:value-of select="item_type" /></xsl:attribute>
								<xsl:attribute name="item_content"><xsl:value-of select="item_content" /></xsl:attribute>
								<xsl:attribute name="english"><xsl:value-of select="english" /></xsl:attribute>
								<xsl:attribute name="class">form-control input-sm order_item</xsl:attribute>
								<xsl:attribute name="value"><xsl:value-of select="name" /></xsl:attribute>
							</xsl:element>
							<xsl:element name="LABEL">
								<xsl:attribute name="class">margin_left_0</xsl:attribute>
								<xsl:attribute name="for"><xsl:value-of select="item_id" /></xsl:attribute>
								<xsl:value-of select="name" />
							</xsl:element>
						</span>
						</xsl:for-each>	

					</div>
				</div>
				
				<div class="dropdown_body">
					<div class="dropdown_body_header">
			    		<span class="car_left"><span class="caret"></span></span>
						<span class="title">条码</span>
					</div>
					<div class="dropdown_body_body">
					<xsl:for-each select="/html/Body/code/item">
						<span style="width:88px;overflow:visible;margin-right:5px;display:inline-block">
							<xsl:element name="INPUT">
								<xsl:attribute name="type">checkbox</xsl:attribute>
								<xsl:attribute name="style">width:8px;display:inline;margin: 2px 0px;</xsl:attribute>
								<xsl:attribute name="id_exist"><xsl:value-of select="id" /></xsl:attribute>
								<xsl:attribute name="name"><xsl:value-of select="english" /></xsl:attribute>
								<xsl:attribute name="item_top"><xsl:value-of select="item_top" /></xsl:attribute>
								<xsl:attribute name="item_left"><xsl:value-of select="item_left" /></xsl:attribute>
								<xsl:attribute name="item_width"><xsl:value-of select="item_width" /></xsl:attribute>
								<xsl:attribute name="item_height"><xsl:value-of select="item_height" /></xsl:attribute>
								<xsl:attribute name="item_font_size"><xsl:value-of select="item_font_size" /></xsl:attribute>
								<xsl:attribute name="item_font_bold"><xsl:value-of select="item_font_bold" /></xsl:attribute>
								<xsl:attribute name="id"><xsl:value-of select="item_id" /></xsl:attribute>
								<xsl:attribute name="item_type"><xsl:value-of select="item_type" /></xsl:attribute>
								<xsl:attribute name="item_content"><xsl:value-of select="item_content" /></xsl:attribute>
								<xsl:attribute name="english"><xsl:value-of select="english" /></xsl:attribute>
								<xsl:attribute name="class">form-control input-sm order_item</xsl:attribute>
								<xsl:attribute name="value"><xsl:value-of select="name" /></xsl:attribute>
							</xsl:element>
							<xsl:element name="LABEL">
								<xsl:attribute name="class">margin_left_0</xsl:attribute>
								<xsl:attribute name="for"><xsl:value-of select="item_id" /></xsl:attribute>
								<xsl:value-of select="name" />
							</xsl:element>
						</span>
						</xsl:for-each>	

					</div>
				</div>
				<!-- <div class="dropdown_body">
					<div class="dropdown_body_header">
			    		<span class="car_left"><span class="caret"></span></span>
						<span class="title">店铺</span>
					</div>
					<div class="dropdown_body_body">
						<span style="width:88px;overflow:visible;margin-right:5px;display:inline-block">
							<input type="checkbox" style="width:8px;display:inline;margin: 2px 0px;" class="form-control input-sm order_item"  value="卖家昵称" aria-describedby="sizing-addon2" id="id"/>
							<label class="margin_left_0" for="id">卖家昵称</label>
						</span>
						<span style="width:88px;overflow:visible;margin-right:5px;display:inline-block">
							<input type="checkbox" style="width:8px;display:inline;margin: 2px 0px;" class="form-control input-sm"  value="" aria-describedby="sizing-addon2" id="id"/>
							<label class="margin_left_0" for="id">店铺名称</label>
						</span>
						<span style="width:88px;overflow:visible;margin-right:5px;display:inline-block">
							<input type="checkbox" style="width:8px;display:inline;margin: 2px 0px;" class="form-control input-sm"  value="" aria-describedby="sizing-addon2" id="id"/>
							<label class="margin_left_0" for="id">联系人</label>
						</span>
						<span style="width:88px;overflow:visible;margin-right:5px;display:inline-block">
							<input type="checkbox" style="width:8px;display:inline;margin: 2px 0px;" class="form-control input-sm"  value="" aria-describedby="sizing-addon2" id="id"/>
							<label class="margin_left_0" for="id">固话</label>
						</span>

						<span style="width:88px;overflow:visible;margin-right:5px;display:inline-block">
							<input type="checkbox" style="width:8px;display:inline;margin: 2px 0px;" class="form-control input-sm"  value="" aria-describedby="sizing-addon2" id="id"/>
							<label class="margin_left_0" for="id">手机</label>
						</span>
						<br/>
						<span style="width:88px;overflow:visible;margin-right:5px;display:inline-block;">
							<input type="checkbox" style="width:8px;display:inline;margin: 2px 0px;" class="form-control input-sm"  value="" aria-describedby="sizing-addon2" id="id"/>
							<label class="margin_left_0" for="id">始发地</label>
						</span>

						<span style="width:88px;overflow:visible;margin-right:5px;display:inline-block">
							<input type="checkbox" style="width:8px;display:inline;margin: 2px 0px;" class="form-control input-sm"  value="" aria-describedby="sizing-addon2" id="id"/>
							<label class="margin_left_0" for="id">始发地编码</label>
						</span>
						<span style="width:88px;overflow:visible;margin-right:5px;display:inline-block">
							<input type="checkbox" style="width:8px;display:inline;margin: 2px 0px;" class="form-control input-sm"  value="" aria-describedby="sizing-addon2" id="id"/>
							<label class="margin_left_0" for="id">省份</label>
						</span>
						<span style="width:88px;overflow:visible;margin-right:5px;display:inline-block">
							<input type="checkbox" style="width:8px;display:inline;margin: 2px 0px;" class="form-control input-sm"  value="" aria-describedby="sizing-addon2" id="id"/>
							<label class="margin_left_0" for="id">市</label>
						</span>							

						<span style="width:88px;overflow:visible;margin-right:5px;display:inline-block">
							<input type="checkbox" style="width:8px;display:inline;margin: 2px 0px;" class="form-control input-sm"  value="" aria-describedby="sizing-addon2" id="id"/>
							<label class="margin_left_0" for="id">县</label>
						</span>
						<span style="width:88px;overflow:visible;margin-right:5px;display:inline-block">
							<input type="checkbox" style="width:8px;display:inline;margin: 2px 0px;" class="form-control input-sm"  value="" aria-describedby="sizing-addon2" id="id"/>
							<label class="margin_left_0" for="id">邮编</label>
						</span>
						<span style="width:88px;overflow:visible;margin-right:5px;display:inline-block">
							<input type="checkbox" style="width:8px;display:inline;margin: 2px 0px;" class="form-control input-sm"  value="" aria-describedby="sizing-addon2" id="id"/>
							<label class="margin_left_0" for="id">地址</label>
						</span>
						<span style="width:88px;overflow:visible;margin-right:5px;display:inline-block">
							<input type="checkbox" style="width:8px;display:inline;margin: 2px 0px;" class="form-control input-sm"  value="" aria-describedby="sizing-addon2" id="id"/>
							<label class="margin_left_0" for="id">店铺网址</label>
						</span>
						<span style="width:88px;overflow:visible;margin-right:5px;display:inline-block">
							<input type="checkbox" style="width:8px;display:inline;margin: 2px 0px;" class="form-control input-sm"  value="" aria-describedby="sizing-addon2" id="id"/>
							<label class="margin_left_0" for="id">宣传标语</label>
						</span>
						<span style="width:88px;overflow:visible;margin-right:5px;display:inline-block">
							<input type="checkbox" style="width:8px;display:inline;margin: 2px 0px;" class="form-control input-sm"  value="" aria-describedby="sizing-addon2" id="id"/>
							<label class="margin_left_0" for="id">打单员</label>
						</span>
						
					</div>
				</div>

				<div class="dropdown_body">
					<div class="dropdown_body_header">
			    		<span class="car_left"><span class="caret"></span></span>
						<span class="title">仓库</span>
					</div>
					<div class="dropdown_body_body">
						<span style="width:88px;overflow:visible;margin-right:5px;display:inline-block">
							<input type="checkbox" style="width:8px;display:inline;margin: 2px 0px;" class="form-control input-sm"  value="" aria-describedby="sizing-addon2" id="id"/>
							<label class="margin_left_0" for="id">仓库编码</label>
						</span>
						<span style="width:88px;overflow:visible;margin-right:5px;display:inline-block">
							<input type="checkbox" style="width:8px;display:inline;margin: 2px 0px;" class="form-control input-sm"  value="" aria-describedby="sizing-addon2" id="id"/>
							<label class="margin_left_0" for="id">仓库名称</label>
						</span>
						<span style="width:88px;overflow:visible;margin-right:5px;display:inline-block">
							<input type="checkbox" style="width:8px;display:inline;margin: 2px 0px;" class="form-control input-sm"  value="" aria-describedby="sizing-addon2" id="id"/>
							<label class="margin_left_0" for="id">联系人</label>
						</span>
						<span style="width:88px;overflow:visible;margin-right:5px;display:inline-block">
							<input type="checkbox" style="width:8px;display:inline;margin: 2px 0px;" class="form-control input-sm"  value="" aria-describedby="sizing-addon2" id="id"/>
							<label class="margin_left_0" for="id">固话</label>
						</span>

						<span style="width:88px;overflow:visible;margin-right:5px;display:inline-block">
							<input type="checkbox" style="width:8px;display:inline;margin: 2px 0px;" class="form-control input-sm"  value="" aria-describedby="sizing-addon2" id="id"/>
							<label class="margin_left_0" for="id">手机</label>
						</span>
						<br/>
						<span style="width:88px;overflow:visible;margin-right:5px;display:inline-block;">
							<input type="checkbox" style="width:8px;display:inline;margin: 2px 0px;" class="form-control input-sm"  value="" aria-describedby="sizing-addon2" id="id"/>
							<label class="margin_left_0" for="id">始发地</label>
						</span>

						<span style="width:88px;overflow:visible;margin-right:5px;display:inline-block">
							<input type="checkbox" style="width:8px;display:inline;margin: 2px 0px;" class="form-control input-sm"  value="" aria-describedby="sizing-addon2" id="id"/>
							<label class="margin_left_0" for="id">事发编码</label>
						</span>
						<span style="width:88px;overflow:visible;margin-right:5px;display:inline-block">
							<input type="checkbox" style="width:8px;display:inline;margin: 2px 0px;" class="form-control input-sm"  value="" aria-describedby="sizing-addon2" id="id"/>
							<label class="margin_left_0" for="id">省份</label>
						</span>
						<span style="width:88px;overflow:visible;margin-right:5px;display:inline-block">
							<input type="checkbox" style="width:8px;display:inline;margin: 2px 0px;" class="form-control input-sm"  value="" aria-describedby="sizing-addon2" id="id"/>
							<label class="margin_left_0" for="id">市</label>
						</span>							

						<span style="width:88px;overflow:visible;margin-right:5px;display:inline-block">
							<input type="checkbox" style="width:8px;display:inline;margin: 2px 0px;" class="form-control input-sm"  value="" aria-describedby="sizing-addon2" id="id"/>
							<label class="margin_left_0" for="id">县</label>
						</span>
						<span style="width:88px;overflow:visible;margin-right:5px;display:inline-block">
							<input type="checkbox" style="width:8px;display:inline;margin: 2px 0px;" class="form-control input-sm"  value="" aria-describedby="sizing-addon2" id="id"/>
							<label class="margin_left_0" for="id">邮编</label>
						</span>
						<span style="width:88px;overflow:visible;margin-right:5px;display:inline-block">
							<input type="checkbox" style="width:8px;display:inline;margin: 2px 0px;" class="form-control input-sm"  value="" aria-describedby="sizing-addon2" id="id"/>
							<label class="margin_left_0" for="id">地址</label>
						</span>
					</div>
				</div> -->
					<!-- <div class="dropdown_body">
						<div class="dropdown_body_header">
				    		<span class="car_left"><span class="caret"></span></span>
							<span class="title">自定义内容</span>
						</div>
						<div class="dropdown_body_body">
							<button id='transverse_line'>添加横线</button>
							<button id='vertical_line'>添加竖线</button>
							<button id='word_text'>添加文本</button>
							<button id='right_sign'>添加 √ </button>
						</div>
					</div> -->
			</div>
		</div>
		<div class="box">
			<div class="print_toolbar" style="height:30px">
				<botton class="btn btn-default btn-sm" id="add_template" data-dismiss="modal">保存为新模板</botton>
				<botton class="btn btn-default btn-sm" id="edit_template" data-dismiss="modal">修改模板</botton>
				<botton class="btn btn-default btn-sm" id="delete_selected" data-dismiss="modal">删除选中项</botton>
				<botton class="btn btn-default btn-sm" id="delete_all" data-dismiss="modal">删除全部</botton>
				<botton class="btn btn-default btn-sm" id="preview" data-dismiss="modal">打印预览</botton>
				<botton class="btn btn-default btn-sm" id="reload_default" data-dismiss="modal">还原默认</botton>
				
			</div>			

			
			<div id="print_order_content" style="display:none;">
				<div id="printi">

					<span class="shop_name">外麦王天猫旗舰店</span>
					<span class="bind_number">20151102123456</span>
					<span class="order_date">2015-11-2 11:41:20</span>
					<span class="user_name">老王</span>
					<span class="user_mobile">13001235521</span>
					<span class="product_total_price">1000.00元</span>
					<span class="post_fee">10.00元</span>
					<span class="total_discount">0.00元</span>
					<span class="theory_amount">1000.00元</span>
					<span class="real_amount">1000.00元</span>
					<span class="product_total_num">100</span>
					<table>
						<tr>
							<td class="product_number">MHI10101</td>
							<td class="product_name">外卖王百度外卖包</td>
							<td class="total">100</td>
							<td class="price">100.00</td>
							<td class="discount">00.00</td>
							<td class="payment">10000.00</td>
						</tr>
					</table>
				</div>
			</div>		
			<object id="LODOP_OB" classid="clsid:2105C259-1E0C-4534-8141-A753534CB4CA" width="870" height="600"> 
				<embed id="LODOP_EM" type="application/x-print-lodop" width="870" height="600" pluginspage="install_lodop32.exe"></embed>
			</object>
		</div>
	</div>
</div>
<script language="javascript" type="text/javascript">

</script>
<script>
$(function(){
	$(".car_right").click(function(){
		$(this).parents(".dropdown").find(".dropdown_body").slideToggle();
	});

	$(".car_left").click(function(){
		$(this).parents(".dropdown_body").find(".dropdown_body_body").slideToggle();
	});
})
</script>		
</body>
</html>
</xsl:template>
</xsl:stylesheet>


