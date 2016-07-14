$(function(){
	/*图片放大效果*/
	$(".images").each(function(i){
		$(this).on("mouseover",function(){
			
			$(this).next().show();
			var height=$(this).siblings(".bigimg").height();
			var height1=$(this).height();
			var top=$(this).siblings(".bigimg").position().top;
			$(this).on("mousemove",function(event){
				var event = event || window.event;	
				var gao=$(window).height();
				var iheight =gao -(event.clientY);	
				var top1 = (iheight < height?-height:top) + "px";
				$(this).siblings(".bigimg").css("top",top1);
			});
		});
	});
	$(".images").each(function(i){
		$(this).on("mouseout",function(){
			$(this).next().hide();
		});
	})
	$(".delete").click(function(){
		if($(this).next().html()==""){
			$(".modal-mouldset1").show();
			mid = $(this).attr("mid");
			// location.href="/admin/deletelodopmodel.php?id="+$(this).attr("mid");
		}else{
			$(".modal-mouldset").show();
		}
	})
	$(".bun-sure").click(function(){
         location.href="/admin/deletelodopmodel.php?id="+mid;
	})

})