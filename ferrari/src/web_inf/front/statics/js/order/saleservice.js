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

	/*保持搜索的条件信息*/
	if (GetRequest().search) {
		$('.ordersearch').val(GetRequest().search);
	}
	/*----------------没有找到记录的显示隐藏---------------*/
	if($(".ortbody tr").length==0){
		$(".empty-img").show();
	}else{
		$(".empty-img").hide();
	}
	/*----------------点击售后---------------*/
	$(".shouhou").on("click",function(){
		/*初始化*/
		$('.refundamount').val('');
		$('.shouhou-mark').val('');
		$('.post').val('');
		$('.backexpress').val('');
		$('.postfees').val('');

		var $num=$(this).attr('custimes'); //售后次数
		var orderid = $(this).attr('orderid'); //订单编号
		if($num==0){
			$('.saleorderid').val(orderid);

	    	/*订单的商品信息*/
	    	var getsalesuccess = function(msg)
	    	{
	    		var prosum = msg['sum'];//商品总数

	    		var trdata = ''; //商品列表信息
	    		$.each(msg['data'],function(i,v){
	    			//商品图片
					var path  = '';
					var image = '';
	    			if (!v.image) {
						path  = '';
						image = '';
	    			} else {
						path  = v.path;
						image = v.image;
	    			}
	    			trdata += "\
							<tr>\
								<td class=\"center\">"+(i+1)+"</td>\
								<td><img width='20px' height='20px' src='"+path+image+"'/></td>\
								<td>\
									<input proid='"+v.productid+"' prototal='"+v.total+"' pricesell='"+v.pricesell+"' class=\"form-control input-sm ubacknum\" type=\"text\" onkeyup='value=value.replace(/[^0-9]/g,\"\")'>\
								</td>\
								<td>"+v.name+"</td>\
								<td>"+v.format+"</td>\
								<td>￥"+v.pricesell+"</td>\
								<td>"+v.total+"</td>\
								<td class='sprototal'>"+v.total+"</td>\
							</tr>";
	    		});
	    		//商品合计信息
	    			trdata += "\
						<tr class=\"heji\">\
							<td class=\"center\">合计</td>\
							<td></td>\
							<td></td>\
							<td colspan=\"4\"></td>\
							<td>"+prosum+"</td>\
							<input name=\"zong_pay\" value=\""+prosum+"\" type=\"hidden\">\
						</tr>";
	    		$('.saleprotr').empty().append(trdata);
	    	}
	    	var getsalefail = function()
	    	{
	    		console.log('get sale fail!');
	    	}
	    	util.ajax_post('/order/getprofromorder.php',{orderid:orderid},getsalesuccess,getsalefail);

			$(".modal-shouhou").show();
		}else if($num>0){
			$(".modal-tip").show();
			$(".sure-continue").on("click",function(){
				$(".modal-shouhou").show();
			});
		}
	});

	/*售后中退回数量的限制*/
	$('.shouhou-table').on('keyup','.ubacknum',function(){
		var sprototal = $(this).closest('tr').find('.sprototal').text();
		if ($(this).val() >= parseInt(sprototal)) {
			$(this).val(sprototal);
		}
	});

	/*售后信息添加*/
	$('.asalesub').click(function(){
		$(".modal-shouhou").hide();

		//售后的商品列表
		var asalepro = Object();
		$.each($('.ubacknum'),function(i){
			var asalein  = Object();
			asalein['total']     = $('.ubacknum').eq(i).val();
			asalein['productid'] = $('.ubacknum').eq(i).attr('proid');
			asalein['price']     = $('.ubacknum').eq(i).attr('pricesell');
			asalepro[i] = asalein;
		});

		var asaleobj = {
			saletype		: $('.saletype').val(),
			orderid			: $('.saleorderid').val(),
			cateid			: $('.salecate').val(),
			backbankid		: $('.salebankid').val(),
			backpay			: $('.refundamount').val(),
			contents		: $('.shouhou-mark').val(),
			backexpress		: $('.post').val(),
			number			: $('.backexpress').val(),
			freight			: $('.expfee').val(),
			backfee			: $('.postfees').val(),

			salepro 		: asalepro
		}
		//进行售后单信息的添加
		var addasalesuccess = function(msg)
		{
			alert('添加售后单成功！');
			location.reload();
		}
		var addasalefail = function()
		{
			alert('添加售后单失败！');
			location.reload();
		}
		util.ajax_post('/order/addaftersale.php',{asaleobj:asaleobj},addasalesuccess,addasalefail);
	});

	/*售后类型*/
	$(".shouhou").change(function(){
		var v = $(this).val();
		//补发
		if (v == "Delivery") {
			$(".express-infor").hide();
			$(".shou-good").show();
			$(".shou-title").text("补发商品");
			$(".shou-num").text("补发数量");
			$(".sure-shounum").text("可补发数量");
		}
		//仅退款
		if(v == "Refunds"){
			$(".express-infor").hide();
			$(".shou-good").hide();

		}
		//退货与换货和维修
		if(v == 'Return' || v == 'Exchange' || v == 'Repair') {
			$(".express-infor").show();
			$(".shou-good").show();
			$(".shou-title").text("退回商品");
			$(".shou-num").text("退回数量");
			$(".sure-shounum").text("可退回数量");
		}

	});
})