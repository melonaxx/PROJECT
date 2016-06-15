$(function () {
	//添加库区
	$(".reservoirareaAdd").click(function(){
		var tab ="<tr><td class='center'></td><td class='center'><a href=''>删除</a></td><td class='xiugaia'></td><td><a href=''></a></td></tr>";
		$(".table-reservoirareaAdd").children().append(tab);
	//重置下标
		$(".table-reservoirareaAdd tr").each(function(index,value){
			if(index >0){
				$(value).find('td').eq(0).html(index);
			}
		})
	});
	
	//添加货架
	$(".shelvesAdd").click(function(){
		var tab = "<tr><td class='center'></td><td class='center'><a href=''>删除</a></td><td class='xiugaia'></td><td><a class='btn_margin' href=''>查看</a><span class='margin_left_2'>共0个</span></td></tr>";
		$(".table-shelves").children().append(tab);
	//重置下标
		$(".table-shelves tr").each(function(index,value){
			if(index >0){
				$(value).find('td').eq(0).html(index);
			}
		});
	});

	//添加货位
	$(".locationAdd").click(function(){
		var tab = "<tr><td class='center'></td><td class='center'><a href=''>删除</a></td><td class='xiugaia'></td><td></td></tr>";
		$(".table-location").children().append(tab);
	//重置下标
		$(".table-location tr").each(function(index,value){
			if(index >0){
				$(value).find('td').eq(0).html(index);
			}
		})
	});
	
	//修改文本框中的内容
	$('.norms').on('click', '.xiugaia', function() { 
		var td = $(this); 
		var txt = td.text(); 
		var input = $("<input style='width:150px' class='form-control input-sm' type='text'value='" + txt + "'/>"); 
		td.html(input); 
		input.click(function() { return false; }); 
		//获取焦点 
		input.trigger("focus"); 
		//文本框失去焦点后提交内容，重新变为文本 
		input.blur(function() { 
			var newtxt = $(this).val(); 
			//判断文本有没有修改 
			if (newtxt != txt) {
				td.html(newtxt); 
			}else{
				td.html(newtxt); 
			} 
		}); 
	});

	//开启/关闭
	$('.warehouseMsg .start').click(function () {
		var txt = $(this).text();
		if(txt == '正常'){
			$(this).text("停止");
		}else{
			$(this).text("正常");
		}
	});
	
	//单个删除
	$('.table .delete').click(function () {
		$('#confirm').modal('show');
		var thisHref = $(this).attr('href');
		$('#confirm .ok').click(function () {
			location.href = thisHref;
		});
		return false;
	});
});