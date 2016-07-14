$(function(){
	var flag=true;
	$("input").val("");
	$("textarea").val("");
	$("input").prop("checked",false);	
	/*-----------------点击编辑>提交印刷单>成功-----------*/
	$(".psedit").on("click",function(){
		$(".psallcontent").hide();
		$(".ps-querysuccess").hide();
		$("#pseditsheet").show();
		$(".ps-com").on("click",function(){
			$("#pseditsheet").hide();
			$(".ps-opsuccess").show();
		})
	});
})
