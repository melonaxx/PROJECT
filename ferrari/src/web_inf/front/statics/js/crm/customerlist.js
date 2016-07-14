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
		if (!$('.rrow option:selected').val() || $('.rrow option:selected').val() =='undefined' )
		{
			var pagesize = '';
		} else {
			var pagesize = $('.rrow option:selected').val();
		}
		paramarr['pagesize'] = pagesize;

		//客户类型
		if ($('.custype option:selected').val())
		{
			var custype = $('.custype option:selected').val();
		} else {
			var custype = '';
		}
		paramarr['custype'] = custype;

		//客户名称
		if ($('.input_search').val() !='')
		{
			var cusname = $('.input_search').val();
		} else {
			var cusname = '';
		}
		paramarr['cusname'] = cusname;

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
		var custype 		= getParam().custype;
		var cusname 		= getParam().cusname;
		var pagesize 		= getParam().pagesize;
		var url = location.href.substring(0,location.href.indexOf('?'));
		window.location.href=url+"?page="+page+"&custype="+custype+"&cusname="+cusname+"&pagesize="+pagesize;
	}
	/*切换页大小*/
	$('.rrow').change(function(){
		keepPageSearch();
	});

	/*重置button*/
	$('.resetbtn').click(function(){
		location.reload();
	});

	/*进行搜索*/
	$('.searchbtn').click(function(){
		keepPageSearch('search');
	});

	/*保持客户状态的选中状态*/
	if (GetRequest().custype)
	{
		$.each($('.custype option'),function(i){
			if ($('.custype option').eq(i).val() == GetRequest().custype)
			{
				$('.custype option').eq(i).attr('selected',true);
			}
		});
	}

	/*保持客户名称搜索值*/
	if (GetRequest().cusname)
	{
		$('.input_search').val(GetRequest().cusname);
	}

	/*删除客户信息*/
	$('.customer-del').click(function(){
		$('.modal-customer').show();
		$delbtn = $(this);

		$('.custom-sure').off('click').click(function(){
			//customerid
			var cusid = $delbtn.parent().find('input[name=cusid]').val();

			var delcussuccess = function(msg)
			{
				location.reload();
			}
			var delcusfail = function()
			{
				location.reload();
			}
			util.ajax_post('/crm/delcustomer.php',{cusid:cusid},delcussuccess,delcusfail);
		});

	});

	//查看客户详情;
	SeeKeHu();
	function SeeKeHu(){
		$(".customer-see").click(function(){
			var $this=$(this);
		    $this.parent().parent().addClass("click").siblings().removeClass("click");
			$(".active2").show().addClass("active").siblings().removeClass("active");
			$(".active1").hide();
			$(".status3").show().siblings(".status").hide();
			//客户的customerid
			var cusid = $this.parent().find('input[name=cusid]').val();
			window.location="customerinfor.php?cusid="+cusid;
		});
	 }

})

