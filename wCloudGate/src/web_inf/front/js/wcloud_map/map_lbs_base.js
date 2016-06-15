/**
*	@brief 高德地图基础函数
*/
function Lbs_map(){
	  map = new AMap.Map('bdMap',{resizeEnable: true}); 	// 创建地图实例
	  map.setZoom(10);				//缩放级别
    map.setCenter([116.39,39.9]);	//中心位置


    //----添加地图工具条----
   	AMap.plugin(['AMap.ToolBar'],function(){

      	var toolBar = new AMap.ToolBar({
      		ruler: 		false,
      		direction: 	false,
      		offset: 	new AMap.Pixel(15,50)
      	});
      	map.addControl(toolBar);
    })


    //---- 添加地图比例尺 ----
    AMap.plugin(['AMap.Scale'],function(){

      	var scale = new AMap.Scale();
        map.addControl(scale);
    })


    //---- 添加地图类型 ----
    AMap.plugin(['AMap.MapType'],function(){
      	var type= new AMap.MapType({
  	    defaultType:0 		//使用2D地图
  	    });
  	    map.addControl(type);
  	    type.show();		//显示图标;
    });

    setTimeout(function(){

      	//---比例尺颜色改变 ----
      	$('.amap-scale-edgeleft').css('border','1px solid #333333');
  	    $('.amap-scale-edgeright').css('border','1px solid #333333');
  	    $('.amap-scale-middle').css('border','1px solid #333333');

  	    //----地图操作工具条 ----
  	    $('.amap-toolbar').removeAttr('style');
  	    $('.amap-toolbar').css({'right':'20px','bottom':'130px','visibility':'visible'});

    		//----地图类型----
    		$('.amap-scalecontrol').next().removeClass('amap-maptypecontrol');
    		$('.amap-scalecontrol').next().addClass('new-maptypecontrol');
    		$('.new-maptypecontrol').css({'position':'absolute','width':'65px','height':'90px','bottom':'30px','right':'30px'});

    },50);


}


/**
*	@brief 高德地图的显示
*/
function showLbsMap(){
	//调用基函数
	Lbs_map();
}