<?xml version="1.0" encoding="utf-8" ?>
<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<!-- UTF8 编码 -->

<xsl:include href="/style/header.xsl" />

<xsl:template name="text">
<div class="mainBody">
	<!-- <ul id="myTab" class="nav nav-tabs">
	    <li>
			<xsl:element name="A">
				<xsl:attribute name="href">
					/stock/stock_product_match.php?store_id=<xsl:value-of select="/html/Body/store_id" />
				</xsl:attribute>
				可发商品
			</xsl:element>
	    </li>
	    <li class="active"><a href="" data-toggle="tab">发货地区</a></li>
	</ul> -->
	<xsl:element name='INPUT'>
		<xsl:attribute name='type'>hidden</xsl:attribute>
		<xsl:attribute name='name'>store_id</xsl:attribute>
		<xsl:attribute name='value'>
			<xsl:value-of select='/html/Body/store_id' />
		</xsl:attribute>
	</xsl:element>
	<div class="tab-content">
	   	<div class="tab-pane fade in active">
	   		<div class="mainBody">
				<div class="goodsMsg" style="clear:both;">
					<div class="table_operate_blocks">
						<div class="btn-group">
							<xsl:element name="INPUT">
								<xsl:attribute name="class">btn btn-default btn-sm btn_margin</xsl:attribute>
								<xsl:attribute name="type">button</xsl:attribute>
								<xsl:attribute name="name">area_set</xsl:attribute>
								<xsl:attribute name="value">设置可发</xsl:attribute>
							</xsl:element>
						</div>
					</div>
					<table class="table table-bordered table-hover table-order-form">
						<tr>
							<th class="table_th_number">序号</th>
							<th width="154">省</th>
							<th width="1000">市</th>
						</tr>
						<xsl:for-each select="/html/Body/city/ul/li">
							<tr>
								<td class="center"><xsl:value-of select="no"/></td>
								<td><xsl:value-of select="sheng" /></td>
								<td>
									<xsl:value-of select="shi" />
								</td>
							</tr>
						</xsl:for-each>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	$('input[name=area_set]').click(function(){
		var store_id = $('input[name=store_id]').val();
		MessageBox('/stock/stock_address_setup.php?store_id='+store_id,'设置地区',800,380);
	})
</script>
</xsl:template>
</xsl:stylesheet>
