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
	<form class="form-inline">
		<div class="base">
			<div class="form-group">
				<label class="margin_left_1">供应商：</label>
				<select class="form-control input-sm" name=""><!--字段暂时没有-->
					<xsl:for-each select="/html/Body/purchase/ul/li">
						<xsl:element name="OPTION">
							<xsl:attribute name="value"><xsl:value-of select="value" /></xsl:attribute>
							<xsl:if test="value=/html/Body/purchase/ul/select_value"><xsl:attribute name="selected">selected</xsl:attribute></xsl:if>
							<xsl:value-of select="text" />
						</xsl:element>
					</xsl:for-each>
				</select>
			</div>
			<div class="form-group margin_left_block">
				<label>收货仓库：</label>
				<select class="form-control input-sm" name="store">
					<xsl:for-each select="/html/Body/store/ul/li">
						<xsl:element name="OPTION">
							<xsl:attribute name="value"><xsl:value-of select="value" /></xsl:attribute>
							<xsl:if test="value=/html/Body/store/select_value"><xsl:attribute name="selected">selected</xsl:attribute></xsl:if>
							<xsl:value-of select="text"/>
						</xsl:element>
					</xsl:for-each>
				</select>
			</div>
			<div class="form-group">
				<label>创建日期：</label>
				<xsl:element name="INPUT">
					<xsl:attribute name="type">text</xsl:attribute>
					<xsl:attribute name="name">tax</xsl:attribute>
					<xsl:attribute name="readonly">readonly</xsl:attribute>
					<xsl:attribute name="class">form-control input-sm</xsl:attribute>
					<xsl:attribute name="value"><xsl:value-of select="/html/Body/purchase_main/action_date"/></xsl:attribute>
				</xsl:element>
			</div>
			<div class="form-group">
				<label>单据编码：</label>
				<xsl:element name="INPUT">
					<xsl:attribute name="type">text</xsl:attribute>
					<xsl:attribute name="name">tax</xsl:attribute>
					<xsl:attribute name="readonly">readonly</xsl:attribute>
					<xsl:attribute name="class">form-control input-sm</xsl:attribute>
				</xsl:element>
			</div>
			<div class="form-group">
				<label>审核状态：</label>
				<select class="form-control input-sm" name="">
					<xsl:for-each select="/html/Body/goodName/type/ul/li">
						<xsl:element name="OPTION">
							<xsl:attribute name="value"><xsl:value-of select="value" /></xsl:attribute>
							<xsl:value-of select="text" />
						</xsl:element>
					</xsl:for-each>
				</select>
			</div>
			<div class="form-group margin_left_block">
				<label>采购摘要：</label>
				<xsl:element name="TEXTAREA">
					<xsl:attribute name="name">tax</xsl:attribute>
					<xsl:attribute name="rows">3</xsl:attribute>
					<xsl:attribute name="class">form-control input-sm merger_two_row_4</xsl:attribute>
					<xsl:value-of select="/html/Body/purchase_main/name"/>
				</xsl:element>
			</div>
			<div class="form-group">
				<label>采购备注：</label>
				<xsl:element name="TEXTAREA">
					<xsl:attribute name="name">tax</xsl:attribute>
					<xsl:attribute name="rows">3</xsl:attribute>
					<xsl:attribute name="readonly">readonly</xsl:attribute>
					<xsl:attribute name="class">form-control input-sm merger_three_row_8</xsl:attribute>
				</xsl:element>
			</div>
		</div>
		
		<div class="goodsMsg">
			<h4>商品信息</h4>
			<div class="table_operate_block">
				<input class="btn btn-default btn-sm btn_margin goodsAdd" type="button" value="添加" />
				<input class="btn btn-default btn-sm goodsDelete" type="button" value="删除" />
			</div>	
			<table width="1200" class="table table-bordered table-hover">
				<tr>
					<th class="table_th_number">序号</th>
					<th class="table_th_checkbox center"><input type="checkbox" name="select_all" /></th>
					<th width="100">搜索</th>
					<th width="150">商品名称与规格</th>
					<th width="100">单价</th>
					<th width="100">单位</th>
					<th width="100">数量</th>
					<th width="100">总价</th>
					<th width="120">备注</th>
					<th width="100">采购入库数量</th>
					<th width="100">在途数量</th>
					<th width="100">退货出库数量</th>
				</tr>
				
				<tr>
					<td class="center">1</td>
					<td class="center"><input type="checkbox" name="select_one" /></td>
					<td><input type="text" class="form-control input-sm form_no_border number" /></td>
					<td>
					   <select class="form-control input-sm form_no_border"></select>
			  		</td>
					<td>
					   <div class="input-group">
							<div class="input-group-addon">￥</div>
							<input type="text" class="form-control input-sm form_no_border" value=""/>
					   </div>
					</td>
					<td>
						<select class="form-control input-sm form_no_border"></select>
					</td>
					<td><input type="text" class="form-control input-sm form_no_border number" /></td>
					<td>
						<div class="input-group">
							<div class="input-group-addon">￥</div>
							<input type="text" class="form-control input-sm form_no_border" value=""/>
						</div>
					</td>
					<td><input style="width:100px;" type="text" class="form-control input-sm form_no_border number" /></td><!--备注-->
					<td></td>
					<td></td>
					<td></td>
				</tr>
				<tr>
					 <td class="center"></td>
					 <td></td>
					 <td></td>
					 <td></td>
					 <td></td>
					 <td></td>
					 <td></td>
					 <td class="luyahe_sum">数量</td>
					 <td class="luyahe_all">总价</td>
					 <td></td>
					 <td></td>
					 <td></td>
				</tr>
			</table>
		</div>
		<h4>合计</h4>
		<div class="commonMsg totalMsg">
			<p>商品总金额<span class="money_color">2070</span>元 - 订单总优惠<input class="form-control input-sm form_control_extra" type="text"/>元  = 应付<span class="money_color">2000</span>元</p>
		</div>
		<h4>物流快递</h4>
		<div class="commonMsg freightMsg">
			<div class="form-group form-group_bottom0">
				<label>付运费方：</label>
				<select class="form-control input-sm" name="">
					<xsl:for-each select="/html/Body/goodName/type/ul/li">
						<xsl:element name="OPTION">
							<xsl:attribute name="value"><xsl:value-of select="value" /></xsl:attribute>
							<xsl:value-of select="text" />
						</xsl:element>
					</xsl:for-each>
				</select>
			</div>
			<div class="form-group form-group_bottom0">
				<label>运费金额：</label>
				<div class="input-group">
					<div class="input-group-addon">￥</div>
					<xsl:element name="INPUT">
						 <xsl:attribute name="type">text</xsl:attribute>
						 <xsl:attribute name="name"></xsl:attribute>
						 <xsl:attribute name="class">form-control input-sm</xsl:attribute>
						 <xsl:attribute name="style">width:110px;</xsl:attribute>
					</xsl:element>
				</div>
			</div>
			<div class="form-group form-group_bottom0">
				<label class="margin_left_1">托运公司：</label>
				<xsl:element name="INPUT">
					<xsl:attribute name="type">text</xsl:attribute>
					<xsl:attribute name="name">tax</xsl:attribute>
					<xsl:attribute name="class">form-control input-sm</xsl:attribute>
				</xsl:element>
			</div>
			<div class="form-group form-group_bottom0">
				<label>运单号码：</label>
				<xsl:element name="INPUT">
					<xsl:attribute name="type">text</xsl:attribute>
					<xsl:attribute name="name">tax</xsl:attribute>
					<xsl:attribute name="class">form-control input-sm</xsl:attribute>
				</xsl:element>
			</div>
		</div>
		<h4>财务</h4>
		<div class="commonMsg financeMsg">
			<div class="form-group">
				<label>付款方式：</label>
				<select class="form-control input-sm" name="">
					<xsl:for-each select="/html/Body/goodName/type/ul/li">
						<xsl:element name="OPTION">
							<xsl:attribute name="value"><xsl:value-of select="value" /></xsl:attribute>
							<xsl:value-of select="text" />
						</xsl:element>
					</xsl:for-each>
				</select>
			</div>
			<div class="form-group">
				<label>付款账号：</label>
				<select class="form-control input-sm" name="">
					<xsl:for-each select="/html/Body/goodName/type/ul/li">
						<xsl:element name="OPTION">
							<xsl:attribute name="value"><xsl:value-of select="value" /></xsl:attribute>
							<xsl:value-of select="text" />
						</xsl:element>
					</xsl:for-each>
				</select>
			</div>
			<div class="form-group">
				<label class="margin_left_1">付款状态：</label>
				<select class="form-control input-sm" name="">
					<xsl:for-each select="/html/Body/goodName/type/ul/li">
						<xsl:element name="OPTION">
							<xsl:attribute name="value"><xsl:value-of select="value" /></xsl:attribute>
							<xsl:value-of select="text" />
						</xsl:element>
					</xsl:for-each>
				</select>
			</div>
			<br />
			<div class="form-group form-group_bottom0">
				<label>已付金额：</label>
				<xsl:element name="INPUT">
					<xsl:attribute name="type">text</xsl:attribute>
					<xsl:attribute name="name">tax</xsl:attribute>
					<xsl:attribute name="class">form-control input-sm</xsl:attribute>
					<xsl:attribute name="readonly">readonly</xsl:attribute>
				</xsl:element>
			</div>
			<div class="form-group form-group_bottom0">
				<label>欠款尾款：</label>
				<xsl:element name="INPUT">
					<xsl:attribute name="type">text</xsl:attribute>
					<xsl:attribute name="name">tax</xsl:attribute>
					<xsl:attribute name="class">form-control input-sm</xsl:attribute>
					<xsl:attribute name="readonly">readonly</xsl:attribute>
				</xsl:element>
			</div>
			<div class="form-group form-group_bottom0">
				<label>供应商欠款：</label>
				<xsl:element name="INPUT">
					<xsl:attribute name="type">text</xsl:attribute>
					<xsl:attribute name="name">tax</xsl:attribute>
					<xsl:attribute name="class">form-control input-sm</xsl:attribute>
					<xsl:attribute name="readonly">readonly</xsl:attribute>
				</xsl:element>
			</div>
			<span class="view_detail"><a href="###">查看详细</a></span>
		</div>
		<h4>收货状态</h4>
		<div class="commonMsg receivingMsg">
			<div class="form-group form-group_bottom0">
				<label>发票收据：</label>
				<select class="form-control input-sm" name="">
					<xsl:for-each select="/html/Body/goodName/type/ul/li">
						<xsl:element name="OPTION">
							<xsl:attribute name="value"><xsl:value-of select="value" /></xsl:attribute>
							<xsl:value-of select="text" />
						</xsl:element>
					</xsl:for-each>
				</select>
			</div>
			<div class="form-group form-group_bottom0">
				<label>收货状态：</label>
				<select class="form-control input-sm" name="">
					<xsl:for-each select="/html/Body/goodName/type/ul/li">
						<xsl:element name="OPTION">
							<xsl:attribute name="value"><xsl:value-of select="value" /></xsl:attribute>
							<xsl:value-of select="text" />
						</xsl:element>
					</xsl:for-each>
				</select>
			</div>
			<div class="form-group form-group_bottom0">
				<label class="margin_left_1">退货状态：</label>
				<select class="form-control input-sm" name="">
					<xsl:for-each select="/html/Body/goodName/type/ul/li">
						<xsl:element name="OPTION">
							<xsl:attribute name="value"><xsl:value-of select="value" /></xsl:attribute>
							<xsl:value-of select="text" />
						</xsl:element>
					</xsl:for-each>
				</select>
			</div>
			<span class="view_detail"><a href="###">查看详细</a></span>
		</div>
		<p class="bottom">
			<input class="btn btn-default btn-sm btn_margin" type="submit" value="提交" />
			<input class="btn btn-default btn-sm" type="reset" value="重置" />
		</p>
	</form>
	
	<form class="form-inline margin_top_add2">
	<div class="record">
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
							<xsl:attribute name="name"></xsl:attribute><!--字段暂时没有-->
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
	</form>
</div>
	<script type="text/javascript">
		var trStr = $('.goodsMsg table tr').eq(1).prop("outerHTML");
		$(".goodsAdd").click(function(){
			$('.goodsMsg table tr').last().before(trStr);
			//重置下标
			$('.goodsMsg table tr').each(function (index, value) {
				if (index > 0) {
					$(value).find('td').eq(0).html(index);
				}
			});
		})
		
	</script>
</xsl:template>
</xsl:stylesheet>
