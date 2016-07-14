$(function(){
     //详细模态窗;
    $(".purenter-detail").click(function(){
        $("#tbs").empty();
        $(".modal-purenter").show();
        var id = $(this).attr('uid');
        var nums = $(this).parent().next().next().next().next().next().next().html();
        $("#nums").html(nums);
        $.ajax({
            type: "POST",
            url: "/purchase/danjuinfo.php",
            data:{"id":id},
            dataType:"json",
            success: function(msg){
               $.each(msg,function(idx,item){ 
                    var purname = item.proname;
                    var dwname = item.dwname;
                    var price = item.price;
                    var total = item.total;
                    var payment = item.payment;
                    var body = item.body;
                    var valuename1 = item.valuename1;
                    var valuename2 = item.valuename2;
                    var valuename3 = item.valuename3;
                    var valuename4 = item.valuename4;
                    var valuename5 = item.valuename5;
                    $("#tbs").append("<tr><td>1</td><td>"+purname+"-"+valuename1+"-"+valuename2+"-"+valuename3+"-"+valuename4+"-"+valuename5+"</td><td>"+dwname+"</td><td class='purentermodal-sinprice1'>"+price+"</td><td class='purentermodal-num1'>"+total+"</td><td class='purentermodal-price1'>"+payment+"</td><td>"+body+"</td></tr>");     
                });
            }
        })
    })


    if($("#tb tr").length == 0){
    $("#tf").hide();
    $(".no-find").show();
    }
    $(".rrow").change(function(){
        var p=$(this).val();
        var pps=$("#pps").html();
        var seach=$("#seach").val();
        window.location.href = "/purchase/inoroutlist.php?num="+p+"&page="+pps+seach;       
    })
})