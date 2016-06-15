<?xml version="1.0" encoding="utf-8" ?>
<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<!-- UTF8 编码 -->

<xsl:include href="/style/header.xsl" />

<xsl:template name="text">
	<script type="text/javascript" src="/js_encode/setting_express_list.js"></script>
	<div class="modal fade" id="confirm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		<div class="modal-dialog modal-sm" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
					<h4 class="modal-title" id="myModalLabel">提示</h4>
				</div>
				<div class="modal-body">

				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-default btn-sm ok" data-dismiss="modal">确定</button>
					<button type="button" class="btn btn-default btn-sm cancel" data-dismiss="modal">取消</button>
				</div>
			</div>
		</div>
	</div>
	<div class="mainBody">
		<div class="headMsg table_operate_block">
			<form action="/setting/setting_express_list.php" method="post" class="form-inline">
				<button class="btn btn-default btn-sm btn_margin" onclick="MessageBox('/setting/setting_add_express.php', '新增快递公司',722,330)" type="button">新增</button>
				<div class="form-group float_right margin0">
					<div class="input-group">
						<xsl:element name="INPUT">
							<xsl:attribute name="type">text</xsl:attribute>
							<xsl:attribute name="style">width:300px</xsl:attribute>
							<xsl:attribute name="name">find</xsl:attribute>
							<xsl:attribute name="class">form-control input-sm</xsl:attribute>
							<xsl:attribute name="placeholder">输入文字搜索</xsl:attribute>
							<xsl:attribute name="value"><xsl:value-of select="/html/Body/find"/></xsl:attribute>
						</xsl:element>
						<span class="input-group-btn">
							<button class="btn btn-default btn-sm" type="submit">搜索</button>
						</span>
					</div>
				</div>
			</form>
		</div>
		<div>
			<table width="" class="table table-bordered table-hover">
				<tr>
					<th class="table_th_number">序号</th>
					<th style="width:160px;">操作</th>
					<th width="50">状态</th>
					<th width="80">快递名称</th>
					<th width="80">快递模板</th>
					<th width="60">联系人</th>
					<th width="100">联系电话</th>
					<th width="60">省份</th>
					<th width="73">市（区）</th>
					<th width="73">区（县）</th>
					<th width="218">详细地址</th>
					<th width="200">备注</th>
				</tr>
				<xsl:for-each select="/html/Body/expresslist/ul/li">
					<tr>
						<td class="center"><xsl:value-of select="num"/></td>
						<td>
							<xsl:element name="A">
								<xsl:attribute name="href">javascript:;</xsl:attribute>
								<xsl:attribute name="onclick">MessageBox('/setting/setting_edit_express.php?id=<xsl:value-of select="id" />','修改快递公司',722,325)</xsl:attribute>
								<xsl:attribute name="class">table_a_operate</xsl:attribute>
								<xsl:text>修改</xsl:text>
							</xsl:element>
							<xsl:element name="A">
								<xsl:attribute name="href">/setting/setting_express_list.php?m=delete&amp;id=<xsl:value-of select="id"/></xsl:attribute>
								<xsl:attribute name="class">table_a_operate delete</xsl:attribute>
								<xsl:text>删除</xsl:text>
							</xsl:element>
							<xsl:element name="A">
								<xsl:attribute name="href">setting_express_postfee.php?express_id=<xsl:value-of select="express_id" /></xsl:attribute>
								<!-- <xsl:attribute name="onclick">MessageBox('/setting/setting_express_postfee.php?id=<xsl:value-of select="id" />','运费设置',900,325)</xsl:attribute> -->
								<xsl:attribute name="class">table_a_operate</xsl:attribute>
								<xsl:text>运费设置</xsl:text>
							</xsl:element>
						</td>
						<td>
							<xsl:element name="A">
								<xsl:attribute name="href">javascript:;</xsl:attribute>
								<xsl:attribute name="class">stop</xsl:attribute>
								<xsl:text>停用</xsl:text>
							</xsl:element>
						</td>
						<td><xsl:value-of select="name"/></td>
						<!-- <td><xsl:value-of select="template_name"/></td> -->
						<xsl:element name="td">
							<xsl:attribute name="id"><xsl:value-of select="template_id"/></xsl:attribute>
							<xsl:value-of select="template_name"/>
						</xsl:element>
						<td><xsl:value-of select="contact_name"/></td>
						<td><xsl:value-of select="telphone"/></td>
						<td><xsl:value-of select="state_id"/></td>
						<td><xsl:value-of select="city_id"/></td>
						<td><xsl:value-of select="district_id"/></td>
						<td><xsl:value-of select="address"/></td>
						<td><xsl:value-of select="body"/></td>
					</tr>
				</xsl:for-each>
			</table>
			<xsl:call-template name="page"></xsl:call-template>
		</div>
	</div>

</xsl:template>

</xsl:stylesheet>