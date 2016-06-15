$(function () {

	$('.table-accessories input[name="select_all"]').click(function () {
	    if(this.checked){   
    		$('.table-accessories input[name="select_one"]').each(function () {
    			$(this).prop("checked",true);
    		});
	    }else{   
    		$('.table-accessories input[name="select_one"]').each(function () {
    			$(this).prop("checked",false);
    		});
	    }
	});

	$('.propertyDeleteaa').click(function () {
	$('.table-property  input[name="select_one"]:checked').parent().parent().remove();
		$('.table-property tr').each(function (index, value) {
			if (index > 0) {
				$(value).find('td').eq(0).html(index);
			}
		});
	});
	$('.propertyDeleteaa').click(function () {
	$('.table-accessories  input[name="select_one"]:checked').parent().parent().remove();
		$('.table-accessories tr').each(function (index, value) {
			if (index > 0) {
				$(value).find('td').eq(0).html(index);
			}
		});
	});

	$('.customersAdd').click(function(){
		$(this).parent().next().children().append('<tr><td class="center"></td><td class="center"><input type="checkbox" name="select_one" /></td><td class="seek"><input type="text" class="form-control input-sm merger_two_row_4 input_border seach" placeholder="搜索"/></td><td class="center"><img src="text" class="img-rounded"></td><td class="form-group"><div class="div"></div></td><td></td></tr>');
	})

	var talb=$('.table-accessories tr').eq(1).clone(true);
	$('.customersAdd').click(function () {
		$('.table-accessories tr').each(function (index, value) {
			if (index > 0) {
				$(value).find('td').eq(0).html(index);
			}
		});
	});	
	var trStr = $(".ttbale tr").eq(1).prop("outerHTML");
	$('.propertyAdd').click(function(){
		$(this).parent().next().children().append(trStr);
	})

	var talb=$('.table-property tr').eq(1).clone(true);
	$('.propertyAdd').click(function () {
		$('.table-property tr').each(function (index, value) {
			if (index > 0) {
				$(value).find('td').eq(0).html(index);
			}
		});
	});	




	$('.display_show').click(function(){
		$(this).parents().next().css('display','');
		$(this).css('display','none');
		$(this).next().css('display','');
	})
	$('.display_nonea').click(function(){
		$(this).parents().next().css('display','none');
		$(this).css('display','none');
		$(this).prev().css('display','');
	})


$(".table-accessories").on('keypress','input:text',function(event){
	if(event.keyCode == 13){
		return false;
	}
});

$('.table-accessories').on('keydown','.seek',function(e){
if(e.keyCode==13){
	var seachText=$(this).children().val();
	if(seachText!=""){
		var tab="<select name='product[]' class='form-control input-sm input_border merger_three_row_8'>";
		var _this = this;
		$.ajax({
			type:'post',
			url:'/product/product_commodity_input.php',
			data:{
				seachText: seachText
			},
			success:function(msg, xhr){
					var json = $.parseJSON(msg);
					$.each(json, function(index, value){
						if(value.name.indexOf(seachText)!=-1){
							tab += "<option value="+ value.id + ">" + value.name + "</optiom>";
						}
					})
				tab += "</select>";
				$(_this).next().next().html(tab);
				$(_this).next().next().next().html('<input type="text" class="form-control input-sm merger_three_row_4 input_border" name="total[]"/>');
	
				$(this).children().next().next().html()
				tab="<select><option></optiom>";
			},
			error:function(){
				alert('无数据');
			}
		});
	}else{
		var seachText=$(this).next().next().html('<div class="div"></div>');
		var seachText=$(this).next().next().next().html('');
	}


}
});







$('.form-inline').validate({
	highlight:function (element,errorClass) {
		$(element).addClass('error_color');
		$(element).tooltip('show');
	},
	
	unhighlight:function (element,errorClass) {
		$(element).removeClass('error_color');
		$(element).tooltip('hide');
	},
	rules:{
		name:{
			required:true,
		},
	},
	messages:{
		name:{
			required:'',
		},
	}
});
$('[data-toggle="tooltip"]').tooltip('hide');
	$(".aa").click(function(){
		$(":reset"); 
		var option="<option></option>";
		$("select[name='format_value_1']").empty();
		$("select[name='format_value_2']").empty();
		$("select[name='format_value_3']").empty();
		$("select[name='format_value_4']").empty();
		$("select[name='format_value_5']").empty();
	});
	$(document).on('change','.attribute',function(){
		var t = $(this);
		var value = t.val();
		if(value == '0'){
			var aa =  "<option  value='0'></option>";
			t.parent().next().find('select:first').empty();
			t.parent().next().find('select:first').append(aa);
		}
		$.ajax({
			'url':'/product/product_commodity_input.php',
			'async':true,
			'type':'POST',
			'data':{"value":value},
			'dataType':'json',
			'success':function(data){
				var option='<option value='+0+'></option>';
				for(var i = 0; i < data.length; i++){
					option += '<option value='+data[i]['id']+'>'+data[i]['body']+'</option>';
				}
				t.parent().next().find('select:first').empty();
				t.parent().next().find('select:first').append(option);
				
			}
		});
	})
});





