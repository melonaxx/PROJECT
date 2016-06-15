<?xml version="1.0" encoding="utf-8" ?>
<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<!-- UTF8 编码 -->
<xsl:include href="/style/header.xsl" />

<xsl:template name="text">
<style>
	.tpl{
		margin-left:50px;
	}
</style>
<script type="text/javascript">
$(function(){
	$('.images').hover(function(){
		var t = $(this).position().top - 90 + 'px';
		var l = $(this).position().left + 20 + 'px';
		$(this).next().css("display","block");
		$(this).next().css("top",t);
		$(this).next().css("left",l);
	},function(){
		$(this).next().css("display","none");
	})
})
</script>
	<script type="text/javascript" src="/js_encode/product_correspondence.js"></script>
	<div class="modal fade" id="confirm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
				<h4 class="modal-title" id="myModalLabel">提示</h4>
			</div>
			<div class="modal-body">
	  			确定解除商品对应关系吗？
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default btn-sm ok" data-dismiss="modal">确定</button>
				<button type="button" class="btn btn-default btn-sm cancel" data-dismiss="modal">取消</button>
			</div>
		</div>
	</div>
</div>
	<div class="mainBody">
		<div class="table_operate_block form-inline">
			<form action="" method="get">
				<input class="btn btn-default btn-sm btn_margin float_left" type="button" onclick="MessageBox('/product/product_select_shop.php', '选择店铺', 300, 150)" value="选择店铺"/>
				<div style="padding-top:7px;float:left">已选择店铺：
					<xsl:value-of select="/html/Body/shop"/>	
				</div>
				<div style="margin-left:20px;float:left">
					<a href="product_select_update.php" class="btn btn-default btn-sm btn_margin float_left">更新当前店铺商品信息</a>
				</div>
				<div class="form-group" style="margin:0; float:right;">
					<div class="input-group">
						<button class="btn btn-default btn-sm" type="submit">查询</button>
					</div>
				</div>
				<div class="form-group form_small_block float_right">
					<label>系统名称：</label>
					<xsl:element name="INPUT">
						<xsl:attribute name="type">text</xsl:attribute>
						<xsl:attribute name="name"></xsl:attribute>
						<xsl:attribute name="class">form-control input-sm</xsl:attribute>
					</xsl:element>
				</div>
				<div class="form-group form_small_block float_right">
					<label>线上名称：</label>
					<xsl:element name="INPUT">
						<xsl:attribute name="type">text</xsl:attribute>
						<xsl:attribute name="name">find</xsl:attribute>
						<xsl:attribute name="class">form-control input-sm</xsl:attribute>
					</xsl:element>
				</div>
			</form>
		</div>
