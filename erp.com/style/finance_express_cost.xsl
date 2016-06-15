<?xml version="1.0" encoding="utf-8" ?>
<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<!-- UTF8 编码 -->

<xsl:include href="/style/header.xsl" />

<xsl:template name="text">

<!-- 时间插件 -->
<script type="text/javascript" src="/js_encode/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript" src="/js_encode/bootstrap-datetimepicker.zh-CN.js"></script>
<link rel="stylesheet" href="/style/bootstrap-datetimepicker.min.css"></link>
<!--  -->
<script type="text/javascript">

	var sheng = '<xsl:value-of select="/html/Body/sheng" />';
	var shi = '<xsl:value-of select="/html/Body/shi" />';
	var xian = '<xsl:value-of select="/html/Body/xian" />';
</script>
<script type="text/javascript" src="/js_encode/finance_running_account.js"></script>
<div class="mainBody">
	<div class="tab-content">
	   	<div class="tab-pane fade in active" id="Blurb">
	   		<div class="mainBody">
				<div class="goodsMsg" style="clear:both;">
					<div class="table_operate_block margin0">
						<div class="form-group float_right margin0">
							<form class="form-inline" method="get" action="/finance/finance_express_cost.php">
							<div class="form-group form-group_1 btn_margin" style='margin-bottom:0px;'>
								<label>快递公司：</label>
								<select class="form-control input-sm" name="express">
									<option value="0"></option>
									<xsl:for-each select="/html/Body/ress">
										<xsl:element name="OPTION">
											<xsl:attribute name="value"><xsl:value-of select="express_id" /></xsl:attribute>
											<xsl:if test="express_id=/html/Body/express_id">
												<xsl:attribute name="selected">selected</xsl:attribute>
											</xsl:if>
											<xsl:value-of select="name" />
										</xsl:element>
									</xsl:for-each>
								</select>
							</div>
							<div class="form-group form-group_1 btn_margin" style='margin-bottom:0px;'>
								<label>店铺：</label>
								<select class="form-control input-sm" name="shop">
									<option value="0"></option>
									<xsl:for-each select="/html/Body/shop">
										<xsl:element name="OPTION">
											<xsl:attribute name="value"><xsl:value-of select="id" /></xsl:attribute>
											<xsl:if test="id=/html/Body/shop_id">
												<xsl:attribute name="selected">selected</xsl:attribute>
											</xsl:if>
											<xsl:value-of select="name" />
										</xsl:element>
									</xsl:for-each>
								</select>
							</div>
							<div class="form-group form-group_1 btn_margin" style='margin-bottom:0px;'>
								<label>日期：</label>
								<xsl:element name="INPUT">
									<xsl:attribute name="id">begin_time</xsl:attribute>
									<xsl:attribute name="type">text</xsl:attribute>
									<xsl:attribute name="name">begin_date</xsl:attribute>
									<xsl:attribute name="value">
										<xsl:value-of select="/html/Body/begin_date"/>
									</xsl:attribute>
									<xsl:attribute name="class">form-control input-sm </xsl:attribute>
								</xsl:element>
							</div>
							<div class="form-group form-group_1 btn_margin" style='margin-bottom:0px;'>
								<label style="margin:0 6px 0 -8px">到</label>
								<xsl:element name="INPUT">
									<xsl:attribute name="id">end_time</xsl:attribute>
									<xsl:attribute name="type">text</xsl:attribute>
									<xsl:attribute name="name">end_date</xsl:attribute>
									<xsl:attribute name="value">
										<xsl:value-of select="/html/Body/end_date"/>
									</xsl:attribute>
									<xsl:attribute name="class">form-control input-sm </xsl:attribute>
								</xsl:element>
							</div>

							<div class="float_right">
								<input class="btn btn-default btn-sm btn_margin" type="submit" value="查询" />
								<input class="btn btn-default btn-sm" name="clear" type="button" value="清空" />
							</div>
							</form>
						</div>
					</div>
					<div style="float:left;line-height:30px;width:100%;height:30px;">
						<span>合计：</span>
						<span style='margin-left:10px;'>运费：<span style='color:red;'><xsl:value-of select="/html/Body/freight_buyer_z"/></span> 元</span>
						<span style='margin-left:10px;'>成本：<span style='color:red;'><xsl:value-of select="/html/Body/freight_seller_z"/></span> 元</span>
						<span style='margin-left:10px;'>毛利：<span style='color:red;'><xsl:value-of select="/html/Body/profit_z"/></span> 元</span>
					</div>
					<table class="table table-bordered table-hover table-order-form">
						<tr>
							<th class="center" width="46px">序号</th>
							<th width="140px">发货日期</th>
							<th width="122px">快递公司</th>
							<th width="142px">店铺</th>
							<th width="150px">订单编号</th>
							<th width="150px">快递单号</th>
							<th width="150px">运费</th>
							<th width="150px">快递成本</th>
							<th width="150px">快递毛利</th>
						</tr>
						<xsl:for-each select="/html/Body/express/ul/li">
						<tr>

							<td class="center"><xsl:value-of select="no"/></td>
							<td><xsl:value-of select="deliver_date"/></td>
							<td><xsl:value-of select="express_id"/></td>
							<td><xsl:value-of select="shop_id"/></td>
							<td><xsl:value-of select="number"/></td>
							<td><xsl:value-of select="bind_number"/></td>
							<td><xsl:value-of select="freight_buyer"/></td>
							<td><xsl:value-of select="freight_seller"/></td>
							<td><xsl:value-of select="profit"/></td>
						</tr>
						</xsl:for-each>
					</table>
				</div>
			</div>
		</div>
		<xsl:if test="/html/Body/express/ul/@total = '0'">
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
		$('select[name=shop]').val('');
		$('select[name=express]').val('');
		$('input[name=begin_date]').val('');
		$('input[name=end_date]').val('');
	})
</script>
</xsl:template>

</xsl:stylesheet>
