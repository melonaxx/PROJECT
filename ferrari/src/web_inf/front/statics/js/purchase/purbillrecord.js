$(function(){
	$(".purbill-detail").click(function(){
		$("#xq").empty();
		var uid = $(this).attr("uid");
		$.ajax({
			type: "POST",
			url: "/purchase/piaoxq.php",
			data:{"uid":uid},
			dataType:"json",
			success: function(msg){
				$.each(msg,function(idx,item){
						number = item.number;
						taxprice = item.taxprice;
						tax = item.tax;
						notaxprice = item.notaxprice;
						$("#xq").append("<tr><td>"+number+"</td><td>"+taxprice+"</td><td>"+tax+"</td><td>"+notaxprice+"</td></tr>")
				});
			}
		})
		$(".modal-record").show();
	});

	//分页
	$(".rrow").change(function(){
		var p=$(this).val();
		var pps=$("#pps").html();
		window.location.href = "/purchase/purbillrecord.php?num="+p+"&page="+pps; 		
	})
	// $(".caret").click(function(){
	// 	$(this).parents(".dropdown").find(".dropdown_body").slideToggle();
	// });
})