$(function(){

	//编号输入
	$('.bian').keyup(function(e){
		if(e.keyCode == 13){
			var v = $(this).val();
			var tiao = $('.tiao').val();
			$.ajax({
				url:'deliver_single_shipment.php',
				type:'post',
				data:{'tiao':tiao,'val':v},
				dataType:'json',
				success:function(data){
				/*	if(data){
						$('.box input[name=bind_number]').val(data['bind_number']);
						$('.box input[name=express]').val(data['express_name']+","+data['number']);
						$('.box input[name=name]').val(data['name']+","+data['mobile']);
						$('.box input[name=customer_text]').val(data['customer_text']);
						$('.box input[name=order_text]').val(data['order_text']);
						var  str = '';
						for(var i=0;i < data['product'].length;i++){
							str += '<tr style="height:28px;"><td style="text-align:center">'+data['product'][i]['num']+'</td><td></td><td></td><td>'+data['product'][i]['name']+','+data['product'][i]['format']+'</td><td></td><td>'+data['product'][i]['total']+'</td></tr>';
						}
						$('.box table tr').last().before(str);
						$('.heji').find('td:nth-child(3)').find('.zong').html(data['zong']);
						$('.heji').find('td:nth-child(3)').find('input[name=order_id]').val(data['id']);
						$('.bian').val('');
						$('.bian').focus();
					}*/

					num = 5;
					inte = null;
					if(data == '1'){
						inte = setInterval(function(){
							$('#confirm_finish .modal-body').html("发货成功("+ num-- +")");
							$('#confirm_finish').modal('show');

							$('.finish').click(function(){
								clearInterval(inte);
								$('#print-menu').hide();
								window.location.href="deliver_single_shipment.php";
							})
							if(num <= 0){
								clearInterval(inte);
								$('#print-menu').hide();
								window.location.href="deliver_single_shipment.php";
							}
						},1000);

					}else if(data == '0'){
						inte = setInterval(function(){
							$('#confirm_finish .modal-body').html("发货失败("+ num-- +")");
							$('#confirm_finish').modal('show');

							$('.finish').click(function(){
								clearInterval(inte);
								$('#print-menu').hide();
								window.location.href="deliver_single_shipment.php";
							})
							if(num <= 0){
								clearInterval(inte);
								$('#print-menu').hide();
								window.location.href="deliver_single_shipment.php";
							}
						},1000);
					}else{
						$('#confirm_finish .modal-body').html("异常订单,异常原因:"+data);
						$('#confirm_finish').modal('show');
						$('#print-menu').hide();

						inte = setTimeout(function(){
							$('#print-menu').hide();
							window.location.href="deliver_single_shipment.php";
						},3000);
					}
				}
			})
			$('.bian').val('');
			$('.bian').focus();

		}else{
			return false;
		}
	});


	//打回审核
	$('.shen').click(function(){
		var tiao = $(".tiao").val();
		var bian = $(".bian").val();
		var shen = "shen";
		con = confirm("您确定要打回审核吗?");
		if(con){
			$.ajax({
				url:'deliver_single_shipment.php',
				type:'get',
				dataType:'json',
				data:{'tiao':tiao,'bian':bian,'shen':shen},
				success:function(data){
					if(data == '1'){
						window.location.href="deliver_single_shipment.php";
						$('.bar').focus();
					}else{
						alert('打回失败');
						window.location.href="deliver_single_shipment.php";
					}

				}
			})
		}

	});

	//打回配货
	$('.pei').click(function(){
		var tiao = $(".tiao").val();
		var bian = $(".bian").val();
		var pei = "pei";

		con = confirm("您确定要打回配货吗?");
		if(con){
			$.ajax({
				url:'deliver_single_shipment.php',
				type:'get',
				dataType:'json',
				data:{'tiao':tiao,'bian':bian,'pei':pei},
				success:function(data){
					if(data == '1'){
						window.location.href="deliver_single_shipment.php";
						$('.bar').focus();
					}else{
						alert('打回失败');
						window.location.href="deliver_single_shipment.php";
					}

				}
			})
		}
	});

	//确认发货
	$('.sure').click(function(){
		// var id = $('.heji').find('td:nth-child(3)').find('input[name=order_id]').val();
		var id = $(".bian").val();
		if(id == ""){
			$('#confirm_finish .modal-body').html("您还没有选择订单,请先选择订单!");
			$('#confirm_finish').modal('show');
			$('#print-menu').hide();
			return false;
		}else{

			$('#confirm .modal-body').html("您确定要发货吗?");
			$('#confirm').modal('show');
			$('.ok').click(function(){
				var v = $('.bian').val();
				var tiao = $('.tiao').val();
				$.ajax({
					url:'deliver_single_shipment.php',
					type:'post',
					dataType:'json',
					data:{'tiao':tiao,'val':v},
				 success:function(data){
				 	// console.log("56789");
						if(data == '1'){
							num = 5;
							inte = setInterval(function(){
								$('#confirm_finish .modal-body').html("发货成功("+ num-- +")");
								$('#confirm_finish').modal('show');

								$('.finish').click(function(){
									clearInterval(inte);
									$('#print-menu').hide();
									window.location.href="deliver_single_shipment.php";
								})
								if(num <= 0){
									clearInterval(inte);
									$('#print-menu').hide();
									window.location.href="deliver_single_shipment.php";
								}
							},1000);

						}else if(data == '0'){
							num = 5;
							inte = setInterval(function(){
								$('#confirm_finish .modal-body').html("发货失败("+ num-- +")");
								$('#confirm_finish').modal('show');

								$('.finish').click(function(){
									clearInterval(inte);
									$('#print-menu').hide();
									window.location.href="deliver_single_shipment.php";
								})
								if(num <= 0){
									clearInterval(inte);
									$('#print-menu').hide();
									window.location.href="deliver_single_shipment.php";
								}
							},1000);
						}else{
							$('#confirm_finish .modal-body').html("异常订单,异常原因:"+data);
							$('#confirm_finish').modal('show');
							$('#print-menu').hide();

							inte = setTimeout(function(){
								$('#print-menu').hide();
								window.location.href="deliver_single_shipment.php";
							},3000);
						}

					}
				})
			})
		}

	});

	//添加订单至异常订单
	$('.yichang').click(function(){
		var tiao = $(".tiao").val();
		var bian = $(".bian").val();
		var yichang = "yichang";
		if(bian == ""){
			alert("请输入编号");
			return false;
		}
		$.ajax({
			url:'deliver_single_shipment.php',
			type:'get',
			dataType:'json',
			data:{'tiao':tiao,'bian':bian,'yichang':yichang},
			success:function(data){
				if(data=='0'){
					alert("对不起,找不到该找不到该订单");
				}else{
					MessageBox('/deliver/deliver_commit_exception.php?id='+data, '提交异常',416,185);
					return false;
				}

			}
		})

	})


})