/*
*	@brief实时同步电动车
*/
var run = null;
//是不是要有动画
var animate = null;
//输入的序列号
var inputseqno = null;

$('.asyncCurrent').attr('flag','open');
$('.asyncCurrent').click(function(){

	//判断是否是通过电动车序列号进行搜索的
	if ($('input[name=inputData]').val()) {
		//输入的seqno
		inputseqno = $.trim($('input[name=inputData]').val());

	}

	if (GetRequest().inttype == undefined && !inputseqno) {
		alert('没有电动车不能进行实时同步！');
		return false;
	};

	//设置等级
	// map.setZoom(13);

	var flag = $(this).attr('flag');
	if (flag == 'open') {
		//不能进行缩放和双击放大
		map.disableDoubleClickZoom();
		map.disableScrollWheelZoom();

		$(this).attr('flag','close');
		$(this).text('关闭实时同步');
		asyncUpdatePoint();
		return false;
	} else if (flag == 'close') {

		//启用缩放和双击放大
		map.enableDoubleClickZoom();
		map.enableScrollWheelZoom();

		$(this).attr('flag','open');
		$(this).text('开启实时同步');
		clearInterval(run);
		return false;
	}

});
/**
 * @brief 获取当前url地址后的所有参数
 * @return [object] 参数对象
 */
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
}

//循环调用的异步函数
function asyncUpdatePoint()
{
	run = setInterval(function(){

		asyncEbike();
	},1000)
}

//判断是否有ID参数
function IsParamId(laborid,platformid,knightid,ebikeid,seqno,groupid){
	var isparamidarr = new Object();
	if (laborid) {
		isparamidarr['laborid'] = laborid;
	}

	if (platformid) {
		isparamidarr['platformid'] = platformid;
	}

	if (knightid) {
		isparamidarr['knightid'] = knightid;
	}

	if (ebikeid) {
		isparamidarr['ebikeid'] = ebikeid;
	}

	if (seqno) {
		isparamidarr['seqno'] = seqno;
	}

	if (groupid) {
		isparamidarr['groupid'] = groupid;
	}
	return isparamidarr;
}

