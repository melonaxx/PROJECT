$(function(){
//全选/全不选
	$("input[name='sel_all']").click(function(){
		if(this.checked){
			$("input[type='checkbox']").prop("checked", true);
		}else{
			$("input[type='checkbox']").prop("checked", false);
		}
	});
//删除
	$(".delete").click(function(){
		$('.tab_select input[name="select_one"]:checked').parent().parent().remove();
		//重置下标
			$('.aa table tr').each(function (index, value) {
				if (index > 0) {
					$(value).find('td').eq(0).html(index);
				}
			});
	})
//添加
	var out = $(".tab_select tr").eq(1).prop("outerHTML");
	$(".add").click(function(){
		$(".tab_select").children().append(out);
//重置下标
	$(".tab_select tr").each(function(index,value){
			if(index >0){
				$(value).find('td').eq(0).html(index);
			}
		});
	});
//期初成本均价
	$('.amaed').on('click', '.xiugaia', function() { 
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
});