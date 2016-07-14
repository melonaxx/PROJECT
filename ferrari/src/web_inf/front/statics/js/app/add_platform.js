$(function(){
	$(".plat-add").click(function(){
		$(".modal-addplatform").show();
	});
	$(".platform-edit").click(function(){
		$(".modal-addplatform1").show();
		var $platformname=$(this).parent().siblings(".platform-name").html();
		var $platformcomment=$(this).parent().siblings(".platform-comment").html();
		var $platid = $(this).attr("uid");
		$(".modal-platname").val($platformname);
		$(".modal-platcomment").val($platformcomment);
		$(".modal-platid").val($platid);
	});
	$(".platform-del").click(function(){
		$(".modal-delplatform").show();
		var id=$(this).attr('uid');
		$(".del").attr("id",id);
		trtr = $(this).closest("tr");
	});
	$(".del").click(function(){
		$(".modal-delplatform").hide();
		id=$(this).attr("id");
		$.ajax({
		   type: "POST",
		   url: "/app/delplatform.php",
		   data:{id:id},
		   success: function(msg){
                if(msg==1){
                	alert("删除成功!");
                	trtr.remove();
                }else if(msg==0){
                	alert("删除失败!");
                }else{
                	alert(msg);
                }
		   },
		   error: function(){
		   	 alert("ajax请求失败")
		   }
		});
	})
})