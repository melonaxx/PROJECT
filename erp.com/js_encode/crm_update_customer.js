$(function(){
	areaSelect.createSelect('.customerMsg .shengDiv','.customerMsg .shiDiv','.customerMsg .xianDiv','<label class="margin_left_2">省份：</label>','<label>市（区）：</label>','<label>区（县）：</label>');
	
	$('.customerMsg .sheng option').each(function (index, value) {
		if ($(value).val() == sheng) {
			$(value).attr('selected', 'selected');
			$('.customerMsg .sheng').change();
		}
	});
	$('.customerMsg .shi option').each(function (index, value) {
		if ($(value).val() == shi) {
			$(value).attr('selected', 'selected');
			$('.customerMsg .shi').change();
		}
	});
	$('.customerMsg .xian option').each(function (index, value) {
		if ($(value).val() == xian) {
			$(value).attr('selected', 'selected');
			$('.customerMsg .xian').change();
		}
	});

	// 发票信息
	var tax_Ok = $("input[name=tax_Ok]").val();
	if(tax_Ok==1){
		$(".ticketMsg").show();
		$('.customerMsg .type option').each(function (index, value) {
		if ($(value).val() == 0) {
			$(value).attr('selected', 'selected');
			$('.customerMsg .type').change();
		}
	});
	}

	$('.customerMsg .type').change(function () {
		if ($('.customerMsg .type option:selected').val() == 0) {
			$('.ticketMsg').show();
		} else {
			$('.ticketMsg').hide();
		}
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