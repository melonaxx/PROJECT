<?xml version="1.0" encoding="utf-8" ?>
<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">

<!-- UTF8 编码 -->
<xsl:include href="/style/header.xsl" />


<xsl:template name="text">

<script type="text/javascript" src="/js_encode/product_list_update.js"></script>
<script>
	var fn	= new Array();
	<xsl:for-each select="/html/Body/format_name/li">
		fn[fn.length]	= {'id':'<xsl:value-of select="@id" />', 'body':'<xsl:value-of select="." />'};
	</xsl:for-each>
	var fv	= new Array();
	<xsl:for-each select="/html/Body/format_value/li">
		fv[fv.length]	= {'id':'<xsl:value-of select="@id" />', 'name_id':'<xsl:value-of select="@name_id" />', 'body':'<xsl:value-of select="." />'};
	</xsl:for-each>
	var format_select	= new Array();
	format_select[1]	= <xsl:value-of select="/html/Body/format_name/@v1" />;
	format_select[2]	= <xsl:value-of select="/html/Body/format_name/@v2" />;
	format_select[3]	= <xsl:value-of select="/html/Body/format_name/@v3" />;
	format_select[4]	= <xsl:value-of select="/html/Body/format_name/@v4" />;
	format_select[5]	= <xsl:value-of select="/html/Body/format_name/@v5" />;
</script>
<script>
	<![CDATA[
	function change_format(index, val)
	{
		var obj_v	= getObject("format_value_" + index);
		obj_v.length	= 0;
		if(val > 0)
		{
			for(var i=0; i < fv.length; i++)
			{
				if(fv[i]['name_id'] == val)
				{
					var opt	= document.createElement("OPTION");
					opt.text	= fv[i]['body'];
					opt.value	= fv[i]['id'];
					obj_v.options[obj_v.length]	= opt;
				}
			}
		}
		var old_sel		= format_select[index];
		format_select[index]	= val;
		var old_opt		= document.createElement("OPTION");
		old_opt.text	= "";
		old_opt.value	= 0;
		if(old_sel > 0)
		{
			for(var j=1; j < 6; j++)
			{
				if(j == index)
				{
					continue;
				}
				var obj_n	= getObject("format_name_" + j);
				var new_opt	= document.createElement("OPTION");
				for(var n=0; n < fn.length; n++)
				{
					if(fn[n]['id'] == old_sel)
					{
						new_opt.text	= fn[n]['body'];
						new_opt.value	= fn[n]['id'];
					}
				}
				obj_n.options[obj_n.length]	= new_opt;
			}
		}
		if(val == 0)
		{
			return true;
		}
		for(var j=1; j < 6; j++)
		{
			if(j == index)
			{
				continue;
			}
			var obj_n	= getObject("format_name_" + j);
			for(var n=0; n < obj_n.length; n++)
			{
				if(obj_n[n].value == val)
				{
					obj_n.options.remove(n);
				}
			}
		}
	}
	]]>
</script>


