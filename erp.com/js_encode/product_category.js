$(function () {
	//window.basicWidth = 24;
	//分类
	$('.sortMsg .table tr').each(function (index, value) {
		if (index > 0) {
			if ($(value).attr('level') == 1) {
				window.tempId = $(value).attr('sort_id');
			} else {
				$(value).addClass('parent_' + window.tempId);
			}
			//alert(tempId);
		}
		
	});
	//收起
	$('.sortMsg .table').on('click', '.packup', function () {
		var sortId = $(this).parent().parent().attr('sort_id');
		$('.sortMsg .parent_' + sortId).hide();
		$(this).hide();
		$(this).parent().find('.unfold').show();
	});
	//展开
	$('.sortMsg .table').on('click', '.unfold', function () {
		var sortId = $(this).parent().parent().attr('sort_id');
		$('.sortMsg .parent_' + sortId).show();
		$(this).hide();
		$(this).parent().find('.packup').show();
	});
	
//	$('.sortMsg .table tr').each(function (index, value) {
//		var level = $(this).attr('level');
//		$(value).find('.sort_name').css('margin-left', (level - 1) * window.basicWidth + 'px');
//	});
	
	//删除
	$('.sortMsg .sort_delete').click(function () {
		$('#confirm .modal-body').html('您确定删除所选分类吗？');
		$('#confirm').modal('show');
		var thisHref = $(this).attr('this_href');
		
		$('#confirm .ok').unbind("click");
		$('#confirm .ok').click(function () {
			$('#confirm').modal('hide');
			$.ajax({
				type: 'post',
				url: thisHref,
				success: function(response, status, xhr){
					//alert(response);
					if (response == 'true') {
						location.reload();
					} else {
						alert('当前分类有子分类，不能删除');
					}
				}
			});
		});
	});
	
	
	
	
	
	//品牌
	$('.brandMsg .table tr').each(function (index, value) {
		if (index > 0) {
			if ($(value).attr('level') == 1) {
				window.tempId = $(value).attr('brand_id');
			} else {
				$(value).addClass('parent_' + window.tempId);
			}
			//alert(tempId);
		}
		
	});
	//收起
	$('.brandMsg .table').on('click', '.packup', function () {
		var brandId = $(this).parent().parent().attr('brand_id');
		$('.brandMsg .parent_' + brandId).hide();
		$(this).hide();
		$(this).parent().find('.unfold').show();
	});
	//展开
	$('.brandMsg .table').on('click', '.unfold', function () {
		var brandId = $(this).parent().parent().attr('brand_id');
		$('.brandMsg .parent_' + brandId).show();
		$(this).hide();
		$(this).parent().find('.packup').show();
	});
	
	//删除
	$('.brandMsg .brand_delete').click(function () {
		$('#confirm .modal-body').html('您确定删除所选品牌吗？');
		$('#confirm').modal('show');
		var thisHref = $(this).attr('this_href');
		
		$('#confirm .ok').unbind("click");
		$('#confirm .ok').click(function () {
			$('#confirm').modal('hide');
			$.ajax({
				type: 'post',
				url: thisHref,
				success: function(response, status, xhr){
					//alert(response);
					if (response == 'true') {
						location.reload();
					} else {
						alert('当前品牌有子品牌，不能删除');
					}
				}
			});
		});
	});
	
});
