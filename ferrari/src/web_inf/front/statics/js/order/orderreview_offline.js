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
			$(".check[type='checkbox']").each(function(){
				if (this.disabled == false) {
					this.checked=true;
					flag=true;
				}
			});
		}else{
			$(".check[type='checkbox']").each(function(){
				if (this.disabled == false) {
					this.checked=false;
					flag=false;
				}
			});
		}
	});
	if($(".ortbody tr").length==0){
		$(".empty-img").show();
	}else{
		$(".empty-img").hide();
	}
	/*---------------------点击确认审核---------------------*/
	$(".sure-check").on("click",function(){
		var orderid = new Object(); //订单ID
		var flag = false;
		$(".check[type='checkbox']").each(function(i){
			if(this.checked==true){
				flag=true;
				orderid[i] = ($(".check[type='checkbox']").eq(i).attr('orderid'));
			}
		});
		if(flag){
			$(".modal-check").show();
		}else{
			$(".modal-tip").show();
		}
		/*审核确认btn*/
		$('.verbtn').off('click').click(function(){
			$(".modal-check").hide();
			var verifysuccess = function(msg)
			{
				location.reload();
			}
			var verifyfail = function()
			{
				alert('订单审核失败！');
				location.reload();
			}
			util.ajax_post('/order/doorderverify.php',{orderid:orderid},verifysuccess,verifyfail);
		});
	});

	/*---------------------点击提交异常---------------------*/
	$(".submit-statu").on("click",function(){
		var orderid = new Object(); //订单ID
		var flag = false;
		$(".check[type='checkbox']").each(function(i){
			if(this.checked==true){
				flag=true;
				orderid[i] = ($(".check[type='checkbox']").eq(i).attr('orderid'));
			}
		});
		if(flag){
			$(".modal-abnormal").show();
		}else{
			$(".modal-tip").show();
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


	/*---------------------点击订单拆合手工合单---------------------*/
	$(".chai").on("click",function(){
		var number1;
		var number=0;
		$(".check").each(function(){
			if($(this).is(":checked")){
				number+=1;
				number1=$(this).parent().siblings(".review-num").html();
				orderid1 = $(this).attr("orderid");
				ordernumber = $(this).parent().siblings(".order-num").html();
			}
		});
		if(number==0){
			$(".modal-tip").show();
			$(".tip-content").html("请至少选择一个订单");
		}else if(number==1){
			if(number1<2){
				$(".modal-tip").show();
				$(".tip-content").html("订单的商品数量不能为1");
			}
			if(number1>=2){
				var chaisuccess = function(msg)
				{
					$("#chaiid").val(ordernumber);
					$("#tb1").empty();
					$("#tb2").empty();
					var key = 0;
					$.each(msg,function(idx,item){
						key +=1;
						var pname = item.productname;
						var gui = item.gui;
						var shu = item.total;
						var pid = item.productid;
						var orderid = item.orderid;
						$("#tb1").append("<tr><td class='table_th_number'>"+key+"<input name='order_id' value='"+orderid+"' type='hidden'><input name='product_id[]' value='"+pid+"' type='hidden'></td><td class='split_name'><input name='split[]' class='form-control input-sm form_no_border chai' type='text'></td><td>"+pname+"</td><td>"+gui+"</td><td class='td'><input name='total' value='"+shu+"' type='hidden'><input name='old_total[]' class='form-control input-sm form_no_border' readonly='readonly' value='"+shu+"' type='text'></td></tr>");
						$("#tb2").append("<tr><td class='table_th_number'>"+key+"<input name='new_id[]' value='"+pid+"' type='hidden'></td><td>"+pname+"</td><td>"+gui+"</td><td class='td'><input name='new_total[]' class='form-control input-sm form_no_border' readonly='readonly' type='text'></td></tr>");
					})
				}
				var chaifail = function()
				{
					alert('提交异常失败！');
					location.reload();
				}
				util.ajax_post('/order/showchai.php',{orderid:orderid1},chaisuccess,chaifail);

				$(".modalsplits3").show();
			}
		}else if(number>1){
			$(".modal-tip").show();
			$(".tip-content").html("所选订单数量必须为1");
		}
	});
	$(".he").on("click",function(){
		var number=0;
		var number1=$(".review-num").html();

		var orderid2 = new Array();
		var num = 0;
		$(".check").each(function(){
			if($(this).is(":checked")){
				number+=1;
				orderid2[num] = $(this).attr("orderid");
				num +=1;
			}
		});
		if(number<2){
			$(".modal-tip").show();
			$(".tip-content").html("请至少选择2个订单");
		}else if(number>=2){
			var hesuccess = function(msg)
			{
				$("#tb3").empty();
				$.each(msg,function(idx,item){
					var ordernumber = item.onlineid;
					var shopname = item.shopname;
					var storename = item.storename;
					var exname = item.exname;
					var cusname = item.cusname;
					var address = item.address;
					var orderid = item.id;
					key =idx+1;
					$("#tb3").append("<tr><td>"+key+"</td><td class='center table_th_checkbox'><input name='select_one' value='"+orderid+"' type='radio'><input name='order_id[]' value='"+orderid+"' type='hidden'></td><td>"+ordernumber+"</td><td>"+shopname+"</td><td>"+storename+"</td><td>"+exname+"</td><td>"+cusname+"</td><td>"+address+"</td></tr>");
				})
			}
			var hefail = function()
			{
				alert('提交异常失败！');
				location.reload();
			}
			util.ajax_post('/order/showhe.php',{orderid:orderid2},hesuccess,hefail);
			$(".modalmerge").show();
		}
	});


	/*---------------------订单拆合下拉菜单的显示隐藏------------------*/
	/*$(".ordownbtn1").on("blur",function(){
		$(".ordownul1").hide();
		flag3=true;
	})*/
	$(".ordown1").on("click",function(){
		if($(".ordownul1").is(':hidden')){
			$(".ordownul1").show();
			$(".ordownul2").hide();
		}else{
			$(".ordownul1").hide();
		}
	})
	/*---------------------点击批量修改备注--------------------*/
	$(".beizhu").on("click",function(){
		$(".check[type='checkbox']").each(function(){
			if(this.checked==true){
				flag=true;
				$(".change-mark").show();
			}
		});
		if(flag){
			$(".change-mark").show();
		}else{
			$(".modal-tip").show();
		}
	});

	/*批量修改快递*/
	$(".express").on("click",function(){
		var orderid = new Object(); //订单ID
		var flag = false;
		$(".check[type='checkbox']").each(function(i){
			if(this.checked==true){
				flag=true;
				orderid[i] = ($(".check[type='checkbox']").eq(i).attr('orderid'));
			}
		});
		if(flag){
			$(".change-express").show();
		}else{
			$(".modal-tip").show();
		}
		/*批量修改快递确认btn*/
		$('.editmulexpress').off('click').click(function(){
			$(".change-express").hide();
			orderid['expressid']      = $('.expressid').val();//快递ID
			var expresssuccess = function(msg)
			{
				location.reload();
			}
			var expressfail = function()
			{
				alert('批量修改快递失败！');
				location.reload();
			}
			util.ajax_post('/order/doorderexpress.php',{orderid:orderid},expresssuccess,expressfail);
		});
	});

	/*批量修改仓库*/
	$(".beizhu").on("click",function(){
		$(".check[type='checkbox']").each(function(){
			if(this.checked==true){
				flag=true;
				$(".change-mark").show();
			}
		});
		if(flag){
			$(".change-mark").show();
		}else{
			$(".modal-tip").show();
		}
	});
	/*下载订单*/
	$(".down-order").on("click",function(){
		$(".choice-shop").show();
	});
	/*-------------------------拆分数量-------------------------------*/
	$('.old').on('keyup','input[name="split[]"]',function(){
		var	t = $(this);
		var id = t.parents('tr').find('input[name="product_id[]"]').val();
		var split = t.val().replace(/[^0-9]/ig,"");
		var total = t.parents('tr').find('input[name=total]').val();
		if(split == ""){
			split = 0;
		}

		var sum = 0;
		var split_sum = 0;
		var total_sum = 0;
		//商品总数量(固定不变)
		$('.old tr').find('input[name="total"]').each(function(){
			var v = $(this).val().replace(/[^0-9]/ig,"");
			total_sum = parseInt(v)+parseInt(total_sum);
		});

		//商品总数量(拆分后数量)
		$('.old tr').find('input[name="old_total[]"]').each(function(){
			var v = $(this).val().replace(/[^0-9]/ig,"");
			sum = parseInt(v)+parseInt(sum);
		});
		//拆分总数量
		$('.old tr').find('input[name="split[]"]').each(function(i){
			var v = $(this).val().replace(/[^0-9]/ig,"");
			var od = $('.old tr').find('input[name="total"]').eq(i).val();
			if(Number(v) > Number(od)){
				split = od;
				t.val(split);
			}
			if(v == ""){
				v = 0;
			}
			split_sum = parseInt(v)+parseInt(split_sum);
		});

		if(split_sum >= total_sum){

			split=total-1;
			t.val(split);
		}
		if(sum<1){
			alert('su');
			split = total-1;
			t.val(split);
		}
		var new_total = parseFloat(total) - parseFloat(split);
		if(new_total < 0){
			new_total = 0;
		}
		t.parents('tr').find('td:nth-child(5)').find('input[name="old_total[]"]').val(new_total);

		$('.new tr td:nth-child(1)').find('input[name="new_id[]"]').each(function(){
			var v = $(this).val();
			if(v == id){
				$(this).parents('tr').find('input[name="new_total[]"]').val(split);
			}
		});
	});
	flags = false;
	$(".sur").click(function(){
		$('input[name="select_one"]').each(function(){
			if(this.checked==true){
				flags = true;
			}
		});
		if(flags == false){
			alert("请选择一个订单!");
		}

	})
})

function fun(){
	return flags;
}