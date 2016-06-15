$(function(){
//手动出库入库:全选/全不选
	$("input[name='select_all']").click(function(){
		if(this.checked){
			$(".tab_sel input[type='checkbox']").prop("checked",true);
		}else{
			$(".tab_sel input[type='checkbox']").prop("checked",false);
		}
	});
//出库入库记录：全选/全不选
	$("input[name='select-all']").click(function(){
		if(this.checked){
			$(".tab-sel input[type='checkbox']").prop("checked",true);
		}else{
			$(".tab-sel input[type='checkbox']").prop("checked",false);
		}
	});
//手动出库入库：删除
	$(".delete").click(function(){
		$(".tab_sel tr input[name='select_one']:checked").parent().parent().remove();
		//重置下标
		$('.tab_sel tr').each(function (index, value) {
			if (index > 0) {
				$(value).find('td').eq(0).html(index);
			}
		});
	})
//手动出库入库：添加
	var trStr = $(".tab_sel tr").eq(1).prop("outerHTML");
	$(".usAdd").click(function(){
   $(".tab_sel").children().append(trStr);
//重置下标
	$(".tab_sel tr").each(function(index,value){
			if(index >0){
				$(value).find('td').eq(0).html(index);
			}
		});
	});
	//商品搜索
	var ware = $(".warehouse").val();
	$(document).on("blur",".find",function(){
		var t = $(this);
		$(this).parents('tr').find("input[name='total[]']").val('');
		$(this).parents('tr').find("input[name='body[]']").val('');
		var value = t.val();
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
			'url':'/stock/stock_warehouse_storage.php',
			'async':true,
			'type':'POST',
			'data':{'value':value,'rows':rows,'arr':arr},
			'dataType':'json',
			'success':function(data){
				var option = '';
				for(var i = 0;i<data.length;i++){
					option += '<option value='+data[i]['id']+'>'+data[i]['name']+data[i]['format']+'</option>'
				}
				t.parent().next().children().html(option);
			}
		});
	})
	$('table').on("change",'.name',function(){
		$(this).parents('tr').find("input[name='total[]']").val('');
		$(this).parents('tr').find("input[name='body[]']").val('');
	})

	$('input[name="total[]"]').on('keyup',function(){
		var val = $(this).val();
		var re = /^[0-9]*[1-9][0-9]*$/;
		if(!re.test(val))
		{
			$(this).val('');
			return false;
		}
	})
	//提交事件
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
			store_id:{
				required:true,
			},
			type:{
				required:true,
			}

		},
		messages:{
			store_id:{
				required:'',
			},
			type:{
				required:'',
			}

		}
	});
	$('[data-toggle="tooltip"]').tooltip('hide');
})