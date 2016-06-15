function controllers(){
	/**
	*	@brief 添加省市县
	*/
	map.enableInertialDragging();

	map.enableContinuousZoom();

	var size = new BMap.Size(30, 10);
	map.addControl(new BMap.CityListControl({
		anchor: BMAP_ANCHOR_TOP_RIGHT,
		offset: size,
	    // 切换城市之间事件
	    // onChangeBefore: function(){
	    //    alert('before');
	    // },
	    // 切换城市之后事件
	    onChangeAfter:function(){

	    	showAreaCar();
	    }
	}));

	//---- 调整省市县的样式 ----
	setTimeout(function(){
		console.log('okkk');
		$('.city_content_top').css('height','40px');
		$('.citylist_popup_main').css({'top':'-2px','left':'0px'});
	},2000);


	//---- 点击地图后失焦事件 ----
	map.addEventListener("click", mapAction);
	function mapAction(){
		$('.places').blur();
		$('.selectType').blur();
		$('.citylist_popup_main').css('display','none');
	}
}