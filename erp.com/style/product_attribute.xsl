<?xml version="1.0" encoding="utf-8" ?>
<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<!-- UTF8 编码 -->

<xsl:include href="/style/header.xsl" />

<xsl:template name="text">
<style>
	.sasl {
			width:291px;
			float:left;
			margin-right:12px;
			border:1px solid #e0e0e0;
		}
	.sasl2 {
			width:291px;
			float:left;
			border:1px solid #e0e0e0;
		}
	#myArea {
			display:none;
		}
	#myShelves {
			display:none;
		}
	#myLocation {
			display:none;
		}
	.top {
			width:100%;
			height:42px;
			float:left;
			color:#666;
			background:#ecedef;
		}
	.top_span {
			float:left;
			height:42px;
			line-height:42px;
			margin-left:10px;
			font-size:14px;
		}
	.top_button {
			float:right;
			height:42px;
			line-height:42px;
			font-size:12px;
			margin-right:10px;
		}
	.top_button:hover {
			cursor:pointer;
		}
	.mainBodyBody ul li {
			width:100%;
			height:32px;
			float:left;
			color:#666;
			line-height:32px;
		}
	.mainBodyBody ul li:hover {
			background-color:#f5f5f5;
		}
	.mySto {
			width:228px;
			float:left;
			padding-left:10px;
		}
	.mySto:hover {
			cursor:pointer;
		}
	.mySto2 {
			width:60px;
			float:right;
		}
	.mySto3 {
			width:236px;
			float:left;
			padding-left:10px;
		}
	.mySto3:hover {
			cursor:pointer;
		}

	.myStoo3 {
			width:236px;
			float:left;
			padding-left:10px;
		}
	.myStoo3:hover {
			cursor:pointer;
		}

	.mySto4 {
			width:52px;
			float:right;
		}
	.setUp {
			float:right;
			margin-right:10px;
		}
	.myMod {
			float:right;
			margin-right:10px;
		}
	.myDel {
			float:right;
			margin-right:10px;
		}
	.setUp:hover {
			cursor:pointer;
		}
</style>
<div class="mainBody">
	<ul id="myTab" class="nav nav-tabs">
		<li class="tab-pane"><a href="/product/product_specifications_properties.php">规格管理</a></li>
		<li class="tab-pane active"><a href="/product/product_attribute.php">属性</a></li>
		<li class="tab-pane"><a href="/product/product_unit_setting.php">单位设置</a></li>
	</ul>
	<br/>
</div>
<div class="mainBodyBody">
	<input type="hidden" name="stock_id" value="" />
	<input type="hidden" name="area" value="" />
	<input type="hidden" name="shelves" value="" />
	<div id="myStore" class="sasl">
		<div class='top'>
			<span class='top_span' >属性</span>
			<span class="top_button" type="button" onclick="MessageBox('product_add_attrib_name.php', '添加规格',240, 75)">添加新属性</span>
		</div>
		<div>
			<ul id="bbb">
				<xsl:for-each select="/html/Body/ul/li">
				<li>
					<xsl:element name="DIV">
						<xsl:attribute name="class">mySto</xsl:attribute>
						<xsl:attribute name="id"><xsl:value-of select="id" /></xsl:attribute>
						<xsl:attribute name="onclick">mmm('<xsl:value-of select="id" />')</xsl:attribute>
						<xsl:value-of select="name" />
					</xsl:element>
					<div class='mySto2'>
						<xsl:element name="INPUT">
							<xsl:attribute name="type">hidden</xsl:attribute>
							<xsl:attribute name="name">store_id</xsl:attribute>
							<xsl:attribute name="value">
								<xsl:value-of select="id" />
							</xsl:attribute>
						</xsl:element>
						<xsl:element name="SPAN">
							<xsl:attribute name="class">myMod</xsl:attribute>
							<xsl:attribute name="onclick">MessageBox('/product/product_edit_attrib_name.php?id=<xsl:value-of select="id" />', '修改属性', 300, 150)</xsl:attribute>
							<img src="/images/iconfont-mod.svg" width='16px;' height='32px;' />
						</xsl:element>
						<xsl:element name="SPAN">
							<xsl:attribute name="onclick">MessageBox('/product/product_delete_attrib_name.php?id=<xsl:value-of select="id" />', '删除属性', 300, 150)</xsl:attribute>
							<xsl:attribute name="class">myDel</xsl:attribute>
							<img src="/images/iconfont-del.svg" width='16px;' height='32px;' />
						</xsl:element>
					</div>
				</li>
				</xsl:for-each>
			</ul>
		</div>
	</div>
	<div id="myArea" class="sasl">
		<div class='top'>
			<span class='top_span' >属性值</span>
			<xsl:element name="SPAN">
				<xsl:attribute name="type">button</xsl:attribute>
				<xsl:attribute name="class">top_button</xsl:attribute>
				<xsl:attribute name="onclick">MessageBox('product_add_attrib_value.php','添加属性值', 240, 75); return false</xsl:attribute>
				<xsl:attribute name="href">#</xsl:attribute>
				新增属性值
			</xsl:element>
		</div>
		<div>
			<ul id="aaa">
			</ul>
		</div>
	</div>
