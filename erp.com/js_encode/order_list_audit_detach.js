$(function () {
	$('.split input[name="select_all"]').click(function () {
	    if(this.checked){   
    		$('.split input[name="select_one"]').each(function () {
    			$(this).prop("checked",true);
    		});
	    }else{   
    		$('.split input[name="select_one"]').each(function () {
    			$(this).prop("checked",false);
    		});
	    }
	});





	//表格5全选
	$('.orders input[name="select_all"]').click(function () {
	    if(this.checked){   
    		$('.orders input[name="select_one"]').each(function () {
    			$(this).prop("checked",true);
    		});
	    }else{   
    		$('.orders input[name="select_one"]').each(function () {
    			$(this).prop("checked",false);
    		});
	    }
	});


	// 表格5删除
	var arr = [];
	var zong = [];
	$('.ordersDelete').click(function () {
	
		$('.orders input[name="select_one"]').each(function(){
			var attr = $(this).prop('checked');
			if(attr){
				var id = $(this).parents('tr').find('td:nth-child(1)').find('input[name="product_id[]"]').val();
				var total = $(this).parents('tr').find('td:nth-child(5)').find('input[name=new_total]').val();
				arr.push(id);
				zong.push(total);
				$(this).parent().parent().remove();
			}
			
		});

		$('.old input[name="product_id[]"]').each(function(){
			var v = $(this).val();
			for(var i=0;i<arr.length;i++){
				if(arr[i] == v){
					var old_t = $(this).parents('tr').find('td:nth-child(6)').find('input[name="old_total[]"]');
					var tol = old_t.val();
					old_t.val(parseInt(tol)+parseInt(zong[i]));
				}
			}
		})

		// $('.orders  input[name="select_one"]:checked').parent().parent().remove();
		$('.orders tr').each(function (index, value) {
			if (index > 0) {
				$(value).find('td').eq(0).html(index);
			}
		});
	});

	$('.old').on('keyup','input[name="split[]"]',function(){
		var	t = $(this);
		var id = t.parents('tr').find('input[name="product_id[]"]').val();
		var split = t.val().replace(/[^0-9]/ig,"");
		var total = t.parents('tr').find('input[name=total]').val();
		if(split == ""){
			split = 0;
		}

		var sum = 0;
		var split_sum = 0;
		var total_sum = 0;
		//商品总数量(固定不变)
		$('.old tr').find('input[name="total"]').each(function(){
			var v = $(this).val().replace(/[^0-9]/ig,"");
			total_sum = parseInt(v)+parseInt(total_sum);
		});

		//商品总数量(拆分后数量)
		$('.old tr').find('input[name="old_total[]"]').each(function(){
			var v = $(this).val().replace(/[^0-9]/ig,"");
			sum = parseInt(v)+parseInt(sum);
		});
		//拆分总数量
		$('.old tr').find('input[name="split[]"]').each(function(){
			var v = $(this).val().replace(/[^0-9]/ig,"");
			if(v == ""){
				v = 0;
			}
			split_sum = parseInt(v)+parseInt(split_sum);
		});

		if(split_sum >= total_sum){
		
			split=total-1;
			t.val(split);
		}
		if(sum<1){
			alert('su');
			split = total-1;
			t.val(split);
		}
		var new_total = parseFloat(total) - parseFloat(split);
		if(new_total < 0){
			new_total = 0;
		}
		t.parents('tr').find('td:nth-child(5)').find('input[name="old_total[]"]').val(new_total);

		$('.new tr td:nth-child(1)').find('input[name="new_id[]"]').each(function(){
			var v = $(this).val();
			if(v == id){
				$(this).parents('tr').find('input[name="new_total[]"]').val(split);
			}
		});	
	
	
	});

});




