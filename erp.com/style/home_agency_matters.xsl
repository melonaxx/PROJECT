<?xml version="1.0" encoding="utf-8" ?>
<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<!-- UTF8 编码 -->
<xsl:include href="/style/header.xsl" />
<xsl:template name="text">
<script type="text/javascript" src="/js_encode/jquery.area.three.js"></script>
<style>
</style>
<!-- 时间插件 -->
<script type="text/javascript" src="/js_encode/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript" src="/js_encode/bootstrap-datetimepicker.zh-CN.js"></script>
<link rel="stylesheet" href="/style/bootstrap-datetimepicker.min.css"></link>

<div class="mainBody">
	<div class="goodsMsg" style="clear:both;">
		<form class="form-inline" method="get" action="/home/home_agency_matters.php">
			<div class="table_operate_blocks">
				<div class="form-group float_right margin0">
					<div class="form-group form-group_1 btn_margin">
						<label>日期：</label>
						<xsl:element name="INPUT">
							<xsl:attribute name="type">text</xsl:attribute>
							<xsl:attribute name="name">date</xsl:attribute>
							<xsl:attribute name="value"><xsl:value-of select="/html/Body/date"/></xsl:attribute>
							<xsl:attribute name="class">form-control input-sm seach</xsl:attribute>
							<xsl:text>日期</xsl:text>
						</xsl:element>
					</div>
					<div class="float_right">
						<input class="btn btn-default btn-sm btn_margin" type="submit" value="查询" />
						<input class="btn btn-default btn-sm" name='clear' type="button" value="清空" />
					</div>
				</div>
				<div class="btn-group">
					<a href="" onclick="MessageBox('/home/home_system_announcement_notice.php', '添加通知',705,380); return false"><input class="btn btn-default btn-sm btn_margin" type="button" value="新增" /></a>
				</div>
			</div>
		</form>
		<table class="table table-bordered table-hover table-order-form">
			<tr>
				<th class="table_th_number">序号</th>
				<th class="center " width="80px;">操作</th>
				<th width="317px;">标题</th>
				<th width="437px;">内容</th>
				<th width="160px;">发布时间</th>
				<th width="160px;">发布人</th>
			</tr>
			<xsl:for-each select="/html/Body/notice/ul/li">
			<tr>
				<td class="center"><xsl:value-of select="no"/></td>
				<td class="center">
				<xsl:element name="A">
					<xsl:attribute name="href">javascript:;</xsl:attribute>
					<xsl:attribute name="onclick">MessageBox('/home/home_system_announcement_xiang.php?id=<xsl:value-of select="id" />', '详细', 705, 400);return false</xsl:attribute>
					<xsl:text>详细</xsl:text>
				</xsl:element>
				</td>
				<td><div style="width:317px;overflow: hidden; text-overflow:ellipsis;"><nobr><xsl:value-of select="name"/></nobr></div></td>
				<td><div style="width:437px;overflow: hidden; text-overflow:ellipsis;"><nobr><xsl:value-of select="body"/></nobr></div></td>
				<td><xsl:value-of select="action_date"/></td>
				<td><xsl:value-of select="sign"/></td>
			</tr>
			</xsl:for-each>
		</table>
		<xsl:if test="/html/Body/notice/ul/@total = '0'">
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
	// 获取时间
	$( "input[name=date]" ).datetimepicker({
		language:'zh-CN',
		format:'yyyy-mm-dd',
		autoclose:true,
		minView:2,
	});
	//清空
	$('input[name=clear]').click(function(){
		$('input[name=date]').val('');
	})
</script>
</xsl:template>
</xsl:stylesheet>