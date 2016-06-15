<?xml version="1.0" encoding="utf-8" ?>
<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<!-- UTF8 编码 -->

<xsl:include href="/style/header.xsl" />

<xsl:template name="text">
<script type="text/javascript" src="/js_encode/stock_warehouse_set.js"></script>
<script type="text/javascript" src="/js_encode/crm_short_message.js"></script>
<style type="text/css">
	.buy{
		cursor:pointer;
	}
</style>
<script type="text/javascript">
	$(function(){
		$('.buy').click(function(){
			var a = $(this).next().val();
			MessageBox('/crm/crm_message_submit.php?id='+a, '订单支付',410,255);
			return false;
		})
	})
</script>
<div class="mainBody">
	<ul id="myTab" class="nav nav-tabs nav_tabs_margin">
	   <li><a href="/crm/crm_message_statis.php">统计报表</a></li>
	   <li><a href="/crm/crm_short_message.php">短信模板</a></li>
	   <li><a href="/crm/crm_message_history.php">发送历史</a></li>
	   <li><a href="/crm/crm_message_allocation.php">短信配置</a></li>
	   <li class="active"><a href="#messageOrder" data-toggle="tab">短信订购</a></li>
	   <li><a href="/crm/crm_buy_logs.php">订购历史</a></li>
	   <li style='float:right;line-height:39px;'>短信剩余条数：<b style='color:#ff0000'><xsl:value-of select="/html/Body/message_remain"/></b> 条</li>
	</ul>
	<div id="myTabContent" class="tab-content">
		<div class="tab-pane fade in active" id="messageOrder">
				<div class="warehouseMsg" style="clear:both;">
					<table class="table table-bordered table-hover">
						<tr>
							<th class="center" width="46px;">序号</th>
							<th class="center" width="94px;">操作</th>
							<th width="530px">短信数量</th>
							<th width="530px">套餐价格</th>
						</tr>
							<xsl:for-each select="/html/Body/short/ul/li">
							<tr>
								<td class="center"><xsl:value-of select="num"/></td>
								<td class="center">
									<xsl:element name="A">
									   <xsl:attribute name="class">table_a_operate buy</xsl:attribute>订购
									</xsl:element>
									<xsl:element name="INPUT">
										<xsl:attribute name="type">hidden</xsl:attribute>
										<xsl:attribute name="name">id</xsl:attribute>
										<xsl:attribute name="value"><xsl:value-of select="id"/></xsl:attribute>
									</xsl:element>
								</td>
								<td><xsl:value-of select="total"/></td>
								<td>￥<xsl:value-of select="price"/>元　　<xsl:value-of select="danjia"/>分/条</td>
								<xsl:element name="INPUT">
								   <xsl:attribute name="value"><xsl:value-of select="include"/></xsl:attribute>
								   <xsl:attribute name="name">tpl_id</xsl:attribute>
								   <xsl:attribute name="type">hidden</xsl:attribute>
								</xsl:element>
							</tr>
							</xsl:for-each>
					</table>
				</div>
		</div>
	</div>
</div>

</xsl:template>

</xsl:stylesheet>
