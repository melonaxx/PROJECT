$(function(){
	$(".stop").click(function(){
		var text = $(this).text();
		if(text == '停用'){
			$(this).text("启用");
		}else{
			$(this).text("停用");
		}
	})
	//单个删除
	$('.table .delete').click(function () {
		$('#confirm .modal-body').html("您确定要删除<span class='number'>1</span>条数据吗？");
		$('#confirm').modal('show');
		var thisHref = $(this).attr('href');
		$('#confirm .ok').click(function () {
			location.href = thisHref;
		});
		return false;
	});
})