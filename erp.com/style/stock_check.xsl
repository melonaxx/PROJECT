<?xml version="1.0" encoding="utf-8" ?>
<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<!-- UTF8 编码 -->
<xsl:include href="/style/header.xsl" />
<xsl:template name="text">
<script type="text/javascript" src="/js_encode/stock_check.js"></script>
<div class="mainBody">
	<ul class="nav nav-tabs nav_tabs_margin">
		<li class="active"><a href="#allocation" data-toggle="tab">库存盘点</a></li>
		<li><a href="/stock/stock_inventory_list.php">盘点单</a></li>
	</ul>
	<div class="tab-content">
		<div class="table_operate_block form-inline">
			<form action="/stock/stock_check.php" method="post">
				<xsl:element name="INPUT">
					<xsl:attribute name="class">btn btn-default danji btn-sm btn_margin</xsl:attribute>
					<xsl:attribute name="type">button</xsl:attribute>
					<xsl:attribute name="value">生成盘点单</xsl:attribute>
				</xsl:element>
				<div class="form-group" style="margin:0; float:right;">
					<div class="input-group">
						<button class="btn btn-default btn-sm" type="submit">查询</button>
						<!-- <input class="btn btn-default btn-sm" type="button" name='clear' value="清空"/> -->
					</div>
				</div>
				<!-- <div class="form-group form_small_block float_right">
					<label>品牌：</label>
					<select name="brand" class="form-control input-sm">
						<option value="0"></option>
						<xsl:for-each select="/html/Body/brandList/ul/li">
							<xsl:element name="OPTION">
								<xsl:attribute name="value"><xsl:value-of select="@id" /></xsl:attribute>
								<xsl:if test="@id=/html/Body/brand"><xsl:attribute name="selected">selected</xsl:attribute></xsl:if>
								<xsl:value-of select="." />
							</xsl:element>
						</xsl:for-each>
					</select>
				</div>
				<div class="form-group form_small_block float_right">
					<label>分类：</label>
					<select name="classification" class="form-control input-sm">
						<option value="0"></option>
						<xsl:for-each select="/html/Body/product_category/ul/li">
							<xsl:element name="OPTION">
								<xsl:attribute name="value"><xsl:value-of select="value"/></xsl:attribute>
								<xsl:if test="value=/html/Body/classification"><xsl:attribute name="selected">selected</xsl:attribute></xsl:if>
								<xsl:value-of select="text"/>
							</xsl:element>
						</xsl:for-each>
					</select>
				</div>
				<div class="form-group form_small_block float_right">
					<label>商品：</label>
					<xsl:element name="INPUT">
						<xsl:attribute name="type">text</xsl:attribute>
						<xsl:attribute name="name">find</xsl:attribute>
						<xsl:attribute name="class">form-control input-sm</xsl:attribute>
						<xsl:attribute name="value"><xsl:value-of select="/html/Body/find"/></xsl:attribute>
						<xsl:attribute name="placeholder">输入商品名称或编码</xsl:attribute>
					</xsl:element>
				</div> -->
				<div class="form-group form_small_block float_right">
					<label>仓库：</label>
					<select name="ware" class="form-control input-sm">
						<xsl:for-each select="/html/Body/store_ware/ul/li">
							<xsl:element name="OPTION">
								<xsl:attribute name="value"><xsl:value-of select="value"/></xsl:attribute>
								<xsl:if test="value=/html/Body/warehouse"><xsl:attribute name="selected">selected</xsl:attribute></xsl:if>
								<xsl:value-of select="text"/>
							</xsl:element>
						</xsl:for-each>
					</select>
				</div>
			</form>
		</div>
		<div>
			<table width='1180' class="table tab-sel table-bordered table-hover">
				<tr>
					<th class="center" width='46'>序号</th>
					<th class="center" width='44'>图片</th>
					<th width='300'>商品名称</th>
					<th width='260'>规格</th>
					<th width='210'>商品编码</th>
					<th width='170'>盘点前数量</th>
					<th width='170'>盘点后数量</th>
				</tr>
				<xsl:for-each select="/html/Body/stock_info/ul/li">
					<tr>
						<td class="center"><xsl:value-of select="num"/></td>
						<td class="center">
							<xsl:element name="img">
								<xsl:attribute name="src"><xsl:value-of select="image"/></xsl:attribute>
								<xsl:attribute name="width">20</xsl:attribute>
								<xsl:attribute name="height">20</xsl:attribute>
								<xsl:attribute name="class">smallimg</xsl:attribute>
							</xsl:element>
							<xsl:element name="img">
								<xsl:attribute name="src"><xsl:value-of select="image"/></xsl:attribute>
								<xsl:attribute name="width">200</xsl:attribute>
								<xsl:attribute name="height">200</xsl:attribute>
								<xsl:attribute name="style">position:absolute;display:none;</xsl:attribute>
								<xsl:attribute name="class">bigimg</xsl:attribute>
							</xsl:element>
						</td>
						<td>
							<xsl:element name="A">
								<xsl:attribute name="href">/product/product_look_see.php?id=<xsl:value-of select="id"/></xsl:attribute>
								<xsl:value-of select="name"/>
							</xsl:element>
						</td>
						<td><xsl:value-of select="format"/></td>
						<td><xsl:value-of select="number"/></td>
						<td><xsl:value-of select="total"/></td>
						<xsl:element name="TD">
							<xsl:attribute name="class">dianji</xsl:attribute>
						</xsl:element>
						<xsl:element name="INPUT">
							<xsl:attribute name="type">hidden</xsl:attribute>
							<xsl:attribute name="value"><xsl:value-of select="id"/></xsl:attribute>
							<xsl:attribute name="name">id</xsl:attribute>
						</xsl:element>
					</tr>
				</xsl:for-each>
			</table>
			<xsl:if test="/html/Body/stock_info/ul/@total = '0'">
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
</xsl:template>

</xsl:stylesheet>