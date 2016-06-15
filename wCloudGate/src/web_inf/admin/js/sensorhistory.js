$(function(){
    $(".use").on("click",function(){                                                      
        $(".modal").show();
        var ver = $(this).parent().prev().prev().prev().prev().prev().html();             
        var cf  = $(this).parent().prev().prev().prev().prev().html();                    
        var f   = $(this).parent().prev().prev().prev().html();                           
        var wi  = $(this).parent().prev().prev().html();                                  
        var wf  = $(this).parent().prev().html();
        var ebikeid = $(this).parent().prev().prev().prev().prev().prev().prev().html();  
        var data = ver+","+cf+","+f+","+wi+","+wf+","+ebikeid;                            
        $(".use-conf").val(data);                                                         
    });
    $(".sure-conf").on("click",function(){                  
        $(".modal").hide();                                 
        var data = {data:$(".use-conf").val()};                    
        util.ajax_post("/sensorupdate.php",data,useSuccess,useFail );
        function useSuccess (res){window.location.reload();}
        function useFail (res){alert("使用失败");}
    });                                                      
    $(".cancel").on("click",function(){
        $(".modal").hide(); 
    });
    $(".del").on("click",function(){
        $(".modalr").show();
        $(".del-id").val($(this).parent().attr("id"));
    });

    $(".sure-del").on("click",function(){                            
        $(".modalr").hide();
        var id =$(".del-id").val();
        var data = {id:id}; 
        util.ajax_post("historydel.php",data,delSuccess,delFail);
        function delSuccess (res){
            $(".del-his").each(function(){        
                if($(this).attr("id") === id)     
                {                                 
                    $(this).parent().remove();    
                }                                 
            });
            $(".resid").each(function(i){
                $(this).html(i+1);
            });
            $(".residd").each(function(i){
                $(this).html(i+1);
            });
        }
        function delFail (res){alert("删除失败");}
    });
    $(".cancel").on("click",function(){ 
        $(".modalr").hide(); 
    });
                        
});    