<form class="form-inline margin_top" action="/product/product_list_update.php"  method="post">
<div>
 	<h4>商品信息</h4>
	<div  class="float_left" style="height:250px;width:700px">
		<div class="form-group">
			<label>商品名称：</label>
			<xsl:element name="INPUT">
			<xsl:attribute name="type">text</xsl:attribute>
			<!-- <xsl:attribute name="id">brand_id</xsl:attribute> -->
			<xsl:attribute name="name">name</xsl:attribute>
			<xsl:attribute name="value"><xsl:value-of select="/html/Body/product_info/name"/></xsl:attribute>
			<xsl:attribute name="class">form-control input-sm merger_three_row_8</xsl:attribute>
			<xsl:attribute name="placeholder">必填</xsl:attribute>
			<xsl:attribute name="data-toggle">tooltip</xsl:attribute>
			<xsl:attribute name="data-placement">bottom</xsl:attribute>
			<!-- <xsl:attribute name="placeholder">商品名称</xsl:attribute> -->
			<xsl:attribute name="title">必填</xsl:attribute>
			</xsl:element>
		</div>
		<div class="form-group">
			<label>商品编码：</label>
			<xsl:element name="INPUT">
				<xsl:attribute name="type">text</xsl:attribute>
				<xsl:attribute name="name">number</xsl:attribute>
				<xsl:attribute name="value"><xsl:value-of select="/html/Body/product_info/number"/></xsl:attribute>
				<xsl:attribute name="class">form-control input-sm</xsl:attribute>
				<xsl:attribute name="placeholder">不填写将自动生成</xsl:attribute>
			</xsl:element>
		</div>
		<div class="form-group">
			<label>商品品牌：</label>
			<select class="form-control input-sm" name="brand">
				<option value="0"></option>
				<xsl:for-each select="/html/Body/supplierMsg/type/ul/li">
					<xsl:element name="OPTION">
						<xsl:attribute name="value"><xsl:value-of select="value" /></xsl:attribute>
						<xsl:if test="value=/html/Body/supplierMsg/type/select_value"><xsl:attribute name="selected">selected</xsl:attribute></xsl:if>
						<xsl:value-of select="text" />
					</xsl:element>
				</xsl:for-each>
			</select>
		</div>
			<div class="form-group">
				<label>商品分类：</label>
				<select class="form-control input-sm" name="fenlei">
					<option value="0"></option>
					<xsl:for-each select="/html/Body/supplierMsg/fenlei/ul/li">
						<xsl:element name="OPTION">
							<xsl:attribute name="value"><xsl:value-of select="value" /></xsl:attribute>
							<xsl:if test="value=/html/Body/supplierMsg/fenlei/select_value"><xsl:attribute name="selected">selected</xsl:attribute></xsl:if>
							<xsl:value-of select="text" />
						</xsl:element>
					</xsl:for-each>
				</select>
			</div>
			<div class="form-group">
				<label>商品货号：</label>
				<xsl:element name="INPUT">
					<xsl:attribute name="type">text</xsl:attribute>
					<xsl:attribute name="id">exampleInputName2</xsl:attribute>
					<xsl:attribute name="class">form-control input-sm</xsl:attribute>
				</xsl:element>
			</div>
			<div class="form-group">
				<label>商品条码：</label>
				<xsl:element name="INPUT">
					<xsl:attribute name="type">text</xsl:attribute>
					<xsl:attribute name="class">form-control input-sm</xsl:attribute>
					<xsl:attribute name="value"><xsl:value-of select="/html/Body/product_info/bar_code"/></xsl:attribute>
					<xsl:attribute name="name">bar_code</xsl:attribute>		
				</xsl:element>
			</div>
			<div class="form-group">
				<label>产品类型：</label>
				<select class="form-control input-sm" name="product_type">
					<xsl:for-each select="/html/Body/supplierMsg/product_type/ul/li">
						<xsl:element name="OPTION">
							<xsl:attribute name="value"><xsl:value-of select="value" /></xsl:attribute>
							<xsl:if test="value=/html/Body/supplierMsg/product_type/select_value"><xsl:attribute name="selected">selected</xsl:attribute></xsl:if>
							<xsl:value-of select="text" />
						</xsl:element>
					</xsl:for-each>
				</select>
			</div>
			<div class="form-group">
				<label>是否二手：</label>
				<select class="form-control input-sm" name="product_quality">
					<xsl:for-each select="/html/Body/supplierMsg/product_quality/ul/li">
						<xsl:element name="OPTION">
							<xsl:attribute name="value"><xsl:value-of select="value" /></xsl:attribute>
							<xsl:if test="value=/html/Body/supplierMsg/product_quality/select_value"><xsl:attribute name="selected">selected</xsl:attribute></xsl:if>
							<xsl:value-of select="text" />
						</xsl:element>
					</xsl:for-each>
				</select>
			</div>
			<div class="form-group">
				<label class="margin_left_2">进价：</label>
				<div class="input-group col-xs-7">
				<div class="input-group-addon">￥</div>
				<xsl:element name="INPUT">
					<xsl:attribute name="type">text</xsl:attribute>
					<xsl:attribute name="class">form-control input-sm</xsl:attribute>
					<xsl:attribute name="style">width:110px;</xsl:attribute>	
					<xsl:attribute name="value"><xsl:value-of select="/html/Body/product_info/price_purchase"/></xsl:attribute>
					<xsl:attribute name="name">price_purchase</xsl:attribute>					
				</xsl:element>
			</div>
			</div>
			<div class="form-group">
				<label  class="margin_left_2">单位：</label>
				<select class="form-control input-sm" name="unit">
					<xsl:for-each select="/html/Body/supplierMsg/unit/ul/li">
						<xsl:element name="OPTION">
							<xsl:attribute name="value"><xsl:value-of select="value" /></xsl:attribute>
							<xsl:if test="value=/html/Body/supplierMsg/unit/select_value"><xsl:attribute name="selected">selected</xsl:attribute></xsl:if>
							<xsl:value-of select="text" />
						</xsl:element>
					</xsl:for-each>
				</select>
			</div>
			<div class="form-group">
				<label class="margin_left_1">零售价：</label>
				<div class="input-group col-xs-7">
				   <div class="input-group-addon">￥</div>
				   <xsl:element name="INPUT">
						<xsl:attribute name="type">text</xsl:attribute>
						<xsl:attribute name="class">form-control input-sm</xsl:attribute>
						<xsl:attribute name="value"><xsl:value-of select="/html/Body/product_info/price_display"/></xsl:attribute>
						<xsl:attribute name="style">width: 110px;</xsl:attribute>
						<xsl:attribute name="name">price_display</xsl:attribute>
					</xsl:element>
				</div>
			</div>
			<div class="form-group">
				<label class="margin_left_2">体积：</label>
				<div class="input-group col-xs-7">	
				   <xsl:element name="INPUT">
						<xsl:attribute name="type">text</xsl:attribute>
						<xsl:attribute name="class">form-control input-sm</xsl:attribute>
						<xsl:attribute name="value"><xsl:value-of select="/html/Body/product_info/volume"/></xsl:attribute>
						<xsl:attribute name="style">width: 105px;</xsl:attribute>
						<xsl:attribute name="name">volume</xsl:attribute>
					</xsl:element>
					<div class="input-group-addon">m³</div>
				</div>
			</div>
			<div class="form-group">
				<label class="margin_left_2">重量：</label>
				<div class="input-group col-xs-7">
				   <xsl:element name="INPUT">
						<xsl:attribute name="type">text</xsl:attribute>
						<xsl:attribute name="class">form-control input-sm</xsl:attribute>
						<xsl:attribute name="style">width: 107px;</xsl:attribute>
						<xsl:attribute name="value"><xsl:value-of select="/html/Body/product_info/volume"/></xsl:attribute>
						<xsl:attribute name="name">weight</xsl:attribute>
					</xsl:element>
				  	<div class="input-group-addon">kg</div>
				</div>
			</div>
			<div class="form-group">
				<label>商品备注：</label>
				<xsl:element name="INPUT">
					<xsl:attribute name="type">text</xsl:attribute>
					<xsl:attribute name="class">form-control input-sm</xsl:attribute>
					<xsl:attribute name="style">width:608px;</xsl:attribute>
					<xsl:attribute name="value"><xsl:value-of select="/html/Body/product_info/content"/></xsl:attribute>
					<xsl:attribute name="name">content</xsl:attribute>			
				</xsl:element>
			</div>
	</div>

	<div  class="float_right " style="height:250px;width:500px; " >
		 <div  class="float_left" style="height:198px;width:198px;border:1px solid #c0c0c0;text-align:center;padding-top:4px;border-radius:3px;" >
			<xsl:element name="IMG">
				<xsl:attribute name="src">text</xsl:attribute>
				<xsl:attribute name="class">img-rounded</xsl:attribute>
				<xsl:attribute name="style">height:188px;width:188px;</xsl:attribute>
			</xsl:element>
		</div>
		<div  class="float_left" style="width:500px;padding-top:12px" >
			<div class="form-group">
				<xsl:element name="A">
					<xsl:attribute name="href">#</xsl:attribute>
					<xsl:attribute name="id"><xsl:value-of select="@id"/></xsl:attribute>
					<xsl:attribute name="onclick">MessageBox('/product/product_commodity_dialog.php?id=<xsl:value-of select="@id"/>', '上传图片', 491, 200); return false</xsl:attribute>
					<xsl:element name="INPUT">
						<xsl:attribute name="type">submit</xsl:attribute>
						<xsl:attribute name="class">btn btn-default btn-sm btn_margin</xsl:attribute>
						<xsl:attribute name="value">上传图片</xsl:attribute>										
					</xsl:element>
				</xsl:element>




				<input class="btn btn-default btn-sm" type="reset" value="重置" />
			</div>
		</div>
	</div>
