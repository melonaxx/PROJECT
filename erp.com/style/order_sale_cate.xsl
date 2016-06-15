<?xml version="1.0" encoding="utf-8" ?>
<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<!-- UTF8 编码 -->

<xsl:include href="/style/header.xsl" />

<xsl:template name="text">
<script type="text/javascript" src="/js_encode/jquery.twbsPagination.min.js"></script>
<script type="text/javascript" src="/js_encode/order_sale_service.js"></script>

<script type="text/javascript">
	$(function(){
		var abc = '<xsl:value-of select="/html/Body/menu/big" />';
		$('.delete').click(function(){
			var con = confirm("您确定要删除吗?");
			if(con){
				return true;
			}else{
				return false;
			}
		});
	})
	
</script>

<div class="mainBody">
	<ul id="myTab" class="nav nav-tabs">
	   <li><a href="order_sale_service.php">新建售后单</a></li>
	   <li><a href="order_sale_deal.php">待处理售后单</a></li>
	   <li><a href="order_sale_list.php">售后单查询</a></li>
	   <li><a href="order_sale_ruku_list.php">销售退货入库</a></li>
	   <li class="active"><a href="#unit" data-toggle="tab">售后分类</a></li>
	</ul>
	<div class="table_operate_block">
		<h4></h4>
		<input class="btn btn-default btn-sm btn_margin" onclick="MessageBox('order_sale_addcate.php', '添加分类', 300, 75); return false" type="button" value="添加" />
	</div>
	<table style="width:1200px" class="table table-bordered table-hover">
		<tr>
			<th class="table_th_number">序号</th>
			<th class="table_th_operate_2">操作</th>
			<th class="center">分类名称</th>
		</tr>
		<xsl:for-each select="/html/Body/sales/ul/li">
			<tr>
				<td class="center">
					<xsl:value-of select="position()"/>
					<xsl:element name="INPUT">
						<xsl:attribute name="type">hidden</xsl:attribute>
						<xsl:attribute name="name">topic_id</xsl:attribute>
						<xsl:attribute name="value"><xsl:value-of select="id"/></xsl:attribute>
						<xsl:attribute name="class">form-control input-sm </xsl:attribute>
					</xsl:element>
				</td>
				<td class="center">
				<xsl:element name="A">
					<xsl:attribute name="href">javascript:;</xsl:attribute>
					<xsl:attribute name="class">table_a_operate</xsl:attribute>
					<xsl:attribute name="onclick">MessageBox('/order/order_sale_editcate.php?id=<xsl:value-of select="id"/>', '修改分类', 300, 75)</xsl:attribute>
					<xsl:text>修改</xsl:text>
				</xsl:element>
				<xsl:element name="A">
					<xsl:attribute name="href">/order/order_sale_deletecate.php?id=<xsl:value-of select="id"/></xsl:attribute>
					<xsl:attribute name="class">delete button</xsl:attribute>
					<xsl:text>删除 </xsl:text>
				</xsl:element>
				</td>
				<td class="Unit_Name"><xsl:value-of select="name"/></td>
			</tr>
		</xsl:for-each>
	</table>
	<xsl:if test="/html/Body/sales/@total = '0'">
		<div class="imgs" style="margin:0 auto; width:100%;text-align:center">
			<div class="img">
				<img src="/images/empty.jpg"  alt=""/>
				<span>没有找到记录，请调整搜索条件。</span>
			</div>
		</div>
	</xsl:if>
	<xsl:call-template name="page"></xsl:call-template>
			
</div>
</xsl:template>

</xsl:stylesheet>