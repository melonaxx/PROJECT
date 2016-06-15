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
</style>

<div class="modal-body mainBody">
	<ul id="myTab" class="nav nav-tabs nav_tabs_margin">
	    <li class="active">
		   <a href="#" data-toggle="tab">按重量计费</a>
	    </li>
		<li><!-- <a href="/setting/setting_express_postfee_vol.php">按体积计费</a> -->
			<xsl:element name="a">
				<xsl:attribute name="href">/setting/setting_express_postfee_vol.php?express_id=<xsl:value-of select="/html/Body/express_id"/></xsl:attribute>
				按体积计费
			</xsl:element>
		</li>
	</ul>
	<div id="myTabContent" class="tab-content">
		<!-- 按重量计费 -->
		<div class="tab-pane fade in active" id="weight">
			<div>
				<div>
					<p class="title-bar">默认运费设置</p>
					<div class="form-group">
						<label class="margin_left_0">默认运费规则：</label>
						<xsl:element name="input">
							<xsl:attribute name="class">form-control input-sm</xsl:attribute>
							<xsl:attribute name="style">width:80%;display:inline-block;margin-right:15px</xsl:attribute>
							<xsl:attribute name="name">default_rule</xsl:attribute>
							<xsl:attribute name="value"><xsl:value-of select="/html/Body/default_rule" /></xsl:attribute>
							<xsl:attribute name="readonly">true</xsl:attribute>
						</xsl:element>

						<button class="btn btn-default btn-sm default_rule" onclick="MessageBox('/setting/setting_express_postfee_rule.php','设置运算规则',650,365)">设置</button >
					</div>
				</div>
				<p style="margin-bottom:8px">地区和仓库设置（未设置的地区仓库使用默认设置）</p>
				<table border="0" class="table tab_select table-bordered table-hover" style="table-layout:fixed; overflow:hidden;">
					<tbody>
					<tr>
						<th class="table_th_number">序号</th>
						<th width="60px">操作</th>
						<th width="320px" class="aria-hidden">适用地区</th>
						<th width="190px">仓库（发货地）</th>
						<th width="497px">运费计算规则</th>
						<th width="60px">设置</th>
					</tr>
					<xsl:for-each select="/html/Body/weight_fee/tr">
					<tr>
						<td style='text-align:center'><xsl:value-of select="num" /></td>
						<td style='text-align:center'><a class="delete_one" href="javascript:;">删除</a></td>
						<td >
							<!-- <span class="area" area_id=""></span> -->
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
						<td>
							<xsl:value-of select="rule" />
						</td>
						<td style="text-align:center">
							<a  class="setting_rule" href="javascript:;">设置</a >
						</td>
					</tr>
					</xsl:for-each>
					</tbody>
				</table>
			</div>
			<div class="modal-footer" style="text-align:center;">
				<button class="btn btn-default btn-sm" data-dismiss="modal" id="weight_save">保存</button >
				
				<button class="btn btn-default btn-sm"  onclick="window.location.replace('/setting/setting_express_list.php')">返回</button>
			</div>
		</div>
	</div>
</div>
<script>

// --- 设置地区 ---
var area_setting;
$("#weight").on("click",".setting_area",function(){
	var default_rule = $("input[name=default_rule]").val();
	tr = "&lt;tr>&lt;td style='text-align:center'>1&lt;/td>&lt;td style='text-align:center'>&lt;a class='delete_one' href='javascript:;'>删除&lt;/a>&lt;/td>&lt;td >&lt;span class='area' area_id=''>&lt;/span>&lt;a style='display:bolock;float:right' area_id='' href='javascript:;' class='setting_area'>设置&lt;/a>&lt;/td>&lt;td>	&lt;span class='store' store_id=''>&lt;/span>	&lt;a style='display:bolock;float:right' href='javascript:;' store_id='' class='setting_store'>设置&lt;/a>&lt;/td>&lt;td>"+default_rule+"&lt;/td>&lt;td style='text-align:center'>&lt;a  class='setting_rule'>设置&lt;/a >&lt;/td>&lt;/tr>";

	area_setting = $(this);
	MessageBox('/setting/setting_express_postfee_area.php','设置地区',400,365);
});

// --- 设置仓库 ---
var store_setting;
$("#weight").on("click",".setting_store",function(){
	var default_rule = $("input[name=default_rule]").val();
	tr = "&lt;tr>&lt;td style='text-align:center'>1&lt;/td>&lt;td style='text-align:center'>&lt;a class='delete_one' href='javascript:;'>删除&lt;/a>&lt;/td>&lt;td >&lt;span class='area' area_id=''>&lt;/span>&lt;a style='display:bolock;float:right' area_id='' href='javascript:;' class='setting_area'>设置&lt;/a>&lt;/td>&lt;td>	&lt;span class='store' store_id=''>&lt;/span>	&lt;a style='display:bolock;float:right' href='javascript:;' store_id='' class='setting_store'>设置&lt;/a>&lt;/td>&lt;td>"+default_rule+"&lt;/td>&lt;td style='text-align:center'>&lt;a  class='setting_rule'>设置&lt;/a >&lt;/td>&lt;/tr>";
	store_setting = $(this);
	MessageBox('/setting/setting_express_postfee_store.php','设置仓库',320,320);
});

// --- 设置运算规则 ---
var rule_setting;
$("#weight").on("click",".setting_rule",function(){
	var len = $(this).parents("table").find('tr').length;
	if($(this).parents("tr").index() == len - 1 ){
		alert("请先填写地区或仓库！");
		return false;
	}
	rule_setting = $(this);	
	MessageBox('/setting/setting_express_postfee_rule.php','设置运算规则',650,365);
});

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
$("#weight_save").click(function(){
	var express_id = window.location.href.split("=")[1]
	var _list = {};
	_list["express_id"] = express_id;
	for(var i = 0;i &lt; $("#weight table tr").length-1; i++){
		_list["weight_fee["+i+"][area_id]"] = $("#weight table tr:eq("+parseInt(i+1)+") td:eq(2) .area").attr("area_id");
		
		_list["weight_fee["+i+"][store_id]"] = $("#weight table tr:eq("+parseInt(i+1)+") td:eq(3) .store").attr("store_id");

		rule_text =$("#weight table tr:eq("+parseInt(i+1)+")").find("td:eq(4)").text()
		
		var reg1 = /(首重部分.*?);;/;
		var arr = rule_text.match(reg1);
		if(arr){
			var first_w = arr[1].split(";");
			for(var j=0;j&lt;first_w.length;j++){
				var reg2 = /从.*-(.+)kg,费用为￥(.+)/;
				var first_tr = first_w[j].match(reg2);
				_list["weight_fee["+i+"][first_weight_"+j+"]"] = first_tr[1];
				_list["weight_fee["+i+"][first_price_"+j+"]"]  = first_tr[2];

			}
		}

		var reg2 = /续重部分：重量每增加(.*?)kg,增加费用￥(.*?);/;
		var _added = rule_text.match(reg2);
		if(_added){
			_list["weight_fee["+i+"][added_weight]"] = _added[1];
			_list["weight_fee["+i+"][added_price]"]  = _added[2];
		}	
	}	

	<!-- console.log(_list);return false; -->
	$.ajax({  
    	url: 'setting_express_postfee_add.php',  
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
</script>

</xsl:template>

</xsl:stylesheet>