$(function(){
	$(".expense-detail").click(function(){
		$(".modal-expense").show();
	});
	$(".caret").click(function(){
		$(this).parents(".dropdown").find(".dropdown_body").slideToggle();
	});
	$(".rrow").change(function(){
		var p=$(this).val();
		var pps=$("#pps").html();
		window.location.href = "/purchase/expenserecord.php?num="+p+"&page="+pps; 		
	})
})