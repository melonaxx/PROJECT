<?xml version="1.0" encoding="utf-8" ?>
<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<!-- UTF8 编码 -->

<xsl:include href="/style/header.xsl" />

<xsl:template name="text">
<script type="text/javascript" src="/js_encode/deliver_to_delivery.js"></script>
<script>
	$(function(){
		//发货
		$('.fahuo').click(function(){
			if($(".tab_select input[type='checkbox']").is(':checked')){
				$('#confirm .modal-body').html("你确定要发货吗?");
				$('#confirm').modal('show');
				$('.ok').click(function(){
					var arr = "";
					$('table tr td:nth-child(2)').find('input[name=select_one]:checked').each(function(){
						var v = $(this).parent().find('input[name=id]').val();
						arr += v+",";
					});
					$.ajax({
						url:'deliver_to_delivery.php',
						type:'get',
						dataType:'json',
						data:{'status':arr},
						success:function(data){
							if(data == 1){
								window.location.href="deliver_to_delivery.php";
							}else{
								alert('发货失败');
							}
						}
					})
				})
			}else{
				$('#confirm').modal('show');
				$('#print-menu').hide();
				return false;
			}

		})
		function getData(){
			var a = '';
			$('.table tr td:nth-child(2)').find('input[name=select_one]:checked').each(function(){
				a += $(this).next().val()+",";
			});
			return a;
		}

		$('.bei').click(function(){
			if($(".tab_select input[type='checkbox']").is(':checked')){
				var a = getData();
				MessageBox('/deliver/deliver_modify_remarks.php?id='+a, '修改备注',489,167);
			}else{
				$('#confirm .modal-body').html("请至少选择1个订单");
				$('#confirm').modal('show');
				$('#print-menu').hide();
				return false;
			}
		})

		//打回审核
		$('.shen').click(function(){
			if($(".tab_select input[type='checkbox']").is(':checked')){
				$('#confirm .modal-body').html("你确定要打回审核吗?");
				$('#confirm').modal('show');
				$('.ok').click(function(){
					var arr = "";
					$('table tr td:nth-child(2)').find('input[name=select_one]:checked').each(function(){
						var v = $(this).parent().find('input[name=id]').val();
						arr += v+",";
					});
					$.ajax({
						url:'deliver_to_delivery.php',
						type:'get',
						dataType:'json',
						data:{'audit':arr},
						success:function(data){
							if(data == 1){
								window.location.href="deliver_to_delivery.php";
							}else{
								alert('打回审核失败');
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
		});

		//打回配货
		$('.pei').click(function(){
			if($(".tab_select input[type='checkbox']").is(':checked')){
				$('#confirm .modal-body').html("你确定要打回配货吗?");
				$('#confirm').modal('show');
				$('.ok').click(function(){
					var arr = "";
					$('table tr td:nth-child(2)').find('input[name=select_one]:checked').each(function(){
						var v = $(this).parent().find('input[name=id]').val();
						arr += v+",";
					});

					$.ajax({
						url:'deliver_to_delivery.php',
						type:'get',
						dataType:'json',
						data:{'pei_id':arr},
						success:function(data){
							if(data == 1){
								window.location.href="deliver_to_delivery.php";
							}else{
								alert('打回配货失败');
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
		});

		//打回验货
		$('.yan').click(function(){
			if($(".tab_select input[type='checkbox']").is(':checked')){
				$('#confirm .modal-body').html("你确定要打回验货吗?");
				$('#confirm').modal('show');
				$('.ok').click(function(){
					var arr = "";
					$('table tr td:nth-child(2)').find('input[name=select_one]:checked').each(function(){
						var v = $(this).parent().find('input[name=id]').val();
						arr += v+",";
					});
					$.ajax({
						url:'deliver_to_delivery.php',
						type:'get',
						dataType:'json',
						data:{'yan_id':arr},
						success:function(data){
							if(data == 1){
								window.location.href="deliver_to_delivery.php";
							}else{
								alert('打回验货失败');
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
		});

		//打回称重
		$('.cheng').click(function(){
			if($(".tab_select input[type='checkbox']").is(':checked')){
				$('#confirm .modal-body').html("你确定要打回称重吗?");
				$('#confirm').modal('show');
				$('.ok').click(function(){
					var arr = "";
					$('table tr td:nth-child(2)').find('input[name=select_one]:checked').each(function(){
						var v = $(this).parent().find('input[name=id]').val();
						arr += v+",";
					});
					$.ajax({
						url:'deliver_to_delivery.php',
						type:'get',
						dataType:'json',
						data:{'cheng_id':arr},
						success:function(data){
							if(data == 1){
								window.location.href="deliver_to_delivery.php";
							}else{
								alert('打回称重失败');
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
		});

		//提交异常
		$('.yichang').click(function(){
			var a = getData();
			if(a == ""){
				$('#confirm .modal-body').html("请至少选择1个订单");
				$('#confirm').modal('show');
				$('#print-menu').hide();
				return false;
			}
			MessageBox('/deliver/deliver_commit_exception.php?id='+a, '提交异常',416,185);
			return false;
		})


	})
</script>
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
		<div class="table_operate_blocks">
			<form class="form-inline" method="get" action="deliver_to_delivery.php">
				<button class="btn btn-default btn-sm btn_margin fahuo" type="button">发货</button>
				<div class="btn-group btn_margin">
					<button type="button" class="form-control input-sm" data-toggle="dropdown">打回<span class="caret"></span></button>
					<ul class="dropdown-menu" role="menu">
						<li><a style="font-size:12px;" href="#" class="shen">打回审核</a></li>
						<li><a style="font-size:12px;" href="#" class="pei">打回配货</a></li>
						<li><a style="font-size:12px;" href="#" class="yan">打回验货</a></li>
						<li><a style="font-size:12px;" href="#" class="cheng">打回称重</a></li>
					</ul>
				</div>
				<!-- <button class="btn btn-default btn-sm btn_margin" onclick="MessageBox('/deliver/deliver_commit_exception.php', '提交异常',416,185)" type="button">提交异常</button> -->
				<button class="btn btn-default btn-sm btn_margin yichang" type="button">提交异常</button>
				<button class="btn btn-default btn-sm btn_margin bei"  type="button">修改备注</button>
				<div class="form-group float_right margin0">
					<div class="input-group">
						<xsl:element name="input">
							<xsl:attribute name="type">text</xsl:attribute>
							<xsl:attribute name="style">width:300px</xsl:attribute>
							<xsl:attribute name="class">form-control input-sm</xsl:attribute>
							<xsl:attribute name="name">find</xsl:attribute>
							<xsl:attribute name="placeholder">输入订单号/收件人等</xsl:attribute>
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
		<div>
			<table style="width:1200px;" class="table tab_select table-bordered table-hover">
				<tr>
					<th class="table_th_number">序号</th>
					<th class="table_th_checkbox center"><input type="checkbox" name="select_all" /></th>
					<th style="width:48px;" class="table_th_number">操作</th>
					<th class="table_th_number">提醒</th>
					<th class="table_th_number">标记</th>
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
						<input type="checkbox" name="select_one" />
						<xsl:element name="INPUT">
							<xsl:attribute name="type">hidden</xsl:attribute>
							<xsl:attribute name="name">id</xsl:attribute>
							<xsl:attribute name="value">
								<xsl:value-of select='id'/>
							</xsl:attribute>
						</xsl:element>
					</td>
					<td class="center">
						<xsl:element name="A">
							<xsl:attribute name="class">table_a_operate</xsl:attribute>
							<xsl:attribute name="href">/order/order_detail.php?id=<xsl:value-of select = 'id'/></xsl:attribute>
						<xsl:text>详细</xsl:text>
						</xsl:element>
					</td>
					<td><xsl:value-of select="remind" /></td>
					<td><xsl:value-of select="biaoji" /></td>
					<td><xsl:value-of select="beizhu" /></td>
					<td><xsl:value-of select="message" /></td>
					<td><xsl:value-of select="store" />
						<xsl:element name="INPUT">
							<xsl:attribute name="type">hidden</xsl:attribute>
							<xsl:attribute name="name">store</xsl:attribute>
							<xsl:attribute name="value">
								<xsl:value-of select='store_id'/>
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

</xsl:template>

</xsl:stylesheet>