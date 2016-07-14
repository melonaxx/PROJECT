$(function(){
    var $code1;
    var $supply1;
    var $ware1;
    var $num1;
    var $price1;
    $(".outer").click(function(){
        $code1=$(this).parent().siblings(".purenter-code").html();
        $supply1=$(this).parent().siblings(".purenter-supply").html();
        $ware1=$(this).parent().siblings(".purenter-ware").html();
        $num1=$(this).parent().siblings(".purenter-num").html();
        $price1=$(this).parent().siblings(".purenter-price").html();
        localStorage.setItem("code1",$code1);
        localStorage.setItem("supply1",$supply1);
        localStorage.setItem("ware1",$ware1);
        localStorage.setItem("num1",$num1);
        localStorage.setItem("price1",$price1);
    });
    if(localStorage.getItem("code1")&&localStorage.getItem("supply1")&&localStorage.getItem("ware1")&&localStorage.getItem("num1")&&localStorage.getItem("price1")){
        $(".outerware-supply").val(localStorage.getItem("supply1"));
        $(".outerware-code").val(localStorage.getItem("code1"));
        $(".outerware-ware").val(localStorage.getItem("ware1"));
        $(".outerware-allnum").html(localStorage.getItem("num1"));
        $(".outerware-allprice").html(localStorage.getItem("price1"));
        $(".outerware-sprice").html(localStorage.getItem("price1")/localStorage.getItem("num1"));
    }
    if($("#tb tr").length == 0){
    $("#tf").hide();
    $(".no-find").show();
    }
    $(".rrow").change(function(){
        var p=$(this).val();
        var pps=$("#pps").html();
        var seach=$("#seach").val();
        window.location.href = "/purchase/returnlibrary.php?num="+p+"&page="+pps+seach;       
    })
})