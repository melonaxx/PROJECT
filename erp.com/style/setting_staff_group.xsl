<?xml version="1.0" encoding="utf-8" ?>
<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<!-- UTF8 编码 -->

<xsl:include href="/style/header.xsl" />

<xsl:template name="text">

<iframe width='0' height='0' frameborder='0' id='ajax_html' name='ajax_html' src='/style/empty.html'></iframe>
<form id="group_valid" target="ajax_html" method="post" action="/setting/setting_group_valid.php">
	<input type="hidden" id="group_id" name="id" value="0" />
	<input type="hidden" id="is_valid" name="is_valid" value="Y" />
</form>
<script>
function valid_group(pid, valid)
{
	if(valid == 'N')
	{
		var cc	= confirm("确定停用吗？");
		if(!cc)
		{
			return false;
		}
	}
	getObject("group_id").value		= pid;
	getObject("is_valid").value		= valid;
	getObject("group_valid").submit();
}
</script>

<ul class="nav nav-tabs">
	<li class="tab-pane"><a href="/setting/setting_staff_management.php">员工管理</a></li>
	<li class="tab-pane active"><a>部门管理</a></li>
</ul>

<br/>
<div class="table_operate_block">
	<input class="btn btn-default btn-sm" type="button" value="添加部门" onclick="MessageBox('/setting/setting_add_group.php', '添加部门', 500, 250)"/>
</div>

<div class="clear"></div>

<table class="table table-bordered table-hover" style="width:1200px">
<tr>
	<th width="50"><center>序号</center></th>
	<th width="100">操作</th>
	<th width="590">部门名称</th>
	<th width="60"><center>状态</center></th>
	<th width="400">备注</th>
</tr>
<xsl:for-each select="/html/Body/ul/li">
	<tr>
		<td><center><xsl:value-of select="@no"/></center></td>
		<td>
			<xsl:element name="A">
				<xsl:attribute name="href">/setting/setting_edit_group.php?id=<xsl:value-of select="@id"/></xsl:attribute>
				<xsl:attribute name="onclick">MessageBox('/setting/setting_edit_group.php?id=<xsl:value-of select="@id"/>', '修改部门信息', 500, 250); return false</xsl:attribute>
				修改
			</xsl:element>
			|
			<xsl:if test="@valid = 'N'">
				<xsl:element name="A">
					<xsl:attribute name="id">group_<xsl:value-of select="@id"/></xsl:attribute>
					<xsl:attribute name="href">#</xsl:attribute>
					<xsl:attribute name="onclick">valid_group(<xsl:value-of select="@id"/>, 'Y'); return false</xsl:attribute>
					启用
				</xsl:element>
			</xsl:if>
			<xsl:if test="@valid = 'Y'">
				<xsl:element name="A">
					<xsl:attribute name="id">group_<xsl:value-of select="@id"/></xsl:attribute>
					<xsl:attribute name="href">#</xsl:attribute>
					<xsl:attribute name="onclick">valid_group(<xsl:value-of select="@id"/>, 'N'); return false</xsl:attribute>
					停用
				</xsl:element>
			</xsl:if>
		</td>
		<td><xsl:value-of select="name"/></td>
		<td>
			<xsl:attribute name="id">status_<xsl:value-of select="@id"/></xsl:attribute>
			<center>
			<xsl:if test="@valid = 'Y'">正常</xsl:if>
			<xsl:if test="@valid = 'N'">已停用</xsl:if>
			</center>
		</td>
		<xsl:element name="TD">
			<xsl:attribute name="id">group_text_<xsl:value-of select="@id"/></xsl:attribute>
			<xsl:value-of select="text" disable-output-escaping="yes" />
		</xsl:element>
	</tr>
	<xsl:if test="system-property('xsl:vendor')='Transformiix'">
		<script language="javascript">
		var txt = document.getElementById('group_text_<xsl:value-of select="@id"/>');
		txt.innerHTML = txt.textContent;
		</script>
	</xsl:if>
</xsl:for-each>
<xsl:if test="/html/Body/total = '0'">
	<tr><td colspan="5"><br/>&#160;&#160;&#160;&#160;&#160;&#160;暂无任何部门，<a href="#" onclick="MessageBox('/setting/setting_add_group.php', '添加部门', 500, 250)">点这里添加</a><br/><br/></td></tr>
</xsl:if>
</table>


</xsl:template>

</xsl:stylesheet>
