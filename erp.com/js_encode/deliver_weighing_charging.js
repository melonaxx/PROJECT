$(function(){
	$('.smallbox .bian').focus();
	$(".start").click(function(){
		var v = $('.smallbox input[name=source]').val();
		$('.smallbox input[name=price]').val(v)
		// if(text == '人工计算'){
			// $(this).text('自动计算');
			// $('.smallbox input[name=price]').attr('disabled',true);
			// $('.smallbox input[name=weight]').attr('disabled',true);

		// }else{
			// $(this).text('人工计算');
			// $('.smallbox input[name=price]').attr('disabled',false);
			// $('.smallbox input[name=weight]').attr('disabled',false);
		// }
	});

	//编号输入
	$('.bian').keyup(function(e){
		if(e.keyCode == 13){
			var v = $(this).val();
			var tiao = $('.tiao').val();
			$.ajax({
				url:'deliver_weighing_charging.php',
				type:'post',
				data:{'tiao':tiao,'val':v},
				dataType:'json',
				success:function(data){
					if(data){
						$('.del .parent').remove();
						$('.smallbox input[name=source]').val(data['fee']);
						$('.box input[name=bind_number]').val(data['bind_number']);
						$('.box input[name=express]').val(data['express_name']+","+data['number']);
						$('.box input[name=name]').val(data['name']+","+data['mobile']);
						$('.box input[name=customer_text]').val(data['customer_text']);
						$('.box input[name=order_text]').val(data['order_text']);
						if(data['weight']>0){
							$('.weight_num').html("("+data['weight']+"kg)");
						}
						
						var  str = '';
						for(var i=0;i < data['product'].length;i++){
							str += '<tr style="height:28px;" class="parent"><td style="text-align:center">'+data['product'][i]['num']+'</td><td></td><td></td><td>'+data['product'][i]['name']+','+data['product'][i]['format']+'</td><td></td><td>'+data['product'][i]['total']+'</td></tr>';
						}
						$('.box table tr').last().before(str);
						$('.heji').find('td:nth-child(3)').find('.zong').html(data['zong']);
						$('.heji').find('td:nth-child(3)').find('input[name=order_id]').val(data['id']);
						$('.heji').find('td:nth-child(3)').find('input[name=state_id]').val(data['state_id']);
						$('.heji').find('td:nth-child(3)').find('input[name=express_id]').val(data['express_id']);
						$('.del .heji').find('td:nth-child(3)').find('input[name=store_id]').val(data['product'][0]['store_id']);
						$('.bian').val('');
						$('.smallbox input[name=weight]').focus();
					}

				}
			})
		}else{
			return false;
		}
	});

	//手动输入
	$('.bian').blur(function(){
		var v = $(this).val();
		var tiao = $('.tiao').val();
		$.ajax({
			url:'deliver_weighing_charging.php',
			type:'post',
			data:{'tiao':tiao,'val':v},
			dataType:'json',
			success:function(data){
				if(data){
					$('.del .parent').remove();
					$('.smallbox input[name=source]').val(data['fee']);
					$('.box input[name=bind_number]').val(data['bind_number']);
					$('.box input[name=express]').val(data['express_name']+","+data['number']);
					$('.box input[name=name]').val(data['name']+","+data['mobile']);
					$('.box input[name=customer_text]').val(data['customer_text']);
					$('.box input[name=order_text]').val(data['order_text']);
					if(data['weight']>0){	
						$('.weight_num').html("("+data['weight']+"kg)");
					}
					var  str = '';
					for(var i=0;i < data['product'].length;i++){
						str += '<tr style="height:28px;" class="parent"><td style="text-align:center">'+data['product'][i]['num']+'</td><td></td><td></td><td>'+data['product'][i]['name']+','+data['product'][i]['format']+'</td><td></td><td>'+data['product'][i]['total']+'</td></tr>';
					}
					$('.box table tr').last().before(str);
					$('.heji').find('td:nth-child(3)').find('.zong').html(data['zong']);
					$('.heji').find('td:nth-child(3)').find('input[name=order_id]').val(data['id']);
					$('.heji').find('td:nth-child(3)').find('input[name=state_id]').val(data['state_id']);
					$('.heji').find('td:nth-child(3)').find('input[name=express_id]').val(data['express_id']);
					$('.del .heji').find('td:nth-child(3)').find('input[name=store_id]').val(data['product'][0]['store_id']);
					$('.bian').val('');
					$('.smallbox input[name=weight]').focus();
				}

			}
		})
	})

	//打回审核
	$('.shen').click(function(){
		var id = $('.heji').find('td:nth-child(3)').find('input[name=order_id]').val();
		if(id){
			$('#confirm .modal-body').html("您确定要打回审核吗?");
			$('#confirm').modal('show');
			$('.ok').click(function(){
				$.ajax({
					url:'deliver_weighing_charging.php',
					type:'get',
					dataType:'json',
					data:{'id':id},
					success:function(data){
						if(data == 1){
							window.location.href="deliver_weighing_charging.php";
							$('.bar').focus();
						}else{
							alert("打回审核失败");
						}

					}
				})
			})
		}

	});

	//打回配货
	$('.pei').click(function(){
		var id = $('.heji').find('td:nth-child(3)').find('input[name=order_id]').val();
		if(id){
			$('#confirm .modal-body').html("您确定要打回配货吗?");
			$('#confirm').modal('show');
			$('.ok').click(function(){
				$.ajax({
					url:'deliver_weighing_charging.php',
					type:'get',
					dataType:'json',
					data:{'pei_id':id},
					success:function(data){
						if(data == 1){
							window.location.href="deliver_weighing_charging.php";
						}else{
							alert('打回配货失败');
						}

					}
				})
			})
		}
	});

	//确认称重
	$('.cheng').click(function(){
		var id = $('.heji').find('td:nth-child(3)').find('input[name=order_id]').val();
		var price = $('.smallbox input[name=price]').val();
		if(id){
			$.ajax({
				url:'deliver_weighing_charging.php',
				type:'get',
				dataType:'json',
				data:{'cheng_id':id,'price':price},
				success:function(data){
					num = 5;
					inte = null;
					if(data == '1'){
						inte = setInterval(function(){
							$('#confirm .modal-body').html("称重成功("+ num-- +")");
							$('#confirm').modal('show');

							$('.ok').click(function(){
								clearInterval(inte);
								$('#print-menu').hide();
								window.location.href="deliver_weighing_charging.php";
							})
							if(num <= 0){
								clearInterval(inte);
								$('#print-menu').hide();
								window.location.href="deliver_weighing_charging.php";
							}
						},1000);
					}else if(data == '0'){
						inte = setInterval(function(){
							$('#confirm .modal-body').html("称重失败("+ num-- +")");
							$('#confirm').modal('show');

							$('.ok').click(function(){
								clearInterval(inte);
								$('#print-menu').hide();
								window.location.href="deliver_weighing_charging.php";
							})
							if(num <= 0){
								clearInterval(inte);
								$('#print-menu').hide();
								window.location.href="deliver_weighing_charging.php";
							}
						},1000);
					}

				}
			})
		}else{
			$('#confirm .modal-body').html("请先选择订单");
			$('#confirm').modal('show');
			$('#print-menu').hide();
			return false;
		}

	});

	//添加订单至异常订单
	$('.yichang').click(function(){
		var id = $('.heji').find('td:nth-child(3)').find('input[name=order_id]').val();
		if(id){
			MessageBox('/deliver/deliver_add_unusual.php?id='+id, '异常原因',412,185);
			return false;
		}

	})

	//称重计费
	$('input[name=weight]').blur(function(){
		var weight 		= $(this).val();
		var order_id 	= $('.heji').find('td:nth-child(3)').find('input[name=order_id]').val();
		var store_id 	= $('.heji').find('td:nth-child(3)').find('input[name=store_id]').val();
		var state_id 	= $('.heji').find('td:nth-child(3)').find('input[name=state_id]').val();
		var express_id 	= $('.heji').find('td:nth-child(3)').find('input[name=express_id]').val();
		$.ajax({
			url:'deliver_weighing_charging.php',
			data:{'weight':weight,'order_id':order_id,'store_id':store_id,'state_id':state_id,'express_id':express_id},
			dataType:'json',
			type:'get',
			success:function(data){
				if(data){

					$('.smallbox input[name=price]').val(data);
				}
			}
		})

	})

})