</div>
	<div class="float_left" style="width:1180px"><h4>商品规格</h4><div>
	<table class="table table-bordered">
		<tr>	
			<td width="200" class="center">操作</td>
			<!-- <td>规格编码</td> -->
			<td width="200">
				<select class="form-control form_no_border input-sm" id="format_name_1" name="format_name_1" onchange="change_format(1, this.value)">
					<option value="0">-- 无 --</option>
					<xsl:for-each select="/html/Body/format_name/li">
						<xsl:element name="OPTION">
							<xsl:attribute name="value"><xsl:value-of select="@id" /></xsl:attribute>
							<xsl:if test="/html/Body/format_name/@v1 = @id"><xsl:attribute name="selected">selected</xsl:attribute></xsl:if>
							<xsl:value-of select="." />
						</xsl:element>
					</xsl:for-each>
				</select>
			</td>
			<td width="200">
				<select class="form-control form_no_border input-sm" id="format_name_2" name="format_name_2" onchange="change_format(2, this.value)">
					<option value="0">-- 无 --</option>
					<xsl:for-each select="/html/Body/format_name/li">
						<xsl:element name="OPTION">
							<xsl:attribute name="value"><xsl:value-of select="@id" /></xsl:attribute>
							<xsl:if test="/html/Body/format_name/@v2 = @id"><xsl:attribute name="selected">selected</xsl:attribute></xsl:if>
							<xsl:value-of select="." />
						</xsl:element>
					</xsl:for-each>
				</select>		
			</td>
			<td width="200">
				<select class="form-control form_no_border input-sm" id="format_name_3" name="format_name_3" onchange="change_format(3, this.value)">
					<option value="0">-- 无 --</option>
					<xsl:for-each select="/html/Body/format_name/li">
						<xsl:element name="OPTION">
							<xsl:attribute name="value"><xsl:value-of select="@id" /></xsl:attribute>
							<xsl:if test="/html/Body/format_name/@v3 = @id"><xsl:attribute name="selected">selected</xsl:attribute></xsl:if>
							<xsl:value-of select="." />
						</xsl:element>
					</xsl:for-each>
				</select>			
			</td>
			<td width="200">
				<select class="form-control form_no_border input-sm" id="format_name_4" name="format_name_4" onchange="change_format(4, this.value)">
					<option value="0">-- 无 --</option>
					<xsl:for-each select="/html/Body/format_name/li">
						<xsl:element name="OPTION">
							<xsl:attribute name="value"><xsl:value-of select="@id" /></xsl:attribute>
							<xsl:if test="/html/Body/format_name/@v4 = @id"><xsl:attribute name="selected">selected</xsl:attribute></xsl:if>
							<xsl:value-of select="." />
						</xsl:element>
					</xsl:for-each>
				</select>			
			</td>
			<td width="200">
				<select class="form-control form_no_border input-sm" id="format_name_5" name="format_name_5" onchange="change_format(5, this.value)">
					<option value="0">-- 无 --</option>
					<xsl:for-each select="/html/Body/format_name/li">
						<xsl:element name="OPTION">
							<xsl:attribute name="value"><xsl:value-of select="@id" /></xsl:attribute>
							<xsl:if test="/html/Body/format_name/@v5 = @id"><xsl:attribute name="selected">selected</xsl:attribute></xsl:if>
							<xsl:value-of select="." />
						</xsl:element>
					</xsl:for-each>
				</select>			
			</td>
		</tr>
		<tr>	
			<td class="center"><a class="aa" href="javascript:;">清除</a></td>
			<!-- <td>0001</td> -->
			<td>
				<select class="form-control form_no_border input-sm" id="format_value_1" name="format_value_1">
					<xsl:for-each select="/html/Body/format_value/li">
						<xsl:if test="/html/Body/format_name/@v1 = @name_id">
						<xsl:element name="OPTION">
							<xsl:attribute name="value"><xsl:value-of select="@id" /></xsl:attribute>
							<xsl:value-of select="." />
						</xsl:element>
						</xsl:if>
					</xsl:for-each>
				</select>	
			</td>
			<td>
				<select class="form-control form_no_border input-sm" id="format_value_2" name="format_value_2">
					<xsl:for-each select="/html/Body/format_value/li">
						<xsl:if test="/html/Body/format_name/@v2 = @name_id">
						<xsl:element name="OPTION">
							<xsl:attribute name="value"><xsl:value-of select="@id" /></xsl:attribute>
							<xsl:value-of select="." />
						</xsl:element>
						</xsl:if>
					</xsl:for-each>
				</select>	
			</td>
			<td>
				<select class="form-control form_no_border input-sm" id="format_value_3" name="format_value_3">
					<xsl:for-each select="/html/Body/format_value/li">
						<xsl:if test="/html/Body/format_name/@v3 = @name_id">
						<xsl:element name="OPTION">
							<xsl:attribute name="value"><xsl:value-of select="@id" /></xsl:attribute>
							<xsl:value-of select="." />
						</xsl:element>
						</xsl:if>
					</xsl:for-each>
				</select>	
			</td>
			<td>
				<select class="form-control form_no_border input-sm" id="format_value_4" name="format_value_4">
					<xsl:for-each select="/html/Body/format_value/li">
						<xsl:if test="/html/Body/format_name/@v4 = @name_id">
						<xsl:element name="OPTION">
							<xsl:attribute name="value"><xsl:value-of select="@id" /></xsl:attribute>
							<xsl:value-of select="." />
						</xsl:element>
						</xsl:if>
					</xsl:for-each>
				</select>	
			</td>
			<td>
				<select class="form-control form_no_border input-sm" id="format_value_5" name="format_value_5">
					<xsl:for-each select="/html/Body/format_value/li">
						<xsl:if test="/html/Body/format_name/@v5 = @name_id">
						<xsl:element name="OPTION">
							<xsl:attribute name="value"><xsl:value-of select="@id" /></xsl:attribute>
							<xsl:value-of select="." />
						</xsl:element>
						</xsl:if>
					</xsl:for-each>
				</select>	
			</td>
		</tr>
	</table>
