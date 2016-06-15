$(function () {
	areaSelect.createSelect('.supplierMsg .shengDiv','.supplierMsg .shiDiv','.supplierMsg .xianDiv','<label class="margin_left_1">省份：</label>','<label>市（区）：</label>','<label>区（县）：</label>');
	
	$('.supplierMsg .sheng option').each(function (index, value) {
		if ($(value).val() == sheng) {
			$(value).attr('selected', 'selected');
			$('.supplierMsg .sheng').change();
		}
	});
	$('.supplierMsg .shi option').each(function (index, value) {
		if ($(value).val() == shi) {
			$(value).attr('selected', 'selected');
			$('.supplierMsg .shi').change();
		}
	});
	$('.supplierMsg .xian option').each(function (index, value) {
		if ($(value).val() == xian) {
			$(value).attr('selected', 'selected');
			$('.supplierMsg .xian').change();
		}
	});
	
	//全选
	$('.goodsMsg input[name="select_all"]').click(function () {
	    if(this.checked){   
    		$('.goodsMsg input[name="select_one"]').each(function () {
    			$(this).prop("checked",true);
    		});
	    }else{   
    		$('.goodsMsg input[name="select_one"]').each(function () {
    			$(this).prop("checked",false);
    		});
	    }
	});
	
	//添加删除
	//这里弄一段比较通用的代码
	$('.goodsMsg table tr').each(function (index, value) {
		if (index > 0) {
			$(value).find('td').eq(1).html(index);
		}
	});
	var trStr = $('.goodsMsg table tr').eq(1).prop("outerHTML");
	$('.goodsAdd').click(function () {
		$('.goodsMsg table').append(trStr);
		$('.goodsMsg table tr').last().find('input[type="text"]').val('');
		//重置下标
		$('.goodsMsg table tr').each(function (index, value) {
			if (index > 0) {
				$(value).find('td').eq(1).html(index);
			}
		});
	});
	$('.goodsDelete').click(function () {
		$('.goodsMsg input[name="select_one"]:checked').parent().parent().remove();
		$('.goodsMsg table tr').each(function (index, value) {
			if (index > 0) {
				$(value).find('td').eq(1).html(index);
			}
		});
	});
	
	$('.form-inline').validate({
		highlight:function (element,errorClass) {
			$(element).addClass('error_color');
			$(element).tooltip('show');
		},
		
		unhighlight:function (element,errorClass) {
			$(element).removeClass('error_color');
			$(element).tooltip('hide');
		},
		rules:{
			name:{
				required:true,
			},
		},
		messages:{
			name:{
				required:'',
			},
		}
	});
	
	$('[data-toggle="tooltip"]').tooltip('hide');
	
});
