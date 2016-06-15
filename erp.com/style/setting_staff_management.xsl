<?xml version="1.0" encoding="utf-8" ?>
<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<!-- UTF8 编码 -->

<xsl:include href="/style/header.xsl" />

<xsl:template name="text">

<iframe width='0' height='0' frameborder='0' id='ajax_html' name='ajax_html' src='/style/empty.html'></iframe>
<form id="staff_valid" target="ajax_html" method="post" action="/setting/setting_staff_valid.php">
	<input type="hidden" id="person_id" name="id" value="0" />
	<input type="hidden" id="is_valid" name="is_valid" value="Y" />
</form>
<script>
function valid_staff(pid, valid)
{
	if(valid == 'N')
	{
		var cc	= confirm("停用后，该操作员将无法登录，确定吗？");
		if(!cc)
		{
			return false;
		}
	}
	getObject("person_id").value	= pid;
	getObject("is_valid").value		= valid;
	getObject("staff_valid").submit();
}
</script>

<ul class="nav nav-tabs">
	<li class="tab-pane active"><a>员工管理</a></li>
	<li class="tab-pane"><a href="/setting/setting_staff_group.php">部门管理</a></li>
</ul>
<br/>
<div class="table_operate_block">
	<input class="btn btn-default btn-sm" type="button" value="添加员工" onclick="MessageBox('/setting/setting_add_staff.php', '添加员工', 600, 350)"/>
</div>

<div class="clear"></div>

<table class="table table-bordered table-hover" style="width:1200px">
<tr>
	<th width="50">序号</th>
	<th width="120">操作</th>
	<th width="110">员工编号</th>
	<th width="120">登录帐号</th>
	<th width="75">员工姓名</th>
	<th width="125">所属部门</th>
	<th width="60"><center>状态</center></th>
	<th width="150">联系方式</th>
	<th width="75">帐号类型</th>
	<th width="250">备注</th>
</tr>
<xsl:for-each select="/html/Body/ul/li">
	<tr>
		<td><center><xsl:value-of select="@no"/></center></td>
		<td>
			<center>
				<xsl:element name="A">
					<xsl:attribute name="href">/setting/setting_edit_staff.php?id=<xsl:value-of select="@id"/></xsl:attribute>
					<xsl:attribute name="onclick">MessageBox('/setting/setting_edit_staff.php?id=<xsl:value-of select="@id"/>', '修改员工信息', 600, 350); return false</xsl:attribute>
					编辑
				</xsl:element>
				|
				<xsl:element name="A">
					<xsl:attribute name="href">/setting/setting_staff_permission.php?id=<xsl:value-of select="@id"/></xsl:attribute>
					权限
				</xsl:element>
				<xsl:if test="@admin = 'N'">
					|
					<xsl:if test="@valid = 'N'">
						<xsl:element name="A">
							<xsl:attribute name="id">staff_<xsl:value-of select="@id"/></xsl:attribute>
							<xsl:attribute name="href">#</xsl:attribute>
							<xsl:attribute name="onclick">valid_staff(<xsl:value-of select="@id"/>, 'Y'); return false</xsl:attribute>
							启用
						</xsl:element>
					</xsl:if>
					<xsl:if test="@valid = 'Y'">
						<xsl:element name="A">
							<xsl:attribute name="id">staff_<xsl:value-of select="@id"/></xsl:attribute>
							<xsl:attribute name="href">#</xsl:attribute>
							<xsl:attribute name="onclick">valid_staff(<xsl:value-of select="@id"/>, 'N'); return false</xsl:attribute>
							停用
						</xsl:element>
					</xsl:if>
				</xsl:if>
			</center>
		</td>
		<td><xsl:value-of select="number"/></td>
		<td><xsl:value-of select="name"/></td>
		<td><xsl:value-of select="nick"/></td>
		<td><xsl:value-of select="group"/></td>
		<td>
			<xsl:attribute name="id">status_<xsl:value-of select="@id"/></xsl:attribute>
			<center>
			<xsl:if test="@valid = 'Y'">正常</xsl:if>
			<xsl:if test="@valid = 'N'">已停用</xsl:if>
			</center>
		</td>
		<td><xsl:value-of select="mobile"/></td>
		<td>
			<xsl:if test="@admin = 'Y'"><font color="blue">管理员</font></xsl:if>
			<xsl:if test="@admin = 'N'">员工</xsl:if>
		</td>
		<xsl:element name="TD">
			<xsl:attribute name="id">staff_text_<xsl:value-of select="@id"/></xsl:attribute>
			<xsl:value-of select="text" disable-output-escaping="yes" />
		</xsl:element>
	</tr>
	<xsl:if test="system-property('xsl:vendor')='Transformiix'">
		<script language="javascript">
		var txt = document.getElementById('staff_text_<xsl:value-of select="@id"/>');
		txt.innerHTML = txt.textContent;
		</script>
	</xsl:if>
</xsl:for-each>
</table>

<xsl:call-template name="page"></xsl:call-template>

</xsl:template>

</xsl:stylesheet>
