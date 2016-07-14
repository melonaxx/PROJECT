function searchpcc(stateid,cityid,countyid){
	var prosuccess = function(msg){
		$.each(msg,function(i,v){
			if(v.number==stateid){
				$('.pro').append("<option value='"+v.number+"' selected='selected'>"+v.name+"</option>");
			}else{
				$('.pro').append("<option value="+v.number+">"+v.name+"</option>");
			}
		});
	}

	util.ajax_post('/warehouse/searchpro.php',{data:'ok'},prosuccess);

	var proid = stateid;
	var citysuccess = function(msg){
		$.each(msg,function(i,v){
			if(v.number==cityid){
				$('.city').append("<option value='"+v.number+"' selected='selected'>"+v.name+"</option>");
			}else{
				$('.city').append("<option value="+v.number+">"+v.name+"</option>");
			}
		})
	}
	util.ajax_post('/warehouse/searchcity.php',{proid:proid},citysuccess);
	var cityid = cityid;
	var citysuccess = function(msg){
		$.each(msg,function(i,v){
			if(v.number==countyid){
				$('.county').append("<option value='"+v.number+"' selected='selected'>"+v.name+"</option>");
			}else{
				$('.county').append("<option value="+v.number+">"+v.name+"</option>");
			}
		})
	}
	util.ajax_post('/warehouse/searchcounty.php',{cityid:cityid},citysuccess);
	$('.pro').on('change',function(){
        //清除城市和区
        $('.city').empty();
        $('.city').append("<option value='-1'>--请选择--</option>");
        $('.county').empty();
        $('.county').append("<option value='-1'>--请选择--</option>");

        var proid = $('.pro option:selected').val();
        var citysuccess = function(msg){
        	$.each(msg,function(i,v){
        		$('.city').append("<option value="+v.number+">"+v.name+"</option>");
        	})
        }
        util.ajax_post('/warehouse/searchcity.php',{proid:proid},citysuccess);
    })
	searchcity();
}

function searchcity(){

	$('.city').on('change',function(){
        //清除城市和区
        $('.county').empty();
        $('.county').append("<option value='-1'>--请选择--</option>");

        searchcounty();
    })
}

function searchcounty(){
	var cityid = $('.city option:selected').val();
	var citysuccess = function(msg){
		$.each(msg,function(i,v){

			$('.county').append("<option value="+v.number+">"+v.name+"</option>");
		})
	}
	util.ajax_post('/warehouse/searchcounty.php',{cityid:cityid},citysuccess);
}