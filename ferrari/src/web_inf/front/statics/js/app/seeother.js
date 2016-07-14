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
		var pagesize = $('.rrow option:selected').val() ? $('.rrow option:selected').val() : '';
		paramarr['pagesize'] = pagesize;

		//搜索的内容
		var search = $('.pbillsearch').val() ? $('.pbillsearch').val() : '';
		paramarr['search'] = $.trim(search);

		//选择的是那种方式
		var switchtype = $('.switchradio:checked').val() ? $('.switchradio:checked').val() : '';
		paramarr['switchtype'] = $.trim(switchtype);

		/*数量查询*/
		//起始时间
		var bnumberdate = $('.bnumberdate').val() ? $('.bnumberdate').val() : '';
		var enumberdate = $('.enumberdate').val() ? $('.enumberdate').val() : '';
		if (bnumberdate >= enumberdate) {
			bnumberdate = enumberdate;
		}
		paramarr['enumberdate'] = $.trim(enumberdate);
		paramarr['bnumberdate'] = $.trim(bnumberdate);
		//客服
		var numbercus = $('.numbercus').val() ? $('.numbercus').val() : '';
		paramarr['numbercus'] = $.trim(numbercus);

		/*费用查询*/
		var ifradio  = $('input[name=ifradio]:checked').val() ? $('input[name=ifradio]:checked').val() : '';
		var payunit  = $('.payunit').val() ? $('.payunit').val() : '';
		var bpaydate = $('.bpaydate').val() ? $('.bpaydate').val() : '';
		var epaydate = $('.epaydate').val() ? $('.epaydate').val() : '';
		if (bpaydate >= epaydate) {
			bpaydate = epaydate;
		}
		paramarr['ifradio']  = $.trim(ifradio);
		paramarr['bpaydate'] = $.trim(bpaydate);
		paramarr['epaydate'] = $.trim(epaydate);
		paramarr['payunit']  = $.trim(payunit);

		//当前页数
		var page = GetRequest().page ? GetRequest().page : 1;
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
		var search      = getParam().search;
		var switchtype  = getParam().switchtype;
		var bnumberdate = getParam().bnumberdate;
		var enumberdate = getParam().enumberdate;
		var numbercus   = getParam().numbercus;
		var ifradio     = getParam().ifradio;
		var bpaydate    = getParam().bpaydate;
		var epaydate    = getParam().epaydate;
		var payunit     = getParam().payunit;
		var pagesize    = getParam().pagesize;
		var url = location.href.substring(0,location.href.indexOf('?'));
		window.location.href=url+"?page="+page+"&switchtype="+switchtype+"&bnumberdate="+bnumberdate+"&enumberdate="+enumberdate+"&numbercus="+numbercus+"&ifradio="+ifradio+"&bpaydate="+bpaydate+"&epaydate="+epaydate+"&payunit="+payunit+"&search="+search+"&pagesize="+pagesize;
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

	/*保持搜索条件选中状态*/
	if (GetRequest().search) {
		$('.pbillsearch').val(GetRequest().search);
	}


	/*保持选择方式选中状态*/
	if (GetRequest().switchtype) {
		if (GetRequest().switchtype == 'number') {

			$(".paybox").hide();
			$(".numberbox").show();
			$('.number').attr('checked',true);
			/*保持印刷状态中起始时间选中状态*/
			if (GetRequest().bnumberdate) {
				$('.bnumberdate').val(GetRequest().bnumberdate);
			}
			if (GetRequest().enumberdate) {
				$('.enumberdate').val(GetRequest().enumberdate);
			}

			/*保持印刷状态中客服选中状态*/
			if (GetRequest().numbercus) {
				$('.numbercus').val(GetRequest().numbercus);
			}

		} else if (GetRequest().switchtype == 'pay') {

			$(".paybox").show();
			$(".numberbox").hide();
			$('.pay').attr('checked',true);

			//保持印刷费用中日期方式
			if (GetRequest().ifradio) {
				$.each($('input[name=ifradio]'),function(i){
					if ($('input[name=ifradio]').eq(i).val() == GetRequest().ifradio) {
						$('input[name=ifradio]').eq(i).attr('checked',true);
					}
				});
			}

			//保持印刷费用中起始日期
			if (GetRequest().bpaydate) {
				$('.bpaydate').val(GetRequest().bpaydate);
			}
			if (GetRequest().epaydate) {
				$('.epaydate').val(GetRequest().epaydate);
			}

			//保持印刷单位
			if (GetRequest().payunit) {
				$('.payunit').val(GetRequest().payunit);
			}
		}
	} else {
		$('.number').attr('checked',true);
	}

	/*其他查询方式的变换*/
	$('.switchradio').click(function(){
		//初始化
		$('.bpaydate').val('');
		$('.epaydate').val('');
		$('.bnumberdate').val('');
		$('.enumberdate').val('');
		$('.payunit').val('');
		$('.numbercus').val('');
		$('input[name=ifradio]').attr('checked',false);
		//费用对应单位
		if ($(this).val() == 'pay') {
			$(".paybox").show();
			$(".numberbox").hide();
		//数量对应客服
		} else if ($(this).val() == 'number') {
			$(".paybox").hide();
			$(".numberbox").show();
		}
	});

})