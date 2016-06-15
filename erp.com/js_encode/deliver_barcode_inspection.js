$(function(){
	$('.bian').focus();
	$(".start").click(function(){
		var text = $(this).text();
		if(text == '验子商品条码开启'){
			$(this).text('验子商品条码关闭');
			$('.smallbox .bar').focus();
			$('.del tr').find('.product').each(function(){
				var t   = $(this);
				var v   = t.val();
				var num = t.parents('tr').find('td:nth-child(4)').html();
				$.ajax({
					url:'deliver_barcode_inspection.php',
					data:{'product_id':v,'num':num},
					dataType:'json',
					type:'get',
					success:function(data){
						if(data){
							var  str = '';
							for(var i=0;i < data['product'].length;i++){
								str += '<tr class="'+data['product'][i]['product_id']+' son" ><td style="text-align:center"></td><td>尚未验货</td><td><input type="text" name="yiyan" class="form-control input-sm" style="width:60px" /></td><td>'+data['product'][i]['total']+'</td><td></td><td> <input type="hidden" class="product" value="'+data['product'][i]['product_id']+'"/> '+data['product'][i]['name']+','+data['product'][i]['format']+'</td><td></td><td>'+data['product'][i]['bar_code']+'</td></tr>';
							}
							t.parents('tr').after(str);
							var	sum = 0;
							$('.del .son').each(function(){
								var  number = $(this).find('td:nth-child(4)').html();
								sum  = parseInt(sum)+parseInt(number);
							})
							$('.heji').find('td:nth-child(4)').html(sum);
							$('.del').find('input[name=yan]').val('');
							$('.heji').find('td:nth-child(3)').html('');
							$('.strong').html("强制通过");
						}
					}
				})
			})

		}else{
			$(this).text('验子商品条码开启');
			$('.del .son').remove();
			var	sum_true = 0;
			$('.del tr').find('td:nth-child(6)').each(function(){
				var v = $(this).parents('tr').find('td:nth-child(4)').html();
				sum_true = parseInt(v) + parseInt(sum_true);
			})
			$('.heji').find('td:nth-child(4)').html(sum_true);
			var sum_yiyan = 0;
			$('.del').find('input[name=yan]').each(function(){
				var v = $(this).parents('tr').find('input[name=yan]').val();
				var sure = $(this).parents('tr').find('td:nth-child(4)').html();
				if(v == ""){
					v = 0;
				}
				if(parseInt(v) == parseInt(sure)){
					$(this).parents('tr').find('td:nth-child(2)').html("验货完成");
				}else if(parseInt(v) < parseInt(sure) && parseInt(v) > 0){
					$(this).parents('tr').find('td:nth-child(2)').html("部分验货");
				}else{
					$(this).parents('tr').find('td:nth-child(2)').html("尚未验货");
				}
				sum_yiyan = parseInt(v) + parseInt(sum_yiyan);
			})
			$('.heji').find('td:nth-child(3)').html(sum_yiyan);
		}
	})
	$('.bian').keyup(function(e){
		if(e.keyCode == 13){
			var v = $(this).val();
			var tiao = $('.tiao').val();
			$.ajax({
				url:'deliver_barcode_inspection.php',
				type:'post',
				data:{'tiao':tiao,'val':v},
				dataType:'json',
				success:function(data){
					if(data){
						$('.del .parent').remove();
						$('.box input[name=bind_number]').val(data['bind_number']);
						$('.box input[name=express]').val(data['express_name']+","+data['number']);
						$('.box input[name=name]').val(data['name']+","+data['mobile']);
						$('.box input[name=customer_text]').val(data['customer_text']);
						$('.box input[name=order_text]').val(data['order_text']);
						var  str = '';
						for(var i=0;i < data['product'].length;i++){
							str += '<tr class="par_'+data['product'][i]['product_id']+' parent"><td style="text-align:center">'+ data['product'][i]['num'] +'</td><td>尚未验货</td><td><input type="text" name="yan" class="form-control input-sm" style="width:60px" /></td><td>'+data['product'][i]['total']+'</td><td></td><td> <input type="hidden" class="product" value="'+data['product'][i]['product_id']+'"/> '+data['product'][i]['name']+','+data['product'][i]['format']+'</td><td></td><td>'+data['product'][i]['bar_code']+'</td></tr>';

							// str += '<tr class="par_'+data['product'][i]['product_id']+' parent"><td style="text-align:center">'+data['product'][i]['num']+'</td><td>尚未验货</td><td></td><td>'+data['product'][i]['total']+'</td><td></td><td> <input type="hidden" class="product" value="'+data['product'][i]['procuct_id']+'" /> '+data['product'][i]['name']+','+data['product'][i]['format']+'</td><td></td><td>'+data['product'][i]['bar_code']+'</td></tr>';
						}
						$('.box table tr').last().before(str);
						$('.heji').find('td:nth-child(2)').find('input[name=order_id]').val(data['id']);
						$('.heji').find('td:nth-child(3)').find('input[name=zong]').val(data['product'].length);
						$('.heji').find('td:nth-child(4)').html(data['zong']);
						$('.bian').val('');
						$('.bar').focus();

						$('.del .parent').on('keyup','input[name=yiyan]',function(){
							alert(1);
							var aa = $(this).val().replace(/[^\d]/g,'');
							$(this).val(aa);
							var num = $(this).val();
							var par_num    = $(this).parent().next().html();
							var product_id = $(this).parents('tr').find('.product').val();
							var son_num    = $('.del .product_id').find('td:neh-child(4)').html();
							alert(num);
							$('.del .product_id').find('input[name=yiyan]').val(son_num/par_num*num);
						})
					}
				}
			})
			$('.bian').val('');

		}else{
			return false;
		}
	});
	$('.bian').blur(function(){
		var v = $(this).val();
		var tiao = $('.tiao').val();
		$.ajax({
			url:'deliver_barcode_inspection.php',
			type:'post',
			data:{'tiao':tiao,'val':v},
			dataType:'json',
			success:function(data){
				if(data){
					$('.del .parent').remove();
					$('.box input[name=bind_number]').val(data['bind_number']);
					$('.box input[name=express]').val(data['express_name']+","+data['number']);
					$('.box input[name=name]').val(data['name']+","+data['mobile']);
					$('.box input[name=customer_text]').val(data['customer_text']);
					$('.box input[name=order_text]').val(data['order_text']);
					var  str = '';
					for(var i=0;i < data['product'].length;i++){
						str += '<tr class="par_'+data['product'][i]['product_id']+' parent"><td style="text-align:center">'+ data['product'][i]['num'] +'</td><td>尚未验货</td><td><input type="text" name="yan" class="form-control input-sm" style="width:60px" /></td><td>'+data['product'][i]['total']+'</td><td></td><td> <input type="hidden" class="product" value="'+data['product'][i]['product_id']+'"/> '+data['product'][i]['name']+','+data['product'][i]['format']+'</td><td></td><td>'+data['product'][i]['bar_code']+'</td></tr>';
					}
					$('.box table tr').last().before(str);
					$('.heji').find('td:nth-child(2)').find('input[name=order_id]').val(data['id']);
					$('.heji').find('td:nth-child(3)').find('input[name=zong]').val(data['product'].length);
					$('.heji').find('td:nth-child(4)').html(data['zong']);
					$('.bian').val('');
					$('.bar').focus();
				}

			}
		})
		$('.bian').val('');

	})

	$('.bar').keyup(function(e){
		if(e.keyCode == 13){
			sum = 0;
			i=1;
			var j = $('.heji').find('td:nth-child(3)').find('input[name=zong]').val();
			var v = $(this).val();
			$('.del tr').find('td:nth-child(8)').each(function(){
				var t = $(this);
				var va = t.html();
				var product_id = t.parents('tr').find('.product').val();
				if(va == v){
					var num = t.parents('tr').find('td:nth-child(3)').find('input[name=yiyan]').val();
					var total_num = t.parents('tr').find('td:nth-child(4)').html();
					if(num == ""){
						num = 0;
					}
					zong = num++;
					t.parents('tr').find('td:nth-child(3)').find('input[name=yiyan]').val(zong);
					//修改父商品的验货状态
					var	sum_yiyan = 0;
					var sum_par   = 0;
					$('.'+product_id).find('input[name=yiyan]').each(function(){
						var v  = $(this).val();
						var va = $(this).parents("tr").find('td:nth-child(4)').html();

						if(v == ""){
							v = 0;
						}
						if(va == ""){
							va = 0;
						}
						sum_yiyan = parseInt(sum_yiyan) + parseInt(v);
						sum_par   = parseInt(sum_par) + parseInt(va);
					})
					yiyan = sum_yiyan + 1;
					$('.heji').find('td:nth-child(3)').html(sum_yiyan+1);

					if(yiyan == sum_par){
						$('.par_'+product_id).find('td:nth-child(2)').html("完成验货");
					}else if(yiyan > 0 && yiyan < sum_par){
						$('.par_'+product_id).find('td:nth-child(2)').html("部分验货");
					}else{
						$('.par_'+product_id).find('td:nth-child(2)').html("尚未验货");
					}

					//修改子商品验货状态
					if(num >= total_num){
						num = total_num;
						t.parents('tr').find('td:nth-child(3)').find('input[name=yiyan]').val(num);
						t.parents('tr').find('td:nth-child(2)').html('完成验货');
					}else if(num < total_num && num > 0){
						t.parents('tr').find('td:nth-child(3)').find('input[name=yiyan]').val(num);
						t.parents('tr').find('td:nth-child(2)').html('部分验货');
					}else{
						t.parents('tr').find('td:nth-child(3)').find('input[name=yiyan]').val(num);
						t.parents('tr').find('td:nth-child(2)').html('尚未验货');
					}

					var new_num = $('.heji').find('td:nth-child(3)').html();
					var old_num = $('.heji').find('td:nth-child(4)').html();
					if(new_num == old_num){
						id = $('.heji').find('td:nth-child(2)').find('input[name=order_id]').val();
						dis_name = $("select[name='type'] option").html();
						$.ajax({
							url:'deliver_barcode_inspection.php',
							type:'get',
							dataType:'json',
							data:{'order_id':id,'dis_name':dis_name},
							success:function(data){
								num = 5;
								inte = null;
								if(data == '1'){
									inte = setInterval(function(){
										$('#confirm .modal-body').html("完成验货("+ num-- +")");
										$('#confirm').modal('show');

										$('.ok').click(function(){
											clearInterval(inte);
											$('#print-menu').hide();
											window.location.href="deliver_barcode_inspection.php";
										})
										if(num <= 0){
											clearInterval(inte);
											$('#print-menu').hide();
											window.location.href="deliver_barcode_inspection.php";
										}
									},1000);

								}else if(data == '0'){
									inte = setInterval(function(){
										$('#confirm .modal-body').html("验货失败("+ num-- +")");
										$('#confirm').modal('show');

										$('.ok').click(function(){
											clearInterval(inte);
											$('#print-menu').hide();
											window.location.href="deliver_barcode_inspection.php";
										})
										if(num <= 0){
											clearInterval(inte);
											$('#print-menu').hide();
											window.location.href="deliver_barcode_inspection.php";
										}
									},1000);
								}else{
									$('#confirm .modal-body').html("异常订单,异常原因:"+data);
									$('#confirm').modal('show');
									$('#print-menu').hide();

									inte = setTimeout(function(){
										$('#print-menu').hide();
										window.location.href="deliver_barcode_inspection.php";
									},3000);
								}

							}
						})
					}
				}

			})
			$('.bar').val('');
			$('.bar').focus();
		}else{
			return false;
		}
	});

	//手动输入父商品已验数量
	$('.del').on('keyup','input[name=yan]',function(){
		var text = $('.start').text();
		if(text == '验子商品条码关闭'){
			var aa = $(this).val().replace(/[^\d]/g,'');
			$(this).val(aa);
			var num = $(this).val();
			var par_num    = $(this).parent().next().html();
			var product_id = $(this).parents('tr').find('.product').val();
			var end = $('.heji').find('td:nth-child(4)').html();
			if(num == ""){
				num = 0;
			}else if(parseInt(num) > parseInt(par_num)){
				num = par_num;
				$(this).val(num);
			}
			$('.'+product_id).find('td:nth-child(4)').each(function(){
				var  v = $(this).html();
				var son_num   = $(this).html();
				var resu = (son_num/par_num)*num;
				$(this).parents('tr').find('input[name=yiyan]').val(resu);
			})

			if(num == par_num){
				$(this).parents('tr').find('td:nth-child(2)').html("验货完成");
				$('.del .'+product_id).find('td:nth-child(2)').html("验货完成");
			}else if(num < par_num && num > 0){
				$(this).parents('tr').find('td:nth-child(2)').html("部分验货");
				$('.del .'+product_id).find('td:nth-child(2)').html("部分验货");
			}else if(num == 0){
				$(this).parents('tr').find('td:nth-child(2)').html("尚未验货");
				$('.del .'+product_id).find('td:nth-child(2)').html("尚未验货");
			}
			var sum = 0;
			$('.del input[name=yiyan]').each(function(){
				var v = $(this).val();
				if(v == ""){
					v = 0;
				}
				sum = parseInt(sum) + parseInt(v);
			})
			$('.heji').find('td:nth-child(3)').html(sum);

			if(sum == end){
				$('.strong').html("确认通过");
			}else{
				$('.strong').html("强制通过");
			}
		}else{
			var aa = $(this).val().replace(/[^\d]/g,'');
			$(this).val(aa);
			var num = $(this).val();
			var sum = 0;
			$('.del input[name=yan]').each(function(){
				var v = $(this).val();
				var sure = $(this).parents('tr').find('td:nth-child(4)').html();
				if(v == ""){
					v = 0;
				}
				if(parseInt(v) == parseInt(sure)){
					$(this).parents('tr').find('td:nth-child(2)').html("验货完成");
				}else if(parseInt(v) < parseInt(sure) && parseInt(v) > 0){
					$(this).parents('tr').find('td:nth-child(2)').html("部分验货");
				}else{
					$(this).parents('tr').find('td:nth-child(2)').html("尚未验货");
				}
				sum = parseInt(sum) + parseInt(v);
			})
			$('.heji').find('td:nth-child(3)').html(sum);
			var	end = $('.heji').find('td:nth-child(4)').html();
			if(sum == end){
				$('.strong').html("确认通过");
			}else{
				$('.strong').html("强制通过");
			}
		}
	})

	$('.del').on('keyup','input[name=yiyan]',function(){
		var aa = $(this).val().replace(/[^\d]/g,'');
		$(this).val(aa);
		var num = $(this).val();
		var product_id = $(this).parents('tr').attr('class').replace(/[^\d]/g,'');
		var son_num   = $(this).parent().next().html();
		var end = $('.heji').find('td:nth-child(4)').html();

		if(num == ""){
			num = 0;
		}else if(parseInt(num) > parseInt(son_num)){
			num = son_num;
			$(this).val(num);
		}

		if(num == son_num){
			$(this).parents('tr').find('td:nth-child(2)').html("验货完成");
		}else if(num < son_num && num > 0){
			$(this).parents('tr').find('td:nth-child(2)').html("部分验货");
		}else if(num == 0){
			$(this).parents('tr').find('td:nth-child(2)').html("尚未验货");
		}


		var sum_son   = 0;
		var sum_yiyan = 0;
		$('.'+product_id).find('input[name=yiyan]').each(function(){
			var  v   = $(this).val();
			var son  = $(this).parent().next().html();
			if(v == ""){
				v = 0;
			}
			sum_son   = parseInt(sum_son) + parseInt(son);
			sum_yiyan = parseInt(sum_yiyan) + parseInt(v);
		})
		if(sum_yiyan == sum_son){
			$('.del .par_'+product_id).find('td:nth-child(2)').html("验货完成");
		}else if(sum_yiyan < sum_son && sum_yiyan > 0){
			$('.del .par_'+product_id).find('td:nth-child(2)').html("部分验货");
		}else if(sum_yiyan == 0){
			$('.del .par_'+product_id).find('td:nth-child(2)').html("尚未验货");
		}
		//结束
		var sum = 0;
		$('.del input[name=yiyan]').each(function(){
			var v = $(this).val();
			if(v == ""){
				v = 0;
			}
			sum = parseInt(sum) + parseInt(v);
		})
		$('.heji').find('td:nth-child(3)').html(sum);
		if(sum == end){
			$('.strong').html("确认通过");
		}else{
			$('.strong').html("强制通过");
		}
	})

	//强制通过
	$('.strong').click(function(){
		var id = $('.heji').find('td:nth-child(2)').find('input[name=order_id]').val();
		if(id == ""){
			$('#confirm .modal-body').html("您还没有选择订单");
			$('#confirm').modal('show');
			$('#print-menu').hide();
			return false;
		}else{
			var v = $(this).val();
			if(v == "强制通过"){
				$('#confirm .modal-body').html("您确定要强制通过吗?");
				$('#confirm').modal('show');
			}else{
				$('#confirm .modal-body').html("您确定要通过验货吗?");
				$('#confirm').modal('show');
			}
			$('.ok').click(function(){
				dis_name = $("select[name='type'] option").html();
				$.ajax({
					url:'deliver_barcode_inspection.php',
					type:'get',
					dataType:'json',
					data:{'order_id':id,'dis_name':dis_name},
					success:function(data){
						num = 5;
						inte = null;
						if(data == '1'){
							inte = setInterval(function(){
								$('#confirm .modal-body').html("完成验货("+ num-- +")");
								$('#confirm').modal('show');

								$('.ok').click(function(){
									clearInterval(inte);
									$('#print-menu').hide();
									window.location.href="deliver_barcode_inspection.php";
								})
								if(num <= 0){
									clearInterval(inte);
									$('#print-menu').hide();
									window.location.href="deliver_barcode_inspection.php";
								}
							},1000);
						}else if(data == '0'){
							inte = setInterval(function(){
								$('#confirm .modal-body').html("验货失败("+ num-- +")");
								$('#confirm').modal('show');

								$('.ok').click(function(){
									clearInterval(inte);
									$('#print-menu').hide();
									window.location.href="deliver_barcode_inspection.php";
								})
								if(num <= 0){
									clearInterval(inte);
									$('#print-menu').hide();
									window.location.href="deliver_barcode_inspection.php";
								}
							},1000);
						}else{
							$('#confirm .modal-body').html("异常订单,异常原因:"+data);
							$('#confirm').modal('show');

							inte = setTimeout(function(){
								$('#print-menu').hide();
								window.location.href="deliver_barcode_inspection.php";
							},3000);
						}

					}
				})
			})
		}



	});

	//打回审核
	$('.shen').click(function(){
		var id = $('.heji').find('td:nth-child(2)').find('input[name=order_id]').val();
		if(id){
			$('#confirm .modal-body').html("您确定要打回审核吗?");
			$('#confirm').modal('show');
			$('.ok').click(function(){
				$.ajax({
					url:'deliver_barcode_inspection.php',
					type:'get',
					dataType:'json',
					data:{'id':id},
					success:function(data){
						if(data == 1){
							window.location.href="deliver_barcode_inspection.php";
						}else{
							alert("打回失败");
						}

					}
				})
			})
		}
	});

	//打回配货
	$('.pei').click(function(){
		var id = $('.heji').find('td:nth-child(2)').find('input[name=order_id]').val();
		if(id){
			$('#confirm .modal-body').html("您确定要打回配货吗?");
			$('#confirm').modal('show');
			$('.ok').click(function(){
				$.ajax({
					url:'deliver_barcode_inspection.php',
					type:'get',
					dataType:'json',
					data:{'pei_id':id},
					success:function(data){
						if(data == 1){
							window.location.href="deliver_barcode_inspection.php";
						}else{
							alert('打回失败');
						}

					}
				})
			})
		}
	});

	//添加订单至异常订单
	$('.yichang').click(function(){
		var id = $('.heji').find('td:nth-child(2)').find('input[name=order_id]').val();
		if(id){
			MessageBox('/deliver/deliver_add_unusual.php?id='+id, '异常原因',412,185);
			return false;
		}

	})

})