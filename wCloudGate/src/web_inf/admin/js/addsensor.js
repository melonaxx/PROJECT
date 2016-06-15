$(function(){
    $(".activebtn").click(function(){                                  
        $(".modal").show();                                            
    });                                                                
    $(".sure").click(function(){                                       
        $(".modal").hide();
        $(".msgg").hide();
        var imei = $(".activationbox").val();                        
        if(imei === ""){                                             
            alert("请输入传感器imei号");                               
            return false;                                              
        }                                                              
        $(".modalr").show();                                           
        var data = {imei:imei}
        util.ajax_post('/doaddsensor.php',data,addSuccess,addFail);
    });
    function addSuccess (res){
        $(".modalr").hide();                                           
        var str = "成功:<br/>";
        if(!res['success']){
            str += '无<br/>';
        }else{
            for(var i=0;i<res['success'].length;i++){
                str += res['success'][i]+"<br/>";
            }
        }
        var ltr = "<span style='color:red;'>失败(imei已存在):<br/>";
        if(!res['fail']){
            ltr += "无</span><br/>";
        }else{
            for(var j=0;j<res['fail'].length;j++){
                ltr += res['fail'][j]+"<br/>";
            }
            ltr = ltr+"</span>";
        }

        $(".msgg").show().empty().html(str+ltr);
    }
    function addFail(res){
        $(".modalr").hide();
        $(".msgg").show().empty().html("<span style='color:red;'>4001:<br/>Please enter the correct serial number</span>");
    }
    $(".cancel").click(function(){
        $(".modal").hide();
    });
    $(".activationbox").keyup(function(){
        this.value = this.value.replace(/[^\d\n]/g,"");
    });

});
