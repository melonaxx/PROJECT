$(function () {
	$('.norms input[name="select_all"]').click(function () {

	    if(this.checked){   
    		$('.norms input[name="select_one"]').each(function () {
    			$(this).prop("checked",true);
    		});
	    }else{   
    		$('.norms input[name="select_one"]').each(function () {
    			$(this).prop("checked",false);
    		});
	    }
	});
	$('.norms').on('click','.delete',function () {
		$('#confirm').modal('show');
		var thisHref = $(this).attr('href');
		var idArr	=$(this).parent().next().next().children().attr('id');
		var idbody	=$(this).parent().next().html();
		$('#confirm .ok').click(function () {
			location.href = '/product/product_specifications_properties.php?m=deleteAll&idArr=' + idArr;
			$.post("/product/product_specifications_properties.php",{"idbody":idbody,},function(data){		
			}, "json");
			 history.go(0);
		});
		return false;
	});


	//表格1添加
	$('.customersAdd').click(function(){
		var adda='<tr><td class="center"><xsl:value-of select="position()"/></td><td class="center"><a href="#" class=" delete">删除</a></td><td class="amend"></td><<td class="center"><a href="#" onclick="MessageBox(' + "'/product/product_specifications_dialog.php?id=1','规格备选值', 470, 400);" + ' return false">修改</a></td>/tr>'
		$(this).parent().next().children().append(adda);
	})

	$('.customersAdd').click(function () {
		// $('.norms').append(adda);
		//重置下标
		$('.norms tr').each(function (index, value) {
			if (index > 0) {
				$(value).find('td').eq(0).html(index);
			}
		});
	});	

	//表格3全选
	$('.custom input[name="select_allb"]').click(function () {
	    if(this.checked){   
    		$('.custom input[name="select_one"]').each(function () {
    			$(this).prop("checked",true);
    		});
	    }else{   
    		$('.custom input[name="select_one"]').each(function () {
    			$(this).prop("checked",false);
    		});
	    }
	});

	// 表格3删除
	$('.custom').on('click','.delete',function () {
		$('#confirm').modal('show');
		var thisHref = $(this).attr('href');
		var idArr	=$(this).parent().next().next().children().attr('id');

		var idbody	=$(this).parent().next().html();
		$('#confirm .ok').click(function () {
			location.href = '/product/product_specifications_properties.php?m=deleteAll&attribute_idArr=' + idArr;
			$.post("/product/product_specifications_properties.php",{"attribute_body":idbody,},function(data){		
			}, "json");
		});
		return false;
	});

// $('#myTab a:last').tab('show');
	//表格3添加
	$('.customersAddb').click(function(){

		var adda='<tr><td class="center"><xsl:value-of select="position()"/></td><td class="center"><a href="#" class="delete">删除</a></td><td class="Property_Name"></td><<td class="center"><a href="#" onclick="MessageBox(' + "'/product/product_specifications_dialog.php?id=1','规格备选值', 470, 400);" + ' return false">修改</a></td>/tr>'
		$(this).parent().next().children().append(adda);
	})
	var talbb=$('.custom tr').eq(1).clone(true);
	var trStrc = $('.custom tr').eq(1).prop("outerHTML");
	$('.customersAddb').click(function () {
		// $('.table-hoverb').append(trStrc);
		//重置下标
		$('.custom tr').each(function (index, value) {
			if (index > 0) {
				$(value).find('td').eq(0).html(index);
			}
		});
	});	

	//表格5全选
	$('.table-units input[name="select_alld"]').click(function () {
	    if(this.checked){   
    		$('.table-units input[name="select_one"]').each(function () {
    			$(this).prop("checked",true);
    		});
	    }else{   
    		$('.table-units input[name="select_one"]').each(function () {
    			$(this).prop("checked",false);
    		});
	    }
	});

	//表格5添加
	$('.unitsAdd').click(function(){
		$(this).parent().next().append('<tr><td class="center"></td><td class="center"><a href="#" class=" delete">删除</a></td><td class="Unit_Name"></td></tr>');
	})
	var talbd=$('.table-units tr').eq(1).clone(true);
	$('.unitsAdd').click(function () {
		//重置下标
		$('.table-units tr').each(function (index, value) {
			if (index > 0) {
				$(value).find('td').eq(0).html(index);
			}
		});
	});	

	// 表格5删除
	$('.table-units').on('click','.delete',function () {
		$('#confirm').modal('show');
		var thisHref = $(this).attr('href');
		var idArr	=$(this).attr('id');
		var idbody	=$(this).parent().next().html();
		$('#confirm .ok').click(function () {
			location.href = '/product/product_specifications_properties.php?m=deleteAll&units_idArr=' + idArr;
			$.post("/product/product_specifications_properties.php",{"units_body":idbody,},function(data){		
			}, "json");
		});
		return false;
	});
	// 表格移动
	$(".table-units").on('click',"a.shang",function(){
		//获取当前序号
		var number=$(this).parent().prev().prev().prev().html();
		var Up=number-1;
		if(Up==0){
			var Up=1;
		}
		var down=number++;
		if(number==1||number==2){
			return; 
		}else{
			$(this).parents("tr").prev().before($(this).parents("tr"));
			$(this).parent().prev().prev().prev().html(Up);
			$(this).parent().parent().next().children().eq(0).html(down);
		}
		})
	$(".table-units").on('click',"a.xia",function(){
		//获取当前序号
		var number=$(this).parent().prev().prev().prev().html();
		var y=1;
		var Up = y*1+number*1;
		//获取当前最大序号
		var MAX=$('.table-units tbody tr:last-child').children().eq(0).html();
		var MAXx=$('.table-units tbody tr:last-child').prev().children().eq(0).html();
		if(Up>MAX>MAXx){
		}else{
			$(this).parents("tr").next().after($(this).parents("tr")); 

		if(Up>MAX){
			if(number>1){
			$(this).parent().parent().prev().children().eq(0).html(number-1);
			}
			$(this).parent().parent().children().eq(0).html(Up-1);
			}else{
			$(this).parent().parent().prev().children().eq(0).html(number);
			$(this).parent().parent().children().eq(0).html(Up);
		}	
		}
	})







	//获取class为caname的元素 
	$('.norms').on('click', '.amend', function() { 
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
	$('.custom').on('click', '.Property_Name', function() { 
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
				$.post("/product/product_specifications_properties.php",{"attribute_caname":newtxt,"attribute_id":id},function(data){		
			}, "json");
				 // history.go(0);
			}else{ 
				
				td.html(newtxt); 
			}

		}); 
	}); 


	var id1=$('#1').attr('id');
	var id2=$('#2').attr('id');
	var id3=$('#3').attr('id');

	if(id1=='1'){
		$('#myTab li:eq(0) a').tab('show') 
	}else if(id2=='2'){
		$('#myTab li:eq(1) a').tab('show') 
	}else if(id3=='3'){
		$('#myTab li:eq(2) a').tab('show') 
	}




	$('.table-units').on('click', '.Unit_Name', function() { 
		var td = $(this); 
		var txt = td.text(); 
		var input = $("<input type='text' class='form-control input-sm merger_two_row_4' value='" + txt + "'/>"); 
		td.html(input); 
		input.click(function() { return false; }); 
		//获取焦点 
		input.trigger("focus"); 
		//文本框失去焦点后提交内容，重新变为文本 


		input.blur(function() { 

			var newtx = $(this).val(); 
			var newtxt= trim(newtx,' ');
			//获取当前ID
			// alert(newtxt);

			var id=$(this).parent().prev().children().attr('id');

			//获取要匹配的VAL
			for(i=0;i<999;i++){
			var kaka=$('table tr').eq(i).find('.Unit_Name').html();

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
			var newtxt = $(this).val(); 
			//判断文本有没有修改 
			if (newtxt != txt||newtxt!=null||newtxt=="") {
				td.html(newtxt); 
				$.post("/product/product_specifications_properties.php",{"units_caname":newtxt,"units_id":id},function(data){		
			}, "json");
			}else{ 
				td.html(newtxt); 
			} 
		}); 
	});
});

$(function(){
	//$('table tr:not(:first)').remove();
	var len = $('norms tr').length;
	for(var i = 1;i<len;i++){
	$('norms tr:eq('+i+') td:first').text(i);
	}

});


$(function(){
	//$('table tr:not(:first)').remove();
	var len = $('custom tr').length;
	for(var i = 1;i<len;i++){
	$('custom tr:eq('+i+') td:first').text(i);
	}

});