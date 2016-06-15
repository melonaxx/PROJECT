$(function () {
	// 开始时间
	$('#bigan_time').datetimepicker({
		language:'zh-CN',
		format:'yyyy-mm-dd',
		autoclose:true,
		minView:2,
    });
	//结束时间
	$('#end_time').datetimepicker({
		language:'zh-CN',
		format:'yyyy-mm-dd',
		autoclose:true,
		minView:2,
    });

});