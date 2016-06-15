<?xml version="1.0" encoding="utf-8" ?>
<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<!-- UTF8 编码 -->

<xsl:include href="/style/header.xsl" />

<xsl:template name="text">
<script type="text/javascript" src="/js_encode/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript" src="/js_encode/bootstrap-datetimepicker.zh-CN.js"></script>
<script type="text/javascript" src="/js_encode/stock_check_list.js"></script>
<link rel="stylesheet" href="/style/bootstrap-datetimepicker.min.css"></link>
	<div class="mainBody">
		<div class="table_operate_block form-inline">
			<form>
				<input class="btn btn-default btn-sm btn_margin" type="button" onclick="location.href='/stock/stock_check.php'" value="盘点库存" />
				<div class="form-group" style="margin:0; float:right;">
					<p>
						<button class="btn btn-default btn-sm btn_margin" type="button">查询</button>
						<button class="btn btn-default btn-sm" type="button">清空</button>
					</p>
				</div>
				<div class="form-group dataendMsg form_small_block float_right">
					<xsl:element name="INPUT">
						<xsl:attribute name="type">text</xsl:attribute>
						<xsl:attribute name="name">date_end</xsl:attribute>
						<xsl:attribute name="class">form-control input-sm</xsl:attribute>
					</xsl:element>
				</div>
				<div class="float_right form_small_block" style="margin-top:5px;">到</div>
				<div class="form-group dateMsg form_small_block float_right">
					<label>日期：</label>
					<xsl:element name="INPUT">
						<xsl:attribute name="type">text</xsl:attribute>
						<xsl:attribute name="name">date_state</xsl:attribute>
						<xsl:attribute name="class">form-control input-sm</xsl:attribute>
					</xsl:element>
				</div>
				<div class="form-group form_small_block float_right">
					<label>商品：</label>
					<xsl:element name="INPUT">
						<xsl:attribute name="class">form-control input-sm</xsl:attribute>
						<xsl:attribute name="type">text</xsl:attribute>
						<xsl:attribute name="placeholder">输入商品名称或编码</xsl:attribute>
					</xsl:element>
				</div>
				<div class="form-group form_small_block float_right">
					<label>仓库：</label>
					<select class="form-control input-sm" name="type">
						<xsl:for-each select="/html/Body/status/ul/li">
							<xsl:element name="OPTION">
							</xsl:element>
						</xsl:for-each>
					</select>
				</div>
			</form>
		</div>
		<div>
			<table class="table tab-sel table-bordered table-hover">
				<tr>
					<th class="table_th_number">序号</th>
					<th class="table_th_operate">操作</th>
					<th style="width:180px;">日期</th>
					<th>单据编码</th>
					<th>仓库</th>
					<th>操作人</th>
					<th style="width:250px;">备注</th>
				</tr>
				<tr>
					<td class="center">1</td>
					<td><a href="">详情</a><span class="margin_left_1"><a href="">打印</a></span></td>
					<td>2015-8-23<span style="margin-left:8px;">08:53:09</span></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td class="center">2</td>
					<td><a href="">详情</a><span class="margin_left_1"><a href="">打印</a></span></td>
					<td>2015-8-23<span style="margin-left:8px;">08:53:09</span></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td class="center">3</td>
					<td><a href="">详情</a><span class="margin_left_1"><a href="">打印</a></span></td>
					<td>2015-8-23<span style="margin-left:8px">08:53:09</span></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
			</table>
			<xsl:call-template name="page"></xsl:call-template>
		</div>
	</div>
</xsl:template>

</xsl:stylesheet>