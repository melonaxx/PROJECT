<?xml version="1.0" encoding="utf-8" ?>
<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<!-- UTF8 编码 -->

<xsl:include href="/style/header.xsl" />

<xsl:template name="text">
<div class="mainBody">
	<ul id="myTab" class="nav nav-tabs nav_tabs_margin">
	   <li><a href="/crm/crm_message_statis.php">统计报表</a></li>
	   <li>
		  <a href="/crm/crm_short_message.php">短信模板</a>
	   </li>
	   <li><a href="/crm/crm_message_history.php">发送历史</a></li>
	   <li class="active"><a href="" data-toggle="tab">短信配置</a></li>
	   <li><a href="/crm/crm_message_buy.php">短信订购</a></li>
	   <li><a href="/crm/crm_buy_logs.php">订购历史</a></li>
	   <li style='float:right;line-height:39px;'>短信剩余条数：<b style='color:#ff0000'><xsl:value-of select="/html/Body/message_remain"/></b> 条</li>
	</ul>
	<div id="myTabContent" class="tab-content">
		<form class="form-inline" method="get" action="/crm/crm_message_allocation.php">
			<div class="table_operate_block" style="margin-bottom:0px;">
				<div class="form-group" style="margin-left:10px;margin-right:0px; float:right;">
					<input type="submit" class="btn btn-default btn-sm" name="send" value="查询" />
				</div>
				<div class="form-group float_right margin0">
					<label>模板类型：</label>
					<select class="form-control input-sm" name="type">
						<option value="0"></option>
						<xsl:element name="OPTION">
							<xsl:attribute name="value">Deliver</xsl:attribute>
							<xsl:if test="'Deliver'=/html/Body/type">
								<xsl:attribute name="selected">selected</xsl:attribute>
							</xsl:if>
							发货通知
						</xsl:element>
						<xsl:element name="OPTION">
							<xsl:attribute name="value">Remind</xsl:attribute>
							<xsl:if test="'Remind'=/html/Body/type">
								<xsl:attribute name="selected">selected</xsl:attribute>
							</xsl:if>
							短信催付
						</xsl:element>
						<xsl:element name="OPTION">
							<xsl:attribute name="value">Payment</xsl:attribute>
							<xsl:if test="'Payment'=/html/Body/type">
								<xsl:attribute name="selected">selected</xsl:attribute>
							</xsl:if>
							已付款通知
						</xsl:element>
					</select>
				</div>
			</div>
		</form>
		<div style="displat:blcok" class="smsMsg">
			<table class="table table-bordered table-hover">
				<tr>
					<th class="center" width="46px;">序号</th>
					<th class="center" width="46px;">操作</th>
					<th width="100px;">状态</th>
					<th width="110px;">短信模板</th>
					<th width="148px;">签名</th>
					<th width="600px;">模板内容</th>
					<th width="150px;">发送策略</th>
				</tr>
				<xsl:for-each select="/html/Body/strategy/ul/li">
					<tr>
						<td class="center"><xsl:value-of select="no"/></td>
						<td class="center">
							<xsl:element name="A">
							   <xsl:attribute name="href">javascript:;</xsl:attribute>
							   <xsl:attribute name="onclick">MessageBox('/crm/crm_modify_config.php?id=<xsl:value-of select="id"/>', '修改配置', 420, 180)
							   </xsl:attribute>修改
							</xsl:element>
						</td>
						<td><xsl:value-of select="strategy_status"/></td>
						<td><xsl:value-of select="type"/></td>
						<td><xsl:value-of select="sign"/></td>
						<td style="width:600px;overflow:hidden; text-overflow:ellipsis">
							<nobr><xsl:value-of select="content"/></nobr>
						</td>
						<td><xsl:value-of select="strategy"/></td>
					</tr>
				</xsl:for-each>
			</table>
		</div>
		<xsl:if test="/html/Body/strategy/ul/@total = '0'">
			<div class="imgs" style="margin:0 auto; width:100%;text-align:center">
				<div class="img">
					<img src="/images/empty.jpg"  alt=""/>
					<span>没有找到记录，请调整搜索条件。</span>
				</div>
			</div>
		</xsl:if>
		<xsl:call-template name="page"></xsl:call-template>
	</div>
</div>

</xsl:template>

</xsl:stylesheet>
