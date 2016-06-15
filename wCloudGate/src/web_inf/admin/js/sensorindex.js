$(function(){
    $(".inshow").click(function(){
        $(".table_conf").attr("style","display:block;"); 
        $(".table_single").attr("style","display:none;");
    });
    $(".inhide").click(function(){
        $(".table_conf").attr("style","display:none;");
        $(".table_single").attr("style","display:block;");

    });
    /*--------------通用配置添加---------------*/  
    $(".add").on("click",function(){               
        $(".modal").show();                        
    });                                            
    $(".addsub").on("click",function(){            
        $(".modal").hide();
        window.location.reload();
    });
    $(".update-btn").one("click",function(){
        var coll = $(".ins-coll").val().replace(/[^0-9]/ig,"");
        var wi   = $(".ins-wi").val().replace(/[^0-9]/ig,"");
        var freq = $(".ins-freq").val().replace(/[^0-9]/ig,"");
        var wf   = $(".ins-wf").val().replace(/[^0-9]/ig,"");
        //var url  = $(".ins-url").val();
        //var wurl = $(".ins-wurl").val();
        var ver  = $(".version").html();
        //var data = {ver:ver,cf:coll,wi:wi,f:freq,wf:wf,url:url,wurl:wurl}
        var data = {ver:ver,cf:coll,wi:wi,f:freq,wf:wf};
        util.ajax_post("/sensorupdate.php" ,data ,updateSuccess , updateFail);
        function updateSuccess(res){window.location.reload();}
        function updateFail(res){alert("更新失败")}
    });
    /*-------------通用配置添加模态框点击修改------------------*/       
    $(".modal").find(".modbtn").each(function(i){                       
        $(this).on("click",function(){                                  
            $(".update_conf"+(i+1)).show();
            //if(i>4){
            //    var value = $(this).prev().val();
            //}else{
                var value = $(this).prev().val().replace(/[^0-9]/ig,"");
            //}
            $(".update_conf"+(i+1)).children("div:nth-child(2)").children("div:nth-child(1)").children("input").focus();
            $(".update_conf"+(i+1)).children("div:nth-child(2)").children("div:nth-child(1)").children("input").keyup(function(){
                //if(i<5){   
                   this.value = this.value.replace(/[^\d]/g,"");
                //}  
            });
            $(".update_conf"+(i+1)).children("div:nth-child(2)").children("div:nth-child(1)").children("input").val(value);
        })                                                              
    });                                                                  
    $(".modsure").on("click",function(){
        var value = $(this).parent().parent().find(".form-control").val();
        var txt = $(this).parent().prev().prev("div").children("span").html();
        var num = $(this).parent().parent().parent().attr("class").replace(/[^0-9]/ig,"").substr(0,1);;
        $(".comm").children("div:nth-child("+num+")").children("input").val(value+txt);
        $(".conn").children("div:nth-child(2)").children("div:nth-child("+num+")").children("input").val(value+txt);
        if($(this).parent().parent().find(".form-control").val()==""){  
            $(".noempty").show();                                       
        }else{                                                          
            $(".noempty").hide();                                       
            $(this).parents(".modalmod").hide();                        
        }                                                               
    });                                                                  
    $(".modcancel").on("click",function(){                              
        $(".noempty").hide();                                           
        $(this).parents(".modalmod").hide();                            
    });                                                                  
    /*--------------个别配置添加---------------*/        

    $(".addresp").on("click",function(){                         
        $(".modalresp").show();
        $(".insert").prev("input").val("");
    });                                                            
    $(".addsub2").on("click",function(){                          
        $(".modalresp").hide();
        $(".stat").val("true");
        $(".insert").prev("input").val("");
    }); 
    $(".insert-btn").on("click",function(){
        var ebkid= $(".conf-ebkid").val();
        var coll = $(".conf-coll").val().replace(/[^0-9]/ig,"");            
        var wi   = $(".conf-wi").val().replace(/[^0-9]/ig,"");              
        var freq = $(".conf-freq").val().replace(/[^0-9]/ig,"");            
        var wf   = $(".conf-wf").val().replace(/[^0-9]/ig,"");              
        //var url  = $(".conf-url").val();                                    
        //var wurl = $(".conf-wurl").val();                                   
        var ver  = 1;
        if(ebkid.length != 16){
            $(".dang").html("请输入正确的序列号");
            return false;
        }
        if(ebkid.length === 0 || coll.lengthi===0 || wi.length===0 || freq.length===0 || wf.length===0){
            $(".dang").html("请填写完整信息");
            return false;
        }
        var stat = $(".stat").val();
        $(".dang").html("");
        var data = {ver:ver,cf:coll,wi:wi,f:freq,wf:wf,ebikeid:ebkid}
        if(stat === "true"){
            $(".stat").val("false");
            util.ajax_post("/sensorupdate.php",data,insertSuccess,insertFail);
            function insertSuccess(res){window.location.reload();}
            function insertFail(res){ alert("新增失败");}
         }   
    });
    /*-------------个别配置添加模态框点击添加------------------*/ 
    $(".modalresp").find(".insert").each(function(i){             
        $(this).on("click",function(){console.log(i)              
            $(".insert_conf"+(i+1)).show();                          
            //if(i>4){
            //    var value = $(this).prev().val();
            //}else{
                var value = $(this).prev().val().replace(/[^0-9]/ig,"");
            //}
            $(".insert_conf"+(i+1)).children("div:nth-child(2)").children("div:nth-child(1)").children("input").focus();
            $(".insert_conf"+(i+1)).children("div:nth-child(2)").children("div:nth-child(1)").children("input").keyup(function(){
                //if(i<5){   
                   this.value = this.value.replace(/[^\d]/g,"");
                //}  
            });
            $(".insert_conf"+(i+1)).children("div:nth-child(2)").children("div:nth-child(1)").children("input").val(value);
        });                                                        
    });                                                            
});    
