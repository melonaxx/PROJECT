$(function(){
	$("input[name = 'select_all']").click(function(){
		if(this.checked){
			$(".table_select input[type = 'checkbox']").prop("checked",true);
		}else{
			$(".table_select input[type = 'checkbox']").prop("checked",false);
		}
	})

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
			aa += $(this).parents('tr').find('td:eq(9)').text()+",";
			bb += $(this).parents('tr').find('td:eq(10)').text()+",";
			cc += $(this).parents('tr').find('td:eq(12)').text()+",";
			dd += $(this).next().val()+",";
			ee += $(this).parents('tr').find('input[name=express_number]').val()+",";
			ff += $(this).parents('tr').find('input[name=address]').val()+",";
			gg += $(this).parents('tr').find('td:eq(13)').text()+",";
			hh += $(this).parents('tr').find('td:eq(8)').text()+",";
			ii += $(this).parents('tr').find('td:eq(11)').text()+",";
		});
		if(aa == ""){
			$('#confirm .modal-body').html("请至少选择1个订单");
			$('#confirm').modal('show');
			$('#print-menu').hide();
			return false;
		}
		MessageBox('/deliver/deliver_delivered2.php?aa='+aa+'&bb='+bb+'&cc='+cc+'&dd='+dd+'&ee='+ee+'&ff='+ff+'&gg='+gg+'&hh='+hh+'&ii='+ii,'选择模板',260,120);
	})
});