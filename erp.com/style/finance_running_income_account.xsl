<?xml version="1.0" encoding="utf-8" ?>
<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<!-- UTF8 编码 -->

<xsl:include href="/style/header.xsl" />

<xsl:template name="text">

<script type="text/javascript" src="/js_encode/crm_business_customers.js"></script>
<!--  -->
<script type="text/javascript">

	var sheng = '<xsl:value-of select="/html/Body/sheng" />';
	var shi = '<xsl:value-of select="/html/Body/shi" />';
	var xian = '<xsl:value-of select="/html/Body/xian" />';
</script>
<style>

</style>
<script type="text/javascript" src="/js_encode/finance_running_account.js"></script>
<div class="modal fade" id="confirm" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
	<div class="modal-dialog modal-sm" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
				<h4 class="modal-title" id="myModalLabel">提示</h4>
			</div>
			<div class="modal-body">
	  			您确定要删除<span class="number">1</span>条数据吗？
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default btn-sm ok" data-dismiss="modal">确定</button>
				<button type="button" class="btn btn-default btn-sm cancel" data-dismiss="modal">取消</button>
			</div>
		</div>
	</div>
</div>
<div class="mainBody">
	<ul id="myTab" class="nav nav-tabs">
	   <li><a href="/finance/finance_running_account.php">资金流水</a></li>
	   <li class="active"><a>收入科目</a></li>
	   <li><a href="/finance/finance_running_spend_account.php">支出科目</a></li>
	</ul>
	<div  class="tab-pane fade in active">
		<div class="tab-pane" id="RWBE1">
			<div class=" " style="clear:both;">
   				<div class="table_operate_block">
	   				<h4></h4>
					<input class="btn btn-default btn-sm btn_margin payAdd1" type="button" value="添加" onclick="MessageBox('/finance/finance_running_incomeadd_account.php', '添加收入科目', 300, 150)"/>
				</div>
				<table class="table table-bordered  ttbale  table-pay">
					<tr>
						<th class="table_th_number">序号</th>
						<th class="table_th_number" style="width:100px;">操作</th>
						<th width="1054px;">收入科目</th>
					</tr>
					<xsl:for-each select="/html/Body/ul/li">
					<tr>
						<td><center><xsl:value-of select="no"/></center></td>
						<td class="center">
							<xsl:element name="A">
								<xsl:attribute name="href">/finance/finance_running_incomeedit_account.php?id=<xsl:value-of select="id"/></xsl:attribute>
								<xsl:attribute name="onclick">MessageBox('/finance/finance_running_incomeedit_account.php?id=<xsl:value-of select="id"/>', '修改收入科目信息', 300, 150); return false</xsl:attribute>
								<xsl:attribute name="style">margin-right:14; return false</xsl:attribute>
								修改
							</xsl:element>
							<xsl:element name="SPAN">
								<xsl:attribute name="style">cursor:pointer</xsl:attribute>
								<xsl:attribute name="class">table_a_operate</xsl:attribute>
								<xsl:attribute name="onclick">MessageBox('/finance/finance_running_incomedelete_account.php?id=<xsl:value-of select="id" />', '删除收入科目', 320, 90)</xsl:attribute>
								<xsl:text>删除</xsl:text>
							</xsl:element>
						</td>
						<td><xsl:value-of select="name"/></td>
					</tr>
					</xsl:for-each>
				</table>
				<!-- <xsl:call-template name="page"></xsl:call-template> -->
			</div>
		</div>
	</div>
</div>

</xsl:template>

</xsl:stylesheet>
