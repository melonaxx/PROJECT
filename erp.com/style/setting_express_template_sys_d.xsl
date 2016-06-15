<?xml version="1.0" encoding="utf-8" ?>
<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<!-- UTF8 编码 -->

<xsl:include href="/style/header.xsl" />

<xsl:template name="text">
<!-- 提示框 -->
<div id="confirm" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none;width:268px;margin:65px auto">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
			<h4 class="modal-title" id="myModalLabel">提示</h4>
		</div>
		<div class="modal-body" style="margin-left:20px">
			<label>模板名称：</label>
			<input class="form-control input-sm " id='template_name' placeholder="请输入模板名称" type="text"/>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default btn-sm ok">确定</button>
			<button type="button" class="btn btn-default btn-sm cancel" data-dismiss="modal">取消</button>
		</div>
	</div>	
</div>
	
<div class="mainBody">
	<div class="headMsg table_operate_block">
		<form action="/setting/setting_express_template_sys_d.php" method="post" class="form-inline">
			<div class="form-group float_right margin0">
				<div class="input-group">
					<xsl:element name="INPUT">
						<xsl:attribute name="type">text</xsl:attribute>
						<xsl:attribute name="name">find</xsl:attribute>
						<xsl:attribute name="class">form-control input-sm input_search</xsl:attribute>
						<xsl:attribute name="placeholder">输入模板名称或快递公司名查询</xsl:attribute>
						<xsl:attribute name="value"><xsl:value-of select="/html/Body/find" /></xsl:attribute>
					</xsl:element>
					<span class="input-group-btn">
						<button class="btn btn-default btn-sm">搜索</button>
					</span>
				</div>
			</div>
		</form>
		<a href="/setting/setting_deliver_template.php"><input class="btn btn-default btn-sm btn_margin" type="button" value="我的模板" /></a>

	</div>
	<table style="" class="table tab_select table-bordered table-hover">
		<tr>
			<th class="table_th_number" rowspan="2">序号</th>
			<th style="width:200px;" class="table_th_number" rowspan="2">操作</th>
			<th style="width:199px;text-align:center" rowspan="2">模板名称</th>
			<th style="width:388px;text-align:center" rowspan="2">模板图片</th>
			<th style="width:377px;text-align:center" colspan="6">纸张信息（单位：毫米）</th>
		</tr>
		<tr>
			<th>向下偏</th>
			<th>向右偏</th>
			<th>纸张宽</th>
			<th>纸张高</th>
			<th>边框</th>
			<th>打印方向</th>
		</tr>
		
		<xsl:for-each select="/html/Body/expressInfo/item">
		<tr>
			<td class="center"><xsl:value-of select="position()"/></td>
			
			<td class="center">
				<!-- <xsl:element name="A">
					<xsl:attribute name="class">table_a_operate delete</xsl:attribute>
					<xsl:attribute name="href">/setting/setting_express_template_sys_d.php?id=<xsl:value-of select='id' />
					</xsl:attribute>
					<xsl:text>添加到我的模板</xsl:text>
					<xsl:attribute name="class">table_a_operate delete</xsl:attribute>
					<xsl:attribute name="href">/setting/setting_express_template_sys_d.php?id=<xsl:value-of select='id' /></xsl:attribute>
					<xsl:attribute name="onclick">MessageBox('/setting/setting_express_template_sys_d.php?id=<xsl:value-of select="@id"/>', '提示', 300, 210); return false</xsl:attribute>
					编辑
				</xsl:element> -->
				<form action="/setting/setting_express_template_sys_d.php?action=add" method="get" >
					<div class="input-group">
						<xsl:element name="INPUT">
							<xsl:attribute name="type">hidden</xsl:attribute>
							<xsl:attribute name="name">id</xsl:attribute>
							<xsl:attribute name="class">table_a_operate delete</xsl:attribute>
							<xsl:attribute name="value"><xsl:value-of select="id" /></xsl:attribute>
						</xsl:element>
						<xsl:element name="INPUT">
							<xsl:attribute name="type">hidden</xsl:attribute>
							<xsl:attribute name="name">action</xsl:attribute>
							<xsl:attribute name="value">add</xsl:attribute>
						</xsl:element>
						<span class="input-group-btn">
							<button class="btn btn-sm" style="border:0 solid red;background-color:#fff;">添加到我的模板</button>
						</span>
					</div>
				</form>
			</td>
			<td><xsl:value-of select="name" /></td>
			<td>
				<xsl:element name="img">
					<xsl:attribute name="src"><xsl:value-of select="image" /></xsl:attribute>
					<xsl:attribute name="width">300px</xsl:attribute>
				</xsl:element>
			</td>
			<td><xsl:value-of select="paper_top" /></td>
			<td><xsl:value-of select="paper_left" /></td>
			<td><xsl:value-of select="paper_width" /></td>
			<td><xsl:value-of select="paper_height" /></td>
			<td><xsl:value-of select="border" /></td>
			<td><xsl:value-of select="print_orient" /></td>
		</tr>
		</xsl:for-each>
	</table>
	<xsl:if test="/html/Body/expressInfo/@total = '0'">
		<div class="imgs" style="margin:0 auto; width:100%;text-align:center">
			<div class="img">
				<img src="/images/empty.jpg"  alt=""/>
				<span>没有找到记录，请调整搜索条件。</span>
			</div>
		</div>
	</xsl:if>
	<xsl:call-template name="page"></xsl:call-template>
</div>
</xsl:template>

</xsl:stylesheet>