/**
	@brief 显示多个电车的图标
*/
$('.showCarIcon').click(function(){
	$.ajax({
		url:'showcaricon.php',
		data:{flag:'ok'},
		dataType:'json',
		type:'post',
		success:function(msg){

			//电车的图标
			var myIcon = new BMap.Icon("/image/electrocar.png", new BMap.Size(57, 32),{
				anchor: new BMap.Size(29, 33), //图像相对于自身左上角的位置
				imageOffset: new BMap.Size(0, 2) //图像相对于可视区的偏移量
			});

			//聚合数据数组
			var markersCluster = new Array();

			//信息框信息数据数组
			var data_info = new Array();

			var msg = msg['data'];
			for(var i in msg){
				var latitude = msg[i]['latitude'];
				var longitude = msg[i]['longitude'];
				var point = new BMap.Point(longitude,latitude);  	// 创建点坐标
				addMarker(point);
				marker.setAnimation(BMAP_ANIMATION_DROP); //跳动的动画

				//marker标记
				var pt = new BMap.Point(longitude,latitude);
				marker = new BMap.Marker(pt,{icon:myIcon});

				//添加聚合数据
				markersCluster.push(marker);
				pt = null;

				//添加信息框数据
				var content = 'this is '+i+'`s message!';
				addClickHandler(content,marker);
			}

			//----鼠标点击后显示信息窗口----

			var opts = {
				width : 250,     		// 信息窗口宽度
				height: 80,     		// 信息窗口高度
				title : "信息窗口" , 	// 信息窗口标题
				enableMessage:true		//设置允许信息窗发送短息
		   	};

			function addClickHandler(content,marker){
				marker.addEventListener("click",function(e){
					openInfo(content,e)}
				);
			}

			function openInfo(content,e){
				var p = e.target;
				var point = new BMap.Point(p.getPosition().lng, p.getPosition().lat);
				var infoWindow = new BMap.InfoWindow(content,opts);  // 创建信息窗口对象
				map.openInfoWindow(infoWindow,point); //开启信息窗口
			}


			//---- 电车图标的聚合 ----
			//生成一个marker数组，然后调用markerClusterer类即可。
			// var markerClusterer = new BMapLib.MarkerClusterer(map, {markers:markersCluster,girdSize:'5',maxZoom:'19',isAverangeCenter:true});

		}
	})
})


/**
	@brief 	5秒进行数据的实时更新
*/
var run = '';
$('.updateData').click(function(){

	updatePoint();

	//---- 缩放地图时清除图标 ----
	map.addEventListener("zoomend", function () {

		// if(!Clusterer)
		// {
		// 	clearInterval(run);
			Clusterer.clearMarkers();
		// 	Clusterer = new BMapLib.MarkerClusterer(map, {markers:markersCluster,girdSize:'5',maxZoom:'19',isAverangeCenter:true});
		// 	updatePoint();
		// }
	});


	//---- 鼠标拖动地图时清除图标 ----
	map.addEventListener("moveend", function () {

		// if(!Clusterer)
		// {
		// 	clearInterval(run);
			Clusterer.clearMarkers();
		// 	Clusterer = new BMapLib.MarkerClusterer(map, {markers:markersCluster,girdSize:'5',maxZoom:'19',isAverangeCenter:true});
		// 	updatePoint();
		// }

	});
})



function updatePoint()
{

	run = setInterval(function(){
		markersCluster = null;
		$.ajax({
			url:'updatedata.php',
			data:{flag:'ok'},
			dataType:'json',
			Type:'post',
			success:function(msg){

				//电车的图标
				var myIcon = new BMap.Icon("/image/electrocar.png", new BMap.Size(57, 32),{
					anchor: new BMap.Size(29, 33), //图像相对于自身左上角的位置
					imageOffset: new BMap.Size(0, 2) //图像相对于可视区的偏移量
				});

				//聚合数据数组
				markersCluster = new Array();

				//信息框信息数据数组
				var data_info = new Array();

				var msg = msg['data'];
				for(var i in msg){
					var longitude = msg[i]['longitude'];
					var latitude = msg[i]['latitude'];

					//marker标记
					var pt = new BMap.Point(longitude,latitude);
					marker = new BMap.Marker(pt,{icon:myIcon});


					//添加聚合数据
					markersCluster.push(marker);
					pt = null;

					//添加信息框数据
					var content = 'this is '+i+'`s message!';
					addClickHandler(content,marker);
				}

				//----鼠标点击后显示信息窗口----

				var opts = {
					width : 250,     		// 信息窗口宽度
					height: 80,     		// 信息窗口高度
					title : "信息窗口" , 	// 信息窗口标题
					enableMessage:true		//设置允许信息窗发送短息
			   	};

				function addClickHandler(content,marker){
					marker.addEventListener("click",function(e){
						openInfo(content,e)}
					);
				}

				function openInfo(content,e){
					var p = e.target;
					var point = new BMap.Point(p.getPosition().lng, p.getPosition().lat);
					var infoWindow = new BMap.InfoWindow(content,opts);  // 创建信息窗口对象
					map.openInfoWindow(infoWindow,point); //开启信息窗口
				}


				//---- 电车图标的聚合 ----
				//生成一个marker数组，然后调用markerClusterer类即可。
				Clusterer = new BMapLib.MarkerClusterer(map, {markers:markersCluster,girdSize:'5',maxZoom:'19',isAverangeCenter:true});

			}
		});
	},5000)
}


