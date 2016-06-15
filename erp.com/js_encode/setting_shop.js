	//单个删除
$(function(){
	$('.table .delete').click(function () {
		$('#confirm').modal('show');
		var thisHref = $(this).attr('href');
		$('#confirm .ok').click(function () {
			location.href = thisHref;
		});
		return false;
	})
});