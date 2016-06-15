<?xml version="1.0" encoding="utf-8" ?>
<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<!-- UTF8 编码 -->

<xsl:include href="/style/header.xsl" />

<xsl:template name="text">

<!-- //日期 -->
<script type="text/javascript" src="/js_encode/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript" src="/js_encode/bootstrap-datetimepicker.zh-CN.js"></script>

<link rel="stylesheet" href="/style/bootstrap-datetimepicker.min.css"></link>
<style>
</style>
<!-- 控件安装提示 -->
<div id="confirm" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none;width:268px;margin:60px auto">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
			<h4 class="modal-title" id="myModalLabel">提示</h4>
		</div>
		<div class="modal-body" style="margin-left:20px"></div>
		<div class="modal-footer"><button type="button" class="btn btn-default btn-sm ok" data-dismiss="modal">确定</button></div>
	</div>
</div>
<div class="mainBody">
	<ul id="myTab" class="nav nav-tabs" style='margin-bottom:12px;'>
	   <li><a href="/purchase/purchase_waiting_storage.php">采购入库</a></li>
	   <li><a href="/purchase/purchase_out_storage.php">退货出库</a></li>
	   <li class="active"><a href="" data-toggle="">出入库单据列表</a></li>

	</ul>
	<div class="customersMsg">
		<div class="table_operate_block">
			<form class="form-inline" method="get" action="/purchase/purchase_documents_List.php">
			<!-- <input class="btn btn-default btn-sm" type="button" onclick="location.href = '/purchase/purchase_out_storage_list.php'" value="退货出库" /> -->
			<div class="form-group" style="margin:0; float:right;">
				<button class="btn btn-default btn-sm form_small_block" type="submit">查询</button>
				<input class="btn btn-default btn-sm" type="button" name="clear" value="清空" />
			</div>
			<div class="form-group form_small_block float_right">
				<label>采购单编号：</label>
				<xsl:element name="INPUT">
					<xsl:attribute name="type">text</xsl:attribute>
					<xsl:attribute name="name">purchase_id</xsl:attribute>
					<xsl:attribute name="value"><xsl:value-of select="/html/Body/purchase_id"/></xsl:attribute>
					<xsl:attribute name="class">form-control input-sm</xsl:attribute>
				</xsl:element>
			</div>
			<div class="form-group form_small_block float_right">
				<label>操作人：</label>
				<xsl:element name="INPUT">
					<xsl:attribute name="type">text</xsl:attribute>
					<xsl:attribute name="name">staff</xsl:attribute>
					<xsl:attribute name="value"><xsl:value-of select="/html/Body/staff"/></xsl:attribute>
					<xsl:attribute name="class">form-control input-sm</xsl:attribute>
				</xsl:element>
			</div>
			<div class="form-group form_small_block float_right">
				<label>日期：</label>
				<xsl:element name="INPUT">
					<xsl:attribute name="type">text</xsl:attribute>
					<xsl:attribute name="name">date</xsl:attribute>
					<xsl:attribute name="value"><xsl:value-of select="/html/Body/date"/></xsl:attribute>
					<xsl:attribute name="class">form-control input-sm</xsl:attribute>
				</xsl:element>
			</div>
			<div class="form-group form_small_block float_right">
				<label>单据类型：</label>
				<select name="type" class="form-control input-sm">
					<option value="0"></option>
					<xsl:element name="OPTION">
						<xsl:attribute name="value">Input</xsl:attribute>
						<xsl:if test="'Input' = /html/Body/type">
							<xsl:attribute name="selected">selected</xsl:attribute>
						</xsl:if>
						入库单据
					</xsl:element>
					<xsl:element name="OPTION">
						<xsl:attribute name="value">Output</xsl:attribute>
						<xsl:if test="'Output' = /html/Body/type">
							<xsl:attribute name="selected">selected</xsl:attribute>
						</xsl:if>
						出库单据
					</xsl:element>
				</select>
			</div>
			</form>
		</div>
		<table class="table table-bordered table-hover">
			<tr>
				<th class="table_th_number">序号</th>
				<th class="table_th_operate">操作</th>
				<th width="120">单据编号</th>
				<th width="110">单据类型</th>
				<th width="120">供应商</th>
				<th width="120">仓库</th>
				<th width="110">数量</th>
				<th width="110">总价</th>
				<th width="138">时间</th>
				<th width="120">采购单编号</th>
				<th width="120">操作人</th>
			</tr>
			<xsl:for-each select="/html/Body/store/ul/li">
			<tr>
				<td class="center"><xsl:value-of select="no" /></td>
				<td class="center">
					<xsl:element name="A">
						<xsl:attribute name="href">
							javascript:;
						</xsl:attribute>
						<xsl:attribute name="class">table_a_operate</xsl:attribute>
						<xsl:attribute name="onclick">MessageBox('/purchase/purchase_documents_xiang.php?id=<xsl:value-of select="id" />', '详细', 900, 400)
						</xsl:attribute>
						详细
					</xsl:element>
					<xsl:element name="A">
						<xsl:attribute name="href">
							javascript:;
						</xsl:attribute>
						<xsl:attribute name="class">table_a_operate doPrint</xsl:attribute>
						<xsl:attribute name="id"><xsl:value-of select="id"/></xsl:attribute>
						打印
					</xsl:element>
				</td>
				<td><xsl:value-of select="number" /></td>
				<td><xsl:value-of select="store_type" /></td>
				<td><xsl:value-of select="supplier_id" /></td>
				<td><xsl:value-of select="store_id" /></td>
				<td><xsl:value-of select="total" /></td>
				<td><xsl:value-of select="price" /></td>
				<td><xsl:value-of select="action_date" /></td>
				<td><xsl:value-of select="purchase_id" /></td>
				<td><xsl:value-of select="input_staff_id" /></td>
			</tr>
			</xsl:for-each>

		</table>
		<xsl:if test="/html/Body/store/ul/@total = '0'">
			<div class="imgs" style="margin:0 auto; width:100%;text-align:center">
				<div class="img">
					<img src="/images/empty.jpg"  alt=""/>
					<span>没有找到记录，请调整搜索条件。</span>
				</div>
			</div>
		</xsl:if>
		<xsl:call-template name="page"></xsl:call-template>

	</div>
		<!-- <object  id="LODOP_OB" classid="clsid:2105C259-1E0C-4534-8141-A753534CB4CA" width="0" height="0">
	       <embed id="LODOP_EM" type="application/x-print-lodop" width="0" height="0"></embed>
		</object> -->
