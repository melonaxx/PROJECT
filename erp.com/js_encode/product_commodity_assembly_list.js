$(function(){
	$("input[name='select_all']").click(function(){
		if(this.checked){
			$("input[name='select_one']").prop("checked",true);
		}else{
			$("input[name='select_one']").prop("checked",false);
		}
	});
	$(".delete").click(function(){
		var addon = new Array();
		$("input[name='select_one']").each(function(){
			if(this.checked){
				var num = $(this).attr("id");
				addon.push(num);
			}
		});
		if(addon.length == 0){
			alert("没有选中任何东西");
		}else{
			MessageBox('/product/product_delete_record.php?addon='+addon+'', '删除商品', 300, 110); return false;
		}
	});
	$(".edit_category").click(function(){
		var addon = new Array();
		$("input[name='select_one']").each(function(){
			if(this.checked){
				var num = $(this).attr("id");
				addon.push(num);
			}
		});
		if(addon.length == 0){
			alert("没有选中任何东西");
		}else{
			MessageBox('/product/product_edit_category.php?addon='+addon+'', '修改品牌', 300, 110); return false;
		}
	});
	$(".edit_brand").click(function(){
		var addon = new Array();
		$("input[name='select_one']").each(function(){
			if(this.checked){
				var num = $(this).attr("id");
				addon.push(num);
			}
		});
		if(addon.length == 0){
			alert("没有选中任何东西");
		}else{
			MessageBox('/product/product_edit_brand.php?addon='+addon+'', '修改品牌', 300, 110); return false;
		}
	});
})