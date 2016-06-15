<?xml version="1.0" encoding="utf-8" ?>
<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<!-- UTF8 编码 -->
<xsl:include href="/style/header.xsl" />
<xsl:template name="text">
<script type="text/javascript" src="/js_encode/deliver_barcode_inspection.js"></script>
<style type="text/css">
	.box{
		width:780px;
		height:600px;
		margin-left:0;
	}
	.smallbox{
		width:360px;
		height:300px;
		float:right;
		margin-top:20px;
	}
	.two a:visited{
		color:red;
	}
	table,tr,th,td{
		border:1px solid #ddd;
	}
	.del td,th{
		padding-left:6px;
	}

</style>

<div class="mainBody">
	<ul id="myTab" class="nav nav-tabs nav_tabs_margin">
	   <li class="active">
		  <a href="deliver_barcode_inspection.php">条码验货</a>
	   </li>
	   <li><a href="deliver_barcode_r.php">待处理</a></li>
	</ul>

	<!-- 提示框 -->
	<div id="confirm" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none;width:268px;margin:200px auto">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close butt" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
				<h4 class="modal-title" id="myModalLabel">提示</h4>
			</div>
			<div class="modal-body" style="margin-left:20px">完成验证
			</div>
			<div class="modal-footer"><button type="button" class="btn btn-default btn-sm ok" data-dismiss="modal">确定</button></div>
		</div>
	</div>

	<!-- 提示框 -->
	<!-- <div id="con" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none;width:268px;margin:200px auto">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close butt" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
				<h4 class="modal-title" id="myModalLabel">提示</h4>
			</div>
			<div class="modal-bb" style="margin-left:20px">完成验证
			</div>
			<div class="modal-footer"><button type="button" class="btn btn-default btn-sm" data-dismiss="modal">确定</button></div>
		</div>
	</div> -->

	<div id="myTabContent" class="tab-content">
		<div class="tab-pane fade in active" id="ware_house">
			<div class="smallbox">
				<center style="margin-top:10px;font-size:18px">待处理：<font color="red"><span class="two"><a style="margin-right:4px;" href="/deliver/deliver_barcode_r.php"><xsl:value-of select="/html/Body/total"/></a></span></font>笔</center><br/>
				<form class="form-inline">
					<div class="form-group">
						<label class="margin_left_3">配货员：</label>
						<select class="form-control input-sm" name="type">
							<xsl:for-each select="/html/Body/nick/type/ul/li">
									<xsl:element name="OPTION">
									   <xsl:attribute name="value"><xsl:value-of select="value"/></xsl:attribute>
									   <xsl:value-of select="text"/>
									</xsl:element>
								</xsl:for-each>
						</select>
					</div>
					<div class="form-group">
						<label class="margin_left_2">查询条件：</label>
						<select class="form-control input-sm tiao" name="type">
							<xsl:for-each select="/html/Body/tiaojian/type/ul/li">
								<xsl:element name="OPTION">
								   <xsl:attribute name="value"><xsl:value-of select="value"/></xsl:attribute>
								   <xsl:value-of select="text"/>
								</xsl:element>
							</xsl:for-each>
						</select>
					</div>
					<div class="form-group">
						<label class="margin_left_2">编号输入：</label>
						<xsl:element name="INPUT">
							<xsl:attribute name="type">text</xsl:attribute>
							<xsl:attribute name="class">form-control input-sm bian</xsl:attribute>
							<xsl:attribute name="onkeyup">value=value.replace(/[^\d]/g,'')</xsl:attribute>
							<xsl:attribute name="onbeforepaste">clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))</xsl:attribute>
						</xsl:element>
					</div>
					<h4 class="margin_left_2">商品验货</h4>
					<div class="form-group" style="margin-top:10px;">
						<label class="margin_left_2">商品条码：</label>
						<xsl:element name="INPUT">
							<xsl:attribute name="type">text</xsl:attribute>
							<xsl:attribute name="class">form-control input-sm bar</xsl:attribute>
						</xsl:element><span class="margin_left_0"><a class="start" href="javascript:;">验子商品条码开启</a></span>
					</div>
					<div class="btn-group margin_left_2">
						<button class="btn btn-default btn-sm btn_margin strong" type="button">强制通过</button>
					</div>
					<div class="btn-group">
						<button type="button" class="form-control input-sm" data-toggle="dropdown">打回<span class="caret"></span></button>
						<ul class="dropdown-menu" role="menu">
							<li><a style="font-size:12px;" href="#" class="shen">订单审核</a></li>
							<li><a style="font-size:12px;" href="#" class="pei">打单配货</a></li>
							<li><a style="font-size:12px;" href="#" class="yichang" type="button">异常订单</a></li>
						</ul>
					</div>
				</form>
			</div>
			<div class="box">
				<div class="form-group">
					<h4 class="margin_left_5">订单信息</h4>
				</div>
				<form class="form-inline ">
					<div class="form-group">
						<label class="margin_left_5">订单编号：</label>
						<input type="text" name="bind_number" class="form-control input-sm" value="" style="border:none;width:678px;box-shadow:none;cursor:default;" disabled="disabled"/>
					</div>
					<div class="form-group">
						<label class="margin_left_5">快速物流：</label>
						<input type="text" name="express" class="form-control input-sm" value="" style="border:none;width:678px;box-shadow:none;cursor:default;" disabled="disabled"/>
					</div>
					<div class="form-group">
						<label class="margin_left_5">买家信息：</label>
						<input type="text" name="name" class="form-control input-sm" style="border:none;width:678px;box-shadow:none;cursor:default;" disabled="disabled"/>
					</div>
					<div class="form-group">
						<label class="margin_left_5">买家留言：</label>
						<input type="text" name="order_text" class="form-control input-sm" style="border:none;width:678px;box-shadow:none;cursor:default;" disabled="disabled"/>
					</div>
					<div class="form-group">
						<label class="margin_left_5">卖家备注：</label>
						<input type="text" name="customer_text" class="form-control input-sm" style="border:none;width:678px;box-shadow:none;cursor:default;" disabled="disabled"/>
					</div>
				</form>
				<h4 class="margin_left_5">商品信息</h4>
					<table class="del table" border="1" width="660px;">
						<tr style="height:28px;">
							<th style="text-align:center" class="table_th_number">序号</th>
							<th style="width:80px;">验货状态</th>
							<th style="width:80px;">已验数量</th>
							<th style="width:80px;">订单数量</th>
							<th style="width:60px;">图片</th>
							<th style="width:130px;">系统商品信息</th>
							<th style="width:130px;">线上商品信息</th>
							<th style="width:132px;">条码</th>
						</tr>

						<tr class="heji" style="height:28px;" bgcolor="#fffae9">
							<td>合计</td>
							<td>
								<div></div>
								<input type="hidden" name="order_id"/>
							</td>
							<td>
								<div class="zong"></div>
								<input type="hidden" name="zong"/>
							</td>
							<td></td>
							<td colspan="4"></td>
						</tr>
					</table>
			</div>
		</div>
	</div>
</div>
</xsl:template>

</xsl:stylesheet>