</div>
<script>
	function mmm(id){
		var store_id = id;
		$('#myArea').css('display','none');
		$('input[name=stock_id]').prop('value',store_id);
		$('input[name=area]').prop('value','');
		$.ajax({
			url:'/product/product_view_attrib_name.php',
			type:'post',
			data:{'id':store_id},
			dataType:'json',
			success : function(msg){
				var opt='';
				console.log(msg);
				for(var n in msg){
					var id 			= msg[n]['id'];
					var name 		= msg[n]['name'];
						opt+= '&lt;li id="'+id+'">&lt;div class="mySto3">'+name+'&lt;/div>&lt;div class="mySto4">&lt;input type="hidden" name="area_id" value="'+id+'">&lt;span class="myMod" onclick="Area_M('+id+')">&lt;img src="/images/iconfont-mod.svg" width="16px;" height="32px;" />&lt;/span>&lt;span class="myDel" onclick="Area_D('+id+')">&lt;img src="/images/iconfont-del.svg" width="16px;" height="32px;" />&lt;/span>&lt;/div>&lt;/li>';
				}
				$('#aaa').html(opt);
       		},
		})
		//显示规格
		$('#myArea').css('display','block');
	}

	//属性修改
	function Area_S(aa){
		var area_id = aa;
		MessageBox('/product/product_edit_attrib_name.php?id='+area_id,'修改规格',300,150);
	}

	//属性删除
	function Area_SS(aa){
		var area_id = aa;
		MessageBox('/product/product_delete_attrib_name.php?id='+area_id,'删除',300,150);
	}



	//属性值修改
	function Area_M(aa){
		var area_id = aa;
		MessageBox('/product/product_edit_attrib_value.php?id='+area_id,'修改属性值',300,150);
	}

	//属性值删除
	function Area_D(aa){
		var area_id = aa;
		MessageBox('/product/product_delete_attrib_value.php?id='+area_id,'删除属性值',300,150);
	}

	//属性值添加
	$('#myArea .top .top_button').on('click',function(){
		var stock_id = $('input[name=stock_id]').val();
		MessageBox("product_add_attrib_value.php?attrib_id="+stock_id, '添加属性值', 500, 250);
	})

	//点击后背景颜色保持
	$('#myStore').on('click','li',function(){
		$('#myStore ul li').each(function(){
			$(this).css('background','#ffffff');
		})
		$(this).css('background','#f5f5f5');
	})
	$('#myArea').on('click','li',function(){
		$('#myArea ul li').each(function(){
			$(this).css('background','#ffffff');
		})
		$(this).css('background','#f5f5f5');
	})
</script>

</xsl:template>

</xsl:stylesheet>
