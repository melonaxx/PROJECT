<?xml version="1.0" encoding="utf-8" ?>
<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<!-- UTF8 编码 -->

<xsl:include href="/style/header.xsl" />

<xsl:template name="text">

<script type="text/javascript" src="/js_encode/crm_business_customers.js"></script>

<br/>
<ul class="nav nav-tabs">
	<li class="tab-pane active"><a>客户列表</a></li>
	<li class="tab-pane"><a href="/crm/crm_add_customer.php">添加客户</a></li>
</ul>

<div class="modal fade" id="confirm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
				<h4 class="modal-title" id="myModalLabel">提示</h4>
			</div>
			<div class="modal-body">
	  			您确定要删除<span class="number">1</span>条数据吗？
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default btn-sm ok" data-dismiss="modal">确定</button>
				<button type="button" class="btn btn-default btn-sm cancel" data-dismiss="modal">取消</button>
			</div>
		</div>
	</div>
</div>
<style>
</style>

<div class="mainBody">
	<form class="form-inline" action="/crm/crm_business_customers.php" method=
		"get">
		<div class="customersMsg">
			<div class="table_operate_block">
				<input class="btn btn-default btn-sm btn_margin customersAdd" type="button" value="添加" />
				<!-- <input class="btn btn-default btn-sm customersDelete" type="button" value="删除" /> -->
				<div class="form-group" style="margin:0; float:right;">
					<div class="form-group form_small_block" >
						<label>客户类型：</label>
						<xsl:element name="select">
							<xsl:attribute name="name">crm_type</xsl:attribute>
							<xsl:attribute name="class">form-control input-sm</xsl:attribute>
							<xsl:element name="option">
								全部客户
							</xsl:element>
							<xsl:element name="option">
								<xsl:attribute name="value">1</xsl:attribute>
								<xsl:if test="/html/Body/find/crm_type = 1" >
									<xsl:attribute name="selected">selected</xsl:attribute>
								</xsl:if>
								个人客户
							</xsl:element>

							<xsl:element name="option">
								<xsl:attribute name="value">2</xsl:attribute>
								<xsl:if test="/html/Body/find/crm_type = 2" >
									<xsl:attribute name="selected">selected</xsl:attribute>
								</xsl:if>
								企业客户
							</xsl:element>
						</xsl:element>

					</div>
					<div class="input-group">
						<xsl:element name="INPUT">
							<xsl:attribute name="type">text</xsl:attribute>
							<xsl:attribute name="name">nick_name</xsl:attribute>
							<xsl:attribute name="class">form-control input-sm input_search</xsl:attribute>
							<xsl:attribute name="placeholder">输入客户名称或昵称查询</xsl:attribute>
							<xsl:attribute name="value"><xsl:value-of select="/html/Body/find/nick_name" /></xsl:attribute>
						</xsl:element>
						<input type="hidden" name="find" value='1'/>
						<span class="input-group-btn">
							<button class="btn btn-default btn-sm"  type="submit">搜索</button>
						</span>
					</div>
				</div>
			</div>
			<table width="1200" class="table table-bordered table-hover">
				<tr>
					<!-- <th class="table_th_checkbox center"><input type="checkbox" name="select_all" /></th> -->
					<th class="table_th_number">序号</th>
					<th class="table_th_operate">操作</th>
					<!-- <th>客户编码</th> -->
					<th width="100">客户名称</th>
					<th width="100">客户昵称</th>
					<th width="100">省份</th>
					<th width="100">市（区）</th>
					<th width="100">区（县）</th>
					<!-- <th>详细地址</th> -->
					<th width="120">手机</th>
					<th width="100">订单数</th>
					<th width="100">订单总额</th>
					<th width="248">备注</th>
				</tr>
				<xsl:for-each select="/html/Body/customersMsg/ul/li">
				<tr>
					<!-- <td class="center"><input type="checkbox" name="select_one" /></td> -->
					<td class="center"><xsl:value-of select="num"/></td>
					<!-- <td class="center"><xsl:value-of select="id" /></td> -->
					<td class="center">
						<xsl:element name="A">
							<xsl:attribute name="href">/crm/crm_update_customer.php?id=<xsl:value-of select="id" />&amp;crm_user_id=<xsl:value-of select="crm_user_id"/></xsl:attribute>
							<xsl:attribute name="class">table_a_operate</xsl:attribute>
							<xsl:text>查看</xsl:text>
						</xsl:element>
						<xsl:element name="A">
							<xsl:attribute name="href">/crm/crm_business_customers.php?m=delete&amp;id=<xsl:value-of select="id"/>&amp;crm_user_id=<xsl:value-of select="crm_user_id"/></xsl:attribute>
							<xsl:attribute name="class">table_a_operate customersDelete delete</xsl:attribute>
							<xsl:text>删除</xsl:text>
						</xsl:element>
					</td>

					<!-- <td class="center"><a href="javascript:;" class="table_a_operate">查看</a><a href="javascript:;">删除</a></td> -->
						<!-- <td><xsl:value-of select="id" /></td> -->
					<!-- <td><xsl:value-of select="crm_user_id" /></td> -->
					<td><xsl:value-of select="name" /></td>
					<td><xsl:value-of select="nick_name" /></td>
					<td><xsl:value-of select="state_id" /></td>
					<td><xsl:value-of select="city_id" /></td>
					<td><xsl:value-of select="district_id" /></td>
					<!-- <td></td> -->
					<td><xsl:value-of select="mobile" /></td>
					<td><xsl:value-of select="order_total" /></td>
					<td><xsl:value-of select="money_total" /></td>
					<td><xsl:value-of select="body" /></td>
				</tr>
				</xsl:for-each>
			</table>
		</div>
	</form>

	<xsl:call-template name="page"></xsl:call-template>
</div>

</xsl:template>

</xsl:stylesheet>