</div>
<script type="text/javascript">
	$('.customersMsg input[name="date"]').datetimepicker({
		language:'zh-CN',
		format:'yyyy-mm-dd',
		autoclose:true,
		minView:2,
	});
	$('input[name=clear]').click(function(){
		$('select[name=type]').val('');
		$('input[name=date]').val('');
		$('input[name=staff]').val('');
	})

	$('.doPrint').click(function(){
		var id=$(this).attr('id');
		$.ajax({
			url: '/purchase/purchase_documents_xiang.php',
			type: 'get',
			data: {action:'print',id:id}
		})
		.done(function(data) {
			var data = JSON.parse(data);

			var html = '&lt;body>&lt;style>td{text-align:center}body{font-size:12px}&lt;/style>&lt;div>&lt;span style="margin:5px 0 0 0px;">单据编号：&lt;span style="width:100px;display:inline-block">'+data.info.number+'&lt;/span>&lt;span style="margin:5px 0 0 87px;">操作人：&lt;span style="width:100px;display:inline-block">'+data.info.input_staff_id+'&lt;/span>&lt;span style="margin:5px 0 0 89px;">仓库：&lt;span style="width:150px;display:inline-block">'+data.info.store_id+'&lt;/span>&lt;/span>&lt;/div>&lt;br>&lt;div>&lt;span style="margin:5px 0 0 0px;">采购单号：&lt;span style="width:100px;display:inline-block">'+data.info.purchase_id+'&lt;/span>&lt;/span>&lt;span style="margin:5px 0 0 87px;">供应商：&lt;span style="width:100px;display:inline-block">'+data.info.supplier_id+'&lt;/span>&lt;/span>&lt;span style="margin:5px 0 0 89px;">日期：&lt;span style="width:200px;display:inline-block">'+data.info.action_date+'&lt;/span>&lt;/span>&lt;/div>&lt;/div>&lt;br/>&lt;div style="float:left;">&lt;table width="95%" style="font-size:12px;border:1px solid #000;border-collapse:collapse;line-height:20px" border="1" cellspacing="0" cellpadding="4">&lt;tr>&lt;th class="center" width="46px">序号&lt;/th>&lt;th width="160px;">商品名称&lt;/th>&lt;th width="120px;">商品规格&lt;/th>&lt;th width="60px;">单位&lt;/th>&lt;th width="60px;">单价&lt;/th>&lt;th width="60px;">数量&lt;/th>&lt;th width="60px;">总价&lt;/th>&lt;th width="105px;">备注&lt;/th>&lt;/tr>';

			for(var i=0;i&lt;data.product_info.length;i++){
				html +='&lt;tr>&lt;td>'+data.product_info[i].no+'&lt;/td>&lt;td>'+data.product_info[i].product_id+'&lt;/td>&lt;td>'+data.product_info[i].format+'&lt;/td>&lt;td>'+data.product_info[i].parts_id+'&lt;/td>&lt;td>'+data.product_info[i].price+'&lt;/td>&lt;td>'+data.product_info[i].total+'&lt;/td>&lt;td>'+data.product_info[i].payment+'&lt;/td>&lt;td>'+data.product_info[i].body+'&lt;/td>&lt;/tr>';
			}
			html +=	'&lt;tr>&lt;td colspan=2 >合计：&lt;/td>&lt;td colspan=3>数量总计：'+data.info.total+'&lt;/td>&lt;td colspan=3>总价：'+data.info.price+'&lt;/td>&lt;/tr>&lt;/table>&lt;/div>&lt;/body>';
			Print(html);
		})
	});

	function Print(html){
		var LODOP=getLodop(document.getElementById('LODOP_OB'),document.getElementById('LODOP_EM'));

		LODOP.PRINT_INIT();
		LODOP.SET_PRINT_PAGESIZE(1,'','','A4');
		LODOP.ADD_PRINT_TEXT(40,0,'100%','30','入库出库单据列表');
		LODOP.SET_PRINT_STYLEA(0,"FontSize",16);
		LODOP.SET_PRINT_STYLEA(0,"Alignment",2);
		LODOP.SET_PRINT_STYLEA(0,"Bold",1);
		LODOP.ADD_PRINT_HTM('90','40','100%','100%',html);

		<!-- LODOP.PRINT_DESIGN(); -->
		LODOP.PREVIEW();
	}
</script>
</xsl:template>

</xsl:stylesheet>
