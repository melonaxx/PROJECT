/**
	@brif 百度地图自定义图标
	@param  point 坐标点对象
	@param 	bool 是否需要动画
	@trtun	图标
*/

function addMarker(point,bool){
	var myIcon = new BMap.Icon("/image/electrocar.png", new BMap.Size(57, 32),{
		anchor: new BMap.Size(29, 33), //图像相对于自身左上角的位置
		imageOffset: new BMap.Size(0, 2) //图像相对于可视区的偏移量
	});
	marker = new BMap.Marker(point);
	map.addOverlay(marker);

	if(bool) {
		marker.setAnimation(BMAP_ANIMATION_DROP) //动画效果
	}

	//----鼠标的拖拽----
	marker.enableDragging(); //图标是否可以拖拽
	// marker.addEventListener("dragend", function(e){
	// 	alert("当前位置：" + e.point.lng + ", " + e.point.lat);
	// });

	//----信息窗口------
	// var opts = {
	// 	width : 200,     // 信息窗口宽度
	// 	height: 110,     // 信息窗口高度
	// 	title : "骑士信息"  // 信息窗口标题
	// }
	// var infoWindow = new BMap.InfoWindow('hellow', opts);  // 创建信息窗口对象
	// map.openInfoWindow(infoWindow, map.getCenter());      // 打开信息窗口


	//-----添加折线----
	// var polyline = new BMap.Polyline([
	//    	new BMap.Point(116.399, 39.910),
	//    	new BMap.Point(116.405, 39.920)
	// 	],
	// 	{strokeColor:"blue", strokeWeight:6, strokeOpacity:0.5}
	// );
	// map.addOverlay(polyline);
}