$(function(){
    //验证码刷新
    $(".imgCaptcha").on("click", function() {
        this.src = "/captcha.php?l=update&_=" + Math.random();
        $("input[name=captcha]").val("").focus();
        return false;
    });
    $(".changecap").on("click", function() {
        $(".imgCaptcha").click();
        $("input[name=captcha]").val("").focus();
        return false;
    });

    $(".close").click(function(){
        window.location.reload();
    });
   // $(".checkcode").on("blur",function(){
   //     $(this).css({"border":"1px solid red"});
   // })
   
    //send code
    $(".getdynamiccode").on("click",function(){
        $(".message-captcha").hide();
        var captcha = $("input[name='captcha']").val();
        if(captcha === ""){
            $(".message-captcha").show();
            return false;
        }
        var data = {captcha:captcha};
        util.ajax_post('/accountset/docheckcaptcha.php',data,capSuccess,capFail);
    })                                             
    function capSuccess(data){
        if(data === 0){
            $(".imgCaptcha").click();
            var mobileno = $("input[name=phone]").val();    
            var data = {mobileno:mobileno};                                       
            $(".getdynamiccode").attr("disabled",true);
            $(".getdynamiccode").html("正在发送......");
            $(".getdynamiccode").attr("style","cursor:wait");
            util.ajax_post("/accountset/sendcode.php",data,sendSuccess,sendFail)  
        }else{
            $(".message-captcha").show();
            $(".imgCaptcha").click();
        }
    }
    function capFail(data){
        $(".error-message").show();
    }

    function sendSuccess(data){
        var time = 59;
        var t=setInterval(function() {               
            time--;
            $(".time").html("("+time+"s)");        
            if (time==0) {
                clearInterval(t);
                $(".time").html("");
            }
        },1000)                                      

        $("#modalverifycode").show();
    }
    function sendFail(data){
        $("#modalverifycode").show();                  
        $(".modalcontent").html("<h3 style='text-align:center;color:red;font-size:20px;'>发送异常,请稍后重试!</h3>");                  
    }

    //重新发送
    $(".resend").click(function(){
        if($(".time").html()=== ""){
          var mobileno = $("input[name=phone]").val();    
          var data = {mobileno:mobileno};                                       
          util.ajax_post("/accountset/sendcode.php",data,sendSuccess,sendFail)  
        } 
    });

    //验证短信
    $(".checkcode").keyup(function(){
        this.value = this.value.replace(/[^\d]/g,"");
    });
    $(".checkcodebtn").click(function(){
        var code = $(".checkcode").val();
        if(code === ""){
            $(".error-code").show();
            return false;
        }
        var data = {code:code};
        util.ajax_post("/accountset/checknote.php",data,verifySuccess,verifyFail);
        $(".checkcodebtn").attr("disabled",true);
    });
    function verifySuccess(data){
        $(".checkcodebtn").attr("disabled",false);
        if(data === 0){
            window.location = '/accountset_updatepassword.php';
        }else{
            $(".error-code").show();
        }
    }
    function verifyFail(data){
        $(".checkcodebtn").attr("disabled",false);
        $(".errno-code").show();
        return false;
    }

//    //send code
//    $(".codebtn").on("click",function(){
//        var status = $(".status").html();
//        var captcha = $("input[name='captcha']").val();  
//        var mobileno = $("input[name=pphone]").val();    
//        if(status == 1){
//        if(captcha.length == 0)                          
//        {                                                
//            $(".checkcapt").html("验证码不能为空");       
//            return false;                                
//        }                                                
//        if(mobileno.length == 0)                         
//        {                                                
//            $(".checkphone").html("手机号不能为空");     
//            return false;                                
//        }                                                
//        $(".checkcapt").html("");                         
//       // check captcha
//        $.ajax({                                              
//            url:"/accountset/docheckcaptcha.php",             
//            type:"POST",                                      
//            data:"captcha="+captcha,                          
//            dataType:"json",                                  
//            async:true,                                       
//            success:function(res){                            
//                if(res['data'] === 0){                          
//                    $(".checkcapt").html("");
//                    $(".smscodebtn").html("发送中...");
//                    $(".status").html("0");
//                    var data = {mobileno:mobileno};
//                    util.ajax_post("/accountset/sendcode.php",data,ckpwSuccess,ckpwFail)
//                }else{                                         
//                    $(".checkcapt").html("验证码错误");        
//                    $(".imgCaptcha").click();                  
//                    return false;                              
//                }                                              
//            }                                                 
//        })
//        }else{
//            return false;
//        }
//    })                                                        
//    
//    function ckpwSuccess(data){
//        $(".smscodebtn").html("59秒");               
//        var time = 59;
//        var t=setInterval(function() {               
//            time--;
//            $(".smscodebtn").html(time+"秒");        
//            if (time==0) {
//                clearInterval(t);                    
//                $(".smscodebtn").html("获取动态码"); 
//                $(".status").html("1");
//            }
//        },1000)                                      
//    }
//    function ckpwFail(data){
//         $(".emptycode").html("发送失败，请稍后重新发送");
//         $(".smscodebtn").html("获取动态码");             
//         $(".status").html("1");                          
//    }

//    $(".verify").on("click",function(){
//        var code = $("input[name=smscode]").val();
//        if(code.length == 0){
//            $(".emptycode").html("请输入手机动态码");
//            return false;
//        }
//       
//        var data = {code:code};
//        util.ajax_post("/accountset/checknote.php",data,verifySuccess,verifyFail);
//    })
//        
//    function verifyFail(data){}
//    function verifySuccess(data)
//    {
//        if(data == 0)
//        {
//            $(".allform1").hide();
//            $(".allform2").show();
//        }else{
//            $(".emptycode").html("手机动态码错误");       
//        }

//    }

   $(".updatepasswd").click(function(){
        $(".updatepasswd").attr("disabled",true);
        var pwd1 = $("input[name=pwd1]").val();
        var pwd2 = $("input[name=pwd2]").val();
        var userid  = $("input[name=userid").val();
             
        if(pwd1.length < 6 || pwd1.length > 18) {  
            $(".checkpasswd").html("密码应为6-18位");                    
            $(".updatepasswd").attr("disabled",false);
            return false;                                   
        }                                                  
        if(pwd1 !== pwd2){
           $(".checkpasswd").html("两次密码不一致，请从新输入");
           $("input[name=pwd1]").val("");
           $("input[name=pwd2]").val("");
           $(".updatepasswd").attr("disabled",false);
           return false;
        }

        $(".checkpasswd").html("");
        
        var data = {pwd1:pwd1,userid:userid};
        util.ajax_post("/accountset_doupdatepasswd.php",data,upSuccess,upFail);
    });
    function upSuccess(data){
        $("#modalmodpw").show(); 
        $("#modalmodpw button").click(function(){
            window.location.reload();
        }); 
    }
    function upFail(data){
        alert("修改失败");
        $(".updatepasswd").attr("disabled",false);
    }

})


