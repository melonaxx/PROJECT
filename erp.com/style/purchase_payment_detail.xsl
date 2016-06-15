<?xml version="1.0" encoding="utf-8" ?>
<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<!-- UTF8 编码 -->

<xsl:include href="/style/header.xsl" />

<xsl:template name="text">
<style>
.record {
		margin:30px 0 0 0;
	}
.record dl dd {
	height:38px;
	line-height:38px;
	border-bottom:1px solid #ddd;
}
.record .mesg_send {
	border-bottom:1px solid #ddd;
	padding:12px 0 0 0;
	clear:both;
}
.record .mesg_send .right{
	margin:10px 0 0 0;
	float:right;
}
.record .mesg_send .right a {
	margin:0 0 0 20px;
}
</style>
<div class="mainBody">
	<form class="form-inline" action="/purchase/purchase_payment_detail.php" method="post">
		<xsl:element name="INPUT">
			<xsl:attribute name="type">hidden</xsl:attribute>
			<xsl:attribute name="name">purchase_id</xsl:attribute>
			<xsl:attribute name="value"><xsl:value-of select="/html/Body/purchase/ul/li/id" /></xsl:attribute>
		</xsl:element>
		<div class="base">
			<xsl:for-each select="/html/Body/purchase/ul/li">
				<div class="form-group">
					<label class="margin_left_1">供应商：</label>
					<xsl:element name="INPUT">
						<xsl:attribute name="type">text</xsl:attribute>
						<xsl:attribute name="class">form-control input-sm</xsl:attribute>
						<xsl:attribute name="readonly">readonly</xsl:attribute>
						<xsl:attribute name="name">supplier</xsl:attribute>
						<xsl:attribute name="value"><xsl:value-of select="sup" /></xsl:attribute>
					</xsl:element>
				</div>
				<div class="form-group margin_left_block">
					<label>收货仓库：</label>
					<xsl:element name="INPUT">
						<xsl:attribute name="type">text</xsl:attribute>
						<xsl:attribute name="class">form-control input-sm</xsl:attribute>
						<xsl:attribute name="readonly">readonly</xsl:attribute>
						<xsl:attribute name="value"><xsl:value-of select="sto" /></xsl:attribute>
					</xsl:element>
				</div>
				<div class="form-group">
					<label>创建日期：</label>
					<xsl:element name="INPUT">
						<xsl:attribute name="type">text</xsl:attribute>
						<xsl:attribute name="value"><xsl:value-of select="action_date" /></xsl:attribute>
						<xsl:attribute name="readonly">readonly</xsl:attribute>
						<xsl:attribute name="class">form-control input-sm</xsl:attribute>
					</xsl:element>
				</div>
				<div class="form-group">
					<label>单据编码：</label>
					<xsl:element name="INPUT">
						<xsl:attribute name="type">text</xsl:attribute>
						<xsl:attribute name="value"><xsl:value-of select="number" /></xsl:attribute>
						<xsl:attribute name="readonly">readonly</xsl:attribute>
						<xsl:attribute name="class">form-control input-sm</xsl:attribute>
					</xsl:element>
				</div>
				<div class="form-group">
					<label>审核状态：</label>
					<xsl:element name="INPUT">
						<xsl:attribute name="type">text</xsl:attribute>
						<xsl:attribute name="value"><xsl:value-of select="status_audit" /></xsl:attribute>
						<xsl:attribute name="readonly">readonly</xsl:attribute>
						<xsl:attribute name="class">form-control input-sm</xsl:attribute>
					</xsl:element>
				</div>
				<div class="form-group margin_left_block">
					<label>采购摘要：</label>
					<xsl:element name="TEXTAREA">
						<xsl:attribute name="rows">3</xsl:attribute>
						<xsl:attribute name="name">brief</xsl:attribute>
						<xsl:attribute name="class">form-control input-sm merger_two_row_4</xsl:attribute>
						<xsl:value-of select="brief" />
					</xsl:element>
				</div>
				<div class="form-group">
					<label>采购备注：</label>
					<xsl:element name="TEXTAREA">
						<xsl:attribute name="rows">3</xsl:attribute>
						<xsl:attribute name="name">body</xsl:attribute>
						<xsl:attribute name="class">form-control input-sm merger_three_row_8</xsl:attribute>
						<xsl:value-of select="body" />
					</xsl:element>
				</div>
			</xsl:for-each>
		</div>

		<div class="goodsMsg">
			<h4>商品信息</h4>
			<table class="table table-bordered table-hover">
				<tr>
					<th class="table_th_number" width="46px;">序号</th>
					<th width="240px;">商品名称</th>
					<th width="154px;">商品规格</th>
					<th width="80px;">单位</th>
					<th width="80px;">单价</th>
					<th width="80px;">数量</th>
					<th width="100px;">总价</th>
					<th width="160px;">备注</th>
					<th width="90px;">采购入库数量</th>
					<th width="80px;">在途数量</th>
					<th width="90px;">退货出库数量</th>
				</tr>
				<xsl:for-each select="/html/Body/product/ul/li">
				<tr>
					<td class="center"><xsl:value-of select="no" /></td>
					<td><xsl:value-of select="pro" /></td>
					<td><xsl:value-of select="format" /></td>
					<td><xsl:value-of select="par" /></td>
					<td><xsl:value-of select="price" /></td>
					<td><xsl:value-of select="total" /></td>
					<td><xsl:value-of select="zongjia" /></td>
					<td class='content'><xsl:value-of select="content" /></td>
					<td><xsl:value-of select="total_finish" /></td>
					<td><xsl:value-of select="total_way" /></td>
					<td><xsl:value-of select="total_refund" /></td>
					<xsl:element name="INPUT">
						<xsl:attribute name="type">hidden</xsl:attribute>
						<xsl:attribute name="name">product_id</xsl:attribute>
						<xsl:attribute name="value"><xsl:value-of select="product_id" /></xsl:attribute>
					</xsl:element>
				</tr>
				</xsl:for-each>
			</table>
		</div>
		<h4>合计</h4>
		<div class="commonMsg totalMsg">
			<p>采购数量：<span class="money_color"><span class="zongshu"><xsl:value-of select="/html/Body/purchase/ul/li/total" /></span></span><span style="margin-left:20px;">采购总价：<span class="money_color"><span class=""><xsl:value-of select="/html/Body/purchase/ul/li/price" /></span></span>元</span></p>
		</div>
		<div style="width:100%;float:left;background:#eeeeee;">
			<h4>本次付款</h4>
			<div class="commonMsg paymentMsg">
				<div class="form-group">
					<label>付款科目：</label>
					<select class="form-control input-sm" name="subject">
						<xsl:element name="OPTION">
							<xsl:attribute name="value">欠款尾款</xsl:attribute>
							欠款尾款
						</xsl:element>
						<xsl:element name="OPTION">
							<xsl:attribute name="value">订金</xsl:attribute>
							订金
						</xsl:element>
						<xsl:element name="OPTION">
							<xsl:attribute name="value">运费</xsl:attribute>
							运费
						</xsl:element>
					</select>
				</div>
				<div class="form-group">
					<label>交易账号：</label>
					<select class="form-control input-sm" name="bank_id">
						<xsl:for-each select="/html/Body/bank/li">
							<xsl:element name="OPTION">
								<xsl:attribute name="value"><xsl:value-of select="id" /></xsl:attribute>
								<xsl:value-of select="name" />
							</xsl:element>
						</xsl:for-each>
					</select>
				</div>
				<div class="form-group">
					<label>交易金额：</label>
					<div class="input-group">
						<div class="input-group-addon">￥</div>
						<xsl:element name="INPUT">
							 <xsl:attribute name="placeholder">必填</xsl:attribute>
							 <xsl:attribute name="type">text</xsl:attribute>
							 <xsl:attribute name="autocomplete">off</xsl:attribute>
							 <xsl:attribute name="name">money</xsl:attribute>
							 <xsl:attribute name="class">form-control input-sm</xsl:attribute>
							 <xsl:attribute name="style">width:110px;</xsl:attribute>
						</xsl:element>
					</div>
				</div>
				<br />
				<div class="form-group form-group_bottom0">
					<label>单据备注：</label>
					<xsl:element name="INPUT">
						<xsl:attribute name="type">text</xsl:attribute>
						<xsl:attribute name="name">body</xsl:attribute>
						<xsl:attribute name="class">form-control input-sm merger_three_row_8</xsl:attribute>
					</xsl:element>
				</div>
			</div>
		</div>
		<div style='width:100%;float:left;'>
			<h4>财务</h4>
			<div class="commonMsg financeMsg">
				<div class="form-group">
					<label>付款方式：</label>
					<xsl:element name="INPUT">
						<xsl:attribute name="type">text</xsl:attribute>
						<xsl:attribute name="value"><xsl:value-of select="/html/Body/purchase/ul/li/pay_method" /></xsl:attribute>
						<xsl:attribute name="readonly">readonly</xsl:attribute>
						<xsl:attribute name="class">form-control input-sm</xsl:attribute>
					</xsl:element>
				</div>
				<div class="form-group">
					<label>交易账号：</label>
					<xsl:element name="INPUT">
						<xsl:attribute name="type">text</xsl:attribute>
						<xsl:attribute name="class">form-control input-sm</xsl:attribute>
						<xsl:attribute name="readonly">readonly</xsl:attribute>
						<xsl:attribute name="value"><xsl:value-of select="/html/Body/bankId" /></xsl:attribute>
					</xsl:element>
				</div>
				<div class="form-group">
					<label class="margin_left_1">付款状态：</label>
					<xsl:element name="INPUT">
						<xsl:attribute name="type">text</xsl:attribute>
						<xsl:attribute name="class">form-control input-sm</xsl:attribute>
						<xsl:attribute name="readonly">readonly</xsl:attribute>
						<xsl:attribute name="value"><xsl:value-of select="/html/Body/purchase/ul/li/status" /></xsl:attribute>
					</xsl:element>
				</div>
				<br />
				<div class="form-group form-group_bottom0">
					<label>已付金额：</label>
					<div class="input-group">
						<div class="input-group-addon">￥</div>
						<xsl:element name="INPUT">
							 <xsl:attribute name="type">text</xsl:attribute>
							 <xsl:attribute name="class">form-control input-sm</xsl:attribute>
							 <xsl:attribute name="style">width:110px;</xsl:attribute>
							 <xsl:attribute name="value"><xsl:value-of select="/html/Body/purchase/ul/li/payment_already" /></xsl:attribute>
							 <xsl:attribute name="readonly">readonly</xsl:attribute>
						</xsl:element>
					</div>
				</div>
				<div class="form-group form-group_bottom0">
					<label>欠款尾款：</label>
					<div class="input-group">
						<div class="input-group-addon">￥</div>
						<xsl:element name="INPUT">
							 <xsl:attribute name="type">text</xsl:attribute>
							 <xsl:attribute name="class">form-control input-sm qkwk</xsl:attribute>
							 <xsl:attribute name="style">width:110px;</xsl:attribute>
							 <xsl:attribute name="readonly">readonly</xsl:attribute>
							 <xsl:attribute name="value"><xsl:value-of select="/html/Body/purchase/ul/li/payment_remain" /></xsl:attribute>
						</xsl:element>
					</div>
				</div>
				<div class="form-group form-group_bottom0">
					<label>供应商欠款：</label>
					<div class="input-group">
						<div class="input-group-addon">￥</div>
						<xsl:element name="INPUT">
							 <xsl:attribute name="type">text</xsl:attribute>
							 <xsl:attribute name="class">form-control input-sm</xsl:attribute>
							 <xsl:attribute name="style">width:110px;</xsl:attribute>
							 <xsl:attribute name="readonly">readonly</xsl:attribute>
							 <xsl:attribute name="value"><xsl:value-of select="/html/Body/purchase/ul/li/payment_return" /></xsl:attribute>
						</xsl:element>
					</div>
				</div>
				<span class="view_detail">
					<xsl:element name="A">
						<xsl:attribute name="href">
							/purchase/purchase_waiting_payment.php?purchase_id=<xsl:value-of select="/html/Body/purchase/ul/li/id" />&amp;type=Output&amp;subject=FF</xsl:attribute>
						查看详细
					</xsl:element>
				</span>
			</div>
			<h4>运费</h4>
			<div class="commonMsg freightMsg">
				<div class="form-group form-group_bottom0">
					<label>付运费方：</label>
					<xsl:element name="INPUT">
						<xsl:attribute name="type">text</xsl:attribute>
						<xsl:attribute name="value"><xsl:value-of select="/html/Body/purchase/ul/li/freight_side" /></xsl:attribute>
						<xsl:attribute name="class">form-control input-sm</xsl:attribute>
						<xsl:attribute name="readonly">readonly</xsl:attribute>
					</xsl:element>
				</div>
				<div class="form-group form-group_bottom0">
					<label>运费金额：</label>
					<div class="input-group">
						<div class="input-group-addon">￥</div>
						<xsl:element name="INPUT">
							<xsl:attribute name="type">text</xsl:attribute>
							<xsl:attribute name="value"><xsl:value-of select="/html/Body/purchase/ul/li/freight_amount" /></xsl:attribute>
							<xsl:attribute name="class">form-control input-sm</xsl:attribute>
							<xsl:attribute name="readonly">readonly</xsl:attribute>
						</xsl:element>
					</div>
				</div>
				<span class="view_detail">
					<xsl:element name="A">
						<xsl:attribute name="href">
							/purchase/purchase_waiting_payment.php?purchase_id=<xsl:value-of select="/html/Body/purchase/ul/li/id" />&amp;type=Output&amp;subject=YF</xsl:attribute>
						查看详细
					</xsl:element>
				</span>
			</div>
			<h4>收货状态</h4>
			<div class="commonMsg receivingMsg">
				<!-- <div class="form-group form-group_bottom0">
					<label>发票收据：</label>
					<select class="form-control input-sm" name="PP">
						<xsl:element name="OPTION">
							<xsl:attribute name="value">N</xsl:attribute>
							无
						</xsl:element>
						<xsl:element name="OPTION">
							<xsl:attribute name="value">Y</xsl:attribute>
							有
						</xsl:element>
					</select>
				</div> -->
				<div class="form-group form-group_bottom0">
					<label>收货状态：</label>
					<xsl:element name="INPUT">
						<xsl:attribute name="type">text</xsl:attribute>
						<xsl:attribute name="value"><xsl:value-of select="/html/Body/purchase/ul/li/status_receipt" /></xsl:attribute>
						<xsl:attribute name="class">form-control input-sm</xsl:attribute>
						<xsl:attribute name="readonly">readonly</xsl:attribute>
					</xsl:element>
				</div>
				<div class="form-group form-group_bottom0">
					<label class="margin_left_1">退货状态：</label>
					<xsl:element name="INPUT">
						<xsl:attribute name="type">text</xsl:attribute>
						<xsl:attribute name="value"><xsl:value-of select="/html/Body/purchase/ul/li/status_refund" /></xsl:attribute>
						<xsl:attribute name="class">form-control input-sm</xsl:attribute>
						<xsl:attribute name="readonly">readonly</xsl:attribute>
					</xsl:element>
				</div>
				<span class="view_detail">
					<xsl:element name="A">
						<xsl:attribute name="href">
							/purchase/purchase_documents_List.php?purchase_id=<xsl:value-of select="/html/Body/purchase/ul/li/id" /></xsl:attribute>
						查看详细
					</xsl:element>
				</span>
			</div>
			<p class="bottom"><input class="btn btn-default btn-sm btn_margin" type="submit" name='made' value="提交" /><input class="btn btn-default btn-sm" type="reset" value="重置" /></p>
		</div>
	</form>

	<!-- <form class="form-inline">
		<div class="record" style='float:left;'>
			<ul id="myTab" class="nav nav-tabs">
				<li class="active"><a href="#operate_record" data-toggle="tab">操作记录</a></li>
				<li><a href="#mesg_record" data-toggle="tab">留言记录</a></li>
			</ul>
			<div id="myTabContent" class="tab-content">
				<div class="tab-pane fade in active" id="operate_record">
					<dl>
						<dd>
							<span class="float_left">打印了配货单</span>
							<span class="float_right">操作人：2号测试人员　时间：<span>2015-08-19 09:06:52</span></span>
						</dd>
						<dd>
							<span class="float_left">打印了配货单</span>
							<span class="float_right">操作人：2号测试人员　时间：<span>2015-08-19 09:06:52</span></span>
						</dd>
						<dd>
							<span class="float_left">打印了配货单</span>
							<span class="float_right">操作人：2号测试人员　时间：<span>2015-08-19 09:06:52</span></span>
						</dd>
					</dl>
				</div>
				<div class="tab-pane fade" id="mesg_record">
					<div class="mesg_send">
						<div class="form-group">
							<label>留言：</label>
							<xsl:element name="INPUT">
								<xsl:attribute name="type">text</xsl:attribute>
								<xsl:attribute name="name"></xsl:attribute>
								<xsl:attribute name="class">form-control input-sm merger_three_row_8</xsl:attribute>
							</xsl:element>
						</div>
						<div class="right">
							<a href="">上传图片</a><a href="">发布</a><a href="">清空</a>
						</div>
					</div>
					<dl>
						<dd>
							<span class="float_left">这个单子遇到的问题02</span>
							<span class="float_right">操作人：2号测试人员　时间：<span>2015-08-19 09:06:52</span></span>
						</dd>
						<dd>
							<span class="float_left">打印了配货单</span>
							<span class="float_right">操作人：2号测试人员　时间：<span>2015-08-19 09:06:52</span></span>
						</dd>
						<dd>
							<span class="float_left">打印了配货单</span>
							<span class="float_right">操作人：2号测试人员　时间：<span>2015-08-19 09:06:52</span></span>
						</dd>
					</dl>
				</div>
			</div>
		</div>
	</form> -->

