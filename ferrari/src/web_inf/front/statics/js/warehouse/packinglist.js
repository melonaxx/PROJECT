$(function(){
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

	/*获取参数*/
	function getParam(){
		var paramarr = new Object();
		//页大小
		if ($('.rrow option:selected').val() !='undefined')
		{
			var pagesize = $('.rrow option:selected').val();
		} else {
			var pagesize = '';
		}
		paramarr['pagesize'] = pagesize;

		//起始时间
		if ($('.startdate').val() != 'undefined' || $('.enddate').val())
		{
			var startdate = $('.startdate').val();
		} else {
			var startdate = '';
		}
		paramarr['startdate'] = startdate;

		//结束时间
		if ($('.enddate').val() != 'undefined' || $('.enddate').val())
		{
			var enddate = $('.enddate').val();
		} else {
			$enddate = '';
		}
		paramarr['enddate'] = enddate;

		//开始时间与结束时间的大小关系
		if (paramarr['startdate'] >= paramarr['enddate'])
		{
			$('.enddate').val(paramarr['startdate']);
		}
		//当前页数
		if (GetRequest().page)
		{
			var page = GetRequest().page;
		} else {
			var page = 1;
		}
		paramarr['page'] = page;

		return paramarr;
	}

	/*保持搜索分页状态*/
	function keepPageSearch(search)
	{
		var page 			= getParam().page;
		if (search)
		{
			page = 1;
		}
		var startdate 		= getParam().startdate;
		var enddate 		= getParam().enddate;
		var pagesize 		= getParam().pagesize;
		var url = location.href.substring(0,location.href.indexOf('?'));
		window.location.href=url+"?page="+page+"&startdate="+startdate+"&enddate="+enddate+"&pagesize="+pagesize;
	}

	/*改变页大小*/
	$('.rrow').change(function(){
		keepPageSearch();
	});

	/*进行时间的搜索查询*/
	$('.searchtime').click(function(){
		keepPageSearch();
	});

	/*保持时间的状态*/
	if (GetRequest().startdate)
	{
		$('.startdate').val(GetRequest().startdate);
	}
	if (GetRequest().enddate)
	{
		$('.enddate').val(GetRequest().enddate);
	}

	/*清空*/
	$('.reset').click(function(){
		var url = window.location.href.substring(0,window.location.href.indexOf('?'));
		window.location.href = url;
	});

	//点击详细弹出模态窗;
	$(".allocate-detail").click(function(){
		$(".modal-allocate").show();
		$(".allocatemodal-time").val($(this).parent().siblings(".allocate-time").html());
		$(".allocatemodal-man").val($(this).parent().siblings(".operate-man").html());
		$(".allocatemodal-ware1").val($(this).parent().siblings(".allocate-ware1").html());
		$(".allocatemodal-ware2").val($(this).parent().siblings(".allocate-ware2").html());
		$(".allocatemodal-movetype").val($(this).parent().siblings(".allocate-type1").html());
		$(".allocatemodal-mark").val($(this).parent().siblings(".allocate-mark").html());

		/*显示商品的信息*/
		var id = $(this).parent().find('input').val();
		var	getprosuccess = function(msg)
		{
			$('.productname').html(msg.proname);
			$('.formatdatas').html(msg.format);
			$('.pronumber').html(msg.total);
		}
		var getprofail = function()
		{
			console.log('get product info fail!');
		}
		util.ajax_post('/warehouse/getallocatedata.php',{id:id},getprosuccess,getprofail);
	});


})