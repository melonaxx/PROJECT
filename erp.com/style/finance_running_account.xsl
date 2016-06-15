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
<div class="modal fade" id="confirm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
				<h4 class="modal-title" id="myModalLabel">提示</h4>
			</div>
			<div class="modal-body">
	  			您确定要删除<span class="number">1</span>条数据吗？
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default btn-sm ok" data-dismiss="modal">确定</button>
				<button type="button" class="btn btn-default btn-sm cancel" data-dismiss="modal">取消</button>
			</div>
		</div>
	</div>
</div>
<div class="mainBody">
	<ul id="myTab" class="nav nav-tabs">
	   <li class="active"><a href="#Blurb" data-toggle="tab">资金流水</a></li>
	   <li><a href="/finance/finance_running_income_account.php">收入科目</a></li>
	   <li><a href="/finance/finance_running_spend_account.php">支出科目</a></li>
	   <!-- <li><a href="#RWBE2" data-toggle="tab">支出科目</a></li> -->
	</ul>
	<div class="tab-content">
	   	<div class="tab-pane fade in active" id="Blurb">
	   		<div class="mainBody">
				<div class="goodsMsg" style="clear:both;">
					<div class="table_operate_blocks">
						<div class="form-group float_right margin0">
							<form class="form-inline" method="get" action="/finance/finance_running_account.php">
							<div class="form-group form-group_1 btn_margin">
								<label>单据类型：</label>
								<select class="form-control input-sm" name="type">
									<option value="0"></option>
									<xsl:element name="OPTION">
											<xsl:attribute name="value">Input</xsl:attribute>
											<xsl:if test="'Input'=/html/Body/type">
												<xsl:attribute name="selected">selected</xsl:attribute>
											</xsl:if>
											收入单据
									</xsl:element>
									<xsl:element name="OPTION">
											<xsl:attribute name="value">Output</xsl:attribute>
											<xsl:if test="'Output'=/html/Body/type">
												<xsl:attribute name="selected">selected</xsl:attribute>
											</xsl:if>
											支出单据
									</xsl:element>
								</select>
							</div>
							<div class="form-group form-group_1 btn_margin">
								<label>结算账户：</label>
								<select class="form-control input-sm" name="name">
									<option value="0"></option>
									<xsl:for-each select="/html/Body/list">
										<xsl:element name="OPTION">
											<xsl:attribute name="value"><xsl:value-of select="id" /></xsl:attribute>
											<xsl:if test="id=/html/Body/na">
												<xsl:attribute name="selected">selected</xsl:attribute>
											</xsl:if>
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
								<input class="btn btn-default btn-sm" name="clear" type="button" value="清空" />
							</div>
							</form>
						</div>
						<div class="btn-group">
							<a href="" onclick="MessageBox('/finance/finance_running_account_fund.php?id=1', '记收入', 702, 265); return false"><input class="btn btn-default btn-sm btn_margin" type="button" value="记收入" /></a>
						</div>
						<div class="btn-group">
							<a href="" onclick="MessageBox('/finance/finance_running_account_pay.php?id=1', '记支出', 702, 265); return false"><input class="btn btn-default btn-sm btn_margin" type="button" value="记支出" /></a>
						</div>
					</div>
					<table class="table table-bordered table-hover table-order-form">
						<tr>
							<th class="center" width="50px">序号</th>
							<th class="center" width="90px">操作</th>
							<th width="210px">记账时间</th>
							<th width="210px">业务日期</th>
							<th width="160px">单据类型</th>
							<th width="160px">收支科目</th>
							<th width="160px">金额</th>
							<th width="160px">账户名称</th>
						</tr>
						<xsl:for-each select="/html/Body/running/ul/li">
						<tr>

							<td class="center"><xsl:value-of select="no"/></td>
							<td class="center">
								<xsl:element name="A">
									<xsl:attribute name="class">table_a_operate</xsl:attribute>
									<xsl:attribute name="href">javascript:;</xsl:attribute>
									<xsl:attribute name="onclick">MessageBox('/finance/finance_running_account_xiang.php?id=<xsl:value-of select="id" />', '详细', 702, 265);return false</xsl:attribute>
									<xsl:text>详细</xsl:text>
								</xsl:element>
							</td>
							<td><xsl:value-of select="amount_date"/></td>
							<td><xsl:value-of select="business_date"/></td>
							<td><xsl:value-of select="type"/></td>
							<td><xsl:value-of select="subject"/></td>
							<td><xsl:value-of select="money"/></td>
							<td><xsl:value-of select="bank"/></td>
						</tr>
						</xsl:for-each>
					</table>
				</div>
			</div>
		</div>
		<xsl:if test="/html/Body/running/ul/@total = '0'">
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
