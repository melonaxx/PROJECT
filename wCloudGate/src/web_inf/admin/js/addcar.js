$(function(){
    $(".activebtn").click(function(){                                 
        $(".modal").show();                                           
    });                                                               
    $(".sure").click(function(){                                      
        $(".modal").hide();                                           
        var seqno = $(".activationbox").val();                         
        if(seqno === ""){                                              
            alert("请输入电动车序列号");                              
            return false;                                             
        }                                                             
        $(".modalr").show();                                          
        var data = {seqno:seqno};
        util.ajax_post('/doaddcar.php',data,addSuccess,addFail);
    });
    function addSuccess(res){
        $(".modalr").hide();                                              
        var str = "成功:<br/>";                                           
        if(!res['success']){                                              
            str += '无<br/>';                                             
        }else{                                                            
            for(var i=0;i<res['success'].length;i++){                     
                str += res['success'][i]+"<br/>";                         
            }                                                             
        }                                                                 
        var ltr = "<span style='color:red;'>失败(序列号已存在):<br/>";      
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
         $(".msgg").show().empty().html("<span style='color:red;'>4001:<br/>Please enter the correct imei number</span>");
    }
    $(".cancel").click(function(){                                    
        $(".modal").hide();                                           
    });                                                               
    $(".activationbox").keyup(function(){                             
        this.value = this.value.replace(/[^\d\n]/g,"");               
    });                                                               
});
