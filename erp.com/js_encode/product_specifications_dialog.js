$(function () {
	//表格4添加
	$('.customersAddca').click(function(leng){
		var adda='<tr><td class="center"></td><td class="center"><a href="#" class="delete" id="{supplierList.id}">删除</a></td><td class="modification"></td></tr>'
		$(this).parent().next().next().children().append(adda);	
	})
	var talb=$('.table-norms tr').eq(1).clone(true);	
	$('.customersAddca').click(function () {
		$('.table-norms tr').each(function (index, value) {
			if (index > 0) {
				$(value).find('td').eq(0).html(index);
			}
		});
	});	

	//获取class为caname的元素 
$('.table-norms').on('click', '.modification', function() {
	var td = $(this); 
	var txt = td.text(); 
	var fid=$(this).parent().parent().children().eq(0).attr('id');

	var input = $("<input type='text' maxlength='15' class='form-control input-sm merger_two_row_4 amend' value='" + txt + "'/>"); 
	td.html(input); 
	input.click(function() { return false; }); 
	//获取焦点 
	input.trigger("focus"); 
	//文本框失去焦点后提交内容，重新变为文本 
	input.blur(function() { 
		var newtxt = $(this).val(); 
		//判断文本有没有修改 

		var id=$(this).parent().attr('id');
		//获取要匹配的VAL
		for(i=0;i<999;i++){
		var kaka=$('table tr').eq(i).find('.amend').html();
		if(kaka==newtxt){
		$(this).parent().children().css('border','1px solid red');
		$(this).parent().children().val('');
		$(this).parent().children().attr('placeholder','不能重复，否则不保存');
		$(this).parent().parent().parent().parent().attr('title','不能重复，否则不保存');
		return;
		}else if(newtxt=="" || newtxt==null){
		$(this).parent().children().css('border','1px solid red');
		$(this).parent().children().attr('placeholder','不能为空，否则不保存');
		$(this).parent().children().attr('title','不能为空，否则恢复原值');
		return;
		}else{
		$(this).css('border','aaa');
		}
	}
		if (newtxt != txt) {
			$.post("/product/product_specifications_dialog.php",{"body":newtxt,"fid":fid,"id":id},function(data){
			}, "json");
			td.html(newtxt);
			}else{ 
			td.html(newtxt);
			}
		}); 
	}); 


$(function(){
        var len = $('table tr').length;
        for(var i = 1;i<len;i++){
            $('table tr:eq('+i+') td:first').text(i);
        }
            
});

$('.table-norms').on('click','.delete',function(){
	var id=$(this).attr('id');
	var idtext=$(this).parent().next().html();

	$.post("/product/product_specifications_dialog.php",{"did":id,"textid":idtext},function(data){	
			}, "json");
	$(this).parent().parent().remove();
			$('.table-norms tr').each(function (index, value) {
			if (index > 0) {
				$(value).find('td').eq(0).html(index);

			}
		});	
})

});
