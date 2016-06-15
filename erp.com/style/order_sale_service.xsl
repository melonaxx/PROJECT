<?xml version="1.0" encoding="utf-8" ?>
<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<!-- UTF8 编码 -->

<xsl:include href="/style/header.xsl" />

<xsl:template name="text">
<script type="text/javascript" src="/js_encode/jquery.twbsPagination.min.js"></script>
<script type="text/javascript" src="/js_encode/order_sale_service.js"></script>

<script type="text/javascript">
	var abc = '<xsl:value-of select="/html/Body/menu/big" />';
	$(function(){
		$('.table').on('click','.souhou',function(){
			var v = $(this).next().val();
			$.ajax({
				url:'order_sale_service.php',
				data:{'order':v},
				type:'post',
				dataType:'json',
				success:function(data){
					if(data == 1){
						MessageBox('/order/order_new_sales.php?id='+v, '新建售后单',970,510);
						return false;
					}else{
						$('#confirm').css('display','block');
						$('.modal-backdrop').css('display','block');
						$('#confirm .modal-body').html("此订单已经创建过售后单,您确定要继续吗?");
						$('#confirm').modal('show');
						$('.ok').click(function(){
							MessageBox('/order/order_new_sales.php?id='+v, '新建售后单',970,510);
							$('#confirm').css('display','none');
							$('.modal-backdrop').css('display','none');
							return false;
						})
					}
				}
			})
		})
	})
</script>

<div class="mainBody">
	<ul id="myTab" class="nav nav-tabs">
	   <li class="active"><a href="order_sale_service.php">新建售后单</a></li>
	   <li><a href="order_sale_deal.php">待处理售后单</a></li>
	   <li><a href="order_sale_list.php">售后单查询</a></li>
	   <li><a href="order_sale_ruku_list.php">销售退货入库</a></li>
	   <li><a href="order_sale_cate.php">售后分类</a></li>
	</ul>
	<div id="myTabContent" class="tab-content">
		<div class="tab-pane fade in active" id="classify">
			<div class="mainBody">
				<div class="headMsg table_operate_block">
					<form class="form-inline">
						<!-- <button class="btn btn-default btn-sm btn_margin order_select exception souhou" type="button">新建售后单</button> -->
						<div class="form-group float_right margin0">
							<div class="input-group">
								<xsl:element name="input">
									<xsl:attribute name="type">text</xsl:attribute>
									<xsl:attribute name="style">width:300px</xsl:attribute>
									<xsl:attribute name="class">form-control input-sm input_search</xsl:attribute>
									<xsl:attribute name="name">find</xsl:attribute>
									<xsl:attribute name="placeholder">输入订单编号/旺旺/收件人等</xsl:attribute>
									<xsl:attribute name="value">
										<xsl:value-of select="/html/Body/find"/>
									</xsl:attribute>
								</xsl:element>
								<span class="input-group-btn">
									<button class="btn btn-default btn-sm">搜索</button>
								</span>
							</div>
						</div>
					</form>
				</div>
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
				<div>
					<table style="width:1200px;" class="table tab_select table-bordered table-hover">
						<tr>
							<th class="table_th_number">序号</th>

							<th style="width:93px;" class="table_th_number">操作</th>
							<th style="width:98px;">已售后</th>
							<!-- <th class="table_th_number" style="width:98px;">打印标记</th> -->
							<th style="width:132px;">买家留言</th>
							<th style="width:132px;">订单备注</th>
							<th style="width:67px;">发货仓库</th>
							<th style="width:67px;">快递</th>
							<th style="width:93px;">买家</th>
							<th style="width:93px;">手机</th>
							<th style="width:71px;">数量</th>
							<th style="width:128px;">订单编号</th>
							<th style="width:120px;">店铺</th>
						</tr>
						<xsl:for-each select="/html/Body/OrderReview/ul/li">
						<tr>
							<td class="center"><xsl:value-of select="position()"/></td>
							<td class="center">
								<xsl:element name="A">
									<xsl:attribute name="class">table_a_operate</xsl:attribute>
									<xsl:attribute name="href">order_detail.php?id=<xsl:value-of select = 'id'/></xsl:attribute>
									<xsl:text>详细</xsl:text>
								</xsl:element>
								<xsl:element name="A">
									<xsl:attribute name="class">table_a_operate souhou</xsl:attribute>
									<xsl:attribute name="href">#</xsl:attribute>
									<xsl:attribute name="type">button</xsl:attribute>
									<xsl:text>售后</xsl:text>
								</xsl:element>
								<xsl:element name="INPUT">
									<xsl:attribute name="type">hidden</xsl:attribute>
									<xsl:attribute name="name">id</xsl:attribute>
									<xsl:attribute name="value">
										<xsl:value-of select='id'/>
									</xsl:attribute>
								</xsl:element>
							</td>
							<td>
								<xsl:value-of select="customer_time" /> 次
							</td>
							<!-- <td>
								<font color="blue" class="mark_express"><xsl:value-of select="mark_express" /></font>
								<font color="green" class="mark_deliver"><xsl:value-of select="mark_deliver" /></font>
								<font color="orange" class="mark_order"><xsl:value-of select="mark_order" /></font>
							</td> -->
							<td><xsl:value-of select="beizhu" /></td>
							<td><xsl:value-of select="message" /></td>
							<td><xsl:value-of select="store" />
								<xsl:element name="INPUT">
									<xsl:attribute name="type">hidden</xsl:attribute>
									<xsl:attribute name="name">store</xsl:attribute>
									<xsl:attribute name="value">
										<xsl:value-of select='store'/>
									</xsl:attribute>
								</xsl:element>
							</td>
							<td><xsl:value-of select="kuaidi" /></td>
							<td><xsl:value-of select="name" /></td>
							<td><xsl:value-of select="mobile" /></td>
							<td><xsl:value-of select="num" />
							<xsl:element name="INPUT">
									<xsl:attribute name="type">hidden</xsl:attribute>
									<xsl:attribute name="name">num</xsl:attribute>
									<xsl:attribute name="value">
										<xsl:value-of select='num'/>
									</xsl:attribute>
								</xsl:element>
							</td>
							<td><xsl:value-of select="number" /></td>
							<td><xsl:value-of select="shop" /></td>
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

</div>
</xsl:template>

</xsl:stylesheet>