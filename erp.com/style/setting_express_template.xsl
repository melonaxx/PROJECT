<?xml version="1.0" encoding="utf-8" ?>
<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<!-- UTF8 编码 -->

<xsl:include href="/style/header.xsl" />

<xsl:template name="text">

	<script>
		$('.images').hover(function(){ 
			var t = $(this).position().top - 160 + 'px';
			var l = $(this).position().left + 110 + 'px';
			$(this).next().css("display","block");
			$(this).next().css("top",t);
			$(this).next().css("left",l);
		},function(){
			$(this).next().css("display","none");
		});
	</script>

<!-- 提示框 -->
<div id="confirm" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none;width:268px;margin:65px auto">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
			<h4 class="modal-title" id="myModalLabel">提示</h4>
		</div>
		<div class="modal-body" style="margin-left:20px">确定要删除吗？<span class="number"></span>
		</div>
		<div class="modal-footer">
			<button type="button" class="btn btn-default btn-sm ok" data-dismiss="modal">确定</button>
			<button type="button" class="btn btn-default btn-sm cancel" data-dismiss="modal">取消</button>
		</div>
	</div>	
</div>
	
<div class="mainBody">
	<ul id="myTab" class="nav nav-tabs nav_tabs_margin">
	   <li class="active">
		  <a href="#" data-toggle="tab">快递单模板</a>
	   </li>
	   <li><a href="/setting/setting_deliver_template.php">发货单模板</a></li>
	   <li><a href="/setting/setting_order_template.php">配货单模板</a></li>
	</ul>
	
	<div class="headMsg table_operate_block">
		<form action="/setting/setting_express_template.php" method="post" class="form-inline">
			<div class="form-group float_right margin0">
				<div class="input-group">
					<xsl:element name="INPUT">
						<xsl:attribute name="type">text</xsl:attribute>
						<xsl:attribute name="name">find</xsl:attribute>
						<xsl:attribute name="class">form-control input-sm input_search</xsl:attribute>
						<xsl:attribute name="placeholder">输入模板名称或快递公司名查询</xsl:attribute>
						<xsl:attribute name="value"><xsl:value-of select="/html/Body/find" /></xsl:attribute>
					</xsl:element>
					<span class="input-group-btn">
						<button class="btn btn-default btn-sm">搜索</button>
					</span>
				</div>
			</div>
		</form>
		<a href="/deliver/deliver_design_express_add.php" target="_blank"><input class="btn btn-default btn-sm btn_margin" type="button" value="添加" /></a>
		<a href="/setting/setting_express_template_sys.php" target="_blank"><input class="btn btn-default btn-sm btn_margin" type="button" value="系统模板" /></a>

	</div>
	<table style="" class="table tab_select table-bordered table-hover">
		<tr>
			<th class="table_th_number" rowspan="2">序号</th>
			<th style="width:200px;" class="table_th_number" rowspan="2">操作</th>
			<th style="width:210px;text-align:center" rowspan="2">模板图片</th>
			<th style="width:188px;text-align:center" rowspan="2">模板名称</th>
			<th style="width:150px;text-align:center" rowspan="2">快递公司</th>
			<th style="width:240px;text-align:center" colspan="4">纸张信息（单位：毫米）</th>
			<th style="width:166px;text-align:center" rowspan="2">最后修改时间</th>
		</tr>
		<tr>
			<th>向下偏</th>
			<th>向右偏</th>
			<th>纸张宽</th>
			<th>纸张高</th>
		</tr>
		
		<xsl:for-each select="/html/Body/expressInfo/item">
		<tr>
			<td class="center"><xsl:value-of select="position()"/></td>
			
			<td class="center">
				<xsl:element name="A">
					<xsl:attribute name="class">table_a_operate</xsl:attribute>
					<xsl:attribute name="target">_blank</xsl:attribute>
					<xsl:attribute name="href">/deliver/deliver_design_express.php?template_id=<xsl:value-of select = 'id'/></xsl:attribute>
				<xsl:text>设计</xsl:text>
				</xsl:element>
				
				<xsl:element name="A">
					<xsl:attribute name="class">table_a_operate delete</xsl:attribute>
					<xsl:attribute name="href">/setting/setting_delete_express_template.php?id=<xsl:value-of select = 'id'/></xsl:attribute>
					<xsl:attribute name="is_default"><xsl:value-of select = 'is_default'/></xsl:attribute>
				<xsl:text>删除</xsl:text>
				</xsl:element>

				<xsl:element name="A">
					<xsl:attribute name="class">table_a_operate</xsl:attribute>
					<xsl:attribute name="href">/setting/setting_express_template.php?default="Y"&amp;id=<xsl:value-of select = 'id'/>&amp;express_id=<xsl:value-of select = 'express_name/@express_id'/></xsl:attribute>
				<!-- <xsl:text>设为默认模板</xsl:text> -->
					<xsl:value-of select = 'is_default'/>
				</xsl:element>
			</td>			
			<td>
				<xsl:element name="img">
					<xsl:attribute name="src"><xsl:value-of select="image" /></xsl:attribute>
					<xsl:attribute name="width">100</xsl:attribute>
					<xsl:attribute name="height">70</xsl:attribute>
					<xsl:attribute name="class">images center</xsl:attribute>
				</xsl:element>
				<xsl:element name="img">
					<xsl:attribute name="src"><xsl:value-of select="image" /></xsl:attribute>
					<xsl:attribute name="width">670</xsl:attribute>
					<xsl:attribute name="height">370</xsl:attribute>
					<xsl:attribute name="style">display:none;position:absolute;z-index:1000;</xsl:attribute>
					<xsl:attribute name="class">bigimg center</xsl:attribute>
				</xsl:element>
			</td>
			<td><xsl:value-of select="name" /></td>
			<!-- <td><xsl:value-of select="express_name" /></td> -->
			<xsl:element name="td">
				<xsl:attribute name="express_id"><xsl:value-of select="express_name/@express_id" /></xsl:attribute>
				<xsl:value-of select="express_name" />
			</xsl:element>
			<td><xsl:value-of select="paper_top" /></td>
			<td><xsl:value-of select="paper_left" /></td>
			<td><xsl:value-of select="paper_width" /></td>
			<td><xsl:value-of select="paper_height" /></td>
			<td><xsl:value-of select="pub_date" /></td>
			
		</tr>
		</xsl:for-each>
	</table>
	<xsl:if test="/html/Body/expressInfo/@total = '0'">
		<div class="imgs" style="margin:0 auto; width:100%;text-align:center">
			<div class="img">
				<img src="/images/empty.jpg"  alt=""/>
				<span>没有找到记录，请调整搜索条件。</span>
			</div>
		</div>
	</xsl:if>
	<xsl:call-template name="page"></xsl:call-template>
</div>
<script>
	//单个删除
	$('.table .delete').click(function () {
		if($(this).attr("is_default")==""){
			alert("不能删除默认模板！");
			return false;
		}

		$('#confirm').modal('show');
		var thisHref = $(this).attr('href');
		$('#confirm .ok').click(function () {
			location.href = thisHref;
		});
		return false;
	});
</script>
</xsl:template>

</xsl:stylesheet>