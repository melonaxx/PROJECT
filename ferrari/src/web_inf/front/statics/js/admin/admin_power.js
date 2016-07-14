$(function(){
	/*初始化*/
	$(".power-list li .check").each(function(){
		if(this.checked){
			$(this).parent().parent().siblings(".allcheck").prop("checked",true);
		}
	});

	/*收起或展开*/
	$(".sign").click(function(){
		if($(this).hasClass("glyphicon-plus")){
			$(this).addClass("glyphicon-minus").removeClass("glyphicon-plus");
			$(this).parent().next(".power-list").show();
		}else{
			$(this).addClass("glyphicon-plus").removeClass("glyphicon-minus");
			$(this).parent().next(".power-list").hide();
		}
	});
	/*总的收起或展开*/
	$(".open-power").click(function(){
		$(".power-list").show();
		$(".sign").addClass("glyphicon-minus").removeClass("glyphicon-plus");

	});
	$(".close-power").click(function(){
		$(".power-list").hide();
		$(".sign").addClass("glyphicon-plus").removeClass("glyphicon-minus");

	});
	/*全选*/
	$(".allcheck").on("click",function(){
		if(this.checked){
			$(this).siblings(".power-list").find("li .check").prop("checked",true);
		}else{
			$(this).siblings(".power-list").find("li .check").prop("checked",false);

		}
	});
	$(".power-list li .check").on("click",function(){
		if(this.checked){
			$(this).parent().parent().siblings(".allcheck").prop("checked",true);
		}
	})

})