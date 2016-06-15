<?xml version="1.0" encoding="utf-8" ?>
<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<!-- UTF8 编码 -->

<xsl:include href="/style/header.xsl" />

<xsl:template name="text">

<script type="text/javascript" src="/js_encode/product_list.js"></script>
<script type="text/javascript">
	var sheng = '<xsl:value-of select="/html/Body/sheng" />';
	var shi = '<xsl:value-of select="/html/Body/shi" />';
	var xian = '<xsl:value-of select="/html/Body/xian" />';
</script>
<style>
</style>





<div class="mainBody">
	<ul id="myTab" class="nav nav-tabs">
	   <li class="active"><a href="#Blurb" data-toggle="tab">待开票</a></li>
	   <li><a href="/finance/finance_billing_open.php">已开票</a></li>
	</ul>
	<div class="tab-content">
	   	<div class="tab-pane fade in active" id="Blurb">
			<div class="mainBody">
				<div class="goodsMsg" style="clear:both;" >
					<div class="table_operate_block" style="margin-bottom:0px;">
						<div class="form-group float_right margin0">
							<form class="form-inline" method="get" action="/finance/finance_for_billing.php">
							<div class="form-group form-group_1 btn_margin">
								<label>订单编号：</label>
								<xsl:element name="INPUT">
									<xsl:attribute name="style">text</xsl:attribute>
									<xsl:attribute name="name">find</xsl:attribute>
									<xsl:attribute name="class">form-control input-sm find</xsl:attribute>
									<xsl:attribute name="value"><xsl:value-of select="/html/Body/find" /></xsl:attribute>
								</xsl:element>
							</div>
							<div class="float_right">
								<input class="btn btn-default btn-sm btn_margin" type="submit" value="查询" />
							</div>
							</form>
						</div>
					</div>

				<table class="table table-bordered table-hover table-order-form">
					<tr>
						<th class="table_th_number">序号</th>
						<th class="center" width="110px;">操作</th>
						<th class="center" width="64px;">提醒</th>
						<th width="100px;">店铺名称</th>
						<th width="130px;">订单编号</th>
						<th width="100px;"> 昵称</th>
						<th class="" width="90px;">收件人</th>
						<th class="input_search" width="120px;">电话</th>
						<th width="100px;">发票类型</th>
						<th width="120px;">发票抬头</th>
						<th width="220px;">发票明细</th>
					</tr>
					<xsl:for-each select="/html/Body/tax/ul/li">
					<tr>
						<td class="center"><xsl:value-of select="no"/></td>
						<td class="center" >
							<xsl:element name="A">
								<xsl:attribute name="class">margin_left_0</xsl:attribute>
								<xsl:attribute name="href">/order/order_list_audit_detail.php?id=<xsl:value-of select = 'id'/></xsl:attribute>
							<xsl:text>详细</xsl:text>
							</xsl:element>
							<xsl:element name="A">
								<xsl:attribute name="class">margin_left_0</xsl:attribute>
								<xsl:attribute name="href">javascript:;</xsl:attribute>
								<xsl:attribute name="onclick">MessageBox("/finance/finance_billing_yes.php?id=<xsl:value-of select = 'id'/>","确认开票",245,90)</xsl:attribute>
							<xsl:text>开票</xsl:text>
							</xsl:element>
							<xsl:element name="A">
								<xsl:attribute name="class">margin_left_0</xsl:attribute>
								<xsl:attribute name="href">javascript:;</xsl:attribute>
								<xsl:attribute name="onclick">MessageBox("/finance/finance_billing_no.php?id=<xsl:value-of select = 'id'/>","取消开票",260,80)</xsl:attribute>
							<xsl:text>取消</xsl:text>
							</xsl:element>
						</td>
						<td class="center">待开票</td>
						<td><xsl:value-of select="user_id"/></td>
						<td><xsl:value-of select="bind_number"/></td>
						<td><xsl:value-of select="name"/></td>
						<td><xsl:value-of select="name"/></td>
						<td><xsl:value-of select="mobile"/></td>
						<td><xsl:value-of select="tax_type"/></td>
						<td><xsl:value-of select="tax_title"/></td>
						<td><xsl:value-of select="tax_text"/></td>
					</tr>
					</xsl:for-each>
				</table>
			</div>
			<xsl:if test="/html/Body/tax/ul/@total = '0'">
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
	</div>
</div>




















</xsl:template>

</xsl:stylesheet>
