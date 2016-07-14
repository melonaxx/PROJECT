$(function(){
	$("input").val("");
	$("textarea").val("");
	$("input").prop("checked",false);
	// $("select").each(function(i){
	// 	$("select").get(i).selectedIndex=0;
	// })
	/*----------------没有找到记录的显示隐藏---------------*/
	if($(".sstbody4 tr").length==0){
		$(".ssnorecord4").show();
	}else{
		$(".ssnorecord4").hide();
	}
})