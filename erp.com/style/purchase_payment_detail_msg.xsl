<?xml version="1.0" encoding="utf-8" ?>
<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<!-- UTF8 编码 -->

<xsl:include href="/style/header.xsl" />

<xsl:template name="text">
<style>
.record {
		margin:30px 0 0 0;
	}
.record dl dd {
	height:38px;
	line-height:38px;
	border-bottom:1px solid #ddd;
}
.record .mesg_send {
	border-bottom:1px solid #ddd;
	padding:12px 0 0 0;
	clear:both;
}
.record .mesg_send .right{
	margin:10px 0 0 0;
	float:right;
}
.record .mesg_send .right a {
	margin:0 0 0 20px;
}
</style>
<div class="mainBody">
	<form class="form-inline" action="" method="">

		<div class="goodsMsg">
			<h4 class="padding_clear">商品信息</h4>
			<table class="table table-bordered table-hover">
				<tr>
					<th class="table_th_number">序号</th>
					<th style="width:120px;">付款日期</th>
					<th style="width:80px;">采购单编号</th>
					<th style="width:80px;">单据类型</th>
					<th style="width:98px;">科目</th>
					<th style="width:98px;">账户</th>
					<th style="width:98px;">采购总价</th>
					<th style="width:100px;">本次金额</th>
					<th style="width:98px;">已付金额</th>
					<th style="width:98px;">欠款尾款</th>
					<th style="width:98px;">供应商欠款</th>
					<th style="width:100px;">备注</th>
				</tr>
				<xsl:for-each select="/html/Body/paymentMsg/ul/li">
				<tr>
					<td class="center"></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				</xsl:for-each>
			</table>
		</div>
		<h4>合计</h4>
		<div class="commonMsg totalMsg">
			<p>商品总金额<span class="money_color">2070</span>元 - 订单总优惠<span class="money_color">70</span>元  = 应付<span class="money_color">2000</span>元</p>
		</div>

	</form>


</div>
</xsl:template>

</xsl:stylesheet>
