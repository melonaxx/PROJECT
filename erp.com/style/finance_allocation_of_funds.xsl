<?xml version="1.0" encoding="utf-8" ?>
<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<!-- UTF8 编码 -->

<xsl:include href="/style/header.xsl" />

<xsl:template name="text">
<!-- 时间插件 -->
<script type="text/javascript" src="/js_encode/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript" src="/js_encode/bootstrap-datetimepicker.zh-CN.js"></script>
<script type="text/javascript" src="/js_encode/finance_allocation_of_funds.js"></script>
<link rel="stylesheet" href="/style/bootstrap-datetimepicker.min.css"></link>

<div class="tab-content">
   	<div class="tab-pane fade in active" id="Blurb">
   		<div class="mainBody">
			<div class="goodsMsg" style="clear:both;">
				<div class="table_operate_blocks">
					<div class="form-group float_right margin0">
						<form class="form-inline" method="get" action="/finance/finance_allocation_of_funds.php">
						<div class="form-group form-group_1 btn_margin">
						<label>转入账户：</label>
							<select class="form-control input-sm" name="input_bank">
								<option value="0"></option>

								<xsl:for-each select="/html/Body/list_bank">
								<xsl:element name="OPTION">
									<xsl:attribute name="value"><xsl:value-of select="id" /></xsl:attribute>
									<xsl:if test="id=/html/Body/input_bank"><xsl:attribute name="selected">selected</xsl:attribute></xsl:if>
									<xsl:value-of select="name" />
								</xsl:element>
								</xsl:for-each>
							</select>
						</div>
						<div class="form-group form-group_1 btn_margin">
							<label>转出账户：</label>
							<select class="form-control input-sm" name="output_bank">
								<option value="0"></option>
								<xsl:for-each select="/html/Body/list_bank1">
									<xsl:element name="OPTION">
										<xsl:attribute name="value"><xsl:value-of select="id" /></xsl:attribute>
										<xsl:if test="id=/html/Body/output_bank"><xsl:attribute name="selected">selected</xsl:attribute></xsl:if>
										<xsl:value-of select="name" />
									</xsl:element>
								</xsl:for-each>
							</select>
						</div>
						<div class="form-group form-group_1 btn_margin">
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
						<div class="form-group form-group_1 btn_margin">
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
							<input class="btn btn-default btn-sm" id="clear" type="button" value="清空" />
						</div>
						</form>
					</div>
					<div class="btn-group">
						<a href="" onclick="MessageBox('/finance/finance_allocation.php?id=1', '新增划拨', 500, 230); return false"><input class="btn btn-default btn-sm btn_margin" type="button" value="新增划拨" /></a>
					</div>
				</div>
				<table class="table table-bordered table-hover table-order-form">
					<tr>
						<th class="center" width="50px">序号</th>
						<th class="center" width="90px">操作</th>
						<th class="" width="150px">创建时间</th>
						<th class="" width="120px">业务日期</th>
						<th class="" width="120px">转出账户</th>
						<th class="" width="120px">转入账户</th>
						<th class="" width="120px">金额</th>
						<th class="" width="310px">备注</th>
						<th class="" width="120px">操作人</th>
					</tr>
					<xsl:for-each select="/html/Body/allocation/ul/li">
					<tr>
						<td class="center"><xsl:value-of select="no"/></td>
						<td class="center">
								<xsl:element name="A">
									<xsl:attribute name="class">table_a_operate</xsl:attribute>
									<xsl:attribute name="href">javascript:;</xsl:attribute>
									<xsl:attribute name="onclick">MessageBox('/finance/finance_allocation_of_xiang.php?id=<xsl:value-of select="id" />', '详细', 500, 230);return false</xsl:attribute>
									<xsl:text>详细</xsl:text>
								</xsl:element>
							</td>
						<td><xsl:value-of select="action_date"/></td>
						<td><xsl:value-of select="business_date"/></td>
						<td><xsl:value-of select="output_bank_id"/></td>
						<td><xsl:value-of select="input_bank_id"/></td>
						<td><xsl:value-of select="money"/></td>
						<td><xsl:value-of select="body"/></td>
						<td><xsl:value-of select="nick"/></td>
					</tr>
					</xsl:for-each>
				</table>
			</div>
		</div>
		<xsl:if test="/html/Body/allocation/ul/@total = '0'">
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
	$('#begin_time,#end_time').datetimepicker({
		language:'zh-CN',
		format:'yyyy-mm-dd',
		autoclose:true,
		minView:2,
    });
	$("#clear").click(function(){
		$('#begin_time').val('');
		$('#end_time').val('');
		$('select[name=output_bank]').val('');
		$('select[name=input_bank]').val('');
	})
</script>
</xsl:template>
</xsl:stylesheet>