$(function () {
	
	$('#confirm').modal('hide');

	// 搜索框鼠标滑过选中
	$('.input_search').mouseover(function(){
		this.select();
	});
	
	//全选
	$('.customersMsg input[name="select_all"]').click(function () {
	    if(this.checked){   
    		$('.customersMsg input[name="select_one"]').each(function () {
    			$(this).prop("checked",true);
    		});
	    }else{   
    		$('.customersMsg input[name="select_one"]').each(function () {
    			$(this).prop("checked",false);
    		});
	    }
	});
	
	//添加删除
	//这里弄一段比较通用的代码
//	var trStr = $('.goodsMsg table tr').eq(1).prop("outerHTML");
//	$('.goodsAdd').click(function () {
//		$('.goodsMsg table').append(trStr);
//		//重置下标
//		$('.goodsMsg table tr').each(function (index, value) {
//			if (index > 0) {
//				$(value).find('td').eq(1).html(index);
//			}
//		});
//	});

		
	//新增
	$('.customersAdd').click(function () {
		location.href = '/crm/crm_add_customer.php';
	});

	
	$('.customersDelete').click(function () {
		$('.customersMsg input[name="select_one"]:checked').parent().parent().remove();
		$('.customersMsg table tr').each(function (index, value) {
			if (index > 0) {
				$(value).find('td').eq(0).html(index);
			}
		});
	});

	

	//单个删除
	$('.table .delete').click(function () {
		$('#confirm').modal('show');
		var thisHref = $(this).attr('href');
		$('#confirm .ok').click(function () {
			location.href = thisHref;
		});
		return false;
	});
	
	
	
	
});
