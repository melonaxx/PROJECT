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

	/*保持是否是预警状态*/
	if (GetRequest().iswarning)
	{
		$.each($('.iswarning option'),function(i){
			if ($('.iswarning option').eq(i).val() == GetRequest().iswarning)
			{
				$('.iswarning option').eq(i).attr('selected',true);
			}
		});
	}

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

		//是否有预警
		if ($('.iswarning option:selected').val() !=-1)
		{
			var iswarning = $('.iswarning option:selected').val();
		} else {
			var iswarning = '';
		}
		paramarr['iswarning'] = iswarning;

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
		var iswarning 		= getParam().iswarning;
		var pagesize 		= getParam().pagesize;
		var url = location.href.substring(0,location.href.indexOf('?'));
		window.location.href=url+"?page="+page+"&storeid="+storeid+"&iswarning="+iswarning+"&pagesize="+pagesize;
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

	$(".warning-scan-right").hover(
		function(){
			$(this).css("color","white");
		},
		function(){
			$(this).css("color","white");
		}
	)

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

	 //全选商品;
	 var flag=false;
	$(".warnset-allcheck").on("click",function(){
		if(this.checked){
			$("input[type='checkbox']").each(function(){
				this.checked=true;
				flag=true;
			})
		}else{
			$("input[type='checkbox']").each(function(){
				this.checked=false;
				flag=false;
			})
		}
	});
	//批量设置的模态窗
	$(".batch-set").click(function(){
		//取消上下限标记
		$(".cancle-lowlimit").data('limit','');
		$("input[type='checkbox']").each(function(i){
			if($("input[type='checkbox']").eq(i).prop("checked")){
				flag=true;
				$(".modal-batchset").show();
				$(".close-btn").click(function(){
					$(".modal-batchset").hide();
					flag=false;
				});
			}
		});
		if(flag){
			$(".modal-batchset").show();
		}else{
			$(".modal-noselect").show();
		}
	});
	//单独设置上限下限
	$(".batch-set1").click(function(){
		$(".modal-batchset1").show();
		var setbtn = $(this);
		setbtn.parent().parent().addClass("hover");
		$(".lower-limit1").val(setbtn.parent().siblings(".low-limit").html());
		$(".upper-limit1").val(setbtn.parent().siblings(".up-limit").html());
		//单独上下限确认btn
		$('.singlebtn').off('click').click(function(){
			$(".modal-batchset1").hide();
			//仓库ID
			var storeid    = $('.storedata option:selected').val();
			//strproductid
			var strproductid = setbtn.closest('tr').find('input[name=strproductid]').val();
			//单独上限
			var singleuplimit  = $('.singleuplimit').val();
			//单独下限
			var singlelowlimit = $('.singlelowlimit').val();
			if (parseInt(singlelowlimit) > parseInt(singleuplimit)) {
				alert('下限不能大于上限!');
				return false;
			}
			//信息obj
			var singleobj   = new Object();
			var singleinobj = new Object();

			singleinobj['strproductid']   = strproductid;
			singleinobj['storeid']        = storeid;
			singleinobj['singleuplimit']  = singleuplimit;
			singleinobj['singlelowlimit'] = singlelowlimit;
			singleobj[0] = singleinobj;
			var cancellowsuccess = function(msg)
			{
				alert('修改成功！');
				location.reload();
			}
			var cancellowfail = function()
			{
				alert('修改失败！');
				location.reload();
			}
			util.ajax_post('/warehouse/editwarninguplow.php',{warning:singleobj},cancellowsuccess,cancellowfail);

		});
		$(".close-btn").click(function(){
			$(".modal-batchset1").hide();
		});
	});
	//取消下限
	$(".cancle-lower-limit").click(function(){
		//设置取消下限标记
		$(".cancle-lowlimit").data('limit','low');
		var flag = false;
		$("input[type='checkbox']").each(function(){
			if(this.checked==true){
				flag=true;
			}
		});
		if(flag){
			var $this=$(this);
			$(this).parent().parent().parent().addClass("hover");
			$(".modal-canclelow").show();
			$(".cancle-title").html("取消下限");
			$(".cancle-sure").html("您确定要取消商品下限吗？");
			$(".cancle-lowlimit").click(function(){
				$(".hover").children(".low-limit").html("");
				$(".modal-canclelow").hide();
				$this.parent().parent().parent().removeClass("hover");
			    $("input").prop("checked",false);
			    flag=false;
			});
			$(".close-btn").click(function(){
				$this.parent().parent().parent().removeClass("hover");
			    $("input").prop("checked",false);
			    flag=false;
			});
			$(".modal-canclelow").show();
		}else{
			$(".modal-noselect").show();
		}
	});

	function getLowUp()
	{
		//信息obj
		var warning    = new Object();
		var checkedbox = $("input[name=warningcheck]:checked");
		//仓库ID
		var storeid    = getParam().storeid;
		//批量上限
		var uplimit    = $('.uplimit').val();
		//批量下限
		var lowlimit   = $('.lowlimit').val();
		//区分上下限
		var islowup        = $(".cancle-lowlimit").data('limit');

		checkedbox.each(function(i,v){
			var warningin  = new Object();
			if(this.checked==true){
				//strproductID
				var strproductid = checkedbox.eq(i).closest('tr').find('input[name=strproductid]').val();
				warningin['strproductid'] = strproductid;
				warningin['storeid'] = storeid;
				if (uplimit) {
					warningin['uplimit'] = uplimit;
				}
				if (lowlimit) {
					warningin['lowlimit'] = lowlimit;
				}
				if (islowup) {
					warningin['islowup'] = islowup;
				}
				warning[i] = warningin;
			}
		});

		var cancellowsuccess = function(msg)
		{
			alert('修改成功！');
			location.reload();
		}
		var cancellowfail = function()
		{
			alert('修改失败！');
			location.reload();
		}
		util.ajax_post('/warehouse/editwarninguplow.php',{warning:warning},cancellowsuccess,cancellowfail);
	}
	/*取消上下限确认btn*/
	$('.cancle-lowlimit').click(function(){
		getLowUp();
	});
	/*批量上下限确认btn*/
	$('.multisetbtn').click(function(){
		$(".modal-batchset").hide();
		var lowlimit = $('.lowlimit').val();
		var uplimit = $('.uplimit').val();
		if (parseInt(lowlimit) > parseInt(uplimit)) {
			alert('下限不能大于上限!');
			return false;
		}
		getLowUp();
	});

	//取消上限
	$(".cancle-upper-limit").click(function(){
		//设置取消上限标记
		$(".cancle-lowlimit").data('limit','up');
		var flag = false;
		$("input[type='checkbox']").each(function(){
			if(this.checked==true){
				flag=true;
			}
		});
		if(flag){
			var $this=$(this);
			$(this).parent().parent().parent().addClass("click");
			$(".modal-canclelow").show();
			$(".cancle-title").html("取消上限");
			$(".cancle-sure").html("您确定要取消商品上限吗？");
			$(".cancle-lowlimit").click(function(){
				$(".click").children(".up-limit").html("");
				$(".modal-canclelow").hide();
				$this.parent().parent().parent().removeClass("click");
				flag=false;
			});
			$(".close-btn").click(function(){
				$this.parent().parent().parent().removeClass("click");
			});
			$(".modal-canclelow").show();
		}else{
			$(".modal-noselect").show();
		}
	});

	//==============================预警扫描========================================//
	$('.warningscanbtn').click(function(){
		var url = '';
		var storeid = $('.storedata option:selected').val();
		var indexpos = location.href.indexOf('?');
		if (indexpos == -1) {
			url = location.href+"?storeid="+storeid;
		} else {
			url = location.href.substring(0,indexpos)+"?storeid="+storeid;
		}
		window.location.href = url;
	});

	/*进行单个商品的修改*/

	//库存预警设置
	$(".warnset").click(function(){
		var $this=$(this);
		$(".modal-batchset1").show();
		$this.parent().parent().addClass("hover");
		$(".lower-limit1").val($this.parent().siblings(".low-limit").html());
		$(".upper-limit1").val($this.parent().siblings(".up-limit").html());
		$(".close-btn").click(function(){
			$this.parent().parent().removeClass("hover");
		});
		/*设置商品*/
		var mainwarningobj = new Object();
		$('.mainsubbtn').click(function(){
		$(".modal-batchset1").hide();
			var mainuplimit = $('.mainuplimit').val();
			var mainlowlimit = $('.mainlowlimit').val();
			var storeid = $('.storedata option:selected').val();
			var strproductid = $this.closest('tr').find('input[name=strproductid]').val();
			//信息obj
			var singleobj   = new Object();
			var singleinobj = new Object();

			singleinobj['strproductid']   = strproductid;
			singleinobj['storeid']        = storeid;
			singleinobj['singleuplimit']  = mainuplimit;
			singleinobj['singlelowlimit'] = mainlowlimit;
			singleobj[0] = singleinobj;
			var cancellowsuccess = function(msg)
			{
				alert('修改成功！');
				location.reload();
			}
			var cancellowfail = function()
			{
				alert('修改失败！');
				location.reload();
			}
			util.ajax_post('/warehouse/editwarninguplow.php',{warning:singleobj},cancellowsuccess,cancellowfail);

		});
	});
})