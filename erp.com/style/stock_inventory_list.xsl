<?xml version="1.0" encoding="utf-8" ?>
<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<!-- UTF8 编码 -->
<xsl:include href="/style/header.xsl" />
<xsl:template name="text">
<script type="text/javascript" src="/js_encode/bootstrap-datetimepicker.min.js"></script>
<script type="text/javascript" src="/js_encode/bootstrap-datetimepicker.zh-CN.js"></script>
<script type="text/javascript" src="/js_encode/LodopFuncs.js"></script>

<link rel="stylesheet" href="/style/bootstrap-datetimepicker.min.css"></link>
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
	<ul class="nav nav-tabs nav_tabs_margin">
		<li><a href="/stock/stock_check.php">库存盘点</a></li>
		<li class="active"><a href="#allocation_list" data-toggle="tab">盘点单</a></li>
	</ul>
	<div class="tab-content">
			<div class="table_operate_block form-inline">
				<form action="stock_inventory_list.php" method="post">
					<div class="form-group" style="margin:0; float:right;">
						<p>
							<button class="btn btn-default btn-sm btn_margin" type="submit">查询</button>
							<input class="btn btn-default btn-sm" type="button" name="clear" value="清空" />
						</p>
					</div>
					<div class="form-group dataendMsg form_small_block float_right">
						<xsl:element name="INPUT">
							<xsl:attribute name="type">text</xsl:attribute>
							<xsl:attribute name="name">date_end</xsl:attribute>
							<xsl:attribute name="class">form-control input-sm</xsl:attribute>
							<xsl:attribute name="value"><xsl:value-of select="/html/Body/date_end"/></xsl:attribute>
						</xsl:element>
					</div>
					<div class="float_right form_small_block" style="margin-top:5px;">到</div>
					<div class="form-group dateMsg form_small_block float_right">
						<label>日期：</label>
						<xsl:element name="INPUT">
							<xsl:attribute name="type">text</xsl:attribute>
							<xsl:attribute name="name">date_state</xsl:attribute>
							<xsl:attribute name="class">form-control input-sm</xsl:attribute>
							<xsl:attribute name="value"><xsl:value-of select="/html/Body/date_state"/></xsl:attribute>
						</xsl:element>
					</div>
					<!-- <div class="form-group form_small_block float_right">
						<label>商品：</label>
						<xsl:element name="INPUT">
							<xsl:attribute name="class">form-control input-sm</xsl:attribute>
							<xsl:attribute name="name">product_id</xsl:attribute>
							<xsl:attribute name="type">text</xsl:attribute>
							<xsl:attribute name="placeholder">输入商品名称或编码</xsl:attribute>
						</xsl:element>
					</div> -->
					<div class="form-group form_small_block float_right">
						<label>仓库：</label>
						<select class="form-control input-sm" name="store_id">
							<option value="0"></option>
							<xsl:for-each select="/html/Body/storeinfo/ul/li">
								<xsl:element name="OPTION">
									<xsl:attribute name="value"><xsl:value-of select="value"/></xsl:attribute>
									  <xsl:if test="value=/html/Body/store_id"><xsl:attribute name="selected">selected</xsl:attribute></xsl:if>
									<xsl:value-of select="text"/>
								</xsl:element>
							</xsl:for-each>
						</select>
					</div>
				</form>
			</div>
			<div>
				<table width="1180" class="table tab-sel table-bordered table-hover">
					<tr>
						<th class="table_th_number">序号</th>
						<th class="table_th_operate">操作</th>
						<th width="140">日期</th>
						<th width="200">商品名称</th>
						<th width="200">商品规格</th>
						<th width="120">单据编码</th>
						<th width="110">仓库</th>
						<th width="100">操作人</th>
						<th width="200">备注</th>
					</tr>
					<xsl:for-each select="/html/Body/storeinventory/ul/li">
						<tr>
							<td class="center"><xsl:value-of select="position()"/></td>
							<td class="center">
								<xsl:element name="SPAN">
									<xsl:attribute name="style">cursor:pointer</xsl:attribute>
									<xsl:attribute name="onclick">MessageBox('/stock/stock_single_detail.php?id=<xsl:value-of select="id"/>', '盘点单详情', 1000,165)</xsl:attribute>
									<xsl:text>详情</xsl:text>
								</xsl:element>
								<xsl:element name="A">
									<xsl:attribute name="class">margin_left_1 doPrint</xsl:attribute>
									<xsl:attribute name="href">javascript:;</xsl:attribute>
									<xsl:attribute name="id"><xsl:value-of select="id"/></xsl:attribute>
									<xsl:text>打印</xsl:text>
								</xsl:element>
							</td>
							<td><xsl:value-of select="action_date"/></td>
							<td><xsl:value-of select="name"/></td>
							<td><xsl:value-of select="format"/></td>
							<td><xsl:value-of select="bill_number"/></td>
							<td><xsl:value-of select="store_id"/></td>
							<td><xsl:value-of select="staff_id"/></td>
							<td><xsl:value-of select="body"/></td>
						</tr>
					</xsl:for-each>
				</table>
				<xsl:call-template name="page"></xsl:call-template>
			</div>
				<!-- <object  id="LODOP_OB" classid="clsid:2105C259-1E0C-4534-8141-A753534CB4CA" width="0" height="0"> 
			       <embed id="LODOP_EM" type="application/x-print-lodop" width="0" height="0"></embed>
				</object> -->
	</div>
