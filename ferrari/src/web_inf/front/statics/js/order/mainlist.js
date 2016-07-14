$(".rrow").change(function(){
	var p=$(this).val();
	var pps=$("#pps").html();
	var seach = String($("#seach").val());
	if(seach){
		window.location.href = "/order/mainlist.php?num="+p+"&page="+pps+seach; 	
	}else{
		window.location.href = "/order/mainlist.php?num="+p+"&page="+pps;
	}
	
	})