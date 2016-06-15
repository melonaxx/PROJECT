$(function(){
	areaSelect.createSelect('.supplierMsg .shengDiv','.supplierMsg .shiDiv','.supplierMsg .xianDiv','<label class="margin_left_2">省份：</label>','<label>市（区）：</label>','<label>区（县）：</label>');
	
	$('.form-inline').validate({
		 // highlight:function(element,errorClass) {
			 // $(element).addClass('error_color');
			 // $(element).tooltip('show');
		 // },
		
		  // unhighlight:function (element,errorClass) {
			  // $(element).removeClass('error_color');
			  // $(element).tooltip('hide');
		  // },
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
	// $('[data-toggle="tooltip"]').tooltip('hide');
	// $(".fixed").keydown(function(){
		// var val = $(this).val();
		// if(val != //)
	// })
})