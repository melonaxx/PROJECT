$(function(){
   $(".cancelbtn").on("click",function(){
       $("textarea[name='notice']").val("");
   });

   $(".noticebtn").on("click",function(){
       var val = $("textarea[name='notice']").val();
       if(val === ""){
           $(".not").html("<span style='color:red;'>请输入内容</span>");
           return false;
       }
       $("input[type=submit]").click();
   });

    $(".use-notice").on("click",function(){
        var notid =  $(this).parent().prev().prev().prev().html();
        $(".notid").each(function(){
            if($(this).html() == 1){
               nowid = $(this).next().html();
            }
        });
        var data = {notid:notid,nowid:nowid};
        util.ajax_post("/notice.php",data,notSuccess,notFail);
    });
    function notSuccess(data){
        window.location.href = "/web.php";
    }
    function notFail(data){
        alert("操作失败");
    }
});
