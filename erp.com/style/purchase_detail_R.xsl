<?xml version="1.0" encoding="utf-8" ?>
<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<!-- UTF8 编码 -->
<xsl:include href="/style/header.xsl" />
<xsl:template name="text">
<script type="text/javascript" src="/js_encode/purchase_edit.js"></script>
<div class="mainBody">
	<form class="form-inline" method="post" action="/purchase/purchase_detail_R.php">

		<xsl:element name="INPUT">
			<xsl:attribute name="type">hidden</xsl:attribute>
			<xsl:attribute name="name">id</xsl:attribute>
			<xsl:attribute name="value"><xsl:value-of select="/html/Body/details/ul/li/id" /></xsl:attribute>
		</xsl:element>
		<div class="base">

			<div class="form-group">
				<label class="margin_left_1">供应商：</label>
				<select class="form-control input-sm" name="supplier_id">
					<xsl:for-each select="/html/Body/purchase_add/ul/li">
						<xsl:element name="OPTION">
							<xsl:attribute name="value"><xsl:value-of select="value" /></xsl:attribute>
							<xsl:if test="value =/html/Body/details/ul/li/supplier_id">
								<xsl:attribute name="selected">selected</xsl:attribute>
							</xsl:if>
							<xsl:value-of select="text" />
						</xsl:element>
					</xsl:for-each>
				</select>
			</div>
			<div class="form-group">
				<label>收货仓库：</label>
				<select class="form-control input-sm" name="store_id">
					<xsl:for-each select="/html/Body/store/ul/li">
						<xsl:element name="OPTION">
							<xsl:attribute name="value"><xsl:value-of select="value" /></xsl:attribute>
							<xsl:if test="value=/html/Body/details/ul/li/store_id">
								<xsl:attribute name="selected">selected</xsl:attribute>
							</xsl:if>
							<xsl:value-of select="text"/>
						</xsl:element>
					</xsl:for-each>
				</select>
			</div>
			<xsl:for-each select="/html/Body/details/ul/li">
			<div class="form-group">
				<label>创建日期：</label>

				<xsl:element name="INPUT">
					<xsl:attribute name="type">text</xsl:attribute>
					<xsl:attribute name="value"><xsl:value-of select="action_date" /></xsl:attribute>
					<xsl:attribute name="class">form-control input-sm</xsl:attribute>
					<xsl:attribute name="readonly">readonly</xsl:attribute>
				</xsl:element>

			</div>
			<div class="form-group">
				<label>单据编码：</label>
				<xsl:element name="INPUT">
					<xsl:attribute name="type">text</xsl:attribute>
					<xsl:attribute name="name">number</xsl:attribute>
					<xsl:attribute name="value"><xsl:value-of select="number" /></xsl:attribute>
					<xsl:attribute name="class">form-control input-sm</xsl:attribute>
					<xsl:attribute name="readonly">readonly</xsl:attribute>
				</xsl:element>
			</div>

			<div class="form-group">
				<label>审核状态：</label>
				<xsl:element name="INPUT">
					<xsl:attribute name="type">text</xsl:attribute>
					<xsl:attribute name="value"><xsl:value-of select="status_audit" /></xsl:attribute>
					<xsl:attribute name="class">form-control input-sm</xsl:attribute>
					<xsl:attribute name="readonly">readonly</xsl:attribute>
				</xsl:element>
			</div>
			</xsl:for-each>
			<br/>
			<div class="form-group">
				<label>采购摘要：</label>
				<xsl:element name="TEXTAREA">
					<xsl:attribute name="class">form-control input-sm merger_two_row_4</xsl:attribute>
					<xsl:attribute name="rows">3</xsl:attribute>
					<xsl:attribute name="name">brief</xsl:attribute>
					<xsl:value-of select="/html/Body/details/ul/li/brief" />
				</xsl:element>
			</div>
			<div class="form-group">
				<label>采购备注：</label>
				<xsl:element name="TEXTAREA">
					<xsl:attribute name="name">body</xsl:attribute>
					<xsl:attribute name="rows">3</xsl:attribute>
					<xsl:attribute name="class">form-control input-sm merger_three_row_8</xsl:attribute>
					<xsl:value-of select="/html/Body/details/ul/li/body" />
				</xsl:element>
			</div>
		</div>
		<div class="goodsMsg">
			<h4>商品信息</h4>
			<div class="table_operate_block">
				<input class="btn btn-default btn-sm btn_margin goodsAdd" type="button" value="添加" />
				<input class="btn btn-default btn-sm goodsDelete" type="button" value="删除" />
			</div>
			<table width="1180" class="table table-bordered table-hover" id="tab">
				<tr>
					<th class="table_th_number">序号</th>
					<th class="table_th_checkbox center"><input type="checkbox" name="select_all" /></th>
					<th width="106">搜索</th>
					<th width="230">商品名称与规格</th>
					<th width="120">单位</th>
					<th width="120">单价</th>
					<th width="120">数量</th>
					<th width="120">总价</th>
					<th width="300">备注</th>
				</tr>

				<xsl:for-each select="/html/Body/product/ul/li">
				<tr>
					<td class="center"><xsl:value-of select="no" /></td>
					<td class="center"><input type="checkbox" name="select_one" /></td>
					<td><input type="text" class="form-control input-sm form_no_border find" /></td>
					<td style="width:230px;">
						<select class="form-control input-sm form_no_border guige" name="product_id[]">
							<xsl:element name="OPTION">
							<xsl:attribute name="value">
								<xsl:value-of select="product_id" />,<xsl:value-of select="format" />
							</xsl:attribute>
							<xsl:value-of select="pro" />,<xsl:value-of select="format" />
							</xsl:element>
						</select>
			  		</td>
			  		<td>
			  			<xsl:element name="INPUT">
							<xsl:attribute name="type">hidden</xsl:attribute>
							<xsl:attribute name="name">parts_id[]</xsl:attribute>
							<xsl:attribute name="value">
								<xsl:value-of select="parts_id" />
							</xsl:attribute>
							<xsl:attribute name="class">form-control input-sm form_no_border</xsl:attribute>
						</xsl:element>
						<xsl:value-of select="parts" />
			  		</td>
					<td>
					   <div class="input-group">
							<div class="input-group-addon">￥</div>
							<xsl:element name="INPUT">
								<xsl:attribute name="type">text</xsl:attribute>
								<xsl:attribute name="name">price[]</xsl:attribute>
								<xsl:attribute name="value"><xsl:value-of select="price" /></xsl:attribute>
								<xsl:attribute name="class">form-control input-sm form_no_border danjia</xsl:attribute>
							</xsl:element>
					   </div>
					</td>
					<td>
						<xsl:element name="INPUT">
							<xsl:attribute name="type">text</xsl:attribute>
							<xsl:attribute name="name">total[]</xsl:attribute>
							<xsl:attribute name="value"><xsl:value-of select="total" /></xsl:attribute>
							<xsl:attribute name="class">form-control input-sm form_no_border number</xsl:attribute>
							<xsl:attribute name="onkeyup">value=value.replace(/[^\d]/g,'')</xsl:attribute>
						</xsl:element>
					</td>
					<td>
						<div class="input-group">
							<xsl:element name="DIV">
								<xsl:attribute name="class">input-group-addon</xsl:attribute>
							￥<xsl:value-of select="zongjia" />
							</xsl:element>
					   </div>
					</td>
					<td>
						<xsl:element name="INPUT">
							<xsl:attribute name="type">text</xsl:attribute>
							<xsl:attribute name="name">content[]</xsl:attribute>
							<xsl:attribute name="value"><xsl:value-of select="content" /></xsl:attribute>
							<xsl:attribute name="class">form-control input-sm form_no_border</xsl:attribute>
						</xsl:element>
					</td>
				</tr>
				</xsl:for-each>
				<tr>
					<td class="center">合计</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td class="purchase_sum">　
						<xsl:element name="INPUT">
							<xsl:attribute name="type">hidden</xsl:attribute>
							<xsl:attribute name="name">sum</xsl:attribute>
							<xsl:attribute name="value"><xsl:value-of select="/html/Body/details/ul/li/total" /></xsl:attribute>
						</xsl:element>
						<span class="zongshu"><xsl:value-of select="/html/Body/details/ul/li/total" /></span></td>
					<td class="purchase_all">￥
						<xsl:element name="INPUT">
							<xsl:attribute name="type">hidden</xsl:attribute>
							<xsl:attribute name="name">price_main</xsl:attribute>
							<xsl:attribute name="value"><xsl:value-of select="/html/Body/details/ul/li/price" /></xsl:attribute>
						</xsl:element>
						<span class='zongjia'>
							<xsl:value-of select="/html/Body/details/ul/li/price" />
						</span>
					</td>
					<td></td>
				</tr>
			</table>
		</div>
		<h4>合计</h4>
		<div class="commonMsg totalMsg">
			<p>采购数量总计：<span class="money_color"><span class="zongshu"><xsl:value-of select="/html/Body/details/ul/li/total" /></span></span><span style="margin-left:20px;">采购总价：<span class="money_color"><span class="zongjia"><xsl:value-of select="/html/Body/details/ul/li/price" /></span></span>元</span>
			</p>
		</div>
		<h4>运费</h4>
		<div class="commonMsg freightMsg">
			<div class="form-group form-group_bottom0">
				<label>付运费方：</label>
				<select class="form-control input-sm" name="freight_side">
					<xsl:element name="OPTION">
						<xsl:attribute name="value">Supplier</xsl:attribute>
						<xsl:if test="'Supplier'=/html/Body/details/ul/li/freight_side">
							<xsl:attribute name="selected">selected</xsl:attribute>
						</xsl:if>
						供应商
					</xsl:element>
					<xsl:element name="OPTION">
						<xsl:attribute name="value">Company</xsl:attribute>
						<xsl:if test="'Company'=/html/Body/details/ul/li/freight_side">
							<xsl:attribute name="selected">selected</xsl:attribute>
						</xsl:if>
						本公司
					</xsl:element>
				</select>
			</div>
			<div class="form-group form-group_bottom0">
				<label>运费金额：</label>
				<div class="input-group">
					<div class="input-group-addon">￥</div>
					<xsl:element name="INPUT">
						 <xsl:attribute name="type">text</xsl:attribute>
						 <xsl:attribute name="name">freight_amount</xsl:attribute>
						 <xsl:attribute name="value"><xsl:value-of select="/html/Body/details/ul/li/freight_amount" /></xsl:attribute>
						 <xsl:attribute name="onkeyup">value=value.replace(/[^\d.]/g,'')</xsl:attribute>
						 <xsl:attribute name="class">form-control input-sm</xsl:attribute>
						 <xsl:attribute name="style">width:110px;</xsl:attribute>
					</xsl:element>
				</div>
			</div>
			<div class="form-group form-group_bottom0">
				<label>托运公司：</label>
				<xsl:element name="INPUT">
					<xsl:attribute name="type">text</xsl:attribute>
					<xsl:attribute name="name">shipping_company</xsl:attribute>
					<xsl:attribute name="value"><xsl:value-of select="/html/Body/details/ul/li/shipping_company" /></xsl:attribute>
					<xsl:attribute name="class">form-control input-sm</xsl:attribute>
				</xsl:element>
			</div>
			<div class="form-group form-group_bottom0">
				<label>运单号码：</label>
				<xsl:element name="INPUT">
					<xsl:attribute name="type">text</xsl:attribute>
					<xsl:attribute name="name">waybill_number</xsl:attribute>
					<xsl:attribute name="value"><xsl:value-of select="/html/Body/details/ul/li/waybill_number" /></xsl:attribute>
					<xsl:attribute name="class">form-control input-sm</xsl:attribute>
				</xsl:element>
			</div>
		</div>
		<h4>财务</h4>
		<div class="commonMsg freightMsg">
			<div class="form-group form-group_bottom0">
				<label>付款方式：</label>
				<select class="form-control input-sm" name="pay_method">
					<xsl:element name="OPTION">
						<xsl:attribute name="value">After</xsl:attribute>
						<xsl:if test="'After' =/html/Body/details/ul/li/pay_method">
							<xsl:attribute name="selected">selected</xsl:attribute>
						</xsl:if>
						后付款
					</xsl:element>
					<xsl:element name="OPTION">
						<xsl:attribute name="value">Deposit</xsl:attribute>
						<xsl:if test="'Deposit'=/html/Body/details/ul/li/pay_method">
							<xsl:attribute name="selected">selected</xsl:attribute>
						</xsl:if>
						订金加尾款
					</xsl:element>
					<xsl:element name="OPTION">
						<xsl:attribute name="value">New</xsl:attribute>
						<xsl:if test="'New'=/html/Body/details/ul/li/pay_method">
							<xsl:attribute name="selected">selected</xsl:attribute>
						</xsl:if>
						先付款
					</xsl:element>
				</select>
			</div>
		</div>
		<p>
			<input class="btn btn-default btn-sm btn_margin" name="send" type="submit" value="提交" />
			<input class="btn btn-default btn-sm" type="reset" value="重置" />
		</p>
	</form>
</div>
</xsl:template>
</xsl:stylesheet>
