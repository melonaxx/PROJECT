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
<script type="text/javascript" src="/js_encode/purchase_in_storage_detail.js"></script>

<div class="mainBody">
	<form class="form-inline" action="/purchase/purchase_in_storage_detail.php" method="post">
		<div class="base">
			<xsl:for-each select="/html/Body/purchase/ul/li">
			<xsl:element name="INPUT">
				<xsl:attribute name="value"><xsl:value-of select="id" /></xsl:attribute>
				<xsl:attribute name="type">hidden</xsl:attribute>
				<xsl:attribute name="name">id</xsl:attribute>
			</xsl:element>
			<div class="form-group">
				<label class="margin_left_1">供应商：</label>
				<xsl:element name="INPUT">
					<xsl:attribute name="value"><xsl:value-of select="supplier_id" /></xsl:attribute>
					<xsl:attribute name="type">hidden</xsl:attribute>
					<xsl:attribute name="name">supplier_id</xsl:attribute>
				</xsl:element>
				<xsl:element name="INPUT">
					<xsl:attribute name="value"><xsl:value-of select="sup" /></xsl:attribute>
					<xsl:attribute name="class">form-control input-sm</xsl:attribute>
					<xsl:attribute name="type">text</xsl:attribute>
					<xsl:attribute name="readonly">readonly</xsl:attribute>
				</xsl:element>
			</div>
			<div class="form-group margin_left_block">
				<label>收货仓库：</label>
				<xsl:element name="INPUT">
					<xsl:attribute name="value"><xsl:value-of select="store_id" /></xsl:attribute>
					<xsl:attribute name="type">hidden</xsl:attribute>
					<xsl:attribute name="name">store_id</xsl:attribute>
				</xsl:element>
				<xsl:element name="INPUT">
					<xsl:attribute name="value"><xsl:value-of select="sto" /></xsl:attribute>
					<xsl:attribute name="class">form-control input-sm</xsl:attribute>
					<xsl:attribute name="type">text</xsl:attribute>
					<xsl:attribute name="readonly">readonly</xsl:attribute>
				</xsl:element>
			</div>
			<div class="form-group">
				<label>创建日期：</label>
				<xsl:element name="INPUT">
					<xsl:attribute name="type">text</xsl:attribute>
					<xsl:attribute name="class">form-control input-sm</xsl:attribute>
					<xsl:attribute name="value"><xsl:value-of select="action_date" /></xsl:attribute>
					<xsl:attribute name="name">action_date</xsl:attribute>
					<xsl:attribute name="readonly">readonly</xsl:attribute>
				</xsl:element>
			</div>
			<div class="form-group">
				<label>单据编码：</label>
				<xsl:element name="INPUT">
					<xsl:attribute name="type">text</xsl:attribute>
					<xsl:attribute name="class">form-control input-sm</xsl:attribute>
					<xsl:attribute name="value"><xsl:value-of select="number" /></xsl:attribute>
					<xsl:attribute name="name">number</xsl:attribute>
					<xsl:attribute name="readonly">readonly</xsl:attribute>
				</xsl:element>
			</div>
			<div class="form-group">
				<label>审核状态：</label>
				<xsl:element name="INPUT">
					<xsl:attribute name="type">text</xsl:attribute>
					<xsl:attribute name="class">form-control input-sm</xsl:attribute>
					<xsl:attribute name="value"><xsl:value-of select="status_audit" /></xsl:attribute>
					<xsl:attribute name="readonly">readonly</xsl:attribute>
				</xsl:element>
			</div>
			<div class="form-group margin_left_block">
				<label>采购摘要：</label>
				<xsl:element name="TEXTAREA">
					<xsl:attribute name="name">brief</xsl:attribute>
					<xsl:attribute name="rows">3</xsl:attribute>
					<xsl:attribute name="class">form-control input-sm merger_two_row_4</xsl:attribute>
					<xsl:value-of select="brief" />
				</xsl:element>
			</div>
			<div class="form-group">
				<label>采购备注：</label>
				<xsl:element name="TEXTAREA">
					<xsl:attribute name="name">body</xsl:attribute>
					<xsl:attribute name="rows">3</xsl:attribute>
					<xsl:attribute name="class">form-control input-sm merger_three_row_8</xsl:attribute>
					<xsl:value-of select="body" />
				</xsl:element>
			</div>
			</xsl:for-each>
		</div>

		<div class="goodsMsg">
			<h4>选择商品</h4>
			<table class="table table-bordered table-hover">
				<tr>
					<th class="table_th_number">序号</th>
					<th width="94px;">本次入库数量</th>
					<th width="130px;">商品名称</th>
					<th width="130px;">商品规格</th>
					<th width="80px;">单位</th>
					<th width="80px;">单价</th>
					<th width="80px;">数量</th>
					<th width="80px;">总价</th>
					<th width="210px;">备注</th>
					<th width="90px;">已入库数量</th>
					<th width="90px;">在途数量</th>
					<th width="90px;">退货出库数量</th>
				</tr>
				<xsl:for-each select="/html/Body/product/ul/li">
				<tr>
					<td class="center"><xsl:value-of select="no" />
						<xsl:element name="INPUT">
							<xsl:attribute name="type">hidden</xsl:attribute>
							<xsl:attribute name="name">no[]</xsl:attribute>
							<xsl:attribute name="value"><xsl:value-of select="no" /></xsl:attribute>
						</xsl:element>
					</td>
					<td style="width:94px;">
						<input type="text" name="sum[]" value="" class="form-control input-sm num" style="width:75px;" autocomplete="off"/>
					</td>
					<td style="width:130px;">
					<xsl:element name="INPUT">
						<xsl:attribute name="value">
							<xsl:value-of select="product_id" /></xsl:attribute>
						<xsl:attribute name="type">hidden</xsl:attribute>
						<xsl:attribute name="name">product_id[]</xsl:attribute>
					</xsl:element>
					<xsl:value-of select="pro" />
					</td>
					<td style="width:130px;">
						<xsl:element name="INPUT">
							<xsl:attribute name="value">
								<xsl:value-of select="format" />
							</xsl:attribute>
							<xsl:attribute name="type">hidden</xsl:attribute>
							<xsl:attribute name="name">format[]</xsl:attribute>
						</xsl:element>
						<xsl:value-of select="format" />
					</td>
					<td>
						<xsl:element name="INPUT">
							<xsl:attribute name="value">
								<xsl:value-of select="parts_id" />
							</xsl:attribute>
							<xsl:attribute name="type">hidden</xsl:attribute>
							<xsl:attribute name="name">parts_id[]</xsl:attribute>
						</xsl:element>
						<xsl:value-of select="par" />
					</td>
					<td>
						<xsl:element name="INPUT">
							<xsl:attribute name="value">
								<xsl:value-of select="price" />
							</xsl:attribute>
							<xsl:attribute name="type">hidden</xsl:attribute>
							<xsl:attribute name="name">price[]</xsl:attribute>
						</xsl:element>
						<xsl:value-of select="price" />
					</td>
					<td>
						<xsl:element name="INPUT">
							<xsl:attribute name="value">
								<xsl:value-of select="total" />
							</xsl:attribute>
							<xsl:attribute name="type">hidden</xsl:attribute>
							<xsl:attribute name="name">total[]</xsl:attribute>
						</xsl:element>
						<xsl:value-of select="total" />
					</td>
					<td>
						<xsl:value-of select="zongjia" />
					</td>
					<td style="width:210px;" class='content'>
						<xsl:value-of select="content" />
					</td>
					<td>
						<xsl:element name="INPUT">
							<xsl:attribute name="value">
								<xsl:value-of select="total_finish" />
							</xsl:attribute>
							<xsl:attribute name="type">hidden</xsl:attribute>
							<xsl:attribute name="name">in_sum[]</xsl:attribute>
							<xsl:attribute name="readonly">readonly</xsl:attribute>
						</xsl:element>
						<xsl:element name="INPUT">
							<xsl:attribute name="value">
							</xsl:attribute>
							<xsl:attribute name="type">hidden</xsl:attribute>
							<xsl:attribute name="name">in_sum2[]</xsl:attribute>
							<xsl:attribute name="readonly">readonly</xsl:attribute>
						</xsl:element>
						<span><xsl:value-of select="total_finish" /></span>
					</td>
					<td>
						<xsl:element name="INPUT">
							<xsl:attribute name="value">
								<xsl:value-of select="total_way" />
							</xsl:attribute>
							<xsl:attribute name="type">hidden</xsl:attribute>
							<xsl:attribute name="name">zt_sum[]</xsl:attribute>
							<xsl:attribute name="readonly">readonly</xsl:attribute>
						</xsl:element>
						<span><xsl:value-of select="total_way" /></span>
					</td>
					<td>
						<xsl:element name="INPUT">
							<xsl:attribute name="value">
								<xsl:value-of select="total_refund" />
							</xsl:attribute>
							<xsl:attribute name="type">hidden</xsl:attribute>
							<xsl:attribute name="name">out_sum[]</xsl:attribute>
							<xsl:attribute name="readonly">readonly</xsl:attribute>
						</xsl:element>
						<span><xsl:value-of select="total_refund" /></span>
					</td>
				</tr>
				</xsl:for-each>

			</table>
		</div>
		<h4>合计</h4>
		<div class="commonMsg totalMsg">
			<p>采购数量总计：<span class="money_color"><span class="zongshu"><xsl:value-of select="/html/Body/purchase/ul/li/total" /></span></span><span style="margin-left:20px;">采购总价：<span class="money_color"><span class="zongjia"><xsl:value-of select="/html/Body/purchase/ul/li/price" /></span></span>元</span>
			<span style="margin-left:20px;">已入库总数：<input type="hidden" name="yiruku" value="" /><span class="money_color"><span class="yiruku"><xsl:value-of select="/html/Body/purchase/ul/li/total_finish" /></span></span></span>
			<span style="margin-left:20px;">在途总数：<input type="hidden" name="zaitu" value="" /><span class="money_color"><span class="zaitu"><xsl:value-of select="/html/Body/purchase/ul/li/total_way" /></span></span></span>
			<span style="margin-left:20px;">退货总数：<input type="hidden" name="tuihuo" value="" /><span class="money_color"><span class="tuihuo"><xsl:value-of select="/html/Body/purchase/ul/li/total_refund" /></span></span></span>
			</p>
		</div>
		<div style='width:100%;float:left;background:#eeeeee;'>
			<h4>本次入库</h4>
			<div class="currentMsg">
				<table class="table table-bordered table-hover">
					<tr>
						<th class="table_th_number">序号</th>
						<th width="167px;">商品名称</th>
						<th width="167px;">商品规格</th>
						<th width="130px;">单位</th>
						<th width="130px;">单价</th>
						<th width="130px;">数量</th>
						<th width="130px;">总价</th>
						<th width="300px;">备注</th>
					</tr>
					 <xsl:for-each select="/html/Body/product/ul/li">
					<tr style='background:#fff;'>
						<td class="center"><xsl:value-of select="no" />
							<xsl:element name="INPUT">
								<xsl:attribute name="type">hidden</xsl:attribute>
								<xsl:attribute name="name">no_rk[]</xsl:attribute>
								<xsl:attribute name="value"><xsl:value-of select="no" /></xsl:attribute>
							</xsl:element>
						</td>
						<td>
							<xsl:element name="INPUT">
								<xsl:attribute name="value">
									<xsl:value-of select="product_id" />
								</xsl:attribute>
								<xsl:attribute name="type">hidden</xsl:attribute>
								<xsl:attribute name="name">product_id_rk[]</xsl:attribute>
							</xsl:element>
							<xsl:value-of select="pro" />
						</td>
						<td>
							<xsl:element name="INPUT">
								<xsl:attribute name="value">
									<xsl:value-of select="format" />
								</xsl:attribute>
								<xsl:attribute name="type">hidden</xsl:attribute>
								<xsl:attribute name="name">format_rk[]</xsl:attribute>
							</xsl:element>
							<xsl:value-of select="format" />
						</td>
						<td>
							<xsl:element name="INPUT">
								<xsl:attribute name="value">
									<xsl:value-of select="parts_id" />
								</xsl:attribute>
								<xsl:attribute name="type">hidden</xsl:attribute>
								<xsl:attribute name="name">parts_id_rk[]</xsl:attribute>
							</xsl:element>
							<xsl:value-of select="par" />
						</td>
						<td>
							<xsl:element name="INPUT">
								<xsl:attribute name="value">
									<xsl:value-of select="price" />
								</xsl:attribute>
								<xsl:attribute name="type">hidden</xsl:attribute>
								<xsl:attribute name="name">price_rk[]</xsl:attribute>
							</xsl:element>
							<xsl:value-of select="price" />
						</td>
						<td><input type="text" value='' name="in_sum_rk[]" class="form-control input-sm" style="width:125px;" readonly="readonly"/></td>
						<td><input type="hidden" name="xiaoji[]" class="form-control input-sm" value=""/><span calss="xiaoji"></span></td>
						<td>
							<input type="text" size="47" class="form-control input-sm" name="rk_body[]"/>
						</td>
					</tr>
					</xsl:for-each>

				</table>
			</div>
			<h4>合计</h4>
			<div class="commonMsg totalMsg1">
				<p>入库数量总计：<input type="hidden" name="zongshu_rk" value="" /><span class="money_color"><span class="zongshu_rk">0</span></span><span style="margin-left:20px;">入库总价：<input type="hidden" name="zongjia_rk" value="" /><span class="money_color"><span class="zongjia_rk">0</span></span>元</span>
				</p>
			</div>
		</div>
		<div style='width:100%;float:left;'>
			<h4>运费</h4>
			<div class="commonMsg freightMsg">
				<xsl:for-each select="/html/Body/purchase/ul/li">
				<div class="form-group form-group_bottom0">
					<label>付运费方：</label>
					<select name="freight_side" class="form-control input-sm">
						<xsl:element name="OPTION">
							<xsl:attribute name="value">Supplier</xsl:attribute>
							<xsl:if test="'Supplier'= freight_side">
								<xsl:attribute name="selected">selected</xsl:attribute>
							</xsl:if>
							供应商
						</xsl:element>
						<xsl:element name="OPTION">
							<xsl:attribute name="value">Company</xsl:attribute>
							<xsl:if test="'Company'= freight_side">
								<xsl:attribute name="selected">selected</xsl:attribute>
							</xsl:if>
							本公司
						</xsl:element>
					</select>
				</div>
				<div class="form-group form-group_bottom0">
					<label>运费金额：</label>
					<div class="input-group">
						<div class="input-group-addon">￥</div>
						<xsl:element name="INPUT">
							 <xsl:attribute name="type">text</xsl:attribute>
							 <xsl:attribute name="name">freight_amount</xsl:attribute>
							 <xsl:attribute name="class">form-control input-sm</xsl:attribute>
							 <xsl:attribute name="value"><xsl:value-of select="freight_amount" /></xsl:attribute>
							 <xsl:attribute name="style">width:110px;</xsl:attribute>
						</xsl:element>
					</div>
				</div>
				<div class="form-group form-group_bottom0">
					<label>托运公司：</label>
					<xsl:element name="INPUT">
						<xsl:attribute name="type">text</xsl:attribute>
						<xsl:attribute name="name">shipping_company</xsl:attribute>
						<xsl:attribute name="value"><xsl:value-of select="shipping_company" /></xsl:attribute>
						<xsl:attribute name="class">form-control input-sm</xsl:attribute>
						<xsl:attribute name="style">width:110px;</xsl:attribute>
					</xsl:element>
				</div>
				<div class="form-group form-group_bottom0">
					<label>运单号码：</label>
					<xsl:element name="INPUT">
						<xsl:attribute name="type">text</xsl:attribute>
						<xsl:attribute name="name">waybill_number</xsl:attribute>
						<xsl:attribute name="value"><xsl:value-of select="waybill_number" /></xsl:attribute>
						<xsl:attribute name="class">form-control input-sm</xsl:attribute>
						<xsl:attribute name="style">width:110px;</xsl:attribute>
					</xsl:element>
				</div>
				</xsl:for-each>
			</div>
			<h4>发货状态</h4>
			<div class="commonMsg receivingMsg">
				<div class="form-group form-group_bottom0">
					<label>收货状态：</label>
					<xsl:element name="INPUT">
						<xsl:attribute name="type">text</xsl:attribute>
						<xsl:attribute name="name">status_receipt</xsl:attribute>
						<xsl:attribute name="value"><xsl:value-of select="/html/Body/purchase/ul/li/status_receipt" /></xsl:attribute>
						<xsl:attribute name="class">form-control input-sm</xsl:attribute>
						<xsl:attribute name="readonly">readonly</xsl:attribute>
					</xsl:element>
				</div>
				<div class="form-group form-group_bottom0">
					<label class="margin_left_1">退货状态：</label>
					<xsl:element name="INPUT">
						<xsl:attribute name="type">text</xsl:attribute>
						<xsl:attribute name="name">status_refund</xsl:attribute>
						<xsl:attribute name="value"><xsl:value-of select="/html/Body/purchase/ul/li/status_refund" /></xsl:attribute>
						<xsl:attribute name="class">form-control input-sm</xsl:attribute>
						<xsl:attribute name="readonly">readonly</xsl:attribute>
					</xsl:element>
				</div>
				<span class="view_detail">
					<xsl:element name="A">
						<xsl:attribute name="href">
							/purchase/purchase_documents_List.php?purchase_id=<xsl:value-of select="/html/Body/purchase/ul/li/number" />&amp;staff=&amp;date=&amp;type=Input</xsl:attribute>
						查看详细
					</xsl:element>
				</span>
			</div>
			<p class="bottom"><input name='made' class="btn btn-default btn-sm btn_margin" type="submit" value="提交" /><input class="btn btn-default btn-sm" type="reset" value="重置" /></p>
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
</xsl:template>

</xsl:stylesheet>
