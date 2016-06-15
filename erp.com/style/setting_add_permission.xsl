<?xml version="1.0" encoding="utf-8" ?>
<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<!-- UTF8 编码 -->

<xsl:include href="/style/header.xsl" />

<xsl:template name="text">

<style>
label	{font-weight:normal}
label.title	{font-weight:bold; color:green}
div.list_program	{text-indent:100px}
</style>
<div class="mainBody">

	<ul class="nav nav-tabs nav_tabs_margin">
		<li class="active"><a href="">添加角色</a></li>
	</ul>
	<div class="table_operate_block form-inline form-group">
		<div class=" form-group margin_left_block">
			<label style="margin-left:0px">角色名称：</label>
			<input class="form-control input-sm" type="text" />
		</div>
		<div class=" form-group margin_left_block">
			<label >备注：</label>
			<input class="form-control input-sm merger_two_row_4" type="text" />
		</div>
	</div>

	<xsl:for-each select="/html/Body/dl">
		<xsl:element name="INPUT">
			<xsl:attribute name="type">checkbox</xsl:attribute>
			<xsl:attribute name="id">chk_channel_<xsl:value-of select="@id"/></xsl:attribute>
		</xsl:element>
		&#160;
		<xsl:element name="LABEL">
			<xsl:attribute name="class">title</xsl:attribute>
			<xsl:attribute name="for">chk_channel_<xsl:value-of select="@id"/></xsl:attribute>
			<xsl:value-of select="@name"/>
		</xsl:element>
		<br/>

		<xsl:for-each select="dd">
			&#160;&#160;&#160;&#160;&#160;&#160;&#160;
			<xsl:element name="INPUT">
				<xsl:attribute name="type">checkbox</xsl:attribute>
				<xsl:attribute name="id">chk_program_<xsl:value-of select="@id"/></xsl:attribute>
			</xsl:element>
			&#160;
			<xsl:element name="LABEL">
				<xsl:attribute name="for">chk_program_<xsl:value-of select="@id"/></xsl:attribute>
				<xsl:value-of select="@name"/>
			</xsl:element>
			<br/>
			<xsl:for-each select="li">
				&#160;&#160;&#160;&#160;&#160;&#160;&#160;
				&#160;&#160;&#160;&#160;&#160;&#160;&#160;
				<xsl:element name="INPUT">
					<xsl:attribute name="type">checkbox</xsl:attribute>
					<xsl:attribute name="id">chk_menu_<xsl:value-of select="@id"/></xsl:attribute>
				</xsl:element>
				&#160;
				<xsl:element name="LABEL">
					<xsl:attribute name="for">chk_menu_<xsl:value-of select="@id"/></xsl:attribute>
					<xsl:value-of select="@name"/>
					<xsl:value-of select="@url"/>
				</xsl:element>
				<br/>
			</xsl:for-each>
		</xsl:for-each>
	</xsl:for-each>

	<input type="submit" class="btn btn-default btn-sm btn_margin" value="保存"/>

</div>

</xsl:template>

</xsl:stylesheet>
