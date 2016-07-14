$(function(){
    if($("#tb tr").length == 0){
    $("#tf").hide();
    $(".no-find").show();
    }
    $(".rrow").change(function(){
        var p=$(this).val();
        var pps=$("#pps").html();
        var seach=$("#seach").val();
        window.location.href = "/purchase/searchpurchase.php?num="+p+"&page="+pps+seach;       
    })
})  