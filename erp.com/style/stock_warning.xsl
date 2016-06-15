<?xml version="1.0" encoding="utf-8" ?>
<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<!-- UTF8 编码 -->

<xsl:include href="/style/header.xsl" />

<xsl:template name="text">
<script type="text/javascript" src="/js_encode/stock_warning.js"></script>
<script type="text/javascript" src="/js_encode/bootstrap-datetimepicker.min.js"></script>
<style>
	#location_dir {
		height:0px;
	}
</style>
<div style="width:100%;margin-bottom:20px;margin-top:10px;" >
	<a href="/stock/stock_early_warning.php">返回</a>
</div>
<div class="mainBody">
	<div class="customersMsg">
		<div class="table_operate_block">
			<form class="form-inline" method="get" action="/stock/stock_warning.php">

				<!-- <a href="" onclick="MessageBox('/stock/stock_warning_set.php?id=1', '批量设置', 500, 150); return false"><input class="btn btn-default btn-sm btn_margin body" type="button" value="批量设置"/></a> -->
				<input class="btn btn-default btn-sm btn_margin set" type="button" value="批量设置" />
				<input class="btn btn-default btn-sm btn_margin set_low" type="button" value="取消下限" />
				<input class="btn btn-default btn-sm btn_margin set_up" type="button" value="取消上限" />
				<div class="form-group" style="margin:0; float:right;">
					<button class="btn btn-default btn-sm form_small_block" type="submit">查询</button>
					<input class="btn btn-default btn-sm" type="button" name="clear" value="清空"/>
				</div>
				<!-- <div class="form-group form_small_block float_right">
					<label>商品名称：</label>
					<xsl:element name="INPUT">
						<xsl:attribute name="type">text</xsl:attribute>
						<xsl:attribute name="name">find</xsl:attribute>
						<xsl:attribute name="value">
							<xsl:value-of select="/html/Body/find"/>
						</xsl:attribute>
						<xsl:attribute name="class">form-control input-sm</xsl:attribute>
					</xsl:element>
				</div> -->
				<div class="form-group form_small_block float_right">
					<label>有无预警：</label>
					<select name="is_yujing" class="form-control input-sm">
						<option value="0"></option>
						<xsl:element name="OPTION">
							<xsl:attribute name="value">Y</xsl:attribute>
							<xsl:if test="'Y'=/html/Body/is_yujing ">
								<xsl:attribute name="selected">selected</xsl:attribute>
							</xsl:if>
							有
						</xsl:element>
						<xsl:element name="OPTION">
							<xsl:attribute name="value">N</xsl:attribute>
							<xsl:if test="'N'=/html/Body/is_yujing ">
								<xsl:attribute name="selected">selected</xsl:attribute>
							</xsl:if>
							无
						</xsl:element>
					</select>
				</div>
				<div class="form-group form_small_block float_right">
					<label>仓库：</label>
					<select name="ware" class="form-control input-sm">
						<xsl:for-each select="/html/Body/store_info/ul/li">
							<xsl:element name="OPTION">
							   <xsl:attribute name="value"><xsl:value-of select="value"/></xsl:attribute>
							   <xsl:if test="value=/html/Body/ware"><xsl:attribute name="selected">selected</xsl:attribute></xsl:if>
							   <xsl:value-of select="text"/>
							</xsl:element>
						</xsl:for-each>
					</select>
				</div>
			</form>
		</div>
		<table width="1180" class="table table-bordered table-hover">
			<tr>
				<th class="table_th_number">序号</th>
				<th class="table_th_checkbox center"><input name="select_one" type="checkbox"/></th>
				<th class="center" width="66">操作</th>
				<th class="center" width="46">图片</th>
				<th width="172">商品名称</th>
				<th width="172">规格</th>
				<th width="120">商品条码</th>
				<th width="90">实际</th>
				<th width="90">可用</th>
				<th width="90">锁定</th>
				<th width="90">在途</th>
				<th width="90">下限</th>
				<th width="90">上限</th>
			</tr>
			<xsl:for-each select="/html/Body/list_store/ul/li">
				<tr>
					<td class="center"><xsl:value-of select="no"/></td>
					<td class="center"><input name="select_all" type="checkbox"/></td>
					<td class="center">
						<a class="setup" href="" type="button">设置</a>
					</td>
					<td class="center">
						<xsl:element name="img">
							<xsl:attribute name="src"><xsl:value-of select="image"/></xsl:attribute>
							<xsl:attribute name="width">20</xsl:attribute>
							<xsl:attribute name="height">20</xsl:attribute>
							<xsl:attribute name="class">smallimg</xsl:attribute>
						</xsl:element>
						<xsl:element name="img">
							<xsl:attribute name="src"><xsl:value-of select="image"/></xsl:attribute>
							<xsl:attribute name="width">200</xsl:attribute>
							<xsl:attribute name="height">200</xsl:attribute>
							<xsl:attribute name="class">bigimg</xsl:attribute>
							<xsl:attribute name="style">display:none;position:absolute</xsl:attribute>
						</xsl:element>
					</td>
					<td><xsl:value-of select="name"/></td>
					<td><xsl:value-of select="format"/></td>
					<td><xsl:value-of select="bar_code"/></td>
					<td><xsl:value-of select="total_real"/></td>
					<td><xsl:value-of select="total_available"/></td>
					<td><xsl:value-of select="total_lock"/></td>
					<td><xsl:value-of select="total_way"/></td>
					<td><xsl:value-of select="lower"/></td>
					<td><xsl:value-of select="upper"/></td>
					<xsl:element name="INPUT">
						<xsl:attribute name="type">hidden</xsl:attribute>
						<xsl:attribute name="name">id</xsl:attribute>
						<xsl:attribute name="value"><xsl:value-of select="id"/></xsl:attribute>
						<xsl:attribute name="class">main_id</xsl:attribute>
					</xsl:element>
				</tr>
			</xsl:for-each>
		</table>
		<xsl:if test="/html/Body/list_store/ul/@total = '0'">
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
	$('.smallimg').hover(function(){
		var t = $(this).position().top - 90 + 'px';
		var l = $(this).position().left + 20 + 'px';
		$(this).next().css("display","block");
		$(this).next().css("top",t);
		$(this).next().css("left",l);
	},function(){
		$(this).next().css("display","none");
	})
	$('input[name=clear]').click(function(){
		$('select[name=is_yujing]').val('');
	})
</script>
</xsl:template>

</xsl:stylesheet>