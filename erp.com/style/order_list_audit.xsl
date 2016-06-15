<?xml version="1.0" encoding="utf-8" ?>
<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<!-- UTF8 编码 -->

<xsl:include href="/style/header.xsl" />

<xsl:template name="text">

<script type="text/javascript" src="/js_encode/order_list_audit.js"></script>
<script type="text/javascript" src="http://g.tbcdn.cn/sj/securesdk/0.0.3/securesdk_v2.js" id="J_secure_sdk_v2" data-appkey="23283073"></script>
<script type="text/javascript">
	$(function(){
	<!-- 订单的审核 -->
		$('.shenhe').click(function(){
			if($(".tab_select input[type='checkbox']").is(':checked')){
				$('#confirm .modal-body').html("您确定要审核吗?");
				$('#confirm').modal('show');
				$('.ok').click(function(){
					var a = new Array();
					var store = new Array();
					$('table tr td:nth-child(2)').find('input[name=select_one]:checked').each(function(i){
						var v = $(this).parent().find('input[name=id]').val();
						var s = $(this).parents('tr').find('input[name=storeId]').val();
						store[i] 	= s;
						a[i]   		= v;
					});
					$('#con').modal('show');
					<!-- 验证仓库中的商品数量 -->
					if(a){
						$.ajax({
							url:'order_list_audit.php',
							type:'get',
							dataType:'json',
							data:{'storeId':store,'orderId':a},
							success:function(data){
								if(isArray(data)){
									MessageBox('/order/order_list_audit_NoStore.php?id='+data, '审核',600,450);
									return false;
								}else if(data == '1'){
									<!-- $('#con').modal('hide'); -->
									window.location.href="order_list_audit.php";
									return false;
								}else if(data == '0'){
									alert('修改失败！');
									return false;
								}
							},
						})
					}

				})
			}else{
				$('#confirm .modal-body').html("请至少选择1个订单");
				$('#confirm').modal('show');
				$('#print-menu').hide();
				return false;
			}
		});
		<!-- 判断是否是array -->
		function isArray(obj) {
		  return Object.prototype.toString.call(obj) === '[object Array]';
		}
		<!-- 获取订单ID的值 -->
		function getData(){
			var a = '';
			$('.table tr td:nth-child(2)').find('input[name=select_one]:checked').each(function(){
				a += $(this).next().val()+",";
			});
			return a;
		}

		<!-- 这是线上订单和线下订单的查询 -->
		var type = '<xsl:value-of select="/html/Body/type" />';
		if(type == "System"){
			$('.butt .butt_name').html("线下订单");
			$('.butt input[name=type]').val("System");
		}else if(type == ''){
			$('.butt .butt_name').html("全部订单");
			$('.butt input[name=type]').val("");
		}else if(type == 'online'){
			$('.butt .butt_name').html("线上订单");
			$('.butt input[name=type]').val("online");
		}

		$('.dropdown-menu .type').click(function(){
			var v = $(this).text();
			if(v == "线下订单"){
				$('.butt .butt_name').html(v);
				$('.butt input[name=type]').val("System");
			}else if(v == "线上订单"){
				$('.butt .butt_name').html(v);
				$('.butt input[name=type]').val("online");
			}else if(v == "全部订单"){
				$('.butt .butt_name').html(v);
				$('.butt input[name=type]').val("");
			}
		})

		<!-- //批量修改备注 -->
		$('.beizhu').click(function(){
			var a = getData();
			if(a == ""){
				$('#confirm .modal-body').html("请至少选择1个订单");
				$('#confirm').modal('show');
				$('#print-menu').hide();
				return false;
			}
			MessageBox('/order/order_list_audit_Notes.php?id='+a, '批量修改备注',410,255);
			return false;

		});

		<!-- //批量修改快递 -->
		$('.express').click(function(){
			var a = getData();
			if(a == ""){
				$('#confirm .modal-body').html("请至少选择1个订单");
				$('#confirm').modal('show');
				$('#print-menu').hide();
				return false;
			}

			MessageBox('/order/order_list_audit_express.php?id='+a, '批量修改快递',265,80);
			return false;

		});

		<!-- //批量修改仓库 -->
		$('.store').click(function(){
			var a = getData();
			if(a == ""){
				$('#confirm .modal-body').html("请至少选择1个订单");
				$('#confirm').modal('show');
				$('#print-menu').hide();
				return false;
			}
			MessageBox('/order/order_list_audit_warehouse.php?id='+a, '批量修改仓库',265,80);
			return false;

		});

		//拆单
		$('.chai').click(function(){
			var a = getData();
			if(a.split(',').length == 1){
				$('#confirm .modal-body').html("请选择一个订单！");
				$('#confirm').modal('show');
				$('#print-menu').hide();
				return false;
			}
			if(a.split(",").length > 2){
				$('#confirm .modal-body').html("所选订单数量必须为1");
				$('#confirm').modal('show');
				$('#print-menu').hide();
				return false;
			}else{
				$.ajax({
					url:'order_list_audit_detach.php',
					data:{'order':a},
					type:'post',
					success:function(data){
						if(data == 0){
							$('#confirm .modal-body').html("订单的商品数量必须大于2");
							$('#confirm').modal('show');
							$('#print-menu').hide();
							return false;

						}else if(data == 1){
							MessageBox('/order/order_list_audit_detach.php?id='+a, '手工拆单',800,600);
						}
					}
				});
			}



		});
		//合单
		$('.he').click(function(){
			var a = getData();
			if(a.split(",").length &lt; 3){
				$('#confirm .modal-body').html("请至少选择2个订单");
				$('#confirm').modal('show');
				$('#print-menu').hide();
				return false;
			}
			MessageBox('/order/order_list_audit_Merge.php?id='+a, '手工合单',890,220);
			return false;
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
			MessageBox('/order/order_list_audit_popup.php?id='+a, '异常原因',412,185);
			return false;
		})


	})
