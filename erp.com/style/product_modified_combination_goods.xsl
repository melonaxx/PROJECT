<?xml version="1.0" encoding="utf-8" ?>
<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<!-- UTF8 编码 -->
<xsl:include href="/style/header.xsl" />
<xsl:template name="text">
<script type="text/javascript" src="/js_encode/product_modified_combination_goods.js"></script>
<div class="mainBody">
	<form class="form-inline margin_top" method="post" action="/product/product_modified_combination_goods.php">
		<h4>商品信息</h4>
		<div style="height:;width:700px">
			<div class="form-group">
				<label>商品编码：</label>
				<xsl:element name="INPUT">
					<xsl:attribute name="type">text</xsl:attribute>
					<xsl:attribute name="name">number</xsl:attribute>
					<xsl:attribute name="class">form-control input-sm</xsl:attribute>
					<xsl:attribute name="value"><xsl:value-of select="/html/Body/product/number"/></xsl:attribute>
					<xsl:attribute name="placeholder"></xsl:attribute>
				</xsl:element>
			</div>
			<div class="form-group">
				<label>商品名称：</label>
				<xsl:element name="INPUT">
				<xsl:attribute name="data-toggle">tooltip</xsl:attribute>
				<xsl:attribute name="data-placement">bottom</xsl:attribute>
				<xsl:attribute name="title">必填</xsl:attribute>
				<xsl:attribute name="type">text</xsl:attribute>
				<xsl:attribute name="id">brand_id</xsl:attribute>
				<xsl:attribute name="name">name</xsl:attribute>
				<xsl:attribute name="value"><xsl:value-of select="/html/Body/product/name"/></xsl:attribute>
				<xsl:attribute name="class">form-control input-sm merger_two_row_4</xsl:attribute>
				<xsl:attribute name="placeholder">商品名称</xsl:attribute>
				</xsl:element>
			</div>
			<div class="form-group">
				<label>商品分类：</label>
				<select class="form-control input-sm" name="classification">
					<option value="0"></option>
					<xsl:for-each select="/html/Body/product_category/ul/li">
						<xsl:element name="OPTION">
							<xsl:attribute name="value"><xsl:value-of select="value" /></xsl:attribute>
							<xsl:if test="value=/html/Body/product_category/ul/select_value"><xsl:attribute name="selected">selected</xsl:attribute></xsl:if>
							<xsl:value-of select="text" />
						</xsl:element>
					</xsl:for-each>
				</select>
			</div>
			<div class="form-group">
				<label>商品品牌：</label>
				<select class="form-control input-sm" name="brand">
					<option value="0"></option>
					<xsl:for-each select="/html/Body/product_brand/ul/li">
						<xsl:element name="OPTION">
							<xsl:attribute name="value"><xsl:value-of select="value" /></xsl:attribute>
							<xsl:if test="value=/html/Body/product_brand/ul/select_value"><xsl:attribute name="selected">selected</xsl:attribute></xsl:if>
							<xsl:value-of select="text" />
						</xsl:element>
					</xsl:for-each>
				</select>
			</div>
			<div class="form-group">
				<label class="margin_left_1">　售价：</label>
				<div class="input-group">
				   <div class="input-group-addon">￥</div>
				   <xsl:element name="INPUT">
						<xsl:attribute name="type">text</xsl:attribute>
						<xsl:attribute name="class">form-control input-sm lsj</xsl:attribute>
						<xsl:attribute name="value"><xsl:value-of select="/html/Body/sj"/></xsl:attribute>
						<xsl:attribute name="style">width:110px;</xsl:attribute>
						<xsl:attribute name="readonly">true</xsl:attribute>
					</xsl:element>
				</div>
			</div>
			<div class="form-group">
				<label>商品备注：</label>
				<xsl:element name="TEXTAREA">
					<xsl:attribute name="class">form-control input-sm merger_two_row_4</xsl:attribute>
					<xsl:attribute name="rows">3</xsl:attribute>
					<xsl:attribute name="name">content</xsl:attribute>
					<xsl:value-of select="/html/Body/product/content"/>
				</xsl:element>
			</div>
			<div class="form-group">
				<label class="margin_left_1">组合价：</label>
				<div class="input-group">
				   <div class="input-group-addon">￥</div>
				   <xsl:element name="INPUT">
						<xsl:attribute name="type">text</xsl:attribute>
						<xsl:attribute name="class">form-control input-sm zhsj</xsl:attribute>
						<xsl:attribute name="style">width:110px;</xsl:attribute>
						<xsl:attribute name="name">price_display</xsl:attribute>
						<xsl:attribute name="value"><xsl:value-of select="/html/Body/product/price_display"/></xsl:attribute>
					</xsl:element>
				</div>
			</div>
		</div>
		<div>
			<div class="table_operate_block">
				<h4>子商品</h4>
				<input class="btn btn-default btn-sm btn_margin goodsAdd" type="button" value="添加" />
				<input class="btn btn-default btn-sm goodsDeleteaa" type="button" value="删除" />
			</div>
			<div>
				<table width="1180" class="table table-bordered table-Goods ttbale table-hover">
					<tr>
						<th class="table_th_number">序号</th>
						<th class="center table_th_checkbox"><input type="checkbox" name="select_all" /></th>
						<th width="190">搜索</th>
						<th width="300">商品名称和规格</th>
						<th width="150">零售价</th>
						<th width="150">数量</th>
						<!-- <th width="150">组合单价</th> -->
						<th width="150">单位</th>
					</tr>
					<xsl:for-each select="/html/Body/productcombination/ul/li">
					<tr>
						<td class="center"><xsl:value-of select="position()"/></td>
						<td class="center"><input type="checkbox" name="select_one" /></td>
						<td><input type="text" class="form-control input-sm form_no_border input_border seach" placeholder="搜索"/></td>
						<td>
							<select class="form-control input-sm input_border product" style="width:280px" name="product[]">
								<xsl:element name="OPTION">
									<xsl:attribute name="value"><xsl:value-of select="value"/>,<xsl:value-of select="value_id_1"/>,<xsl:value-of select="value_id_2"/>,<xsl:value-of select="value_id_3"/>,<xsl:value-of select="value_id_4"/>,<xsl:value-of select="value_id_5"/></xsl:attribute>
									<xsl:if test="value=select_value"><xsl:attribute name="selected">selected</xsl:attribute></xsl:if>
									<xsl:value-of select="text" />
								</xsl:element>
							</select>
						</td>
						<td><xsl:value-of select="price_display"/></td>
						<td>
							<xsl:element name="INPUT">
								<xsl:attribute name="style">width:150px</xsl:attribute>
								<xsl:attribute name="onkeyup">this.value=this.value.replace(/\D/g,'')</xsl:attribute>
								<xsl:attribute name="type">text</xsl:attribute>
								<xsl:attribute name="name">total[]</xsl:attribute>
								<xsl:attribute name="value"><xsl:value-of select="total"/></xsl:attribute>
								<xsl:attribute name="class">input-sm form-control input_border sl</xsl:attribute>
							</xsl:element>
						</td>
						<!-- <td>
							<xsl:element name="INPUT">
								<xsl:attribute name="style">width:150px</xsl:attribute>
								<xsl:attribute name="onkeyup">this.value = this.value.replace(/[^\d.]/g,"");this.value = this.value.replace(/^\./g,"");this.value = this.value.replace(/\.{2,}/g,".");this.value = this.value.replace(".","$#$").replace(/\./g,"").replace("$#$",".");</xsl:attribute>
								<xsl:attribute name="type">text</xsl:attribute>
								<xsl:attribute name="name">price_group[]</xsl:attribute>
								<xsl:attribute name="class">input-sm form-control input_border grp_pri</xsl:attribute>
								<xsl:attribute name="value"><xsl:value-of select="price_group"/></xsl:attribute>
							</xsl:element>
						</td> -->
						<td><xsl:value-of select="name"/></td>
					</tr>
					</xsl:for-each>
				</table>
				<p>
					<input class="btn btn-default btn-sm btn_margin" name="send" type="submit" value="提交" />
					<input class="btn btn-default btn-sm" type="reset" value="重置" />
				</p>
			</div>
		</div>
		<xsl:element name="INPUT">
			<xsl:attribute name="type">hidden</xsl:attribute>
			<xsl:attribute name="name">id</xsl:attribute>
			<xsl:attribute name="value"><xsl:value-of select="/html/Body/id"/></xsl:attribute>
		</xsl:element>
	</form>
</div>
</xsl:template>
</xsl:stylesheet>
