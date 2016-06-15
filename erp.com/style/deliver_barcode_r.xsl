<?xml version="1.0" encoding="utf-8" ?>
<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<!-- UTF8 编码 -->
<xsl:include href="/style/header.xsl" />
<xsl:template name="text">
<script type="text/javascript" src="/js_encode/deliver_barcode_inspection.js"></script>
<style type="text/css">
	.box{
		width:780px;
		height:600px;
		margin-left:0;
	}
	.smallbox{
		width:360px;
		height:300px;
		float:right;
		<!-- margin-right:80px; -->
		margin-top:80px;
	}
	.two a:visited{
		color:red;	
	}
	table,tr,th,td{
		border:1px solid #ddd;
	}
	.del td,th{
		padding-left:6px;
	}
	
</style>
<div class="mainBody">
	<ul id="myTab" class="nav nav-tabs nav_tabs_margin">
	   <li>
		  <a href="deliver_barcode_inspection.php">条码验货</a>
	   </li>
	   <li  class="active"><a href="deliver_barcode_r.php">待处理</a></li>
	</ul>
	<div id="myTabContent" class="tab-content">


		<div class="tab-pane fade in active" id="reser_voirarea">
			<div class="headMsg table_operate_block">
				<form class="form-inline" method="get" action="deliver_barcode_r.php">
					<div class="form-group float_right margin0">
						<div class="input-group">
							<input type="text" style="width:300px;" class="form-control input-sm" placeholder="输入订单编号" name="find" />
							<span class="input-group-btn">
								<button class="btn btn-default btn-sm">搜索</button>
							</span>
						</div>
					</div>
				</form>
			</div>
			<div>
				<table style="width:1200px;" class="table tab-sel table-bordered table-hover">
					<tr>
						<th class="table_th_number">序号</th>
						<th>快递单号</th>
						<th>快递公司</th>
						<th>买家留言</th>
						<th>订单备注</th>
						<th>数量</th>
						<th>总金额</th>
						<th>仓库</th>
						<th>店铺名称</th>
						<th>订单编号</th>
					</tr>
					<xsl:for-each select="/html/Body/OrderReview/ul/li">
					<tr>
						<td class="table_th_number"><xsl:value-of select="num"/></td>
						<td><xsl:value-of select="number"/></td>
						<td><xsl:value-of select="kuaidi"/></td>
						<td><xsl:value-of select="beizhu"/></td>
						<td><xsl:value-of select="message"/></td>
						<td><xsl:value-of select="total"/></td>
						<td><xsl:value-of select="theory_amount"/></td>
						<td><xsl:value-of select="store"/></td>
						<td><xsl:value-of select="shop"/></td>
						<td><xsl:value-of select="bind_number"/></td>
					</tr>
					</xsl:for-each>
				</table>
				<xsl:if test="/html/Body/OrderReview/ul/@total = '0'">
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
	</div>
</div>
</xsl:template>

</xsl:stylesheet>