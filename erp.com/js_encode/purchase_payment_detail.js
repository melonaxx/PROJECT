$(function () {
	
	//添加
	//这里弄一段比较通用的代码
	$('.goodsMsg table tr').each(function (index, value) {
		if (index > 0) {
			$(value).find('td').eq(0).html(index);
		}
	});
	$('.goodsMsg table tr').last().find('td').eq(0).html('');
	
	
});
