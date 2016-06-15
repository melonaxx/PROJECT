$(function(){
    $(document).ready(function(e) {                               
        $(".nowpage").keydown(function (e){                       
            if(e.which == "13"){                                  
                var num  = $(this).val();                         
                var url = window.location.href;                   
                var numm = parseInt(url.replace(/[^\d]/g,""));    
                if(numm>0){                                       
                    window.location = url+"&num="+num;            
                }else{                                            
                    window.location = url+"?num="+num;            
                }                                                 
            }                                                     
        })                                                        
    });  

    $(".nowpage").keyup(function (){                                             
        this.value = this.value.replace(/[^\d]/g,"");                            
        var now = $(this).val();                                                 
        var num = $(".pageallnum").html();                                       
        if(parseInt(now)>parseInt(num)){                                         
            $(this).val(num);                                                    
        }                                                                        
        if(parseInt(now) === 0){                                                 
            $(this).val(1);                                                      
        }                                                                        
    });                                                                          

    $("select[name=pagenum]").on("change", function() {                          
        var data = {page:$("select[name=pagenum] option:selected").html()};       
        util.ajax_post("/setpage.php",data,setSuccess,setFail);                   
    });                                                                          
    function setSuccess(data){window.location.reload();}                         
    function setFail(data){window.location.reload();}
    $(".receive").click(function(){
        var platformid = $(this).attr("attr");
        window.location.href = "/platform/carmanagement.php?labour="+platformid+"&belong=2";
    });
    $(".owner").click(function(){
        var platformid = $(this).attr("attr");
        window.location.href = "/platform/carmanagement.php?labour="+platformid+"&belong=1";
    });
});
