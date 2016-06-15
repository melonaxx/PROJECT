$(function () {


	$('.table-pay input[name="select_alld"]').click(function () {
	    if(this.checked){
    		$('.table-pay input[name="select_one"]').each(function () {
    			$(this).prop("checked",true);
    		});
	    }else{
    		$('.table-pay input[name="select_one"]').each(function () {
    			$(this).prop("checked",false);
    		});
	    }
	});

	//日期
	$('#begin_time,#end_time').datetimepicker({
		language:'zh-CN',
		format:'yyyy-mm-dd',
		autoclose:true,
		minView:2,
	});
	//点击清空
	$('input[name=clear]').click(function(){
		$('select[name=type]').val('');
		$('select[name=name]').val('');
		$('#begin_time').val('');
		$('#end_time').val('');
	})

	$('.payAdd').click(function(){
		$(this).parent().next().append('<tr><td class="center"></td><td class="center"><a href="#" class="delete_pay">删除</a></td>	<td class="Unit_Name"></td></tr>');
	})
	var talbd=$('.table-pay tr').eq(1).clone(true);
	$('.payAdd').click(function () {

		$('.table-pay tr').each(function (index, value) {
			if (index > 0) {
				$(value).find('td').eq(0).html(index);
			}
		});
	});

	$('.table-pay').on('click','.delete_pay',function () {
		$('#confirm').modal('show');
		var thisHref = $(this).attr('href');
		var idArr	=$(this).parent().next().next().children().attr('id');
		var idbody	=$(this).parent().next().html();
		$('#confirm .ok').click(function () {
			// location.href = '/product/product_specifications_properties.php?m=deleteAll&idArr=' + idArr;
			// $.post("/product/product_specifications_properties.php",{"idbody":idbody,},function(data){
			// }, "json");
		});
		return false;
	});



	$('.table-income input[name="select_alld"]').click(function () {
	    if(this.checked){
    		$('.table-income input[name="select_one"]').each(function () {
    			$(this).prop("checked",true);
    		});
	    }else{
    		$('.table-income input[name="select_one"]').each(function () {
    			$(this).prop("checked",false);
    		});
	    }
	});

	$('.incomeadd').click(function(){
		$(this).parent().next().append('<tr><td class="center"></td><td class="center"><a href="#" class="delete_income">删除</a></td>	<td class="amend"></td></tr>');
	})
	var talbd=$('.table-income tr').eq(1).clone(true);
	$('.incomeadd').click(function () {
		$('.table-income tr').each(function (index, value) {
			if (index > 0) {
				$(value).find('td').eq(0).html(index);
			}
		});
	});


	$('.table-income').on('click','.delete_ncome',function () {
		$('#confirm').modal('show');
		var thisHref = $(this).attr('href');
		var idArr	=$(this).parent().next().next().children().attr('id');
		var idbody	=$(this).parent().next().html();
		$('#confirm .ok').click(function () {
			// location.href = '/product/product_specifications_properties.php?m=deleteAll&idArr=' + idArr;
			// $.post("/product/product_specifications_properties.php",{"idbody":idbody,},function(data){
			// }, "json");
		});
		return false;
	});













	//获取class为caname的元素
	$('.table-income').on('click', '.amend', function() {
		var td = $(this);
		var txt = td.text();
		var input = $("<input type='text' maxlength='15' class='form-control input-sm merger_two_row_4 amend' value='" + txt + "'/>");
		td.html(input);
		input.click(function() { return false; });
		//获取焦点
		input.trigger("focus");
		//文本框失去焦点后提交内容，重新变为文本
		input.blur(function() {
			var newtx = $(this).val();
			var newtxt= trim(newtx,' ');
			//获取当前ID
			var id=$(this).parent().next().children().attr('id');
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
				var gaga=$(this).css('border','aaa');
			}
		}
			//判断文本有没有修改
			if (newtxt != txt||newtxt!=null||newtxt=="") {
				td.html(newtxt);
				$.post("/product/product_specifications_properties.php",{"caname":newtxt,"id":id},function(data){
			}, "json");
				 // history.go(0)
			}else{

				td.html(newtxt);
			}

		});
	});


	//获取class为caname的元素
	$('.table-pay').on('click', '.Unit_Name', function() {
		var td = $(this);
		var txt = td.text();
		var input = $("<input type='text' maxlength='15' class='form-control input-sm merger_two_row_4 Property_Name' value='" + txt + "'/>");
		td.html(input);
		input.click(function() { return false; });
		//获取焦点
		input.trigger("focus");
		//文本框失去焦点后提交内容，重新变为文本
		input.blur(function() {
			var newtx = $(this).val();
			var newtxt= trim(newtx,' ');
			//获取当前ID
			var id=$(this).parent().next().children().attr('id');
			//获取要匹配的VAL
			for(i=0;i<999;i++){
			var kaka=$('table tr').eq(i).find('.Property_Name').html();
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
				var gaga=$(this).css('border','aaa');
			}
		}
			//判断文本有没有修改
			if (newtxt != txt||newtxt!=null||newtxt=="") {
				td.html(newtxt);
			// 	$.post("/product/product_specifications_properties.php",{"attribute_caname":newtxt,"attribute_id":id},function(data){
			// }, "json");
				 // history.go(0);
			}else{

				td.html(newtxt);
			}

		});
	});













});




