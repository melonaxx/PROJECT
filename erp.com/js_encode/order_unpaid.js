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
	//关闭订单
	$('.headMsg .shutdown').click(function(){
		if($('.table input[name=select_one]').is(':checked')){
			$('#confirm .modal-body').html("您确定要关闭订单吗?");
			$('#confirm').modal('show');
			$('.ok').click(function(){
				var a = "";
				$('table tr td:nth-child(2)').find('input[name=select_one]:checked').each(function(){
					var v = $(this).parent().find('input[name=id]').val();
					a += v+",";
				});
				$.ajax({
					url:'order_unpaid.php',
					type:'get',
					dataType:'json',
					data:{'id':a},
					success:function(data){
						if(data == '1'){
							window.location.href="order_unpaid.php";
						}else{
							alert('关闭订单失败');
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

	//点击发送短信
	$('.headMsg .duanxin').click(function(){
		var aa = '';
		var bb = '';
		var cc = '';
		var dd = '';
		var ee = '';
		var ff = '';
		var gg = '';
		var hh = '';
		var ii = '';
		$('.table tr td:nth-child(2)').find('input[name=select_one]:checked').each(function(){
			aa += $(this).parents('tr').find('td:eq(7)').text()+",";
			bb += $(this).parents('tr').find('td:eq(8)').text()+",";
			cc += $(this).parents('tr').find('td:eq(10)').text()+",";
			dd += $(this).next().val()+",";
			ee += $(this).parents('tr').find('input[name=express_number]').val()+",";
			ff += $(this).parents('tr').find('input[name=address]').val()+",";
			gg += $(this).parents('tr').find('td:eq(11)').text()+",";
			hh += $(this).parents('tr').find('td:eq(12)').text()+",";
			ii += $(this).parents('tr').find('td:eq(9)').text()+",";
		});
		if(aa == ""){
				$('#confirm .modal-body').html("请至少选择1个订单");
				$('#confirm').modal('show');
				$('#print-menu').hide();
				return false;
		}
		MessageBox('/order/order_unpaid2.php?aa='+aa+'&bb='+bb+'&cc='+cc+'&dd='+dd+'&ee='+ee+'&ff='+ff+'&gg='+gg+'&hh='+hh+'&ii='+ii, '选择模板',260,120);
	})
})