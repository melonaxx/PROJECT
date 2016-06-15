<?xml version="1.0" encoding="utf-8" ?>
<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<!-- UTF8 编码 -->
<xsl:include href="/style/header.xsl" />
<xsl:template name="text">
<script type="text/javascript" src="/js_encode/deliver_weighing_charging.js"></script>
<style type="text/css">
	.box{
		width:702px;
		height:600px;
	}
	.smallbox{
		width:360px;
		height:300px;
		float:right;
		margin-right:100px;
		margin-top:20px;
	}
	.two a:visited{
		color:red;
	}
	table,th,tr,td{
		border:1px solid #ddd;
	}
	.del th,td{
		padding-left:6px;
	}
</style>
<div class="mainBody">
	<ul id="myTab" class="nav nav-tabs nav_tabs_margin">
	   <li class="active">
		  <a href="deliver_weighing_charging.php" >称重计费</a>
	   </li>
	   <li><a href="deliver_weighing_r.php">待处理</a></li>
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
			<div class="modal-footer"><button type="button" class="btn btn-default btn-sm ok butt" data-dismiss="modal">确定</button></div>
		</div>
	</div>
	<div id="myTabContent" class="tab-content">
		<div class="tab-pane fade in active" id="ware_house">
			<div class="mainBody">
				<div id="myTabContent" class="tab-content">
					<div class="smallbox">
						<center style="margin-top:10px;font-size:18px">待处理：<font color="red"><span class="two"><a href="/deliver/deliver_weighing_r.php" style="margin-right:4px;"><xsl:value-of select="/html/Body/total"/></a></span></font>笔</center><br/>
						<form class="form-inline">
							<div class="form-group">
								<label class="margin_left_2">商品重量：</label>
								<xsl:element name="INPUT">
									<xsl:attribute name="type">text</xsl:attribute>
									<xsl:attribute name="name">weight</xsl:attribute>
									<xsl:attribute name="class">form-control input-sm</xsl:attribute>
								</xsl:element>
								<span class="weight_num"></span>
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
							<div class="form-group">
								<label class="margin_left_2">手动计算：</label>
								<xsl:element name="INPUT">
									<xsl:attribute name="type">text</xsl:attribute>
									<xsl:attribute name="name">source</xsl:attribute>
									<xsl:attribute name="class">form-control input-sm</xsl:attribute>
								</xsl:element>
								<span class="margin_left_1"><a class="start" href="javascript:;">用此运费</a></span>
							</div>

							<div class="form-group">
								<label class="margin_left_2">快递成本：</label>
								<xsl:element name="INPUT">
									<xsl:attribute name="type">text</xsl:attribute>
									<xsl:attribute name="name">price</xsl:attribute>
									<xsl:attribute name="class">form-control input-sm</xsl:attribute>
									<xsl:attribute name="disabled">true</xsl:attribute>
								</xsl:element>
								<xsl:element name="A">
									<xsl:attribute name="target">_blank</xsl:attribute>
								<xsl:attribute name="href">/setting/setting_express_list.php</xsl:attribute>
								<xsl:attribute name="class">myMod</xsl:attribute>
								<img title="点击前往设置运费！" src="https://img.alicdn.com/imgextra/i2/85662775/TB2uxSfipXXXXazXXXXXXXXXXXX_!!85662775.png" width='16px;' height='16px;' />
								</xsl:element>
								<!-- <span class="margin_left_0"><a class="start" href="javascript:;">自动计算</a></span> -->
							</div><br/>
							<div class="btn-group margin_left_2">
								<button class="btn btn-default btn-sm btn_margin cheng" type="button">确认称重</button>
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
								<input type="text" name="bind_number" class="form-control input-sm" value="" style="border:none;width:600px;box-shadow:none;cursor:default;" disabled="disabled"/>
							</div>
							<div class="form-group">
								<label class="margin_left_5">快速物流：</label>
								<input type="text" name="express" class="form-control input-sm" value="" style="border:none;width:600px;box-shadow:none;cursor:default;" disabled="disabled"/>
							</div>
							<div class="form-group">
								<label class="margin_left_5">买家信息：</label>
								<input type="text" name="name" class="form-control input-sm" style="border:none;width:600px;box-shadow:none;cursor:default;" disabled="disabled"/>
							</div>
							<div class="form-group">
								<label class="margin_left_5">买家留言：</label>
								<input type="text" name="order_text" class="form-control input-sm" style="border:none;width:600px;box-shadow:none;cursor:default;" disabled="disabled"/>
							</div>
							<div class="form-group">
								<label class="margin_left_5">卖家备注：</label>
								<input type="text" name="customer_text" class="form-control input-sm" style="border:none;width:600px;box-shadow:none;cursor:default;" disabled="disabled"/>
							</div>
						</form>
						<h4 class="margin_left_5">商品信息</h4>
							<table class="del" border="1" width="660px;">
								<tr style="height:28px;">
									<th style="text-align:center;width:46px;">序号</th>
									<th style="text-align:center;width:46px;">提醒</th>
									<th style="text-align:center;width:46px;">图片</th>
									<th style="width:221px">系统商品信息</th>
									<th style="width:221px">线上商品信息</th>
									<th style="width:80px">数量</th>
								</tr>
								<tr class="heji" style="height:28px;" bgcolor="#fffae9">
									<td>合计</td>

									<td colspan="4"></td>
									<td>
										<div class="zong"></div>
										<input type="hidden" name="order_id"/>
										<input type="hidden" name="store_id"/>
										<input type="hidden" name="state_id"/>
										<input type="hidden" name="express_id"/>
									</td>
								</tr>
							</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>










</xsl:template>

</xsl:stylesheet>