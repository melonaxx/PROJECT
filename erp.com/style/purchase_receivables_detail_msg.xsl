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
		<h4 class="padding_clear">出库单</h4>
		<div class="deliveryMsg">
			<table class="table table-bordered table-hover">
				<tr>
					<th class="table_th_number">序号</th>
					<th style="width:80px;">日期</th>
					<th style="width:100px;">单据类型</th>
					<th style="width:98px;">单据编码</th>
					<th style="width:80px;">申请人</th>
					<th style="width:80px;">申请部门</th>
					<th style="width:98px;">供应商</th>
					<th style="width:100px;">收货仓库</th>
					<th style="width:90px;">商品金额</th>
					<th style="width:80px;">备注</th>
					<th style="width:90px;">采购订单编码</th>
					<th style="width:90px;">历史已结算</th>
					<th style="width:90px;">剩余未结算</th>
				</tr>
				<xsl:for-each select="/html/Body/receivablesMsg/ul/li">
				<tr>
					<td class="center"></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td><!--备注-->
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				</xsl:for-each>
				
			</table>
		</div>
		
		
	</form>
	
	
	
	
</div>
</xsl:template>

</xsl:stylesheet>
