$(function(){
	//序号
	//添加删除
	//这里弄一段比较通用的代码
	$('.goodsMsg table tr').each(function (index, value) {
		if (index > 0) {
			$(value).find('td').eq(0).html(index);
		}
	});
	var trStr = $('.goodsMsg table tr').eq(1).prop("outerHTML");
	$('.goodsAdd').click(function () {
		$('.goodsMsg table').append(trStr);
		//重置下标
		$('.goodsMsg table tr').each(function (index, value) {
			if (index > 0) {
				$(value).find('td').eq(0).html(index);
			}
		});
	});
	$('.goodsDelete').click(function () {
		$('.goodsMsg input[name="select_one"]:checked').parent().parent().remove();
		$('.goodsMsg table tr').each(function (index, value) {
			if (index > 0) {
				$(value).find('td').eq(0).html(index);
			}
		});
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


	//日期
	$('.allocationMsg input[name="date"],.allocationListMsg input[name="date_start"],.allocationListMsg input[name="date_end"]').datetimepicker({
		language:'zh-CN',
		format:'yyyy-mm-dd HH:ii',
		autoclose:true,
		minView:0,
    });

	$(".deliverywarehouse").change(function(){
		var value = $(".deliverywarehouse").find("option:selected").val();
		$.ajax({
			'url':'stock_inventory_allocation.php',
			'async':true,
			'type':'POST',
			'data':{"name":value},
			'dataType':'json',
			'success':function(data){
				if(data === 0){
					$(".warehousereceipt").html("<option value='' style='display:none'>请选择发货仓库</option>");
				}else{
					var option = "<option value='' style='display:none'>请选择收货仓库</option>";
					for(var i = 0;i<data.length;i++){
						option += '<option value='+data[i]['id']+'>'+data[i]['name']+'</option>'
					}
					$(".warehousereceipt").html(option);
				}
			}
		});
	});

	$(document).on("blur",".find",function(){
		var t = $(this);
		t.parents('tr').find("input[name='total[]']").val('');
		t.parents('tr').find("input[name='body[]']").val('');
		var text = t.val();
		var rows = $('table').find('tr').length;
		val = new Array();
		$('table tr').find('select').each(function(){
            var a = $(this).val();
            if(a != null){
                val.push(a);
            }
        })
        arr = val.join(",");
		$.ajax({
			'url':'stock_inventory_allocation.php',
			'async':true,
			'type':'POST',
			'data':{"text":text,"rows":rows,"arr":arr},
			'dataType':'json',
			'success':function(data){
				var option = '';
				for(var i = 0;i<data.length;i++){
					option += '<option value='+data[i]['id']+'>'+data[i]['name']+','+data[i]['format']+'</option>'
				}
				t.parent().next().children().html(option);
			}
		});
	});
	$('table').on("change",'.name',function(){
		$(this).parents('tr').find("input[name='total[]']").val('');
		$(this).parents('tr').find("input[name='body[]']").val('');
	})
	$('input[name="total[]"]').on('keyup',function(){
		var val = $(this).val();
		var re  = /^[0-9]*[1-9][0-9]*$/;
		if(!re.test(val))
		{
			$(this).val('');
			return false;
		}
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
			delivery_warehouse:{
				required:true,
			},
			warehouse_receipt:{
				required:true,
			}
		},
		messages:{
			delivery_warehouse:{
				required:'',
			},
			warehouse_receipt:{
				required:'',
			}
		}
	});
	$('[data-toggle="tooltip"]').tooltip('hide');

	$("input[name='send']").click(function(){
		$("input[name='total[]']").each(function(){
			var num = $(this).val();
			if(num == ""){
				$(this).css("border","1px solid red");
				 sta = false;
			}else{
				 sta = true;
			}
		})

		if(sta == false){
			return false;
		}

		$(".name").each(function(){
			var vall = $(this).val();
			if(vall == null){
				$(this).css("border","1px solid red");
				 stauts = false;
			}else{
				 stauts = true;
			}
		})

		if(stauts == false){
			return false;
		}
	})
});