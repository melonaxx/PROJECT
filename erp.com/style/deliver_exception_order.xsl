<?xml version="1.0" encoding="utf-8" ?>
<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<!-- UTF8 编码 -->

<xsl:include href="/style/header.xsl" />

<xsl:template name="text">
<script type="text/javascript" src="/js_encode/deliver_exception_order.js"></script>
<script>
	$(function(){
		//关闭订单
		$('.shutdown').click(function(){
			if($(".table_select input[type='checkbox']").is(':checked')){
				$('#confirm .modal-body').html("您确定要关闭订单吗?");
				$('#confirm').modal('show');
				$('.ok').click(function(){
					var arr = [];
					$('table tr td:nth-child(2)').find('input[name=select_one]:checked').each(function(){
						var v = $(this).parent().find('input[name=id]').val();
						arr += v+",";
					})
					$.ajax({
						url:'deliver_exception_order.php',
						type:'get',
						data:{'audit':arr},
						dataType:'json',
						success:function(data){
							if(data == 1){
								window.location.href="deliver_exception_order.php";
							}else{
								alert('关闭订单失败');
							}
						}
					})
				});

			}else{
				$('#confirm').modal('show');
				$('#print-menu').hide();
				return false;
			}
		});

		//恢复正常
		$('.back').click(function(){
			if($(".table_select input[type='checkbox']").is(':checked')){
				$('#confirm .modal-body').html("您确定恢复订单吗?");
				$('#confirm').modal('show');
				$('.ok').click(function(){
					var arr = [];
					$('table tr td:nth-child(2)').find('input[name=select_one]:checked').each(function(){
						var v = $(this).parent().find('input[name=id]').val();
						arr += v+",";
					})

					$.ajax({
						url:'deliver_exception_order.php',
						type:'get',
						data:{'back_id':arr},
						dataType:'json',
						success:function(data){
							if(data == 1){
								window.location.href="deliver_exception_order.php";
							}else{
								alert('恢复订单失败');
							}
						}
					})
				})
			}else{
				$('#confirm').modal('show');
				$('#print-menu').hide();
				return false;
			}
		});


		//修改异常
		$('.modify').click(function(){
			if($(".table_select input[type='checkbox']").is(':checked')){
				var a = getData();
				MessageBox('/deliver/deliver_modifying_exception.php?id='+a, '修改异常',495,170);
				return false;
			}else{
				$('#confirm').modal('show');
				$('#print-menu').hide();
				return false;
			}
		});

		//修改备注
		$('.beizhu').click(function(){
			if($(".table_select input[type='checkbox']").is(':checked')){
				var a = getData();
				MessageBox('/deliver/deliver_modifying_remarks.php?id='+a, '修改备注',495,170);
				return false;
			}else{
				$('#confirm').modal('show');
				$('#print-menu').hide();
				return false;
			}
		});

		//获取订单ID
		function getData(){
			var a = '';
			$('.table tr td:nth-child(2)').find('input[name=select_one]:checked').each(function(){
				a += $(this).next().val()+",";
			});
			return a;
		}
	})
</script>
	<div class="mainBody">
		<div class="headMsg table_operate_block">
			<form class="form-inline" method="get" action="deliver_exception_order.php">
				<button class="btn btn-default btn-sm btn_margin shutdown" type="button">关闭订单</button>
				<button class="btn btn-default btn-sm btn_margin back" type="button">恢复正常</button>
				<button class="btn btn-default btn-sm btn_margin modify" type="button">修改异常</button>
				<button class="btn btn-default btn-sm beizhu" type="button">修改备注</button>
				<div class="form-group float_right margin0">
					<div class="input-group">
						<input type="text" style="width:300px;" class="form-control input-sm" placeholder="输入订单号/旺旺/收件人等" name="find" />
						<span class="input-group-btn">
							<button class="btn btn-default btn-sm">搜索</button>
						</span>
					</div>
				</div>
			</form>
		</div>
		<div>

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

			<table style="width:1200px;" class="table table_select table-bordered table-hover">
				<tr>
					<th class="table_th_number">序号</th>
					<th class="table_th_checkbox center"><input type="checkbox" name="select_all" /></th>
					<th style="width:48px;" class="table_th_number">操作</th>
					<th class="table_th_number" style="width:80px;">所在位置</th>
					<!-- <th class="table_th_number">提醒</th>
					<th class="table_th_number">标记</th> -->
					<th style="width:120px;">买家留言</th>
					<th style="width:132px;">订单备注</th>
					<th style="width:75px;">发货仓库</th>
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
						<!-- <a href=order_list_audit_detail.php?id=<xsl:value-of select ='id'/> >详细</a> -->
						<xsl:element name="A">
							<xsl:attribute name="class">table_a_operate</xsl:attribute>
							<xsl:attribute name="href">deliver_edit_order.php?id=<xsl:value-of select = 'id'/></xsl:attribute>
						<xsl:text>详细</xsl:text>
						</xsl:element>
					</td>
					<!-- <td><xsl:value-of select="remind" /></td>
					<td><xsl:value-of select="biaoji" /></td> -->
					<td><xsl:value-of select="audit" /></td>
					<td><xsl:value-of select="beizhu" /></td>
					<td><xsl:value-of select="message" /></td>
					<td><xsl:value-of select="store" /></td>
					<td><xsl:value-of select="kuaidi" /></td>
					<td><xsl:value-of select="name" /></td>
					<td><xsl:value-of select="mobile" /></td>
					<td><xsl:value-of select="num" /></td>
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