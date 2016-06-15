//----百度地图的函数 ----
/**
*	@brif 百度地图的基函数
*	@param  long 	地图的经度
*	@param 	lat		地图的纬度
*	@return
*/
function BDmap(long,lat){
	// var long = long || 116.404;
	// var lat  = lat || 39.915;
	map = new BMap.Map("bdMap");   	// 创建地图实例

	//通过ip进行定位。
	function myFun(result){
		var cityName = result.name;
		//是否是首次定位
		setTimeout(function() {
			if (map.getCenter().lat == 0) {
				map.setCenter(cityName);
			}
		}, 1000);
	}
	var myCity = new BMap.LocalCity();
	myCity.get(myFun);

	point = new BMap.Point(long,lat);  	// 创建点坐标
	map.centerAndZoom(point, 10);  		// 初始化地图，设置中心点坐标和地图级别

	//----放大缩小工具----
	var navigation = new BMap.NavigationControl({
		anchor: BMAP_ANCHOR_BOTTOM_RIGHT,
		type: BMAP_NAVIGATION_CONTROL_ZOOM,
	    enableGeolocation:true, // 启用显示定位
	    offset: new BMap.Size(30, 140)
	});
	map.addControl(navigation);

	//----缩略图 -----
 	// var overviewMapControl = new BMap.OverviewMapControl({isOpen:true});
 	// map.addControl(overviewMapControl);

 	//---- 比例尺 -----
 	var scaleControl = new BMap.ScaleControl();
 	map.addControl(scaleControl);

 	//---- 地图的类型 ----
 	var size = new BMap.Size(30,0);
 	var MapTypeControl = new BMap.MapTypeControl({
 		offset:size,
 		type:BMAP_MAPTYPE_CONTROL_MAP,
 		anchor:BMAP_ANCHOR_BOTTOM_RIGHT,
 	});
 	map.addControl(MapTypeControl);

 	//---- 版权 ----
 	var CopyrightControl = new BMap.CopyrightControl();
 	map.addControl(CopyrightControl);

 	//---定位----
 	// var GeolocationControl = new BMap.GeolocationControl();;
 	// map.addControl(GeolocationControl);

 	//----开启滚轮缩放 ----
 	map.enableScrollWheelZoom(true);
}


/**
* 	@brief 显示百度地图
*/
function showBDMap(){
	//----显示地图 ----
	BDmap();
    //----添加省市县控件 ----
    // controllers();
    //----拖拽地图时显示图标 ----
    // showMapByDrag();
}

/**
*	@brief 	地图中单个点的路径显示
*/
function showPath(pathSeqno){
	var pathSeqno = pathSeqno;
	if (pathSeqno) {
		var showpointpathsuccess = function(msg){
			//清除所有的图标
			map.clearOverlays();

			//清除聚合
			if (typeof(bMarkerClusterer) != 'undefined') {
				bMarkerClusterer.clearMarkers();
			}

			map.reset();

			if(msg['errno'] == 0){

				//路径数据数组
				var paths = new Array();

				var msg = msg['data']
				for (var i in msg) {
					var longitude = msg[i]['longitude'];
					var latitude = msg[i]['latitude'];
					pathPoint = new BMap.Point(longitude,latitude);  	// 创建点坐标

					//-----添加折线----
					paths.push(pathPoint);

				}

				var myIcon = new BMap.Icon("/image/map/goto.png", new BMap.Size(30,30),{
					anchor: new BMap.Size(15, 15), //图像相对于自身左上角的位置
					imageOffset: new BMap.Size(0, 0) //图像相对于可视区的偏移量
				});

				var polyline = new BMap.Polyline(
					paths,
					{strokeColor:"blue", strokeWeight:6, strokeOpacity:0.5}
					);
				map.addOverlay(polyline);
				map.setCenter(paths[0]);
				map.setZoom(12);

				var lushu = new BMapLib.LuShu(map, paths, {
				  	landmarkPois:[],//显示的特殊点，似乎是必选参数，可以留空，据说要和距原线路10米内才会暂停，这里就用原线的点
 					speed: 8000,//路书速度
					icon: myIcon,//覆盖物图标，默认是百度的红色地点标注
					autoView: true,//自动调整路线视野
					enableRotation: true,//覆盖物随路线走向
				});
				lushu.start();//启动路书函数
			}
		}
		var showpointpathfail = function(){
			console.log('show point path fail!');
		}
		util.ajax_post('showpointpath.php',{pathPoint:pathSeqno},showpointpathsuccess,showpointpathfail);
	}
};

