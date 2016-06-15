<?xml version="1.0" encoding="utf-8" ?>
<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<!-- UTF8 编码 -->

<xsl:include href="/style/header.xsl" />

<xsl:template name="text">
<script type="text/javascript" src="/js_encode/jquery.twbsPagination.min.js"></script>
<script type="text/javascript" src="/js_encode/order_sale_service.js"></script>
<script>
	$(function(){
		$('.delete').click(function(){
			var con = confirm("您确定要删除吗?");
			if(con){
				return true;
			}else{
				return false;
			}
		})
	})
</script>
<div class="mainBody">

	<div id="myTabContent" class="tab-content">
	   	<div class="tab-pane fade in active" id="classify">
	   		<div class="float_left">
   				<div class="table_operate_block">
	   				<h4></h4>
					<input class="btn btn-default btn-sm btn_margin abnormalAdd" onclick="MessageBox('order_add_unusual.php', '添加异常', 300, 75); return false" type="button" value="添加" />
				</div>
				<table style="width:1200px" class="table table-bordered table-abnormal ttbale  table-hover">
					<tr>
						<th class="table_th_number">序号</th>
						<th class="table_th_operate_2">操作</th>
						<th class="center">异常类型</th>
					</tr>
				<xsl:for-each select="/html/Body/yichang/ul/li">
					<tr>
						<td class="center"><xsl:value-of select="num"/></td>
						<td class="center">
							<xsl:element name="A">
								<xsl:attribute name="href">javascript:;</xsl:attribute>
								<xsl:attribute name="class">table_a_operate</xsl:attribute>
								<xsl:attribute name="onclick">MessageBox('/order/order_edit_unusual.php?id=<xsl:value-of select="id"/>', '修改分类', 300, 75)</xsl:attribute>
								<xsl:text>修改</xsl:text>
							</xsl:element>
							<xsl:element name="A">
								<xsl:attribute name="href">/order/order_setting.php?id=<xsl:value-of select="id" /></xsl:attribute>
								<xsl:attribute name="class">delete button</xsl:attribute>
								<xsl:text>删除</xsl:text>
							</xsl:element>
						</td>
						<td class="Unit_Name"><xsl:value-of select="name" /></td>
					</tr>
				</xsl:for-each>
				</table>
			</div>
		</div>
	</div>
</div>
</xsl:template>

</xsl:stylesheet>
