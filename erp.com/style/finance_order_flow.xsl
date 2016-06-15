<?xml version="1.0" encoding="utf-8" ?>
<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<!-- UTF8 编码 -->

<xsl:include href="/style/header.xsl" />

<xsl:template name="text">
<script type="text/javascript" src="/js_encode/purchase_list.js"></script>
<script type="text/javascript" src="/js_encode/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript" src="/js_encode/bootstrap-datetimepicker.zh-CN.js"></script>
<link rel="stylesheet" href="/style/bootstrap-datetimepicker.min.css"></link>
<div class="mainBody">
	<ul id="myTab" class="nav nav-tabs" style="margin-bottom:10px;">
	   <li class="active"><a href="finance_order_flow.php">订单流水</a></li>
	   <li><a href="finance_after_flow.php">退款流水</a></li>
	</ul>
	<div class="customersMsg">
		<div class="table_operate_block">
			<form class="form-inline" method="get" action="/finance/finance_order_flow.php">
				<div class="form-group" style="margin:0; float:right;">
					<button class="btn btn-default btn-sm form_small_block" type="submit">查询</button>
					<input class="btn btn-default btn-sm" name="clear" type="button" value="清空" />
				</div>
				<div class="form-group form_small_block float_right">
					<xsl:element name="INPUT">
						<xsl:attribute name="type">text</xsl:attribute>
						<xsl:attribute name="name">end_date</xsl:attribute>
						<xsl:attribute name="value"><xsl:value-of select="/html/Body/end_date"/></xsl:attribute>
						<xsl:attribute name="class">form-control input-sm</xsl:attribute>
					</xsl:element>
				</div>
				<div class="form-group form_small_block float_right">
					<label>日期：</label>
					<xsl:element name="INPUT">
						<xsl:attribute name="type">text</xsl:attribute>
						<xsl:attribute name="name">begin_date</xsl:attribute>
						<xsl:attribute name="value"><xsl:value-of select="/html/Body/begin_date"/></xsl:attribute>
						<xsl:attribute name="class">form-control input-sm</xsl:attribute>
						<xsl:attribute name="style">margin-right:5px;</xsl:attribute>
					</xsl:element>
					到
				</div>

			</form>
		</div>
		<table width="1180" class="table table-bordered table-hover">
			<tr>
				<th class="table_th_number">序号</th>
				<th width="164">日期</th>
				<th width="150">订单编号</th>
				<th width="150">订单总额</th>
				<th width="160">客户</th>
				<th width="190">店铺</th>
				<th width="150">实收金额</th>
				<th width="190">结算账户<xsl:value-of select="bank_id"/></th>
			</tr>
			<xsl:for-each select="/html/Body/fin_order/ul/li">
				<tr>
					<td class="center"><xsl:value-of select="no"/></td>
					<!-- <td class="center"><input name="select_all" type="checkbox"/></td> -->
					<td><xsl:value-of select="amount_date"/></td>
					<td><xsl:value-of select="bind_number"/></td>
					<td><xsl:value-of select="theory_amount"/></td>
					<td><xsl:value-of select="crm_user_id"/></td>
					<td><xsl:value-of select="user_id"/></td>
					<td><xsl:value-of select="real_amount"/></td>
					<td><xsl:value-of select="bank_id"/></td>
					<xsl:element name="INPUT">
						<xsl:attribute name="type">hidden</xsl:attribute>
						<xsl:attribute name="value"><xsl:value-of select="id"/></xsl:attribute>
						<xsl:attribute name="class">main_id</xsl:attribute>
					</xsl:element>
				</tr>
			</xsl:for-each>
		</table>
		<xsl:if test="/html/Body/fin_order/ul/@total = '0'">
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
<script type="text/javascript">
	//日期
	$('.customersMsg input[name="begin_date"]').datetimepicker({
		language:'zh-CN',
		format:'yyyy-mm-dd',
		autoclose:true,
		minView:2,
	});

	$('.customersMsg input[name="end_date"]').datetimepicker({
		language:'zh-CN',
		format:'yyyy-mm-dd',
		autoclose:true,
		minView:2,
	});

	$('input[name=clear]').click(function(){
		$('input[name=begin_date]').val('');
		$('input[name=end_date]').val('');
	})
</script>
</xsl:template>

</xsl:stylesheet>