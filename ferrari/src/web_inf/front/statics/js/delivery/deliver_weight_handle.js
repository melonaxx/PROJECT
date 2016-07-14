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
		if ($('.rrow option:selected').val())
		{
			var pagesize = $('.rrow option:selected').val();
		} else {
			var pagesize = '';
		}
		paramarr['pagesize'] = pagesize;

		//搜索的内容
		if ($('.ordersearch').val())
		{
			var search = $('.ordersearch').val();
		} else {
			var search = '';
		}
		paramarr['search'] = $.trim(search);

		//当前页数
		if (GetRequest().page)
		{
			var page = GetRequest().page;
		} else {
			var page = 1;
		}
		paramarr['page'] = page;

		//pagewaring
		if ($('input[name=pagewarning]').val() == 'callback') {
			paramarr['page'] = 1;
		}

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
		var search   = getParam().search;
		var pagesize = getParam().pagesize;
		var url = location.href.substring(0,location.href.indexOf('?'));
		window.location.href=url+"?page="+page+"&search="+search+"&pagesize="+pagesize;
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
		if (!$('.ordersearch').val()) {
			$('.ordersearch').css('border','1px solid red');
			$('.ordersearch').focus();
			return false;
		} else {
			keepPageSearch('search');
		}
	});

	/*搜索框失焦事件*/
	$('.ordersearch').blur(function(){
		$('.ordersearch').css('border','');
		return false;
	});

	/*保持搜索条件选中状态*/
	if (GetRequest().search)
	{
		if (GetRequest().search) {
			$('.ordersearch').val(GetRequest().search);
		}
	}

	//初始化
	if($(".weight-tr").length==0){
		$(".empty-img").show();
	}else{
		$(".empty-img").hide();
	}
	//全选;
	var flag=false;
	$(".allcheck").on("click",function(){
		if(this.checked){
			$(".check[type='checkbox']").each(function(){
				this.checked=true;
				flag=true;
			});
		}else{
			$(".check[type='checkbox']").each(function(){
				this.checked=false;
				flag=false;
			});
		}
	});
	//扫单发货
	$(".scan-fahuo").click(function(){
		$(".check[type='checkbox']").each(function(){
			if(this.checked==true){
				flag=true;
				$(".modal-saodan").show();

			}
		});
		if(flag){
			$(".modal-saodan").show();
		}else{
			$(".modal-tip").show();
		}
	});
	//提交异常;
	$(".submit-statu").click(function(){
		$(".check[type='checkbox']").each(function(){
			if(this.checked==true){
				flag=true;
				$(".modal-subabnormal").show();
			}
		});
		if(flag){
			$(".modal-subabnormal").show();
		}else{
			$(".modal-tip").show();
		}
	});
	//订单审核
	$(".shen").click(function(){
		$(".check[type='checkbox']").each(function(){
			if(this.checked==true){
				flag=true;
				$(".modal-returncheck").show();
			}
		});
		if(flag){
			$(".modal-returncheck").show();
		}else{
			$(".modal-tip").show();
		}
	});
	//打单配货
	$(".pei").click(function(){
		$(".check[type='checkbox']").each(function(){
			if(this.checked==true){
				flag=true;
				$(".modal-peihuo").show();
			}
		});
		if(flag){
			$(".modal-peihuo").show();
		}else{
			$(".modal-tip").show();
		}
	});
	//打单配货
	$(".yan").click(function(){
		$(".check[type='checkbox']").each(function(){
			if(this.checked==true){
				flag=true;
				$(".modal-yanhuo").show();
			}
		});
		if(flag){
			$(".modal-yanhuo").show();
		}else{
			$(".modal-tip").show();
		}
	});


})