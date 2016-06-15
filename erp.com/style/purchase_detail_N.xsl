<?xml version="1.0" encoding="utf-8" ?>
<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<!-- UTF8 编码 -->
<xsl:include href="/style/header.xsl" />
<xsl:template name="text">

<div class="mainBody">
	<form class="form-inline" method="post" action="/purchase/purchase_add.php">
		<xsl:element name="INPUT">
			<xsl:attribute name="type">hidden</xsl:attribute>
			<xsl:attribute name="name">id</xsl:attribute>
			<xsl:attribute name="value"><xsl:value-of select="/html/Body/details/ul/li/id" /></xsl:attribute>
		</xsl:element>
		<div class="base">
			<xsl:for-each select="/html/Body/details/ul/li">
				<div class="form-group">
					<label class="margin_left_1">供应商：</label>
					<xsl:element name="INPUT">
						<xsl:attribute name="type">text</xsl:attribute>
						<xsl:attribute name="value"><xsl:value-of select="supplier_id" /></xsl:attribute>
						<xsl:attribute name="class">form-control input-sm</xsl:attribute>
						<xsl:attribute name="readonly">readonly</xsl:attribute>
					</xsl:element>
				</div>
				<div class="form-group">
					<label>收货仓库：</label>
					<xsl:element name="INPUT">
						<xsl:attribute name="type">text</xsl:attribute>
						<xsl:attribute name="value"><xsl:value-of select="store_id" /></xsl:attribute>
						<xsl:attribute name="class">form-control input-sm</xsl:attribute>
						<xsl:attribute name="readonly">readonly</xsl:attribute>
					</xsl:element>
				</div>
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
				<br/>
				<div class="form-group">
					<label>采购摘要：</label>
					<xsl:element name="TEXTAREA">
						<xsl:attribute name="class">form-control input-sm merger_two_row_4</xsl:attribute>
						<xsl:attribute name="rows">3</xsl:attribute>
						<xsl:attribute name="name">brief</xsl:attribute><xsl:value-of select="brief" />
					</xsl:element>
				</div>
				<div class="form-group">
					<label>采购备注：</label>
					<xsl:element name="TEXTAREA">
						<xsl:attribute name="name">body</xsl:attribute>
						<xsl:attribute name="rows">3</xsl:attribute>
						<xsl:attribute name="class">form-control input-sm merger_three_row_8</xsl:attribute>
						<xsl:value-of select="body" />
					</xsl:element>
				</div>
			</xsl:for-each>
		</div>
		<div class="goodsMsg">
			<h4>商品信息</h4>
			<table width="1180" class="table table-bordered table-hover" id="tab">


				<tr>
					<th class="table_th_number">序号</th>
					<th width="165">商品名称</th>
					<th width="165">商品规格</th>
					<th width="130">单位</th>
					<th width="130">单价</th>
					<th width="130">数量</th>
					<th width="130">总价</th>
					<th width="304">备注</th>
				</tr>
				<xsl:for-each select="/html/Body/product/ul/li">
				<tr>
					<td class="center"><xsl:value-of select="no" /></td>
					<td>
					<xsl:element name="INPUT">
						<xsl:attribute name="type">text</xsl:attribute>
						<xsl:attribute name="value"><xsl:value-of select="pro" /></xsl:attribute>
						<xsl:attribute name="class">form-control input-sm</xsl:attribute>
						<xsl:attribute name="style">width:160px</xsl:attribute>
						<xsl:attribute name="readonly">readonly</xsl:attribute>
					</xsl:element>
			  		</td>
			  		<td>
			  			<xsl:element name="INPUT">
						<xsl:attribute name="type">text</xsl:attribute>
						<xsl:attribute name="value"><xsl:value-of select="format" /></xsl:attribute>
						<xsl:attribute name="class">form-control input-sm</xsl:attribute>
						<xsl:attribute name="style">width:160px</xsl:attribute>
						<xsl:attribute name="readonly">readonly</xsl:attribute>
					</xsl:element>
			  		</td>
			  		<td>
			  			<xsl:element name="INPUT">
						<xsl:attribute name="type">text</xsl:attribute>
						<xsl:attribute name="value"><xsl:value-of select="parts_id" /></xsl:attribute>
						<xsl:attribute name="class">form-control input-sm</xsl:attribute>
						<xsl:attribute name="style">width:112px</xsl:attribute>
						<xsl:attribute name="readonly">readonly</xsl:attribute>
					</xsl:element>
			  		</td>
					<td>
					   <div class="input-group">
							<div class="input-group-addon">￥</div>
							<xsl:element name="INPUT">
								<xsl:attribute name="type">text</xsl:attribute>
								<xsl:attribute name="value"><xsl:value-of select="price" /></xsl:attribute>
								<xsl:attribute name="class">form-control input-sm form_no_border danjia</xsl:attribute>
								<xsl:attribute name="readonly">readonly</xsl:attribute>
							</xsl:element>
					   </div>
					</td>
					<td>
						<xsl:element name="INPUT">
							<xsl:attribute name="type">text</xsl:attribute>
							<xsl:attribute name="value"><xsl:value-of select="total" /></xsl:attribute>
							<xsl:attribute name="class">form-control input-sm form_no_border number</xsl:attribute>
							<xsl:attribute name="readonly">readonly</xsl:attribute>
						</xsl:element>
					</td>
					<td>
						<div class="input-group">
							<div class="input-group-addon">￥</div>
							<xsl:element name="INPUT">
								<xsl:attribute name="type">text</xsl:attribute>
								<xsl:attribute name="value"><xsl:value-of select="zongjia" /></xsl:attribute>
								<xsl:attribute name="class">form-control input-sm form_no_border danjia</xsl:attribute>
								<xsl:attribute name="readonly">readonly</xsl:attribute>
							</xsl:element>
					   </div>
					</td>
					<td>
						<xsl:element name="INPUT">
							<xsl:attribute name="type">text</xsl:attribute>
							<xsl:attribute name="name">content</xsl:attribute>
							<xsl:attribute name="value"><xsl:value-of select="content" /></xsl:attribute>
							<xsl:attribute name="class">form-control input-sm form_no_border</xsl:attribute>
						</xsl:element>
					</td>
					<xsl:element name="INPUT">
						<xsl:attribute name="type">hidden</xsl:attribute>
						<xsl:attribute name="value"><xsl:value-of select="product_id" /></xsl:attribute>
						<xsl:attribute name="name">product_id</xsl:attribute>
					</xsl:element>
				</tr>
			</xsl:for-each>
			<xsl:for-each select="/html/Body/details/ul/li">
				<tr>
					<td class="center">合计</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td class="purchase_sum">　<input type="hidden" name="sum" value=''/><span class="zongshu"><xsl:value-of select="total" /></span></td>
					<td>
						<div class="input-group">
							<div class="input-group-addon">￥</div>
							<xsl:element name="INPUT">
								<xsl:attribute name="type">text</xsl:attribute>
								<xsl:attribute name="value"><xsl:value-of select="price" /></xsl:attribute>
								<xsl:attribute name="class">form-control input-sm form_no_border danjia</xsl:attribute>
								<xsl:attribute name="readonly">readonly</xsl:attribute>
							</xsl:element>
					   </div>
					</td>
					<td></td>
				</tr>
			</xsl:for-each>
			</table>
		</div>
		<xsl:for-each select="/html/Body/details/ul/li">
		<h4>合计</h4>
		<div class="commonMsg totalMsg">
			<p>采购数量总计：<span class="money_color"><span class="zongshu"><xsl:value-of select="total" /></span></span><span style="margin-left:20px;">采购总价：<span class="money_color"><span class=""><xsl:value-of select="price" /></span></span>元</span>
			</p>
		</div>
		<h4>运费</h4>
		<div class="commonMsg freightMsg">
			<div class="form-group form-group_bottom0">
				<label>付运费方：</label>
				<xsl:element name="INPUT">
					 <xsl:attribute name="type">text</xsl:attribute>
					 <xsl:attribute name="value"><xsl:value-of select="freight_side" /></xsl:attribute>
					 <xsl:attribute name="class">form-control input-sm</xsl:attribute>
					 <xsl:attribute name="style">width:110px;</xsl:attribute>
					 <xsl:attribute name="readonly">readonly</xsl:attribute>
				</xsl:element>
			</div>
			<div class="form-group form-group_bottom0">
				<label>运费金额：</label>
				<div class="input-group">
					<div class="input-group-addon">￥</div>
					<xsl:element name="INPUT">
						 <xsl:attribute name="type">text</xsl:attribute>
						 <xsl:attribute name="value"><xsl:value-of select="freight_amount" /></xsl:attribute>
						 <xsl:attribute name="class">form-control input-sm</xsl:attribute>
						 <xsl:attribute name="style">width:110px;</xsl:attribute>
						 <xsl:attribute name="readonly">readonly</xsl:attribute>
					</xsl:element>
				</div>
			</div>
			<div class="form-group form-group_bottom0">
				<label>托运公司：</label>
				<xsl:element name="INPUT">
					<xsl:attribute name="type">text</xsl:attribute>
					<xsl:attribute name="value"><xsl:value-of select="shipping_company" /></xsl:attribute>
					<xsl:attribute name="class">form-control input-sm</xsl:attribute>
					<xsl:attribute name="name">shipping_company</xsl:attribute>
				</xsl:element>
			</div>
			<div class="form-group form-group_bottom0">
				<label>运单号码：</label>
				<xsl:element name="INPUT">
					<xsl:attribute name="type">text</xsl:attribute>
					<xsl:attribute name="value"><xsl:value-of select="waybill_number" /></xsl:attribute>
					<xsl:attribute name="class">form-control input-sm</xsl:attribute>
					<xsl:attribute name="name">waybill_number</xsl:attribute>
				</xsl:element>
			</div>
		</div>
		<h4>财务</h4>
		<div class="commonMsg freightMsg">
			<div class="form-group form-group_bottom0">
				<label>付款方式：</label>
				<xsl:element name="INPUT">
					<xsl:attribute name="type">text</xsl:attribute>
					<xsl:attribute name="value"><xsl:value-of select="pay_method" /></xsl:attribute>
					<xsl:attribute name="class">form-control input-sm</xsl:attribute>
					<xsl:attribute name="readonly">readonly</xsl:attribute>
				</xsl:element>

			</div>
		</div>
		</xsl:for-each>
		<p>
			<a href="/purchase/purchase_list.php"><input class="btn btn-default btn-sm btn_margin" name="send" type="button" value="返回" /></a>
		</p>
	</form>
