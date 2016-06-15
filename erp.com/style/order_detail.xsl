<?xml version="1.0" encoding="utf-8" ?>
<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<!-- UTF8 编码 -->

<xsl:include href="/style/header.xsl" />


<xsl:template name="text">

<script type="text/javascript" src="/js_encode/order_detail.js"></script>

<script type="text/javascript">
	var sheng = '<xsl:value-of select="/html/Body/supplierMsg/type/ul/li/sheng" />';
	var shi   = '<xsl:value-of select="/html/Body/supplierMsg/type/ul/li/shi" />';
	var xian  = '<xsl:value-of select="/html/Body/supplierMsg/type/ul/li/xian"/>';
	var express = '<xsl:value-of select="/html/Body/supplierMsg/type/ul/li/express"/>';
	var bank_id = '<xsl:value-of select="/html/Body/supplierMsg/type/ul/li/bank_id"/>';
	var store = '<xsl:value-of select="/html/Body/product/type/ul/li/store_id"/>';
	var shop = '<xsl:value-of select="/html/Body/supplierMsg/type/ul/li/shop"/>';
	var status = '<xsl:value-of select="/html/Body/supplierMsg/type/ul/li/status"/>';
	var invoice =  '<xsl:value-of select="/html/Body/supplierMsg/type/ul/li/invoice"/>';
	var tax_type =  '<xsl:value-of select="/html/Body/supplierMsg/type/ul/li/tax_type"/>';
</script>

<style type="text/css">
.order_add_td{width:170px;}
.order_add_td_1{width:150px;}
.order_add_td_2{width:120px;}
.order_add_td_3{width:80px;}
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
<xsl:for-each select="/html/Body/supplierMsg/type/ul/li">

