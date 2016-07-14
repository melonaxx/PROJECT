$(function(){
	/*图片放大效果*/
	$(".img1").each(function(i){
		$('.img1').eq(i).on("mouseover",function(){
			$(".img2").eq(i).show();
			var height=$(this).siblings(".img2").height();
			var height1=$(this).height();
			var top=$(this).siblings(".img2").position().top;
			$(this).on("mousemove",function(event){
				var event = event || window.event;
				var gao=$(window).height();
				var iheight =gao -(event.clientY+height1+10);
				var top1 = (iheight < height?-height:top) + "px";
				$(this).siblings(".img2").css("top",top1);
			});
		});
	});
	$(".img1").each(function(i){
		$(this).on("mouseout",function(){
			$(".img2").eq(i).hide();
		});
	})
})