$(function () {
	$('.purchaseGoods .erpTableCommon').on('click','.delete',function () {
		$(this).parent().parent().remove();
	});


	//全选
	$('.goodsMsg input[name="select_all"]').click(function () {
	    if(this.checked){
    		$('.goodsMsg input[name="select_one"]').each(function () {
    			$(this).prop("checked",true);
    		});
	    }else{
    		$('.goodsMsg input[name="select_one"]').each(function () {
    			$(this).prop("checked",false);
    		});
	    }
	});
	var trStr = '<tr><td class="center"></td><td class="center"><input type="checkbox" name="select_one"></td><td><input type="text" class="form-control input-sm form_no_border find"></td><td style="width:230px;"><select class="form-control input-sm form_no_border guige" name="product_id[]"><option value="" style="display:none">请选择商品</option></select></td><td><input type="hidden" name="parts_id[]" value="" class="form-control input-sm form_no_border"></td><td><div class="input-group"><div class="input-group-addon">￥</div><input type="text" name="price[]" value="" class="form-control input-sm form_no_border danjia"></div></td><td><input type="text" name="total[]" value="" placeholder="必填" class="form-control input-sm form_no_border number" onkeyup="value=value.replace(/[^\\d]/g,\'\')"></td><td><div class="input-group"><div class="input-group-addon"></div></div></td><td><input type="text" name="content[]" value="" class="form-control input-sm form_no_border"></td></tr>'
	//添加删除
	$('.goodsAdd').click(function () {
		$('.goodsMsg table tr').last().before(trStr);
		//重置下标
		$('.goodsMsg table tr').each(function(index, value) {
			if (index > 0) {
				$(value).find('td').eq(0).html(index);
			}
		});
		$('.goodsMsg table tr').last().before().css('display','');
		$(".goodsMsg table tr").last().children().first().html("合计");
	});
	$('.goodsDelete').click(function () {
		$('.goodsMsg input[name="select_one"]:checked').parent().parent().remove();
		$('.goodsMsg table tr').each(function (index, value) {
			if (index > 0) {
				$(value).find('td').eq(0).html(index);
			}
		});
		$(".goodsMsg table tr").last().children().first().html("合计");

		var sum = 0;
		$('.danjia').each(function(){
    		var value = $(this).parent().parent().next().next().children().text().replace(/[^0-9]/ig,"");
    		if(value == ""){
    			value = 0;
    		}
			sum = parseInt(value) + parseInt(sum);
		});
		$(".zongjia").html(sum);

		var max = 0;
		$('.number').each(function(){
			var soga = $(this).val();
			if(soga == ""){
    			soga = 0;
    		}
			max = parseInt(soga) + parseInt(max);

		})
		$(".zongshu").html(max);
	});
	//按商品进行搜索
	$(document).on('focus','.find',function(){
		var t = $(this);
		//获取焦点时原有值
		sou = t.val();
		//失去焦点事件
		t.blur(function(){
			//失去焦点时的新值
			var newsou = t.val();
			//判断,如果新的值与原值不同则修改相应商品信息
			if(newsou != sou)
			{
				t.parents('tr').find('input[name="price[]"]').val('');
				t.parents('tr').find('input[name="total[]"]').val('');
				t.parents('tr').find('td:eq(7)').children().html('￥');
				t.parents('tr').find('input[name="content[]"]').val('');
				var sum = 0;
				$('.danjia').each(function(){
		    		var mmm = $(this).val();
		    		if(mmm == ""){
		    			mmm = 0;
		    		}
		    		var nnn =$(this).parent().parent().next().find('input[name="total[]"]').val();
		    		var sss = '';
		    		if(nnn == ''){
		    			sss = 0;
		    		}else{
		    			sss = parseFloat(mmm)*parseInt(nnn);
		    		}

					sum = parseFloat(sss) + parseFloat(sum);
					sum = parseFloat(sum).toFixed(2);
					$("input[name=price_main]").attr('value',sum);
				});
				$(".zongjia").html(sum);
				var max = 0;
				$('.number').each(function(){
					var soga = $(this).val();
					if(soga == ""){
		    			soga = 0;
		    		}
					max = parseInt(soga) + parseInt(max);
					$("input[name=sum]").attr("value",max);
				})
				$(".zongshu").html(max);

				var value = t.val();
				var rows = $('.goodsMsg table').find('tr').length;
				val = new Array();
				$('.goodsMsg table tr').find('select').each(function(){
		            var a = $(this).val();
		            if(a != null){
		                val.push(a);
		            }
		        })

		        arr = val.join(",");
				$.ajax({
					type:'POST',
					url:'/purchase/purchase_add.php',
					async:true,
					data:{"value":value,'bb':arr,'cc':rows},
					dataType:'json',
					'success':function(data){
						 if(data){
						 	//存在商品则去掉商品提示边框的红色
						 	t.parents('tr').find('select[name="product_id[]"]').css('border','');
		                    t.parent().next().find('select:first').empty();
		                    var str = "";
		                    for(var i=0;i<data.length;i++){
		                        str += "<option value="+data[i]['id']+">"+data[i]['name']+data[i]['format']+"</option>";
		                    }
		                    t.parent().next().find('select:first').append(str);
		                    t.parents('tr').find('td:nth-child(5)').html(data[0]['part_name']);
		                    t.parents('tr').find("input[name='price[]']").val(data[0]['price_purchase']);
		                }
					}
				});
			}
		})
    });
	$(document).on("change",".guige",function(){
		var a = $(this);
		var v = a.val();
		// a.parent().next().text('');
		a.parent().next().next().find("input[name='price[]']").val('');
		a.parent().next().next().next().find("input[name='total[]']").val('');
		a.parent().next().next().next().next().children().text('￥');

		var sum = 0;
		$('.danjia').each(function(){
    		var mmm = $(this).val();
    		if(mmm == ""){
    			mmm = 0;
    		}
    		var nnn = $(this).parent().parent().next().find('input[name="total[]"]').val();
    		var sss = '';
    		if(nnn=='')
    		{
    			sss=0;
    		}else{
    			sss = parseFloat(mmm)*parseInt(nnn);
    		}
			sum = parseFloat(sss) + parseFloat(sum);
			sum = parseFloat(sum).toFixed(2);
			$("input[name=price_main]").attr('value',sum);
		});
		$(".zongjia").html(sum);

		var max = 0;
		$('.number').each(function(){
			var soga = $(this).val();
			if(soga == ""){
    			soga = 0;
    		}
			max = parseInt(soga) + parseInt(max);

		})
		$(".zongshu").html(max);

		$.ajax({
			url:"purchase_add.php",
    		type:'post',
    		data:{'guige':v},
    		dataType:'json',
    		success:function(data){
    			if(data){
    				a.parent().next().html(data['unit']);
    				a.parent().next().next().find("input[name='price[]']").val(data['price_purchase']);

    			}
    		}
		})

	});
	//商品数量键盘抬起事件
	$(document).on("keyup",".number",function(){

		var b = $(this);
		//判断当前行是否已选择商品,没选择禁止填入数量
		var product = b.parents('tr').find('select[name="product_id[]"]').val();
		if(product=='')
		{
			b.parents('tr').find('select[name="product_id[]"]').css('border','1px solid #E74C3C');
			b.val('');
			return false;
		}

		var vale = b.val();
		var c = b.parent().prev().find("input:first").val();
		var  num = parseFloat(c)*parseInt(vale);
		num = parseFloat(num).toFixed(2);
		b.parent().next().children().html("￥"　+num);

		var sum = 0;
		$('.danjia').each(function(){
    		var mmm = $(this).val();
    		if(mmm == ""){
    			mmm = 0;
    		}
    		var nnn =$(this).parent().parent().next().find('input[name="total[]"]').val();
    		var sss = '';
    		if(nnn == ''){
    			sss = 0;
    		}else{
    			sss = parseFloat(mmm)*parseInt(nnn);
    		}

			sum = parseFloat(sss) + parseFloat(sum);
			sum = parseFloat(sum).toFixed(2);
			$("input[name=price_main]").attr('value',sum);
		});
		$(".zongjia").html(sum);
		var max = 0;
		$('.number').each(function(){
			var v = $(this).val();
			if(v == ""){
    			v = 0;
    		}
			max = parseInt(v) + parseInt(max);
			$("input[name=sum]").attr("value",max);
		})
		$(".zongshu").html(max);
	});
	//数量失去焦点事件去除提示用的红色边框
	$(document).on('blur','.number',function(){
		var val = $(this).val();
		if(val != '')
		{
			$(this).css('border','');
		}
	})
	//价格失去焦点事件
	$(document).on("blur",".danjia",function(){
		var e = $(this);
		var va = e.val();
		if(isNaN(va) || va<=0)
		{
			e.val('');
			return false;
		}
		var tol = e.parents('td').next().children().val();
		var numb = tol*va;
		e.parents('td').next().next().children().html("￥"　+numb);
		var sum = 0;
		$('.danjia').each(function(){
    		var value = $(this).parents('td').next().next().children().text().replace(/[^0-9]/ig,"");
    		if(value == ""){
    			value = 0;
    		}
			sum = parseInt(value) + parseInt(sum);
			$("input[name=price_main]").attr('value',sum);
		});
		$(".zongjia").html(sum);
	});

	if($(".guige").val() == null){
		$(".zongjia").text(0);
	}

	//托运公司失去焦点更改信息
	$('input[name=shipping_company]').blur(function(){
		var shipping_company = $(this).val();
		var purchase_id = $('input[name=id]').val();
		$.ajax({
			url:'purchase_detail_R.php',
			type:'post',
			data:{'shipping_company':shipping_company,'purchase_id':purchase_id},
		})
	})
	//运单号时区焦点更改信息
	$('input[name=waybill_number]').blur(function(){
		var waybill_number = $(this).val();
		var purchase_id = $('input[name=id]').val();
		$.ajax({
			url:'purchase_detail_R.php',
			type:'post',
			data:{'waybill_number':waybill_number,'purchase_id':purchase_id},
		})
	})

	$(".form-inline").validate({
		highlight:function (element,errorClass) {
			$(element).addClass('error_color');
			$(element).tooltip('show');
		},

		unhighlight:function (element,errorClass) {
			$(element).removeClass('error_color');
			$(element).tooltip('hide');
		},
		rules:{
			supplier_id:{
				required:true,
			},
			store_id:{
				required:true,
			},
			name:{
				required:true,
			},
            'price[]':{
                required: true,
            },
            'total[]':{
                required: true,
            },
            'product_id[]':{
                required: true,
            },
		},
		messages:{
			supplier_id:{
				required:'',
			},
			store_id:{
				required:'',
			},
			name:{
				required:'',
			},
            'price[]':{
                required:'',
            },
            'total[]':{
                required:'',
            },
            'product_id[]':{
                required:'',
            },
		}
	});
	$('[data-toggle="tooltip"]').tooltip('hide');

	$('.form-inline').submit(function(){
		var cc = true;
		$(".goodsMsg").find("input[name='total[]']").each(function(){
			var aa = $(this).val();
			if(aa == 0 || aa=='')
			{
				$(this).css('border','1px solid #E74C3C');
				cc =false;
				return false;
			}else
			{
				$(this).css('border','');
				cc = true;
			}
		})
		if(cc==false)
		{
			return false;
		}
	})
});
