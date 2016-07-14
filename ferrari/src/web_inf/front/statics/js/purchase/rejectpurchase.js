$(function(){
    if($("#tb tr").length == 0){
    $("#tf").hide();
    $(".no-find").show();
    }
    $(".rrow").change(function(){
		var p=$(this).val();
		var pps=$("#pps").html();
		window.location.href = "/purchase/rejectpurchase.php?num="+p+"&page="+pps; 		
	})
})  