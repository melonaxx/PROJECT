<?xml version="1.0" encoding="utf-8" ?>
<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<!-- UTF8 编码 -->

<xsl:include href="/style/header.xsl" />

<xsl:template name="text">

<script type="text/javascript" src="/js_encode/crm_supplier_update.js"></script>
<script type="text/javascript">
	var sheng = '<xsl:value-of select="/html/Body/supplierMsg/state_id" />';
	var shi = '<xsl:value-of select="/html/Body/supplierMsg/city_id" />';
	var xian = '<xsl:value-of select="/html/Body/supplierMsg/district_id" />';
</script>
<style>
</style>
<br/>
<ul class="nav nav-tabs">
	<li class="tab-pane"><a href="/crm/crm_supplier_list.php">供应商</a></li>
	<li class="tab-pane active"><a>供应商信息</a></li>
</ul>

<div class="mainBody">
	<form class="form-inline" method="post" action="/crm/crm_supplier_update.php">
		<xsl:element name="INPUT">
			<xsl:attribute name="type">hidden</xsl:attribute>
			<xsl:attribute name="name">id</xsl:attribute>
			<xsl:attribute name="value"><xsl:value-of select="/html/Body/supplierMsg/id" /></xsl:attribute>
		</xsl:element>
		<div class="supplierMsg">
			<div class="main">
				<div class="left" style="width:505px; margin:0 0 0 0; float:left;">
					<div class="form-group">
						<label>供应商编码：</label>
						<xsl:element name="INPUT">
							<xsl:attribute name="type">text</xsl:attribute>
							<xsl:attribute name="name">number</xsl:attribute>
							<xsl:attribute name="class">form-control input-sm</xsl:attribute>
							<xsl:attribute name="placeholder">默认自动生成</xsl:attribute>
							<xsl:attribute name="value"><xsl:value-of select="/html/Body/supplierMsg/number" /></xsl:attribute>
						</xsl:element>
					</div>
					<div class="form-group">
						<label>供应商名称：</label>
						<xsl:element name="INPUT">
							<xsl:attribute name="type">text</xsl:attribute>
							<xsl:attribute name="name">name</xsl:attribute>
							<xsl:attribute name="class">form-control input-sm</xsl:attribute>
							<xsl:attribute name="placeholder">必填</xsl:attribute>
							<xsl:attribute name="data-toggle">tooltip</xsl:attribute>
							<xsl:attribute name="data-placement">bottom</xsl:attribute>
							<xsl:attribute name="title">必填</xsl:attribute>
							<xsl:attribute name="value"><xsl:value-of select="/html/Body/supplierMsg/name" /></xsl:attribute>
						</xsl:element>
					</div>
					<div class="form-group">
						<label>供应商类型：</label>
						<select class="form-control input-sm" name="type">
							<xsl:for-each select="/html/Body/supplierMsg/type/ul/li">
								<xsl:element name="OPTION">
									<xsl:attribute name="value"><xsl:value-of select="value" /></xsl:attribute>
									<xsl:if test="value=/html/Body/supplierMsg/type/select_value"><xsl:attribute name="selected">selected</xsl:attribute></xsl:if>
									<xsl:value-of select="text" />
								</xsl:element>
							</xsl:for-each>
						</select>
					</div>
					<div class="form-group">
						<label>供应商级别：</label>
						<select class="form-control input-sm" name="level">
							<xsl:for-each select="/html/Body/supplierMsg/level/ul/li">
								<xsl:element name="OPTION">
									<xsl:attribute name="value"><xsl:value-of select="value" /></xsl:attribute>
									<xsl:if test="value=/html/Body/supplierMsg/level/select_value"><xsl:attribute name="selected">selected</xsl:attribute></xsl:if>
									<xsl:value-of select="text" />
								</xsl:element>
							</xsl:for-each>
						</select>
					</div>
					<div class="form-group">
						<label class="margin_left_3">税号：</label>
						<xsl:element name="INPUT">
							<xsl:attribute name="type">text</xsl:attribute>
							<xsl:attribute name="name">tax</xsl:attribute>
							<xsl:attribute name="class">form-control input-sm</xsl:attribute>
							<xsl:attribute name="value"><xsl:value-of select="/html/Body/supplierMsg/tax" /></xsl:attribute>
						</xsl:element>
					</div>
					<div class="form-group">
						<label class="margin_left_1">开户银行：</label>
						<xsl:element name="INPUT">
							<xsl:attribute name="type">text</xsl:attribute>
							<xsl:attribute name="name">bank_name</xsl:attribute>
							<xsl:attribute name="class">form-control input-sm</xsl:attribute>
							<xsl:attribute name="value"><xsl:value-of select="/html/Body/supplierMsg/bank_name" /></xsl:attribute>
						</xsl:element>
					</div>
					<div class="form-group">
						<label class="margin_left_1">银行账号：</label>
						<xsl:element name="INPUT">
							<xsl:attribute name="type">text</xsl:attribute>
							<xsl:attribute name="name">bank_number</xsl:attribute>
							<xsl:attribute name="class">form-control input-sm merger_two_row_5</xsl:attribute>
							<xsl:attribute name="value"><xsl:value-of select="/html/Body/supplierMsg/bank_number" /></xsl:attribute>
						</xsl:element>
					</div>
					<div class="form-group">
						<label class="margin_left_1">网站网址：</label>
						<xsl:element name="INPUT">
							<xsl:attribute name="type">text</xsl:attribute>
							<xsl:attribute name="name">website</xsl:attribute>
							<xsl:attribute name="class">form-control input-sm merger_two_row_5</xsl:attribute>
							<xsl:attribute name="value"><xsl:value-of select="/html/Body/supplierMsg/website" /></xsl:attribute>
						</xsl:element>
					</div>
				</div>
				<div class="right" style="float:left; width:680px;">
					<div class="form-group">
						<label>联系人：</label>
						<xsl:element name="INPUT">
							<xsl:attribute name="type">text</xsl:attribute>
							<xsl:attribute name="name">contact_name</xsl:attribute>
							<xsl:attribute name="class">form-control input-sm</xsl:attribute>
							<xsl:attribute name="value"><xsl:value-of select="/html/Body/supplierMsg/contact_name" /></xsl:attribute>
						</xsl:element>
					</div>
					<div class="form-group">
						<label class="margin_left_2">手机：</label>
						<xsl:element name="INPUT">
							<xsl:attribute name="type">text</xsl:attribute>
							<xsl:attribute name="name">mobile</xsl:attribute>
							<xsl:attribute name="class">form-control input-sm</xsl:attribute>
							<xsl:attribute name="value"><xsl:value-of select="/html/Body/supplierMsg/mobile" /></xsl:attribute>
						</xsl:element>
					</div>
					<div class="form-group">
						<label class="margin_left_2">固话：</label>
						<xsl:element name="INPUT">
							<xsl:attribute name="type">text</xsl:attribute>
							<xsl:attribute name="name">phone</xsl:attribute>
							<xsl:attribute name="class">form-control input-sm</xsl:attribute>
							<xsl:attribute name="value"><xsl:value-of select="/html/Body/supplierMsg/phone" /></xsl:attribute>
						</xsl:element>
					</div>
					<div class="form-group">
						<label class="margin_left_1">邮箱：</label>
						<xsl:element name="INPUT">
							<xsl:attribute name="type">text</xsl:attribute>
							<xsl:attribute name="name">email</xsl:attribute>
							<xsl:attribute name="class">form-control input-sm</xsl:attribute>
							<xsl:attribute name="value"><xsl:value-of select="/html/Body/supplierMsg/email" /></xsl:attribute>
						</xsl:element>
					</div>
					<div class="form-group">
						<label class="margin_left_2">传真：</label>
						<xsl:element name="INPUT">
							<xsl:attribute name="type">text</xsl:attribute>
							<xsl:attribute name="name">fax</xsl:attribute>
							<xsl:attribute name="class">form-control input-sm</xsl:attribute>
							<xsl:attribute name="value"><xsl:value-of select="/html/Body/supplierMsg/fax" /></xsl:attribute>
						</xsl:element>
					</div>
					<div class="form-group">
						<label class="margin_left_2">邮编：</label>
						<xsl:element name="INPUT">
							<xsl:attribute name="type">text</xsl:attribute>
							<xsl:attribute name="name">post_code</xsl:attribute>
							<xsl:attribute name="class">form-control input-sm</xsl:attribute>
							<xsl:attribute name="value"><xsl:value-of select="/html/Body/supplierMsg/post_code" /></xsl:attribute>
						</xsl:element>
					</div>
					<div class="form-group shengDiv">
						<!--
						<label style="margin:0 22px 0 0;">省份</label>
						<select class="form-control input-sm sheng" style="width:143px;">
							<option>张小北</option>
							<option>张小北</option>
							<option>张小北</option>
							<option>张小北</option>
						</select>
						-->
					</div>
					<div class="form-group shiDiv">
						<!--
						<label>市(区)</label>
						<select class="form-control input-sm shi" style="width:143px;">
							<option>张小北</option>
							<option>张小北</option>
							<option>张小北</option>
							<option>张小北</option>
						</select>
						-->
					</div>
					<div class="form-group xianDiv" style="margin:0 0 12px 0;">
						<!--
						<label>区(县)</label>
						<select class="form-control input-sm xian" style="width:144px;">
							<option>张小北</option>
							<option>张小北</option>
							<option>张小北</option>
							<option>张小北</option>
						</select>
						-->
					</div>
					<div class="form-group">
						<label class="margin_left_1">地址：</label>
						<xsl:element name="INPUT">
							<xsl:attribute name="type">text</xsl:attribute>
							<xsl:attribute name="name">address</xsl:attribute>
							<xsl:attribute name="class">form-control input-sm merger_three_row_8</xsl:attribute>
							<xsl:attribute name="value"><xsl:value-of select="/html/Body/supplierMsg/address" /></xsl:attribute>
						</xsl:element>
					</div>
					<div class="form-group">
						<label class="margin_left_1">备注：</label>
						<xsl:element name="INPUT">
							<xsl:attribute name="type">text</xsl:attribute>
							<xsl:attribute name="name">content</xsl:attribute>
							<xsl:attribute name="class">form-control input-sm merger_three_row_8</xsl:attribute>
							<xsl:attribute name="value"><xsl:value-of select="/html/Body/supplierMsg/content" /></xsl:attribute>
						</xsl:element>
					</div>
				</div>
			</div>
		</div>
		<div class="goodsMsg" style="clear:both;">
			<h4>商品信息</h4>
			<div class="table_operate_block">
				<input class="btn btn-default btn-sm btn_margin goodsAdd" type="button" value="添加" />
				<input class="btn btn-default btn-sm goodsDelete" type="button" value="删除" />
			</div>
			<table class="table table-bordered table-hover">
				<tr><th class="table_th_checkbox center"><input type="checkbox" name="select_all" /></th><th class="table_th_number">序号</th><th style="width:769px">商品名称</th><th width="303">商品价格</th></tr>
				<xsl:for-each select="/html/Body/goodsMsg/ul/li">
					<tr>
						<td class="center"><input type="checkbox" name="select_one" /></td>
						<td class="center">1</td>
						<td>
							<xsl:element name="INPUT">
								<xsl:attribute name="type">text</xsl:attribute>
								<xsl:attribute name="name">goods_name[]</xsl:attribute>
								<xsl:attribute name="class">form-control input-sm form_no_border</xsl:attribute>
								<xsl:attribute name="value"><xsl:value-of select="goods_name" /></xsl:attribute>
							</xsl:element>
						</td>
						<td>
							<div class="input-group">
							<div class="input-group-addon">￥</div>
							<xsl:element name="INPUT">
								<xsl:attribute name="type">text</xsl:attribute>
								<xsl:attribute name="name">goods_price[]</xsl:attribute>
								<xsl:attribute name="style">width:220px;</xsl:attribute>
								<xsl:attribute name="class">form-control input-sm form_no_border</xsl:attribute>
								<xsl:attribute name="value"><xsl:value-of select="goods_price" /></xsl:attribute>
							</xsl:element>
							</div>
						</td>
					</tr>
				</xsl:for-each>
			</table>
		</div>

		<p><input class="btn btn-default btn-sm btn_margin" name="send" type="submit" value="提交" /><input class="btn btn-default btn-sm" type="reset" value="重置" /></p>

	</form>
</div>

</xsl:template>

</xsl:stylesheet>
