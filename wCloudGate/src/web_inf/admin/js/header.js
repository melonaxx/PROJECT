$(function(){
    var stat = 0;                            
    $(".users").on("click",function(){       
        stat++;                              
        if(stat%2 === 0){                    
            $(".users_ul").slideDown();      
            $(".icon").html("▼");           
        }else{                               
            $(".users_ul").slideUp();        
            $(".icon").html("◀");           
        }                                    
    });                                      

    var s = 0;                              
    $(".car").on("click",function(){      
        s++;                                
        if(s%2 === 0){                      
            $(".car_ul").slideDown();     
            $(".icon1").html("▼");          
        }else{                               
            $(".car_ul").slideUp();       
            $(".icon1").html("◀");          
        }                                    
    }); 
    var st = 0;                              
    $(".sensor").on("click",function(){      
        st++;                                
        if(st%2 === 0){                      
            $(".sensor_ul").slideDown();     
            $(".icon2").html("▼");          
        }else{                               
            $(".sensor_ul").slideUp();       
            $(".icon2").html("◀");          
        }                                    
    }); 

    var num = 0;                                    
    $(".phone-card").on("click",function(){         
        num++;                                      
        if(num%2 === 0){                            
            $(".card-ul").slideDown();              
            $(".icon3").html("▼");                 
        }else{                                      
            $(".card-ul").slideUp();                
            $(".icon3").html("◀");                 
        }                                           
    });                                             
    var num1 = 0;                                   
    $(".web-manage").on("click",function(){         
        num1++;                                     
        if(num1%2 === 0){                           
            $(".web-ul").slideDown();               
            $(".icon4").html("▼");                 
        }else{                                      
            $(".web-ul").slideUp();                 
            $(".icon4").html("◀");                 
        }                                           
    });

    var num2 = 0;                                   
    $(".son-manage").on("click",function(){         
        num2++;                                     
        if(num2%2 === 0){                           
            $(".son-ul").slideDown();               
            $(".icon5").html("▼");                 
        }else{                                      
            $(".son-ul").slideUp();                 
            $(".icon5").html("◀");                 
        }                                           
    });
    
    $(".liall").click(function(){
        var href = $(this).children().attr('href');
        if(href.length != 0){
            window.location = href;
        }
    })
    var name =  window.location.pathname;
    $(".liall").each(function(){
        if($(this).children().attr('href')==name){
            $(this).addClass("activeli");
        }else{
            $(this).removeClass("activeli");
        }
    }) 
    if(name === '/carlabor.php'){
        $(".liall:eq(4)").addClass("activeli");
    }
    if(name === '/allotcar.php'){
        $(".liall:eq(4)").addClass("activeli");
    }
    if(name === '/relievecar.php')
    {
        $(".liall:eq(4)").addClass("activeli");
    }
})    