</div>
<script>
	//采购摘要失去焦点事件
	$('textarea[name=brief]').blur(function(){
		var brief =$(this).val();
		var purchase_id =$('input[name=purchase_id]').val();
		$.ajax({
			url:'purchase_payment_detail.php',
			type:'post',
			data:{'brief':brief,'purchase_id':purchase_id},
		})
	})
	//采购备注失去焦点事件
	$('textarea[name=body]').blur(function(){
		var body =$(this).val();
		var purchase_id =$('input[name=purchase_id]').val();
		$.ajax({
			url:'purchase_payment_detail.php',
			type:'post',
			data:{'body':body,'purchase_id':purchase_id},
		})
	})
	//采购商品备注双击修改事件
	$('.content').dblclick(function(){
		var td = $(this);
		var content =$(this).text();
		var purchase_id =$('input[name=purchase_id]').val();
		var product_id =$(this).parents('tr').find('input[name=product_id]').val();
		var input = $("&lt;input type='text' style='width:143px;' class='form-control input-sm' value='"+content+"' />");
		td.html(input);
		input.trigger("focus");
		input.blur(function(){
			var newcontent = $(this).val();
			//判断文本有没有修改
			if (newcontent != content) {
				$.ajax({
					url:'purchase_payment_detail.php',
					type:'post',
					data:{'content':newcontent,'purchase_id':purchase_id,'product_id':product_id},
				})
				td.html(newcontent);
			}else{
				td.html(newcontent);
			}
		})

	})
	//托运公司失去焦点事件
	$('input[name=shipping_company]').blur(function(){
		var shipping_company =$(this).val();
		var purchase_id =$('input[name=purchase_id]').val();
		$.ajax({
			url:'purchase_payment_detail.php',
			type:'post',
			data:{'shipping_company':shipping_company,'purchase_id':purchase_id},
		})
	})
	//运单号码失去焦点事件
	$('input[name=waybill_number]').blur(function(){
		var waybill_number =$(this).val();
		var purchase_id =$('input[name=purchase_id]').val();
		$.ajax({
			url:'purchase_payment_detail.php',
			type:'post',
			data:{'waybill_number':waybill_number,'purchase_id':purchase_id},
		})
	})
	//收款金额失去焦点取消红色边框
	$('input[name=money]').blur(function(){
		var val = $(this).val();
		if(val){
			$('input[name=money]').removeClass('error_color');
		}
	});
	//判断提交时入库商品数量,为0则不提交
	$('.form-inline').submit(function(){
		var money = $('input[name=money]').val();
		if( isNaN(money) || money&lt;=0){
			$('input[name=money]').val('');
			$('input[name=money]').addClass('error_color');
			$('input[name=money]').trigger('focus');
			return false;
		}
	})
	//交易金额不能大于欠款尾款
	$("input[name=money]").keyup(function(){
		this.value = this.value.replace(/[^\d.]/g,"");
		this.value = this.value.replace(/^\./g,"");
		this.value = this.value.replace(/\.{2,}/g,".");
		this.value = this.value.replace(".","$#$").replace(/\./g,"").replace("$#$",".");
		var cha = parseFloat($(".qkwk").val())-parseFloat($(this).val());
		if(cha &lt; 0){
			$(this).val($(".qkwk").val());
		}
	})
</script>
</xsl:template>

</xsl:stylesheet>