<!-- 		<div style="width:552px;float:right">
			<table class="table table-bordered table-hover">
				<tr>
					<th class="table_th_number">序号</th>
					<th style="width:42px;">图片</th>
					<th>系统名称</th>
					<th>规格</th>
				</tr>
				<tr>
					<td class="center">1</td>
					<td><img src="dafsad"/></td>
					<td>测试商品001</td>
					<td>白色：12GB</td>
				</tr>
			</table>
		</div> -->
		<table style="width:1200px;" class="table table-bordered table-hover">
			<tr>
				<th class="table_th_number">序号</th>
				<th style="width:42px;">图片</th>
				<th style="width:150px;">线上名称</th>
				<th style="width:150px;">规格</th>
				<th colspan="6" class="center">操作</th>
				<th style="width:42px;">图片</th>
				<th style="width:150px;">系统名称</th>
				<th style="width:150px;">规格</th>
			</tr>
			<xsl:for-each select="/html/Body/online/ul/li">
			<tr>
				<td class="table_th_number"><xsl:value-of select="num"/></td>
				<td style="width:42px;">
					<xsl:element name="IMG">
						<xsl:attribute name="src"><xsl:value-of select="pic_url"/></xsl:attribute>
						<xsl:attribute name="width">20</xsl:attribute>
						<xsl:attribute name="height">20</xsl:attribute>
						<xsl:attribute name="class">images center</xsl:attribute>
					</xsl:element>
					<xsl:element name="IMG">
						<xsl:attribute name="src"><xsl:value-of select="pic_url"/></xsl:attribute>
						<xsl:attribute name="width">200</xsl:attribute>
						<xsl:attribute name="height">200</xsl:attribute>
						<xsl:attribute name="style">display:none;position:absolute</xsl:attribute>
						<xsl:attribute name="class">bigimg center</xsl:attribute>
					</xsl:element>
				</td>
				<td style="width:150px;"><xsl:value-of select="title"/></td>
				<td style="width:150px;"><xsl:value-of select="format"/></td>
				<td colspan="6" class="center">
					<xsl:element name="A">
						<xsl:attribute name="class">btn btn-default tpl</xsl:attribute>
						<xsl:attribute name="href">javascript:;</xsl:attribute>
						<xsl:attribute name="onclick">MessageBox('product_modify.php?id=<xsl:value-of select="id"/>', '修改商品对应关系', 510, 150)</xsl:attribute>
						<xsl:text>改</xsl:text>
					</xsl:element>　　　　
					<!-- <xsl:element name="A">
						<xsl:attribute name="class">btn btn-default</xsl:attribute>
						<xsl:attribute name="href">product_corr_fire.php?id=<xsl:value-of select="id" /></xsl:attribute>
						<xsl:text>解</xsl:text>
					</xsl:element> -->　　　　
					<xsl:element name="A">
						<xsl:attribute name="class">btn btn-default</xsl:attribute>
						<xsl:attribute name="href">product_update.php?product_id=<xsl:value-of select="product_id"/>&amp;num_iid=<xsl:value-of select="num_iid" />&amp;format_list=<xsl:value-of select="format_list" /></xsl:attribute>
						<xsl:text>更</xsl:text>
					</xsl:element>　　　　
				</td>
				<td style="width:42px;">
					<xsl:element name="IMG">
						<xsl:attribute name="src"><xsl:value-of select="image"/></xsl:attribute>
						<xsl:attribute name="width">20</xsl:attribute>
						<xsl:attribute name="height">20</xsl:attribute>
						<xsl:attribute name="class">images center</xsl:attribute>
					</xsl:element>
					<xsl:element name="IMG">
						<xsl:attribute name="src"><xsl:value-of select="image"/></xsl:attribute>
						<xsl:attribute name="width">200</xsl:attribute>
						<xsl:attribute name="height">200</xsl:attribute>
						<xsl:attribute name="style">display:none;position:absolute</xsl:attribute>
						<xsl:attribute name="class">bigimg center</xsl:attribute>
					</xsl:element>
				</td>
				<td style="width:150px;"><xsl:value-of select="name"/></td>
				<td style="width:150px;"><xsl:value-of select="value_1"/>、<xsl:value-of select="value_2"/></td>
			</tr>
			</xsl:for-each>
		</table>
	<!-- 	<div style="width:554px;">
			<table class="table table-bordered table-hover">
				<tr>
					<th class="table_th_number">序号</th>
					<th style="width:42px;">图片</th>
					<th>线上名称</th>
					<th>规格</th>
				</tr>
				<tr>
					<td class="center">1</td>
					<td><img src="dsaf"/></td>
					<td>测试商品001</td>
					<td>测试商品002</td>
				</tr>
			</table>
		</div> -->
		<!-- <div style="width:74px;float:right;margin-right:562px;margin-top:-89px;">
			<table class="table table-bordered table-hover">
				<tr>
					<th class="center">操作</th>
				</tr>
				<tr>
					<td><a style="margin-right:5px;" href="javascript:;"><span onclick="MessageBox('/product/product_edit_shop.php', '修改',660,166)">修改</span></a><a class="delete" href="javascript:;">解除</a></td>
				</tr>
			</table>
		</div> -->
	</div>
	<xsl:if test="/html/Body/online/ul/@total = '0'">
		<div class="imgs" style="margin:0 auto; width:100%;text-align:center">
			<div class="img">
				<img src="/images/empty.jpg"  alt=""/>
				<span>没有找到记录，请调整搜索条件。</span>
			</div>
		</div>
	</xsl:if>
	<xsl:call-template name="page"></xsl:call-template>
</xsl:template>

</xsl:stylesheet>