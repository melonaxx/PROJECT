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
			express_id:{
				required:true,
			}
		},
		messages:{
			name:{
				required:'',
			},
			express_id:{
				required:'',
			},
		}
	});
	$(".post").keyup(function(){
		var value = $(this).val();
		$.ajax({
        	'url': '/setting/setting_edit_express.php',
        	'async':true,
        	'type': "GET",
        	'data': {"aa":value},
        	'dataType': 'json',
        	'success': function(data){
        	var option = '';
			for(var i = 0; i < data['expressInfo'].length; i++){
				option += '<option value='+data['expressInfo'][i]['id']+'>'+data['expressInfo'][i]['name']+'</option>'
			}
			$(".wre").html(option);

			var option2 = '';
			for(var j=0; j< data['templateInfo'].length; j++){
				option2 += '<option value='+data['templateInfo'][j]['id']+'>'+data['templateInfo'][j]['name']+'</option>'
			}	
			$("select[name=template_id]").html(option2);				
		}
		
      });
	});

	 $(".wre").change(function(){
			var bb = $(this).children("option:selected").text();
			var express_id = $(this).val();
			$(".post").val(bb);

			$.ajax({
	        	'url': '/setting/setting_edit_express.php',
	        	'async':true,
	        	'type': "GET",
	        	'data': {"bb":1,"express_id":express_id},
	        	'dataType': 'json',
	        	'success': function(data){

		        	var option = '';
					for(var i = 0; i < data.length; i++){
						option += '<option value='+data[i]['id']+'>'+data[i]['name']+'</option>'
					}
						$("select[name=template_id]").html(option);

				}
					
			});
	  })
})