$(function(){
	//收起或展开
	$(".car_right").click(function(){
		$(this).parents(".dropdown").find(".dropdown_body").slideToggle();
	});

	$(".car_left").click(function(){
		$(this).parents(".dropdown_body").find(".dropdown_body_body").slideToggle();
	});
	// 选择打印模板
	$("body").on('click','#express_template',function(){	
		var td = $(this).parent(".dropdown_body");
		$(this).remove();
		td.find("select").attr("style","display:inline-block;width:150px;margin:0 10px 0 10px");
		
    });
})