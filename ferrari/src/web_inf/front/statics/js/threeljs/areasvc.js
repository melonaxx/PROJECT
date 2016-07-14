function searchpcc(proname,cityname,countyname,stateid,cityid,countyid){
    proname         = proname || 'pro';
    cityname        = cityname || 'city';
    countyname      = countyname || 'county';
    stateid         = stateid || -1;
    cityid          = cityid || -1;
    countyid        = countyid || -1;
    //初始化
    $('.'+proname).empty();
    $('.'+cityname).empty();
    $('.'+countyname).empty();
    $('.'+proname).append("<option value='-1'>--请选择--</option>");
    $('.'+cityname).append("<option value='-1'>--请选择--</option>");
    $('.'+countyname).append("<option value='-1'>--请选择--</option>");


    var prosuccess = function(msg){
        $.each(msg,function(i,v){
            if(v.number==stateid){
                $('.'+proname).append("<option value='"+v.number+"' selected='selected'>"+v.name+"</option>");
            }else{
                $('.'+proname).append("<option value="+v.number+">"+v.name+"</option>");
            }
        });

    }
    util.ajax_post('/warehouse/searchpro.php',{data:'ok'},prosuccess);
    var proid = stateid;
    var citysuccess = function(msg){
        $('.'+cityname).empty();
        $.each(msg,function(i,v){
            if(v.number==cityid){
                $('.'+cityname).append("<option value='"+v.number+"' selected='selected'>"+v.name+"</option>");
            }else{
                $('.'+cityname).append("<option value="+v.number+">"+v.name+"</option>");
            }
        })
    }
    util.ajax_post('/warehouse/searchcity.php',{proid:proid},citysuccess);
    var cityid = cityid;
    var citysuccess = function(msg){
        $('.'+countyname).empty();
        $.each(msg,function(i,v){
            if(v.number==countyid){
                $('.'+countyname).append("<option value='"+v.number+"' selected='selected'>"+v.name+"</option>");
            }else{
                $('.'+countyname).append("<option value="+v.number+">"+v.name+"</option>");
            }
        })
    }
    util.ajax_post('/warehouse/searchcounty.php',{cityid:cityid},citysuccess);
    $('.'+proname).off('change').on('change',function(){
        //清除城市和区
        $('.'+cityname).empty();
        $('.'+cityname).append("<option value='-1'>--请选择--</option>");
        $('.'+countyname).empty();
        $('.'+countyname).append("<option value='-1'>--请选择--</option>");

        var proid = $('.'+proname+' option:selected').val();

        var citysuccess = function(msg){
            $.each(msg,function(i,v){
                $('.'+cityname).append("<option value="+v.number+">"+v.name+"</option>");
            })
        }
        util.ajax_post('/warehouse/searchcity.php',{proid:proid},citysuccess);
    })

    //显示区
    $('.'+cityname).off('change').on('change',function(){
        //清除城市和区
        $('.'+countyname).empty();
        $('.'+countyname).append("<option value='-1'>--请选择--</option>");

        var cityid = $('.'+cityname+' option:selected').val();

        //显示县
        var citysuccess = function(msg){
            $.each(msg,function(i,v){

                $('.'+countyname).append("<option value="+v.number+">"+v.name+"</option>");
            })
        }
        util.ajax_post('/warehouse/searchcounty.php',{cityid:cityid},citysuccess);
    })
}