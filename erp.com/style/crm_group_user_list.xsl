<?xml version="1.0" encoding="utf-8" ?>
<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<!-- UTF8 编码 -->

<xsl:include href="/style/header.xsl" />

<xsl:template name="text">
	
<link rel="stylesheet" href="/style/xin_basic.css"></link>
<script type="text/javascript" src="/js_encode/crm_group_user_list.js"></script>

<div class="mainBody">
	
	<form class="form-inline">
		
		<div class="supplierMsg">
			<div class="table_operate_block">
				<div class="form-group" style="margin:0; float:right;">
					<div class="input-group">
						<input type="text" class="form-control input-sm" id="exampleInputAmount" style="width:320px;" placeholder="请输入供应商名称或编码" />
						<div class="input-group-addon">搜索</div>
					</div>
				</div>
				<input class="btn btn-default btn-sm btn_margin supplierAdd" type="button" value="添加" />
				<input class="btn btn-default btn-sm supplierDelete" type="button" value="删除" />
			</div>
			<table class="table table-bordered">
				<tr>
					<th class="table_th_checkbox center"><input type="checkbox" name="select_all" /></th>
					<th class="table_th_number">序号</th>
					<th class="table_th_operate">操作</th>
					<th style="width:165px;">供应商名称</th>
					<th>供应商类型</th>
					<th style="width:260px;">网站网址</th>
					<th>联系人</th>
					<th>手机</th>
					<th style="width:160px;">备注</th>
				</tr>
				
				<tr>
					<td class="center"><input type="checkbox" name="select_one" /></td>
					<td class="center">1</td>
					<td class="center"><a href="javascript:;" class="table_a_operate">查看</a><a href="javascript:;">删除</a></td>
					<td>制造厂</td>
					<td>成品供应商</td>
					<td>http://www.baidu.com</td>
					<td>张小北</td>
					<td>15216108922</td>
					<td>100件以上</td>
				</tr>
			</table>
		</div>
		
	</form>
	
	
</div>
	
</xsl:template>

</xsl:stylesheet>