</script>
<style>
</style>

<div class="mainBody">
	<ul id="myTab" class="nav nav-tabs" style="margin-bottom:10px;">
	   <li class="active"><a href="order_list_audit.php">线上订单</a></li>
	   <li><a href="order_list_audit_system.php">线下订单</a></li>
	</ul>
	<form class="form-inline" method="get" action="">
		<div class="goodsMsg" style="clear:both;">
			<div class="table_operate_blocks">
				<div class="form-group float_right margin0">
					<!-- <div class="input-group">
						订单类型：
			        	<button type="button" class="btn btn-default btn_margin btn-sm butt" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="width:78px;">
			        	<span class="butt_name">全部订单</span>
						<xsl:element name='INPUT'>
							<xsl:attribute name="type">hidden</xsl:attribute>
							<xsl:attribute name="name">type</xsl:attribute>
							<xsl:attribute name="value"><xsl:value-of select="/html/Body/type"/></xsl:attribute>
						</xsl:element>
			        	<span class="caret"></span></button>
			        	<ul class="dropdown-menu dropdown-menu-left">
			          		<li><a href="#" class="type">全部订单</a></li>
			          		<li><a href="#" class="type">线上订单</a></li>
			         		<li><a href="#" class="type">线下订单</a></li>
			        	</ul>
			      	</div> -->
					<div class="input-group">
						<xsl:element name="input">
							<xsl:attribute name="type">text</xsl:attribute>
							<xsl:attribute name="style">width:200px</xsl:attribute>
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
				<div class="btn-group">
					<input class="btn btn-default btn-sm btn_margin shenhe" type="button" value="确认审核" />
				</div>
				<div class="btn-group">
						<button class="btn btn-default btn-sm btn_margin yichang" type="button">提交异常</button>
				</div>

				<div class="btn-group">
					<button type="button" class="form-control input-sm" data-toggle="dropdown">
					订单拆合<span class="caret"></span>
					</button>
					<ul class="dropdown-menu" role="menu">
						<li><a href="#" class="chai" style="font-size:12px;">手工拆单</a></li>
						<li><a href="#" class="he" style="font-size:12px;">手工合单</a></li>
					</ul>
				</div>

				<div class="btn-group margin_left_1">
					<button type="button" class="form-control input-sm" data-toggle="dropdown">
					批量修改<span class="caret"></span>
					</button>
					<ul class="dropdown-menu" role="menu">
						<li><a href="#" class="beizhu" style="font-size:12px;">批量修改备注</a></li>
						<li><a href="#" class="express" style="font-size:12px;">批量修改快递</a></li>
						<li><a href="#" class="store" style="font-size:12px;">批量修改仓库</a></li>
					</ul>
				</div>

				<div class="btn-group" style="margin-left:10px;">
						<a href="#" class="btn btn-default btn-sm btn_margin" onclick="MessageBox('order_select_shop.php', '选择店铺', 300, 120)" style="color: #555">下载订单</a>
				</div>


			</div>

			<!-- 进度条 -->
			<!-- <div id="con" class="modal fade" role="dialog" style="display: none;width:268px;heigth:400px;margin:200px auto"> -->
			<!-- 	<div class="modal-content">
					<span style="display: block;margin:20px 0 20px 70px">正在处理,请您耐心等待</span>
				<div class="progress">
				  	<div class="progress-bar progress-bar-striped active" role="progressbar" aria-valuenow="45" aria-valuemin="0" aria-valuemax="100" style="width: 0%">
				  	</div>
				</div>
				</div> -->
				<!-- <img src="https://img.alicdn.com/imgextra/i1/2456424883/TB2malOiVXXXXaZXXXXXXXXXXXX_!!2456424883.gif" style="width:260px;height:200px" alt=""/>
			</div> -->

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

			<!-- 仓库中商品数量验证提示框 -->
			<div id="proConfirm" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none;width:800px;margin:65px auto">
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

			<table style="width:1200px;" class="table tab_select table-bordered table-hover table-order-form">
				<tr>
					<th class="table_th_number">序号</th>
					<th class="table_th_checkbox center"><input type="checkbox" name="select_all" /></th>
					<th style="width:48px;" class="table_th_number">操作</th>
					<th class="table_th_number" style="width:60px;">标记</th>
					<th style="width:132px;">买家留言</th>
					<th style="width:100px;">订单备注</th>
					<th style="width:67px;">发货仓库</th>
					<th style="width:67px;">快递</th>
					<th style="width:130px;">买家</th>
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
							<xsl:attribute name="href">order_list_audit_detail.php?id=<xsl:value-of select = 'id'/></xsl:attribute>
						<xsl:text>详细</xsl:text>
						</xsl:element>
					</td>
					<td>
						<font><xsl:value-of select="split" /></font>
						<font><xsl:value-of select="merge" /></font>
						<font color="blue" class="mark_express"><xsl:value-of select="mark_express" /></font>
						<font color="green" class="mark_deliver"><xsl:value-of select="mark_deliver" /></font>
						<font color="orange" class="mark_order"><xsl:value-of select="mark_order" /></font>
					</td>
					<td><xsl:value-of select="beizhu" /></td>
					<td><xsl:value-of select="message" /></td>
					<td>
						<xsl:value-of select="store" />
						<xsl:element name="INPUT">
							<xsl:attribute name="type">hidden</xsl:attribute>
							<xsl:attribute name="name">storeId</xsl:attribute>
							<xsl:attribute name="value">
								<xsl:value-of select='storeId'/>
							</xsl:attribute>
						</xsl:element>
					</td>
					<td><xsl:value-of select="kuaidi" /></td>
					<td>
						<xsl:value-of select="name" />
						<xsl:if test="bind_type = 'Taobao'">
							<a target="_blank" style='display:block;float:right;' href="http://amos.alicdn.com/msg.aw?v=2&amp;uid=nickname&amp;site=cnalichn&amp;s=10&amp;charset=UTF-8" ><img border="0" src="http://amos.alicdn.com/online.aw?v=2&amp;uid=nickname&amp;site=cnalichn&amp;s=10&amp;charset=UTF-8" alt="旺旺" /></a>
						</xsl:if>
					</td>
					<td><xsl:value-of select="mobile" /></td>
					<td><xsl:value-of select="num" /></td>
					<td><xsl:value-of select="number" /></td>
					<td><xsl:value-of select="shop" /></td>
				</tr>
				</xsl:for-each>
			</table>
		</div>
	</form>

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

</xsl:template>

</xsl:stylesheet>
