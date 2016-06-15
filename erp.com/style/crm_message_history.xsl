<?xml version="1.0" encoding="utf-8" ?>
<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<!-- UTF8 编码 -->

<xsl:include href="/style/header.xsl" />

<xsl:template name="text">
<div class="mainBody">
	<ul id="myTab" class="nav nav-tabs nav_tabs_margin">
	   <li><a href="/crm/crm_message_statis.php">统计报表</a></li>
	   <li><a href="/crm/crm_short_message.php">短信模板</a></li>
	   <li class="active"><a href="#reser_voirarea" data-toggle="tab">发送历史</a></li>
	   <li><a href="/crm/crm_message_allocation.php">短信配置</a></li>
	   <li><a href="/crm/crm_message_buy.php">短信订购</a></li>
	   <li><a href="/crm/crm_buy_logs.php">订购历史</a></li>
	   <li style='float:right;line-height:39px;'>短信剩余条数：<b style='color:#ff0000'><xsl:value-of select="/html/Body/message_remain"/></b> 条</li>
	</ul>
	<div id="myTabContent" class="tab-content">
		<div class="allocationListMsg">
			<form class="form-inline" method="get" action="/crm/crm_message_history.php">
				<div class="table_operate_block">
					<div class="form-group" style="margin:0; float:right;">
						<input type="submit" class="btn btn-default btn-sm btn_margin" name="send" value="查询" />
						<input type="button" class="btn btn-default btn-sm" name="clear" value="清空" />
					</div>
					<div class="form-group form_small_block float_right">
						<label>发送状态：</label>
						<select class="form-control input-sm" name="status">
							<option value="0"></option>
							<xsl:element name="OPTION">
							   <xsl:attribute name="value">Y</xsl:attribute>
							   <xsl:if test="'Y'=/html/Body/status">
							   		<xsl:attribute name="selected">selected</xsl:attribute>
							   </xsl:if>
							   发送成功
							</xsl:element>
							<xsl:element name="OPTION">
							   <xsl:attribute name="value">N</xsl:attribute>
							   <xsl:if test="'N'=/html/Body/status">
							   		<xsl:attribute name="selected">selected</xsl:attribute>
							   </xsl:if>
							   发送失败
							</xsl:element>
						</select>
					</div>
					<div class="form-group form_small_block float_right">
						<label>店铺名称：</label>
						<select class="form-control input-sm" name="shop_id">
							<option value="0"></option>
							<xsl:for-each select="/html/Body/shopName">
								<xsl:element name="OPTION">
								   <xsl:attribute name="value"><xsl:value-of select="value"/></xsl:attribute>
								   <xsl:if test="value=/html/Body/shop_id">
										<xsl:attribute name="selected">selected</xsl:attribute>
								   </xsl:if>
								   <xsl:value-of select="text"/>
								</xsl:element>
							</xsl:for-each>
						</select>
					</div>
				</div>
			</form>
			<table class="table table-bordered table-hover">
				<tr>
					<th class="center" width="46px;">序号</th>
					<th width="90px;">订单号</th>
					<th width="70px;">短信类型</th>
					<th width="94px;">店铺名称</th>
					<th width="80px;">接收人</th>
					<th width="95px;">手机号码</th>
					<th width="350px;">短信内容</th>
					<th width="80px;">发送状态</th>
					<th width="155px;">失败原因</th>
					<th width="140px;">发送时间</th>
				</tr>
				<xsl:for-each select="/html/Body/logs/ul/li">
				<tr>
					<td class="center"><xsl:value-of select="no"/></td>
					<td><xsl:value-of select="bind_number"/></td>
					<td><xsl:value-of select="type"/></td>
					<td><xsl:value-of select="shop_id"/></td>
					<td><xsl:value-of select="crm_user_id"/></td>
					<td><xsl:value-of select="mobile"/></td>
					<td style="overflow: hidden; text-overflow:ellipsis">
						<nobr><xsl:value-of select="content"/></nobr>
					</td>
					<td><xsl:value-of select="status"/></td>
					<td><xsl:value-of select="reason"/></td>
					<td><xsl:value-of select="action_date"/></td>
				</tr>
				</xsl:for-each>
			</table>
		</div>
		<xsl:if test="/html/Body/logs/ul/@total = '0'">
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
<script>
	$('input[name=clear]').click(function(){
		$('select[name=status]').val('');
		$('select[name=shop_id]').val('');
	})
</script>
</xsl:template>

</xsl:stylesheet>
