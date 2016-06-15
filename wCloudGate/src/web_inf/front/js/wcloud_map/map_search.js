/**
* @brief 地图中搜索电车位置与单位电车信息
*/
$('.searchIcon').on('click',function(){
	//清除所有的图标
	map.clearOverlays();

	//清除聚合
	if (typeof(bMarkerClusterer) != 'undefined') {
		bMarkerClusterer.clearMarkers();
	}

	var inputData 	= $('input[name=inputData]').val();
	inputData 		= $.trim(inputData);

	searchpointbyseqno(inputData);
});


/**
* 	当点击回车的时候进行搜索
*/
$('.places').on('keydown',function(e){
	var keyCode = e.keyCode;
	if(keyCode == 13){
		$('.searchIcon').click();
		return false;
	}
});


/**
*	@brief 地图托动过程中电动车数量变化
*/
// function showMapByDrag(){
// 	map.addEventListener("dragend", showAreaCar);
// }


/**
* 	@brief 地图进行缩放中电动车数量变化
*/
// function showMapByZoom(){
// 	map.addEventListener('zoomend', showAreaCar);
// }