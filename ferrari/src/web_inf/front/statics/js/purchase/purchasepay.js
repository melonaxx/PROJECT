$(function(){
	$(".money").change(function(){
		var money = $(this).val();
		var market = $(".market").val();
		if(Number(money)>Number(market)){
			$(this).val(market);
		}else if(Number(money)<0){
			$(this).val(0);
		}
	})

	if($("#tb tr").length == 0){
    $("#tf").hide();
    $(".no-find").show();
    }
    $(".rrow").change(function(){
        var p=$(this).val();
        var pps=$("#pps").html();
        var seach=$("#seach").val();
        window.location.href = "/purchase/purchasepay.php?num="+p+"&page="+pps+seach;       
    })
})