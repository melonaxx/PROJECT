<?xml version="1.0" encoding="utf-8" ?>
<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<!-- UTF8 编码 -->

<xsl:include href="/style/header.xsl" />

<xsl:template name="text">

<script type="text/javascript" src="/js_encode/crm_supplier_list.js"></script>
<br/>
<ul class="nav nav-tabs">
	<li class="tab-pane active"><a>供应商</a></li>
	<li class="tab-pane"><a href="/crm/crm_supplier_add.php">添加供应商</a></li>
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

<div class="mainBody">
	<form class="form-inline" method='get'>
		<div class="supplierMsg">
			<div class="table_operate_block">
				<div class="form-group float_right margin0">
					<div class="input-group">
						<xsl:element name="input">
							<xsl:attribute name="type">text</xsl:attribute>
							<xsl:attribute name="class">form-control input-sm input_search</xsl:attribute>
							<xsl:attribute name="name">name</xsl:attribute>
							<xsl:attribute name="value"><xsl:value-of select="/html/Body/find" /></xsl:attribute>
							<xsl:attribute name="placeholder">请输入供应商名称或产品名查询</xsl:attribute>;
						</xsl:element>
						<span class="input-group-btn">
							<button class="btn btn-default btn-sm" type="submit">搜索</button>
						</span>
					</div>
				</div>
				<input class="btn btn-default btn-sm btn_margin supplierAdd" type="button" value="添加" />
			</div>
			<table class="table table-bordered table-hover">
				<tr>
					<th class="table_th_number">序号</th>
					<th class="table_th_operate">操作</th>
					<th style="width:80px">供应商编码</th>
					<th style="width:165px;">供应商名称</th>
					<th style="width:100px">供应商类型</th>
					<th style="width:260px;">网站网址</th>
					<th style="width:80px">联系人</th>
					<th style="width:120px">手机</th>
					<th style="width:263px;">备注</th>
				</tr>
				<xsl:for-each select="/html/Body/supplierMsg/ul/li">
				<tr>
					<td class="center"></td>
					<td class="center">
						<xsl:element name="A">
							<xsl:attribute name="href">/crm/crm_supplier_update.php?id=<xsl:value-of select="id" /></xsl:attribute>
							<xsl:attribute name="class">table_a_operate</xsl:attribute>
							<xsl:text>查看</xsl:text>
						</xsl:element>
						<xsl:element name="A">
							<xsl:attribute name="href">/crm/crm_supplier_list.php?m=delete&amp;id=<xsl:value-of select="id"/></xsl:attribute>
							<xsl:attribute name="class">table_a_operate delete</xsl:attribute>
							<xsl:text>删除</xsl:text>
						</xsl:element>
					</td>
					<td><xsl:value-of select="number" /></td>
					<td><xsl:value-of select="name" /></td>
					<td><xsl:value-of select="type" /></td>
					<td><xsl:value-of select="website" /></td>
					<td><xsl:value-of select="contact_name" /></td>
					<td><xsl:value-of select="mobile" /></td>
					<td><xsl:value-of select="content" /></td>
				</tr>
				</xsl:for-each>
			</table>
			
		</div>
		
	</form>
	<xsl:call-template name="page"></xsl:call-template>
	
</div>
	
</xsl:template>

</xsl:stylesheet>
