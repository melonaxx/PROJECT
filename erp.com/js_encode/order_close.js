$(function(){
	$("input[name = 'select_all']").click(function(){
		if(this.checked){
			$(".tab_select input[type = 'checkbox']").prop("checked",true);
		}else{
			$(".tab_select input[type = 'checkbox']").prop("checked",false);
		}
	})
	// 修改备注
	$('.headMsg .bei').click(function(){
		var a = getData();
		if(a == ""){
			$('#confirm .modal-body').html("请至少选择1个订单");
				$('#confirm').modal('show');
				$('#print-menu').hide();
				return false;
		}
		MessageBox('/order/order_modify_remarks.php?id='+a, '修改备注',410,255);
	})
	//打回订单
	$('.headMsg .goback').click(function(){
		if($('.table input[name=select_one]').is(':checked')){
			$('#confirm .modal-body').html("您确定要打回订单吗?");
			$('#confirm').modal('show');
			$('.ok').click(function(){
				var a = "";
				$('table tr td:nth-child(2)').find('input[name=select_one]:checked').each(function(){
					var v = $(this).parent().find('input[name=id]').val();
					a += v+",";
				});
				$.ajax({
					url:'order_close.php',
					type:'get',
					dataType:'json',
					data:{'id':a},
					success:function(data){
						if(data == '1'){
							window.location.href="order_close.php";
						}else{
							alert('打回订单失败');
						}
					}
				})
			})
		}else{
			$('#confirm .modal-body').html("请至少选择1个订单");
			$('#confirm').modal('show');
			$('#print-menu').hide();
			return false;
		}

	})

	function getData(){
		var a = '';
		$('.table tr td:nth-child(2)').find('input[name=select_one]:checked').each(function(){
			a += $(this).next().val()+",";
		});
		return a;
	}
})