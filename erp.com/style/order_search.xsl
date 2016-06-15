<?xml version="1.0" encoding="utf-8" ?>
<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<!-- UTF8 编码 -->

<xsl:include href="/style/header.xsl" />

<xsl:template name="text">
<script typr="text/javascript">
	$(function(){
		var type = '<xsl:value-of select="/html/Body/type" />';

		$('.table-order-form input[name="select_all"]').click(function () {
		    if(this.checked){
				$('.table-order-form input[name="select_one"]').each(function () {
					$(this).prop("checked",true);
				});
		    }else{
				$('.table-order-form input[name="select_one"]').each(function () {
					$(this).prop("checked",false);
				});
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
			var is_ok = true;
			$('.table tr td').find('input[name=select_one]:checked').each(function(){
				var v = $(this).parents('tr').find('td:nth-child(5)').html();
				if(v == "已发货"){
					is_ok = false;
				}
			})
			if(is_ok){
				MessageBox('/order/order_list_audit_popup.php?id='+a, '异常原因',412,185);
				return false;
			}else{
				$('#confirm .modal-body').html("已发货订单不允许再提交异常");
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

		if(type == "shou"){
			$('.butt .butt_name').html("收件人");
			$('.butt input[name=type]').val("shou");
		}else if(type == "bian"){
			$('.butt .butt_name').html("订单编号");
			$('.butt input[name=type]').val("bian");
		}else if(type == "kuai"){
			$('.butt .butt_name').html("快递编号");
			$('.butt input[name=type]').val("kuai");
		}else if(type == ""){
			$('.butt .butt_name').html("订单编号");
			$('.butt input[name=type]').val("bian");
		}

		$('.dropdown-menu .type').click(function(){
			var v = $(this).text();
			if(v == "订单编号"){
				$('.butt .butt_name').html(v);
				$('.butt input[name=type]').val("bian");
			}else if(v == "收件人"){
				$('.butt .butt_name').html(v);
				$('.butt input[name=type]').val("shou");
			}else{
				$('.butt .butt_name').html(v);
				$('.butt input[name=type]').val("kuai");
			}
		})
	})
</script>
<div class="mainBody">
	<!-- <form class="form-inline" method="get" action="/search/search_order.php"> -->
		<div class="goodsMsg" style="clear:both;">
		<form class="form-inline" method="get" action="/search/search_order.php">

			<div class="table_operate_blocks">
				<div class="form-group float_right margin0">
					<div class="input-group">
						<div class="row">
						  <div class="col-lg-6">
						    <div class="input-group">

						      	<xsl:element name="input">
									<xsl:attribute name="type">text</xsl:attribute>
									<xsl:attribute name="style">width:300px</xsl:attribute>
									<xsl:attribute name="name">find</xsl:attribute>
									<xsl:attribute name="class">form-control input-sm input_search</xsl:attribute>
									<xsl:attribute name="placeholder">输入订单编号/收件人/快递编号等</xsl:attribute>
									<xsl:attribute name="value">
										<xsl:value-of select="/html/Body/find"/>
									</xsl:attribute>
								</xsl:element>
						      <div class="input-group-btn">
						        <button type="button" class="btn btn-default btn-sm butt" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" style="width:78px;">
						        	<span class="butt_name">订单编号</span>
									<xsl:element name='INPUT'>
										<xsl:attribute name="type">hidden</xsl:attribute>
										<xsl:attribute name="name">type</xsl:attribute>
										<xsl:attribute name="value"><xsl:value-of select="/html/Body/type"/></xsl:attribute>
									</xsl:element>
						        	<span class="caret"></span></button>
						        <ul class="dropdown-menu dropdown-menu-right">
						          <li><a href="#" class="type">订单编号</a></li>
						          <li><a href="#" class="type">收件人</a></li>
						          <li><a href="#" class="type">快递编号</a></li>
						        </ul>
						      </div>
						    </div>
						  </div>
						</div>

						<span class="input-group-btn">
							<button class="btn btn-default btn-sm" style="margin-bottom:15px;">搜索</button>
						</span>
					</div>
				</div>
				<div class="btn-group">
						<button class="btn btn-default btn-sm btn_margin yichang" type="button">提交异常</button>
				</div>

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
					<div class="modal-footer"><button type="button" class="btn btn-default btn-sm ok" data-dismiss="modal">确定</button></div>
				</div>
			</div>
			<table style="width:1200px" class="table table-bordered table-hover table-order-form">
				<tr>
					<th class="table_th_number">序号</th>
					<th class="table_th_checkbox center"><input type="checkbox" name="select_all" /></th>
					<th class="center table_th_number" style="width:48px;">操作</th>
					<th class="table_th_number" style="width:96px">打印标记</th>
					<th style="width:100px;">所在位置</th>
					<th style="width:100px;">订单备注</th>
					<th style="width:100px;">发货仓库</th>
					<th style="width:100px;">快递</th>
					<th style="width:100px;">买家</th>
					<th style="width:100px;">手机</th>
					<th style="width:100px;">数量</th>
					<th style="width:172px;">订单编号</th>
					<th style="width:100px;">店铺</th>
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
							<xsl:attribute name="class"></xsl:attribute>
							<xsl:attribute name="href">/order/order_detail.php?id=<xsl:value-of select = 'id'/></xsl:attribute>
						<xsl:text>详细</xsl:text>
						</xsl:element>
					</td>
					<td>
						<font color="blue" class="mark_express"><xsl:value-of select="mark_express" /></font>
						<font color="green" class="mark_deliver"><xsl:value-of select="mark_deliver" /></font>
						<font color="orange" class="mark_order"><xsl:value-of select="mark_order" /></font>
					</td>
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
		</div>
		<xsl:if test="/html/Body/OrderReview/@total = '0'">
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
