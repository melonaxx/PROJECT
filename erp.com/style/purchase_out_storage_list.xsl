<?xml version="1.0" encoding="utf-8" ?>
<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<!-- UTF8 编码 -->

<xsl:include href="/style/header.xsl" />

<xsl:template name="text">

<style>
</style>
<div class="mainBody">
	<form class="form-inline" action="" method="">
		<div class="storageMsg">
			<div class="table_operate_block">
				<div class="form-group" style="margin:0; float:right;">
					<div class="input-group">
						<input type="text" class="form-control input-sm input_search" placeholder="输入编码或申请人等"/>
						<span class="input-group-btn">
							<button class="btn btn-default btn-sm" type="button">搜索</button>
						</span>
					</div>
				</div>
				<div class="form-group form_small_block" style="float:right;">
					<label>到货状态：</label>
					<select class="form-control input-sm" style="width:80px;" name="type">
						<xsl:for-each select="/html/Body/status/ul/li">
							<xsl:element name="OPTION">
							   <xsl:attribute name="value"><xsl:value-of select="value"/></xsl:attribute>
							   <xsl:value-of select="text"/>
							</xsl:element>
						</xsl:for-each>
					</select>
				</div>
				
			</div>
			<table class="table table-bordered table-hover">
				<tr>
					<th class="table_th_number">序号</th>
					<th class="table_th_operate_1">操作</th>
					<th>申请日期</th>
					<th>采购单编码</th>
					<th>申请人</th>
					<th>申请部门</th>
					<th style="width:250px;">采购摘要</th>
					<th>供应商</th>
					<th>收货仓库</th>
					<th>采购总额</th>
					<th>到货状态</th>
				</tr>
				<xsl:for-each select="/html/Body/storageMsg/ul/li">
				<tr>
					<td class="center">1</td>
					<td class="center"><a href="/purchase/purchase_out_storage_detail.php">退货出库</a></td>
					<td>2015-08-20</td>
					<td>UC201508200003</td>
					<td>赵四</td>
					<td>销售部</td>
					<td>商品1</td>
					<td>供应商2</td>
					<td><!--<xsl:value-of select="mobile" />-->北京仓库</td>
					<td>￥22.00</td>
					<td>部分到货</td>
				</tr>
				</xsl:for-each>
			</table>
		</div>	
	</form>
</div>
</xsl:template>

</xsl:stylesheet>
