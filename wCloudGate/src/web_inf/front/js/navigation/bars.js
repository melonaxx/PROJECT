$(function($){
	var typeobj = $(".topul").children();
	typeobj.removeClass("active");
	var href = typeobj.children("a");
	var activeurl = window.location.href;
	$.each(href , function() {
		if(activeurl.indexOf($(this).attr("href")) > -1){
			$(this).parent().addClass("active");
		}
	});	
	$(".topright").on("mouseover",function(){
		$(".selectul").show();
	});
	$(".topright").on("mouseout",function(){
		$(".selectul").hide();
	});

});