</div>
<script>
	//采购摘要失去焦点更改信息
	$('textarea[name=brief]').blur(function(){
		var brief = $(this).val();
		var purchase_id = $('input[name=id]').val();
		$.ajax({
			url:'purchase_detail_N.php',
			type:'post',
			data:{'brief':brief,'purchase_id':purchase_id},
		})
	})
	//采购备注失去焦点更改信息
	$('textarea[name=body]').blur(function(){
		var body = $(this).val();
		var purchase_id = $('input[name=id]').val();
		$.ajax({
			url:'purchase_detail_N.php',
			type:'post',
			data:{'body':body,'purchase_id':purchase_id},
		})
	})
	//商品备注失去焦点更改信息
	$('input[name=content]').blur(function(){
		var content = $(this).val();
		var purchase_id = $('input[name=id]').val();
		var product_id = $(this).parents('tr').find('input[name=product_id]').val();

		$.ajax({
			url:'purchase_detail_N.php',
			type:'post',
			data:{'content':content,'purchase_id':purchase_id,'product_id':product_id},
		})
	})
	//托运公司失去焦点更改信息
	$('input[name=shipping_company]').blur(function(){
		var shipping_company = $(this).val();
		var purchase_id = $('input[name=id]').val();
		$.ajax({
			url:'purchase_detail_N.php',
			type:'post',
			data:{'shipping_company':shipping_company,'purchase_id':purchase_id},
		})
	})
	//运单号时区焦点更改信息
	$('input[name=waybill_number]').blur(function(){
		var waybill_number = $(this).val();
		var purchase_id = $('input[name=id]').val();
		$.ajax({
			url:'purchase_detail_N.php',
			type:'post',
			data:{'waybill_number':waybill_number,'purchase_id':purchase_id},
		})
	})
</script>
</xsl:template>
</xsl:stylesheet>