<div class="mainBody">
<form class="form-inline margin_top_add2" method="post" action="">
	<div class="orderMsg">
		<h4 class="padding_clear">订单信息　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　&#160;　&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;客户信息</h4>
		<div class="supplierMsg">
		   <div class="form-group">
				<label>下单时间：</label>
				<xsl:element name="INPUT">
					<xsl:attribute name="type">text</xsl:attribute>
					<xsl:attribute name="name">order_date</xsl:attribute>
					<xsl:attribute name="readonly">readonly</xsl:attribute>

					<xsl:attribute name="value"><xsl:value-of select="time"/></xsl:attribute>
					<xsl:attribute name="class">form-control input-sm </xsl:attribute>
				</xsl:element>
			</div>
			<div class=" form-group margin_left_block">
				<label>订单编号：</label>
				<xsl:element name="INPUT">
					<xsl:attribute name="type">text</xsl:attribute>
					<xsl:attribute name="name">bind_number</xsl:attribute>
					<xsl:attribute name="class">form-control input-sm order_num</xsl:attribute>
					<xsl:attribute name="readonly">readonly</xsl:attribute>
					<xsl:attribute name="value"><xsl:value-of select="text"/></xsl:attribute>
					<!-- <xsl:attribute name="disabled">true	</xsl:attribute> -->
				</xsl:element>
			</div>
			<div class="form-group recog">
				<label>客户识别：</label>
				<xsl:element name="INPUT">
					<xsl:attribute name="type">text</xsl:attribute>
					<xsl:attribute name="name">recog</xsl:attribute>
					<xsl:attribute name="placeholder">在此搜索</xsl:attribute>
					<xsl:attribute name='readonly'>readonly</xsl:attribute>
					<xsl:attribute name="class"><form-contro></form-contro>form-control input-sm</xsl:attribute>
				</xsl:element>
			</div>
			<div class="form-group">
				<label>客户选择：</label>
				<select class="form-control input-sm crm" disabled="disabled" placeholder="213">
				</select>
			</div>
				<div class="form-group">
				<label>用户昵称：</label>
				<xsl:element name="INPUT">
					<xsl:attribute name="type">text</xsl:attribute>
					<xsl:attribute name="name">phone</xsl:attribute>
					<xsl:attribute name="readonly">readonly</xsl:attribute>
					<xsl:attribute name="class">form-control input-sm</xsl:attribute>
				</xsl:element>
			</div>
			<div class="form-group">
				<label>购买渠道：</label>
				<select class="form-control input-sm" disabled="disabled" name="bind_type">
					<!-- <xsl:for-each select="/html/Body/buy/type/ul/li"> -->
						<xsl:element name="OPTION">
							<xsl:attribute name="value"><xsl:value-of select="btype" /></xsl:attribute>
							<xsl:value-of select="bindtype"/>
						</xsl:element>
					<!-- </xsl:for-each> -->
				</select>
			</div>
			<div class="form-group margin_left_block">
				<label>关联订单：</label>
				<xsl:element name="INPUT">
					<xsl:attribute name="type">text</xsl:attribute>
					<xsl:attribute name="name">related_order</xsl:attribute>
					<xsl:attribute name="value"><xsl:value-of select="relatedorder" /></xsl:attribute>
					<xsl:attribute name="readonly">readonly</xsl:attribute>
					<xsl:attribute name="class">form-control input-sm</xsl:attribute>
				</xsl:element>
			</div>

			<div class="form-group">
				<label class="margin_left_1">收件人：</label>
				<xsl:element name="INPUT">
					<xsl:attribute name="type">text</xsl:attribute>
					<xsl:attribute name="name">name</xsl:attribute>
					<xsl:attribute name="class">form-control input-sm</xsl:attribute>
					<xsl:attribute name="readonly">readonly</xsl:attribute>

					<xsl:attribute name="value">
						<xsl:value-of select="name" />
					</xsl:attribute>

				</xsl:element>
			</div>
			<div class="form-group">
				<label>手机号码：</label>
				<xsl:element name="INPUT">
					<xsl:attribute name="type">text</xsl:attribute>
					<xsl:attribute name="class">form-control input-sm</xsl:attribute>
					<xsl:attribute name="name">mobile</xsl:attribute>
					<xsl:attribute name="readonly">readonly</xsl:attribute>

					<xsl:attribute name="value">
						<xsl:value-of select="mobile" />
					</xsl:attribute>
				</xsl:element>
			</div>
			<div class="form-group">
				<label>固定电话：</label>
				<xsl:element name="INPUT">
					<xsl:attribute name="class">form-control input-sm</xsl:attribute>
					<xsl:attribute name="data-toggle">tooltip</xsl:attribute>
					<xsl:attribute name="data-placement">bottom</xsl:attribute>
					<xsl:attribute name="data-original-title">手机和固话选填一个</xsl:attribute>
					<xsl:attribute name="type">text</xsl:attribute>
					<xsl:attribute name="readonly">readonly</xsl:attribute>

					<xsl:attribute name="name">phone</xsl:attribute>
					<xsl:attribute name="value">
						<xsl:value-of select="phone" />
					</xsl:attribute>
				</xsl:element>
			</div>
			<div class="form-group">
				<label>店铺名称：</label>
				<select class="form-control input-sm shop" disabled="disabled" name="shop_title">
					<xsl:for-each select="/html/Body/shop/type/ul/li">
						<xsl:element name="OPTION">
							<xsl:attribute name="value">
									<xsl:value-of select="value" />
							</xsl:attribute>
							<xsl:value-of select="text" />
						</xsl:element>
					</xsl:for-each>
				</select>
			</div>
			<div class="form-group margin_left_block">
				<label>发货仓库：</label>
				<select class="form-control input-sm store" disabled="disabled" name="store_id">
					<xsl:for-each select="/html/Body/store/type/ul/li">
						<xsl:element name="OPTION">
							<xsl:attribute name="value">
									<xsl:value-of select="value" />
							</xsl:attribute>
							<xsl:value-of select="text" />
						</xsl:element>
					</xsl:for-each>
				</select>
			</div>
			<div class="form-group">
				<label>公司名称：</label>
				<xsl:element name="INPUT">
					<xsl:attribute name="type">text</xsl:attribute>
					<xsl:attribute name="readonly">readonly</xsl:attribute>

					<xsl:attribute name="name">company_name</xsl:attribute>
					<xsl:attribute name="class">form-control input-sm merger_two_row_4</xsl:attribute>
					<xsl:attribute name="value">
						<xsl:value-of select="companyname" />
					</xsl:attribute>
				</xsl:element>
			</div>
			<div class="form-group">
				<label>邮政编码：</label>
				<xsl:element name="INPUT">
					<xsl:attribute name="type">text</xsl:attribute>
					<xsl:attribute name="name">post_code</xsl:attribute>
					<xsl:attribute name="class">form-control input-sm</xsl:attribute>
					<xsl:attribute name="readonly">readonly</xsl:attribute>

					<xsl:attribute name="value">
						<xsl:value-of select="postcode" />
					</xsl:attribute>
				</xsl:element>
			</div>
			<div class="form-group">
				<label>到账账户：</label>
				<select class="form-control input-sm bank" disabled="disabled" name="bank_id">
					<xsl:for-each select="/html/Body/account/type/ul/li">
						<xsl:element name="OPTION">
						<xsl:attribute name="value"><xsl:value-of select="value" /></xsl:attribute>
						<!-- <xsl:if test="is_default = 'Y'"> -->
						<!-- <xsl:attribute name="checked">true</xsl:attribute> -->
						<!-- </xsl:if> -->
						<xsl:value-of select="text" />
						</xsl:element>
						<xsl:element name="INPUT">
							<xsl:attribute name="type">hidden</xsl:attribute>
							<xsl:attribute name="name">bankId</xsl:attribute>
							<xsl:attribute name="value">
								<xsl:value-of select="value" />
							</xsl:attribute>
						</xsl:element>
					</xsl:for-each>
				</select>
			</div>
			<div class="form-group margin_left_block">
				<label>发票收据：</label>
				<select class="form-control input-sm shouju" disabled="disabled" name="need_invoice">
					<option value="N">无</option>
					<option value="Y">有</option>

				</select>
			</div>

			<div class="form-group shengDiv">

			</div>
			<div class="form-group shiDiv">

			</div>
			<div class="form-group xianDiv">

			</div>

			<div class="form-group">
				<label>快递公司：</label>
				<select class="form-control input-sm express" disabled="disabled" name="express_id">
					<xsl:for-each select="/html/Body/kdgs/type/ul/li">
						<xsl:element name="OPTION">
							<xsl:attribute name="value"><xsl:value-of select="value" /></xsl:attribute>
							<xsl:value-of select="text" />
						</xsl:element>
					</xsl:for-each>
				</select>
			</div>
			<div class="form-group margin_left_block">
				<label>快递运费：</label>
					<div class="input-group">
					<div class="input-group-addon">￥</div>
					   <xsl:element name="INPUT">
							<xsl:attribute name="type">text</xsl:attribute>
							<xsl:attribute name="name">post_fee</xsl:attribute>
							<xsl:attribute name="value"><xsl:value-of select="postfee" /></xsl:attribute>
							<xsl:attribute name="readonly">readonly</xsl:attribute>
							<xsl:attribute name="class">form-control input-sm</xsl:attribute>
							<xsl:attribute name="style">width: 110px;</xsl:attribute>
						</xsl:element>
					</div>
			</div>
			<div class="form-group">
				<label>详细地址：</label>
				<xsl:element name="INPUT">
					<xsl:attribute name="type">text</xsl:attribute>
					<xsl:attribute name="name">address</xsl:attribute>
					<xsl:attribute name='value'><xsl:value-of select="address" /></xsl:attribute>
					<xsl:attribute name="readonly">readonly</xsl:attribute>
					<xsl:attribute name="class">form-control input-sm merger_three_row_8</xsl:attribute>
				</xsl:element>
			</div>
			<div class=" form-group margin_left_block" style="margin:0 20px 12px 0;">
				<label>快递单号：</label>
				<xsl:element name="INPUT">
					<xsl:attribute name="type">text</xsl:attribute>
					<xsl:attribute name="name">express_number</xsl:attribute>
					<xsl:attribute name="class">form-control input-sm</xsl:attribute>
					<xsl:attribute name='value'><xsl:value-of select="number" /></xsl:attribute>
					<xsl:attribute name="readonly">readonly</xsl:attribute>
				</xsl:element>
			</div>
			<div class="form-group">
				<label>　　重量：</label>
				<div class="input-group col-xs-7">
					<xsl:element name="INPUT">
						<xsl:attribute name="name">weight_number</xsl:attribute>
						<xsl:attribute name="class">form-control input-sm</xsl:attribute>
						<xsl:attribute name="readonly">readonly</xsl:attribute>
						<xsl:attribute name="style">width: 107px;</xsl:attribute>
						<xsl:attribute name="value"><xsl:value-of select="weight" /></xsl:attribute>
					</xsl:element>
					<div class="input-group-addon">kg</div>
				</div>
			</div>
			<div class="form-group" style="margin:0 20px 7px 20px;">
				<label>客户留言：</label>
				<xsl:element name="INPUT">
					<xsl:attribute name="type">text</xsl:attribute>
					<xsl:attribute name="name">order_text</xsl:attribute>
					<xsl:attribute name="readonly">readonly</xsl:attribute>
					<xsl:attribute name="value"><xsl:value-of select="ordertext" /></xsl:attribute>
					<xsl:attribute name="class">form-control input-sm merger_three_row_8</xsl:attribute>
				</xsl:element>
			</div>
			<div class="form-group margin_left_block">
				<label>订单备注：</label>
				<xsl:element name="INPUT">
					<xsl:attribute name="type">text</xsl:attribute>
					<xsl:attribute name="value"><xsl:value-of select="customer" /></xsl:attribute>
					<xsl:attribute name="name">customer_text</xsl:attribute><!--字段未找到-->
					<xsl:attribute name="readonly">readonly</xsl:attribute>
					<xsl:attribute name="class">form-control input-sm merger_two_row_4</xsl:attribute>
				</xsl:element>
			</div>
		</div>
		<div class="goodsMsg">
			<h4>选择商品</h4>
			<table style="margin-top:10px;" class="table table-bordered table-hover">
				<tr>
					<th class="table_th_number">序号</th>
					<th class="table_th_number">图片</th>
					<th style="width:200px">商品名称</th>
					<th style="width:200px">商品规格</th>
					<th class="table_th_number" width="1000px">单位</th>
					<th style="width:100px">单价</th>
					<th style="width:100px">数量</th>
					<th style="width:100px">优惠</th>
					<th style="width:100px">应付</th>
					<th style="width:200px">备注</th>
				</tr>
				<xsl:for-each select="/html/Body/product/type/ul/li">

				<tr>
					<td class="center"><xsl:value-of select='num' /></td>
					<td class="center">
						<xsl:element name="IMG">
							<xsl:attribute name='src'>
								<xsl:value-of select="img"/>
							</xsl:attribute>
							<xsl:attribute name="width">20</xsl:attribute>
							<xsl:attribute name="height">20</xsl:attribute>
							<xsl:attribute name="class">images center</xsl:attribute>
						</xsl:element>
						<xsl:element name="IMG">
							<xsl:attribute name="src"><xsl:value-of select="img"/></xsl:attribute>
							<xsl:attribute name="width">200</xsl:attribute>
							<xsl:attribute name="height">200</xsl:attribute>
							<xsl:attribute name="style">display:none;position:absolute</xsl:attribute>
							<xsl:attribute name="class">bigimg center</xsl:attribute>
						</xsl:element>
					</td>
					<td class="order_add_td_2" style="width:200px;">
						<xsl:value-of select='name' />
					</td>
					<td class="order_add_td_2" style="width:200px;">
						<xsl:value-of select='format' />
					</td>
					<td>
						 <xsl:value-of select='unit' />
					</td>
					<td class="order_add_td_2" style="width:80px;">
						￥<xsl:value-of select='price' />
					</td>
					<td class="order_add_td_3">
						<xsl:value-of select='total' />
					</td>
					<td class="order_add_td_2" style="width:100px;">
						￥<xsl:value-of select='discount' />
					</td>
					<td class="order_add_td_2" style="width:80px;">
						￥<xsl:value-of select='payment' />
					</td>
					<td class="order_add_td_2">
						<xsl:value-of select='content' />
					</td>
				</tr>
				</xsl:for-each>

				<tr class="total">
					<td class="center">合计</td>
					<td colspan='5'></td>
					<td class="num">
						<xsl:value-of select='total'/>
					</td>
					<td class="order_add_td_2" style="width:80px;">
						￥<xsl:value-of select='discount'/>
					</td>
					<td class="order_add_td_2" style="width:80px;">
						￥<xsl:value-of select='zong'/>
					</td>
					<td></td>
				</tr>
			</table>
		</div>
		<h4>合计</h4>
		<div class="commonMsg totalMsg">
			<p>商品总金额
				<span class="money_color total">
					<xsl:value-of select='total_amount'/>
				</span>元 - 商品总优惠
				<span class="money_color yh">
					<xsl:value-of select='discount'/>
				</span>元 - 订单总优惠
				<input class="form-control input-sm" style="width:69px;margin:0 4px;" readonly="readonly" type="text"/>元 +
				<xsl:element name="INPUT">
					<xsl:attribute name="type">text</xsl:attribute>
					<xsl:attribute name="class">form-control input-sm</xsl:attribute>
					<xsl:attribute name='value'><xsl:value-of select="freight_buyer"/></xsl:attribute>
					<xsl:attribute name="name">freight_buyer</xsl:attribute>
					<xsl:attribute name="readonly">readonly</xsl:attribute>
					<xsl:attribute name="style">width:69px;margin:0 4px;</xsl:attribute>
				</xsl:element>元 = 实付
				<span class="money_color end">
					<xsl:value-of select='theory'/>
				</span>元　<label>审核：</label>


				<xsl:element name="INPUT">
					<xsl:attribute name="type">text</xsl:attribute>
					<xsl:attribute name="value"><xsl:value-of select="audit" /></xsl:attribute>
					<xsl:attribute name="class">form-control input-sm</xsl:attribute>
					<xsl:attribute name="readonly">readonly</xsl:attribute>
					<xsl:attribute name="style">width: 110px;</xsl:attribute>
				</xsl:element>
			</p>

		</div>
		<div id='fapiao' style="display:none">
			<h4>发票</h4>
			<div class="commonMsg invoiceMsg">
				<div class="form-group form-group_bottom0">
					<label>发票类型：</label>
					<select class="none form-control input-sm" disabled="disabled" name="">
				        invoice_type/ul/li">
						<xsl:for-each select="/html/Body/invoiceMsg/type/ul/li">
							<xsl:element name="OPTION">
								<xsl:attribute name="value"><xsl:value-of select="value" /></xsl:attribute>
								<xsl:value-of select="text" />
							</xsl:element>
						</xsl:for-each>
					</select>
				</div>
				<div class="form-group form-group_bottom0">
					<label>发票抬头：</label>
					<xsl:element name="INPUT">
						<xsl:attribute name="type">text</xsl:attribute>
						<xsl:attribute name="name">tax_title</xsl:attribute>
						<xsl:attribute name="value"><xsl:value-of select="tax_title"/></xsl:attribute>
						<xsl:attribute name="class">form-control input-sm</xsl:attribute>
						<xsl:attribute name="readonly">readonly</xsl:attribute>
					</xsl:element>
				</div>
				<div class="form-group form-group_bottom0">
					<label>发票明细：</label>
					<xsl:element name="INPUT">
						<xsl:attribute name="type">text</xsl:attribute>
						<xsl:attribute name="name">tax_text</xsl:attribute>
						<xsl:attribute name="value"><xsl:value-of select="tax_text"/></xsl:attribute>
						<xsl:attribute name="class">form-control input-sm</xsl:attribute>
						<xsl:attribute name="readonly">readonly</xsl:attribute>
					</xsl:element>
				</div>
				<br />
				<div id="block" style="margin-top:12px;display:none;">
					<div class="form-group form-group_bottom0">
						<label>开户银行：</label>
						<xsl:element name="INPUT">
							<xsl:attribute name="type">text</xsl:attribute>
							<xsl:attribute name="name">tax_bank_name</xsl:attribute>
							<xsl:attribute name="value"><xsl:value-of select="tax_bank_name"/></xsl:attribute>
							<xsl:attribute name="readonly">readonly</xsl:attribute>
							<xsl:attribute name="class">form-control input-sm</xsl:attribute>
						</xsl:element>
					</div>
					<div class="form-group form-group_bottom0">
						<label>银行账号：</label>
						<xsl:element name="INPUT">
							<xsl:attribute name="type">text</xsl:attribute>
							<xsl:attribute name="name">tax_bank_number</xsl:attribute>
							<xsl:attribute name="value"><xsl:value-of select="tax_bank_number"/></xsl:attribute>
							<xsl:attribute name="readonly">readonly</xsl:attribute>
							<xsl:attribute name="class">form-control input-sm</xsl:attribute>
						</xsl:element>
					</div>
					<div class="form-group form-group_bottom0">
						<label class="margin_left_2">税号：</label>
						<xsl:element name="INPUT">
							<xsl:attribute name="type">text</xsl:attribute>
							<xsl:attribute name="name">tax_number</xsl:attribute>
							<xsl:attribute name="value"><xsl:value-of select="tax_number"/></xsl:attribute>
							<xsl:attribute name="readonly">readonly</xsl:attribute>
							<xsl:attribute name="class">form-control input-sm</xsl:attribute>
						</xsl:element>
					</div>
				</div>
			</div>
		</div>
		<div>
		<h4>定制<span class="need" style="margin-left:20px;list-style: outside none none;font-weight: normal;cursor:pointer" >需要定制</span></h4>
		</div>
		<div class="commonMsg customizeMsg" style="display:none">
			<div class="form-group form-group_bottom0">
				<label>定制内容：</label>
				<xsl:element name="INPUT">
					<xsl:attribute name="type">text</xsl:attribute>
					<xsl:attribute name="name"> </xsl:attribute>
					<xsl:attribute name="readonly">readonly</xsl:attribute>
					<xsl:attribute name="class">form-control input-sm merger_two_row_4</xsl:attribute>
					<xsl:attribute name="value"><xsl:value-of select="body"/></xsl:attribute>
				</xsl:element>
			</div>
			<div class="form-group form-group_bottom0">
				<label>定制数量：</label>
				<xsl:element name="INPUT">
					<xsl:attribute name="type">text</xsl:attribute>
					<xsl:attribute name="name"> </xsl:attribute>
					<xsl:attribute name="class">form-control input-sm</xsl:attribute>
					<xsl:attribute name="readonly">readonly</xsl:attribute>
					<xsl:attribute name="value"><xsl:value-of select="c_total"/></xsl:attribute>
				</xsl:element>
			</div>
			<label> &#160;&#160;&#160;&#160;&#160;&#160;&#160;附件：</label>
			<div class="form-group form-group_bottom0">
				<input class="btn btn-default btn-sm" type="text" value="">

				</input>
			</div>
			<div class="form-group form-group_bottom0">
				<label>确认情况：</label>
				<select class="form-control input-sm" disabled="disabled" name="">
						<xsl:element name="OPTION">
							<xsl:attribute name="value">Y<xsl:value-of select="value" /></xsl:attribute>
							是
						</xsl:element>
						<xsl:element name="OPTION">
							<xsl:attribute name="value">N<xsl:value-of select="value" /></xsl:attribute>
							否
						</xsl:element>
				</select>
			</div>
		</div>
		<h4>财务</h4>
		<div class="commonMsg financeMsg">
			<div class="form-group form-group_bottom2">
				<label>应收金额：</label>
				<div class="input-group">
					<div class="input-group-addon">￥</div>
					<xsl:element name="INPUT">
						<xsl:attribute name="type">text</xsl:attribute>
						<xsl:attribute name="class">form-control input-sm theory</xsl:attribute>
						<xsl:attribute name="value"><xsl:value-of select="theory" /></xsl:attribute>
						<xsl:attribute name="readonly">readonly</xsl:attribute>
						<xsl:attribute name="style">width:110px;</xsl:attribute>
						<xsl:attribute name="name">theory_amount</xsl:attribute>
					</xsl:element>
				</div>
			</div>
			<div class="form-group form-group_bottom0">
				<label>实收金额：</label>
				<div class="input-group">
					<div class="input-group-addon">￥</div>
				   <xsl:element name="INPUT">
						<xsl:attribute name="type">text</xsl:attribute>
						<xsl:attribute name="class">form-control input-sm real</xsl:attribute>
						<xsl:attribute name="value"><xsl:value-of select="real" /></xsl:attribute>
						<xsl:attribute name="style">width: 110px;</xsl:attribute>
						<xsl:attribute name="name">real_amount</xsl:attribute>
						<xsl:attribute name="onkeyup">value=value.replace(/[^\d\.]/g,'')</xsl:attribute>
						<xsl:attribute name="onbeforepaste">clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d\.]/g,''))</xsl:attribute>
					</xsl:element>
					<!-- 增加前的金额 -->
					<input type="hidden" name="beforAmount" value=""/>
					<!-- 增加后的金额 -->
					<input type="hidden" name='afterAmount' value=''/>
				</div>
			</div>

			<!-- 提示框 -->
			<div id="confirm" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none;width:268px;margin:65px auto">
				<div class="modal-content">
					<div class="modal-header">
						<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
						<h4 class="modal-title" id="myModalLabel">提示</h4>
					</div>
					<div class="modal-body" style="margin-left:20px">实收金额不能少于 ￥<span class="number">1</span>
					</div>
					<div class="modal-footer"><button type="button" class="btn btn-default btn-sm ok" data-dismiss="modal">确定</button></div>
				</div>
			</div>

			<div class="form-group form-group_bottom0">
				<label>欠款尾款：</label>
				<div class="input-group">
					<div class="input-group-addon">￥</div>
					<xsl:element name="INPUT">
						<xsl:attribute name="type">text</xsl:attribute>
						<xsl:attribute name="class">form-control input-sm remain</xsl:attribute>
						<xsl:attribute name="value"><xsl:value-of select="arrears" /></xsl:attribute>
						<xsl:attribute name="readonly">readonly</xsl:attribute>
						<xsl:attribute name="style">width:110px;</xsl:attribute>
						<xsl:attribute name="name">remain_amount</xsl:attribute><!--字段暂时没有-->
					</xsl:element>
				</div>
			</div>
			<div class="form-group form-group_bottom0">
				<label class="public_font">财务入账：</label>
					<xsl:element name="INPUT">
						<xsl:attribute name="type">text</xsl:attribute>
						<xsl:attribute name="class">form-control input-sm pa</xsl:attribute>
						<xsl:attribute name="value"><xsl:value-of select="paystatus" /></xsl:attribute>
						<xsl:attribute name="readonly">readonly</xsl:attribute>
						<xsl:attribute name="style">width:110px;</xsl:attribute>
						<xsl:attribute name="name">pa</xsl:attribute>
					</xsl:element>
					<xsl:element name="INPUT">
						<xsl:attribute name="type">hidden</xsl:attribute>
						<xsl:attribute name="class">form-control input-sm payment_status</xsl:attribute>
						<xsl:attribute name="value"><xsl:value-of select="pay" /></xsl:attribute>
						<xsl:attribute name="style">width:110px;</xsl:attribute>
						<xsl:attribute name="name">payment_status</xsl:attribute>
					</xsl:element>
					<xsl:element name="INPUT">
						<xsl:attribute name="type">hidden</xsl:attribute>
						<xsl:attribute name="class">form-control input-sm</xsl:attribute>
						<xsl:attribute name="value"><xsl:value-of select="id" /></xsl:attribute>
						<xsl:attribute name="style">width:110px;</xsl:attribute>
						<xsl:attribute name="name">order_id</xsl:attribute>
					</xsl:element>

			</div>
		</div>
		<p>
			<input class="btn btn-default btn-sm btn_margin" name="submit" type="submit" value="提交" />
			<input class="btn btn-default btn-sm" type="reset" value="重置" />
		</p>
	</div>
</form>
</div>
</xsl:for-each>
</xsl:template>
</xsl:stylesheet>

