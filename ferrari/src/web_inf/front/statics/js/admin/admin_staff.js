$(function(){
	/*删除人员*/
	$(".staff-del").click(function(){
		var $this=$(this);
		$(".modal-staff").show();
		$(".staff-sure").on("click",function(){
			$(".modal-staff").hide();
			$this.parent().parent().remove();
			$(".staff-td").each(function(i){
			   	 $(this).html(i+1);
			});
		})
	});
})
$(".rrow").change(function(){
	var p=$(this).val();
	var pps=$("#pps").html();
	var seach = String($("#seach").val());
	if(seach){
		window.location.href = "/admin/admin_staff.php?num="+p+"&page="+pps+"&seach="+seach; 	
	}else{
		window.location.href = "/admin/admin_staff.php?num="+p+"&page="+pps;
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