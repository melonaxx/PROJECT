<?xml version="1.0" encoding="utf-8" ?>
<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<!-- UTF8 编码 -->

<xsl:include href="/style/header.xsl" />

<xsl:template name="text">
<script type="text/javascript" src="/js_encode/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript" src="/js_encode/bootstrap-datetimepicker.zh-CN.js"></script>
<link rel="stylesheet" href="/style/bootstrap-datetimepicker.min.css"></link>
<style>
	span{display:inline-block;width:120px;line-height:25px;margin-right:40px}
	label{margin-left:4px}
</style>
<div class="mainBody">
	<ul class="nav nav-tabs nav_tabs_margin">
		<li class="active"><a href="">角色管理</a></li>
		<li><a href="">操作权限</a></li>
	</ul>
	<div class="table_operate_block form-inline">
		<!-- <form action="/setting/setting_record.php" method="get">
			<div class="form-group" style="margin:0; float:right;">
				<p>
					<button class="btn btn-default btn-sm btn_margin" type="submit">查询</button>
						<input  name="clear" class="btn btn-default btn-sm" type="button" value='清空' />
				</p>
			</div>
			<div class="form-group form_small_block float_right">
				<label>姓名：</label>

				<span > 
				<xsl:element name="input">
					<xsl:attribute name="type">text</xsl:attribute>
					<xsl:attribute name="class">form-control input-sm</xsl:attribute>
					<xsl:attribute name="name">name</xsl:attribute>
				</xsl:element>
			</div>
			<div class="form-group form_small_block float_right">
				<label>部门：</label>

				<span > 
				<select class="form-control input-sm" name="group_id" id="department">
					<xsl:for-each select="/html/Body/companystaffgroup/ul/li">
						<xsl:element name="OPTION">
							<xsl:attribute name="value"><xsl:value-of select="value"/></xsl:attribute>
							<xsl:if test="value=/html/Body/group_id"><xsl:attribute name="selected">selected</xsl:attribute></xsl:if>
							<xsl:value-of select="text"/>
						</xsl:element>
					</xsl:for-each>
				</select>
			</div>
			<div class="form-group form_small_block float_right">
				<label>时间：</label>

				<span > 
				<xsl:element name="input">
					<xsl:attribute name="type">text</xsl:attribute>
					<xsl:attribute name="class">form-control input-sm</xsl:attribute>
					<xsl:attribute name="value"><xsl:value-of select="/html/Body/action_date"/></xsl:attribute>
					<xsl:attribute name="name">action_date</xsl:attribute>
				</xsl:element>
			</div>
		</form> -->

		<table width="1200px" class="table table-bordered">
			<tr>
				<th width='46px'>序号</th>
				<th width="100px">角色</th>
				<th width="1056px">权限管理</th>
			</tr>
			<tr>
				<td width='' style="text-align:center"></td>
				<td width='' style="vertical-align:middle; ">
					<span > 
						客服
					</span>
				</td>
				<td>
					<a href="/setting/setting_rights_management.php">设置</a>
				</td>
			</tr>	
			<tr>
				<td width='' style="text-align:center"></td>
				<td width='' style="vertical-align:middle; ">
					<span >
						销售
					</span>
				</td>
				<td>
					<a href="">设置</a>
				</td>
			</tr>
		
		</table>
	</div>

	</div>
	<script>
		$("table tr").each(function(index){
			var a = $(this).index();
			if(a>0){
				$(this).find('td').eq(0).text(a);;
			}
		});
	</script>
</xsl:template>

</xsl:stylesheet>
