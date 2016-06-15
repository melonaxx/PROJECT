<?xml version="1.0" encoding="utf-8" ?>
<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<!-- UTF8 编码 -->

<xsl:include href="/style/header.xsl" />

<xsl:template name="text">

<script type="text/javascript" src="/js_encode/crm_add_customer.js"></script>
<script type="text/javascript">
	var sheng = '<xsl:value-of select="/html/Body/sheng" />';
	var shi = '<xsl:value-of select="/html/Body/shi" />';
	var xian = '<xsl:value-of select="/html/Body/xian" />';
</script>
<style>

</style>
<br/>
<ul class="nav nav-tabs">
	<li class="tab-pane"><a href="/crm/crm_business_customers.php">客户列表</a></li>
	<li class="tab-pane active"><a>添加客户</a></li>
</ul>

<div class="mainBody">
	<form class="form-inline" method="post" action="crm_add_customer.php">
		<div class="customerMsg">
			<h4 class="padding_clear">客户信息</h4>

			<div class="main">
				<div class="left" style="width:800px;">
					<div class="form-group" >
						<label>客户类型：</label>
						<xsl:element name="select">
							<xsl:attribute name="name">crm_type</xsl:attribute>
							<xsl:attribute name="class">form-control input-sm</xsl:attribute>
							<xsl:attribute name="name"></xsl:attribute>

							<xsl:element name="option">
								<xsl:attribute name="value">1</xsl:attribute>
								个人客户
							</xsl:element>
							<xsl:element name="option">
								<xsl:attribute name="value">2</xsl:attribute>
								企业客户
							</xsl:element>
						</xsl:element>
					</div>

					<div class="form-group">
						<label>客户名称：</label>
						<xsl:element name="INPUT">
							<xsl:attribute name="type">text</xsl:attribute>
							<xsl:attribute name="name">name</xsl:attribute>
							<xsl:attribute name="class">form-control input-sm</xsl:attribute>
							<xsl:attribute name="placeholder">必填</xsl:attribute>
							<xsl:attribute name="data-toggle">tooltip</xsl:attribute>
							<xsl:attribute name="data-placement">bottom</xsl:attribute>
							<xsl:attribute name="title">必填</xsl:attribute>
						</xsl:element>
					</div>
					<div class="form-group">
						<label>客户昵称：</label>
						<xsl:element name="INPUT">
							<xsl:attribute name="type">text</xsl:attribute>
							<xsl:attribute name="name">nick_name</xsl:attribute>
							<xsl:attribute name="class">form-control input-sm</xsl:attribute>
						</xsl:element>
					</div>
					<br/>
					<div class="form-group">
						<label>会员分组：</label>
						<select class="form-control input-sm">
							<option>无</option>
						</select>
					</div>
					<div class="form-group">
						<label>手机号码：</label>
						<xsl:element name="INPUT">
							<xsl:attribute name="type">text</xsl:attribute>
							<xsl:attribute name="name">mobile</xsl:attribute>
							<xsl:attribute name="class">form-control input-sm</xsl:attribute>
						</xsl:element>
					</div>
					<div class="form-group">
						<label>固定电话：</label>
						<xsl:element name="INPUT">
							<xsl:attribute name="type">text</xsl:attribute>
							<xsl:attribute name="name">telphone</xsl:attribute>
							<xsl:attribute name="class">form-control input-sm</xsl:attribute>
						</xsl:element>
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
						<label class="margin_left_2">发票：</label>
						<select class="form-control input-sm type" name="type">
							<option value="1">无</option>
							<option value="0">有</option>
						</select>
					</div>
					<div class="form-group">
						<label class="margin_left_2">邮箱：</label>
						<xsl:element name="INPUT">
							<xsl:attribute name="type">text</xsl:attribute>
							<xsl:attribute name="name">email</xsl:attribute>
							<xsl:attribute name="class">form-control input-sm</xsl:attribute>
						</xsl:element>
					</div>
					<div class="form-group">
						<label style="margin:0 0 0 4px;">QQ号码：</label>
						<xsl:element name="INPUT">
							<xsl:attribute name="type">text</xsl:attribute>
							<xsl:attribute name="name">qq</xsl:attribute>
							<xsl:attribute name="class">form-control input-sm</xsl:attribute>
						</xsl:element>
					</div>
					<div class="form-group">
						<label class="margin_left_2">邮编：</label>
						<xsl:element name="INPUT">
							<xsl:attribute name="type">text</xsl:attribute>
							<xsl:attribute name="name">post_code</xsl:attribute>
							<xsl:attribute name="class">form-control input-sm</xsl:attribute>
						</xsl:element>
					</div>
					<div class="form-group shengDiv">

					</div>
					<div class="form-group shiDiv">

					</div>
					<div class="form-group xianDiv">

					</div>
					<div class="form-group">
						<label>详细地址：</label>
						<xsl:element name="INPUT">
							<xsl:attribute name="type">text</xsl:attribute>
							<xsl:attribute name="name">address</xsl:attribute>
							<xsl:attribute name="class">form-control input-sm merger_three_row_8</xsl:attribute>
						</xsl:element>
					</div>
					<div class="form-group">
						<label class="margin_left_2">备注：</label>
						<xsl:element name="INPUT">
							<xsl:attribute name="type">text</xsl:attribute>
							<xsl:attribute name="name">body</xsl:attribute>
							<xsl:attribute name="class">form-control input-sm merger_three_row_8</xsl:attribute>
						</xsl:element>
					</div>
				</div>
			</div>
		</div>
		<div class="ticketMsg clear_both" style="display:none">
			<h4>开票信息</h4>
			<table class="table table-bordered table-hover">
				<tr>
					<th>开票单位</th>
					<th>税号</th>
					<th>纳税人地址</th>
					<th>电话</th>
					<th>开户行名称</th>
					<th>银行账号</th>
				</tr>

				<tr>
					<td>
						<xsl:element name="INPUT">
							<xsl:attribute name="type">text</xsl:attribute>
							<xsl:attribute name="name">tax_title</xsl:attribute>
							<xsl:attribute name="class">form-control input-sm form_no_border</xsl:attribute>
						</xsl:element>
					</td>
					<td width="150px">
						<xsl:element name="INPUT">
							<xsl:attribute name="type">text</xsl:attribute>
							<xsl:attribute name="name">tax_number</xsl:attribute>
							<xsl:attribute name="class">form-control input-sm form_no_border</xsl:attribute>
						</xsl:element>
					</td>
					<td width="330px">
						<xsl:element name="INPUT">
							<xsl:attribute name="type">text</xsl:attribute>
							<xsl:attribute name="name">tax_address</xsl:attribute>
							<xsl:attribute name="class">form-control input-sm form_no_border</xsl:attribute>
						</xsl:element>
					</td>
					<td width="120px">
						<xsl:element name="INPUT">
							<xsl:attribute name="type">text</xsl:attribute>
							<xsl:attribute name="name">tax_telphone</xsl:attribute>
							<xsl:attribute name="class">form-control input-sm form_no_border</xsl:attribute>
						</xsl:element>
					</td>
					<td>
						<xsl:element name="INPUT">
							<xsl:attribute name="type">text</xsl:attribute>
							<xsl:attribute name="name">tax_bank_name</xsl:attribute>
							<xsl:attribute name="class">form-control input-sm form_no_border</xsl:attribute>
						</xsl:element>
					</td>
					<td>
						<xsl:element name="INPUT">
							<xsl:attribute name="type">text</xsl:attribute>
							<xsl:attribute name="name">tax_bank_number</xsl:attribute>
							<xsl:attribute name="class">form-control input-sm form_no_border</xsl:attribute>
						</xsl:element>
					</td>
				</tr>

			</table>
		</div>

		<p><input class="btn btn-default btn-sm btn_margin" type="submit" name='send' value="提交" /><input class="btn btn-default btn-sm" type="reset" value="重置" /></p>
	</form>
</div>

</xsl:template>

</xsl:stylesheet>
