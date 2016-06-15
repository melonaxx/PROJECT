<?xml version="1.0" encoding="utf-8" ?>
<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<!-- UTF8 编码 -->

<xsl:include href="/style/header.xsl" />

<xsl:template name="text">
<!-- 时间插件 -->
<script type="text/javascript" src="/js_encode/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript" src="/js_encode/bootstrap-datetimepicker.zh-CN.js"></script>
<link rel="stylesheet" href="/style/bootstrap-datetimepicker.min.css"></link>

<div class="mainBody">
	<ul id="myTab" class="nav nav-tabs nav_tabs_margin">
	   <li class="active"><a href="#goods_shelves" data-toggle="tab">统计报表</a></li>
	   <li><a href="/crm/crm_short_message.php">短信模板</a></li>
	   <li><a href="/crm/crm_message_history.php">发送历史</a></li>
	   <li><a href="/crm/crm_message_allocation.php">短信配置</a></li>
	   <li><a href="/crm/crm_message_buy.php">短信订购</a></li>
	   <li><a href="/crm/crm_buy_logs.php">订购历史</a></li>
	   <li style='float:right;line-height:39px;'>短信剩余条数：<b style='color:#ff0000'><xsl:value-of select="/html/Body/message_remain"/></b> 条</li>
	</ul>
	<div id="myTabContent" class="tab-content">
		<div class="allocationListMsg">
			<form class="form-inline" method="get" action="/crm/crm_message_statis.php">
				<div class="table_operate_block">
					<div class="form-group" style="margin:0; float:right;">
						<input type="submit" class="btn btn-default btn-sm btn_margin" name="send" value="查询" />
						<input type="button" class="btn btn-default btn-sm" name="clear" value="清空" />
					</div>
					<div class="form-group form_small_block float_right">
						<label class="btn_margin">到</label>
						<xsl:element name="INPUT">
							<xsl:attribute name="type">text</xsl:attribute>
							<xsl:attribute name="name">date_end</xsl:attribute>
							<xsl:attribute name="class">form-control input-sm</xsl:attribute>
							<xsl:attribute name="value"><xsl:value-of select="/html/Body/date_end"/></xsl:attribute>
						</xsl:element>
					</div>
					<div class="form-group form_small_block float_right">
						<label>日期：</label>
						<xsl:element name="INPUT">
							<xsl:attribute name="type">text</xsl:attribute>
							<xsl:attribute name="name">date_start</xsl:attribute>
							<xsl:attribute name="class">form-control input-sm</xsl:attribute>
							<xsl:attribute name="value"><xsl:value-of select="/html/Body/date_start"/></xsl:attribute>
						</xsl:element>
					</div>
				</div>
			</form>
			<table class="table table-bordered table-hover">
				<tr>
					<th class="center" width="46px;">序号</th>
					<th width="154px;">发送日期</th>
					<th width="500px;">店铺名称</th>
					<th width="500px;">发送数量</th>
				</tr>
				<xsl:for-each select="/html/Body/number/ul/li">
				<tr>
					<td class="center"><xsl:value-of select="no"/></td>
					<td><xsl:value-of select="action_date"/></td>
					<td><xsl:value-of select="shop_id"/></td>
					<td><xsl:value-of select="total"/></td>
				</tr>
				</xsl:for-each>
			</table>
		</div>
		<xsl:if test="/html/Body/number/ul/@total = '0'">
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
<script type="text/javascript">
	$('input[name=date_end] , input[name=date_start]').datetimepicker({
		language:'zh-CN',
		format:'yyyy-mm-dd',
		autoclose:true,
		minView:2,
    });
    $('input[name=clear]').click(function(){
    	$('input[name=date_end] , input[name=date_start]').val('');
    })
</script>
</xsl:template>

</xsl:stylesheet>
