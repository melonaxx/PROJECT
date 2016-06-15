$(function () {

	$('.table-Goods input[name="select_all"]').click(function () {
	    if(this.checked){   
    		$('.table-Goods input[name="select_one"]').each(function () {
    			$(this).prop("checked",true);
    		});
	    }else{   
    		$('.table-Goods input[name="select_one"]').each(function () {
    			$(this).prop("checked",false);
    		});
	    }
	});

	$('.goodsDeleteaa').click(function () {
	$('.table-Goods  input[name="select_one"]:checked').parent().parent().remove();
		$('.table-Goods tr').each(function (index, value) {
			if (index > 0) {
				$(value).find('td').eq(0).html(index);
			}
		});
	});

	$('.customersAdd').click(function(){
		$(this).parent().next().children().append('<tr><td class="center"></td><td class="center"><input type="checkbox" name="select_one" /></td><td class="seek"><input type="text" class="form-control input-sm merger_two_row_4 input_border seach" placeholder="搜索"/></td><td class="center"></td><td class="form-group"><div class="div"></div></td><td></td><td class="amount"></td><td></td></tr>');
	})
	var talb=$('.table-Goods tr').eq(1).clone(true);
	$('.customersAdd').click(function () {
		$('.table-Goods tr').each(function (index, value) {
			if (index > 0) {
				$(value).find('td').eq(0).html(index);
			}
		});
	});	



var user=[	{"name": "张三","good":"25","price":"998","unit":"个"},
			{"name": "李四","good":"25","price":"998","unit":"个"},
			{"name": "啊啊","good":"25","price":"998","unit":"个"},
			{"name": "搜索","good":"25","price":"998","unit":"个"}]
$(".table-Goods").on('keyup','.seach',function(){
	var seachText=$(this).val();
		if(seachText!=""){
			var tab="<select class='form-control form_no_border input-sm'>";
			var goods="<select class='form-control form_no_border input-sm'>";
			var price="<select class='form-control form_no_border input-sm'>";
			var unit="<select class='form-control form_no_border input-sm'>";
			$.each(user,function(id, item){
				if(item.name.indexOf(seachText)!=-1||item.good.indexOf(seachText)!=-1){
					tab+="<option>"+item.name+"</optiom>";
					goods+="<option>"+item.good+"</optiom>";
					price+="<option>"+item.price+"</optiom>";
					unit+="<option>"+item.unit+"</optiom>";
				}
			})
			tab+="</select>";
			goods+="</select>";
			price+="</select>";
			unit+="</select>";
			// $(".div").html(tab);
			$(this).parent().next().next().html(tab)
			$(this).parent().next().html(goods)
			$(this).parent().next().next().next().html(price)
			$(this).parent().next().next().next().next().next().html(unit)
	
			tab="<select><option></optiom>";
			goods="<select><option></optiom>";
			price="<select><option></optiom>";
			unit="<select><option></optiom>";
		}else{
			$(this).parent().next().next().html("")
			$(this).parent().next().html("")
			$(this).parent().next().next().next().html("")
			$(this).parent().next().next().next().next().next().html("")
		}
	});

	$('.table-Goods').on('click', '.amount', function() { 
		var td = $(this); 
		var txt = td.text(); 
		var input = $("<input type='text' class='form-control  input-sm' value='" + txt + "'/>"); 
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

