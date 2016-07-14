	$(".rrow").change(function(){

		var p=$(this).val();
		var pps=$("#pps").html();
		var search = String($("#exampleInputName2").val());

		window.location.href = "/product/product_foundry_manage.php?num="+p; 	
		// window.location.href = "/product/product_foundry_manage.php?num="+p; 	

	})