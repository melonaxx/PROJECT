<?xml version="1.0" encoding="utf-8" ?>
<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<!-- UTF8 编码 -->

<xsl:include href="/style/header.xsl" />

<xsl:template name="text">
<script type="text/javascript" src="/js_encode/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript" src="/js_encode/bootstrap-datetimepicker.zh-CN.js"></script>
<link rel="stylesheet" href="/style/bootstrap-datetimepicker.min.css"></link>
<style>
	span{display:inline-block;width:120px;line-height:25px;margin-right:40px}
	label{margin-left:4px;font-weight:normal}
</style>
<div class="mainBody">
	<ul class="nav nav-tabs nav_tabs_margin">
		<li class="active"><a href="">角色修改</a></li>
	</ul>
	<div class="table_operate_block form-inline form-group">
		<!-- <form action="/setting/setting_record.php" method="get">
			<div class="form-group" style="margin:0; float:right;">
				<p>
					<button class="btn btn-default btn-sm btn_margin" type="submit">查询</button>
						<input  name="clear" class="btn btn-default btn-sm" type="button" value='清空' />
				</p>
			</div>
			<div class="form-group form_small_block float_right">
				<label>姓名：</label>

				<span > 
				<xsl:element name="input">
					<xsl:attribute name="type">text</xsl:attribute>
					<xsl:attribute name="class">form-control input-sm</xsl:attribute>
					<xsl:attribute name="name">name</xsl:attribute>
				</xsl:element>
			</div>
			<div class="form-group form_small_block float_right">
				<label>部门：</label>

				<span > 
				<select class="form-control input-sm" name="group_id" id="department">
					<xsl:for-each select="/html/Body/companystaffgroup/ul/li">
						<xsl:element name="OPTION">
							<xsl:attribute name="value"><xsl:value-of select="value"/></xsl:attribute>
							<xsl:if test="value=/html/Body/group_id"><xsl:attribute name="selected">selected</xsl:attribute></xsl:if>
							<xsl:value-of select="text"/>
						</xsl:element>
					</xsl:for-each>
				</select>
			</div>
			<div class="form-group form_small_block float_right">
				<label>时间：</label>

				<span > 
				<xsl:element name="input">
					<xsl:attribute name="type">text</xsl:attribute>
					<xsl:attribute name="class">form-control input-sm</xsl:attribute>
					<xsl:attribute name="value"><xsl:value-of select="/html/Body/action_date"/></xsl:attribute>
					<xsl:attribute name="name">action_date</xsl:attribute>
				</xsl:element>
			</div>
		</form> -->
		<div>
			<h4>操作权限</h4>

			<div class=" form-group margin_left_block">
				<label style="margin-left:0px">角色名称：</label>
				<input class="form-control input-sm" type="text" />
			</div>
			<div class=" form-group margin_left_block">
				<label>备注：</label>
				<input class="form-control input-sm merger_two_row_4" type="text" />
			</div>
			<table width="1200" class="table table-bordered">
				<tr>
					<td width='200px' style="text-align:center">
						<input id="首页" style="margin-top:0px;" class="check_own" type="checkbox"/><label for="首页">首页</label>
					</td>
					<td width='1000px' style="vertical-align:middle; ">
						<span > 
							<input id="1" style="margin-top:0px;" type="checkbox"/><label for="1">通知添加</label>
						</span>
					</td>
				</tr>	
				<tr>
					<td width='200px' style="text-align:center">
						<input id="订单" style="margin-top:0px;" class="check_own" type="checkbox"/><label for="订单">订单</label>
					</td>
					<td width='1000px' style="vertical-align:middle; ">
						<span >
							<input id="2" style="margin-top:0px;" type="checkbox"/><label for="2">新增订单</label>
						</span>

						<span > 
							<input id="3" style="margin-top:0px;" type="checkbox"/><label for="3">订单审核</label>
						</span>

						<span > 
							<input id="4" style="margin-top:0px;" type="checkbox"/><label for="4">定制订单</label>
						</span>

						<span > 
							<input id="5" style="margin-top:0px;" type="checkbox"/><label for="5">未付款订单</label>
						</span>

						<span > 
							<input id="6" style="margin-top:0px;" type="checkbox"/><label for="6">售后服务</label>
						</span>

						<span > 
							<input id="7" style="margin-top:0px;" type="checkbox"/><label for="7">订单查询</label>
						</span>

						<span > 
							<input id="8" style="margin-top:0px;" type="checkbox"/><label for="8">异常标记设计</label>
						</span>
					</td>
				</tr>
				<tr>
					<td width='200px' style="text-align:center">
						<input id="发货" style="margin-top:0px;" class="check_own" type="checkbox"/><label for="发货">发货</label>
					</td>
					<td width='1000px' style="vertical-align:middle; ">
						<span > 
							<input id="9" style="margin-top:0px;" type="checkbox"/><label for="9">打单配货</label>
						</span>
						<span > 
							<input id="条码验货" style="margin-top:0px;" type="checkbox"/><label for="条码验货">条码验货</label>
						</span>
						<span > 
							<input id="11" style="margin-top:0px;" type="checkbox"/><label for="11">称重计费</label>
						</span>

						<span > 
							<input id="12" style="margin-top:0px;" type="checkbox"/><label for="12">扫单发后</label>
						</span>

						<span > 
							<input id="13" style="margin-top:0px;" type="checkbox"/><label for="13">异常订单</label>
						</span>

						<span > 
							<input id="14" style="margin-top:0px;" type="checkbox"/><label for="14">待发货</label>
						</span>

						<span > 
							<input id="15" style="margin-top:0px;" type="checkbox"/><label for="15">已发货</label>
						</span>
					</td>
				</tr>
				<tr>
					<td width='200px' style="text-align:center">
						<input id="库存" style="margin-top:0px;" type="checkbox"/><label for="库存">库存</label>
					</td>
					<td width='1000px' style="vertical-align:middle; ">
						<span >
							<input id="16" style="margin-top:0px;" type="checkbox"/><label for="16">库存状况</label>
						</span>
						<span > 
							<input id="入库出库" style="margin-top:0px;" type="checkbox"/><label for="入库出库">入库出库</label>
						</span>
						<span > 
							<input id="库存调拨" style="margin-top:0px;" type="checkbox"/><label for="库存调拨">库存调拨</label>
						</span>
						<span > 
							<input id="库存盘点" style="margin-top:0px;" type="checkbox"/><label for="库存盘点">库存盘点</label>
						</span>
						<span > 
							<input id="库存预警" style="margin-top:0px;" type="checkbox"/><label for="库存预警">库存预警</label>
						</span>
						<span > 
							<input id="仓库设置" style="margin-top:0px;" type="checkbox"/><label for="仓库设置">仓库设置</label>
						</span>
					</td>
				</tr>
				<tr>
					<td width='200px' style="text-align:center">
						<input id="商品" style="margin-top:0px;" type="checkbox"/><label for="商品">商品</label>
					</td>
					<td width='1000px' style="vertical-align:middle; ">
						<span > 
							<input id="商品录入" style="margin-top:0px;" type="checkbox"/><label for="商品录入">商品录入</label>
						</span>
						<span > 
							<input id="商品列表" style="margin-top:0px;" type="checkbox"/><label for="商品列表">商品列表</label>
						</span>
						<span > 
							<input id="组合商品" style="margin-top:0px;" type="checkbox"/><label for="组合商品">组合商品</label>
						</span>
						<span > 
							<input id="分类和品牌" style="margin-top:0px;" type="checkbox"/><label for="分类和品牌">分类和品牌</label>
						</span>
						<span > 
							<input id="商品对应关系" style="margin-top:0px;" type="checkbox"/><label for="商品对应关系"
						>商品对应关系</label>
						</span>
						<span > 
							<input id="规格和属性" style="margin-top:0px;" type="checkbox"/><label for="规格和属性">规格和属性</label>
						</span>
					</td>
				</tr>
				<tr>
					<td width='200px' style="text-align:center">
						<input id="采购" style="margin-top:0px;" type="checkbox"/><label for="采购">采购</label>
					</td>
					<td width='1000px' style="vertical-align:middle; ">
						
						<span > 
							<input id="添加采购单" style="margin-top:0px;" type="checkbox"/><label for="添加采购单">添加采购单</label>
						</span>
						<span > 
							<input id="待审核采购单" style="margin-top:0px;" type="checkbox"/><label for="待审核采购单">待审核采购单</label>
						</span>
						<span > 
							<input id="采购入库" style="margin-top:0px;" type="checkbox"/><label for="采购入库">采购入库</label>
						</span>
						<span > 
							<input id="入库出库单据列表" style="margin-top:0px;" type="checkbox"/><label for="入库出库单据列表">入库出库单据列表</label>
						</span>
						<span > 
							<input id="采购单查询" style="margin-top:0px;" type="checkbox"/><label for="采购单查询">采购单查询</label>
						</span>
					</td>
				</tr>
				<tr>
					<td width='200px' style="text-align:center">
						<input id="CRM" style="margin-top:0px;" type="checkbox"/><label for="CRM">CRM</label>
					</td>
					<td width='1000px' style="vertical-align:middle; ">
						<span > 
							<input id="客户列表" style="margin-top:0px;" type="checkbox"/><label for="客户列表">客户列表</label>
						</span>
						<span > 
							<input id="供应商" style="margin-top:0px;" type="checkbox"/><label for="供应商">供应商</label>
						</span>
						<span > 
							<input id="渠道设置" style="margin-top:0px;" type="checkbox"/><label for="渠道设置">渠道设置</label>
						</span>
						<span > 
							<input id="短信渠道" style="margin-top:0px;" type="checkbox"/><label for="短信渠道">短信渠道</label>
						</span>
						<span > 
							<input id="中差评管理" style="margin-top:0px;" type="checkbox"/><label for="中差评管理">中差评管理</label>
						</span>
					</td>
				</tr>	
				<tr>
					<td width='200px' style="text-align:center">
						<input id="财务" style="margin-top:0px;" type="checkbox"/><label for="财务">财务</label>
					</td>
					<td width='1000px' style="vertical-align:middle; ">
						
						<span > 
							<input id="资金划拨" style="margin-top:0px;" type="checkbox"/><label for="资金划拨">资金划拨</label>
						</span>
						<span > 
							<input id="资金流水" style="margin-top:0px;" type="checkbox"/><label for="资金流水">资金流水</label>
						</span>
						<span > 
							<input id="订单流水" style="margin-top:0px;" type="checkbox"/><label for="订单流水">订单流水</label>
						</span>
						<span > 
							<input id="开票订单" style="margin-top:0px;" type="checkbox"/><label for="开票订单">开票订单</label>
						</span>
						<span > 
							<input id="账户管理" style="margin-top:0px;" type="checkbox"/><label for="账户管理">账户管理</label>
						</span>
					</td>
				</tr>
				<tr>
					<td width='200px' style="text-align:center">
						<input id="管理" style="margin-top:0px;" class="check_own" type="checkbox"/><label for="管理">管理</label>
					</td>
					<td width='1000px' style="vertical-align:middle; ">
						<span > 
							<input id="人员管理" style="margin-top:0px;" type="checkbox"/><label for="人员管理">人员管理</label>
						</span>
						<span > 
							<input id="角色权限" style="margin-top:0px;" type="checkbox"/><label for="角色权限">角色权限</label>
						</span>
						<span > 
							<input id="考勤记录" style="margin-top:0px;" type="checkbox"/><label for="考勤记录">考勤记录</label>
						</span>
						<span > 
							<input id="快递公司" style="margin-top:0px;" type="checkbox"/><label for="快递公司">快递公司</label>
						</span>
						<span > 
							<input id="模板设置" style="margin-top:0px;" type="checkbox"/><label for="模板设置">模板设置</label>
						</span>
						<span > 
							<input id="店铺管理" style="margin-top:0px;" type="checkbox"/><label for="店铺管理">店铺管理</label>
						</span>
					</td>
				</tr>	
			</table>
		</div>
		<div>
			<h4>数据权限</h4>
			<p>
				<span > 
					<input id="查看客户手机" style="margin-top:0px;" type="checkbox"/><label for="查看客户手机">查看客户手机</label>
				</span>
				<span > 
					<input id="修改客户地址" style="margin-top:0px;" type="checkbox"/><label for="修改客户地址">修改客户地址</label>
				</span>
				<span > 
					<input id="查看成本价" style="margin-top:0px;" type="checkbox"/><label for="查看成本价">查看成本价</label>
				</span>
				<span > 
					<input id="修改成本价" style="margin-top:0px;" type="checkbox"/><label for="修改成本价">修改成本价</label>
				</span>
				<span > 
					<input id="修改快递成本" style="margin-top:0px;" type="checkbox"/><label for="修改快递成本">修改快递成本</label>
				</span>
			</p>
		</div>
	</div>
	<input type="submit" class="btn btn-default btn-sm btn_margin" value="保存"/>
</div>
<script>
$(function(){
	$(".check_own").change(function(){
		if(this.checked){
			$(this).parents("tr").find("td:eq(1) input[type=checkbox]").prop("checked",true);
		}else{
			$(this).parents("tr").find("td:eq(1) input[type=checkbox]").prop("checked",false);
		}
	});
	
	$('table tr ').find('td:eq(1) input[type=checkbox]').change(function(){
		var allChecked = false;
		$(this).parents('td').find('input[type=checkbox]').each(function(){
			if(this.checked){
				allCheck = true;				
			}else{
				allCheck = false;
				return false;
			}
		})
		if(allCheck == true){
			$(this).parents("tr").find('.check_own').prop("checked",true);
		}else{
			$(this).parents("tr").find('.check_own').prop("checked",false);
		}
	})
	
})
</script>

</xsl:template>

</xsl:stylesheet>
