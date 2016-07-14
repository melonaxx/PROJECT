$(function(){
	$('.customer-del').click(function(){
		var id=$(this).attr("uid");
		trtr=$(this).closest("tr");
		$(".modal-customer").show();
		$(".custom-sure").attr("id",id);
	});
	$(".custom-sure").click(function(){
		$(".modal-customer").hide();
		var id=$(this).attr("id");
		$.ajax({
		   type: "POST",
		   url: "/crm/delsupplier.php",
		   data:{id:id},
		   success: function(msg){
                if(msg==1){
                	alert("删除成功!");
                	trtr.remove();
                }else{
                	alert("删除失败!");
                }
		   },
		   error: function(){
		   	 alert("ajax请求失败")
		   }
		});
	})
})

$(".rrow").change(function(){
	var p=$(this).val();
	var pps=$("#pps").html();
	var seach = String($("#seach").val());
	if(seach){
		window.location.href = "/crm/supplier.php?num="+p+"&page="+pps+"&seach="+seach; 	
	}else{
		window.location.href = "/crm/supplier.php?num="+p+"&page="+pps;
	}
	
})

var flag = false;

$("#seach").change(function(){
	var seach = $("#seach").val();
	if(seach){
		flag = true;
		$("#seach").removeClass("sousou");
	}else{
		flag =false;
	}
})

function fun(){
	return flag;
}
$("#sou").click(function(event){
	event.stopPropagation();
	var seach = $("#seach").val();
	if(!seach){
	$("#seach").addClass("sousou");
}
})
if($("#tb tr").length==0){
	$("#noe").show();
}
$("body").click(function(){
		$("#seach").removeClass("sousou");
})