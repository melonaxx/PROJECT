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

	//全选;
	var flag=false;
	$(".allcheck").on("click",function(){
		if(this.checked){
			$("input[type='checkbox']").each(function(){
				this.checked=true;
				flag=true;
			});
		}else{
			$("input[type='checkbox']").each(function(){
				this.checked=false;
				flag=false;
			});
		}
	});
	//确认配货;
	$(".sure-peihuo").click(function(){
		$("input[type='checkbox']").each(function(){
			if(this.checked==true){
				flag=true;
				$(".modal-deliver").show();

			}
		});
		if(flag){
			$(".modal-deliver").show();
		}else{
			$(".modal-deliver1").show();
		}
		var num = 0;
		$("input[type='checkbox']:checked").each(function(){
			var va = $(this).parents('tr').find('td:nth-child(8)').html();
			if(va == ""){
				num++;
				$(".deliver-many").html(num);
			}
		});
	});
	//打回审核
	$(".return-check").click(function(){
		$("input[type='checkbox']").each(function(){
			if(this.checked==true){
				flag=true;
				$(".modal-deliver2").show();

			}
		});
		if(flag){
			$(".modal-deliver2").show();
		}else{
			$(".modal-deliver1").show();
		}
	});
	//提交异常;
	$(".submit-statu").click(function(){
		$("input[type='checkbox']").each(function(){
			if(this.checked==true){
				flag=true;
				$(".modal-deliver3").show();
			}
		});
		if(flag){
			$(".modal-deliver3").show();
		}else{
			$(".modal-deliver1").show();
		}
	});
	//批量生成快递单
	$(".express_add").click(function(){
		$("input[type='checkbox']").each(function(){
			if(this.checked==true){
				flag=true;
				$(".modal-deliver4").show();

			}
		});
		if(flag){
			$(".modal-deliver4").show();
		}else{
			$(".modal-deliver1").show();
		}
	});
	$(".mod_express").click(function(){
		$("input[type='checkbox']").each(function(){
			if(this.checked==true){
				flag=true;
				$(".modal-deliver5").show();

			}
		});
		if(flag){
			$(".modal-deliver5").show();
		}else{
			$(".modal-deliver1").show();
		}
	});
	$(".bei").click(function(){
		$("input[type='checkbox']").each(function(){
			if(this.checked==true){
				flag=true;
				$(".modal-deliver6").show();

			}
		});
		if(flag){
			$(".modal-deliver6").show();
		}else{
			$(".modal-deliver1").show();
		}
	});
	//判断是否覆盖
	$(".deliver6-sure").click(function(){
		if($(".change-method").val()=="在原备注上追加"){
			$("input[type='checkbox']:checked").each(function(){
				$(this).parents("tr").find('td:nth-child(5)').html($(this).parents("tr").find('td:nth-child(5)').html()+"[追加]"+$(".deliver6-mark").val());
			    $(".modal-deliver6").hide();
			});
		}else if($(".change-method").val()=="覆盖原先的备注"){
			$("input[type='checkbox']:checked").each(function(){
				$(this).parents("tr").find('td:nth-child(5)').html($(".deliver6-mark").val());
			    $(".modal-deliver6").hide();
			});
		}
	});
})