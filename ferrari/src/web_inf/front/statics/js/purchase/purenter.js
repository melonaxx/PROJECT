$(function(){
    //点击入库;
    var $code;
    var $supply;
    var $ware;
    var $num;
    var $price;
    $(".enter").click(function(){
    	$code=$(this).parent().siblings(".purenter-code").html();
    	$supply=$(this).parent().siblings(".purenter-supply").html();
    	$ware=$(this).parent().siblings(".purenter-ware").html();
    	$num=$(this).parent().siblings(".purenter-num").html();
    	$price=$(this).parent().siblings(".purenter-price").html();
    	localStorage.setItem("code",$code);
    	localStorage.setItem("supply",$supply);
    	localStorage.setItem("ware",$ware);
    	localStorage.setItem("num",$num);
    	localStorage.setItem("price",$price);
    });
    if(localStorage.getItem("code")&&localStorage.getItem("supply")&&localStorage.getItem("ware")&&localStorage.getItem("num")&&localStorage.getItem("price")){
    	$(".enterware-supply").val(localStorage.getItem("supply"));
    	$(".enterware-code").val(localStorage.getItem("code"));
    	$(".enterware-ware").val(localStorage.getItem("ware"));
    	$(".enterware-allnum").html(localStorage.getItem("num"));
    	$(".enterware-allprice").html(localStorage.getItem("price"));
    	$(".enterware-sprice").html(localStorage.getItem("price")/localStorage.getItem("num"));
    }
    if($("#tb tr").length == 0){
    $("#tf").hide();
    $(".no-find").show();
    }
    $(".rrow").change(function(){
        var p=$(this).val();
        var pps=$("#pps").html();
        var seach=$("#seach").val();
        window.location.href = "/purchase/purenter.php?num="+p+"&page="+pps+seach;       
    })
})