<?xml version="1.0" encoding="utf-8" ?>
<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<!-- UTF8 编码 -->

<xsl:include href="/style/header.xsl" />

<xsl:template name="text">
	<script type="text/javascript" src="/js_encode/deliver_express_list.js"></script>
	<div class="mainBody">
		<div class="headMsg table_operate_block">
			<form class="form-inline">
				<button class="btn btn-default btn-sm btn_margin" onclick="MessageBox('/deliver/deliver_add_express.php', '新增快递公司',470,156)" type="button">新增</button>
				<div class="form-group float_right margin0">
					<div class="input-group">
						<input type="text" style="width:300px;" class="form-control input-sm" placeholder="输入订单号/旺旺/收件人等" />
						<span class="input-group-btn">
							<button class="btn btn-default btn-sm" type="button">搜索</button>
						</span>
					</div>
				</div>
			</form>
		</div>
		<div>
			<table class="table table-bordered table-hover">
				<tr>
					<th class="table_th_number">序号</th>
					<th style="width:150px;">操作</th>
					<th style="width:80px;">电子面单</th>
					<th>快递编码</th>
					<th>联系电话</th>
					<th>快递名称</th>
					<th style="width:150px;">打印模板</th>
				</tr>
				<tr>
					<td class="center">1</td>
					<td><a class="table_a_operate" href="javascript:;">修改</a><a class="table_a_operate" href="javascript:;">运费设置</a><a href="javascript:;">停用</a></td>
					<td><a class="start" href="javascript:;">开启</a></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td class="center">2</td>
					<td><a class="table_a_operate" href="javascript:;">修改</a><a class="table_a_operate" href="javascript:;">运费设置</a><a href="javascript:;">停用</a></td>
					<td><a class="start" href="javascript:;">开启</a></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					<td class="center">3</td>
					<td><a class="table_a_operate" href="javascript:;">修改</a><a class="table_a_operate" href="javascript:;">运费设置</a><a href="javascript:;">停用</a></td>
					<td><a class="start" href="javascript:;">开启</a></td>
					<td></td>
					<td></td>
					<td></td>
					<td></td>
				</tr>
			</table>
			<xsl:call-template name="page"></xsl:call-template>
		</div>
	</div>

</xsl:template>

</xsl:stylesheet>