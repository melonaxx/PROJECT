$(function(){
	var LODOP = getLodop(document.getElementById('LODOP_OB'),document.getElementById('LODOP_EM'));

	function init()	
	{
		// 获取系统打印机信息
		var printer_count 	=  LODOP.get_printer_count();//获取打印机个数
		var printer_name 	= new Array();

		for(var i=0;i<printer_count;i++){
			printer_name[i] = LODOP.get_printer_name(i);//获取打印机设备名字
			$("select[name=printer]").append("<option value="+i+">"+printer_name[i]+"</option>");
		}

		var page_top 	= $("#page_top").val()?$("#page_top").val():0;
		var page_left 	= $("#page_left").val()?$("#page_left").val():0;
		var page_width  = $("#page_width").val()?$("#page_width").val():229;
		var page_height = $("#page_height").val()?$("#page_height").val():140;
		LODOP.PRINT_INITA(page_top+"mm",page_left+"mm",page_width+"mm",page_height+"mm","");
		//打印初始化、设定纸张整体偏移量、设定可视编辑区域大小
		// LODOP.SET_SHOW_MODE("DESIGN_IN_BROWSE",1);
		LODOP.SET_SHOW_MODE("SETUP_IN_BROWSE",1);//设置显示模式

		LODOP.SET_PRINT_PAGESIZE(1,page_width+"mm",page_height+"mm","");
		//设定打印纸张为固定纸张或自适应内容高，并设定相关大小值或纸张名及打印方向。
		LODOP.SET_SHOW_MODE("HIDE_PBUTTIN_SETUP",1);//：隐藏打印维护及设计窗口的打印按钮
		LODOP.SET_SHOW_MODE("HIDE_VBUTTIN_SETUP",1);//隐藏打印维护及设计窗口的预览按钮
		LODOP.SET_SHOW_MODE("HIDE_RBUTTIN_SETUP",1);//隐藏打印维护及设计窗口的复原按钮
		LODOP.SET_SHOW_MODE("HIDE_GROUND_LOCK",1);//隐藏打印维护及设计窗口的纸钉按钮
		LODOP.SET_SHOW_MODE("HIDE_ABUTTIN_SETUP",1);//隐藏打印维护及设计窗口的复原按钮
		LODOP.SET_SHOW_MODE("HIDE_DISBUTTIN_SETUP",1);//隐藏打印维护及设计窗口的禁止使用的按钮
		LODOP.SET_SHOW_MODE("SETUP_ENABLESS",10010111000001);//隐藏打印维护及设计窗口的复原按钮
		// 背景图片
		var image = $('input[name=web_image]').val()?$('input[name=web_image]').val():$("input[name=template_image]:checked").val();
		// alert(image);
		if(image!=""){
			LODOP.ADD_PRINT_SETUP_BKIMG("<img border='0' src='"+image+"'>");//用程序方式指定打印维护或打印设计的背景图
			LODOP.SET_SHOW_MODE("BKIMG_WIDTH",page_width+"mm");//背景宽
			LODOP.SET_SHOW_MODE("BKIMG_HEIGHT",page_height+"mm");//背景高
		}
		//LODOP.PRINT_DESIGN();
		
		$('.express_item').each(function(){
			if($(this).attr('id_exist')){
				var top 		= $(this).attr('item_top')?$(this).attr('item_top'):50;
				var left 		= $(this).attr('item_left')?$(this).attr('item_left'):20;
				var width 		= $(this).attr('item_width')?$(this).attr('item_width'):160;
				var height 		= $(this).attr('item_height')?$(this).attr('item_height'):20;
				var font_size 	= $(this).attr('item_font_size')?$(this).attr('item_font_size'):10;
				var item_id 	= this.id;
				var english 	= $(this).attr('english');
				LODOP.ADD_PRINT_TEXTA(item_id+"***"+english,top,left,width,height,this.value);
				//增加纯文本打印项，设定该打印项在纸张内的位置和区域大小，文本内容在该区域内自动折行，当内容超出区域高度时，如果对象被设为“多页文档”则会自动分页继续打印，否则内容被截取。
				LODOP.SET_PRINT_STYLEA(0,"FontSize",font_size);
				//设置打印项的输出风格，成功执行该函数，此后再增加的打印项按此风格输出。
			}
		});
		LODOP.PRINT_SETUP();//对整页的打印布局和打印风格进行界面维护，它与打印设计的区别是不具有打印项增删功能，目标使用者是最终用户。

	}
	
	init();

	$('.express_item').each(function(){
		if($(this).attr('id_exist')){
			$(this).prop('checked','checked');
		}
	});

	// 添加/删除
	$('.express_item').click(function(){
		var top 			= $(this).attr('item_top');
		var left 			= $(this).attr('item_left');
		var width 			= $(this).attr('item_width');
		var height 			= $(this).attr('item_height');
		var font_size 		= $(this).attr('item_font_size');
		var item_id 		= $(this).attr('id');
		var english 		= $(this).attr('english');
		Moditify(this,top,left,width,height,font_size,item_id,english);
	});


	function Moditify(item,top,left,width,height,font_size,item_id,english){
		top 		= top ? top : 50;
		left 		= left ? left : 20;
		width 		= width ? width : 160;
		height 		= height ? height : 20;
		font_size 	= font_size ? font_size : 10;
		LODOP=getLodop(document.getElementById('LODOP_OB'),document.getElementById('LODOP_EM'));

		if ((!LODOP.GET_VALUE("ItemIsExist",item_id+"***"+english)) && (item.checked)){
			LODOP.ADD_PRINT_TEXTA(item_id+"***"+english,top,left,width,height,item.value);
			LODOP.SET_PRINT_STYLEA(0,"FontSize",font_size);
		}
		else {
			LODOP.SET_PRINT_STYLEA(item_id+"***"+english,'Deleted',!item.checked);
		}
	}
	// 添加对号
	// $("#right_sign").click(function(){
	// 	LODOP=getLodop(document.getElementById('LODOP_OB'),document.getElementById('LODOP_EM'));
	// 	LODOP.ADD_PRINT_TEXT(50,30,20,20,"√");
		
	// })


	// 删除全部
	$("#delete_all").click(function() {
		LODOP=getLodop(document.getElementById('LODOP_OB'),document.getElementById('LODOP_EM'));
		LODOP.SET_PRINT_STYLEA('All','Deleted',true);
		$('.express_item').each(function() {
			if(this.checked) {
				$(this).trigger('click');
			}
		});	
	});
	
	// 删除选中项
	$("#delete_selected").click(function(){
		LODOP = getLodop(document.getElementById('LODOP_OB'),document.getElementById('LODOP_EM'));
		LODOP.SET_PRINT_STYLEA('Selected','Deleted',true);
		var count = LODOP.GET_VALUE('ItemCount',0);//获得程序代码、打印项属性等数据值。
		var item_id_list = "";
		for(var i = 0; i < count; i++){
			item = LODOP.GET_VALUE("ItemName",i+1);
			var type_id  = new Array();
			type_id= item.split("***");
			item_id_list += type_id[0]+',';
		}

		$('.express_item').each(function() {
			if(this.checked && item_id_list.indexOf($(this).attr("id")) == -1){
				$(this).trigger('click');
			}
		});

	});
	
	// 打印预览
	$("#preview").click(function(){
		LODOP = getLodop(document.getElementById('LODOP_OB'),document.getElementById('LODOP_EM'));
		// 纸张信息
		// var strResult=LODOP. GET_PAGESIZES_LIST(1,"\n");
		// 获取打印设计框的内容
		// var template_info = new Array();
		// template_info['page_width']=LODOP.GET_VALUE("PRINTSETUP_PAGE_WIDTH",0);
		// template_info['page_height']=LODOP.GET_VALUE("PRINTSETUP_PAGE_HEIGHT",0);
		// template_info['page_top']=LODOP.GET_VALUE("PrintInitTop",0);
		// template_info['page_left']=LODOP.GET_VALUE("PrintInitLeft",0);
		// template_info['print_orient']=LODOP.GET_VALUE("PRINTSETUP_ORIENT",0);
		// template_info['image']=LODOP.GET_VALUE("BKIMG_CONTENT",0);

		// 获取每个打印项的信息
		var count = LODOP.GET_VALUE('ItemCount',0);
		var template_position=new Array();
		for(var i = 0; i < count; i++){
			template_position[i] = new Array();
			template_position[i]['top']		  = LODOP.GET_VALUE("ItemTop",i+1);
			template_position[i]['left']	  = LODOP.GET_VALUE("ItemLeft",i+1);
			template_position[i]['width']	  = LODOP.GET_VALUE("ItemWidth",i+1);
			template_position[i]['height']	  = LODOP.GET_VALUE("ItemHeight",i+1);
			template_position[i]['font_size'] = LODOP.GET_VALUE("ItemFontSize",i+1)?LODOP.GET_VALUE("ItemFontSize",i+1):9;
			
			type_english = LODOP.GET_VALUE("ItemName",i+1);
			var type_id  = new Array();
			type_id= type_english.split("***");
			// template_position[i]['type']=type_id[0];
			template_position[i]['english']   = type_id[1];
		}
		
		// 预览张数
		LODOP = getLodop();
		var page_top  = $("#page_top").val()?$("#page_top").val():0;
		var page_left = $("#page_left").val()?$("#page_left").val():0;

		LODOP.PRINT_INITA(page_top+"mm",page_left+"mm",800,600,"");
		var	print_total_num = $("#preview_num").val();
		// 设置纸张大小 和 类型
		var page_width  = $("#page_width").val() || !isNaN(page_width)?$("#page_width").val():229;
		var page_height = $("#page_height").val() || !isNaN(page_height)?$("#page_height").val():126;
			
		if(page_width<20 || page_width>400 || page_height<10 || page_height>400){
			alert("请设置合适的纸张大小");
			return false;
		}

		LODOP.SET_PRINT_PAGESIZE(1,page_width+"mm",page_height+"mm","");
		// 边框设置
		// var border = $("input[type=radio]:checked").val();
		for(var i = 1;i <= print_total_num; i++){
			LODOP.NewPageA(); //强行分页
			// 显示页码（仅预览）
			LODOP.ADD_PRINT_HTM(1,"70%",500,100,"总页号：<font color='#0000ff'><span tdata='pageNO'>第##页</span>/<span tdata='pageCount'>共##页(预览)</span></font>")
			LODOP.SET_PRINT_STYLEA(0,"ItemType",1);
			LODOP.SET_PRINT_STYLEA(0,"PreviewOnly",1);

			var image = $("input[name=template_image]:checked").val();
			
			if(image ==""){
				image = $("input[name=web_image]").val();
			}

			if(image != ""){
				LODOP.ADD_PRINT_SETUP_BKIMG("<img border='0' src='"+image+"'>");
				LODOP.SET_SHOW_MODE("BKIMG_IN_PREVIEW",1); //注："BKIMG_IN_PREVIEW"-预览包含背景图 "BKIMG_IN_FIRSTPAGE"- 仅首页包含背景图
				// LODOP.SET_SHOW_MODE("BKIMG_LEFT",1);
				// LODOP.SET_SHOW_MODE("BKIMG_TOP",1);
	
				LODOP.SET_SHOW_MODE("BKIMG_WIDTH",page_width+"mm"); //
				LODOP.SET_SHOW_MODE("BKIMG_HEIGHT",page_height+"mm"); //
			}
			
			for(var k in template_position){
				LODOP.ADD_PRINT_TEXTA(template_position[k]['english'],parseInt(template_position[k]['top']),parseInt(template_position[k]['left']),parseInt(template_position[k]['width']),parseInt(template_position[k]['height']),$("#printi ."+template_position[k]['english']).text());
				LODOP.SET_PRINT_STYLEA(template_position[k]['english'],"FontSize",template_position[k]['font_size']);
			}
		}
		LODOP.PREVIEW();
	});	

	// 二维数组排序
	function keysrt(key,desc) {
	 	return function(a,b){
	    	return desc ? ~~(a[key] - b[key]) : ~~(a[key] - b[key]);
	  	}
	}
	
	var template_position	= "";
	var template_info  		= "";
	// 保存为新模板
	$('#add_template').click(function(){
		var express_id 		= $("#company_express").val();
		var template_name 	= $("#template_name").val();
		if(express_id == "") {
			alert("请先选择快递公司！");
			return  false;
		}else if(template_name == ""){
			alert("模板名称不能为空！");
			return  false;
		}

		get_info();

		$.ajax({
			url: '/deliver/deliver_add_express_template.php',
			type: 'POST',
			dataType: 'text',
			data: {action:'add',template_position:template_position,template_info:template_info},
		})
		.done(function(data) {
			if(data == "ok"){
				alert("保存成功！");
			}else if(data == "exit"){
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
			url: '/deliver/deliver_edit_express_template.php',
			type: 'POST',
			dataType: 'text',
			data: {action:'edit',template_position:template_position,template_info:template_info},
		})
		.done(function(data) {//alert(data);
			if(data == "ok"){
				alert("修改完成！");
				location.href = "/setting/setting_express_template.php";
			}else if(data == "no"){
				alert("修改完成！");
				location.href = "/setting/setting_express_template.php";
			}
			//else if(data == "noexit"){
		// 		alert("模板不存在！");
		// 	}else if(data == "cant_name"){
		// 		alert("不能修改模板名称！");
		// 	}
		// 
		})
		.fail(function() {
			alert("网络异常！");
		});
	});

	// 获取打印控件中各个控件的信息
	function get_info(){
		LODOP = getLodop(document.getElementById('LODOP_OB'),document.getElementById('LODOP_EM'));
		// 模板信息
		var image 	= LODOP.GET_VALUE('BKIMG_CONTENT',0);
		var srcReg  =  /src=['"]?([^'"]*)['"]?/i;;
		var src 	= image.match(srcReg);
		if(src){
			img_src=src[1];
		}else{
			img_src=image;
		}

		if(img_src == ""){
			img_src = $("input[name=web_image]").val();
		}

		page_width = !isNaN($("#page_width").val())?$("#page_width").val():230;
		page_height = !isNaN($("#page_height").val())?$("#page_height").val():127;
		if(page_width<20 || page_width>400 || page_height<10 || page_height>400){
			alert("请设置合适的纸张大小");
			return false;
		}
		page_top = !isNaN($("#page_top").val())?$("#page_top").val():0;
		page_left = !isNaN($("#page_left").val())?$("#page_left").val():0;

		template_info = "{'express_id':'"+$("#template_id").attr('express_id')+"','template_id':'"+$("#template_id").attr('value')+"','template_name':'"+$("#template_name").val()+"','border':'"+$("input[type=radio]:checked").val()+"','page_width':'"+page_width+"','page_height':'"+page_height+"','page_top':'"+page_top+"','page_left':'"+page_left+"','template_image':'"+img_src+"'}";
		// 获取每个打印项的信息
		var count 	  = LODOP.GET_VALUE('ItemCount',0);
		// 控件位置信息
		template_position="[";
		for(var i = 0; i < count; i++){
		// 如果要添加不存在的信息，还要添加到template_info 表中item表中信息
			var type_item_id = LODOP.GET_VALUE("ItemName",i+1);
			var type_id =new Array();
			type_id= type_item_id.split("***");
			// template_position[i]['item_id']=type_id[1];
			// template_position[i]['english']=type_id[2];
			// template_position[i]['type']=type_id[0];
			template_position += "{'item_top':'"+LODOP.GET_VALUE("ItemTop",i+1)+"','item_left':'"+LODOP.GET_VALUE("ItemLeft",i+1)+"','item_width':'"+LODOP.GET_VALUE("ItemWidth",i+1)+"','item_height':'"+LODOP.GET_VALUE("ItemHeight",i+1)+"','item_font_size':'"+LODOP.GET_VALUE("ItemFontSize",i+1)+"','item_id':'"+type_id[0]+"'},";
		}
		template_position = template_position.substring(0,template_position.length-1);
		template_position+="]";
	}
	// 还原默认
	$("#reload_default").click(function(){
		location.reload(true);
	})

	// 获取上传图片地址
	// $("input[name=template_pic]").change(function(){
	// 	LODOP=getLodop(document.getElementById('LODOP_OB'),document.getElementById('LODOP_EM'));
	// 	alert(window.navigator.userAgent);
	// 	if(!this.value.match(/.jpg|.gif|.png|.bmp/i)){
	// 		alert('File type must be: .jpg, .gif, .bmp or .png !');return;
	// 	}
		
	// 	if(window.navigator.userAgent.indexOf("MSIE")>=1){
	// 			this.select();
	// 			var img_src = document.selection.createRange().text; 
	// 			alert(img_src);
	// 			alert(2);
 //       	}	
	// 	else if(window.navigator.userAgent.indexOf("Firefox")>=1){
	// 		if(this.files){
	// 			var img_src=window.URL.createObjectURL(this.files[0]);
	// 			alert(img_src);
	// 			LODOP.ADD_PRINT_SETUP_BKIMG("<img style='width:200px;height:200px;' src='"+img_src+"'></img>");
	// 			alert(1);
	// 			return false;
	// 		}

	// 		alert(this.value);
	// 			return false;

	// 	}
	// 		alert(this.value);
	// 			return false;
	// 	});

	$("#upload_pic").click(function(){
		var express_id 	= $("#company_express").val();
		if(express_id == ""){
			alert("请先选择快递公司！");
			return false;
		}
		var pic = $("input[name=uploadFile]").val();
		if(!pic){
			alert("请先选择图片！");
			return false;
		}

		if(!pic.match(/.jpg|.gif|.png|.bmp/i)){
			alert("图片格式错误");
			return false;
		}

		$('form').submit();
	});

	// function upload_template_pic(){
		// alert(1);
		// var express_id 	= $("#company_express").val();
		// if(express_id == ""){
		// 	alert("请先选择快递公司！");
		// 	return false;
		// }
		// var pic = $("input[name=template_pic]");
		// if(!pic.val()){
		// 	alert("请先选择图片！");
		// 	return false;
		// }

		// if(!pic.val().match(/.jpg|.gif|.png|.bmp/i)){
		// 	alert("图片格式错误");
		// 	return false;
		// }
		
		// $.ajax({
		// 	url: '/deliver/deliver_edit_express_template.php',
		// 	type: "POST",
  //           data: { imgPath: pic.val() },
  //           cache: false,
		// })
		// .done(function(data) {
		// 	if(data==200){
		// 		alert("success");
		// 	}
		// 	alert(data);
		// })
		// .fail(function() {
		// 	alert("error");
		// })
		// .always(function() {
		// 	alert("complete");
		// });

	// } 

	$('#edit_image').click(function(){
		var template_image	= $("input[name=web_image]").val();
		var template_id		= $("#template_id").attr('value');
		$.ajax({
			url: '/deliver/deliver_edit_express_template.php',
			type: 'POST',
			dataType: 'text',
			data: {action:'edit_image',template_image:template_image,template_id:template_id},
		})
		.done(function(data) {
			if(data == "ok"){
				$("input[name=template_image]").eq(0).attr("value",template_image);
				alert("设置成功！");
			}else{
				alert("设置完成！");
			}
		})
		.fail(function() {
			alert("网络异常！");
		});
		
	});

	// 选择打印模板
	$("body").on('click','#express_template',function(){	
		var td = $(this).parent(".dropdown_body");
		$(this).remove();
		td.find("select").attr("style","display:inline-block;width:150px;margin:0 10px 0 10px");
		td.find("select option").click(function(){
			var template_id = this.value;
			location.replace("/deliver/deliver_design_express.php?template_id="+template_id);
		});
    });


    $("#page_size").change(function(){
    	var page_size = $(this).val();
    	if(page_size == 1){
    		$("#page_width").val("230");
    		$("#page_height").val("127");
    	}else if(page_size == 2){
    		$("#page_width").val("217");
    		$("#page_height").val("140");
    	}else if(page_size == 0){
    		$("#page_width").val("");
    		$("#page_height").val("");
    	}
    	init();
    });

    $("#page_width,#page_height,#page_top,#page_left").change(function(){
    	init();
    })

    // 背景图片预览
    $("input[name=web_image]").change(function(){

    	if(!$(this).val().match(/.jpg|.gif|.png|.bmp/i)){
			alert("图片格式错误");
			return false;
		}
		init();
    });

    $('input[type=text]').focus(function(){$(this).select()});
});	