/**
* 	@brief 通过不同的参数来判断实时同步电动车坐标
*
*   @param inttype
*/
function asyncEbike(){
	//清除图标
	map.clearOverlays();

	var paramlist = new Object();

	var inttype    = GetRequest().inttype;
	//通过搜索出来的点进行实时同步。
	if (inputseqno) {
		inttype = 'searcseqnosync';
	}

	if (inttype) {
		var laborid    = GetRequest().laborid;		//劳务方ID
		var platformid = GetRequest().platformid;   //平台ID
		var knightid   = GetRequest().knightid;   	//骑士ID
		var ebikeid    = GetRequest().ebikeid;		//电动车ID
		var seqno      = GetRequest().seqno;		//电动车序列号
		var groupid    = GetRequest().groupid;		//骑士分组ID

		//通过序列号进行搜索
		if (inputseqno) {
			seqno = inputseqno;
		}

		switch (inttype) {
			case 'plall':   //平台帐号->劳务方->劳务方管理->所属员工->查看定位
			paramlist['inttype'] = 'plall';
			paramlist['paramid'] = IsParamId(laborid,platformid,knightid,ebikeid,seqno);
			break;


            case 'lkseqno':  //劳务方帐号->骑士->骑士管理->查看定位
            paramlist['inttype'] = 'lkseqno';
            paramlist['paramid'] = IsParamId(laborid,platformid,knightid,ebikeid,seqno);
            break;


            case 'lcall':   //劳务方帐号/平台账号->车辆管理->所有车辆->查看定位
            paramlist['inttype'] = 'lcall';
            paramlist['paramid'] = IsParamId(laborid,platformid,knightid,ebikeid,seqno);
            break;


            case 'pcualarm':  //平台帐号->车辆管理->异常车辆->所有车辆->振动报警->查看定位
            paramlist['inttype'] = 'pcualarm';
            paramlist['paramid'] = IsParamId(laborid,platformid,knightid,ebikeid,seqno);
            break;
            case 'pcuelealarm'://平台帐号->车辆管理->异常车辆->所有车辆->电量报警->查看定位
            paramlist['inttype'] = 'pcuelealarm';
            paramlist['paramid'] = IsParamId(laborid,platformid,knightid,ebikeid,seqno);
            break;
            case 'pculost': //平台帐号->车辆管理->异常车辆->所有车辆->失去联系->查看定位
            paramlist['inttype'] = 'pculost';
            paramlist['paramid'] = IsParamId(laborid,platformid,knightid,ebikeid,seqno);
            break;


            case 'pculalarm': //平台帐号->车辆管理->异常车辆->劳务方->振动报警->查看定位
            paramlist['inttype'] = 'pculalarm';
            paramlist['paramid'] = IsParamId(laborid,platformid,knightid,ebikeid,seqno);
            break;
            case 'pculelealarm': //平台帐号->车辆管理->异常车辆->劳务方->电量报警->查看定位
            paramlist['inttype'] = 'pculelealarm';
            paramlist['paramid'] = IsParamId(laborid,platformid,knightid,ebikeid,seqno);
            break;
            case 'pcullost': //平台帐号->车辆管理->异常车辆->劳务方->失去联系->查看定位
            paramlist['inttype'] = 'pcullost';
            paramlist['paramid'] = IsParamId(laborid,platformid,knightid,ebikeid,seqno);
            break;


            case 'pipandectall'://平台帐号->首页->总览->所有车辆->查看定位
            paramlist['inttype'] = 'pipandectall';
            paramlist['paramid'] = IsParamId(laborid,platformid,knightid,ebikeid,seqno);
            break;
            case 'pipandectrun'://平台帐号->首页->总览->正在运行->查看定位
            paramlist['inttype'] = 'pipandectrun';
            paramlist['paramid'] = IsParamId(laborid,platformid,knightid,ebikeid,seqno);
            break;
            case 'pipandectunusual'://平台帐号->首页->总览->异常车辆->查看定位
            paramlist['inttype'] = 'pipandectunusual';
            paramlist['paramid'] = IsParamId(laborid,platformid,knightid,ebikeid,seqno);
            break;
            case 'pipandectrest'://平台帐号->首页->总览->正在休息->查看定位
            paramlist['inttype'] = 'pipandectrest';
            paramlist['paramid'] = IsParamId(laborid,platformid,knightid,ebikeid,seqno);
            break;


            case 'pilabortall'://平台帐号->首页->劳务方->所有车辆->查看定位
            paramlist['inttype'] = 'pilabortall';
            paramlist['paramid'] = IsParamId(laborid,platformid,knightid,ebikeid,seqno);
            break;
            case 'pilaborrun'://平台帐号->首页->劳务方->正在运行->查看定位
            paramlist['inttype'] = 'pilaborrun';
            paramlist['paramid'] = IsParamId(laborid,platformid,knightid,ebikeid,seqno);
            break;
            case 'pilaborunusual'://平台帐号->首页->劳务方->异常车辆->查看定位
            paramlist['inttype'] = 'pilaborunusual';
            paramlist['paramid'] = IsParamId(laborid,platformid,knightid,ebikeid,seqno);
            break;
            case 'pilaborrest'://平台帐号->首页->劳务方->正在休息->查看定位
            paramlist['inttype'] = 'pilaborrest';
            paramlist['paramid'] = IsParamId(laborid,platformid,knightid,ebikeid,seqno);
            break;


            case 'uipandectall'://员工帐号->首页->总览->所有车辆->查看定位
            paramlist['inttype'] = 'uipandectall';
            paramlist['paramid'] = IsParamId(laborid,platformid,knightid,ebikeid,seqno);
            break;
            case 'uipandectrun'://员工帐号->首页->总览->正在运行->查看定位
            paramlist['inttype'] = 'uipandectrun';
            paramlist['paramid'] = IsParamId(laborid,platformid,knightid,ebikeid,seqno);
            break;
            case 'uipandectunusual'://员工帐号->首页->总览->异常车辆->查看定位
            paramlist['inttype'] = 'uipandectunusual';
            paramlist['paramid'] = IsParamId(laborid,platformid,knightid,ebikeid,seqno);
            break;
            case 'uipandectrest'://员工帐号->首页->总览->正在休息->查看定位
            paramlist['inttype'] = 'uipandectrest';
            paramlist['paramid'] = IsParamId(laborid,platformid,knightid,ebikeid,seqno);
            break;


            case 'uiplatall'://员工帐号->首页->平台->所有车辆->查看定位
            paramlist['inttype'] = 'uiplatall';
            paramlist['paramid'] = IsParamId(laborid,platformid,knightid,ebikeid,seqno);
            break;
            case 'uiplatrun'://员工帐号->首页->平台->正在运行->查看定位
            paramlist['inttype'] = 'uiplatrun';
            paramlist['paramid'] = IsParamId(laborid,platformid,knightid,ebikeid,seqno);
            break;
            case 'uiplatunusual'://员工帐号->首页->平台->异常车辆->查看定位
            paramlist['inttype'] = 'uiplatunusual';
            paramlist['paramid'] = IsParamId(laborid,platformid,knightid,ebikeid,seqno);
            break;
            case 'uiplatrest'://员工帐号->首页->平台->正在休息->查看定位
            paramlist['inttype'] = 'uiplatrest';
            paramlist['paramid'] = IsParamId(laborid,platformid,knightid,ebikeid,seqno);
            break;


            case 'lipandectall'://劳务方帐号->首页->总览->所有车辆->查看定位
            paramlist['inttype'] = 'lipandectall';
            paramlist['paramid'] = IsParamId(laborid,platformid,knightid,ebikeid,seqno);
            break;
            case 'lipandectrun'://劳务方帐号->首页->总览->正在运行->查看定位
            paramlist['inttype'] = 'lipandectrun';
            paramlist['paramid'] = IsParamId(laborid,platformid,knightid,ebikeid,seqno);
            break;
            case 'lipandectunusual'://劳务方帐号->首页->总览->异常车辆->查看定位
            paramlist['inttype'] = 'lipandectunusual';
            paramlist['paramid'] = IsParamId(laborid,platformid,knightid,ebikeid,seqno);
            break;
            case 'lipandectrest'://劳务方帐号->首页->总览->正在休息->查看定位
            paramlist['inttype'] = 'lipandectrest';
            paramlist['paramid'] = IsParamId(laborid,platformid,knightid,ebikeid,seqno);
            break;


            case 'liplatall'://劳务方帐号->首页->平台->所有车辆->查看定位
            paramlist['inttype'] = 'liplatall';
            paramlist['paramid'] = IsParamId(laborid,platformid,knightid,ebikeid,seqno);
            break;
            case 'liplatrun'://劳务方帐号->首页->平台->正在运行->查看定位
            paramlist['inttype'] = 'liplatrun';
            paramlist['paramid'] = IsParamId(laborid,platformid,knightid,ebikeid,seqno);
            break;
            case 'liplatunusual'://劳务方帐号->首页->平台->异常车辆->查看定位
            paramlist['inttype'] = 'liplatunusual';
            paramlist['paramid'] = IsParamId(laborid,platformid,knightid,ebikeid,seqno);
            break;
            case 'liplatrest'://劳务方帐号->首页->平台->正在休息->查看定位
            paramlist['inttype'] = 'liplatrest';
            paramlist['paramid'] = IsParamId(laborid,platformid,knightid,ebikeid,seqno);
            break;


            case 'liflatall'://劳务方帐号->首页->分组总览->所有车辆->查看定位
            paramlist['inttype'] = 'liflatall';
            paramlist['paramid'] = IsParamId(laborid,platformid,knightid,ebikeid,seqno,groupid);
            break;
            case 'liflatrun'://劳务方帐号->首页->分组总览->正在运行->查看定位
            paramlist['inttype'] = 'liflatrun';
            paramlist['paramid'] = IsParamId(laborid,platformid,knightid,ebikeid,seqno,groupid);
            break;
            case 'liflatunusual'://劳务方帐号->首页->分组总览->异常车辆->查看定位
            paramlist['inttype'] = 'liflatunusual';
            paramlist['paramid'] = IsParamId(laborid,platformid,knightid,ebikeid,seqno,groupid);
            break;
            case 'liflatrest'://劳务方帐号->首页->分组总览->正在休息->查看定位
            paramlist['inttype'] = 'liflatrest';
            paramlist['paramid'] = IsParamId(laborid,platformid,knightid,ebikeid,seqno,groupid);
            break;


            case 'liftokntall'://劳务方帐号->首页->分组->所有车辆->查看定位
            paramlist['inttype'] = 'liftokntall';
            paramlist['paramid'] = IsParamId(laborid,platformid,knightid,ebikeid,seqno,groupid);
            break;
            case 'liftokntrun'://劳务方帐号->首页->分组->正在运行->查看定位
            paramlist['inttype'] = 'liftokntrun';
            paramlist['paramid'] = IsParamId(laborid,platformid,knightid,ebikeid,seqno,groupid);
            break;
            case 'liftokntunusual'://劳务方帐号->首页->分组->异常车辆->查看定位
            paramlist['inttype'] = 'liftokntunusual';
            paramlist['paramid'] = IsParamId(laborid,platformid,knightid,ebikeid,seqno,groupid);
            break;
            case 'liftokntrest'://劳务方帐号->首页->分组->正在休息->查看定位
            paramlist['inttype'] = 'liftokntrest';
            paramlist['paramid'] = IsParamId(laborid,platformid,knightid,ebikeid,seqno,groupid);
            break;


            case 'mymap'://骑士帐号->查看自己定位
            paramlist['inttype'] = 'mymap';
            paramlist['paramid'] = IsParamId(laborid,platformid,knightid,ebikeid,seqno);
            break;
            case 'kispeed'://骑士帐号->首页->查看周围骑士定位
            paramlist['inttype'] = 'kispeed';
            paramlist['paramid'] = IsParamId(laborid,platformid,knightid,ebikeid,seqno);
            break;


            case 'searcseqnosync'://通过序列号进行实时同步
            paramlist['inttype'] = 'searcseqnosync';
            paramlist['paramid'] = IsParamId(laborid,platformid,knightid,ebikeid,seqno);
            break;
        }

    }
	// console.log(paramlist);return false;
	var asyncebikesuccess = function(msg) {
		if (typeof(msg) == 'object') {
			//实时同步时去掉聚合时自动定位中心位置
			var setzoom = 'setzoom';
			//显示多个点的同步
			listebikebyseqno(msg,setzoom);
			return false;
		} else {
			//是否有动画
			animate = 'animate';
			//单个点的同步
			searchpointbyseqno(msg,animate);
		};

	};
	var asyncebikefail = function(){
		console.log('async ebike fail!');
	};
	util.ajax_post('mapasync.php',{paramlist:paramlist},asyncebikesuccess,asyncebikefail);
}