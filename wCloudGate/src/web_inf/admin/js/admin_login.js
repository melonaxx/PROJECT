$(function(){
   $("input[type='button']").click(function(){
       var name = $("input[name='name']").val();
       var password = $("input[name='password']").val();
       if(name.length === 0)
       {
            $(".attention").html("请输入账号");
            return false;
       }
       if(password.length === 0)
       {
            $(".attention").html("请输入密码");
            return false;
       }
       var data = {name:name,password:password};
       util.ajax_post("/verify_login.php" , data , loginSuccess , loginFail);
   });

   $(document).ready(function(e) {
       $(this).keydown(function (e){
           if(e.which == "13"){
               $("input[type='button']").click();
           }
       })  
   });
   function loginSuccess(res){
       window.location = "/users_check.php";
   }
   function loginFail(res){
       $(".attention").html("用户名或密码错误");
   }
});
