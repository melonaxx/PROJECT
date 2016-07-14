$(function(){
 
   var LODOP=getLodop(document.getElementById('LODOP_OB'),document.getElementById('LODOP_EM')); //声明为全局变量

	function load(){
		// 获取系统打印机信息
		var printer_count 	=  LODOP.get_printer_count();//获取打印机个数
		var printer_name 	= new Array();
		for(var i=0;i<printer_count;i++){
			printer_name[i] = LODOP.get_printer_name(i);//获取打印机设备名字
			$("select[name=printer]").append("<option value="+i+">"+printer_name[i]+"</option>");
		}

		//左边距,上边距  宽高
		var page_top = $("#page_top").val()?$("#page_top").val():0;
		var page_left 	= $("#page_left").val()?$("#page_left").val():0;
		var page_width  = $("#page_width").val()?$("#page_width").val():229;
		var page_height = $("#page_height").val()?$("#page_height").val():140;

		LODOP.PRINT_INITA(page_top+"mm",page_left+"mm",page_width+"mm",page_height+"mm","");
		//打印初始化、设定纸张整体偏移量、设定可视编辑区域大小
		LODOP.SET_SHOW_MODE("DESIGN_IN_BROWSE",1);//打印设计界面是否内嵌到网页内部
		LODOP.SET_SHOW_MODE("HIDE_PBUTTIN_PREVIEW",1);//隐藏预览窗口的打印按钮
		LODOP.SET_SHOW_MODE("HIDE_PBUTTIN_SETUP",1);//隐藏打印维护及设计窗口的打印按钮
		LODOP.SET_SHOW_MODE("HIDE_ABUTTIN_SETUP",1);//隐藏打印维护及设计窗口的应用（暂存）按钮
		LODOP.SET_SHOW_MODE("HIDE_DISBUTTIN_SETUP",1);//隐藏打印维护窗口那些已经禁止操作的无效按钮
		// LODOP.SET_SHOW_MODE("HIDE_VBUTTIN_SETUP",1);//隐藏打印维护及设计窗口的预览按钮
		LODOP.SET_SHOW_MODE("HIDE_RBUTTIN_SETUP",1);//隐藏打印维护及设计窗口的复原按钮
		LODOP.SET_SHOW_MODE("BKIMG_IN_PREVIEW",1);// 打印预览时是否包含背景图
		LODOP.SET_SHOW_MODE("HIDE_GROUND_LOCK",1);//隐藏打印维护及设计窗口的纸钉按钮

		LODOP.SET_PRINT_PAGESIZE(1,page_width+"mm",page_height+"mm","");
		LODOP.SET_PRINT_STYLEA(0,"PreviewOnly",1);
		//背景图是否显示
		var img = $("input[name='template_image']:checked").val();
		LODOP.SET_SHOW_MODE("BKIMG_IN_PREVIEW",img);// 打印预览时是否包含背景图
        //背景图
        var src="http://img.1sheng.com/";
            src += $("#bgform").attr("src");
        if(src != "http://img.1sheng.com/"){
        	LODOP.ADD_PRINT_SETUP_BKIMG("<img border='0' src='"+src+"'>");
	    }
	    
		LODOP.PRINT_DESIGN();
	}
	load();

	// 获取小控件的属性
	$(".express_item").on("click",function(){
	   var top = $(this).attr("item_top") ? $(this).attr("item_top") : Math.random()*Math.random()* 500;
	   var left = $(this).attr("item_left") ? $(this).attr("item_left") : Math.random()*Math.random()*500;
	   var width = $(this).attr("item_width") ? $(this).attr("item_width") : 160;
	   var height = $(this).attr("item_height") ? $(this).attr("item_height") : 20;
	   var font_size = $(this).attr("font_size") ? $(this).attr("font_size") : 10;
	   var item_id = $(this).attr('id');
	   console.log(item_id);
	   Moditify(this,top,left,width,height,font_size,item_id);
	})
	
	function Moditify(item,top,left,width,height,font_size,item_id){
		LODOP=getLodop(document.getElementById('LODOP_OB'),document.getElementById('LODOP_EM'));
		if ((!LODOP.GET_VALUE("ItemExist",item_id)) && (item.checked)){
			LODOP.ADD_PRINT_TEXTA(item_id,top,left,width,height,item.value);
			LODOP.SET_PRINT_STYLEA(0,"FontSize",font_size);
		} else {
			LODOP.SET_PRINT_STYLEA(item_id,'Deleted',!item.checked);
		}
	}

	//删除全部
	$(".del-all").click(function(){
		LODOP.SET_PRINT_STYLEA('All','Deleted',true);
		 $(".express_item").each(function(){
			if(this.checked) {
				$(this).attr("checked",false);
			}
		 })
	})
	//删除单个
	$(".del-select").click(function(){
		LODOP.SET_PRINT_STYLEA('Selected','Deleted',true);
		var count = LODOP.GET_VALUE('ItemCount',0);////获得程序代码、打印项属性等数据值。
		var item_id_list = "";
		var type_id  = new Array();
		for(var i = 0; i < count; i++){
			item = LODOP.GET_VALUE("ItemName",i+1);
			type_id= item.split("|");
			item_id_list += type_id[0]+',';
		}
		$('.express_item').each(function() {
			if(this.checked && item_id_list.indexOf($(this).attr("id")) == -1){
				$(this).trigger('click');
			}
		});
	})
	// 预览
	// $("#preview").click(function(){
	// 	LODOP=getLodop(document.getElementById('LODOP_OB'),document.getElementById('LODOP_EM')); //声明为全局变量
	// 	var temp_item = new Array();
	// 	var count = LODOP.GET_VALUE("ItemCount",0);
	// 	for(i = 0 ; i < count ; i++){
	// 		temp_item[i]=new Array();
	// 		temp_item[i]['top']=LODOP.GET_VALUE("ItemTop",i+1);
	// 		temp_item[i]['left']=LODOP.GET_VALUE("ItemLeft",i+1);
	// 		temp_item[i]['width']=LODOP.GET_VALUE("ItemWidth",i+1);
	// 		temp_item[i]['height']=LODOP.GET_VALUE("ItemHeight",i+1);
	// 		temp_item[i]['fontsize']=LODOP.GET_VALUE("ItemFontSize",i+1);
	// 		temp_item[i]['itemname']=LODOP.GET_VALUE("ItemContent",i+1);
	// 	}
	// 	// 预览张数
	// 	var page_top  = $("#page_top").val()?$("#page_top").val():0;
	// 	var page_left = $("#page_left").val()?$("#page_left").val():0;
	// 	var page_width  = $("#page_width").val()?$("#page_width").val():230;
	// 	var page_height = $("#page_height").val()?$("#page_height").val():142;
	// 	var	preview_num = $("#preview_num").val();

	// 	// LODOP.PRINT_INITA(page_top+"mm",page_left+"mm",800,600,"");
	// 	var page_width  = $("#page_width").val() || !isNaN(page_width)?$("#page_width").val():229;
	// 	var page_height = $("#page_height").val() || !isNaN(page_height)?$("#page_height").val():126;
	// 	//设置纸张宽高
	// 	// LODOP.SET_PRINT_PAGESIZE(1,page_width+"mm",page_height+"mm","");
	// 	LODOP.SET_PRINT_PAGESIZE(1,"229mm","126mm","");
	// 	LODOP.SET_PRINT_STYLEA(0,"PreviewOnly",1);

	// 	for(var k in temp_item){
	// 		LODOP.ADD_PRINT_TEXTA(temp_item[k]['itemname'],parseInt(temp_item[k]['top']),parseInt(temp_item[k]['left']),parseInt(temp_item[k]['width']),parseInt(temp_item[k]['height']),temp_item[k]['itemname']);
	// 		LODOP.SET_PRINT_STYLEA(temp_item[k]['itemname'],"FontSize",temp_item[k]['font_size']);
	// 	}
	// 	LODOP.SET_SHOW_MODE("PREVIEW_IN_BROWSE",0);
	// 	LODOP.SET_SHOW_MODE("BKIMG_IN_PREVIEW",1);
	// 	LODOP.SET_SHOW_MODE("HIDE_PAPER_BOARD ",true);

	// 	LODOP.PREVIEW();
	// });
	
		//设置偏移
	var page_top = document.getElementById("page_top");
	var page_left = document.getElementById("page_left");
    page_top.onblur=function(){	
    	
        if(page_top.value==""){
    		page_top.value=0;
    	}
    	load();
    }
    page_left.onblur=function(){
    	
	    if(page_left.value==""){
    		page_left.value=0;
    	}
    	load();
    }
    //设置宽高
    var page_width = document.getElementById("page_width");
    var page_height = document.getElementById("page_height");

    page_width.onblur=function(){
    	
    	if(page_width.value==""){
    		page_width.value=230;
    	}
    	load();
    }
    page_height.onblur=function(){	
    	
    	if(page_height.value==""){
    		page_height.value=127;
    	}
		load();
    }
    //select框选中宽高
    page_size = document.getElementById("page_size");
    page_size.onchange = function(){
    	
    	 page_width.value=page_size.value.substr(0,3);
    	 page_height.value=page_size.value.substr(4,7);
    	 load();
    }
    //保存快递模版
    $("#savemodel").on("click",function(){
		var temp_item = new Array();
		//小控件数组
		var count = LODOP.GET_VALUE("ItemCount",0);
		for(i = 0 ; i < count ; i++){
			temp_item[i]={};
			temp_item[i]['top']=LODOP.GET_VALUE("ItemTop",i+1);
			temp_item[i]['left']=LODOP.GET_VALUE("ItemLeft",i+1);
			temp_item[i]['width']=LODOP.GET_VALUE("ItemWidth",i+1);
			temp_item[i]['height']=LODOP.GET_VALUE("ItemHeight",i+1);
			temp_item[i]['fontsize']=LODOP.GET_VALUE("ItemFontSize",i+1);
			temp_item[i]['itemname']=LODOP.GET_VALUE("ItemContent",i+1);
			temp_item[i]['Itemid']=LODOP.GET_VALUE("ItemName",i+1);
		}
		//宽高,偏移量
   		var page_top = $("#page_top").val()?$("#page_top").val():0;
		var page_left 	= $("#page_left").val()?$("#page_left").val():0;
		var page_width  = $("#page_width").val()?$("#page_width").val():229;
		var page_height = $("#page_height").val()?$("#page_height").val():140;
        //背景图地址
        var src =$("#bgform").attr("src");
        //快递公司
        var company_express = $("#company_express option:selected").val();
        //模版名称
        var template_name = $("#template_name").val();
        
        var templateinfo = {};
   	 		templateinfo['company_express'] = company_express;
		 	templateinfo['template_name'] = template_name;
			templateinfo['page_top'] = page_top;
            templateinfo['page_left'] = page_left;
            templateinfo['page_width'] = page_width;
            templateinfo['page_height'] = page_height;
            templateinfo['image'] = src;
        if(company_express == "0"){
        	alert("请选择快递公司");
        	return false;
        }else if(template_name == ""){
            alert("请输入模版名称");
            return false;
        }else{
            console.log(temp_item);
        	$.ajax({
			   type: "POST",
			   url: "/admin/savemodel.php",
			   data: {
			   	     'temp_item':temp_item,
			   	     'templateinfo':templateinfo
			   	     },
			   success: function(msg){
			   	    if(msg=="yes"){
			   	    	alert("模版保存成功");
			   	     location.href="/admin/adddesignexpress.php";
			   	    }else{
			   	    	alert("模版保存失败");
			   	    }
			   },
			   error:function(){
			   	  alert("ajax请求失败");
			   }
			});
        }
    });

});
    // 预览是否显示背景图
    function bg(){
    	var bgimg = document.getElementsByName("template_image");
	    for(i = 0 ; i < bgimg.length ; i++){
		   	 	if(bgimg[i].checked){
			    	bgvalue = bgimg[i].value;
		   	 	}
	    }
	   LODOP.SET_SHOW_MODE("BKIMG_IN_PREVIEW",bgvalue);
    }
