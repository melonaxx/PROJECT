$(function(){
	$(".wuliu-del").click(function(){
		delid = $(this).attr("uid");
		$(".modal-wuliu").show();
	})
	$("#del").click(function(){
		$(".modal-wuliu").hide();
		var id = delid;
		$.ajax({
		   type: "POST",
		   url: "/admin/delwuliu.php",
		   data:{"id":id},
		   success: function(msg){
                if(msg==1){
                	alert("删除成功!");
                	window.location.reload()
                }else{
                	alert("删除失败!");
                	window.location.reload()
                }
		   },
		   error: function(){
		   	 alert("ajax请求失败")
		   }
		});
	})
})