/**
*	@brief 		实时同步电动车的坐标
*	@return 	实时同步的电动车的数量。
*/
function asyncCar(){

	var searchscopsuccess = function(msg){

		//清除所有图标
		map.clearOverlays();

		//清除聚合
		if (typeof(bMarkerClusterer) != 'undefined') {
			bMarkerClusterer.clearMarkers();
		}

		if (msg['errno'] == 0) {

			//聚合数据数组
			var markersCluster = new Array();

			//信息框信息数据数组
			var data_info = new Array();

			var msg = msg['data'];
			for (var i in msg) {
				var latitude 		= msg[i]['latitude'];
				var longitude 		= msg[i]['longitude'];
					var seqno 			= msg[i]['seqno']; //序列号
					var batpercent		= msg[i]['batpercent']; //电量
					var mobileno		= msg[i]['mobileno']; //手机号
					var name			= msg[i]['name']; //名字
					// var point = new BMap.Point(longitude,latitude);  	// 创建点坐标
				// addMarker(point,true);

				//marker标记
				var pt = new BMap.Point(longitude,latitude);
				marker = new BMap.Marker(pt);

				//添加聚合数据
				markersCluster.push(marker);
				pt = null;

				//添加信息框数据
				var contentss0 = "<table><tr><td style='width:70px;text-align:right;'>车主姓名：</td><td style='width:170px;'>";
				var contentss1 =name+"</td></tr><tr><td style='text-align:right;'>序列号：</td><td class='pathSeqno'>";
				var contentss2 =seqno+"</td></tr><tr><td style='text-align:right;'>手机：</td><td>";
				var contentss3 =mobileno+"</td></tr><tr><td style='text-align:right;'>电量：</td><td>";
				var contentss4 =batpercent+" %</td></tr><tr><td colspan='2'>";
				var contentss5 ="<div class='pathButton' style='cursor: pointer;font-size: 12px;border-radius: 4px;background: #f5f5f5;border: 1px solid #ddd;padding: 3px;float: left;margin-top: 5px;'>一小时内路径</div>";
				var contentss6 ="</td></tr></table>"

				var content = contentss0+contentss1+contentss2+contentss3+contentss4+contentss5+contentss6;
				addClickHandler(content,marker);
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
								showPath(pathSeqno);
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
			//生成一个marker数组，然后调用markerClusterer类即可。
			var psize 		= new BMap.Size(40,20); //图片的大小。
			var poffset 	= new BMap.Size(0,1);	//图片相对于可视区域的偏移值
			bMarkerClusterer = new BMapLib.MarkerClusterer(map, {
				markers:markersCluster, 			//要聚合的标记数组
				girdSize:15,						//聚合计算时网格的像素大小，默认60
				maxZoom:19,							//最大的聚合级别
				isAverangeCenter:false,				//聚合点的落脚位置是否是所有聚合在内点的平均值
				styles:[{'url':'/image/map/Rectangle.svg',size:psize,offset:poffset,textSize:10,textColor:'white'}]
			});
		}
	}
	util.ajax_post('searchcartoscope.php',searchscopdata,searchscopsuccess);
}


/**
*	@brief 		通过电动车序列号显示单个电动车信息
* 	@param 		[ebikepoint]	序列号
* 	@param 		[animate]	是否有动画
*	@return 	显示单个电动车的坐标。
*/
function searchpointbyseqno(ebikepoint,animate){
	//清除所有的图标
	map.clearOverlays();
	//清除聚合
	if (typeof(bMarkerClusterer) != 'undefined') {
		bMarkerClusterer.clearMarkers();
	}

	var getpbyseqnosuccess = function(msg) {
		if(msg['errno'] == 0){

			//转化坐标数组
			var transonearr = [];

			var longitude 		= msg['data']['longitude'];
			var latitude  		= msg['data']['latitude'];
			var batpercent 		= msg['data']['batpercent'];
			var seqno 			= msg['data']['seqno'];
			var mobileno 		= msg['data']['mobileno'];
			var name 			= msg['data']['name'];
			var seqPoint 		= new BMap.Point(longitude,latitude);  	// 创建点坐标

			if (!name) {
				name = '';
			}

			if (!mobileno) {
				mobileno = '';
			}

			if (!seqno) {
				seqno = '';
			}

			if (!batpercent) {
				batpercent = '0';
			}
			transonearr.push(seqPoint);
			var convertor = new BMap.Convertor();
			convertor.translate(transonearr, 1, 5, translateOneCallback);

			 //坐标转换完之后的回调函数
			 function translateOneCallback(data){

			 	if(data.status === 0) {

			 		var longitude 	= data.points[0].lng;
			 		var latitude  	= data.points[0].lat;
			 		var transPoint 		= new BMap.Point(longitude,latitude);

            		var marker = new BMap.Marker(transPoint);  // 创建标注
					map.addOverlay(marker);              // 将标注添加到地图中
					map.centerAndZoom(transPoint, 15);
					if (!animate) {
						marker.setAnimation(BMAP_ANIMATION_DROP); //跳动的动画
					}
					// marker.enableDragging(); //是否是可拖拽的

					var opts = {
					  width : 240,     // 信息窗口宽度
					  height: 135,     // 信息窗口高度
					}


					var contentss0 = "<table class='windowtable' style='border:0px;'><tr style='height:25px;border-bottom:0px;'><td style='width:65px;text-align:right;'>车主姓名：</td><td style='width:170px;text-align:left;'>";
					var contentss1 =name+"</td></tr><tr style='height:25px;border-bottom:0px;'><td style='text-align:right;'>序列号：</td><td class='pathSeqno' style='text-align:left;'>";
					var contentss2 =seqno+"</td></tr><tr style='height:25px;border-bottom:0px;'><td style='text-align:right;'>手机：</td><td style='text-align:left;'>";
					var contentss3 =mobileno+"</td></tr><tr style='height:25px;border-bottom:0px;'><td style='text-align:right;'>电量：</td><td style='text-align:left;'>";
					var contentss4 =batpercent+" %</td></tr><tr style='height:25px;border-bottom:0px;'><td colspan='2'>";
					var contentss5 ="<div class='pathButton' style='cursor: pointer;font-size: 12px;border-radius: 4px;background: #f5f5f5;border: 1px solid #ddd;padding: 3px;float: left;margin-top: 5px;'>一小时内路径</div>";
					var contentss6 ="</td></tr></table>";

					var contentss = contentss0+contentss1+contentss2+contentss3+contentss4+contentss5+contentss6;
					var infoWindow = new BMap.InfoWindow(contentss, opts);  // 创建信息窗口对象
					marker.addEventListener("click", function(){
					map.openInfoWindow(infoWindow,transPoint); //开启信息窗口
					$('.pathButton').off('click').on('click',function(){
						pathSeqno = $.trim($('.pathSeqno').text());
						if (pathSeqno) {
							showPath(pathSeqno);
						}
					})
				});
				}
			}

		}else {
			function GetRequest() {
				var url = location.search; //获取url中"?"符后的字串
				var theRequest = new Object();
				if (url.indexOf("?") != -1) {
					var str = url.substr(1);
					strs = str.split("&");
					for(var i = 0; i < strs.length; i ++) {
						theRequest[strs[i].split("=")[0]]=decodeURIComponent(strs[i].split("=")[1]);
					}
				}
				return theRequest;
			};
			var usertype = GetRequest().usertype;
			alert('该电动车不存在！');
			location.replace('map.php');
		}
	}
	var getpbyseqnofail = function(){
		console.log('get point by seqno fail!');
	}
	util.ajax_post('getpointbyseqno.php',{inputData:ebikepoint},getpbyseqnosuccess,getpbyseqnofail);
}

/**
*	@brief 		通过电动车序列号显示多个电动车信息
* 	@param 		[seqnoarr]	序列号数组
* 	@param 		[setzoom]	是否自动设置中心位置
*	@return 	显示单个电动车的坐标。
*/
function listebikebyseqno(seqnoarr,setzoom) {
	msglen = 0;
	//清除所有的图标
	map.clearOverlays();

	var listebikesuccess = function(msg){
		//为找中心点数组
		viewport = [];

		//聚合数据数组
		var markersCluster = new Array();
		$.each(msg,function(i,v){
			var longitude 	= v['longitude'];
			var latitude  	= v['latitude'];

			if (v['name']) {
				var name  	= v['name'];
			} else {
				var name  	= '';
			}

			if (v['seqno']) {
				var seqno  	= v['seqno'];
			} else {
				var seqno  	= '';
			}

			if (v['mobileno']) {
				var mobileno = v['mobileno'];
			} else {
				var mobileno = '';
			}

			if (v['batpercent']) {
				var batpercent = v['batpercent'];
			} else {
				var batpercent = '0';
			}

      		//转化数组
      		var transearr = [];

      		if (longitude !=null  && latitude !=null) {
            	var seqPoint 		= new BMap.Point(longitude,latitude);  	// 创建点坐标
            	transearr.push(seqPoint);

            	var convertor = new BMap.Convertor();
            	convertor.translate(transearr, 1, 5, translateCallback);

            }
            //更新点的长度
            msglen+=transearr.length;
            //坐标转换完之后的回调函数
            function translateCallback(data){

            	if(data.status === 0) {

            		var longitude 	= data.points[0].lng;
            		var latitude  	= data.points[0].lat;
            		var transPoint 		= new BMap.Point(longitude,latitude);  	// 创建点坐标

					//存放所有点
					viewport.push(transPoint);

					addebikeMarker(transPoint);
					function addebikeMarker(point){
						ebikemarker = new BMap.Marker(point);
	                    ebikemarker.setAnimation(BMAP_ANIMATION_DROP); //跳动的动画
	                    // ebikemarker.enableDragging(); //是否是可拖拽的
	                }

	                //添加聚合数据
	                markersCluster.push(ebikemarker);

	                var opts = {
	                    width : 240,     // 信息窗口宽度
	                    height: 130,     // 信息窗口高度
	                }

	                var contentss0 = "<table class='windowtable' style='border:0px;'><tr style='height:25px;border-bottom:0px;'><td style='width:65px;text-align:right;'>车主姓名：</td><td style='width:170px;text-align:left;'>";
	                var contentss1 =name+"</td></tr><tr style='height:25px;border-bottom:0px;'><td style='text-align:right;'>序列号：</td><td class='pathSeqno' style='text-align:left;'>";
	                var contentss2 =seqno+"</td></tr><tr style='height:25px;border-bottom:0px;'><td style='text-align:right;'>手机：</td><td style='text-align:left;'>";
	                var contentss3 =mobileno+"</td></tr><tr style='height:25px;border-bottom:0px;'><td style='text-align:right;'>电量：</td><td style='text-align:left;'>";
	                var contentss4 =batpercent+" %</td></tr><tr style='height:25px;border-bottom:0px;'><td colspan='2'>";
	                var contentss5 ="<div class='pathButton' style='cursor: pointer;font-size: 12px;border-radius: 4px;background: #f5f5f5;border: 1px solid #ddd;padding: 3px;float: left;margin-top: 5px;'>一小时内路径</div>";
	                var contentss6 ="</td></tr></table>";

	                var contentss = contentss0+contentss1+contentss2+contentss3+contentss4+contentss5+contentss6;
	                var infoWindow = new BMap.InfoWindow(contentss, opts);  // 创建信息窗口对象
	                ebikemarker.addEventListener("click", function(){
	                    map.openInfoWindow(infoWindow,transPoint); //开启信息窗口
	                    $('.pathButton').off('click').on('click',function(){
	                    	pathSeqno = $.trim($('.pathSeqno').text());
	                    	if (pathSeqno) {
	                    		showPath(pathSeqno);
	                    		map.reset();
	                    	}
	                    })
	                });
	            }

		      	//清除聚合
		      	if (typeof(bMarkerClusterer) != 'undefined') {
		      		bMarkerClusterer.clearMarkers();
		      	}
		      	if (markersCluster.length == msglen) {
	            	//---- 电车图标的聚合 ----
			        //生成一个marker数组，然后调用markerClusterer类即可。
			        var psize 		= new BMap.Size(30,23); //图片的大小。
			        var poffset 	= new BMap.Size(0,1);	//图片相对于可视区域的偏移值
			        bMarkerClusterer = new BMapLib.MarkerClusterer(map, {
			            markers:markersCluster, 			//要聚合的标记数组
			            girdSize:6,						//聚合计算时网格的像素大小，默认60
			            maxZoom:13,							//最大的聚合级别
			            maxZoom:8,							//最大的聚合级别
			            isAverangeCenter:false,				//聚合点的落脚位置是否是所有聚合在内点的平均值
			            styles:[{'url':'/image/map/Rectangle.svg',size:psize,offset:poffset,textSize:10,textColor:'white'}]
			        });
			        if (!setzoom) {
			        	//获取视图窗口数据
			        	var portview = map.getViewport(viewport);
				        //中心点坐标
				        var viewpoint = new BMap.Point(portview.center.lng,portview.center.lat);
				        map.centerAndZoom(viewpoint, portview.zoom);
				    }
				    markersCluster.length = 0;
				}
			}
		});
	};
	var listebikefail = function(){
		console.log('list ebike fail!');
	};
	util.ajax_post('listpointbyseqno.php',{seqnoarr:seqnoarr},listebikesuccess,listebikefail);
}