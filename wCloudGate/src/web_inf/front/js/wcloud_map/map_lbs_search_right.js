/**
*	@brief 高德地图右侧城市搜索
*/
AMap.event.addDomListener(document.getElementsByClassName('citySearchBtn')[0], 'click', function() {
    var cityName = document.getElementsByClassName('cityName')[0].value;

    map.setCity(cityName);
});


/**
* 	当点击回车的时候进行搜索
*/
$('.cityName').on('keydown',function(e){
	var keyCode = e.keyCode;
	if(keyCode == 13){
	    var cityName = document.getElementsByClassName('cityName')[0].value;
	    if(!cityName){
	    	map.setCity();
	    	return false;
	    }
	    map.setCity(cityName);
	    map.setZoom(8);
		return false;
	}
})
