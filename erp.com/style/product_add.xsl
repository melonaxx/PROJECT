<?xml version="1.0" encoding="utf-8" ?>
<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<!-- UTF8 编码 -->
<xsl:include href="/style/header.xsl" />

<xsl:template name="text">

<style>
	td.dotted {border: 1px dashed #cccccc; position:relative}
	.image_bar	{width:90px; height:20px; line-height:20px; background-color:#333333; color:#ffffff; position:absolute; left:0; top:70px; display:none}
	.image_bar label:hover	{color:#cccccc; cursor:pointer}
	.image_bar label.left	{width:45px; text-align:left; text-indent:13px; float:left}
	.image_bar label.right	{width:45px; text-align:left; text-indent:6px; float:right}
	.float_left h4{display:inline-block;}
	.btn_margin{margin-bottom:12px;margin-top:0px;}
	.table_operate_block{margin-bottom:0px;}
	.store_checkbox{width:20px;height:20px;}
	.store_remove:hover{color:blue;}
	.cangku{cursor:pointer;}
	.cangku:hover{background:#F5F5F5;}
	#manager{cursor:text;}
</style>
<script>
	var fn	= new Array();
	<xsl:for-each select="/html/Body/format_name/li">
		fn[fn.length]	= {'id':'<xsl:value-of select="@id" />', 'body':'<xsl:value-of select="." />'};
	</xsl:for-each>
	var fv	= new Array();
	<xsl:for-each select="/html/Body/format_value/li">
		fv[fv.length]	= {'id':'<xsl:value-of select="@id" />', 'name_id':'<xsl:value-of select="@name_id" />', 'body':'<xsl:value-of select="." />'};
	</xsl:for-each>
	var an	= new Array();
	<xsl:for-each select="/html/Body/attrib_name/li">
		an[an.length]	= {'id':'<xsl:value-of select="@id" />', 'body':'<xsl:value-of select="name" />'};
	</xsl:for-each>
	var av	= new Array();
	<xsl:for-each select="/html/Body/attrib_value/li">
		av[av.length]	= {'id':'<xsl:value-of select="@value_id" />', 'name_id':'<xsl:value-of select="@attrib_id" />', 'body':'<xsl:value-of select="." />'};
	</xsl:for-each>
	var attrib_index	= 0;
	var accessory_index	= 0;
	var format_select	= new Array();
	format_select[1]	= <xsl:value-of select="/html/Body/format_name/@v1" />;
	format_select[2]	= <xsl:value-of select="/html/Body/format_name/@v2" />;
	format_select[3]	= <xsl:value-of select="/html/Body/format_name/@v3" />;
	format_select[4]	= <xsl:value-of select="/html/Body/format_name/@v4" />;
	format_select[5]	= <xsl:value-of select="/html/Body/format_name/@v5" />;
</script>
<script>
	<![CDATA[
	$(function(){
		$('.form-inline').submit(function()
		{

			var nm = $("#test1").html().length;
			var type = $("select[name='product_type']").val();
			if(nm==0 && type !='Virtual'){
				alert("请选择商品仓库");
				return false;
			}

			var m = $('input[name=name]').val();
			if(m == "")
			{
				$("input[name=name]").focus();
				$('input[name=name]').addClass('error_color');
				$('input[name=name]').tooltip('show');
				return false;
			}
			else
			{
				$('input[name=mobile]').removeClass('error_color');
				$('input[name=mobile]').tooltip('hide');
				getObject('add_ok').disabled	= true;
				return true;
			}
		});
	})
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
	function clear_format()
	{
		getObject("format_name_1").selectedIndex	= 0;
		getObject("format_name_2").selectedIndex	= 0;
		getObject("format_name_3").selectedIndex	= 0;
		getObject("format_name_4").selectedIndex	= 0;
		getObject("format_name_5").selectedIndex	= 0;
		change_format(1, 0);
		change_format(2, 0);
		change_format(3, 0);
		change_format(4, 0);
		change_format(5, 0);
	}
	function add_attrib()
	{
		attrib_index++;
		var line_obj	= document.createElement("TR");
		line_obj.id		= "attrib_obj_" + attrib_index;
		var td_1_obj	= document.createElement("TD");
		var td_2_obj	= document.createElement("TD");
		var td_3_obj	= document.createElement("TD");
		var td_4_obj	= document.createElement("TD");
		td_1_obj.className	= "center";
		td_2_obj.className	= "center";
		td_4_obj.className	= "form-group namena";

		td_1_obj.innerHTML	= attrib_index;
		var delete_obj		= document.createElement("A");
		delete_obj.setAttribute("ix", attrib_index);
		delete_obj.innerHTML	= "删除";
		delete_obj.href			= "#";
		delete_obj.onclick		= function()
		{
			var ix	= this.getAttribute("ix");
			getObject("attrib_list_obj").removeChild(getObject("attrib_obj_" + ix));
			return false;
		};
		td_2_obj.appendChild(delete_obj);

		var select_obj			= document.createElement("SELECT");
		select_obj.setAttribute("ix", attrib_index);
		select_obj.id			= "attrib_id_" + attrib_index;
		select_obj.name			= "attrib_id[" + attrib_index + "]";
		select_obj.className	= "form-control form_no_border input-sm attribute";
		select_obj.onchange		= function()
		{
			change_attrib(this.getAttribute("ix"), this.value)
		};
		var opt		= document.createElement("OPTION");
		opt.text    = "-- 无 --";
		opt.value   = 0;
		select_obj.options[select_obj.length] = opt;
		for(var k=0; k < an.length; k++)
		{
			var opt		= document.createElement("OPTION");
			opt.text    = an[k]['body'];
			opt.value   = an[k]['id'];
			select_obj.options[select_obj.length] = opt;
		}
		td_3_obj.appendChild(select_obj);

		var select_obj			= document.createElement("SELECT");
		select_obj.id			= "value_id_" + attrib_index;
		select_obj.name			= "value_id[" + attrib_index + "]";
		select_obj.className	= "form-control form_no_border input-sm";
		td_4_obj.appendChild(select_obj);

		line_obj.appendChild(td_1_obj);
		line_obj.appendChild(td_2_obj);
		line_obj.appendChild(td_3_obj);
		line_obj.appendChild(td_4_obj);

		getObject("attrib_list_obj").appendChild(line_obj);
	}
	function change_attrib(index, attrib_id)
	{
		var value_obj		= getObject("value_id_" + index);
		value_obj.length	= 0;
		for(var k=0; k < av.length; k++)
		{
			if(av[k]['name_id'] == attrib_id)
			{
				var opt		= document.createElement("OPTION");
				opt.text    = av[k]['body'];
				opt.value   = av[k]['id'];
				value_obj.options[value_obj.length] = opt;
			}
		}
	}
	function add_accessory()
	{
		accessory_index++;
		var line_obj	= document.createElement("TR");
		line_obj.id		= "accessory_obj_" + accessory_index;
		var td_1_obj	= document.createElement("TD");
		var td_2_obj	= document.createElement("TD");
		var td_3_obj	= document.createElement("TD");
		var td_4_obj	= document.createElement("TD");
		var td_5_obj	= document.createElement("TD");
		var td_6_obj	= document.createElement("TD");
		var td_7_obj	= document.createElement("TD");
		var td_8_obj	= document.createElement("TD");

		td_1_obj.className	= "center";
		td_2_obj.className	= "center";
		td_3_obj.className	= "seek";
		td_4_obj.className	= "center";
		td_5_obj.className	= "center";

		td_1_obj.innerHTML	= accessory_index;

		var delete_obj		= document.createElement("A");
		delete_obj.setAttribute("ix", accessory_index);
		delete_obj.innerHTML	= "删除";
		delete_obj.href			= "#";
		delete_obj.onclick		= function()
		{
			var ix	= this.getAttribute("ix");
			getObject("accessory_list_obj").removeChild(getObject("accessory_obj_" + ix));
			return false;
		};
		td_2_obj.appendChild(delete_obj);

		var search_obj			= document.createElement("INPUT");
		search_obj.type			= "text";
		search_obj.className	= "form-control input-sm merger_three_row_4 input_border seach";
		search_obj.setAttribute("ix", accessory_index);
		search_obj.setAttribute("placeholder", "搜索");
		search_obj.onkeyup		= function()
		{
			search_product(this.value, "accessory_id_" + this.getAttribute("ix"));
		};
		td_3_obj.appendChild(search_obj);

		var select_obj			= document.createElement("SELECT");
		select_obj.className	= "form-control input-sm form_no_border find";
		select_obj.id			= "accessory_id_" + accessory_index;
		select_obj.name			= "accessory_id[" + accessory_index + "]";
		select_obj.setAttribute("ix", accessory_index);
		select_obj.setAttribute("data-toggle", "tooltip");
		select_obj.setAttribute("data-placement", "bottom");
		select_obj.onchange		= function()
		{
			var index	= this.getAttribute("ix");
			change_product_id(this.value, "accessory_image_" + index, "accessory_price_" + index, "accessory_unit_" + index);
		};
		td_4_obj.appendChild(select_obj);

		var image_obj			= document.createElement("IMG");
		image_obj.id			= "accessory_image_" + accessory_index;
		image_obj.width			= "60";
		image_obj.height		= "60";
		image_obj.src			= "/images/space.gif";
		td_5_obj.appendChild(image_obj);

		td_6_obj.id				= "accessory_unit_" + accessory_index;

		var price_obj			= document.createElement("FONT");
		price_obj.innerHTML		= "￥";
		td_7_obj.appendChild(price_obj);

		var price_obj			= document.createElement("FONT");
		price_obj.id			= "accessory_price_" + accessory_index;
		price_obj.innerHTML		= "0.00";
		td_7_obj.appendChild(price_obj);

		var total_obj			= document.createElement("INPUT");
		total_obj.id			= "accessory_total_" + accessory_index;
		total_obj.name			= "accessory_total[" + accessory_index + "]";
		total_obj.value			= "1";
		total_obj.style.width	= "70px";
		total_obj.className		= "form-control input-sm form_no_border num";
		total_obj.setAttribute("data-toggle", "tooltipx");
		total_obj.setAttribute("data-placement", "bottom");
		total_obj.setAttribute("data-original-title", "商品数量至少为1");
		total_obj.onkeyup		= function()
		{
			this.value	= this.value.replace(/[^\d]/g,'');
		};
		td_8_obj.appendChild(total_obj);

		line_obj.appendChild(td_1_obj);
		line_obj.appendChild(td_2_obj);
		line_obj.appendChild(td_3_obj);
		line_obj.appendChild(td_4_obj);
		line_obj.appendChild(td_5_obj);
		line_obj.appendChild(td_6_obj);
		line_obj.appendChild(td_7_obj);
		line_obj.appendChild(td_8_obj);

		getObject("accessory_list_obj").appendChild(line_obj);
	}
	var product_status	= null;
	function change_product_image(index, image)
	{
		var img_obj	= getObject("image_object_" + index);
		img_obj.innerHTML	= "";
		if(image == "" || image == "/images/space.gif")
		{
			getObject("product_image_" + index).value	= 0;
			var link_obj		= document.createElement("A");
			link_obj.href		= "#";
			link_obj.innerHTML	= "上传";
			link_obj.setAttribute("ix", index);
			link_obj.onclick	= function()
			{
				var ix		= this.getAttribute("ix");
				var href	= '/product/product_edit_photo.php?index=' + ix;
				MessageBox(href, '上传图片', 490, 200);
				return false;
			};
			img_obj.appendChild(link_obj);
			return false;
		}
		var label_left			= document.createElement("LABEL");
		label_left.className	= "left";
		label_left.setAttribute("ix", index);
		label_left.onclick		= function()
		{
			var ix		= this.getAttribute("ix");
			var href	= '/product/product_edit_photo.php?index=' + ix;
			MessageBox(href, '上传图片', 490, 200);
			return false;
		};
		label_left.innerHTML	= "编辑";

		var label_right			= document.createElement("LABEL");
		label_right.className	= "right";
		label_right.setAttribute("ix", index);
		label_right.onclick		= function()
		{
			var ix		= this.getAttribute("ix");
			change_product_image(ix, "");
		};
		label_right.innerHTML	= "删除";

		var tool_bar		= document.createElement("DIV");
		tool_bar.id			= "photo_bar_" + index;
		tool_bar.className	= "image_bar";
		tool_bar.onmouseover	= function()
		{
			this.style.display	= 'block';
		};
		tool_bar.onmouseout		= function()
		{
			this.style.display	= 'none';
		};

		tool_bar.appendChild(label_left);
		tool_bar.appendChild(label_right);
		img_obj.appendChild(tool_bar);

		var photo_obj		= document.createElement("IMG");
		photo_obj.width		= 90;
		photo_obj.height	= 90;
		photo_obj.setAttribute("ix", index);
		photo_obj.src		= image;
		photo_obj.onmouseover	= function()
		{
			var ix	= this.getAttribute("ix");
			getObject("photo_bar_" + ix).style.display	= 'block';
		};
		photo_obj.onmouseout	= function()
		{
			var ix	= this.getAttribute("ix");
			getObject("photo_bar_" + ix).style.display	= 'none';
		};
		img_obj.appendChild(photo_obj);

	}
	]]>
</script>


<form class="form-inline margin_top" method="post" action="/product/product_add.php">

<h4>商品信息</h4>


<div class="float_left" style="height:250px;width:700px">
	<div class="form-group">
		<label>商品编码：</label>
		<xsl:element name="INPUT">
			<xsl:attribute name="name">number</xsl:attribute>
			<xsl:attribute name="class">form-control input-sm</xsl:attribute>
			<xsl:attribute name="placeholder">不填写将自动生成</xsl:attribute>
		</xsl:element>
	</div>
	<div class="form-group">
		<label>商品名称：</label>
		<xsl:element name="INPUT">
			<xsl:attribute name="name">name</xsl:attribute>
			<xsl:attribute name="autocomplete">off</xsl:attribute>
			<xsl:attribute name="class">form-control input-sm merger_two_row_4</xsl:attribute>
			<xsl:attribute name="placeholder">必填</xsl:attribute>
			<!-- <xsl:attribute name="data-toggle">tooltip</xsl:attribute> -->
			<!-- <xsl:attribute name="data-placement">bottom</xsl:attribute> -->
			<!-- <xsl:attribute name="title">必填</xsl:attribute> -->
		</xsl:element>
	</div>
	<div class="form-group">
		<label>商品品牌：</label>
		<select class="form-control input-sm" name="brand_id">
			<option value="0"></option>
			<xsl:for-each select="/html/Body/brand/li">
				<xsl:element name="OPTION">
					<xsl:attribute name="value"><xsl:value-of select="@id" /></xsl:attribute>
					<xsl:if test="@current = 'Y'"><xsl:attribute name="selected">true</xsl:attribute></xsl:if>
					<xsl:value-of select="." />
				</xsl:element>
			</xsl:for-each>
		</select>
	</div>
	<div class="form-group">
		<label>所属店铺：</label>
		<select class="form-control input-sm" name="shop_user_id">
			<xsl:for-each select="/html/Body/shop/li">
				<xsl:element name="OPTION">
					<xsl:attribute name="value"><xsl:value-of select="@id"/></xsl:attribute>
					<xsl:value-of select="."/>
				</xsl:element>
			</xsl:for-each>
		</select>
	</div>
	<div class="form-group">
		<label>商品分类：</label>
		<select class="form-control input-sm" name="category_id">
			<option value="0"></option>
			<xsl:for-each select="/html/Body/category/li">
				<xsl:element name="OPTION">
					<xsl:attribute name="value"><xsl:value-of select="@id" /></xsl:attribute>
					<xsl:if test="@current = 'Y'"><xsl:attribute name="selected">selected</xsl:attribute></xsl:if>
					<xsl:value-of select="." />
				</xsl:element>
			</xsl:for-each>
		</select>
	</div>
	<div class="form-group">
		<label>产品类型：</label>
		<select class="form-control input-sm" name="product_type">
			<xsl:for-each select="/html/Body/product_type/li">
				<xsl:element name="OPTION">
					<xsl:attribute name="value"><xsl:value-of select="@name" /></xsl:attribute>
					<xsl:value-of select="." />
				</xsl:element>
			</xsl:for-each>
		</select>
	</div>
	<div class="form-group">
		<label>是否二手：</label>
		<select class="form-control input-sm" name="product_quality">
			<xsl:for-each select="/html/Body/product_quality/li">
				<xsl:element name="OPTION">
					<xsl:attribute name="value"><xsl:value-of select="@name" /></xsl:attribute>
					<xsl:value-of select="." />
				</xsl:element>
			</xsl:for-each>
		</select>
	</div>
	<div class="form-group">
		<label>　　单位：</label>
		<select class="form-control input-sm" name="parts_id">
			<xsl:for-each select="/html/Body/parts/li">
				<xsl:element name="OPTION">
					<xsl:attribute name="value"><xsl:value-of select="@id" /></xsl:attribute>
					<xsl:value-of select="." />
				</xsl:element>
			</xsl:for-each>
		</select>
	</div>
	<div class="form-group">
		<label>商品货号：</label>
		<xsl:element name="INPUT">
			<xsl:attribute name="name">serial_number</xsl:attribute>
			<xsl:attribute name="class">form-control input-sm</xsl:attribute>
		</xsl:element>
	</div>
	<div class="form-group">
		<label>商品条码：</label>
		<xsl:element name="INPUT">
			<xsl:attribute name="name">bar_code</xsl:attribute>
			<xsl:attribute name="class">form-control input-sm</xsl:attribute>
		</xsl:element>
	</div>
	<div class="form-group">
		<label>商品数量：</label>
		<xsl:element name="INPUT">
			<xsl:attribute name="name">total</xsl:attribute>
			<xsl:attribute name="disabled">true</xsl:attribute>
			<xsl:attribute name="class">form-control input-sm</xsl:attribute>
			<xsl:attribute name="onkeyup">this.value=this.value.replace(/\D/g,'')</xsl:attribute>
		</xsl:element>
	</div>


	<div class="form-group">
		<label>　　体积：</label>
		<div class="input-group col-xs-7">
			<xsl:element name="INPUT">
				<xsl:attribute name="name">volume</xsl:attribute>
				<xsl:attribute name="class">form-control input-sm cont</xsl:attribute>
				<xsl:attribute name="style">width: 105px;</xsl:attribute>
			</xsl:element>
			<div class="input-group-addon">m³</div>
		</div>
	</div>
	<div class="form-group">
		<label>　　重量：</label>
		<div class="input-group col-xs-7">
			<xsl:element name="INPUT">
				<xsl:attribute name="name">weight</xsl:attribute>
				<xsl:attribute name="class">form-control input-sm cont</xsl:attribute>
				<xsl:attribute name="style">width: 107px;</xsl:attribute>
			</xsl:element>
			<div class="input-group-addon">kg</div>
		</div>
	</div>
	<div class="form-group">
		<label>　吊牌价：</label>
		<div class="input-group col-xs-7">
			<div class="input-group-addon">￥</div>
			<xsl:element name="INPUT">
				<xsl:attribute name="name">price_tag</xsl:attribute>
				<xsl:attribute name="class">form-control input-sm cont</xsl:attribute>
				<xsl:attribute name="style">width: 110px;</xsl:attribute>
			</xsl:element>
		</div>
	</div>

	<br/>

	<div class="form-group">
		<label>　　进价：</label>
		<div class="input-group col-xs-7">
			<div class="input-group-addon">￥</div>
			<xsl:element name="INPUT">
				<xsl:attribute name="name">price_purchase</xsl:attribute>
				<xsl:attribute name="class">form-control input-sm cont</xsl:attribute>
				<xsl:attribute name="style">width: 110px;</xsl:attribute>
			</xsl:element>
		</div>
	</div>
	<div class="form-group">
		<label>　零售价：</label>
		<div class="input-group col-xs-7">
			<div class="input-group-addon">￥</div>
			<xsl:element name="INPUT">
				<xsl:attribute name="name">price_display</xsl:attribute>
				<xsl:attribute name="class">form-control input-sm cont</xsl:attribute>
				<xsl:attribute name="style">width: 110px;</xsl:attribute>
			</xsl:element>
		</div>
	</div>
	<!-- <div class="form-group">
		<label>组合单价：</label>
		<div class="input-group col-xs-7">
			<div class="input-group-addon">￥</div>
			<xsl:element name="INPUT">
				<xsl:attribute name="name">price_combination</xsl:attribute>
				<xsl:attribute name="class">form-control input-sm</xsl:attribute>
				<xsl:attribute name="style">width: 110px;</xsl:attribute>
			</xsl:element>
		</div>
	</div> -->
	<div class="form-group">
		<label>　　总价：</label>
		<div class="input-group col-xs-7">
			<div class="input-group-addon">￥</div>
			<xsl:element name="INPUT">
				<xsl:attribute name="name">price_total</xsl:attribute>
				<xsl:attribute name="class">form-control input-sm cont</xsl:attribute>
				<xsl:attribute name="style">width: 110px;</xsl:attribute>
			</xsl:element>
		</div>
	</div>

	<div class="form-group">
		<label>在售状态：</label>
		<select class="form-control input-sm" name="product_sale">
			<xsl:for-each select="/html/Body/product_sale/li">
				<xsl:element name="OPTION">
					<xsl:attribute name="value"><xsl:value-of select="@name" /></xsl:attribute>
					<xsl:value-of select="." />
				</xsl:element>
			</xsl:for-each>
		</select>
	</div>

	<div class="form-group">
		<label>产品经理：</label>
		<input type="text" class="form-control input-sm" name="manager" id="manager"/>
		<input type="text"  name="manager_num" id="manager_num" style="display:none;"/>
	</div>
</div>

<div class="float_right" style="width:500px">

	<input type="hidden" id="product_image_1" name="product_image_1"/>
	<input type="hidden" id="product_image_2" name="product_image_2"/>
	<input type="hidden" id="product_image_3" name="product_image_3"/>
	<input type="hidden" id="product_image_4" name="product_image_4"/>

	<table cellspacing="0" cellpadding="0">
	<tr height="90" align="center">
		<td width="90" id="image_object_1" class="dotted">
			<a href="#" onclick="MessageBox('/product/product_edit_photo.php?index=1', '上传图片', 491, 200); return false">上传</a>
		</td>
		<td width="10"></td>
		<td width="90" id="image_object_2" class="dotted">
			<a href="#" onclick="MessageBox('/product/product_edit_photo.php?index=2', '上传图片', 491, 200); return false">上传</a>
		</td>
	</tr>
	<tr height="10">
		<td></td>
		<td></td>
		<td></td>
	</tr>
	<tr height="90" align="center">
		<td id="image_object_3" class="dotted">
			<a href="#" onclick="MessageBox('/product/product_edit_photo.php?index=3', '上传图片', 491, 200); return false">上传</a>
		</td>
		<td></td>
		<td id="image_object_4" class="dotted">
			<a href="#" onclick="MessageBox('/product/product_edit_photo.php?index=4', '上传图片', 491, 200); return false">上传</a>
		</td>
	</tr>
	</table>
</div>

<div class="form-group" style="margin-top:50px;">
	<label>商品描述：</label>
	<br/>
	<xsl:element name="TEXTAREA">
		<xsl:attribute name="name">content</xsl:attribute>
		<xsl:attribute name="class">form-control input-sm</xsl:attribute>
		<xsl:attribute name="style">width:1200px; height:80px</xsl:attribute>
	</xsl:element>
</div>

<div class="float_left">
		<h4>商品规格</h4>
		<xsl:element name="A">
		<xsl:attribute name="target">_blank</xsl:attribute>
		<xsl:attribute name="href">/product/product_specifications_properties.php</xsl:attribute>
		<xsl:attribute name="class">myMod</xsl:attribute>
		<img title="点击前往编辑规格" src="https://img.alicdn.com/imgextra/i2/85662775/TB2uxSfipXXXXazXXXXXXXXXXXX_!!85662775.png" width='16px;'  height='16px;' />
		</xsl:element>
		<table class="table table-bordered">
	<tr>
		<td class="center" width="150">操作</td>
		<td width="210">
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
		<td width="210">
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
		<td width="210">
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
		<td width="210">
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
		<td width="210">
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
		<td class="center"><a href="#" onclick="clear_format(); return false">清除</a></td>
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

	<div class="table_operate_block">
		<h4>商品属性</h4>
		<xsl:element name="A">
		<xsl:attribute name="target">_blank</xsl:attribute>
		<xsl:attribute name="href">/product/product_attribute.php</xsl:attribute>
		<xsl:attribute name="class">myMod</xsl:attribute>
		<img title="点击前往编辑属性" src="https://img.alicdn.com/imgextra/i2/85662775/TB2uxSfipXXXXazXXXXXXXXXXXX_!!85662775.png" width='16px;'  height='16px;' />
		</xsl:element>
		<!--
		<span class="btn btn-default btn-sm display_show">显示</span>
		<span class="btn btn-default btn-sm display_nonea" style="display:none;">隐藏</span>
		-->
	</div>

	<div class="propertys">
		<div class="table_operate_block">
			<input class="btn btn-default btn-sm btn_margin" type="button" value="添加" onclick="add_attrib()" />
		</div>
		<table class="table table-bordered">
		<tr>
			<th width="50" class="center">序号</th>
			<th width="50" class="center">操作</th>
			<th width="400">属性</th>
			<th width="700">属性值</th>
		</tr>
		<tbody id="attrib_list_obj"></tbody>
		</table>
	</div>


	<div class="table_operate_block">
	<h4>商品配件</h4>
	</div>
	<div class="propertys">
	<div class="table_operate_block">
		<input class="btn btn-default btn-sm btn_margin" type="button" value="添加" onclick="add_accessory()" />
	</div>
		<table class="table table-bordered" width="1200">
		<tr>
			<th class="center" width="50">序号</th>
			<th class="center" width="50">操作</th>
			<th width="160">搜索</th>
			<th width="462">商品名称与规格</th>
			<th class="center" width="140">图片</th>
			<th width="80">单位</th>
			<th width="100">单价</th>
			<th width="150">数量</th>
		</tr>
		<tbody id="accessory_list_obj"></tbody>
		</table>

		<!-- 仓库选择 -->
		<div class="table_operate_block">
			<h4>仓库选择</h4>
		</div>
		<div class="table_operate_block">
			<input class="btn btn-default btn-sm btn_margin store_add" type="button" value="添加"/>
		</div>

		<div id="test1"></div>

 <div style="clear:both;margin-top:10px;"></div>
		<input class="btn btn-default btn-sm btn_margin" id="add_ok" type="submit" value="提交" />
		<input class="btn btn-default btn-sm btn_margin" type="reset" value="重置" />

	</div>


</div>
</form>

<script>
add_accessory();
add_attrib();
</script>
<script type="text/javascript">
<![CDATA[
	$(".store_add").click(function(){
		MessageBox('/product/product_store_select.php','选择仓库',800,380);
	});
]]>
</script>
<script type="text/javascript">
	$(function(){
		$(document).on('mouseover', '.cangku', function() {
        	dd = $(this).find("span").html();
        	$(this).find("span").html("删除");
        });
        $(document).on('mouseout', '.cangku', function() {
        	$(this).find("span").html(dd);
        });
        //点击删除
        $(document).on('click', '.cangku', function() {
        	$(this).remove();
        });

        $("#manager").click(function(){
			MessageBox('/product/product_manager.php','产品经理',800,400);
        });

		$("#manager").attr("readonly",true);
		$("#manager").css("background","white");

		 $(".cont").keyup(function(){
	    	this.value = this.value.replace(/[^\d.]/g,"");
			this.value = this.value.replace(/^\./g,"");
			this.value = this.value.replace(/\.{2,}/g,".");
			this.value = this.value.replace(".","$#$").replace(/\./g,"").replace("$#$",".");
  		});
	});
</script>
</xsl:template>
</xsl:stylesheet>