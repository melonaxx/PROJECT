<?xml version="1.0" encoding="utf-8" ?>
<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<!-- UTF8 编码 -->

<xsl:include href="/style/header.xsl" />

<xsl:template name="text">
<style>

	.dropdown-toggle{
	margin-right:0px;
	}

	.button-group{
	margin-left:10px;
	}

</style>
<script type="text/javascript" src="/js_encode/deliver_express_printing.js"></script>
<script type="text/javascript" src="/js_encode/LodopFuncs.js"></script>
	<!-- 提示框 -->
	<div id="confirm" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none;width:268px;margin:65px auto">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
				<h4 class="modal-title" id="myModalLabel">提示</h4>
			</div>
			<div class="modal-body" style="margin-left:20px">
				请至少选择<span class="number">1</span>个订单
			</div>
			<div class="other" style="margin-left:40px">

			</div>
			<div class="modal-footer"><button type="button" class="btn btn-default btn-sm ok" data-dismiss="modal">确定</button></div>
		</div>
	</div>

	<div class="table_operate_blocks">
	<div class="mainBody">
			<form class="form-inline" action="deliver_express_printing.php" method="get">
				<button class="btn btn-default btn-sm btn_margin pei" type="button">确认配货</button>
				<button class="btn btn-default btn-sm btn_margin shen" type="button">打回审核</button>
				<button class="btn btn-default btn-sm btn_margin exception"  type="button" >提交异常</button>

				<!-- 打印快递单 -->
				<div class="btn-group">
				  <button type="button" id="go_print_express" go="/deliver/deliver_print_express.php?print=1" class="btn btn-sm  btn-primary order_select2 express go_print">打印快递单</button>
				  <button type="button" class="btn btn-sm btn_margin btn-primary dropdown-toggle " data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="height:30px;">
				    <span class="caret"></span>
				    <span class="sr-only">Toggle Dropdown</span>
				  </button>
				  <ul class="dropdown-menu">
				    <li><a href="javascript:;" go="/deliver/deliver_print_express.php?print=1" id="express" class="express order_select2">打印预览和设计</a></li>
				  </ul>
				  <xsl:element name="A">
				  	<xsl:attribute name="target">_blank</xsl:attribute>
					<xsl:attribute name="href">/setting/setting_express_template.php</xsl:attribute>
					<xsl:attribute name="class">myMod</xsl:attribute>
					<img title="点击前往设置快递单模板" src="https://img.alicdn.com/imgextra/i2/85662775/TB2uxSfipXXXXazXXXXXXXXXXXX_!!85662775.png" width='16px;'  height='16px;' />
					</xsl:element>
				</div>
				<!-- 打印发货单 -->
				<div class="btn-group">
				  <button type="button" id="go_print_deliver" go="/deliver/deliver_print_deliver.php?print=1" class="btn btn-sm  btn-success order_select2 go_print deliver">打印发货单</button>
				  <button type="button"  class="btn btn-sm btn_margin btn-success dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="height:30px;">
				    <span class="caret"></span>
				    <span class="sr-only">Toggle Dropdown</span>
				  </button>
				  <ul class="dropdown-menu">
				    <li><a href="javascript:;" go="/deliver/deliver_print_deliver.php?print=1" class="order_select2 deliver">打印预览和设计</a></li>
				  </ul>
				  <xsl:element name="A">
				  	<xsl:attribute name="target">_blank</xsl:attribute>
					<xsl:attribute name="href">/setting/setting_deliver_template.php</xsl:attribute>
					<xsl:attribute name="class">myMod</xsl:attribute>
					<img title="点击前往设置发货单模板" src="https://img.alicdn.com/imgextra/i2/85662775/TB2uxSfipXXXXazXXXXXXXXXXXX_!!85662775.png" width='16px;'  height='16px;' />
				  </xsl:element>
				</div>
				<!-- 打印配货单 -->
				<div class="btn-group">
				  <button type="button" id="go_print_order" go="/deliver/deliver_print_order.php?print=1" class="btn btn-sm  btn-warning order_select2 go_print order">打印配货单</button>
				  <button type="button" class="btn btn-sm  btn-warning dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="height:30px;">
				    <span class="caret"></span>
				    <span class="sr-only">Toggle Dropdown</span>
				  </button>
				  <xsl:element name="A">
				  	<xsl:attribute name="target">_blank</xsl:attribute>
					<xsl:attribute name="href">/setting/setting_order_template.php</xsl:attribute>
					<xsl:attribute name="class">myMod</xsl:attribute>
					<img title="点击前往设置配货单模板" src="https://img.alicdn.com/imgextra/i2/85662775/TB2uxSfipXXXXazXXXXXXXXXXXX_!!85662775.png" width='16px;'  height='16px;' />
					</xsl:element>
				  <ul class="dropdown-menu">
				    <li><a href="javascript:;" go="/deliver/deliver_print_order.php?print=1" class="order_select2 order">打印预览和设计</a></li>
				    <!-- <li><a href="#">设置单张配货单容量</a></li> -->
				    <!-- <li role="separator" class="divider"></li>
				    <li><a href="#" class="order_select">导出-按（查询条件）</a></li>
				    <li><a href="#" class="order_select">导出-按（勾选订单）</a></li> -->
				  </ul>
				</div>

				<!-- 批量生成快递单 -->
				<div class="btn-group" style="margin-left:10px">
				  <button type="button" class="btn btn-sm btn-default express_add">批量生成快递单</button>
				  <button type="button" class="btn btn-sm btn-default dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="height:30px;">
				    <span class="caret"></span>
				    <span class="sr-only">Toggle Dropdown</span>
				  </button>
				  <ul class="dropdown-menu">
				    <li><a href="#" class="mod_express">批量修改快递</a></li>
				    <li><a href="#" class="bei">批量修改备注</a></li>
				  </ul>
				</div>
				<div class="form-group float_right margin0">
					<div class="input-group">
						<xsl:element name="INPUT">
							<xsl:attribute name="type">text</xsl:attribute>
							<xsl:attribute name="name">find</xsl:attribute>
							<xsl:attribute name="class">form-control input-sm input_search</xsl:attribute>
							<xsl:attribute name="placeholder">输入订单编号/收件人查询</xsl:attribute>
							<xsl:attribute name="value"><xsl:value-of select="/html/Body/find" /></xsl:attribute>
						</xsl:element>
						<span class="input-group-btn">
							<button class="btn btn-default btn-sm">搜索</button>
						</span>
					</div>
				</div>
			</form>
		</div>
		<div>
			<table style="width:1200px;" class="table tab_select table-bordered table-hover">
				<tr>
					<th class="table_th_number">序号</th>
					<th class="table_th_checkbox center"><input type="checkbox" name="select_all" /></th>
					<th style="width:48px;" class="table_th_number">操作</th>

					<th class="table_th_number" style="width:70px">打印标记</th>

					<th style="width:152px;">订单备注</th>
					<th style="width:67px;">发货仓库</th>
					<th style="width:67px;">快递</th>

					<th style="width:132px;">快递单号</th>
					<th style="width:93px;">买家</th>
					<th style="width:93px;">手机</th>
					<th style="width:71px;">数量</th>
					<th style="width:128px;">订单编号</th>
					<th style="width:120px;">店铺</th>
				</tr>
				<xsl:for-each select="/html/Body/OrderReview/ul/li">
				<tr>
					<td class="center"><xsl:value-of select="position()"/></td>
					<td class="center">
						<xsl:element name="INPUT">
							<xsl:attribute name="type">checkbox</xsl:attribute>
							<xsl:attribute name="name">select_one</xsl:attribute>
							<xsl:attribute name="value">
								<xsl:value-of select='id'/>
							</xsl:attribute>
						</xsl:element>
						<xsl:element name="INPUT">
							<xsl:attribute name="type">hidden</xsl:attribute>
							<xsl:attribute name="name">id</xsl:attribute>
							<xsl:attribute name="value">
								<xsl:value-of select='id'/>
							</xsl:attribute>
						</xsl:element>
					</td>
					<td class="center">
						<xsl:element name="A">
							<xsl:attribute name="class">table_a_operate</xsl:attribute>
							<xsl:attribute name="href">/deliver/deliver_edit_order.php?id=<xsl:value-of select = 'id'/></xsl:attribute>
						<xsl:text>详细</xsl:text>
						</xsl:element>
					</td>

					<td>
						<font color="blue" class="mark_express"><xsl:value-of select="mark_express" /></font>
						<font color="green" class="mark_deliver"><xsl:value-of select="mark_deliver" /></font>
						<font color="orange" class="mark_order"><xsl:value-of select="mark_order" /></font>
					</td>

					<td><xsl:value-of select="message" /></td>
					<td><xsl:value-of select="store" /></td>
					<td class="express"><xsl:value-of select="kuaidi" /><xsl:element name="INPUT">
							<xsl:attribute name="type">hidden</xsl:attribute>
							<xsl:attribute name="name">express_id</xsl:attribute>
							<xsl:attribute name="value">
								<xsl:value-of select='express_id'/>
							</xsl:attribute>
						</xsl:element>
					</td>
					<td><xsl:value-of select="express_number" /></td>

					<td><xsl:value-of select="name" /></td>
					<td><xsl:value-of select="mobile" /></td>
					<td><xsl:value-of select="num" /></td>
					<td><xsl:value-of select="number" /></td>
					<td><xsl:value-of select="shop" /></td>
				</tr>
				</xsl:for-each>
			</table>
			<xsl:if test="/html/Body/OrderReview/ul/@total = '0'">
				<div class="imgs" style="margin:0 auto; width:100%;text-align:center">
					<div class="img">
						<img src="/images/empty.jpg"  alt=""/>
						<span>没有找到记录，请调整搜索条件。</span>
					</div>
				</div>
			</xsl:if>
			<xsl:call-template name="page"></xsl:call-template>
		</div>
	</div>
</xsl:template>

</xsl:stylesheet>