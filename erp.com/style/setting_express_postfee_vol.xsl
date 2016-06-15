<?xml version="1.0" encoding="utf-8" ?>
<xsl:stylesheet version="2.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<!-- UTF8 编码 -->

<xsl:include href="/style/header.xsl" />
<xsl:template name="text">

<!-- 提示框 -->
<div id="confirm" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" style="display: none;width:268px;margin:65px auto">
	<div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
			<h4 class="modal-title" id="myModalLabel">提示</h4>
		</div>
		<div class="modal-body" style="margin-left:20px">默认运费规则不能为空！<span class="number"></span>
		</div>
		<div class="modal-footer"><button type="button" class="btn btn-default btn-sm ok" data-dismiss="modal">确定</button></div>
	</div>
</div>

<style>
.error_color{
	border:1px solid red;
}
._input{overflow-x: hidden; overflow-y: visible; width: 100%;height:100%; line-height:30px;cursor:text}
 .table tr th,td{text-align: center}
.dropdown_body{line-height:25px;display:none;margin-left:50px;}
.car_left{width:25px;height:25px;margin-top:2px;border-radius: 3px;line-height: 1;padding: 4px 5px;} 
.car_left:hover{background:#bbb;border:1px solid lightblue;}
.dropdown .checkbox{margin-bottom:7px;display:inline-block;}
.dropbody label{display: block}
td input.form-control{width:70%;display:inline-block;}
td ._input{text-align:center}
</style>

<div class="modal-body mainBody">
	<ul id="myTab" class="nav nav-tabs nav_tabs_margin">
	   <li >
		  <xsl:element name="a">
				<xsl:attribute name="href">/setting/setting_express_postfee.php?express_id=<xsl:value-of select="/html/Body/express_id"/></xsl:attribute>
				按重量计费
			</xsl:element>
	   </li>
	   <li class="active"><a href="#" data-toggle="tab">按体积计费</a></li>
	</ul>
	<div id="myTabContent" class="tab-content">
		<!-- 按体积计费 -->
		<div class="tab-pane fade in active" id="weight">
			<div>
				<div>
					<p class="title-bar">默认运费设置</p>
					<div class="form-group default">
						<label class="margin_left_0">运费单价：</label>
						<xsl:element name="input">
							<xsl:attribute name="class">form-control input-sm default</xsl:attribute>
							<xsl:attribute name="style">width:70px;display:inline-block;</xsl:attribute>
							<xsl:attribute name="name">default_fee</xsl:attribute>
							<xsl:attribute name="value"><xsl:value-of select="/html/Body/default_fee"/></xsl:attribute>
						</xsl:element>
						 元/m³
						<label class="margin_left_2">最低运费：</label>
						<xsl:element name="input">
							<xsl:attribute name="class">form-control input-sm default</xsl:attribute>
							<xsl:attribute name="style">width:70px;display:inline-block;</xsl:attribute>
							<xsl:attribute name="name">least_fee</xsl:attribute>
							<xsl:attribute name="value"><xsl:value-of select="/html/Body/default_fee"/></xsl:attribute>
						</xsl:element> 元
						<label class="margin_left_2">附加运费：</label>
						<xsl:element name="input">
							<xsl:attribute name="class">form-control input-sm default</xsl:attribute>
							<xsl:attribute name="style">width:70px;display:inline-block;</xsl:attribute>
							<xsl:attribute name="name">extra_fee</xsl:attribute>
							<xsl:attribute name="value"><xsl:value-of select="/html/Body/extra_fee"/></xsl:attribute>
						</xsl:element> 元
					</div>
				</div>
				<p>地区和仓库设置（未设置的地区仓库使用默认设置） </p>
				<table border="0" class="table tab_select table-bordered table-hover" style="table-layout:fixed; overflow:hidden;" width="100%">
					<tbody>
					<tr>
						<th class="table_th_number">序号</th>
						<th width="60px">操作</th>
						<th width="364px" class="aria-hidden">适用地区</th>
						<th width="300px">仓库（发货地）</th>
						<th width="150px">运费单价（元/m³）</th> 
						<th width="140px">最低运费（元）</th> 
						<th width="140px">附加费用（元）</th> 
					</tr>
					<xsl:for-each select="/html/Body/vol_fee/tr">
					
					<tr>
						<td style='text-align:center'>1</td>
						<td style='text-align:center'><a class="delete_one" href="javascript:;">删除</a></td>
						<td >
							<xsl:element name="span">
								<xsl:attribute name="class">area</xsl:attribute>
								<xsl:attribute name="area_id"><xsl:value-of select="area_id" /></xsl:attribute>
								<xsl:value-of select="area_name" />
							</xsl:element>
							<a style="display:bolock;float:right" href="javascript:;" class="setting_area">设置</a>
						</td>
						<td>
							<xsl:element name="span">
								<xsl:attribute name="class">store</xsl:attribute>
								<xsl:attribute name="store_id"><xsl:value-of select="store_id" /></xsl:attribute>
								<xsl:value-of select="store_name" />
							</xsl:element>
							<a style="display:bolock;float:right" href="javascript:;" class="setting_store">设置</a>
							
						</td>
						<td width="150px"><div class="_input default_fee" true_value=""><xsl:value-of select="per_fee" /></div></td>
						<td width="140px"><div class="_input least_fee" true_value=""><xsl:value-of select="least_fee" /></div></td>
						<td width="140px"><div class="_input extra_fee" true_value=""><xsl:value-of select="extra_fee" /></div></td>
					</tr>
					</xsl:for-each>
					</tbody>
				</table>
			</div>
			<div class="modal-footer" style="text-align:center;">
				<button class="btn btn-default btn-sm"  data-dismiss="modal" id="vol_save">保存</button >
				
				<button class="btn btn-default btn-sm"  onclick="window.location.replace('/setting/setting_express_list.php')">返回</button>
				
			</div>
		</div>
	</div>
</div>
<script>
// 默认规则
$('.default').focus(function(){$(this).select()}).blur(function(){
	var value=number_test($(this).val());
	$(this).val(value);
	var name  = $(this).attr('name');
	max = $('#weight table tr').length-1;
	$('#weight table tr:eq('+max+') .'+name).html(value);
	$('#weight table tr:eq('+max+') td .'+name).attr('true_value',value);
	if(max == 0){
		tr = gettr();
		$("#weight table").append(tr);
		
	}
});


// --- 设置地区 ---
var area_setting;
$("#weight").on("click",".setting_area",function(){
 	tr = gettr();	
 	area_setting = $(this);
	MessageBox('/setting/setting_express_postfee_area.php','设置地区',400,365);
});

// --- 设置仓库 ---
var store_setting;
$("#weight").on("click",".setting_store",function(){
	tr = gettr();
	store_setting = $(this);
	MessageBox('/setting/setting_express_postfee_store.php','设置仓库',320,320);
});

// 插入的行
function gettr(){
	var default_fee = $('#weight input[name=default_fee]').val();
	var least_fee = $('#weight input[name=least_fee]').val();
	var extra_fee = $('#weight input[name=extra_fee]').val();

	tr = '&lt;tr>&lt;td style="text-align:center">1&lt;/td>&lt;td style="text-align:center">&lt;a class="delete_one" href="javascript:;">删除&lt;/a>&lt;/td>&lt;td >&lt;span class="area" area_id="">&lt;/span>&lt;a style="display:bolock;float:right" href="javascript:;" class="setting_area">设置&lt;/a>&lt;/td>&lt;td>&lt;span class="store" store_id="">&lt;/span>&lt;a style="display:bolock;float:right" href="javascript:;" class="setting_store">设置&lt;/a>&lt;/td>&lt;td width="150px">&lt;div class="_input default_fee" true_value='+default_fee+'>'+default_fee+'&lt;/div>&lt;/td>&lt;td width="140px">&lt;div class="_input least_fee" true_value='+least_fee+'>'+least_fee+'&lt;/div>&lt;/td>&lt;td width="140px">&lt;div class="_input extra_fee" true_value='+extra_fee+'>'+extra_fee+'&lt;/div>&lt;/td>&lt;/tr>';

	return tr;
}

// 删除
$("#weight").on('click','.delete_one',function(){
	var len = $(this).parents("table").find('tr').length;
	if(len-1 != $(this).parents("tr").index()){
		$(this).parents("tr").remove();
		for(var i = 1;i&lt;len;i++){
	        $('table tr:eq('+i+') td:first').text(i);
	    }
	}
});

// 保存
$("#vol_save").click(function(){
	var express_id = window.location.href.split("=")[1]
	var _list = {};
	_list["express_id"] = express_id;
	for(var i = 0;i &lt; $("#weight table tr").length-1; i++){
		_list["vol_fee["+i+"][area_id]"] = $("#weight table tr:eq("+parseInt(i+1)+") td:eq(2) .area").attr("area_id");
		
		_list["vol_fee["+i+"][store_id]"] = $("#weight table tr:eq("+parseInt(i+1)+") td:eq(3) .store").attr("store_id");

		_list["vol_fee["+i+"][price]"] = $("#weight table tr:eq("+parseInt(i+1)+") td:eq(4)").text();

		_list["vol_fee["+i+"][least]"] = $("#weight table tr:eq("+parseInt(i+1)+") td:eq(5)").text();

		_list["vol_fee["+i+"][extra]"] = $("#weight table tr:eq("+parseInt(i+1)+") td:eq(6)").text();
		
	}	

	<!-- console.log(_list);return false; -->
	$.ajax({  
    	url: 'setting_express_postfee_vol_add.php',  
    	data: _list,  
    	type: "POST",  
    	success: function (data) {  
    	    if(data=="ok"){
    	   		alert('保存成功！');  
    	    	window.location.href = '/setting/setting_express_list.php';
    	    }
    	},
	});  
});


// 添加输入框
$("#weight").on("click","._input",function(){
	$(this).removeClass('error_color');
	var input_fee = '&lt;input class="form-control input-sm weight_text" style="width:100%;display: inline-block;" type="text" />';
	var old_value =$(this).text();
	$(this).empty();
	$(this).append(input_fee);
	$(this).find(".weight_text").focus();
	$(this).find(".weight_text").val(old_value);
	$(this).find(".weight_text").select();
});

$("#weight").on("blur",".weight_text",function(){
	var tr = $(this).parents("tr");
	var td_div = $(this).parent();	
	var weight_text=number_test($(this).val());
	td_div.html(weight_text);
});


// 检测是否为合法的数字（保留3位有效小数）
function number_test(text){
	if(isNaN(parseFloat(text)) || parseFloat(text)&lt;0){
		return "";
	}else{
		return parseFloat(text).toFixed(2);
	}
}
</script>

</xsl:template>

</xsl:stylesheet>