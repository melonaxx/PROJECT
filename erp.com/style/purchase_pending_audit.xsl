<?xml version="1.0" encoding="utf-8" ?>
<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<!-- UTF8 编码 -->

<xsl:include href="/style/header.xsl" />

<xsl:template name="text">
	
<link rel="stylesheet" href="/style/xin_basic.css"></link>
<script type="text/javascript" src="/js_encode/purchase_list.js"></script>

<style>
</style>

<div class="mainBody">
	<form class="form-inline">
		<div class="customersMsg">
			<div class="table_operate_block">
				<input class="btn btn-default btn-sm btn_margin customersAdd" type="button" value="添加" />
				<input class="btn btn-default btn-sm customersDelete" type="button" value="打印" />
				<div class="form-group" style="margin:0; float:right;">
					<div class="input-group">
						<input type="text" class="form-control input-sm" placeholder="输入编码或申请人等"/>
						<span class="input-group-btn">
							<button class="btn btn-default btn-sm" type="button">搜索</button>
						</span>
					</div>
				</div>
				
			</div>
			<table class="table table-bordered table-hover">
				<tr>
					<th class="table_th_number">序号</th>
					<th class="table_th_checkbox center"><input type="checkbox" name="select_all" /></th>
					<th class="table_th_operate center">操作</th>
					<th class="center" style="width:120px;">审核</th>
					<th>申请日期</th>
					<th>采购单编码</th>
					<th>申请人</th>
					<th>申请部门</th>
					<th>商品名称</th>
					<th>供应商</th>
					<th>收货仓库</th>
					<th>采购数量</th>
					<th style="width:80px;">单位</th>
					<th>采购总额</th>
				</tr>
				<!--<xsl:for-each select="/html/Body/customersMsg/ul/li">-->
				<tr>
					<td class="center">1</td>
					<td class="center"><input type="checkbox" name="select_one" /></td>
					<td class="center"><a href="javascript:;" class="table_a_operate">详细</a><a href="javascript:;" class="table_a_operate">打印</a></td>
					<td class="center"><a href="javascript:;" class="table_a_operate_l">通过</a><a href="javascript:;" class="table_a_operate_l">修改</a><a href="javascript:;" class="table_a_operate_l">拒绝</a></td>
					<td>2015-08-20</td>
					<td>UC201508200003</td>
					<td>赵四</td>
					<td>销售部</td>
					<td>商品1</td>
					<td>供应商2</td>
					<td><!--<xsl:value-of select="mobile" />-->北京仓库</td>
					<td>1000</td>
					<td>
						<select class="form-control input-sm form_no_border">
						  	<option value="0">个</option>
							<option value="1">件</option>
							<option value="2">卷</option>
							<option value="3">批</option>
							<option value="4">盒</option>
							<option value="5">箱</option>
							<option value="7">米</option>
							<option value="6">kg</option>
						</select>
					</td>
					<td>￥1000</td>
				</tr>
				<!--</xsl:for-each>-->
			</table>
		</div>	
	</form>
</div>
</xsl:template>

</xsl:stylesheet>
