$(".rrow").change(function(){
		var p=$(this).val();
		var pps=$("#pps").html();
		window.location.href = "/purchase/openbillrecord.php?num="+p+"&page="+pps; 		
	})