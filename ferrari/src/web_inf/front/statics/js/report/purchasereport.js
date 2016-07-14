$(function(){
$("#sousou").keyup(function(){

	var comment=$.trim($(this).val());
	var $this=$(this);
	if(comment){
	$.ajax({
			type: "POST",
			url: "/report/findproduct.php",
			data:{comment:comment},
			dataType:"json",
			success: function(msg){
				console.log(msg);
				$("#selsp").empty();
					$.each(msg,function(idx,item){ 
						var id = item.productid;
						var name = item.name;
						var guige = item.guige;
						$("#selsp").append("<option value="+id+">"+name+"（"+guige+"）</option>");		
					})
			}
		})
	}
});

if($("#tb tr").length == 0){
	$("#tf").hide();
	$(".no-find").show();
}
})