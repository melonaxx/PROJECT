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
	<div class="customersMsg">
		<div class="table_operate_block">
			<form class="form-inline" method="get" action="/purchase/purchase_inquiry.php">
				<div class="form-group" style="margin:0; float:right;">
					<button class="btn btn-default btn-sm form_small_block" type="submit">查询</button>
					<input class="btn btn-default btn-sm" type="button" name="clear" value="清空"/>
				</div>
				<div class="form-group form_small_block float_right">
					<label>申请人：</label>
					<xsl:element name="INPUT">
						<xsl:attribute name="type">text</xsl:attribute>
						<xsl:attribute name="name">staff</xsl:attribute>
						<xsl:attribute name="value"><xsl:value-of select="/html/Body/staff"/></xsl:attribute>
						<xsl:attribute name="class">form-control input-sm</xsl:attribute>
					</xsl:element>
				</div>
				<div class="form-group form_small_block float_right">
					<label>申请日期：</label>
					<xsl:element name="INPUT">
						<xsl:attribute name="type">text</xsl:attribute>
						<xsl:attribute name="name">application_date</xsl:attribute>
						<xsl:attribute name="value"><xsl:value-of select="/html/Body/application_date"/></xsl:attribute>
						<xsl:attribute name="class">form-control input-sm</xsl:attribute>
						<xsl:attribute name="style">width:100px;</xsl:attribute>
					</xsl:element>
				</div>
				<div class="form-group form_small_block float_right">
				<label>审核状态：</label>
				<select name="status_audit" class="form-control input-sm" style="width:100px;" >
					<option value="0"></option>
					<xsl:element name="OPTION">
						<xsl:attribute name="value">N</xsl:attribute>
						<xsl:if test="'N' = /html/Body/audit">
							<xsl:attribute name="selected">selected</xsl:attribute>
						</xsl:if>
						待审核
					</xsl:element>
					<xsl:element name="OPTION">
						<xsl:attribute name="value">R</xsl:attribute>
						<xsl:if test="'R' = /html/Body/audit">
							<xsl:attribute name="selected">selected</xsl:attribute>
						</xsl:if>
						待修改
					</xsl:element>
					<xsl:element name="OPTION">
						<xsl:attribute name="value">Y</xsl:attribute>
						<xsl:if test="'Y' = /html/Body/audit">
							<xsl:attribute name="selected">selected</xsl:attribute>
						</xsl:if>
						通过审核
					</xsl:element>
					<xsl:element name="OPTION">
						<xsl:attribute name="value">F</xsl:attribute>
						<xsl:if test="'F' = /html/Body/audit">
							<xsl:attribute name="selected">selected</xsl:attribute>
						</xsl:if>
						拒绝
					</xsl:element>
				</select>
			</div>
			<div class="form-group form_small_block float_right">
				<label>收货状态：</label>
				<select name="status_receipt" class="form-control input-sm" style="width:100px;">
					<option value="0"></option>
					<xsl:element name="OPTION">
						<xsl:attribute name="value">N</xsl:attribute>
						<xsl:if test="'N' = /html/Body/receipt">
							<xsl:attribute name="selected">selected</xsl:attribute>
						</xsl:if>
						未收货
					</xsl:element>
					<xsl:element name="OPTION">
						<xsl:attribute name="value">P</xsl:attribute>
						<xsl:if test="'P' = /html/Body/receipt">
							<xsl:attribute name="selected">selected</xsl:attribute>
						</xsl:if>
						部分收货
					</xsl:element>
					<xsl:element name="OPTION">
						<xsl:attribute name="value">Y</xsl:attribute>
						<xsl:if test="'Y' = /html/Body/receipt">
							<xsl:attribute name="selected">selected</xsl:attribute>
						</xsl:if>
						全部收货
					</xsl:element>
				</select>
			</div>
			<div class="form-group form_small_block float_right">
				<label>退货状态：</label>
				<select name="status_refund" class="form-control input-sm" style="width:100px;">
					<option value="0"></option>
					<xsl:element name="OPTION">
						<xsl:attribute name="value">N</xsl:attribute>
						<xsl:if test="'N' = /html/Body/refund">
							<xsl:attribute name="selected">selected</xsl:attribute>
						</xsl:if>
						未退货
					</xsl:element>
					<xsl:element name="OPTION">
						<xsl:attribute name="value">P</xsl:attribute>
						<xsl:if test="'P' = /html/Body/refund">
							<xsl:attribute name="selected">selected</xsl:attribute>
						</xsl:if>
						部分退货
					</xsl:element>
					<xsl:element name="OPTION">
						<xsl:attribute name="value">Y</xsl:attribute>
						<xsl:if test="'Y' = /html/Body/refund">
							<xsl:attribute name="selected">selected</xsl:attribute>
						</xsl:if>
						全部退货
					</xsl:element>
				</select>
			</div>
			</form>
		</div>
		<table width="1180" class="table table-bordered table-hover">
			<tr>
				<th class="table_th_number">序号</th>
				<th class="center" width="104">操作</th>
				<th width="120">采购单编号</th>
				<th width="70">审核状态</th>
				<th width="70">收货状态</th>
				<th width="70">退货状态</th>
				<th width="100">供应商</th>
				<th width="100">仓库</th>
				<th width="70">采购数量</th>
				<th width="90">采购总价</th>
				<th width="150">摘要</th>
				<th width="140">申请日期</th>
				<th width="110">申请人</th>
			</tr>
			<xsl:for-each select="/html/Body/purchasemain/ul/li">
				<tr>
					<td class="center"><xsl:value-of select="position()"/></td>
					<td class="center">
						<xsl:element name="A">
							<xsl:attribute name="href">
								/purchase/purchase_inquiry_xiang.php?id=<xsl:value-of select="id"/>
							</xsl:attribute>
							<xsl:attribute name="class">table_a_operate</xsl:attribute>
							详细
						</xsl:element>
					<!-- <xsl:element name="A">
						<xsl:attribute name="href">
							javascript:;
						</xsl:attribute>
						<xsl:attribute name="class">table_a_operate doPrint</xsl:attribute>
						<xsl:attribute name="id"><xsl:value-of select="id"/></xsl:attribute>
						打印
					</xsl:element> -->
					</td>
					<td><xsl:value-of select="number"/></td>
					<td><xsl:value-of select="status_audit"/></td>
					<td><xsl:value-of select="status_receipt"/></td>
					<td><xsl:value-of select="status_refund"/></td>
					<td width="100"><xsl:value-of select="supplier_id"/></td>
					<td width="100"><xsl:value-of select="store_id"/></td>
					<td><xsl:value-of select="total"/></td>
					<td width="90"><xsl:value-of select="price"/></td>
					<td width="200"><xsl:value-of select="brief"/></td>
					<td><xsl:value-of select="action_date"/></td>
					<td width="110"><xsl:value-of select="staff_id"/></td>
				</tr>
			</xsl:for-each>
		</table>
		<xsl:if test="/html/Body/purchasemain/ul/@total = '0'">
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
	$('.customersMsg input[name="application_date"]').datetimepicker({
		language:'zh-CN',
		format:'yyyy-mm-dd',
		autoclose:true,
		minView:2,
	});
	$('input[name=clear]').click(function(){
		$('input[name=staff]').val('');
		$('input[name=application_date]').val('');
		$('select[name=status_refund]').val('');
		$('select[name=status_receipt]').val('');
		$('select[name=status_audit]').val('');
	});


	$('.doPrint').click(function(){
		var id=$(this).attr('id');
		$.ajax({
			url: '/purchase/purchase_inquiry_xiang.php',
			type: 'get',
			data: {action:'print',id:id}
		})
		.done(function(data) {
			var data = JSON.parse(data);
			console.log(data.details);
		});
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

		LODOP.PRINT_DESIGN();
		<!-- LODOP.PREVIEW(); -->
	}
</script>
</xsl:template>

</xsl:stylesheet>