</div>

<div class="table_operate_block"><h4>商品属性</h4> <span class="btn btn-default btn-sm display_show">显示</span><span class="btn btn-default btn-sm display_nonea" style="display:none;">隐藏</span></div>




<!-- 自定义商品属性 -->
<div style="display:none;" class="propertys">
<!-- 	<xsl:for-each select="/html/Body/attrib_name/li">
	<div class="form-group nature">
		<div class="shou_attributes">
			<xsl:element name="DIV">
			</xsl:element>
			<xsl:value-of select="." /></div>：
			<select class="form-control form_no_border input-sm" name="attrib">
				<xsl:attribute name="class">form-control input-sm</xsl:attribute>
				<xsl:attribute name="name">purchase_channels</xsl:attribute>
				<xsl:attribute name="src">text</xsl:attribute>
				<xsl:variable name="pid" select="@id" />			
					<xsl:for-each select="/html/Body/attrib_value/li">
						<xsl:if test="@attrib_id = $pid">						
							<xsl:element name="OPTION">
								<xsl:attribute name="value"><xsl:value-of select="@value_id" /></xsl:attribute>
								<xsl:value-of select="." />		
							</xsl:element>
						</xsl:if>		
					</xsl:for-each>
			</select>
	</div>
	</xsl:for-each> -->
	<table class="table table-bordered table-property ttbale  table-hover">
		<div class="table_operate_block">
			<input class="btn btn-default btn-sm btn_margin propertyAdd" type="button" value="添加" />
			<input class="btn btn-default btn-sm propertyDeleteaa" type="button" value="删除" />
		</div>
		<tr>
			<th class="table_th_number">序号</th>
			<th class="center table_th_checkbox"><input type="checkbox" name="select_all" /></th>
			<th style="width:160px;">属性</th>
			<th style="width:638px;">属性值</th>
		</tr>
		<xsl:for-each select="/html/Body/likun/ul/li">
			<tr>
				<td class="center"><xsl:value-of select="num"/></td>
				<td class="center"><input type="checkbox" name="select_one" /></td>	
				<td>
					<select class="form-control form_no_border input-sm attribute" name="attrib_id[]">
						<option value="0">-- 无 --</option>
						<xsl:for-each select="/html/Body/product/li">
							<xsl:element name="OPTION">
								<xsl:attribute name="value"><xsl:value-of select="value" /></xsl:attribute>
								<xsl:if test="value=select_value"><xsl:attribute name="selected">selected</xsl:attribute></xsl:if>
								<xsl:value-of select="text" />
							</xsl:element>
						</xsl:for-each>
					</select>						
				</td>
				<td class="form-group namena">
					<select class="form-control form_no_border input-sm" name="value_id[]" id="bb">
						<xsl:for-each select="/html/Body/array/ul/li">
							<xsl:element name="OPTION">
								<xsl:attribute name="value"><xsl:value-of select="value"/></xsl:attribute>
								<xsl:if test="value=select_value"><xsl:attribute name="selected">selected</xsl:attribute></xsl:if>
								<xsl:value-of select="text" />
							</xsl:element>
						</xsl:for-each>
					</select>						
				</td>
			</tr>
		</xsl:for-each>
	</table>
