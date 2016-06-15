$(function () {
	$('.table-Goods input[name="select_all"]').click(function () {
	    if(this.checked){
    		$('.table-Goods input[name="select_one"]').each(function () {
    			$(this).prop("checked",true);
    		});
	    }else{
    		$('.table-Goods input[name="select_one"]').each(function () {
    			$(this).prop("checked",false);
    		});
	    }
	});

	$('.goodsDeleteaa').click(function () {
	$('.table-Goods input[name="select_one"]:checked').parent().parent().remove();
		$('.table-Goods tr').each(function (index, value) {
			if (index > 0) {
				$(value).find('td').eq(0).html(index);
			}
		});
	});

	//添加商品
	var trStr = $('.table-Goods tr').eq(1).prop("outerHTML");
	$('.goodsAdd').click(function(){
		$('.table-Goods').append(trStr);
		$('.table-Goods tr').each(function (index, value) {
			if (index > 0) {
				$(value).find('td').eq(0).html(index);
			}
		});
	})
	var talb=$('.table-Goods tr').eq(1).clone(true);
	$('.customersAdd').click(function () {
		$('.table-Goods tr').each(function (index, value) {
			if (index > 0) {
				$(value).find('td').eq(0).html(index);
			}
		});
	});
	$(document).on("keyup",".seach",function(){
		var t = $(this);
		var value = t.val();
		$.ajax({
			type:'POST',
			url:'/product/product_commodity_assembly.php',
			'async':true,
			'data':{"value":value},
			'dataType':'json',
			'success':function(data){
				for(var i = 0;i<data.length;i++){
					$(".product").each(function(i){
						if(data[i]['id']==$(this).val()){
							data="";
						}else{
							var option = '';
							for(var i = 0;i<data.length;i++){
								option += '<option value='+data[i]['id']+'>'+data[i]['name']+','+data[i]['value_id_1']+','+data[i]['value_id_2']+','+data[i]['value_id_3']+','+data[i]['value_id_4']+','+data[i]['value_id_5']+'</option>';
							}
							t.parent().next().find('select:first').html(option);
							t.parent().next().next().html(data[0]['price_display']);
							t.parent().next().next().next().next().html(data[0]['parts_id']);
						}
					})
				}
				
			}
		});
	});
	$(document).on("change",".product",function(){
		var v = $(this);
		var select_value = v.val();
		$.ajax({
			type:'POST',
			url:'/product/product_commodity_assembly.php',
			'async':true,
			'data':{"select_value":select_value},
			'dataType':'json',
			'success':function(data){
				v.parent().next().next().next().html(data['parts_id']);
				v.parent().next().html(data['price_display']);
				// v.parent().next().next().next().html(data['price_combination']);
			}
		})
	});
	$(".form-inline").validate({
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
	$("input[name='send']").click(function(){
		aa = true;
		var max = 0;
		$("select[name='product[]']").each(function(){
			if($(this).val() != null){
				max += $(this).length;
			}
			// else{
			// 	aa = "false";
			// }
		});
		// if(aa == "false"){
		// 	alert("组合的商品或数量不正确1，请重新输入！");
		// 	return false;
		// }
		var sum = 0;
		$("input[name='total[]']").each(function(){
			if($(this).val().length != 0 && $(this).val() >=1){
				sum += $(this).length;
			}

		});
		//alert(max+"/"+sum)
		if(max == 1 || sum == 1){
			if($(".sl").val()<2){
				alert("组合的商品或数量不正确，请重新输入！");
				return false;
			}
		}else{
			var maxed = 0;
			$("input[name='total[]']").each(function(){
				if($(this).val().length != 0 && $(this).val() != 0){
					maxed += $(this).length;
				}

			});
			if(maxed < 2 || sum < 1){
				alert("组合的商品或数量不正确，请重新输入！");
				return false;
			}
		}

		var zhp = $(".zhsj").val();
		if(zhp.length<1 || zhp==0){
			alert("组合商品的组合价不正确，请重新输入");
			return false;
		}
		// var ln = $(".seach").length;
		// for(var i=0;i<ln;i++){
		// 	$(".grp_pri").each(function(i){
		// 		bb = true;
		// 		if($(this).val() == ""){
		// 			bb = "false";
		// 			return false;
		// 		}
		// 	})
		// }

		// if(bb == "false"){
		// 	alert("子商品的组合单价不正确，请重新输入！");
		// 	return false;
		// 	bb = "true";
		// }


	})

	// $(document).on('keyup', '.grp_pri', function() {
 //   		var len = $(".grp_pri").length;
 //   		var t = 0;
 //   		for(var i=0;i<len;i++){
 //    		$(".grp_pri").each(function(i){
	// 			var m = $(this).val();
 //   				var n = $(this).parent().prev().children().val();
 //   				t += m*n
	// 		})
 //    	}
 //    	$(".lsj").val(t/len);
 //    });

	$(document).on('keyup', '.sl', function() {
   		var len = $(".sl").length;
   		var t = 0;
    		$(".sl").each(function(){
				var m = $(this).val();
   				var n = $(this).parent().prev().html();
   				t += m*n
			})
    	$(".lsj").val(t);
    });

    $(".zhsj").keyup(function(){
    	this.value = this.value.replace(/[^\d.]/g,"");
		this.value = this.value.replace(/^\./g,"");
		this.value = this.value.replace(/\.{2,}/g,".");
		this.value = this.value.replace(".","$#$").replace(/\./g,"").replace("$#$",".");
    });
});