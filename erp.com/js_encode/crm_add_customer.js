$(function () {
	areaSelect.createSelect('.customerMsg .shengDiv','.customerMsg .shiDiv','.customerMsg .xianDiv','<label class="margin_left_2">省份：</label>','<label>市（区）：</label>','<label>区（县）：</label>');
	
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
