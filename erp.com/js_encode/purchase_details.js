$(function () {
	//全选
	$('.table-hover input[name="select_all"]').click(function () {
	    if(this.checked){   
    		$('.table-hover input[name="select_one"]').each(function () {
    			$(this).prop("checked",true);
    		});
	    }else{   
    		$('.table-hover input[name="select_one"]').each(function () {
    			$(this).prop("checked",false);
    		});
	    }
	});
	
	//添加
	//这里弄一段比较通用的代码
	$('.goodsMsg table tr').each(function (index, value) {
		if (index > 0) {
			$(value).find('td').eq(1).html(index);
		}
	});
	var trStr = $('.table-hover tr').eq(1).prop("outerHTML");
	$('.customersAdd').click(function () {
		$('.table-hover').append(trStr);
		//重置下标
		$('.table-hover tr').each(function (index, value) {
			if (index > 0) {
				$(value).find('td').eq(0).html(index);
			}
		});
	});
	
	
	
});
