$(function () {
	//验证颜色

	function regColor(){
		if($(".luyahetd_color").text()==='已完成'){

			$(".luyahetd_color").css("color","#2ECC71");
			$(".luyahetd_color").css("font-weight","bold");
		}else if($(".luyahetd_color").text()==='已拒绝'){

			$(".luyahetd_color").css("color","#999999");
			$(".luyahetd_color").css("font-weight","bold");
		}else if($(".luyahetd_color").text()==='未完成'){

			$(".luyahetd_color").css("color","#E74C3C");
			$(".luyahetd_color").css("font-weight","bold");
		}else if($(".luyahetd_color").text()==='待修改'){

			$(".luyahetd_color").css("color","#F1C40F");
			$(".luyahetd_color").css("font-weight","bold");
		}else if($(".luyahetd_color").text()==='未到货'){

			$(".luyahetd_color").css("color","#9B59B6");
			$(".luyahetd_color").css("font-weight","bold");
		}else{

			$(".luyahetd_color").css("color","#34495E");
			$(".luyahetd_color").css("font-weight","bold");
		}

	}

	//全选
	$('.table-hover input[name="select_one"]').click(function () {
	    if(this.checked){
    		$('.table-hover input[name="select_all"]').each(function () {
    			$(this).prop("checked",true);
    		});
	    }else{
    		$('.table-hover input[name="select_all"]').each(function () {
    			$(this).prop("checked",false);
    		});
	    }
	});

	//添加
	//这里弄一段比较通用的代码
	$('.customersMsg table tr').each(function (index, value) {
		if (index > 0) {
			$(value).find('td').eq(0).html(index);
		}
	});
	var trStr = $('.table-hover tr').eq(1).prop("outerHTML");
	$('.customersAdd').click(function () {
		$('.table-hover').append(trStr);
		//重置下标
		$('.table-hover tr').each(function (index, value) {
			if (index > 0) {
				$(value).find('td').eq(0).html(index);
			}
		});
	});
	//日期
	$('.customersMsg input[name="application_date"]').datetimepicker({
		language:'zh-CN',
		format:'yyyy-mm-dd',
		autoclose:true,
		minView:2,
	});
	//点击清空搜索框
	$('input[name=clear]').click(function(){
		$('input[name="application_date"]').val('');
		$('input[name="staff"]').val('');
	})

	$(".body").click(function(){
		var addon = new Array();
		$("input[name='select_all']").each(function(){
			if(this.checked){
				var num = $(this).parent().next().next().next().next().next().next().next().next().next().next().val();
				addon.push(num);
			}
		});
		if(addon.length != 0){
			MessageBox('/purchase/purchase_yes.php?addon='+addon+'', '审核通过', 300, 110); return false;
		}else{
			alert("没有选中任何东西");
		}
	});
	$(".edit").click(function(){
		var addon = new Array();
		$("input[name='select_all']").each(function(){
			if(this.checked){
				var num = $(this).parent().next().next().next().next().next().next().next().next().next().next().val();
				addon.push(num);
			}
		});
		if(addon.length != 0){
			MessageBox('/purchase/purchase_back_edit.php?addon='+addon+'', '打回修改', 300, 110); return false;
		}else{
			alert("没有选中任何东西");
		}
	})
	$(".yes").click(function(){
		var addon = new Array();
		$("input[name='select_all']").each(function(){
			if(this.checked){
				var num = $(this).parent().next().next().next().next().next().next().next().next().next().next().val();
				addon.push(num);
			}
		});
		if(addon.length != 0){
			MessageBox('/purchase/purchase_yes.php?addon='+addon+'', '审核通过', 300, 110); return false;
		}else{
			alert("没有选中任何东西");
		}
	})
	$(".no").click(function(){
		var addon = new Array();
		$("input[name='select_all']").each(function(){
			if(this.checked){
				var num = $(this).parent().next().next().next().next().next().next().next().next().next().next().val();
				addon.push(num);
			}
		});
		if(addon.length != 0){
			MessageBox('/purchase/purchase_no.php?addon='+addon+'', '拒绝', 300, 110); return false;
		}else{
			alert("没有选中任何东西");
		}
	})
});