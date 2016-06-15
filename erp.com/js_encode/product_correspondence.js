$(function(){
	//单个解除
	$('.table .delete').click(function () {
		$('#confirm').modal('show');
		var thisHref = $(this).attr('href');
		$('#confirm .ok').click(function () {
			location.href = thisHref;
		});
		return false;
	});
});