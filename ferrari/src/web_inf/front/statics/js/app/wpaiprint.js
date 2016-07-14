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
		if ($('.pbillsearch').val())
		{
			var search = $('.pbillsearch').val();
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
		if (!$('.pbillsearch').val()) {
			$('.pbillsearch').css('border','1px solid red');
			$('.pbillsearch').focus();
			return false;
		} else {
			keepPageSearch('search');
		}
	});

	/*搜索框失焦事件*/
	$('.pbillsearch').blur(function(){
		$('.pbillsearch').css('border','');
		return false;
	});

	/*保持搜索条件选中状态*/
	if (GetRequest().search)
	{
		if (GetRequest().search) {
			$('.pbillsearch').val(GetRequest().search);
		}
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
	/*没找到记录*/
	if($(".tbody tr").length==0){
		$(".no-find").show();
	}else{
		$(".no-find").hide();
	}

	/*关闭订单*/
	$(".sure-close").on("click",function(){
		var pbillid = new Object(); //印刷单ID
		var flag = false;
		$(".check[type='checkbox']").each(function(i){
			if(this.checked==true){
				flag=true;
				pbillid[i] = ($(".check[type='checkbox']").eq(i).attr('pbillid'));
			}
		});
		if(flag){
			$(".modal-close").show();
		}else{
			$(".modal-tip").show();
		}
		/*印刷单关闭确认btn*/
		$('.closeprint').off('click').click(function(){
			$(".modal-close").hide();
			var closepbsuccess = function(msg)
			{
				location.reload();
			}
			var closepbfail = function()
			{
				alert('关闭印刷单失败！');
				location.reload();
			}
			util.ajax_post('/app/closeprintbill.php',{pbillid:pbillid},closepbsuccess,closepbfail);
		});
	});
})