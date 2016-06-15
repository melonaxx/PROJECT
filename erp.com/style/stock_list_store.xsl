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
			letter-spacing:5px;
		}
	.top_button {
			float:right;
			height:42px;
			line-height:42px;
			<!-- width:70px; -->
			<!-- height:26px; -->
			font-size:12px;
			margin-right:10px;
			<!-- margin-top:8px; -->
		}
	.top_button:hover {
			cursor:pointer;
		}
	.mainBody ul li {
			width:100%;
			height:32px;
			float:left;
			color:#666;
			line-height:32px;
		}
	.mainBody ul li:hover {
			background-color:#f5f5f5;
		}
	.mySto {
			width:178px;
			float:left;
			padding-left:10px;
		}
	.mySto:hover {
			cursor:pointer;
		}
	.mySto2 {
			width:110px;
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
	<input type="hidden" name="stock_id" value="" />
	<input type="hidden" name="area" value="" />
	<input type="hidden" name="shelves" value="" />
	<div id="myStore" class="sasl">
		<div class='top'>
			<span class='top_span' >仓库</span>
			<span class="top_button" type="button" onclick="MessageBox('/stock/stock_add_store.php', '添加仓库',740, 325)">新增仓库</span>
		</div>
		<div>
			<ul>
				<li id='diyige'>
					<xsl:element name="DIV">
						<xsl:attribute name="class">mySto</xsl:attribute>
						<xsl:attribute name="onclick">mmm('<xsl:value-of select="/html/Body/def_id" />')</xsl:attribute>
						<xsl:value-of select="/html/Body/def_name" />
					</xsl:element>
					<div class='mySto2'>
						<xsl:element name="SPAN">
							<span class='setUp'>默认仓库</span>
						</xsl:element>
						<xsl:element name="INPUT">
							<xsl:attribute name="type">hidden</xsl:attribute>
							<xsl:attribute name="name">store_id</xsl:attribute>
							<xsl:attribute name="value">
								<xsl:value-of select="id" />
							</xsl:attribute>
						</xsl:element>
						<xsl:element name="SPAN">
							<xsl:attribute name="class">myMod</xsl:attribute>
							<xsl:attribute name="onclick">MessageBox('/stock/stock_edit_store.php?id=<xsl:value-of select="/html/Body/def_id" />', '修改仓库', 740, 325)</xsl:attribute>
							<img src="/images/iconfont-mod.svg" width='16px;' height='32px;' />
						</xsl:element>
					</div>
				</li>
				<xsl:for-each select="/html/Body/store/ul/li">
				<li>
					<xsl:element name="DIV">
						<xsl:attribute name="class">mySto</xsl:attribute>
						<xsl:attribute name="id">S<xsl:value-of select="id" /></xsl:attribute>
						<xsl:attribute name="onclick">mmm('<xsl:value-of select="id" />')</xsl:attribute>
						<xsl:value-of select="name" />
					</xsl:element>
					<div class='mySto2'>
						<xsl:element name="A">
							<xsl:attribute name="href">/stock/stock_address_match.php?store_id=<xsl:value-of select="id" /></xsl:attribute>
							<xsl:attribute name="target">_blank</xsl:attribute>
							<span class='setUp'>发货设置</span>
						</xsl:element>
						<xsl:element name="INPUT">
							<xsl:attribute name="type">hidden</xsl:attribute>
							<xsl:attribute name="name">store_id</xsl:attribute>
							<xsl:attribute name="value">
								<xsl:value-of select="id" />
							</xsl:attribute>
						</xsl:element>
						<xsl:element name="SPAN">
							<xsl:attribute name="class">myMod</xsl:attribute>
							<xsl:attribute name="onclick">Store_M('<xsl:value-of select="id" />')</xsl:attribute>
							<img src="/images/iconfont-mod.svg" width='16px;' height='32px;' />
						</xsl:element>
						<xsl:element name="SPAN">
							<xsl:attribute name="class">myDel</xsl:attribute>
							<xsl:attribute name="onclick">Store_D('<xsl:value-of select="id" />')</xsl:attribute>
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
			<span class='top_span' >库区</span>
			<xsl:element name="SPAN">
				<xsl:attribute name="type">button</xsl:attribute>
				<xsl:attribute name="class">top_button</xsl:attribute>
				新增库区
			</xsl:element>
		</div>
		<div>
			<ul id="aaa">
			</ul>
		</div>
	</div>
	<div id="myShelves" class="sasl">
		<div class='top'>
			<span class='top_span' >货架</span>
			<xsl:element name="SPAN">
				<xsl:attribute name="type">button</xsl:attribute>
				<xsl:attribute name="class">top_button</xsl:attribute>
				新增货架
			</xsl:element>
		</div>
		<div>
			<ul id = 'bbb'>
			</ul>
		</div>
	</div>
	<div id="myLocation" class="sasl2">
		<div class='top'>
			<span class='top_span' >货位</span>
			<span class="top_button" type="button" >新增货位</span>
		</div>
		<div>
			<ul id='ccc'>
			</ul>
		</div>
	</div>
</div>
<script>
	$(function(){
		var main_tol = '<xsl:value-of select="/html/Body/tol" />';
		if(main_tol=='no')
		{
			$('#diyige').css('display','none');
		}
	})
</script>
<script>

	function mmm(id){
		var store_id = id;
		$('#myArea').css('display','none');
		$('#myShelves').css('display','none');
		$('#myLocation').css('display','none');
		$('input[name=stock_id]').prop('value',store_id);
		$('input[name=area]').prop('value','');
		$('input[name=shelves]').prop('value','');
		$.ajax({
			url:'/stock/stock_list_store.php',
			type:'post',
			data:{'Location':'Area','store_id':store_id},
			dataType:'json',
			success : function(msg){
				var opt='';
				for(var n in msg){
					var id 			= msg[n]['id'];
					var name 		= msg[n]['name'];
					var type 		= msg[n]['type'];
					var parent 		= msg[n]['parent'];
					var store_id 	= msg[n]['store_id'];
					if(type == 'Area'){
						opt+= '&lt;li>&lt;div class="mySto3" id="L'+id+'" onclick="Area('+id+')" >'+name+'&lt;/div>&lt;div class="mySto4">&lt;input type="hidden" name="area_id" value="'+id+'">&lt;span class="myMod" onclick="Area_M('+id+')">&lt;img src="/images/iconfont-mod.svg" width="16px;" height="32px;" />&lt;/span>&lt;span class="myDel" onclick="Area_D('+id+')">&lt;img src="/images/iconfont-del.svg" width="16px;" height="32px;" />&lt;/span>&lt;/div>&lt;/li>';
					}
				}
				$('#aaa').html(opt);
       		},
		})
		//显示库区
		$('#myArea').css('display','block');
	}
	function Area(id){
		var area_id = id;
		$('#myShelves').css('display','none');
		$('#myLocation').css('display','none');
		var store_id = $('input[name=stock_id]').val();
		$('input[name=area]').prop('value',area_id);
		$('input[name=shelves]').prop('value','');
		$.ajax({
			url:'/stock/stock_list_store.php',
			type:'post',
			data:{'Location':'Shelves','area_id':area_id,'store_id':store_id},
			dataType:'json',
			success : function(msg){
				var opc='';
				for(var n in msg){
					var id 			= msg[n]['id'];
					var name 		= msg[n]['name'];
					var type 		= msg[n]['type'];
					var parent 		= msg[n]['parent'];
					var store_id 	= msg[n]['store_id'];
					if(parent == area_id){
						opc+= '&lt;li>&lt;div class="mySto3" id="L'+id+'" onclick="Shelves('+id+')" >'+name+'&lt;/div>&lt;div class="mySto4">&lt;input type="hidden" name="shelves_id" value="'+id+'">&lt;span class="myMod" onclick="Shelves_M('+id+')">&lt;img src="/images/iconfont-mod.svg" width="16px;" height="32px;" />&lt;/span>&lt;span class="myDel" onclick="Shelves_D('+id+')">&lt;img src="/images/iconfont-del.svg" width="16px;" height="32px;" />&lt;/span>&lt;/div>&lt;/li>';
					}
				}
				$('#bbb').html(opc);
       		},
		})
		$('#myShelves').css('display','block');
	}
	function Shelves(id){
		var shelves_id = id;
		$('#myLocation').css('display','none');
		var store_id = $('input[name=stock_id]').val();
		$('input[name=shelves]').prop('value',shelves_id);
		$.ajax({
			url:'/stock/stock_list_store.php',
			type:'post',
			data:{'Location':'Shelves','shelves_id':shelves_id,'store_id':store_id},
			dataType:'json',
			success : function(msg){
				var opd = '';
				for(var n in msg){
					var id 			= msg[n]['id'];
					var name 		= msg[n]['name'];
					var type 		= msg[n]['type'];
					var parent 		= msg[n]['parent'];
					var store_id 	= msg[n]['store_id'];
					if(parent == shelves_id){
						opd+= '&lt;li>&lt;div class="mySto" id="L'+id+'" >'+name+'&lt;/div>&lt;div class="mySto2">&lt;a href="/stock/stock_product_match.php?id='+id+'"class="setUp" target="_blank">商品设置&lt;/a>&lt;input type="hidden" name="location_id" value="'+id+'">&lt;span class="myMod" onclick="Location_M('+id+')">&lt;img src="/images/iconfont-mod.svg" width="16px;" height="32px;" />&lt;/span>&lt;span class="myDel" onclick="Location_D('+id+')">&lt;img src="/images/iconfont-del.svg" width="16px;" height="32px;" />&lt;/span>&lt;/div>&lt;/li>';
					}
				}
				$('#ccc').html(opd);
       		},
		})
		$('#myLocation').css('display','block');
	}
	//仓库查看
	function Store_M(aa){
		var store_id = aa;
		MessageBox('/stock/stock_edit_store.php?id='+store_id, '修改仓库', 740, 325)
	}
	//仓库删除
	function Store_D(aa){
		var store_id = aa;
		MessageBox('/stock/stock_delete_store.php?id='+store_id, '删除仓库', 320, 90)
	}

	//库位查看
	function Area_M(aa){
		var area_id = aa;
		MessageBox('/stock/stock_edit_location.php?id='+area_id,'查看',500,250);
	}
	function Shelves_M(aa){
		var shelves_id = aa;
		MessageBox('/stock/stock_edit_location.php?id='+shelves_id,'查看',500,250);
	}
	function Location_M(aa){
		var location_id = aa;
		MessageBox('/stock/stock_edit_location.php?id='+location_id,'查看',500,250);
	}

	//库位删除
	function Area_D(aa){
		var area_id = aa;
		MessageBox('/stock/stock_delete_location.php?id='+area_id,'删除',320,90);
	}
	function Shelves_D(aa){
		var shelves_id = aa;
		MessageBox('/stock/stock_delete_location.php?id='+shelves_id,'删除',320,90);
	}
	function Location_D(aa){
		var location_id = aa;
		MessageBox('/stock/stock_delete_location.php?id='+location_id,'删除',320,90);
	}

	//库位添加
	$('#myArea .top .top_button').on('click',function(){
		var stock_id = $('input[name=stock_id]').val();
		MessageBox("/stock/stock_add_location.php?location=Area&amp;store_id="+stock_id, '添加', 500, 250);
	})

	$('#myShelves .top .top_button').on('click',function(){
		var stock_id = $('input[name=stock_id]').val();
		var area = $('input[name=area]').val();
		MessageBox("/stock/stock_add_location.php?location=Shelves&amp;store_id="+stock_id+"&amp;area_id="+area, '添加', 500, 250);
	})
	$('#myLocation .top .top_button').on('click',function(){
		var stock_id = $('input[name=stock_id]').val();
		var shelves = $('input[name=shelves]').val();
		MessageBox("/stock/stock_add_location.php?location=Location&amp;store_id="+stock_id+"&amp;shelves_id="+shelves, '添加', 500, 250);
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
	$('#myShelves').on('click','li',function(){
		$('#myShelves ul li').each(function(){
			$(this).css('background','#ffffff');
		})
		$(this).css('background','#f5f5f5');
	})
	$('#myLocation').on('click','li',function(){
		$('#myLocation ul li').each(function(){
			$(this).css('background','#ffffff');
		})
		$(this).css('background','#f5f5f5');
	})
</script>
</xsl:template>

</xsl:stylesheet>
