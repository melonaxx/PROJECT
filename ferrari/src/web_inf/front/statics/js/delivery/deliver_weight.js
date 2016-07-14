$(function(){
	$(".btn-weight").click(function(){
		$(".modal-weight1").show();
	});
	$(".themoney").click(function(){
		$(".express-cost").val($(".weight-money").val());
	})
})