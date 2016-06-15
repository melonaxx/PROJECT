<?xml version="1.0" encoding="utf-8" ?>
<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<!-- UTF8 编码 -->

<xsl:include href="/style/header.xsl" />


<xsl:template name="text">
<script type="text/javascript" src="/js_encode/order_custom.js"></script>


<style type="text/css">

</style>

	<!-- 提示框 -->
	<div id="confirm" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none;width:268px;margin:65px auto">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
				<h4 class="modal-title" id="myModalLabel">提示</h4>
			</div>
			<div class="modal-body" style="margin-left:20px">请至少选择<span class="number">1</span>个订单
			</div>
			<div class="modal-footer"><button type="button" class="btn btn-default btn-sm ok" data-dismiss="modal">确定</button></div>
		</div>
	</div>
<div class="mainBody">
	<ul class="nav nav-tabs nav_tabs_margin">
		<li><a href="order_custom.php">待确认</a></li>
		<li><a href="order_custom_r.php">待定制</a></li>
		<li class="active"><a href="order_custom_y.php">已完成</a></li>
	</ul>
		<div class="tab-pane" id="completed">
	   		<div class="form-inline" >
				<div class="completedMsg">
	   				<form method="get" action="order_custom_y.php">
					<div class="table_operate_block">
						<div class="form-group float_right margin0">
							<div class="input-group">
								<xsl:element name="input">
									<xsl:attribute name="type">text</xsl:attribute>
									<xsl:attribute name="style">width:300px</xsl:attribute>
									<xsl:attribute name="class">form-control input-sm input_search</xsl:attribute>
									<xsl:attribute name="name">find</xsl:attribute>
									<xsl:attribute name="placeholder">输入订单号/收件人等</xsl:attribute>
									<xsl:attribute name="value">
										<xsl:value-of select="/html/Body/find"/>
									</xsl:attribute>
								</xsl:element>
								<span class="input-group-btn">
									<button class="btn btn-default btn-sm" >搜索</button>
								</span>
							</div>
						</div>
					</div>
					</form>
					<table style="width:1200px" class="table table-bordered table-hover">
						<tr>
							<th class="table_th_number">序号</th>
							<th class="table_th_checkbox center"><input type="checkbox" name="select_all" /></th>
							<th style="width:48px;" class="table_th_number">操作</th>
							<th class="table_th_number" style="width:98px">打印标记</th>
							<th style="width:132px;">买家留言</th>
							<th style="width:132px;">订单备注</th>
							<th style="width:67px;">发货仓库</th>
							<th style="width:67px;">快递</th>
							<th style="width:93px;">买家</th>
							<th style="width:93px;">手机</th>
							<th style="width:71px;">数量</th>
							<th style="width:128px;">定制内容</th>
							<th style="width:120px;">附件</th>
						</tr>
						<xsl:for-each select="/html/Body/sure/ul/li">
						<tr>
							<td class="center"><xsl:value-of select="position()"/></td>
							<td class="center">
								<input type="checkbox" name="select_one" />
								<xsl:element name="INPUT">
									<xsl:attribute name="type">hidden</xsl:attribute>
									<xsl:attribute name="name">id[]</xsl:attribute>
									<xsl:attribute name="value">
										<xsl:value-of select='id'/>
									</xsl:attribute>
								</xsl:element>
							</td>
							<td class="center">
								<xsl:element name="A">
									<xsl:attribute name="class">table_a_operate</xsl:attribute>
									<xsl:attribute name="href">order_detail.php?id=<xsl:value-of select = 'id'/></xsl:attribute>
								<xsl:text>详细</xsl:text>
								</xsl:element>
							</td>
							<td><xsl:value-of select="biaoji" /></td>
							<td><xsl:value-of select="beizhu" /></td>
							<td><xsl:value-of select="message" /></td>
							<td><xsl:value-of select="store" /></td>
							<td><xsl:value-of select="kuaidi" /></td>
							<td><xsl:value-of select="name" /></td>
							<td><xsl:value-of select="mobile" /></td>
							<td><xsl:value-of select="num" /></td>
							<td><xsl:value-of select="body" /></td>
							<td>下载</td>
						</tr>
						</xsl:for-each>
					</table>

					<xsl:if test="/html/Body/sure/ul/@total = '0'">
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
