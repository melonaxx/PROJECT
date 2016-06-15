$(function(){
	$(".start").click(function(){
		var start = $(this).text();
		if(start == '开启'){
			$(this).text('关闭');
		}else{
			$(this).text('开启');
		}
	})
})