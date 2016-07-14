$(".rrow").change(function(){
	var p=$(this).val();
	var pps=$("#pps").html();
	var seach = String($("#seach").val());
	if(seach){
		window.location.href = "/report/traderecord.php?num="+p+"&page="+pps+"&seach="+seach; 	
	}else{
		window.location.href = "/report/traderecord.php?num="+p+"&page="+pps;
	}
})
if($("#tb tr").length==0){
	$(".no-find").show();
}
