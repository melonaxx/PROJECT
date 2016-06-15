<?xml version="1.0" encoding="utf-8" ?>
<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<!-- UTF8 编码 -->

<xsl:include href="/style/header.xsl" />

<xsl:template name="text">
<script type="text/javascript" src="/js_encode/jquery.twbsPagination.min.js"></script>
<script type="text/javascript">
$(function(){
	$('.detail').click(function(){
		var a = $(this).parents('tr').find('td:nth-child(3)').html();
		MessageBox('order_sale_detail.php?after_id='+a, '商品入库',970,400);
		return false;
	})
})
</script>
<div class="mainBody">
	<ul id="myTab" class="nav nav-tabs">
	   <li><a href="order_sale_service.php">新建售后单</a></li>
	   <li><a href="order_sale_deal.php">待处理售后单</a></li>
	   <li><a href="order_sale_list.php">售后单查询</a></li>
	   <li class="active"><a href="order_sale_ruku_list.php">销售退货入库</a></li>
	   <li><a href="order_sale_cate.php">售后分类</a></li>
	</ul>
<div id="myTabContent" class="tab-content">
	<div class="tab-pane fade in active" id="unit">
		<div class="mainBody">
			<div class="form-inline" >
				<div class="goodsMsg" style="clear:both;">
					<form method="get" action="order_sale_ruku_list.php">
						<div class="table_operate_block">
							<div class="form-group float_right margin0">
								<div class="input-group">
									<xsl:element name="input">
										<xsl:attribute name="type">text</xsl:attribute>
										<xsl:attribute name="style">width:200px</xsl:attribute>
										<xsl:attribute name="class">form-control input-sm input_search</xsl:attribute>
										<xsl:attribute name="name">find</xsl:attribute>
										<xsl:attribute name="placeholder">输入订单编号/售后单号</xsl:attribute>
										<xsl:attribute name="value">
											<xsl:value-of select="/html/Body/find"/>
										</xsl:attribute>
									</xsl:element>
									<span class="input-group-btn">
										<button class="btn btn-default btn-sm">搜索</button>
									</span>
								</div>
							</div>
						</div>
					</form>
					<table style="width:1200px" class="table table-bordered table-hover tab_Pending">
						<tr>
							<th class="table_th_number">序号</th>
							<th style="width:97px;">操作</th>
							<th style="width:120px;">售后单号</th>
							<th style="width:67px;">订单编号</th>
							<th style="width:111px;">退回仓库</th>
							<th class="table_th_operate_1">店铺名称</th>
							<th class="table_th_operate_2" style="width:120px;">已退回数量</th>
							<th class="table_th_operate_2" style="width:120px;">未退回数量</th>
							<th class="table_th_operate_2">备注</th>
						</tr>
						<xsl:for-each select="/html/Body/sales/ul/li">
							<tr>
								<td class="center"><xsl:value-of select="position()"/></td>
								<td>
									<xsl:element name="A">
										<xsl:attribute name="href">#</xsl:attribute>
										<xsl:attribute name="class">detail</xsl:attribute>
										<xsl:attribute name="type">button</xsl:attribute>
										查看
									</xsl:element>
								</td>
								<td><xsl:value-of select="after"/></td>
								<td><xsl:value-of select="bind_number"/></td>
								<td><xsl:value-of select="store"/></td>
								<td><xsl:value-of select="shop_name"/></td>
								<td><xsl:value-of select="total_finish"/></td>
								<td><xsl:value-of select="total_wait"/></td>
								<td><xsl:value-of select="body"/></td>
							</tr>
						</xsl:for-each>
					</table>
				</div>
			</div>
			<xsl:if test="/html/Body/sales/ul/@total = '0'">
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