$(function(){
    $(".goback").click(function(){
        window.location = "/car.php";
    });

    $(".activebtn").click(function(){
        $(".modal").show();
    });
    $(".sure").click(function(){
        $(".modal").hide();
        var userid = $("h4").children().attr("userid");
        var serial = $(".activationbox").val();
        if(serial === ""){
            alert("请输入电动车序列号");
            return false;
        }
        if(userid === ""){
            alert("请检查用户信息");
            return false;            
        }
        $(".modalr").show();
        var data = {userid:userid,serial:serial}
        util.ajax_post("/dorelievecar.php",data,relieveSuccess,relieveFail);
    });
    $(".cancel").click(function(){                      
        $(".modal").hide();                             
    });                                                 
    $(".activationbox").keyup(function(){               
        this.value = this.value.replace(/[^\d\n]/g,""); 
    }); 

    function relieveSuccess(res){
        $(".msg").show();                                     
        $(".modalr").hide();
        var str = "成功解除激活:<br/>";                           
        if(!res['success']){                                  
            str += "无<br/>";                                 
        }else{                                                
            for(var i=0;i<res['success'].length;i++){         
                str += res['success'][i]+"<br/>";             
            }                                                 
        }                                                     
        var ltr = "<span style='color:red;'>解除激活失败:<br/>";  
        if(!res['fail']){                                     
            ltr += "无</span><br/>";                          
        }else{                                                
            for(var j=0;j<res['fail'].length;j++){            
                ltr += res['fail'][j]+"<br/>";                
            }                                                 
            ltr = ltr+"</span>";                              
        }                                                     
        $(".msg").html(str+ltr);                              
    }
    function relieveFail(res){
         $(".msg").show();
         $(".msg").html(res+":<br/>Please complete the serial number");
    }

});
