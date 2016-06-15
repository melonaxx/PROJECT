<?xml version="1.0" encoding="utf-8" ?>
<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<!-- UTF8 编码 -->

<xsl:include href="/style/header.xsl" />

<xsl:template name="text">
<script type="text/javascript" src="/js_encode/jquery.twbsPagination.min.js"></script>

<div class="mainBody">
	<ul id="myTab" class="nav nav-tabs">
	   <li><a href="order_sale_service.php">新建售后单</a></li>
	   <li><a href="order_sale_deal.php">待处理售后单</a></li>
	   <li class="active"><a href="#unit">售后单查询</a></li>
	   <li><a href="order_sale_ruku_list.php">销售退货入库</a></li>
	   <li><a href="order_sale_cate.php">售后分类</a></li>
	</ul>
<div id="myTabContent" class="tab-content">
	<div class="tab-pane fade in active" id="unit">
		<div class="mainBody">
			<form class="form-inline" method="get" action="order_sale_list.php">
				<div class="goodsMsg" style="clear:both;">
						<div class=" float_right">
							<div class="float_right">
								<button class="btn btn-default btn-sm">查询</button>
							</div>
							<div class="form-group form-group_1 btn_margin float_right">
								<label>状态处理：</label>
								<select class="form-control input-sm" name="find">
									<xsl:for-each select="/html/Body/supplierMsg/ul/li">
										<xsl:element name="OPTION">
											<xsl:attribute name="value">
												<xsl:value-of select="value" />
											</xsl:attribute>
											<!-- <xsl:if test="status = value">
												<xsl:attribute name="checked">true</xsl:attribute>
											</xsl:if> -->
												<xsl:value-of select="text" />
										</xsl:element>
									</xsl:for-each>
								</select>
							</div>

						</div>

					<table style="width:1200px" class="table table-bordered table-hover tab_Pending">
						<tr>
							<th class="table_th_number">序号</th>
							<!-- <th style="width:97px;">已售后</th> -->
							<th style="width:120px;">单据编号</th>
							<th style="width:67px;">售后类型</th>
							<th style="width:111px;">退款金额</th>

							<th class="table_th_operate_1">收件人</th>
							<th class="table_th_operate_2" style="width:120px;">电话</th>
							<th class="table_th_operate_2">退回快递</th>
							<th style="width:146px;">退回单号</th>
							<th style="width:91px;">操作人</th>
							<th class="table_th_operate_1">售后分类</th>
							<th style="width:141px;">店铺</th>
						</tr>
						<xsl:for-each select="/html/Body/sales/ul/li">
								<tr>
									<td class="center"><xsl:value-of select="position()"/></td>
									<!-- <td>2</td> -->
									<td><xsl:value-of select="id"/></td>
									<td><xsl:value-of select="service_type"/></td>
									<td>￥
										<xsl:element name="INPUT">
											<xsl:attribute name="type">text</xsl:attribute>
											<xsl:attribute name="class">form-control input-sm form_no_border</xsl:attribute>
											<xsl:attribute name="style">width:56px;background-color:white;</xsl:attribute>
											<xsl:attribute name="maxlength">4</xsl:attribute>
											<xsl:attribute name="value"><xsl:value-of select="payment"/></xsl:attribute>
											<xsl:attribute name="readonly">readonly</xsl:attribute>
										</xsl:element>
									</td>

									<td><xsl:value-of select="name"/></td>
									<td><xsl:value-of select="phone"/></td>
									<td><xsl:value-of select="e_name"/></td>
									<td><xsl:value-of select="number"/></td>
									<td><xsl:value-of select="staff_name"/></td>
									<td><xsl:value-of select="topic_name"/></td>
									<td><xsl:value-of select="shop_name"/></td>
								</tr>
							</xsl:for-each>
					</table>
				</div>
			</form>
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