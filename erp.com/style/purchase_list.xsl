<?xml version="1.0" encoding="utf-8" ?>
<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<!-- UTF8 编码 -->

<xsl:include href="/style/header.xsl" />

<xsl:template name="text">
<script type="text/javascript" src="/js_encode/purchase_list.js"></script>
<script type="text/javascript" src="/js_encode/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript" src="/js_encode/bootstrap-datetimepicker.zh-CN.js"></script>
<link rel="stylesheet" href="/style/bootstrap-datetimepicker.min.css"></link>
<style>
	#alert {
		display:none;
		height:30px;
		line-height:30px;
		float:right;
		width:450px;
	}
</style>
<div class="mainBody">
	<div class="customersMsg">
		<div class="table_operate_block">
			<form class="form-inline" method="get" action="/purchase/purchase_list.php">
				<input class="btn btn-default btn-sm btn_margin body" type="button" value="审核通过" />
				<input class="btn btn-default btn-sm btn_margin edit" type="button" value="打回修改" />
				<input class="btn btn-default btn-sm btn_margin no" type="button" value="拒绝" />
				<div class="form-group" style="margin:0; float:right;">
					<button class="btn btn-default btn-sm form_small_block" type="submit">查询</button>
					<input class="btn btn-default btn-sm" type="button" name='clear' value='清空' />
				</div>
				<div class="form-group form_small_block float_right">
					<label>申请人：</label>
					<xsl:element name="INPUT">
						<xsl:attribute name="type">text</xsl:attribute>
						<xsl:attribute name="name">staff</xsl:attribute>
						<xsl:attribute name="value"><xsl:value-of select="/html/Body/staff"/></xsl:attribute>
						<xsl:attribute name="class">form-control input-sm</xsl:attribute>
					</xsl:element>
				</div>
				<div class="form-group form_small_block float_right">
					<label>申请日期：</label>
					<xsl:element name="INPUT">
						<xsl:attribute name="type">text</xsl:attribute>
						<xsl:attribute name="name">application_date</xsl:attribute>
						<xsl:attribute name="value"><xsl:value-of select="/html/Body/application_date"/></xsl:attribute>
						<xsl:attribute name="class">form-control input-sm</xsl:attribute>
					</xsl:element>
				</div>
				<div class="form-group form_small_block float_right" role="alert" id='alert' >
				  <span id='warning'></span>
				</div>
			</form>
		</div>
		<table width="1180" class="table table-bordered table-hover">
			<tr>
				<th class="table_th_number">序号</th>
				<th class="table_th_checkbox center"><input name="select_one" type="checkbox"/></th>
				<th width="136">申请日期</th>
				<th width="70">状态</th>
				<th width="120">采购单编号</th>
				<th width="130">供应商</th>
				<th width="120">仓库</th>
				<th width="100">采购数量</th>
				<th width="100">采购总价</th>
				<th width="220">摘要</th>
				<th width="120">申请人</th>
			</tr>
			<xsl:for-each select="/html/Body/purchase_main/ul/li">
				<tr>
					<td class="center"><xsl:value-of select="position()"/></td>
					<td class="center"><input name="select_all" type="checkbox"/></td>
					<td><xsl:value-of select="action_date"/></td>
					<td class="status">
						<xsl:if test="status_audit= '待审核'">
						<xsl:element name="span">
							<xsl:attribute name="style"></xsl:attribute>
							<xsl:value-of select="status_audit"/>
						</xsl:element>
						</xsl:if>
						<xsl:if test="status_audit= '待修改'">
						<xsl:element name="span">
							<xsl:attribute name="style">color:#eda93a</xsl:attribute>
							<xsl:value-of select="status_audit"/>
						</xsl:element>
						</xsl:if>
					</td>
					<td>
						<xsl:if test="status_audit= '待审核'">
						<xsl:element name="A">
							<xsl:attribute name="href">/purchase/purchase_detail_N.php?id=<xsl:value-of select="id"/>
							</xsl:attribute>
							<xsl:value-of select="number"/>
						</xsl:element>
						</xsl:if>
						<xsl:if test="'待修改' = status_audit">
						<xsl:element name="A">
							<xsl:attribute name="style">color:#eda93a</xsl:attribute>
							<xsl:attribute name="href">/purchase/purchase_detail_R.php?id=<xsl:value-of select="id"/>
							</xsl:attribute>
							<xsl:value-of select="number"/>
						</xsl:element>
						</xsl:if>
					</td>
					<td><xsl:value-of select="supplier_id"/></td>
					<td><xsl:value-of select="store_id"/></td>
					<td><xsl:value-of select="total"/></td>
					<td><xsl:value-of select="price"/></td>
					<td><xsl:value-of select="brief"/></td>
					<td><xsl:value-of select="staff_id"/></td>
					<xsl:element name="INPUT">
						<xsl:attribute name="type">hidden</xsl:attribute>
						<xsl:attribute name="name">id</xsl:attribute>
						<xsl:attribute name="value"><xsl:value-of select="id"/></xsl:attribute>
						<xsl:attribute name="class">main_id</xsl:attribute>
					</xsl:element>
				</tr>
			</xsl:for-each>
		</table>
		<xsl:if test="/html/Body/purchase_main/ul/@total = '0'">
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
</script>
</xsl:template>

</xsl:stylesheet>