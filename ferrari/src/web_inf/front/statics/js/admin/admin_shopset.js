$(function(){
	$(".shopset-add").click(function(){
		$(".modal-shopset").show();
	});
	//删除类型
	$(".shopset-del").click(function(){
		delid = $(this).attr("uid");
		$(".modal-shopset1").show();
	});
	$("#del").click(function(){
		$(".modal-shopset1").hide();
		var id = delid;
		$.ajax({
		   type: "POST",
		   url: "/admin/delshop.php",
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
	//修改类型
	$(".shopset-change").click(function(){
		$(".modal-shopset2").show();
		editid = $(this).attr('uid');
		editname = $(this).closest("tr").children("td:eq(2)").html();
		editcomment = $(this).closest("tr").children("td:eq(3)").html();
		$("#edit").parent().prev().children().children("input").val(editname);
		$("#edit").parent().prev().children().children("textarea").val(editcomment);
	});
	$("#edit").click(function(){
		$(".modal-shopset2").hide();
		var id = editid;
		var editname = $(this).parent().prev().children().children("input").val();
		var editcomment = $(this).parent().prev().children().children("textarea").val();
		$.ajax({
		   type: "POST",
		   url: "/admin/editshop.php",
		   data:{"id":id,"name":editname,"comment":editcomment},
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
	});

})