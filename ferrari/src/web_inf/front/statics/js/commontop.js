$(function(){
	var winUrl=window.location.href;
	var $navNode=$(".navbar-nav>li");
	$(".navbar-nav>li").removeClass("comtop-activemy");
	$navNode.each(function(){
		var $currentUrl=$(this).attr("licontent");
		winUrl.indexOf($currentUrl)>-1?$(this).addClass("comtop-activemy"):'';
	})
	// $(".mouseover-change").on("click",function(){
	// 	$(".mouseover-change").parent().removeClass("comtop-activeli");
	// 	$(this).parent().unbind("hover");
	// 	$(this).parent().removeClass();
	// 	$(this).parent().addClass("comtop-activeli");
	// 	$(".mouseover-change").css("border","");
	// 	$(this).css("border","1px solid #d6d6d6");
	// })
	top_enter($(".dropdown-all"));
	top_enter($(".dropdown-all-right"));
	top_enter($(".dropdown-ware"));
	top_enter($(".dropdown-purchase"));
	top_enter($(".dropdown-CRM"));
	top_enter($(".dropdown-money"));
	top_enter($(".dropdown-manage"));
	top_enter($(".dropdown-ship"));
	top_enter($(".dropdown-goods"));
	top_enter($(".dropdown-use"));
	top_enter($(".dropdown-prodction"));
	top_enter($(".dropdown-report"));
	var navbtnflag=true;
	$(".navbar-toggle").on("click",function(){
		if(navbtnflag){
			$(".navbar-nav").show();
			$(".navbar-nav").css("background","#ddd");
			navbtnflag=false;
		}else{
			$(".navbar-nav").hide();
			navbtnflag=true;
		}
	})
	if($(window).width()<750){
		$(".comtop-right").addClass("dropup");
	}else{
		$(".comtop-right").removeClass("dropup");
	}
	$(window).resize(function(){
		if($(window).width()>750 && $(window).width()<1120){
			$(".navbar-nav").hide();
			navbtnflag=true;
			console.log("a");
		}else if($(window).width()>1120){
			$(".navbar-nav").show();
			$(".navbar-nav").css("background","");
			$(".comtop-right").removeClass("dropup");
		}else if($(window).width()<750){
			$(".comtop-right").addClass("dropup");
		}
	});




})
//top下拉菜单的显示隐藏
function top_enter($obj){
	$obj.on("mouseover mouseout",function(event){
		if(event.type=="mouseover"){
			$obj.children().eq(1).show();
		}else{
			$obj.children().eq(1).hide();
		}
	});
	$obj.find("li").on("mouseover mouseout",function(event){
		if(event.type=="mouseover"){
			$obj.children().eq(1).show();
		}else{
			$obj.children().eq(1).hide()
		}
	});
}
/*图片放大效果*/
function Big(){
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
	}
