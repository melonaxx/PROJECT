$(function(){
	$(".newCreate").click(function(){
		$(".modal-purchasepay").show();
	});
	//跳转指定tab页面
	var $url=window.location.href;
	if($url.indexOf("see")!=-1){
		$(".order-list").addClass("active").siblings().removeClass("active");
		$(".status2").removeClass("status1").siblings(".status").addClass("status1");
    }

    if($("#tb tr").length == 0){
    $("#tf").hide();
    $(".no-find").show();
    }
    $(".rrow").change(function(){
        var p=$(this).val();
        var pps=$("#pps").html();
        var seach=$("#seach").val();
        window.location.href = "/purchase/pendingpayment.php?num="+p+"&page="+pps+seach;       
    })
})