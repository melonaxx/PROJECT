$(function(){

    var usertype = $("input[name=usertype]").val() || 0;
    if(usertype == 4) {
        $(".useroperul li:eq(1)").addClass("useractive");
    }else {
        $(".useroperul li:eq(2)").addClass("useractive");
    }
    $(".editoruser").on("click",function(){
        $("input[name='username']").val($("input[name='name']").val());
        $(".modalu").show();
        $("input[name='username']").focus();
    })
    $(".cancelu").on("click",function(){
        $(".modalu").hide();
    })
    $(".editoremail").on("click",function(){
       $("input[name='useremail']").val($("input[name='email']").val());
       $(".modale").show();
       $("input[name='useremail']").focus();
    })
    $(".cancela").on("click",function(){
        $(".modal").hide();
    })

    //update passwd                                       
    $(".modpassword").click(function(){                   
        if($("input[name='mobileno']").val().length>0){     
            $("input[type='submit']").click();               
        }else{                                              
            alert("手机号不合法！");                         
        }                                                   
    }) 

    //updatename
    $(".addu").on("click",function(){
        var username = $("input[name=username]").val();
        var name = $("input[name=name]").val();
        if(username.match(/^[\u4E00-\u9FA5\uF900-\uFA2Da-zA-Z0-9]{1,}$/) === null) {
           $(".errorname").attr("style","color:red;");
           $(".errorname").html("姓名格式错误");
            return false;;
        }
        if(name != username ){
            var data = {name:username}; 
            util.ajax_get("accountset_updatename.php",data,updSuccess,updFail);
        }else{
            window.location="accountset.php";
        }
    })

    //updateemail
    $(".add").on("click",function(){
        var useremail = $("input[name=useremail]").val();
        var email = $("input[name=email]").val();
        
        if(useremail.match(/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/) == null) {   
            $(".erroremail").attr("style","color:red;");
            $(".erroremail").html("邮箱格式错误");                                                
            return false;                                                            
        }                                                                            
       
        if(email != useremail ){
            var data = {email:useremail}; 
            util.ajax_get("accountset_updateemail.php",data,updSuccess,updFail);
        }else{
            window.location="accountset.php";
        }

    })

    function updSuccess(data)
    {
        if(data == 0)
        {
            window.location = "accountset.php";   
        }
    }
    function updFail(data){
        alert("修改失败");
        window.location = "accountset.php";
    };
})