</div>
<script type="text/javascript">
	$('input[name="date_state"],input[name="date_end"]').datetimepicker({
		language:'zh-CN',
		format:'yyyy-mm-dd',
		autoclose:true,
		minView:2,
    });
    $('input[name=clear]').click(function(){
    	$('input[name=date_state]').val('');
    	$('input[name=date_end]').val('');
    	$('select[name=store_id]').val('');
    });

    $('.doPrint').click(function(){
		var id=$(this).attr('id');
		$.ajax({
			url: '/stock/stock_single_detail.php',
			type: 'get',
			data: {action:'print',id:id}
		})
		.done(function(data) {
			var info = JSON.parse(data);
		
			var html = '&lt;style>td{text-align:center}&lt;/style>&lt;p>时间：'+info.action_date+'　　　　　仓库：'+info.store_id+'&lt;/p>&lt;p>备注：'+info.body+'&lt;/p>&lt;table width="95%" style="font-size:12px;border:1px solid #000;border-collapse:collapse;line-height:20px" border="1" cellspacing="0" cellpadding="4">&lt;tr>&lt;th>商品名称：&lt;/th>&lt;th>商品规格：&lt;/th>&lt;th>商品编码：&lt;/th>&lt;th>盘点前数量&lt;/th>&lt;th>盘点后数量&lt;/th>&lt;th>盈亏数量&lt;/th>&lt;/tr>&lt;tr>&lt;td>'+info.product_id+'&lt;/td>&lt;td>'+info.format+'&lt;/td>&lt;td>'+info.bill_number+'&lt;td/>&lt;td>'+info.bill_number+'&lt;/td>&lt;td>'+info.total+'&lt;/td>&lt;/tr>&lt;/table>&lt;br>';

			Print(html);
		})
	});

	function Print(html){
		var LODOP=getLodop(document.getElementById('LODOP_OB'),document.getElementById('LODOP_EM'));  

		LODOP.PRINT_INIT();
		LODOP.ADD_PRINT_TEXT(40,0,'100%','30','库存盘点单');
		LODOP.SET_PRINT_STYLEA(0,"FontSize",16);
		LODOP.SET_PRINT_STYLEA(0,"Bold",1);
		LODOP.SET_PRINT_STYLEA(0,"Alignment",2);
		LODOP.SET_PRINT_PAGESIZE(3,2100,'','');
		LODOP.ADD_PRINT_HTM('80','40','95%','100%',html);

		<!-- LODOP.PRINT_DESIGN(); -->
		LODOP.PREVIEW();
	}

</script>
</xsl:template>

</xsl:stylesheet>