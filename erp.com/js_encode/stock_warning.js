$(function () {

	//全选
	$('.table-hover input[name="select_one"]').click(function () {
	    if(this.checked){
    		$('.table-hover input[name="select_all"]').each(function () {
    			$(this).prop("checked",true);
    		});
	    }else{
    		$('.table-hover input[name="select_all"]').each(function () {
    			$(this).prop("checked",false);
    		});
	    }
	});

	//点击批量设置事件
	$(".set").click(function(){
		var ware = $('select[name="ware"]').val();
		var box = new Array();
		$("input[name='select_all']").each(function(){
			if(this.checked){
				var num = $(this).parents('tr').find('input[name="id"]').val();
				box.push(num);
			}
		});
		if(box.length != 0){
			MessageBox('/stock/stock_warning_set.php?ware='+ware+' & box='+box, '批量设置', 430, 160); return false;
		}else{
			alert("没有选中任何东西");
		}
	});
	//取消下限
	$('.set_low').click(function(){
		var ware = $('select[name="ware"]').val();
		var box = new Array();
		$("input[name='select_all']").each(function(){
			if(this.checked){
				var num = $(this).parents('tr').find('input[name="id"]').val();
				box.push(num);
			}
		});
		if(box.length != 0){
			MessageBox('/stock/stock_warning_clear_low.php?ware='+ware+' & box='+box, '取消下限', 320, 90); return false;
		}else{
			alert("没有选中任何东西");
		}
	})
	//取消上限
	$('.set_up').click(function(){
		var ware = $('select[name="ware"]').val();
		var box = new Array();
		$("input[name='select_all']").each(function(){
			if(this.checked){
				var num = $(this).parents('tr').find('input[name="id"]').val();
				box.push(num);
			}
		});
		if(box.length != 0){
			MessageBox('/stock/stock_warning_clear_up.php?ware='+ware+' & box='+box, '取消上限', 320, 90); return false;
		}else{
			alert("没有选中任何东西");
		}
	})
	//单条设置
	$('.setup').click(function(){
		var ware = $('select[name="ware"]').val();
		var setup = $(this).parents('tr').find('input[name="id"]').val();
		MessageBox('/stock/stock_warning_setup.php?ware='+ware+' & setup='+setup, '批量设置', 430, 130); return false;
	});

});