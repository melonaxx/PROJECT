<?xml version="1.0" encoding="utf-8" ?>
<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<!-- UTF8 编码 -->

<xsl:include href="/style/header.xsl" />

<xsl:template name="text">
<script type="text/javascript" src="/js_encode/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript" src="/js_encode/bootstrap-datetimepicker.zh-CN.js"></script>
<link rel="stylesheet" href="/style/bootstrap-datetimepicker.min.css"></link>

<style>
</style>
<div class="mainBody">
	<ul class="nav nav-tabs nav_tabs_margin">
		<li><a href="/purchase/purchase_tobe_paid.php">待付款</a></li>
		<li><a href="/purchase/purchase_collection.php">待收款</a></li>
		<li class="active"><a href="">单据列表</a></li>
	</ul>
		<div class="paymentMsg">
			<form class="form-inline" action="/purchase/purchase_waiting_payment.php" method="get">
				<div class="table_operate_block">
					<div class="float_right">
							<input class="btn btn-default btn-sm btn_margin" type="submit" value="查询" />
							<input class="btn btn-default btn-sm" name="clear" type="button" value="清空" />
						</div>
					<div class="form-group form_small_block float_right">
						<label>记账日期：</label>
						<xsl:element name="INPUT">
							<xsl:attribute name="type">text</xsl:attribute>
							<xsl:attribute name="name">date</xsl:attribute>
							<xsl:attribute name="value"><xsl:value-of select="/html/Body/date"/></xsl:attribute>
							<xsl:attribute name="class">form-control input-sm</xsl:attribute>
						</xsl:element>
					</div>
					<div class="form-group form_small_block" style="float:right;">
						<label>单据类型：</label>
						<select class="form-control input-sm" name="type">
							<option value="0"></option>
							<xsl:element name="OPTION">
							   	<xsl:attribute name="value">Output</xsl:attribute>
							   	<xsl:if test="'Output'=/html/Body/type">
							   	<xsl:attribute name="selected">selected</xsl:attribute>
								</xsl:if>
							   支出单据
							</xsl:element>
							<xsl:element name="OPTION">
							   	<xsl:attribute name="value">Input</xsl:attribute>
							   	<xsl:if test="'Input'=/html/Body/type">
							   	<xsl:attribute name="selected">selected</xsl:attribute>
								</xsl:if>
							   收入单据
							</xsl:element>
						</select>
					</div>
				</div>
			</form>
			<table width="1200" class="table table-bordered table-hover">
				<tr>
					<th class="table_th_number" width="46px;">序号</th>
					<th class="table_th_operate" width="84px;">操作</th>
					<th width="130">日期</th>
					<th width="80">单据类型</th>
					<th width="80">单据科目</th>
					<th width="100">账号</th>
					<th width="90">金额</th>
					<th width="90">已付金额</th>
					<th width="90">欠款尾款</th>
					<th width="90">供应商欠款</th>
					<th width="200">备注</th>
					<th width="120">采购单编码</th>
				</tr>
				<xsl:for-each select="/html/Body/logs/ul/li">
				<tr>
					<td class="center"><xsl:value-of select="no" /></td>
					<td class="center">
						<xsl:element name="A">
							<xsl:attribute name="class">table_a_operate</xsl:attribute>
							<xsl:attribute name="href">ajaxscript:;</xsl:attribute>
							<xsl:attribute name="onclick">
								MessageBox('/purchase/purchase_waiting_payment_msg.php?id=<xsl:value-of select="id" />','详细',745,270);return false
							</xsl:attribute>
							详细
						</xsl:element>
						<a href="javascript:;">打印</a>
					</td>
					<td><xsl:value-of select="amount_date" /></td>
					<td><xsl:value-of select="type" /></td>
					<td><xsl:value-of select="subject" /></td>
					<td><xsl:value-of select="bank_id" /></td>
					<td><xsl:value-of select="money" /></td>
					<td><xsl:value-of select="payment_already" /></td>
					<td><xsl:value-of select="payment_remain" /></td>
					<td><xsl:value-of select="payment_return" /></td>
					<td><xsl:value-of select="body" /></td>
					<td><xsl:value-of select="number" /></td>
				</tr>
				</xsl:for-each>
			</table>
		</div>
		<xsl:if test="/html/Body/logs/ul/@total = '0'">
			<div class="imgs" style="margin:0 auto; width:100%;text-align:center">
				<div class="img">
					<img src="/images/empty.jpg"  alt=""/>
					<span>没有找到记录，请调整搜索条件。</span>
				</div>
			</div>
		</xsl:if>
		<xsl:call-template name="page"></xsl:call-template>
</div>
<script>
	//日期
	$('input[name="date"]').datetimepicker({
		language:'zh-CN',
		format:'yyyy-mm-dd',
		autoclose:true,
		minView:2,
	});
	//点击清空搜索框
	$('input[name=clear]').click(function(){
		$('input[name="date"]').val('');
		$('select[name="type"]').val('');
	})
</script>
</xsl:template>

</xsl:stylesheet>
