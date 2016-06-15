<?xml version="1.0" encoding="utf-8" ?>
<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<!-- UTF8 编码 -->

<xsl:include href="/style/header.xsl" />

<xsl:template name="text">

<script type="text/javascript" src="/js_encode/home_system_announcement.js"></script>
<!-- 时间插件 -->
<script type="text/javascript" src="/js_encode/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript" src="/js_encode/bootstrap-datetimepicker.zh-CN.js"></script>
<link rel="stylesheet" href="/style/bootstrap-datetimepicker.min.css"></link>
<div class="mainBody" style="width:1200px; height:521px;">
	<ul id="myTab" class="nav nav-tabs nav_tabs_margin">
		<li><a href="/home/home_system_announcement.php">通知公告</a></li>
		<li class="active"><a href="#">待办事项</a></li>
	</ul>
	<div id="myTabContent" class="tab-content">
		
			<form class="form-inline" method="post" action="/home/home_system_daiban.php">
				<div class="goodsMsg" style="clear:both;">
					<div class="table_operate_blocks">
						<div class="form-group float_right margin0">
							<div class="form-group form-group_1 btn_margin">
								<label>日期：</label>
								<xsl:element name="INPUT">
									<xsl:attribute name="type">text</xsl:attribute>
									<xsl:attribute name="class">form-control input-sm seach</xsl:attribute>
									<xsl:attribute name="name">date</xsl:attribute>
									<xsl:attribute name="value"><xsl:value-of select="/html/Body/date"/></xsl:attribute>
								</xsl:element>
							</div>
							<div class="form-group form-group_1 btn_margin">
								<label>状态：</label>
								<select class="form-control input-sm" name="status">
									<option value="0"></option>
									<xsl:element name="OPTION">
											<xsl:attribute name="value">N</xsl:attribute>
											<xsl:if test="'N'=/html/Body/sta">
												<xsl:attribute name="selected">selected</xsl:attribute>
											</xsl:if>
											未完成
									</xsl:element>
									<xsl:element name="OPTION">
											<xsl:attribute name="value">Y</xsl:attribute>
											<xsl:if test="'Y'=/html/Body/sta">
												<xsl:attribute name="selected">selected</xsl:attribute>
											</xsl:if>
											完成
									</xsl:element>
								</select>
							</div>
							<div class="form-group form-group_1 btn_margin">
								<label>姓名：</label>
								<xsl:element name="INPUT">
									<xsl:attribute name="type">text</xsl:attribute>
									<xsl:attribute name="class">form-control input-sm seach</xsl:attribute>
									<xsl:attribute name="name">name</xsl:attribute>
									<xsl:attribute name="placeholder">搜索</xsl:attribute>
									<xsl:attribute name="value"><xsl:value-of select="/html/Body/na"/></xsl:attribute>
								</xsl:element>
							</div>
							<div class="float_right">
								<input class="btn btn-default btn-sm btn_margin" type="submit" value="查询" /><input class="btn btn-default btn-sm" type="reset" value="清空" />
							</div>
						</div>
						<div class="btn-group">
							<a href="" onclick="MessageBox('/home/home_agency_matters_add.php?id=1', '新增', 680, 400); return false"><input class="btn btn-default btn-sm btn_margin" type="button" value="新增" /></a>
						</div>
					</div>
					<table class="table table-bordered table-hover table-order-form">
						<tr>
							<th class="table_th_number">序号</th>	
							<th class="center " width="120px;">操作</th>
							<th class="" width="297px;">标题</th>
							<th class="" width="297px;">内容</th>
							<th class="" width="65px">状态</th>
							<th class="" width="140px">截止时间</th>
							<th class="" width="140px">发布时间</th>
							<th class="" width="95px">发布人</th>
						</tr>
						<xsl:for-each select="/html/Body/schedule/ul/li">
						<tr>
							<td class="center"><xsl:value-of select="no"/></td>
							<td class="center">
								<xsl:if test="source='Notice'">
									<xsl:element name="A">
									<xsl:attribute name="href">javascript:;</xsl:attribute>
									<xsl:attribute name="onclick">MessageBox('/home/home_agency_matters_xiang2.php?notice_id=<xsl:value-of select="notice_id"/>', '详情', 705, 500)</xsl:attribute>
									详细
									</xsl:element>
								</xsl:if>
								<xsl:if test="source='Person'">
									<xsl:element name="A">
									<xsl:attribute name="href">javascript:;</xsl:attribute>
									<xsl:attribute name="onclick">MessageBox('/home/home_agency_matters_xiang1.php?id=<xsl:value-of select="id"/>', '详情', 680, 400)</xsl:attribute>
									详细
									</xsl:element>
								</xsl:if>
							<a href="#" class="margin_left_1">完成</a>
							<!-- <a href="#" class="margin_left_1">完成</a> -->
							<xsl:element name="A">
									<xsl:attribute name="href">javascript:;</xsl:attribute>
									<xsl:attribute name="class">margin_left_1</xsl:attribute>
									<xsl:attribute name="onclick">MessageBox('/home/home_agency_matters_delete.php?id=<xsl:value-of select="id"/>', '删除', 320, 90)</xsl:attribute>删除
								</xsl:element>
							</td>
							<td><xsl:value-of select="name"/></td>
							<td><xsl:value-of select="body"/></td>
							<td><xsl:value-of select="status"/></td>
							<td><xsl:value-of select="end_date"/></td>
							<td><xsl:value-of select="action_date"/></td>
							<td><xsl:value-of select="staff_id"/></td>
						</tr>
						</xsl:for-each>
					</table>
				</div>
			</form>
		<xsl:call-template name="page"></xsl:call-template>
		
	</div>
</div>
</xsl:template>
</xsl:stylesheet>