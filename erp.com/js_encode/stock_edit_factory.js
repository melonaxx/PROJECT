$(function(){
	areaSelect.createSelect('.supplierMsg .shengDiv','.supplierMsg .shiDiv','.supplierMsg .xianDiv','<label class="margin_left_2">省份：</label>','<label>市（区）：</label>','<label>区（县）：</label>');
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
	
	$('.form-inline').validate({
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
})