$(function(){
	$(".add-normalset").click(function(){
		$(".modal-addnormalset").show();
	});
	//修改
	$(".abnormalset-change").click(function(){
         editid = $(this).attr('uid');
		 $(".modal-addnormalset1").show();
		 editname = $(this).parent().next("td").html();
		 $("#edit").parent().prev().children().children("input").val(editname);
	});
	$("#edit").click(function(){
		$(".modal-addnormalset1").hide();
		var id = editid;
		var editname = $(this).parent().prev().children().children("input").val();
		$.ajax({
		   type: "POST",
		   url: "/order/editabnormal.php",
		   data:{"id":id,"name":editname},
		   success: function(msg){
                if(msg==1){
                	alert("修改成功!");
                	window.location.reload()
                }else{
                	alert("修改失败!");
                	window.location.reload()
                }
		   },
		   error: function(){
		   	 alert("ajax请求失败")
		   }
		});
	})
	//删除
	$(".abnormalset-del").click(function(){
         delid = $(this).attr("uid");
		 $(".modal-addnormalset2").show();
	});
	$("#del").click(function(){
		$(".modal-addnormalset2").hide();
		var id = delid;
		$.ajax({
		   type: "POST",
		   url: "/order/delabnormal.php",
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