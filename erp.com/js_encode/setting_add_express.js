$(function(){
	areaSelect.createSelect('.supplierMsg .shengDiv','.supplierMsg .shiDiv','.supplierMsg .xianDiv','<label class="margin_left_2">省份：</label>','<label>市（区）：</label>','<label>区（县）：</label>');
	$('[data-toggle="tooltip"]').tooltip('hide');

	$(".post").keyup(function(){
		var value = $(this).val();
		$.ajax({
        	'url': '/setting/setting_add_express.php',
        	'async':true,
        	'type': "POST",
        	'data': {"aa":value},
        	'dataType': 'json',
        	'success': function(data){
        		var option = '';
        		if(data){
					for(var i = 0; i < data.length; i++){
						option += '<option value='+data[i]['id']+'>'+data[i]['name']+'</option>'
					}

					$(".wre").html(option);
				}
			}

      });
	});

	$(".wre").change(function(){
 		$(this).removeClass("error_color");
		$(this).attr('data-original-title','必选');
		$(this).tooltip('hide');
		var bb = $(this).children("option:selected").text();
		$(".post").val(bb);
	});


	// $('.form-inline').validate({
	// 	highlight:function (element,errorClass) {
	// 		$(element).addClass('error_color');
	// 		$(element).tooltip('show');
	// 	},

	// 	unhighlight:function (element,errorClass) {
	// 		$(element).removeClass('error_color');
	// 		$(element).tooltip('hide');
	// 	},
	// 	rules:{
	// 		name:{
	// 			required:true,
	// 		},
	// 		express_id:{
	// 			required:true,
	// 		}
	// 	},
	// 	messages:{
	// 		name:{
	// 			required:'',

	// 		},
	// 		express_id:{
	// 			required:'',
	// 		},
	// 	}
	// });

	$('.input-sm').focus(function(){
		$(this).tooltip('hide');
		$(this).removeClass('error_color');
	});


	$("input[name=send]").click(function(){
		if(!$(".wre").val()){
			$(".wre").addClass('error_color');
			$(".wre").tooltip('show');
			return false;
		}

		if(!$("input[name=name]").val()){
			$("input[name=name]").addClass('error_color');
			$("input[name=name]").tooltip('show');
			return false;
		}

		$.ajax({
			url: '/setting/setting_add_express.php',
			type: 'POST',
			data: $('form').serialize(),
		})
		.done(function(data) {
			if(data == "exit"){
				$(".wre").addClass('error_color');
				$(".wre").attr('data-original-title','存在');
				$(".wre").tooltip('show');
			}else if(data == 1){
				parent.window.location.reload(true);
				alert('添加完成！');
				parent.$('#MessageBox').modal('hide');
			}else if(data == 'no'){
				alert("添加失败！");
			}
		})
		// echo "<script>\n";
	// echo ";";
	// echo "var index = parent.layer.getFrameIndex(window.name);\n";
	// echo "parent.layer.close(index);\n";
	// echo "</script>\n";
	// echo "";
	});

})