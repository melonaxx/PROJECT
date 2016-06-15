$(function () {
	//获取输入的入库数量，改变时，采购单总量与入库数之差为在途数量
	$(document).on('keyup','input[name="sum[]"]',function(){
		var t = $(this);
		var sum = t.val();
		var re = /^[0-9]*[1-9][0-9]*$/;
		if(!re.test(sum))
		{
			alert('请输入大于零的整数值！');
			t.val('');
			return flase;
		}
		//获取商品id
		var no = t.parents('tr').find('input[name="no[]"]').val();
		//获取采购数量
		var total = t.parents('tr').find('input[name="total[]"]').val().replace(/[^0-9]/ig,"");
		if(total == ""){
			total = 0;
		}
		var in_sum_old = t.parents('tr').find('input[name="in_sum[]"]').val().replace(/[^0-9]/ig,"");
		if(in_sum_old == ""){
			in_sum_old = 0;
		}
		var in_sum2_old = t.parents('tr').find('input[name="in_sum2[]"]').val().replace(/[^0-9]/ig,"");
		if(in_sum2_old == ""){
			in_sum2_old = 0;
		}
		var out_sum_old = t.parents('tr').find('input[name="out_sum[]"]').val().replace(/[^0-9]/ig,"");
		if(out_sum_old == ""){
			out_sum_old = '0';

		}
		var zt_sum_old = t.parents('tr').find('input[name="zt_sum[]"]').val().replace(/[^0-9]/ig,"");
		if(zt_sum_old == ""){
			zt_sum_old = 0;
		}
		var sum_x = t.val().replace(/[^0-9]/ig,"");
		if(sum_x == ""){
			sum_x = 0;
		}
		if(parseInt(sum_x) > parseInt(total)){
			sum_x = total;
			t.val(sum_x);
		}
		//新的入库数量
		in_sum2_old = sum_x;
		var in_sum_x = parseInt(in_sum2_old)+parseInt(in_sum_old);
		if(parseInt(in_sum_x)>parseInt(total)){
			in_sum_x = total;
			sum_x = parseInt(total)-parseInt(in_sum_old);
			t.val(sum_x);
		}
		//新的出库数量
		var zt_sum_x = parseInt(total)-parseInt(out_sum_old)-parseInt(in_sum_x);
		if(parseInt(zt_sum_x) < 1){
			zt_sum_x = 0;
		}
		//给已入库和在途负新值
		t.parents('tr').find('input[name="zt_sum[]"]').val(zt_sum_x);
		t.parents('tr').find('td').eq(10).find('span').text(zt_sum_x);
		t.parents('tr').find('input[name="in_sum2[]"]').val(sum_x);
		t.parents('tr').find('td').eq(9).find('span').text(in_sum_x);
		t.parents('tr').find('input[name="out_sum[]"]').val(out_sum_old);
		t.parents('tr').find('td').eq(11).find('span').text(out_sum_old);

		var in_sum = 0;
		var in_sum2 = 0;
		var zt_sum = 0;
		var out_sum = 0;
		var out_sum2 = 0;
		$('.goodsMsg tr').find('input[name="in_sum[]"]').each(function(){
			var v = $(this).val().replace(/[^0-9]/ig,"");
			if(v == ''){
				v = 0;
			}
			in_sum = parseInt(v)+parseInt(in_sum);
		})
		$('.goodsMsg tr').find('input[name="in_sum2[]"]').each(function(){
			var v = $(this).val().replace(/[^0-9]/ig,"");
			if(v == ''){
				v = 0;
			}
			in_sum2 = parseInt(v)+parseInt(in_sum2);
		})

		$('.goodsMsg tr').find('input[name="zt_sum[]"]').each(function(){
			var v = $(this).val().replace(/[^0-9]/ig,"");
			if(v == ''){
				v = 0;
			}
			zt_sum = parseInt(v)+parseInt(zt_sum);
		})

		$('.goodsMsg tr').find('input[name="out_sum[]"]').each(function(){
			var v = $(this).val().replace(/[^0-9]/ig,"");
			if(v == ''){
				v = 0;
			}
			out_sum = parseInt(v)+parseInt(out_sum);
		})

		$('.goodsMsg tr').find('input[name="out_sum2[]"]').each(function(){
			var v = $(this).val().replace(/[^0-9]/ig,"");
			if(v == ''){
				v = 0;
			}
			out_sum2 = parseInt(v)+parseInt(out_sum2);
		})
		var yiruku = parseInt(in_sum) + parseInt(in_sum2);
		var tuihuo = parseInt(out_sum) + parseInt(out_sum2);
		var zongshu_rk = 0;
		var zongjia_rk = 0;
		$('.currentMsg tr td:nth-child(1)').find('input[name="no_rk[]"]').each(function(){
			var a = $(this);
			var v = a.val();
			if(v == no){
				$(this).parents('tr').find('input[name="in_sum_rk[]"]').attr('value',sum_x);
				var p = $(this).parents('tr').find('input[name="price_rk[]"]').val();
				var xiaoji = parseFloat(p)*parseInt(sum_x);
				$(this).parent().next().next().next().next().next().next().find('span').text(xiaoji);
				$(this).parents('tr').find('input[name="xiaoji[]"]').attr('value',xiaoji);
			}
		});

		$('.currentMsg tr').find('input[name="in_sum_rk[]"]').each(function(){
			var v = $(this).val();
			if(v == ''){
				v = 0;
			}
			zongshu_rk = parseInt(v)+parseInt(zongshu_rk);
		})
		$('.currentMsg tr').find('input[name="xiaoji[]"]').each(function(){
			var v = $(this).val();
			if(v == ''){
				v = 0;
			}
			zongjia_rk = parseFloat(v)+parseInt(zongjia_rk);
		})
		$('.totalMsg .yiruku').text(yiruku);
		$('.totalMsg').find('input[name="yiruku"]').attr('value',yiruku);
		$('.totalMsg .zaitu').text(zt_sum);
		$('.totalMsg').find('input[name="zaitu"]').attr('value',zt_sum);
		$('.totalMsg .tuihuo').text(tuihuo);
		$('.totalMsg').find('input[name="tuihuo"]').attr('value',tuihuo);
		$('.totalMsg1 .zongshu_rk').text(zongshu_rk);
		$('.totalMsg1').find('input[name="zongshu_rk"]').attr('value',zongshu_rk);
		$('.totalMsg1 .zongjia_rk').text(zongjia_rk);
		$('.totalMsg1').find('input[name="zongjia_rk"]').attr('value',zongjia_rk);

	})
	//双击修改采购商品备注
	$('.content').dblclick(function(){
		var purchase_id = $('input[name=id]').val();
		var product_id = $(this).parents('tr').find('input[name="product_id[]"]').val();
		var td = $(this);
		var val = $(this).text();
		var input = $("<input type='text' class='form-control input-sm' style='width:193px' value='"+val+"'/>");
		td.html(input);
		input.dblclick(function(){ return false; });
		input.trigger("focus");
		input.blur(function(){
			var newval = $(this).val();
			//判断文本有没有修改
			if (newval != val) {
				$.ajax({
					url:'purchase_in_storage_detail.php',
					type:'post',
					data:{'content':newval,'purchase_id':purchase_id,'product_id':product_id},
				})
				td.html(newval);
			}else{
				td.html(newval);
			}
		})
	});
	//采购摘要更改信息
	$('textarea[name=brief]').blur(function(){
		var brief = $(this).val();
		var purchase_id = $('input[name=id]').val();
		$.ajax({
			url:'purchase_in_storage_detail.php',
			type:'post',
			data:{'brief':brief,'purchase_id':purchase_id},
		})
	})
	//采购备注更改信息
	$('textarea[name=body]').blur(function(){
		var body = $(this).val();
		var purchase_id = $('input[name=id]').val();
		$.ajax({
			url:'purchase_in_storage_detail.php',
			type:'post',
			data:{'body':body,'purchase_id':purchase_id},
		})
	})
	//托运公司失去焦点更改信息
	$('input[name=shipping_company]').blur(function(){
		var shipping_company = $(this).val();
		var purchase_id = $('input[name=id]').val();
		$.ajax({
			url:'purchase_in_storage_detail.php',
			type:'post',
			data:{'shipping_company':shipping_company,'purchase_id':purchase_id},
		})
	})
	//运单号时区焦点更改信息
	$('input[name=waybill_number]').blur(function(){
		var waybill_number = $(this).val();
		var purchase_id = $('input[name=id]').val();
		$.ajax({
			url:'purchase_in_storage_detail.php',
			type:'post',
			data:{'waybill_number':waybill_number,'purchase_id':purchase_id},
		})
	})

	//判断提交时入库商品数量,为0则不提交
	$('.form-inline').submit(function(){
		var zongshu_rk = $('input[name=zongshu_rk]').val();
		if(zongshu_rk<=0)
		{
			//
			$(".num").focus();
			$(".num").css("border","1px solid red");
			return false;
		}
	})

});
