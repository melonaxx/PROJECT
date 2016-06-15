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
		$('.headMsg .souhou').click(function(){
			if($(".table input[type='radio']").is(':checked')){
				var v = $('.table tr td:nth-child(2)').find('input[name=id]:checked').val();
				$.ajax({
					url:'order_sale_service.php',
					data:{'order':v},
					type:'post',
					success:function(data){
						if(data==1){
							MessageBox('/order/order_new_sales.php?id='+v, '新建售后单',970,510);
						}else{
								$('#confirm .modal-body').html("此订单已经创建过售后单,您确定要继续吗?");
								$('#confirm').modal('show');
								$('#print-menu').hide();
								$('.ok').click(function(){
									MessageBox('/order/order_new_sales.php?id='+v, '新建售后单',970,510);
								})

						}
					}
				})
			}else{
				$('#confirm .modal-body').html("请至少选择1个订单");
				$('#confirm').modal('show');
				$('#print-menu').hide();
				return false;
			}

		})

		//入库
		$('.ruku').click(function(){

			var a = $(this).parent().prev().find('input[name=order_id]').val();
			var b = $(this).parent().prev().find('input[name=after_id]').val();
			<!-- var b = $(this).parent().next().next().html(); -->
			MessageBox('/order/order_sale_ruku.php?id='+a+'&amp;after_id='+b, '商品入库',900,400);
			return false;

		})

		//记帐
		$('.refunds').click(function(){

			var a = $(this).parent().prev().find('input[name=order_id]').val();
			var b = $(this).parent().prev().find('input[name=after_id]').val();
			var c = $(this).parents('tr').find('input[name=payment]').val().replace(/[^0-9\.]/ig,'');
			<!-- var b = $(this).parent().next().next().html(); -->
			MessageBox('/order/order_sale_refunds.php?id='+a+'&amp;after_id='+b+'&amp;payment='+c, '退款记帐',600,400);
			return false;

		})

		//关闭订单
		$('.guanbi').click(function(){

			if($(".table input[type='checkbox']").is(':checked')){
				$('#confirm .modal-body').html("您确定要关闭此订单售后吗?");
				$('#confirm').modal('show');
				$('.ok').click(function(){
					var arr = "";
					$('table tr td:nth-child(2)').find('input[name=select_one]:checked').each(function(){
						var v = $(this).next().next().val();
						arr += v+",";
					});
					$.ajax({
						url:'order_sale_deal.php',
						type:'get',
						dataType:'json',
						data:{'id':arr},
						success:function(data){
							if(data == '1'){
								window.location.href="order_sale_deal.php";
							}else{
								alert('关闭订单失败');
							}
						}
					})
				})

			}else{
				$('#confirm .modal-body').html("请至少选择1个订单");
				$('#confirm').modal('show');
				$('#print-menu').hide();
				return false;
			}
		})

		//解决
		$('.solve').click(function(){
			if($(".table input[type='checkbox']").is(':checked')){
				$('#confirm .modal-body').html("您确定此订单售后吗?");
				$('#confirm').modal('show');
				$('.ok').click(function(){
					var arr = "";
					$('table tr td:nth-child(2)').find('input[name=select_one]:checked').each(function(){
						var v = $(this).next().next().val();
						arr += v+",";
					});
					$.ajax({
						url:'order_sale_deal.php',
						type:'get',
						dataType:'json',
						data:{'after_id':arr},
						success:function(data){
							if(data == '1'){
								window.location.href="order_sale_deal.php";
							}else{
								alert('解决订单失败');
							}
						}
					})
				})
			}else{
				$('#confirm .modal-body').html("请至少选择1个订单");
				$('#confirm').modal('show');
				$('#print-menu').hide();
				return false;
			}
		})

	})
</script>

<div class="mainBody">
	<ul id="myTab" class="nav nav-tabs">
	   <li><a href="order_sale_service.php">新建售后单</a></li>
	   <li class="active"><a href="#commodity">待处理售后单</a></li>
	   <li><a href="order_sale_list.php">售后单查询</a></li>
	   <li><a href="order_sale_ruku_list.php">销售退货入库</a></li>
	   <li><a href="order_sale_cate.php">售后分类</a></li>
	</ul>
