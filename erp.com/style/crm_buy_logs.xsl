<?xml version="1.0" encoding="utf-8" ?>
<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<!-- UTF8 编码 -->

<xsl:include href="/style/header.xsl" />

<xsl:template name="text">
<script type="text/javascript" src="/js_encode/stock_warehouse_set.js"></script>
<script type="text/javascript" src="/js_encode/crm_short_message.js"></script>
<div class="mainBody">
	<ul id="myTab" class="nav nav-tabs nav_tabs_margin">
	   <li><a href="/crm/crm_message_statis.php">统计报表</a></li>
	   <li><a href="/crm/crm_short_message.php">短信模板</a></li>
	   <li><a href="/crm/crm_message_history.php">发送历史</a></li>
	   <li><a href="/crm/crm_message_allocation.php">短信配置</a></li>
	   <li><a href="/crm/crm_message_buy.php">短信订购</a></li>
	   <li class="active"><a href="#Buy_logs" data-toggle="tab">订购历史</a></li>
	   <li style='float:right;line-height:39px;'>短信剩余条数：<b style='color:#ff0000'><xsl:value-of select="/html/Body/message_remain"/></b> 条</li>
	</ul>
	<div id="myTabContent" class="tab-content">
		<div class="tab-pane fade in active" id="Buy_logs">
				<div class="warehouseMsg" style="clear:both;">
					<form class="form-inline">
						<div class="table_operate_block">
							<!-- <input class="btn btn-default btn-sm btn_margin"  onclick="MessageBox('/crm/crm_add_template.php', '添加模板',415, 255)" type="button" value="添加模板"/>
							<div class="form-group float_right margin0">
								<label>模板类型：</label>
								<select class="form-control input-sm" name="purchase_channels">
									<xsl:for-each select="/html/Body/supplierMsg/type/ul/li">
										<xsl:element name="OPTION">
										</xsl:element>
									</xsl:for-each>
								</select>
							</div> -->
						</div>
					</form>
					<table style="width:1200px" class="table table-bordered table-hover">
						<tr>
							<th class="center" width="46px;">序号</th>
							<th width="334px;">支付宝交易号</th>
							<th width="230px">充值账户</th>
							<th width="230px">实付金额</th>
							<th width="130px">短信数量</th>
							<th width="230px">订购时间</th>
						</tr>
						<xsl:for-each select="/html/Body/short/ul/li">
							<tr>
								<td class="center"><xsl:value-of select="num"/></td>

								<td width="100px"><xsl:value-of select="alipay_no"/></td>
								<td><xsl:value-of select="alipay_name"/></td>
								<td width="120px"><xsl:value-of select="payment"/></td>
								<td width="550px"><xsl:value-of select="total"/></td>
								<td><xsl:value-of select="action_date"/></td>
							</tr>
						</xsl:for-each>
					</table>
				</div>
				<xsl:if test="/html/Body/stock/ul/@total = '0'">
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
</div>

</xsl:template>

</xsl:stylesheet>
