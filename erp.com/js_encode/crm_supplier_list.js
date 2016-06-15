$(function () {
	$('#confirm').modal('hide');
	//全选
	$('.supplierMsg input[name="select_all"]').click(function () {
	    if(this.checked){   
    		$('.supplierMsg input[name="select_one"]').each(function () {
    			$(this).prop("checked",true);
    		});
	    }else{   
    		$('.supplierMsg input[name="select_one"]').each(function () {
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
	
	$('.supplierMsg table tr').each(function (index, value) {
		if (index > 0) {
			$(value).find('td').eq(0).html(index);
		}
	});
	$('.supplierDelete').click(function () {
		var length = $('.supplierMsg input[name="select_one"]:checked').length;
		
		if (length > 0) {
			$('#confirm .number').html(length);
			$('#confirm').modal('show');
			$('#confirm .ok').click(function(){
				var idArr = [];
				$('.supplierMsg input[name="select_one"]:checked').each(function(index, value){
					idArr.push($(this).val());
				});
				//alert(idArr);
				location.href = '/crm/crm_supplier_list.php?m=deleteAll&idArr=' + idArr.join(',');
			});
		}
	});
	
	//新增
	$('.supplierAdd').click(function () {
		location.href = '/crm/crm_supplier_add.php';
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


