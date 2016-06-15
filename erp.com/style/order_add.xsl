<?xml version="1.0" encoding="utf-8" ?>
<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<!-- UTF8 编码 -->

<xsl:include href="/style/header.xsl" />


<xsl:template name="text">

<script type="text/javascript" src="/js_encode/order_add.js"></script>
<!-- 时间插件 -->
<script type="text/javascript" src="/js_encode/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript" src="/js_encode/bootstrap-datetimepicker.zh-CN.js"></script>
<link rel="stylesheet" href="/style/bootstrap-datetimepicker.min.css"></link>

<script type="text/javascript">
	var sheng = '<xsl:value-of select="/html/Body/sheng" />';
	var shi   = '<xsl:value-of select="/html/Body/shi" />';
	var xian  = '<xsl:value-of select="/html/Body/xian"/>';
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

.form-group{
  position:relative;
}
.form-group a{
	position:absolute;
	cursor:pointer;
}
<div class="progress">
  <div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width:90%">
    <span class="sr-only">90% Complete</span>
  </div>
</div>

</style>

<div class="mainBody" style="overflow:auto;" >
<form class="form-inline margin_top_add2" action="/order/order_add.php" method="post">
	<div class="orderMsg" >
		<h4 class="padding_clear">订单信息　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　　&#160;　&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;&#160;客户信息</h4>
		<div class="supplierMsg">
		   <div class="form-group">
				<label>下单时间：</label>
				<xsl:element name="INPUT">
					<xsl:attribute name="id">order_time</xsl:attribute>
					<xsl:attribute name="type">text</xsl:attribute>
					<xsl:attribute name="name">order_date</xsl:attribute>
					<xsl:attribute name="value"><xsl:value-of select="/html/Body/riqi"/></xsl:attribute>
					<xsl:attribute name="class">form-control input-sm </xsl:attribute>
				</xsl:element>
			</div>
			<div class=" form-group margin_left_block">
				<label>订单编号：</label>
				<xsl:element name="INPUT">
					<xsl:attribute name="type">text</xsl:attribute>
					<xsl:attribute name="name">bind_number</xsl:attribute>
					<xsl:attribute name="class">form-control input-sm order_num</xsl:attribute>
				</xsl:element>
			</div>
			<div class="form-group recog">
				<label>客户识别：</label>
				<xsl:element name="INPUT">
					<xsl:attribute name="type">text</xsl:attribute>
					<xsl:attribute name="name">recog</xsl:attribute>
					<xsl:attribute name="placeholder">在此搜索</xsl:attribute>
					<xsl:attribute name="class"><form-contro></form-contro>form-control input-sm</xsl:attribute>
				</xsl:element>
				<xsl:element name="A">
				<xsl:attribute name="target">_blank</xsl:attribute>
				<xsl:attribute name="href">/crm/crm_business_customers.php</xsl:attribute>
				<xsl:attribute name="class">myMod</xsl:attribute>
				<img title="点击前往查看客户" src="https://img.alicdn.com/imgextra/i2/85662775/TB2uxSfipXXXXazXXXXXXXXXXXX_!!85662775.png" width='16px;'  height='16px;' />
				</xsl:element>
			</div>
			<div class="form-group">
				<label>客户选择：</label>
				<select class="form-control input-sm crm" placeholder="213">
				</select>
			</div>

			<div class="form-group">
				<label>用户昵称：</label>
				<xsl:element name="INPUT">
					<xsl:attribute name="type">text</xsl:attribute>
					<xsl:attribute name="name">nick_name</xsl:attribute>
					<xsl:attribute name="class">form-control input-sm</xsl:attribute>
				</xsl:element>
			</div>
			<div class="form-group">
				<label>购买渠道：</label>
				<select class="form-control input-sm" name="bind_type">
					<xsl:element name="OPTION">
						<xsl:attribute name="value">System</xsl:attribute>系统</xsl:element>
				</select>
				<xsl:element name="A">
				<xsl:attribute name="target">_blank</xsl:attribute>
				<xsl:attribute name="href">/crm/crm_company_sales.php</xsl:attribute>
				<xsl:attribute name="class">myMod</xsl:attribute>
				<img title="点击前往设置渠道" src="https://img.alicdn.com/imgextra/i2/85662775/TB2uxSfipXXXXazXXXXXXXXXXXX_!!85662775.png" width='16px;' height='16px;' />
				</xsl:element>
			</div>
			<div class="form-group margin_left_block">
				<label>关联订单：</label>
				<xsl:element name="INPUT">
					<xsl:attribute name="type">text</xsl:attribute>
					<xsl:attribute name="name">related_order</xsl:attribute>
					<xsl:attribute name="class">form-control input-sm</xsl:attribute>
				</xsl:element>
			</div>
			<div class="form-group">
				<label class="margin_left_1">收件人：</label>
				<xsl:element name="INPUT">
					<xsl:attribute name="type">text</xsl:attribute>
					<xsl:attribute name="name">name</xsl:attribute>
					<xsl:attribute name="autocomplete">off</xsl:attribute>
					<xsl:attribute name="placeholder">必填</xsl:attribute>
					<!-- <xsl:attribute name="data-toggle">tooltip</xsl:attribute> -->
					<!-- <xsl:attribute name="data-placement">bottom</xsl:attribute> -->
					<!-- <xsl:attribute name="data-original-title">必填</xsl:attribute> -->
					<xsl:attribute name="class">form-control input-sm</xsl:attribute>
				</xsl:element>
			</div>
			<div class="form-group">
				<label>手机号码：</label>
				<xsl:element name="INPUT">
					<xsl:attribute name="type">text</xsl:attribute>
					<xsl:attribute name="name">mobile</xsl:attribute>
					<xsl:attribute name="autocomplete">off</xsl:attribute>
					<xsl:attribute name="placeholder">必填</xsl:attribute>
					<!-- <xsl:attribute name="data-toggle">tooltip</xsl:attribute> -->
					<!-- <xsl:attribute name="data-placement">bottom</xsl:attribute> -->
					<!-- <xsl:attribute name="data-original-title">手机和固话选填一个</xsl:attribute> -->
					<xsl:attribute name="class">form-control input-sm</xsl:attribute>
				</xsl:element>
			</div>
			<div class="form-group">
				<label>固定电话：</label>
				<xsl:element name="INPUT">
					<xsl:attribute name="type">text</xsl:attribute>
					<xsl:attribute name="name">phone</xsl:attribute>
					<xsl:attribute name="class">form-control input-sm</xsl:attribute>
				</xsl:element>
			</div>
			<div class="form-group">
				<label>店铺名称：</label>
				<select class="form-control input-sm" name="shop_title"><!--字段暂时没有-->
					<xsl:for-each select="/html/Body/shop/type/ul/li">
						<xsl:element name="OPTION">
							<xsl:attribute name="value"><xsl:value-of select="value" /></xsl:attribute>
							<xsl:value-of select="text" />
						</xsl:element>
					</xsl:for-each>
				</select>
			</div>
			<div class="form-group margin_left_block">
				<label>发货仓库：</label>
				<select class="form-control input-sm" name="store_id">
					<xsl:for-each select="/html/Body/fhck/type/ul/li">
						<xsl:element name="OPTION">
							<xsl:attribute name="value"><xsl:value-of select="value" /></xsl:attribute>
							<xsl:if test="status = 'Y'">
								<xsl:attribute name="checked">true</xsl:attribute>
							</xsl:if>
						<xsl:value-of select="text" />
						</xsl:element>
					</xsl:for-each>
				</select>
			</div>
			<div class="form-group">
				<label>公司名称：</label>
				<xsl:element name="INPUT">
					<xsl:attribute name="type">text</xsl:attribute>
					<xsl:attribute name="name">company_name</xsl:attribute>
					<xsl:attribute name="class">form-control input-sm merger_two_row_4</xsl:attribute>
				</xsl:element>
			</div>
			<div class="form-group">
				<label>邮政编码：</label>
				<xsl:element name="INPUT">
					<xsl:attribute name="type">text</xsl:attribute>
					<xsl:attribute name="name">post_code</xsl:attribute>
					<xsl:attribute name="class">form-control input-sm</xsl:attribute>
				</xsl:element>
			</div>
			<div class="form-group">
				<label>到账账户：</label>
				<select class="form-control input-sm" name="bank_id">
					<xsl:for-each select="/html/Body/supplierMsg/type/ul/li">
						<xsl:element name="OPTION">
						<xsl:attribute name="value"><xsl:value-of select="value" /></xsl:attribute>
						<xsl:if test="is_default = 'Y'">
						<xsl:attribute name="checked">true</xsl:attribute>
						</xsl:if>
						<xsl:value-of select="text" />
						</xsl:element>
					</xsl:for-each>
				</select>
			</div>
			<div class="form-group margin_left_block">
				<label>发票收据：</label>
				<select class="form-control input-sm shouju" name="need_invoice"><!--字段暂时没有-->
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
				<select class="form-control input-sm" name="express_id"><!--字段暂时没有-->
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
						<xsl:attribute name="name">post_fee</xsl:attribute><!--字段未找到-->
						<xsl:attribute name="class">form-control input-sm</xsl:attribute>
						<xsl:attribute name="style">width:110px;</xsl:attribute>
						<xsl:attribute name="onkeyup">value=value.replace(/[^\d\.]/g,'')</xsl:attribute>
						<xsl:attribute name="onbeforepaste">clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d\.]/g,''))</xsl:attribute>
					</xsl:element>
				</div>
			</div>
			<div class="form-group">
				<label>详细地址：</label>
				<xsl:element name="INPUT">
					<xsl:attribute name="type">text</xsl:attribute>
					<xsl:attribute name="name">address</xsl:attribute>
					<xsl:attribute name="class">form-control input-sm merger_three_row_8</xsl:attribute>
				</xsl:element>
			</div>
			<div class=" form-group margin_left_block" style="margin:0 20px 12px 0;">
				<label>快递单号：</label>
				<xsl:element name="INPUT">
					<xsl:attribute name="type">text</xsl:attribute>
					<xsl:attribute name="name">express_number</xsl:attribute>
					<xsl:attribute name="class">form-control input-sm</xsl:attribute>
				</xsl:element>
			</div>
			<div class="form-group">
				<label>　　重量：</label>
				<div class="input-group col-xs-7">
					<xsl:element name="INPUT">
						<xsl:attribute name="name">weight_number</xsl:attribute>
						<xsl:attribute name="class">form-control input-sm</xsl:attribute>
						<xsl:attribute name="style">width: 107px;</xsl:attribute>
					</xsl:element>
					<div class="input-group-addon">kg</div>
				</div>
			</div>
			<div class="form-group" style="margin:0 20px 7px 20px;">
				<label>客户留言：</label>
				<xsl:element name="INPUT">
					<xsl:attribute name="type">text</xsl:attribute>
					<xsl:attribute name="name">order_text</xsl:attribute><!--字段暂时未找到-->
					<xsl:attribute name="class">form-control input-sm merger_three_row_8</xsl:attribute>
				</xsl:element>
			</div>
			<div class="form-group margin_left_block">
				<label>订单备注：</label>
				<xsl:element name="INPUT">
					<xsl:attribute name="type">text</xsl:attribute>
					<xsl:attribute name="name">customer_text</xsl:attribute><!--字段未找到-->
					<xsl:attribute name="class">form-control input-sm merger_two_row_4</xsl:attribute>
				</xsl:element>
			</div>

		</div>
		<div class="goodsMsg">
			<h4>选择商品</h4>
			<p>
				<input class="btn btn-default btn-sm btn_margin goodsAdd" type="button" value="添加" />
				<input class="btn btn-default btn-sm btn_margin goodsDelete" type="button" value="删除" />
				<input class="btn btn-default btn-sm bar" type="button" value="条码添加" />
				<span class="tiao" style="display:none;margin-left:20px"><label>条形码：</label><input type="text" name="bar" placeholder="条形码"  class="form-control input-sm" /></span>
			</p>
			<table style="margin-top:10px;width:1200px;" class="table table-bordered table-hover">
				<tr>
					<th class="table_th_number">序号</th>
					<th class="center table_th_checkbox">
						<input type="checkbox" name="select_all" /></th>
					<th class="table_th_number">图片</th>
					<th style="width:150px;">搜索</th>
					<th style="width:315px;" width="315px">商品名称与规格</th>
					<th class="table_th_number">单位</th>
					<th style="width:85px;">单价</th>
					<th style="width:90px;">数量</th>
					<th style="width:100px;">优惠</th>
					<th style="width:100px;">应付</th>
					<th style="width:130px;">备注</th>
				</tr>
				<tr class="tr">
					<td class="center">1</td>
					<td class="center"><input type="checkbox" name="select_one" /></td>
					<td class="center">
						<xsl:element name="IMG">
							<xsl:attribute name="src"></xsl:attribute>
							<xsl:attribute name="width">20</xsl:attribute>
							<xsl:attribute name="height">20</xsl:attribute>
							<xsl:attribute name="class">images center</xsl:attribute>
						</xsl:element>
						<xsl:element name="IMG">
							<xsl:attribute name="src"></xsl:attribute>
							<xsl:attribute name="width">200</xsl:attribute>
							<xsl:attribute name="height">200</xsl:attribute>
							<xsl:attribute name="style">display:none;position:absolute</xsl:attribute>
							<xsl:attribute name="class">bigimg center</xsl:attribute>
						</xsl:element>
					</td>
					<td class="order_add_td_2">
						<xsl:element name="INPUT">
							<xsl:attribute name="type">text</xsl:attribute>
							<xsl:attribute name="name">search</xsl:attribute><!--商品搜索字段暂时没有-->
							<xsl:attribute name="class">form-control input-sm form_no_border search</xsl:attribute>
						</xsl:element>
					</td>
					<td class="order_add_td_2" style="width:200px;">
						<select class="form-control input-sm form_no_border find good_name" name="good_name[]" placeholder="必填">
							<xsl:attribute name="onbeforepaste">clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))</xsl:attribute>
							<option value="" style="display:none;">必填</option>
						</select>
					</td>
					<td></td>

					<td class="order_add_td_2" style="width:80px;">
						<div class="input-group">
						  <div class="input-group-addon danjia">￥</div>
						  	<xsl:element name="INPUT">
								<xsl:attribute name="type">hidden</xsl:attribute>
								<xsl:attribute name="name">price[]</xsl:attribute>
								<xsl:attribute name="class">price</xsl:attribute>
								<xsl:attribute name="value"></xsl:attribute>
							</xsl:element>
						</div>
					</td>
					<td class="order_add_td_3">
					   	<xsl:element name="INPUT">
							<xsl:attribute name="type">text</xsl:attribute>
							<xsl:attribute name="name">number[]</xsl:attribute>
							<xsl:attribute name="flag">pnum</xsl:attribute>
							<xsl:attribute name="style">width:70px</xsl:attribute>
							<xsl:attribute name="placeholder">必填</xsl:attribute>
							<!-- <xsl:attribute name="data-toggle">tooltip</xsl:attribute> -->
							<xsl:attribute name="onkeyup">value=value.replace(/[^\d]/g,'')</xsl:attribute>
							<xsl:attribute name="onbeforepaste">clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))</xsl:attribute>
							<!-- <xsl:attribute name="data-placement">top</xsl:attribute>
							<xsl:attribute name="data-original-title">商品数量至少为1,且不能超过100万</xsl:attribute> -->
							<xsl:attribute name="class">form-control input-sm num number_order</xsl:attribute>
						</xsl:element>
					</td>
					<td class="order_add_td_2" style="width:100px;">
						<div class="input-group">
						  <div class="input-group-addon">￥</div>
						  <xsl:element name="INPUT">
							 <xsl:attribute name="type">text</xsl:attribute>
							 <xsl:attribute name="name">discount[]</xsl:attribute><!--订单优惠字段暂时没有-->
							<xsl:attribute name="style">width:70px</xsl:attribute>
							<xsl:attribute name="onkeyup">value=value.replace(/[^\d.]/g,'')</xsl:attribute>
							<xsl:attribute name="onbeforepaste">clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d.]/g,''))</xsl:attribute>
							 <xsl:attribute name="class">form-control input-sm form_no_border yh</xsl:attribute>
						  </xsl:element>
						</div>
					</td>
					<td class="order_add_td_2" style="width:80px;">
						<div class="input-group">
							<div class="input-group-addon total_one">￥</div>
							<xsl:element name="INPUT">
								<xsl:attribute name="type">hidden</xsl:attribute>
								<xsl:attribute name="name">pay[]</xsl:attribute>
								<xsl:attribute name="class">pay</xsl:attribute>
								<xsl:attribute name="value">pay</xsl:attribute>
							</xsl:element>
						</div>
					</td>
					<td class="order_add_td_2">
						<xsl:element name="INPUT">
							<xsl:attribute name="type">text</xsl:attribute>
							<xsl:attribute name="name">content[]</xsl:attribute>
							<xsl:attribute name="class">form-control input-sm form_no_border</xsl:attribute>
						</xsl:element>
					</td>
				</tr>
				<tr class="total">
					<td class="center">合计</td>
					<td class="center"></td>
					<td colspan='5'></td>
					<td>
						<div class='num'></div>
						<xsl:element name="INPUT">
								<xsl:attribute name="type">hidden</xsl:attribute>
								<xsl:attribute name="name">total_num</xsl:attribute>
								<xsl:attribute name="class">total_num</xsl:attribute>
								<xsl:attribute name="display">none</xsl:attribute>
								<xsl:attribute name="value"></xsl:attribute>
						</xsl:element>
					</td>
					<td class="order_add_td_2" style="width:80px;">
						<div class="input-group">
						  <div class="input-group-addon yh">￥</div>
						  	<xsl:element name="INPUT">
								<xsl:attribute name="type">hidden</xsl:attribute>
								<xsl:attribute name="name">total_yh</xsl:attribute>
								<xsl:attribute name="class">total_yh</xsl:attribute>
								<xsl:attribute name="value"></xsl:attribute>
							</xsl:element>
						</div>
					</td>
					<td class="order_add_td_2" style="width:50px;">
						<div class="input-group">
						  <div class="input-group-addon zong">￥</div>
						  <xsl:element name="INPUT">
								<xsl:attribute name="type">hidden</xsl:attribute>
								<xsl:attribute name="name">total_pay</xsl:attribute>
								<xsl:attribute name="class">total_pay</xsl:attribute>
								<xsl:attribute name="value"></xsl:attribute>
							</xsl:element>
						</div>
					</td>
					<td></td>
				</tr>
			</table>
		</div>
		<h4>合计</h4>
		<div class="commonMsg totalMsg">
			<p>商品总金额<span class="money_color total">0</span>元 - 商品总优惠<span class="money_color yh">0</span>元 - 订单总优惠
			<xsl:element name="INPUT">
				<xsl:attribute name="type">text</xsl:attribute>
				<xsl:attribute name="class">form-control input-sm</xsl:attribute>
				<xsl:attribute name='value'><xsl:value-of select="total_favore"/></xsl:attribute>
				<xsl:attribute name="name">total_favore</xsl:attribute>
				<xsl:attribute name="onkeyup">value=value.replace(/[^\d\.]/g,'')</xsl:attribute>
				<xsl:attribute name="onbeforepaste">clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d\.]/g,''))</xsl:attribute>
				<xsl:attribute name="style">width:69px;margin:0 4px;</xsl:attribute>
			</xsl:element>
			元 + 运费
			<xsl:element name="INPUT">
				<xsl:attribute name="type">text</xsl:attribute>
				<xsl:attribute name="class">form-control input-sm</xsl:attribute>
				<xsl:attribute name='value'><xsl:value-of select="freight_buyer"/></xsl:attribute>
				<xsl:attribute name="name">freight_buyer</xsl:attribute>
				<xsl:attribute name="onkeyup">value=value.replace(/[^\d\.]/g,'')</xsl:attribute>
				<xsl:attribute name="onbeforepaste">clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d\.]/g,''))</xsl:attribute>
				<xsl:attribute name="style">width:69px;margin:0 4px;</xsl:attribute>
			</xsl:element>
			元= 实付<span class="money_color end">0</span><span  style="margin-right:20px">元</span>　<label>审核：</label>
				<select class="form-control input-sm" name="is_audit" style="width:147px;">
					<option value="Y">已审核</option>
					<option value="N">待审核</option>
				</select>
			</p>
		</div>
		<div id='fapiao' style="display:none">
			<h4>发票</h4>
			<div class="commonMsg invoiceMsg">
				<div class="form-group form-group_bottom0">
					<label>发票类型：</label>
					<select class="none form-control input-sm" name="tax_type">
				        <xsl:for-each select="/html/Body/invoiceMsg/invoice_type/ul/li">
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
						<xsl:attribute name="name">tax_title</xsl:attribute><!--字段暂时没有-->
						<xsl:attribute name="class">form-control input-sm</xsl:attribute>
					</xsl:element>
				</div>
				<div class="form-group form-group_bottom0">
					<label>发票明细：</label>
					<xsl:element name="INPUT">
						<xsl:attribute name="type">text</xsl:attribute>
						<xsl:attribute name="name">tax_text</xsl:attribute><!--字段暂时没有-->
						<xsl:attribute name="class">form-control input-sm</xsl:attribute>
					</xsl:element>
				</div>
				<br />
				<div id="block" style="margin-top:12px;display:none;">
					<div class="form-group form-group_bottom0">
						<label>开户银行：</label>
						<xsl:element name="INPUT">
							<xsl:attribute name="type">text</xsl:attribute>
							<xsl:attribute name="name">tax_bank_name</xsl:attribute>
							<xsl:attribute name="class">form-control input-sm</xsl:attribute>
						</xsl:element>
					</div>
					<div class="form-group form-group_bottom0">
						<label>银行账号：</label>
						<xsl:element name="INPUT">
							<xsl:attribute name="type">text</xsl:attribute>
							<xsl:attribute name="name">tax_bank_number</xsl:attribute>
							<xsl:attribute name="class">form-control input-sm</xsl:attribute>
						</xsl:element>
					</div>
					<div class="form-group form-group_bottom0">
						<label class="margin_left_2">税号：</label>
						<xsl:element name="INPUT">
							<xsl:attribute name="type">text</xsl:attribute>
							<xsl:attribute name="name">tax_number</xsl:attribute><!--字段暂时没有-->
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
				<input type="hidden" name="need_dz" value="N"/>
				<xsl:element name="INPUT">
					<xsl:attribute name="type">text</xsl:attribute>
					<xsl:attribute name="name">body</xsl:attribute><!--字段暂时没有-->
					<xsl:attribute name="class">form-control input-sm merger_two_row_4</xsl:attribute>
				</xsl:element>
			</div>
			<div class="form-group form-group_bottom0">
				<label>定制数量：</label>
				<xsl:element name="INPUT">
					<xsl:attribute name="type">text</xsl:attribute>
					<xsl:attribute name="onkeyup">value=value.replace(/[^\d\.]/g,'')</xsl:attribute>
					<xsl:attribute name="onbeforepaste">clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d\.]/g,''))</xsl:attribute>
					<xsl:attribute name="name">total</xsl:attribute>
					<xsl:attribute name="class">form-control input-sm</xsl:attribute>
				</xsl:element>
			</div>
			<label> &#160;&#160;&#160;&#160;&#160;&#160;&#160;附件：</label>
			<div class="form-group form-group_bottom0">
				<input class="btn btn-default btn-sm" type="text" value="">

				</input>
			</div>
			<div class="form-group form-group_bottom0">
				<label>确认情况：</label>
				<select class="form-control input-sm" name="status"><!--字段暂时没有-->
						<xsl:element name="OPTION">
							<xsl:attribute name="value">N</xsl:attribute>
							<xsl:text>待确认</xsl:text>
						</xsl:element>
						<xsl:element name="OPTION">
							<xsl:attribute name="value">Y</xsl:attribute>
							<xsl:text>已确认</xsl:text>
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
						<xsl:attribute name="readonly">readonly</xsl:attribute>
						<xsl:attribute name="onkeyup">value=value.replace(/[^\d]/g,'')</xsl:attribute>
						<xsl:attribute name="onbeforepaste">value=clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))</xsl:attribute>
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
						<xsl:attribute name="style">width: 110px;</xsl:attribute>
						<xsl:attribute name="name">real_amount</xsl:attribute>
						<xsl:attribute name="onkeyup">value=value.replace(/[^\d]/g,'')</xsl:attribute>
						<xsl:attribute name="onbeforepaste">clipboardData.setData('text',clipboardData.getData('text').replace(/[^\d]/g,''))</xsl:attribute>
					</xsl:element>
				</div>
			</div>
			<div class="form-group form-group_bottom0">
				<label>欠款尾款：</label>
				<div class="input-group">
					<div class="input-group-addon">￥</div>
					<xsl:element name="INPUT">
						<xsl:attribute name="type">text</xsl:attribute>
						<xsl:attribute name="class">form-control input-sm remain</xsl:attribute>
						<xsl:attribute name="readonly"></xsl:attribute><!--字段暂时没有-->
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
						<xsl:attribute name="value">未付款</xsl:attribute>
						<xsl:attribute name="readonly">readonly</xsl:attribute>
						<xsl:attribute name="style">width:110px;</xsl:attribute>
						<xsl:attribute name="name">pa</xsl:attribute>
					</xsl:element>
					<xsl:element name="INPUT">
						<xsl:attribute name="type">hidden</xsl:attribute>
						<xsl:attribute name="class">form-control input-sm payment_status</xsl:attribute>
						<xsl:attribute name="value">N</xsl:attribute>
						<xsl:attribute name="style">width:110px;</xsl:attribute>
						<xsl:attribute name="name">payment_status</xsl:attribute>
					</xsl:element>
			</div>
		</div>

		<p>
			<input class="btn btn-default btn-sm btn_margin" id="add_ok" name="submit" type="submit" value="提交" />
			<input class="btn btn-default btn-sm" type="reset" value="重置" />
		</p>
	</div>
</form>
</div>

</xsl:template>
</xsl:stylesheet>
