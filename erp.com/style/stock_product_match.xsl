<?xml version="1.0" encoding="utf-8" ?>
<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<!-- UTF8 编码 -->

<xsl:include href="/style/header.xsl" />

<xsl:template name="text">
<div class="mainBody">
	<xsl:element name='INPUT'>
		<xsl:attribute name='type'>hidden</xsl:attribute>
		<xsl:attribute name='name'>store_id</xsl:attribute>
		<xsl:attribute name='value'>
			<xsl:value-of select='/html/Body/store_id' />
		</xsl:attribute>
	</xsl:element>
	<xsl:element name='INPUT'>
		<xsl:attribute name='type'>hidden</xsl:attribute>
		<xsl:attribute name='name'>location_id</xsl:attribute>
		<xsl:attribute name='value'>
			<xsl:value-of select='/html/Body/location_id' />
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
								<xsl:attribute name="name">product_set</xsl:attribute>
								<xsl:attribute name="value">添加商品</xsl:attribute>
							</xsl:element>
						</div>
						<xsl:value-of select="/html/Body/store_name"/> >> <xsl:value-of select="/html/Body/area_name"/> >> <xsl:value-of select="/html/Body/shelves_name"/> >> <xsl:value-of select="/html/Body/location_name"/>
					</div>

					<table class="table table-bordered table-hover table-order-form">
						<tr>
							<th class="table_th_number">序号</th>
							<th class='center' width='48'>操作</th>
							<th class='center' width="46">图片</th>
							<th width="660">商品名</th>
							<th width="200">规格</th>
							<th width="200">单位</th>
						</tr>
						<xsl:for-each select="/html/Body/product/ul/li">
							<tr>
								<td class="center"><xsl:value-of select="no"/></td>
								<td class="center">
									<xsl:element name="A">
										<xsl:attribute name="href">javascript:;</xsl:attribute>
										<xsl:attribute name="onclick">
											MessageBox("/stock/stock_product_delete.php?store_id=<xsl:value-of select='store'/>&amp;location_id=<xsl:value-of select='location'/>&amp;product_id=<xsl:value-of select='product'/>",'删除商品',300,100)
										</xsl:attribute>
										删除
									</xsl:element>
								</td>
								<td>
									<xsl:element name="img">
										<xsl:attribute name="src"><xsl:value-of select='image'/></xsl:attribute>
										<xsl:attribute name="style">width:20px;height:20px;</xsl:attribute>
									</xsl:element>
								</td>
								<td><xsl:value-of select="name"/></td>
								<td><xsl:value-of select="format"/></td>
								<td><xsl:value-of select="parts"/></td>
							</tr>
						</xsl:for-each>
					</table>
				</div>
			</div>
		</div>
	</div>
</div>
<script>
	$('input[name=product_set]').click(function(){
		var store_id = $('input[name=store_id]').val();
		var location_id = $('input[name=location_id]').val();
		MessageBox('/stock/stock_product_setup.php?store_id='+store_id+'&amp;location_id='+location_id,'添加商品',905,360);
	})
</script>
</xsl:template>
</xsl:stylesheet>
