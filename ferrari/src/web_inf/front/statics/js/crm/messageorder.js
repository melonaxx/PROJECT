$(function(){
	$(".message-order").click(function(){
		$(".modal-messageset3").show();
		$(".order-setmenu").html($(this).siblings(".message-ordermenu").html());
		$(".order-money").html($(this).siblings(".message-ordermoney").html());
	})
})