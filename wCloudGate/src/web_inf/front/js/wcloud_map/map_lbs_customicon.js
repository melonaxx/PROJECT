/**
* 	@brief 自定义添加地图图标
*/
function addMarker(point,animate){
	marker = new AMap.Marker({
       icon: 			"http://webapi.amap.com/theme/v1.3/markers/n/mark_b.png",
       position: 		point, //点标记在地图上显示的位置
       draggable: 		false,			//设置点标记是否可拖拽移动
       autoRotation: 	false,			//是否自动旋转
       title: 			'',				//鼠标滑过点标记时的文字提示
       // offset:      (-10,-34),    //点标记显示位置偏移量
    });

    if (animate) {
    	marker.setAnimation('AMAP_ANIMATION_DROP');
    }

    marker.setMap(map);
}