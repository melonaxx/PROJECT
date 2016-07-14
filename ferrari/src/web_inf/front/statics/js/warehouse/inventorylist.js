$(function(){
	/*查看盘点单的详情*/
	$(".check-detail").click(function(){
		//日期
		$(".checkmodal-time").html($(this).parent().siblings(".checktime").html());
		//仓库
		$(".checkmodal-ware").val($(this).parent().siblings(".checkware").html());
		//备注
		$('.checkmodal-mark').val($(this).parent().siblings('.checkcomment').html());
		//图片
		var url = $('input[name=path]').val()+$('input[name=image]').val();
		$('.checkmodalimg img').attr('src',url);
		//规格
		$('.checkmodalsize').html($(this).parent().siblings('.chechformat').html());
		//商品名称
		$(".checkmodalname").html($(this).parent().siblings(".checkname").html());
		//商品编码
		$(".checkmodalcode").html($(this).parent().siblings(".checkcode").html());
		//盘点前数量
		$('.checkmodalnum1').html($(this).parent().find('input[name=oldtotal]').val());
		$('.checkmodalnum2').html($(this).parent().find('input[name=newtotal]').val());
		$('.checkmodalnum3').html($(this).parent().find('input[name=total]').val());
		$(".modal-checklist").show();
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

	/*保持仓库选项的选中状态*/
	if (GetRequest().storeid)
	{
		$.each($('.storedata option'),function(i){
			if ($('.storedata option').eq(i).val() == GetRequest().storeid)
			{
				$('.storedata option').eq(i).attr('selected',true);
			}
		});
	}

	/*保持结束时间的选中状态*/
	if (GetRequest().startdate)
	{
		$('.startdate').val(GetRequest().startdate);
	}

	/*保持开始时间的选中状态*/
	if (GetRequest().enddate)
	{
		$('.enddate').val(GetRequest().enddate);
	}

	/*图片放大效果*/
	$(".img1").each(function(i){
		$('.img1').eq(i).on("mouseover",function(){
			$(".img2").eq(i).show();
			var height=$(this).siblings(".img2").height();
			var height1=$(this).height();
			var top=$(this).siblings(".img2").position().top;
			$(this).on("mousemove",function(event){
				var event = event || window.event;
				var gao=$(window).height();
				var iheight =gao -(event.clientY+height1+10);
				var top1 = (iheight < height?-height:top) + "px";
				$(this).siblings(".img2").css("top",top1);
			});
		});
	});
	$(".img1").each(function(i){
		$(this).on("mouseout",function(){
			$(".img2").eq(i).hide();
		});
	})

	/*获取参数*/
	function getParam(){
		var paramarr = new Object();
		//页大小
		if ($('.rrow option:selected').val() !='undefined' || !$('.rrow option:selected').val())
		{
			var pagesize = $('.rrow option:selected').val();
		} else {
			var pagesize = '';
		}
		paramarr['pagesize'] = pagesize;

		//起始时间
		if ($('.startdate').val() != 'undefined' || !$('.startdate').val())
		{
			var startdate = $('.startdate').val();
		} else {
			var startdate = '';
		}
		paramarr['startdate'] = startdate;

		//结束时间
		if ($('.enddate').val() !='undefined' || !$('.enddate').val())
		{
			var enddate = $('.enddate').val();
		} else {
			var enddate = '';
		}
		paramarr['enddate'] = enddate;

		//仓库ID
		if ($('.storedata option:selected').val() !='undefined' || !$('.storedata option:selected').val())
		{
			var storeid = $('.storedata option:selected').val();
		} else {
			var storeid = '';
		}
		paramarr['storeid'] = storeid;

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
		var storeid 		= getParam().storeid;
		var startdate 		= getParam().startdate;
		var enddate 		= getParam().enddate;
		var pagesize 		= getParam().pagesize;
		var url = location.href.substring(0,location.href.indexOf('?'));
		window.location.href=url+"?page="+page+"&storeid="+storeid+"&enddate="+enddate+"&startdate="+startdate+"&pagesize="+pagesize;
	}
	/*切换页大小*/
	$('.rrow').change(function(){
		keepPageSearch();
	});

	/*重置button*/
	$('.resetbtn').click(function(){
		var url = location.href.substring(0,location.href.indexOf('?'));
		window.location.href = url;
	});

	/*进行搜索*/
	$('.searchbtn').click(function(){
		keepPageSearch('search');
	});
})