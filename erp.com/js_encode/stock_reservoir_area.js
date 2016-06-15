$(function(){
	$(".opt").change(function(){
		var value = $(this).val();
		$.post("/stock/stock_reservoir_area.php",{"name":value},function(data){
			alert(data);
		})
	})
})