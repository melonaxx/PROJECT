$(function(){
	var LODOP;
	LODOP=getLodop(document.getElementById('LODOP_OB'),document.getElementById('LODOP_EM'));

	// 获取系统打印机信息
	var printer_count =  LODOP.get_printer_count();
	var printer_name = new Array();
	for(var i=0;i<printer_count;i++){
		printer_name[i] = LODOP.get_printer_name(i);
		$("select[name=printer]").append("<option value="+i+">"+printer_name[i]+"</option>");
	}
	
	LODOP.SET_SHOW_MODE("DESIGN_IN_BROWSE",1);

	var print_orient = $("#print_orient").val();
	var page_width = $("#page_width").val()*10;
	var page_height = $("#page_height").val()*10;
	var page_name = $("#page_name option:selected").text();
	LODOP.SET_PRINT_PAGESIZE(print_orient,page_width,page_height,"");
	// LODOP.SET_SHOW_MODE("HIDE_PBUTTIN_SETUP",1);//：隐藏打印维护及设计窗口的打印按钮
	LODOP.SET_SHOW_MODE("HIDE_VBUTTIN_SETUP",1);//隐藏打印维护及设计窗口的预览按钮
	LODOP.SET_SHOW_MODE("HIDE_ABUTTIN_SETUP",0);//隐藏打印维护及设计窗口的应用（暂存）按钮
	LODOP.SET_SHOW_MODE("HIDE_RBUTTIN_SETUP",1);//隐藏打印维护及设计窗口的复原按钮
	LODOP.SET_SHOW_MODE("HIDE_GROUND_LOCK",1);//隐藏打印维护及设计窗口的纸钉按钮

	LODOP.PRINT_DESIGN();


	$('.ordership_item').each(function(){
		if($(this).attr('id_exist')){
			$(this).trigger('click');
			var top=$(this).attr('item_top');
			var left=$(this).attr('item_left');
			var width=$(this).attr('item_width');
			var height=$(this).attr('item_height');
			var font_size=$(this).attr('item_font_size');
			var font_bold=$(this).attr('item_font_bold');
			var content=$(this).attr('item_content');

			var type=$(this).attr('item_type');
			var item_id=$(this).attr('id');
			var english=$(this).attr('english');
			Moditify(this,top,left,width,height,font_size,font_bold,type,item_id,english,content);
		}
	});

	// 添加/删除
	$('.ordership_item').click(function(){
		var top=$(this).attr('item_top');
		var left=$(this).attr('item_left');
		var width=$(this).attr('item_width');
		var height=$(this).attr('item_height');
		var font_size=$(this).attr('item_font_size');
		var font_bold=$(this).attr('item_font_bold');
		var type=$(this).attr('item_type');
		var content=$(this).attr('item_content');
		var item_id=$(this).attr('id');
		var english=$(this).attr('english');
		Moditify(this,top,left,width,height,font_size,font_bold,type,item_id,english,content);
	});


	function Moditify(item,top,left,width,height,font_size,font_bold,type,item_id,english,content){
		top=top?top:50;
		left=left?left:20;
		width=width?width:160;
		height=height?height:20;
		font_size=font_size?font_size:10;
		font_bold=font_bold?font_bold:0;
		type=type?type:"other";
		LODOP=getLodop(document.getElementById('LODOP_OB'),document.getElementById('LODOP_EM'));

		if ((!LODOP.GET_VALUE("ItemIsExist",type+"***"+item_id+"***"+english)) && (item.checked)){
			LODOP.ADD_PRINT_TEXTA(type+"***"+item_id+"***"+english,top,left,width,height,content);
			LODOP.SET_PRINT_STYLEA(0,"FontSize",font_size);
			LODOP.SET_PRINT_STYLEA(0,"Bold",font_bold);
		}
		else {
			LODOP.SET_PRINT_STYLEA(type+"***"+item_id+"***"+english,'Deleted',!item.checked);
		}
	}
	
	// 删除全部
	$("#delete_all").click(function(){
		LODOP=getLodop(document.getElementById('LODOP_OB'),document.getElementById('LODOP_EM'));
		LODOP.SET_PRINT_STYLEA('All','Deleted',true);
		$('.ordership_item').each(function(){
			if(this.checked){
				$(this).trigger('click');
			}
		});	
	});
	
	// 删除选中项
	$("#delete_selected").click(function(){
		LODOP=getLodop(document.getElementById('LODOP_OB'),document.getElementById('LODOP_EM'));

		LODOP.SET_PRINT_STYLEA('Selected','Deleted',true);
	});
	
	// 打印预览
	$("#preview").click(function(){
		
		LODOP=getLodop(document.getElementById('LODOP_OB'),document.getElementById('LODOP_EM'));
		// 纸张信息
		// var strResult=LODOP. GET_PAGESIZES_LIST(1,"\n");
		// 获取打印设计框的内容
		var template_info = new Array();
		template_info['page_width']=LODOP.GET_VALUE("PRINTSETUP_PAGE_WIDTH",0);
		template_info['page_height']=LODOP.GET_VALUE("PRINTSETUP_PAGE_HEIGHT",0);
		template_info['page_top']=LODOP.GET_VALUE("PrintInitTop",0);
		template_info['page_left']=LODOP.GET_VALUE("PrintInitLeft",0);
		template_info['print_orient']=LODOP.GET_VALUE("PRINTSETUP_ORIENT",0);
		
		// 获取每个打印项的信息
		var count = LODOP.GET_VALUE('ItemCount',0);
		var template_position=new Array();
		for(var i=0;i<=count;i++){
			template_position[i]=new Array();
			template_position[i]['top']=LODOP.GET_VALUE("ItemTop",i+1);
			template_position[i]['left']=LODOP.GET_VALUE("ItemLeft",i+1);
			template_position[i]['width']=LODOP.GET_VALUE("ItemWidth",i+1);
			template_position[i]['height']=LODOP.GET_VALUE("ItemHeight",i+1);
			template_position[i]['font_size']=LODOP.GET_VALUE("ItemFontSize",i+1)?LODOP.GET_VALUE("ItemFontSize",i+1):10;
			template_position[i]['font_bold']=LODOP.GET_VALUE("Itembold",i+1)?LODOP.GET_VALUE("Itembold",i+1):0;
			template_position[i]['ItemContent']=LODOP.GET_VALUE("ItemContent",i+1);
			
			type_english = LODOP.GET_VALUE("ItemName",i+1);
			var type_id =new Array();
			type_id= type_english.split("***");
			template_position[i]['type']=type_id[0];
			template_position[i]['english']=type_id[2];
		}
		
		// console.log(template_position);

		// 预览张数
		LODOP = getLodop();
		var	print_total_num = $("#preview_num").val();
		// 设置纸张大小 和 类型
		var print_orient = $("#print_orient").val();
		var page_width = $("#page_width").val()*10;
		var page_height = $("#page_height").val()*10;
		var page_name = $("#page_name option:selected").text();
		LODOP.SET_PRINT_PAGESIZE(print_orient,page_width,page_height,"");
		// LODOP.SET_PRINT_PAGESIZE("1","210mm","148mm","");
		
		
		// 边框设置
		var border = $("input[type=radio]:checked").val();
		for(var i=1;i<=print_total_num;i++){
			// LODOP.PRINT_INIT(i);
			LODOP.NewPageA(); //强行分页
			
			// 显示页码（仅预览）
			LODOP.ADD_PRINT_HTM(1,"70%",500,100,"总页号：<font color='#0000ff'><span tdata='pageNO'>第##页</span>/<span tdata='pageCount'>共##页(预览)</span></font>")
			LODOP.SET_PRINT_STYLEA(0,"ItemType",1);
			// LODOP.SET_PRINT_STYLE("PreviewOnly",1);
			// 条码
			var table=new Array();
			var n=0;
			for(var k in template_position){
				if(template_position[k]['type'] == 'code'){
					LODOP.ADD_PRINT_BARCODE(parseInt(template_position[k]['top']),parseInt(template_position[k]['left']),parseInt(template_position[k]['width']),parseInt(template_position[k]['height']),"128B",template_position[k]['ItemContent']);
					LODOP.SET_PRINT_STYLEA(0,"ShowBarText",template_position[k]['font_bold']);

				}

				if(template_position[k]['type'] == 'title'){
					LODOP.ADD_PRINT_TEXTA(template_position[k]['english'],parseInt(template_position[k]['top']),parseInt(template_position[k]['left']),parseInt(template_position[k]['width']),parseInt(template_position[k]['height']),template_position[k]['ItemContent']);
					LODOP.SET_PRINT_STYLEA(template_position[k]['english'],"FontSize",template_position[k]['font_size']);
					LODOP.SET_PRINT_STYLEA(template_position[k]['english'],"Bold",template_position[k]['font_bold']);
					LODOP.SET_PRINT_STYLEA(template_position[k]['english'],"ItemType",1);
					// LODOP.SET_PRINT_STYLEA(0,"LinkedItem",-1);

				}
				
				if(template_position[k]['type'] == 'other'){

					LODOP.ADD_PRINT_TEXTA(template_position[k]['english'],parseInt(template_position[k]['top']),parseInt(template_position[k]['left']),parseInt(template_position[k]['width']),parseInt(template_position[k]['height']),template_position[k]['ItemContent']+"："+$("#printi ."+template_position[k]['english']).text());
					LODOP.SET_PRINT_STYLEA(template_position[k]['english'],"FontSize",template_position[k]['font_size']);
					LODOP.SET_PRINT_STYLEA(template_position[k]['english'],"Bold",template_position[k]['font_bold']);

				}
				// 商品列表
				if(template_position[k]['type'] == 'table'){
					table[n] = k;
					n++;
				}
			
			}
			if(table.length>0){

				var table_header=new Array();
				for(var a=0;a<table.length;a++){
					table_header[a]=new Array();
					table_header[a]=template_position[table[a]];
				}
				table_header=table_header.sort(keysrt("left",false));
				// console.log(table_header);
				var	strhtml = '<table style="font-size:12px;border:'+border+'px solid #000;border-collapse:collapse;line-height:20px" width="90%" border="'+border+'" cellspacing="0" cellpadding="4"><thead><tr>';
				for(var k=0;k<table_header.length;k++){
					strhtml += "<th width="+table_header[k]['width']+">"+table_header[k]['ItemContent']+"</th>";
				}	
				strhtml += "</thead></tr>";

				// 每条订单的商品数量
				var every_page_num = $("#every_page_num").val();
				for (var j = 0; j <every_page_num; j++) {
					strhtml+="<tr>";
					for(var k=0;k<table_header.length;k++){
						strhtml += "<td align='center'>"+$("#printi table tr").eq(0).find('td.'+table_header[k]['english']).text()+"</td>";
					}
					strhtml+="</tr>";				
				}
				strhtml+='</table>';
				
				// console.log(table_header);return false;
				LODOP.ADD_PRINT_TABLE(table_header[0]['top'],table_header[0]['left'],"RightMargin:0cm","BottomMargin:10mm",strhtml);
				LODOP.SET_PRINT_STYLEA(0,"Offset2Top",-80);
				// 测试内容
				// LODOP.ADD_PRINT_TEXTA("foot1",490,96,"76.25%",20,"真诚祝您好远，欢迎下次再来！");
				// // LODOP.SET_PRINT_STYLEA(0,"LinkedItem",0);
				// LODOP.SET_PRINT_STYLEA(0,"FontSize",12);
				// LODOP.SET_PRINT_STYLEA(0,"FontColor","#FF0000");
				// LODOP.SET_PRINT_STYLEA(0,"Alignment",2);
				// LODOP.SET_PRINT_STYLEA(0,"ItemType",1);
				// LODOP.SET_PRINT_STYLEA(0,"Horient",3);	
				
			}
		}
		
		LODOP.PREVIEW();
			// LODOP.PRINT_DESIGN();

	});	

	// 二维数组排序
	function keysrt(key,desc) {
	  return function(a,b){
	    return desc ? ~~(a[key] - b[key]) : ~~(a[key] - b[key]);
	  }
	}
	
	var template_position="";
	var template_info="";
	// 保存为新模板
	$('#add_template').click(function(){
		get_info();
		$.ajax({
			url: '/deliver/deliver_add_shiporder_template.php',
			type: 'POST',
			dataType: 'text',
			data: {action:'add',template_position:template_position,template_info:template_info},
		})
		.done(function(data) {
			if(data=="ok"){
				alert("保存成功！");
			}else if(data=="exit"){
				alert("模板名称已存在！请重新命名！");
			}else{
				alert("保存失败！");
			}
		})
		.fail(function() {
			alert("网络异常！");
		});
	});

	// 修改模板
	$('#edit_template').click(function(){
		get_info();
		$.ajax({
			url: '/deliver/deliver_edit_shiporder_template.php',
			type: 'POST',
			dataType: 'text',
			data: {action:'edit',template_position:template_position,template_info:template_info},
		})
		.done(function(data) {
			if(data=="ok"){
				alert("修改成功！");
			}else if(data=="noexit"){
				alert("模板不存在！");
			}else if(data=="cant_name"){
				alert("不能修改模板名称！");
			}
		})
		.fail(function() {
			alert("网络异常！");
		});
	});

	// 获取打印控件中各个控件的信息
	function get_info(){
		LODOP=getLodop(document.getElementById('LODOP_OB'),document.getElementById('LODOP_EM'));
		// 模板信息
		template_info = "";
		// var template_id=$("#template_id").attr("value");
		template_info="{'template_id':'"+$("#template_id").attr('value')+"','template_name':'"+$("#template_name").val()+"','border':'"+$("input[type=radio]:checked").val()+"','page_width':'"+$("#page_width").val()+"','page_height':'"+$("#page_height").val()+"','page_top':'"+$("#page_top").val()+"','page_left':'"+$("#page_left").val()+"','print_orient':'"+LODOP.GET_VALUE("PRINTSETUP_ORIENT",0)+"'}"
		// 获取每个打印项的信息
		var count = LODOP.GET_VALUE('ItemCount',0);
		// var template_position=new Array();
		
		// 控件位置信息
		template_position="[";
		for(var i=0;i<count;i++){
		// 如果要添加不存在的信息，还要添加到template_info 表中item表中信息
			var type_item_id = LODOP.GET_VALUE("ItemName",i+1);
			var type_id =new Array();
			type_id= type_item_id.split("***");
			// template_position[i]['item_id']=type_id[1];
			// template_position[i]['english']=type_id[2];
			// template_position[i]['type']=type_id[0];
			template_position += "{'item_top':'"+LODOP.GET_VALUE("ItemTop",i+1)+"','item_left':'"+LODOP.GET_VALUE("ItemLeft",i+1)+"','item_width':'"+LODOP.GET_VALUE("ItemWidth",i+1)+"','item_height':'"+LODOP.GET_VALUE("ItemHeight",i+1)+"','item_font_size':'"+LODOP.GET_VALUE("ItemFontSize",i+1)+"','item_font_bold':'"+LODOP.GET_VALUE("Itembold",i+1)+"','item_content':'"+LODOP.GET_VALUE("ItemContent",i+1)+"','item_id':'"+type_id[1]+"'},";
		}
		template_position = template_position.substring(0,template_position.length-1);
		template_position+="]";
	}


	// 还原默认
	$("#reload_default").click(function(){
		location.reload(true);
	})


	// 获取上传图片地址
	$("input[name=template_pic]").change(function(){
		LODOP=getLodop(document.getElementById('LODOP_OB'),document.getElementById('LODOP_EM'));
		alert(window.navigator.userAgent);
		if(!this.value.match(/.jpg|.gif|.png|.bmp/i)){
			alert('File type must be: .jpg, .gif, .bmp or .png !');return;
		}
		
		if(window.navigator.userAgent.indexOf("MSIE")>=1){
				this.select();
				var img_src = document.selection.createRange().text; 
				alert(img_src);
				alert(2);
       	}	
		else if(window.navigator.userAgent.indexOf("Firefox")>=1){
			if(this.files){
				var img_src=window.URL.createObjectURL(this.files[0]);
				alert(img_src);
				LODOP.ADD_PRINT_SETUP_BKIMG("<img style='width:200px;height:200px;' src='"+img_src+"'></img>");
				alert(1);
				return false;
			}

			alert(this.value);
				return false;

		}
			alert(this.value);
				return false;
		});

	function upload_template_pic(){
		var pic = $("input[name=template_pic]");
		if(!pic.val()){
			alert("请先选择图片！");
			return false;
		}

		if(!pic.val().match(/.jpg|.gif|.png|.bmp/i)){
			alert("图片格式错误");
			return false;
		}
		
		$.ajax({
			url: '/deliver/deliver_print_design.php',
			type: "POST",
            data: { imgPath: pic.val() },
            cache: false,
		})
		.done(function(data) {
			if(data==200){
				alert("success");
			}
				alert("success");

		})
		.fail(function() {
			alert("error");
		})
		.always(function() {
			alert("complete");
		});

	} 

	// 选择打印模板
	$("body").on('click','#ordership_template',function(){	
		var td = $(this).parent(".dropdown_body");
		$(this).remove();
		td.find("select").attr("style","display:inline-block;width:150px;margin:0 10px 0 10px");
		td.find("select option").click(function(){
			var template_id=this.value;
			// alert(template_id);
			// return false;
			location.replace("/deliver/deliver_print_design.php?template_id="+template_id);
		});
    });

});	