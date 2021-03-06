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

	$('.dropdown-menu .type').click(function(){
		var v = $(this).text();
		if(v == "订单编号"){
			$('.butt .butt_name').html(v);
			// $('.butt .check[name=type]').val("bian");
		}else if(v == "收件人"){
			$('.butt .butt_name').html(v);
			// $('.butt .check[name=type]').val("shou");
		}else{
			$('.butt .butt_name').html(v);
			// $('.butt .check[name=type]').val("kuai");
		}
	});
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

	/*提交异常*/;
	$(".submit-statu").click(function(){
		var orderid = new Object(); //订单ID
		var flag = false;
		$(".check[type='checkbox']").each(function(i){
			if(this.checked==true){
				flag=true;
				orderid[i] = ($(".check[type='checkbox']").eq(i).attr('orderid'));
			}
		});
		if(flag){
			$(".modal-orderall").show();
		}else{
			$(".modal-orderall1").show();
		}
		/*异常提交确认btn*/
		$('.unusualbtn').off('click').click(function(){
			$(".modal-abnormal").hide();
			orderid['unusualid']      = $('.orderunusualid').val();//异常ID
			orderid['unusualcomment'] = $('.unusualcomment').val();//异常备注
			var unusualsuccess = function(msg)
			{
				location.reload();
			}
			var unusualfail = function()
			{
				alert('提交异常失败！');
				location.reload();
			}
			util.ajax_post('/order/doorderunusual.php',{orderid:orderid},unusualsuccess,unusualfail);
		});
	});
})