<div id="myTabContent" class="tab-content">
	<div class="tab-pane fade in active" id="commodity">
		<div class="mainBody">
			<div class="form-inline" >
				<div class="goodsMsg" style="clear:both;">
					<form method="get" action="">
						<div class="table_operate_block">
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
							<a href="#"><input class="btn btn-default btn-sm btn_margin solve" type="button" value="已解决" /></a>
							<input class="btn btn-default btn-sm guanbi" type="button" value="关闭" />
						</div>
					</form>
					<!-- 提示框 -->
					<div id="confirm" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none;width:268px;margin:65px auto">
						<div class="modal-content">
							<div class="modal-header">
								<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
								<h4 class="modal-title" id="myModalLabel">提示</h4>
							</div>
							<div class="modal-body" style="margin-left:20px">请至少选择<span class="number">1</span>个订单
							</div>
							<div class="modal-footer">
								<button type="button" class="btn btn-default btn-sm ok" data-dismiss="modal">确定</button>
								<input class="btn btn-default btn-sm  margin_left_0" type="button" onclick="parent.$('#confirm').modal('hide')"  value="取消"/>
							</div>

						</div>
					</div>

					<table style="width:1200px" class="table table-bordered table-hover tab_Pending">
						<tr>
							<th class="table_th_number">序号</th>
							<th class="table_th_checkbox center">
								<input name="select_all" type="checkbox"/>
							</th>
							<th class="center"  style="width:76px;">操作</th>
							<!-- <th style="width:47px;">处理</th> -->
							<th style="width:120px;">单据编号</th>
							<th style="width:67px;">售后类型</th>
							<th style="width:111px;">退款金额</th>
							<th class="table_th_operate_1">收件人</th>
							<th class="table_th_operate_2">电话</th>
							<th class="table_th_operate_2">退回快递</th>
							<th style="width:146px;">退回单号</th>
							<th style="width:71px;">操作人</th>
							<th class="table_th_operate_1">售后分类</th>
							<th style="width:121px;">店铺</th>
						</tr>
							<xsl:for-each select="/html/Body/sales/ul/li">
								<tr>
									<td class="center"><xsl:value-of select="position()"/></td>
									<td class="center">
										<input type="checkbox" name="select_one"/>
										<xsl:element name="INPUT">
											<xsl:attribute name="type">hidden</xsl:attribute>
											<xsl:attribute name="name">order_id</xsl:attribute>
											<xsl:attribute name="class">form-control input-sm form_no_border</xsl:attribute>
											<xsl:attribute name="value"><xsl:value-of select="order_id"/></xsl:attribute>
										</xsl:element>

										<xsl:element name="INPUT">
											<xsl:attribute name="type">hidden</xsl:attribute>
											<xsl:attribute name="name">after_id</xsl:attribute>
											<xsl:attribute name="class">form-control input-sm form_no_border</xsl:attribute>
											<xsl:attribute name="value"><xsl:value-of select="id"/></xsl:attribute>
										</xsl:element>
									</td>
									<td class="center">
										<xsl:if test="service_type = 'Exchange'">
											<a href="" class="ruku" type="button">入库</a>
										</xsl:if>
										<xsl:if test="service_type = 'Return'">
											<a href="" class="ruku" type="button">
												入库
											</a>
										</xsl:if>
										<xsl:if test="service_type = 'Exchange'">
											<xsl:element name="A">
												<xsl:attribute name="href">order_sale_add.php?order_id=<xsl:value-of select="order_id"/>&amp;after_id=<xsl:value-of select="order_id"/></xsl:attribute>
												<xsl:attribute name="name">order_id</xsl:attribute>
													下单
											</xsl:element>
										</xsl:if>
										<xsl:if test="service_type = 'Delivery'">
											<xsl:element name="A">
												<xsl:attribute name="href">order_sale_add.php?order_id=<xsl:value-of select="order_id"/>&amp;after_id=<xsl:value-of select="order_id"/></xsl:attribute>
												<xsl:attribute name="name">order_id</xsl:attribute>
													下单
											</xsl:element>
										</xsl:if>
										<xsl:if test="service_type = 'Refunds'">
											<a href="" class="refunds" type="button">记账</a>
										</xsl:if>
										<xsl:if test="service_type = 'Return'">
											<a href="" class="refunds" type="button">
												记账
											</a>
										</xsl:if>
									</td>
									<!-- <td>2</td> -->
									<td><xsl:value-of select="id"/></td>
									<td><xsl:value-of select="service_name"/></td>
									<td>
										￥<xsl:value-of select="payment"/>
										<xsl:element name="INPUT">
											<xsl:attribute name="type">hidden</xsl:attribute>
											<xsl:attribute name="name">payment</xsl:attribute>
											<xsl:attribute name="class">form-control input-sm form_no_border</xsl:attribute>
											<xsl:attribute name="value"><xsl:value-of select="payment"/></xsl:attribute>
										</xsl:element>
									</td>
									<td><xsl:value-of select="name"/></td>
									<td><xsl:value-of select="phone"/></td>
									<td><xsl:value-of select="ename"/></td>
									<td><xsl:value-of select="number"/></td>
									<td><xsl:value-of select="staff_name"/></td>
									<td><xsl:value-of select="topic_name"/></td>
									<td><xsl:value-of select="shop_name"/></td>
								</tr>
							</xsl:for-each>
					</table>
				</div>
			</div>
				<xsl:if test="/html/Body/sales/ul/@total = '0'">
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