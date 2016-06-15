$(function () {
//---- 拆单与合单 ----
$('.indent').change(function(a){
	if($('.indent').val()==1){
		MessageBox('/order/order_list_audit_detach.php?id=1', '手工拆单',800,400); return false
	}else if($('.indent').val()==2){
		MessageBox('/order/order_list_audit_Merge.php?id=1', '手工合单',800,400); return false
	}

})

//---- 标记为选中标记 ----
$('.table-order-form input[name="select_all"]').click(function () {
    if(this.checked){
		$('.table-order-form input[name="select_one"]').each(function () {
			$(this).prop("checked",true);
		});
    }else{
		$('.table-order-form input[name="select_one"]').each(function () {
			$(this).prop("checked",false);
		});
    }
});

});
