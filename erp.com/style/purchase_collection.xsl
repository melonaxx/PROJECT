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
		<li class="active"><a href="">待收款</a></li>
		<li><a href="/purchase/purchase_waiting_payment.php">单据列表</a></li>
	</ul>
	<div class="receivablesMsg">
		<form class="form-inline" action="/purchase/purchase_collection.php" method="get">
			<input class="btn btn-default btn-sm btn_margin float_left" type="button" onclick="MessageBox('/purchase/purchase_add_collection.php', '新建收款', 600, 270)" value="新建收款"/>
		<div class="table_operate_block">
			<div class="float_right">
				<input class="btn btn-default btn-sm btn_margin" type="submit" value="查询" />
				<input class="btn btn-default btn-sm" name="clear" type="button" value="清空" />
			</div>
			<div class="form-group form_small_block" style="float:right;">
				<label>采购日期：</label>
				<xsl:element name="INPUT">
				   <xsl:attribute name="class">form-control input-sm</xsl:attribute>
				   <xsl:attribute name="type">text</xsl:attribute>
				   <xsl:attribute name="name">date</xsl:attribute>
				   <xsl:attribute name="value"><xsl:value-of select="/html/Body/date"/></xsl:attribute>
				</xsl:element>
			</div>
			<div class="form-group form_small_block" style="float:right;">
				<label>供应商欠款：</label>
				<select class="form-control input-sm" name="is_return">
					<option value="0"></option>
					<xsl:element name="OPTION">
					   	<xsl:attribute name="value">Y</xsl:attribute>
					   	<xsl:if test="'Y'=/html/Body/is_return">
					   		<xsl:attribute name="selected">selected</xsl:attribute>
					   	</xsl:if>
					   	有欠款
					</xsl:element>
					<xsl:element name="OPTION">
					   	<xsl:attribute name="value">N</xsl:attribute>
					   	<xsl:if test="'N'=/html/Body/is_return">
					   		<xsl:attribute name="selected">selected</xsl:attribute>
					   	</xsl:if>
					   	无欠款
					</xsl:element>
				</select>
			</div>
		</div>
		</form>
		<table class="table table-bordered table-hover">
			<tr>
				<th class="table_th_number" width="46px;">序号</th>
				<th class="table_th_operate_1" width="64px;">操作</th>
				<th width="130px;">申请日期</th>
				<th width="110px;">采购单编码</th>
				<th width="100px;">申请人</th>
				<th width="250px;">采购摘要</th>
				<th width="100px;">供应商</th>
				<th width="100px;">收货仓库</th>
				<th width="100px;">付款方式</th>
				<th width="100px;">欠款尾款</th>
				<th width="100px;">退货状态</th>
			</tr>
			<xsl:for-each select="/html/Body/purchasemain/ul/li">
			<tr>
				<td class="center"><xsl:value-of select="no"/></td>
				<td class="center">
					<xsl:element name="A">
					   <xsl:attribute name="href">
					   		/purchase/purchase_receivables_detail.php?id=<xsl:value-of select="id"/>
					   </xsl:attribute>
						收款
					</xsl:element>
				</td>
				<td><xsl:value-of select="action_date"/></td>
				<td><xsl:value-of select="number"/></td>
				<td><xsl:value-of select="staff_id"/></td>
				<td><xsl:value-of select="brief"/></td>
				<td><xsl:value-of select="supplier_id"/></td>
				<td><xsl:value-of select="store_id"/></td>
				<td><xsl:value-of select="pay_method" /></td>
				<td><xsl:value-of select="payment_remain"/></td>
				<td><xsl:value-of select="status_refund"/></td>
			</tr>
			</xsl:for-each>
		</table>
	</div>
	<xsl:if test="/html/Body/purchasemain/ul/@total = '0'">
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
		$('select[name="is_return"]').val('');
	})
</script>
</xsl:template>

</xsl:stylesheet>
