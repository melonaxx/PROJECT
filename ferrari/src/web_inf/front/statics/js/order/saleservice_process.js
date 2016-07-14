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
	if($(".ortbody tr").length==0){
		$(".empty-img").show();
	}else{
		$(".empty-img").hide();
	}
	/*关闭订单*/
	$(".sale-close").on("click",function(){
		var saleid = new Object(); //售后单信息ID
		var flag = false;
		$(".check[type='checkbox']").each(function(i){
			if(this.checked==true){
				var ordersaleid = $(".check[type='checkbox']").eq(i).attr('saleid');
				flag=true;
				if (ordersaleid) {
					saleid[i] = (ordersaleid);
				}
			}
		});
		if(flag){
			$(".modal-closeshou").show();
		}else{
			$(".modal-tip").show();
		}

		/*确认btn*/
		$('.closesalebtn').off('click').click(function(){
			$(".modal-closeshou").hide();
			var closesalesuccess = function(msg)
			{
				location.reload();
			}
			var closesalefail = function()
			{
				alert('订单关闭失败！');
				location.reload();
			}
			util.ajax_post('/order/doclosesale.php',{saleid:saleid},closesalesuccess,closesalefail);
		});
	});

	/*已解决售后单*/
	$(".sale-finish").on("click",function(){
		var saleid = new Object(); //售后单信息ID
		var flag = false;
		$(".check[type='checkbox']").each(function(i){
			if(this.checked==true){
				var ordersaleid = $(".check[type='checkbox']").eq(i).attr('saleid');
				flag=true;
				if (ordersaleid) {
					saleid[i] = (ordersaleid);
				}
			}
		});
		if(flag){
			$(".modal-finished").show();
		}else{
			$(".modal-tip").show();
		}
		/*确认btn*/
		$('.solvesalebtn').off('click').click(function(){
			$(".modal-finished").hide();
			var solvesuccess = function(msg)
			{
				location.reload();
			}
			var solvefail = function()
			{
				alert('订单关闭失败！');
				location.reload();
			}
			util.ajax_post('/order/solveordersale.php',{saleid:saleid},solvesuccess,solvefail);
		});
	});

	/*售后时进行入库*/
	$(".ruku").click(function(){
		$('.ascomment').val('');
		var onlineid = $(this).attr('onlineid'); //订单编号
		var orderid  = $(this).attr('orderid'); //订单ID
		var saleid   = $(this).attr('saleid'); //售后单ID
		var shopid   = $(this).attr('shopid'); //店铺ID
		var shopname = $(this).attr('shopname'); //店铺名称

		$('.asonlineid').val(onlineid);
		$('.asshopid').val(shopid);
		$('.asshopname').val(shopname);
		$('input[name=orderid]').val(orderid);
		$('input[name=saleid]').val(saleid);

		$(".modal-enterware").show();

		var saleid = $(this).attr('saleid'); //售后ID

		var getprosuccess = function(msg)
		{
			var trdata = '';
			$.each(msg,function(i,v){
				trdata += '\
					<tr>\
						<td>'+(i+1)+'</td>\
						<td>\
							<input class="form-control input-sm float_left inputnumber" type="text" '+'onkeyup="value=value.replace(/[^0-9\.]/g,\'\')"'+'>\
							<input type="hidden" name="saleproductid" value="'+v.productid+'">\
							<input type="hidden" name="waitinstore" value="'+v.total+'">\
						</td>\
						<td>'+v.name+'</td>\
						<td>'+v.total+'</td>\
						<td>\
							<div class="total">'+v.total+'</div>\
						</td>\
					</tr>';
			});
			$('.prolist').empty().append(trdata);

			//判断输入的数量
			$('.inputnumber').off('keyup').keyup(function(){
				var indata = $(this).val();
				var olddata = $(this).closest('tr').find('.total').text();
				if (parseInt(indata) > parseInt(olddata)) {
					$(this).val(olddata);
				}
			});

		}
		var getprofail = function()
		{
			console.log("get productinfo fail!");
		}
		util.ajax_post('/order/listprobysaleid.php',{saleid:saleid},getprosuccess,getprofail);
	});

	/*确认入库*/
	$('.instorebtn').click(function(){
		$(".modal-enterware").hide();
		//商品列表信息
		var proinfolist = new Object();
		$.each($('.inputnumber'),function(i){
			//如果入库数量存在
			if ($('.inputnumber').eq(i).val()) {
				var proinarr = new Object();
				proinarr['alinstore']   = $('.inputnumber').eq(i).val();
				proinarr['waitinstore'] = $('input[name=waitinstore]').eq(i).val();
				proinarr['productid']   = $('input[name=saleproductid]').eq(i).val();

				proinfolist[i] = proinarr;
			}
		});

		var instoredata = {
			onlineid : $('.asonlineid').val(),
			orderid  : $('input[name=orderid]').val(),
			saleid   : $('input[name=saleid]').val(),
			shopid   : $('.asshopid').val(),
			storeid  : $('.asstoreid').val(),
			comment  : $('.ascomment').val(),

			prodata  : proinfolist
		}

		var desstoresuccess = function(msg)
		{
			alert('售后单入库成功！');
			// location.reload();
		}
		var desstorefail = function()
		{
			alert('售后单入库失败！');
			// location.reload();
		}
		util.ajax_post('/order/saleinstore.php',{instoredata:instoredata},desstoresuccess,desstorefail);
	});

	/*售后时进行记账*/
	$(".jizhang").click(function(){
		$(".modal-jizhang").show();
	});


})