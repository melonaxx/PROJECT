<?xml version="1.0" encoding="utf-8" ?>
<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<!-- UTF8 编码 -->

<xsl:include href="/style/header.xsl" />

<xsl:template name="text">

<script type="text/javascript" src="/js_encode/purchase_waiting_storage.js"></script>
<!-- //日期 -->
<script type="text/javascript" src="/js_encode/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript" src="/js_encode/bootstrap-datetimepicker.zh-CN.js"></script>
<link rel="stylesheet" href="/style/bootstrap-datetimepicker.min.css"></link>
<style>
</style>
<div class="mainBody">
	<ul id="myTab" class="nav nav-tabs" style='margin-bottom:12px;'>
	   <li><a href="/purchase/purchase_waiting_storage.php">采购入库</a></li>
	   <li class="active"><a href="" data-toggle="">退货出库</a></li>
	   <li><a href="/purchase/purchase_documents_List.php">出入库单据列表</a></li>

	</ul>
	<div class="customersMsg">
		<div class="table_operate_block">
			<form class="form-inline" action="purchase_out_storage.php" method="get">
				<div class="form-group" style="margin:0; float:right;">
					<button class="btn btn-default btn-sm form_small_block" type="submit">查询</button>
					<input class="btn btn-default btn-sm" type="button" name="clear" value="清空" />
				</div>
				<div class="form-group form_small_block float_right">
					<label>申请人：</label>
					<xsl:element name="INPUT">
						<xsl:attribute name="type">text</xsl:attribute>
						<xsl:attribute name="name">staff</xsl:attribute>
						<xsl:attribute name="value"><xsl:value-of select="/html/Body/staff"/></xsl:attribute>
						<xsl:attribute name="class">form-control input-sm</xsl:attribute>
					</xsl:element>
				</div>
				<div class="form-group form_small_block float_right">
					<label>申请日期：</label>
					<xsl:element name="INPUT">
						<xsl:attribute name="type">text</xsl:attribute>
						<xsl:attribute name="name">date</xsl:attribute>
						<xsl:attribute name="value"><xsl:value-of select="/html/Body/date"/></xsl:attribute>
						<xsl:attribute name="class">form-control input-sm</xsl:attribute>
					</xsl:element>
				</div>
			</form>
		</div>
		<table class="table table-bordered table-hover">
			<tr>
				<th class="table_th_number">序号</th>
				<th class="center" width="88px">操作</th>
				<th width="130px">采购单编号</th>
				<th width="130px">供应商</th>
				<th width="130px">仓库</th>
				<th width="130px">采购数量</th>
				<th width="130px">采购总价</th>
				<th width="286px">摘要</th>
				<th width="130px">申请人</th>
			</tr>
			<xsl:for-each select="/html/Body/purchase/ul/li">
			<tr>
				<td class="center"><xsl:value-of select="no" /></td>
				<td class="center">
					<!-- <xsl:element name="A">
						<xsl:attribute name="href">
							/purchase/purchase_in_storage_detail.php?id=<xsl:value-of select="id" />
						</xsl:attribute>
						<xsl:attribute name="class">table_a_operate</xsl:attribute>
						入库
					</xsl:element> -->
					<xsl:element name="A">
						<xsl:attribute name="href">
							/purchase/purchase_out_storage_detail.php?id=<xsl:value-of select="id" />
						</xsl:attribute>
						<xsl:attribute name="class">table_a_operate</xsl:attribute>
						出库
					</xsl:element>
				</td>
				<td><xsl:value-of select="number" /></td>
				<td><xsl:value-of select="supplier_id" /></td>
				<td><xsl:value-of select="store_id" /></td>
				<td><xsl:value-of select="total" /></td>
				<td><xsl:value-of select="price" /></td>
				<td style="width:286px;"><xsl:value-of select="brief" /></td>
				<td><xsl:value-of select="staff_id" /></td>
			</tr>
			</xsl:for-each>
		</table>
		<xsl:if test="/html/Body/purchase/ul/@total = '0'">
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
	$('.customersMsg input[name="date"]').datetimepicker({
		language:'zh-CN',
		format:'yyyy-mm-dd',
		autoclose:true,
		minView:2,
	});
	$('input[name=clear]').click(function(){
		$('input[name=staff]').val('');
		$('input[name=date]').val('');
	})
</script>
</xsl:template>

</xsl:stylesheet>
