$(function(){
	$(".channel-add").click(function(){
		$(".modal-channelset").show();
	});

	var $this;
	var $this1;
	
	ChangeChannel();
	DelChannel();
	function ChangeChannel(){
		$(".channel-change").click(function(){
			$(".modal-channelset1").show();
			$edit = $(this);
			var name=$(this).parent().next().html();
			var comment=$(this).parent().next().next().html();
			$("#editname").val(name);
			$("#editcomment").val(comment);
		});
	}
	$(".edit").click(function(){
			$(".modal-channelset1").hide();

		var id = $edit.attr("uid");
		var name = $("#editname").val();
		var comment = $("#editcomment").val();
		$.ajax({
		   type: "POST",
		   url: "/crm/editsales.php",
		   data:{"id":id,"name":name,"comment":comment},
		   success: function(msg){
                if(msg==1){
                	alert("修改成功!");
					window.location.reload();
                }else{
                	alert("修改失败!");
                }
		   },
		   error: function(){
		   	 alert("ajax请求失败");
		   }
		});
	})
	function DelChannel(){
		$(".channel-del").click(function(){
			// $this1=$(this);
			$(".modal-channelset2").show();
			$del = $(this);
		});
	}
	$(".del").click(function(){
		$(".modal-channelset2").hide();
		var id=$del.attr("uid");
		$.ajax({
		   type: "POST",
		   url: "/crm/delsales.php",
		   data:{"id":id},
		   success: function(msg){
                if(msg==1){
                	alert("删除成功!");
					window.location.reload();
                }else{
                	alert("删除失败!");
                }
		   },
		   error: function(){
		   	 alert("ajax请求失败");
		   }
		});
	})
})