</div>
<div class="float_left">
	<div class="table_operate_block">
		<h4>商品配件</h4>
		<input class="btn btn-default btn-sm btn_margin customersAdd" type="button" value="添加" />
		<input class="btn btn-default btn-sm goodsDeleteaa" type="button" value="删除" />
	</div>
	<div class="table_operate_block">
		<table class="table table-bordered table-accessories ttbale  table-hover">
			<tr>
				<th class="table_th_number">序号</th>
				<th class="center table_th_checkbox"><input type="checkbox" name="select_all" /></th>
				<th style="width:160px;">搜索</th>
				<th class="center" style="width:140px;">图片</th>
				<th style="width:638px;">商品名称</th>
				<th style="width:170px;">数量</th>
			</tr>
			<xsl:for-each select="/html/Body/accessory/ul/li">
			<tr>
				<td class="center"><xsl:value-of select="num"/></td>
				<td class="center"><input type="checkbox" name="select_one" /></td>	
				<td id="seek" class="seek"><input type="text" class="form-control input-sm merger_three_row_4 input_border seach" placeholder="搜索"/></td>
				<td class="center">
					<xsl:element name="IMG">
						<xsl:attribute name="src">text</xsl:attribute>
						<xsl:attribute name="class">img-rounded</xsl:attribute>
					</xsl:element>											
				</td>
				<td class="form-group namena">
					<select class="form-control form_no_border input-sm" name="product[]">
						<xsl:element name="OPTION">
							<xsl:attribute name="value"><xsl:value-of select="value"/></xsl:attribute>
							<xsl:if test="value=select_value"><xsl:attribute name="selected">selected</xsl:attribute></xsl:if>
							<xsl:value-of select="text" />
						</xsl:element>
					</select>
					<div class="div"></div>
				</td>
				<td>
					<xsl:element name="INPUT">
						<xsl:attribute name="class">form-control form_no_border input-sm</xsl:attribute>
						<xsl:attribute name="value"><xsl:value-of select="total"/></xsl:attribute>
						<xsl:attribute name="name">total[]</xsl:attribute>
					</xsl:element>
				</td>
			</tr>
			</xsl:for-each>
		</table>
		<xsl:element name="INPUT">
			<xsl:attribute name="type">hidden</xsl:attribute>
			<xsl:attribute name="value"><xsl:value-of select="/html/Body/product_info/id"/></xsl:attribute>
			<xsl:attribute name="name">id</xsl:attribute>
		</xsl:element>
		<input class="btn btn-default btn-sm btn_margin" type="submit" name="send" value="提交" />
		<input class="btn btn-default btn-sm" type="reset" value="重置" />
	</div>
</div>


</div>   
</form>
</xsl:template>
</xsl:stylesheet>