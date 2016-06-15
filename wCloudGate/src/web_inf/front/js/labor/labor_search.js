$(function(){

    $(".nav li:eq(3)").addClass("navactive");
    $(".carsearch").click(function(){
        $(".carsearch").css("border","1px solid $ccc");
    })    
    $(".searchbtn").click(function(){
        var val = $(".carsearch").val();
        if(val.length == 0){
            $(".carsearch").css("border","1px solid red");
            return false;
        }
        $(".carsearch").css("border","1px solid #ccc");
        $(".searchsub").click();
    })

    $(".adda").click(function(){
      var laborid = $(this).parent().prev('td').prev('td').prev('td').prev('td').prev('td').attr('attr');
      $(".laborid").val(laborid);
    })

    $(".add").click(function(){
        $(this).attr("disabled","true");
        $(".addsubmit").click();
    })

    $(".adda").on("click",function(){
        $("#modaladd").show();
    })
    $(".cancel").on("click",function(){
        $("#modaladd").hide();
    })


});
