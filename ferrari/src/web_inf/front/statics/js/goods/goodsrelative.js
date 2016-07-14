$(function(){
	$("input").val("");
	$("textarea").val("");
	$("input").prop("checked",false);
	$("select").each(function(i){
		$("select").get(i).selectedIndex=0;
	})
	if($(".pstbody1 tr").length==0){
		$(".norecord").show();
	}else{
		$(".norecord").hide();
	}
	$(".gladdbtn").click(function(){
		$("#myModal").show();
	})
})