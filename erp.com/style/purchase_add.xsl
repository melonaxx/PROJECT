<?xml version="1.0" encoding="utf-8" ?>
<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<!-- UTF8 编码 -->
<xsl:include href="/style/header.xsl" />
<xsl:template name="text">

<style>
	.form-group{
  position:relative;
}
.form-group a{
	position:absolute;
	cursor:pointer;
}
</style>
<script type="text/javascript" src="/js_encode/purchase_add.js"></script>
<div class="mainBody" >
	<form class="form-inline" method="post" action="/purchase/purchase_add.php">
		<div class="base">

			<div class="form-group">
				<label class="margin_left_1">供应商：</label>
				<select class="form-control input-sm" name="supplier_id">
					<!-- <xsl:attribute name="data-toggle">tooltip</xsl:attribute>
					<xsl:attribute name="data-placement">bottom</xsl:attribute>
					<xsl:attribute name="title">必选</xsl:attribute> -->
					<option value='' style='display:none;'>请选择供应商</option>
					<xsl:for-each select="/html/Body/purchase_add/ul/li">
						<xsl:element name="OPTION">
							<xsl:attribute name="value"><xsl:value-of select="value" /></xsl:attribute>
							<xsl:value-of select="text" />
						</xsl:element>
					</xsl:for-each>
				</select>
				<!-- <xsl:element name="A">
				<xsl:attribute name="target">_blank</xsl:attribute>
				<xsl:attribute name="href">/crm/crm_supplier_add.php</xsl:attribute>
				<xsl:attribute name="class">myMod</xsl:attribute>
				<img title="点击前往设置供应商" src="https://img.alicdn.com/imgextra/i2/85662775/TB2uxSfipXXXXazXXXXXXXXXXXX_!!85662775.png" width='16px;' height='16px;' />
				</xsl:element> -->
			</div>
			<div class="form-group">
				<label>收货仓库：</label>
				<select class="form-control input-sm" name="store_id">
					<!-- <xsl:attribute name="data-toggle">tooltip</xsl:attribute>
					<xsl:attribute name="data-placement">bottom</xsl:attribute>
					<xsl:attribute name="title">必选</xsl:attribute> -->
					<option value='' style='display:none;'>请选择收货仓库</option>
					<xsl:for-each select="/html/Body/store/ul/li">
						<xsl:element name="OPTION">
							<xsl:attribute name="value"><xsl:value-of select="value" /></xsl:attribute>
							<xsl:value-of select="text"/>
						</xsl:element>
					</xsl:for-each>
				</select>
			</div>
			<br/>
			<div class="form-group">
				<label>采购摘要：</label>
				<xsl:element name="TEXTAREA">
					<xsl:attribute name="class">form-control input-sm merger_two_row_4</xsl:attribute>
					<xsl:attribute name="rows">3</xsl:attribute>
					<xsl:attribute name="name">brief</xsl:attribute>
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
		</div>
		<div class="goodsMsg">
			<h4>商品信息</h4>
			<div class="table_operate_block">
				<input class="btn btn-default btn-sm btn_margin goodsAdd" type="button" value="添加" />
				<input class="btn btn-default btn-sm goodsDelete" type="button" value="删除" />
			</div>
			<table width="1180" class="table table-bordered table-hover" id="tab">
				<tr>
					<th class="" width="46px">序号</th>
					<th class="table_th_checkbox center" width="38px"><input type="checkbox" name="select_all" /></th>
					<th width="150px">搜索</th>
					<th width="266px">商品名称与规格</th>
					<th width="90px">单位</th>
					<th width="120px">单价</th>
					<th width="90px">数量</th>
					<th width="100px">总价</th>
					<th width="300px">备注</th>
				</tr>
				<tr>
					<td class="center">1</td>
					<td class="center"><input type="checkbox" name="select_one" /></td>
					<td><input type="text" class="form-control input-sm form_no_border find" /></td>
					<td style="width:226px;">
						<!-- <select class="form-control input-sm form_no_border guige" name="product_id[]" placeholder="必填" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="最少选择一个商品">
						</select> -->
						<select class="form-control input-sm form_no_border guige" name="product_id[]">
							<!-- <xsl:attribute name="placeholder">最少选择一个商品</xsl:attribute>
							<xsl:attribute name="data-toggle">tooltip</xsl:attribute>
							<xsl:attribute name="data-placement">bottom</xsl:attribute>
							<xsl:attribute name="title">最少选择一个商品</xsl:attribute> -->
							<option value='' style='display:none;'>请选择商品</option>
						</select>
			  		</td>
			  		<td>
			  			<xsl:element name="INPUT">
							<xsl:attribute name="type">hidden</xsl:attribute>
							<xsl:attribute name="name">parts_id[]</xsl:attribute>
							<xsl:attribute name="class">form-control input-sm form_no_border danwei</xsl:attribute>
						</xsl:element>
			  		</td>
					<td>
					   <div class="input-group">
							<div class="input-group-addon danjia1">￥</div>
							<xsl:element name="INPUT">
								<xsl:attribute name="type">text</xsl:attribute>
								<xsl:attribute name="name">price[]</xsl:attribute>
								<xsl:attribute name="class">form-control input-sm form_no_border danjia</xsl:attribute>
							</xsl:element>
					   </div>
					</td>
					<td width='90px'>
						<xsl:element name="INPUT">
							<xsl:attribute name="type">text</xsl:attribute>
							<xsl:attribute name="name">total[]</xsl:attribute>
							<xsl:attribute name="placeholder">必填</xsl:attribute>
							<xsl:attribute name="class">form-control input-sm form_no_border number</xsl:attribute>
							<xsl:attribute name="onkeyup">value=value.replace(/[^\d]/g,'')</xsl:attribute>
							<!-- <xsl:attribute name="data-placement">bottom</xsl:attribute>
							<xsl:attribute name="data-original-title">商品数量至少为1</xsl:attribute>
							<xsl:attribute name="data-toggle">tooltip</xsl:attribute> -->
						</xsl:element>
					</td>
					<td>
						<div class="input-group">
							<div class="input-group-addon xiaoji">￥</div>
					   </div>
					</td>
					<td>
						<xsl:element name="INPUT">
							<xsl:attribute name="type">text</xsl:attribute>
							<xsl:attribute name="name">content[]</xsl:attribute>
							<xsl:attribute name="class">form-control input-sm form_no_border</xsl:attribute>
						</xsl:element>
					</td>
				</tr>
				<tr>
					<td class="center">合计</td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
					<td class="purchase_sum">　<input type="hidden" name="sum" value=''/><span class="zongshu"></span></td>
					<td class="purchase_all">￥<input type="hidden" name="price_main" value=''/><span class="zongjia"></span></td>
					<td></td>
				</tr>
			</table>
		</div>
		<h4>合计</h4>
		<div class="commonMsg totalMsg">
			<p>采购数量总计：<span class="money_color"><span class="zongshu">0</span></span><span style="margin-left:20px;">采购总价：<span class="money_color"><span class="zongjia">0</span></span>元</span>
				<!-- <label style="margin-left:20px;">审核：</label>
				<select class="form-control input-sm" name="status" style="width:147px;"> --><!--字段暂时没有-->
					<!-- <option value="Y">审核通过</option>
					<option value="N">待审核</option>
				</select> -->
			</p>
		</div>
		<h4>运费</h4>
		<div class="commonMsg freightMsg">
			<div class="form-group form-group_bottom0">
				<label>付运费方：</label>
				<select class="form-control input-sm" name="freight_side">
					<option value="Supplier">供应商</option>
					<option value="Company">本公司</option>
				</select>
			</div>
			<div class="form-group form-group_bottom0">
				<label>运费金额：</label>
				<div class="input-group">
					<div class="input-group-addon">￥</div>
					<xsl:element name="INPUT">
						 <xsl:attribute name="type">text</xsl:attribute>
						 <xsl:attribute name="name">freight_amount</xsl:attribute>
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
					<xsl:attribute name="class">form-control input-sm</xsl:attribute>
				</xsl:element>
			</div>
			<div class="form-group form-group_bottom0">
				<label>运单号码：</label>
				<xsl:element name="INPUT">
					<xsl:attribute name="type">text</xsl:attribute>
					<xsl:attribute name="name">waybill_number</xsl:attribute>
					<xsl:attribute name="class">form-control input-sm</xsl:attribute>
				</xsl:element>
			</div>
		</div>
		<h4>财务</h4>
		<div class="commonMsg freightMsg">
			<div class="form-group form-group_bottom0">
				<label>付款方式：</label>
				<select class="form-control input-sm" name="pay_method">
					<option value="After">后付款</option>
					<option value="Deposit">订金加尾款</option>
					<option value="New">先付款</option>
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