//=========================================================================

/**
	@brief 	搜索框的搜索
*/
$('.unit').click(function(){
	var unit = $(this).text();
	$('.searchFlag').text(unit);
	$('.searchFlag').attr('flag','unit');
	$('.places')[0]['value'] = '';
	$('.places').attr('placeholder','请输入单位名称')
})

$('.single').click(function(){
	var single = $(this).text();
	$('.searchFlag').text(single);
	$('.searchFlag').attr('flag','single');
	$('.places')[0]['value'] = '';
	$('.places').attr('placeholder','请输入电车序列号')
})

/**
	@brief 地图中搜索电车位置与单位电车信息
*/
$('.searchButton').on('click',function(){

	//清除所有的图标
	map.clearOverlays();

	var searchFlag 	= $('.searchFlag').attr('flag');
	var searchType 	= $('.searchType li a[name='+searchFlag+']').attr('name');
	var inputData 	= $('input[name=inputData]').val();
	inputData 		= $.trim(inputData);
	if (searchType == 'unit') {
		$.ajax({
			url:'getallpointbyunitname.php',
			data:{'searchType':searchType,'inputData':inputData},
			dataType:'json',
			Type:'post',
			success:function(msg){
				// console.log(msg['data'][0][0]['longitude']);
				// return false;

				//清除所有的图标
				map.clearOverlays();

				if(msg['errno'] == 0){

					//聚合数据数组
					var markersCluster = new Array();

					var msg = msg['data'];
					for (var i in msg) {
						var longitude 		= msg[i]['longitude'];
						var latitude  		= msg[i]['latitude'];
						var batpercent 		= msg[i]['batpercent'];
						var seqno 			= msg[i]['seqno'];
						var mobileno 		= msg[i]['mobileno'];
						var name 			= msg[i]['name'];

						// //marker标记
						var pt = new BMap.Point(longitude,latitude);
						marker = new BMap.Marker(pt);

						// //添加聚合数据
						markersCluster.push(marker);
						pt = null;


						//添加信息框数据
						var contentss0 = "<table><tr><td style='width:70px;text-align:right;'>车主姓名：</td><td style='width:170px;'>";
						var contentss1 =name+"</td></tr><tr><td style='text-align:right;'>序列号：</td><td class='pathSeqno'>";
						var contentss2 =seqno+"</td></tr><tr><td style='text-align:right;'>手机：</td><td>";
						var contentss3 =mobileno+"</td></tr><tr><td style='text-align:right;'>电量：</td><td>";
						var contentss4 =batpercent+" %</td></tr><tr><td colspan='2'>";
						var contentss5 ="<div class='pathButton' style='cursor: pointer;font-size: 12px;border-radius: 4px;background: #f5f5f5;border: 1px solid #ddd;padding: 3px;float: left;margin-top: 5px;'>十五钟内路径</div>";
						var contentss6 ="</td></tr></table>"

						var content = contentss0+contentss1+contentss2+contentss3+contentss4+contentss5+contentss6;
						addClickHandler(content,marker);
						// map.setZoom(10);
						// addMarker(pt,true);
					}


					//----鼠标点击后显示信息窗口----

					var opts = {
						width : 240,     		// 信息窗口宽度
						height: 100,     		// 信息窗口高度
				   	};

					function addClickHandler(content,marker){
						marker.addEventListener("click",function(e){
							openInfo(content,e);
							// 添加显示路径的点击事件
							setTimeout(function(){
								$('.pathButton').on('click',function(){
									var pathSeqno = $('.pathSeqno').text();
									if (pathSeqno) {
										$.ajax({
											url:'showpointpath.php',
											data:{pathPoint:pathSeqno},
											dataType:'json',
											Type:'post',
											success:function(msg){

												//清除所有的图标
												map.clearOverlays();

												if(msg['errno'] == 0){
													//路径数据数组
													var paths = new Array();

													var msg = msg['data']
													for (var i in msg) {
														var longitude = msg[i]['longitude'];
														var latitude = msg[i]['latitude'];
														var pathPoint = new BMap.Point(longitude,latitude);  	// 创建点坐标

														//-----添加折线----
														paths.push(pathPoint);

													}
													console.log(paths);
														var polyline = new BMap.Polyline(
															paths,
															{strokeColor:"blue", strokeWeight:6, strokeOpacity:0.5}
														);
														map.addOverlay(polyline);
														map.setCenter(paths[0]);
														map.setZoom(14);
												}
											}
										})
									}
								})
							},50);
						});
					}

					function openInfo(content,e){
						var p = e.target;
						var point = new BMap.Point(p.getPosition().lng, p.getPosition().lat);
						var infoWindow = new BMap.InfoWindow(content,opts);  // 创建信息窗口对象
						map.openInfoWindow(infoWindow,point); //开启信息窗口
					}


					//---- 电车图标的聚合 ----

					//聚合图标的样式
					// var position =
					//生成一个marker数组，然后调用markerClusterer类即可。
					var markerClusterer = new BMapLib.MarkerClusterer(map, {
						markers:markersCluster, 	//要聚合的标记数组
						girdSize:15,				//聚合计算时网格的像素大小，默认60
						maxZoom:19,					//最大的聚合级别
						isAverangeCenter:false		//聚合点的落脚位置是否是所有聚合在内点的平均值
						// styles:
					});


				}else {
					alert('没有该单位名称！请确认后重新输入！');
				}
			}
		});
		return false;
	} else if (searchType == 'single') {
		$.ajax({
			url:'getpointbyseqno.php',
			data:{'searchType':searchType,'inputData':inputData},
			dataType:'json',
			type:'post',
			success:function(msg){
				if(msg['errno'] == 0){

					//清除所有的图标
					map.clearOverlays();

					var longitude 		= msg['data']['longitude'];
					var latitude  		= msg['data']['latitude'];
					var batpercent 		= msg['data']['batpercent'];
					var seqno 			= msg['data']['seqno'];
					var mobileno 		= msg['data']['mobileno'];
					var name 			= msg['data']['name'];
					var seqPoint 		= new BMap.Point(longitude,latitude);  	// 创建点坐标

					var marker = new BMap.Marker(seqPoint);  // 创建标注
					map.addOverlay(marker);              // 将标注添加到地图中
					map.centerAndZoom(seqPoint, 15);
					marker.setAnimation(BMAP_ANIMATION_DROP); //跳动的动画

					var opts = {
					  width : 240,     // 信息窗口宽度
					  height: 100,     // 信息窗口高度
					}


					var contentss0 = "<table><tr><td style='width:70px;text-align:right;'>车主姓名：</td><td style='width:170px;'>";
					var contentss1 =name+"</td></tr><tr><td style='text-align:right;'>序列号：</td><td class='pathSeqno'>";
					var contentss2 =seqno+"</td></tr><tr><td style='text-align:right;'>手机：</td><td>";
					var contentss3 =mobileno+"</td></tr><tr><td style='text-align:right;'>电量：</td><td>";
					var contentss4 =batpercent+" %</td></tr><tr><td colspan='2'>";
					var contentss5 ="<div class='pathButton' style='cursor: pointer;font-size: 12px;border-radius: 4px;background: #f5f5f5;border: 1px solid #ddd;padding: 3px;float: left;margin-top: 5px;'>十五钟内路径</div>";
					var contentss6 ="</td></tr></table>"

					var contentss = contentss0+contentss1+contentss2+contentss3+contentss4+contentss5+contentss6;
					var infoWindow = new BMap.InfoWindow(contentss, opts);  // 创建信息窗口对象
					marker.addEventListener("click", function(){
					map.openInfoWindow(infoWindow,seqPoint); //开启信息窗口
						$('.pathButton').on('click',function(){
							var pathSeqno = $('.pathSeqno').text();
							if (pathSeqno) {
								$.ajax({
									url:'showpointpath.php',
									data:{pathPoint:pathSeqno},
									dataType:'json',
									Type:'post',
									success:function(msg){

										//清除所有的图标
										map.clearOverlays();

										if(msg['errno'] == 0){

											//路径数据数组
											var paths = new Array();

											var msg = msg['data']
											for (var i in msg) {
												var longitude = msg[i]['longitude'];
												var latitude = msg[i]['latitude'];
												var pathPoint = new BMap.Point(longitude,latitude);  	// 创建点坐标

												//-----添加折线----
												paths.push(pathPoint);

											}
												var polyline = new BMap.Polyline(
													paths,
													{strokeColor:"blue", strokeWeight:6, strokeOpacity:0.5}
												);
												map.addOverlay(polyline);
												map.setCenter(paths[0]);
												map.setZoom(14);
										}
									}
								})
							}
						})
					});

				}else {
					alert('没有该电动车序列号！请确认后重新输入！');
				}
			}
		});
		return false;
	}
});




/**
	@brief 	地图中单个点的路径显示
*/
function showPath(){

	if (pathPoint) {
		$.ajax({
			url:'showpointpath.php',
			data:{pathPoint:pathPoint},
			dataType:'json',
			Type:'post',
			success:function(msg){
				console.log(msg['data'][0]['latitude']);
				return false;

				//清除所有的图标
				map.clearOverlays();

				if(msg['errno'] == 0){

					//路径数据数组
					var paths = new Array();

					var msg = msg['data']
					for (var i in msg) {
						var longitude = msg[i]['longitude'];
						var latitude = msg[i]['latitude'];
						var pathPoint = new BMap.Point(longitude,latitude);  	// 创建点坐标

						//-----添加折线----
						paths.push(pathPoint);

					}
						var polyline = new BMap.Polyline(
							paths,
							{strokeColor:"blue", strokeWeight:6, strokeOpacity:0.5}
						);
						map.addOverlay(polyline);
						map.setCenter(paths[0]);
						map.setZoom(14);
				}
			}
		})
	}
};



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