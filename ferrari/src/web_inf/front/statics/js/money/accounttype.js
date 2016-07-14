$(function () {
	//删除
	$('.sort_delete').click(function () {
		$(".modal-tip").show();
		del = $(this);
	});
	$(".delacc").click(function(){
		$(".modal-tip").hide();
		var id = del.attr("uid");
		$.ajax({
		   type: "POST",
		   url: "/money/delaccounttype.php",
		   data:{id:id},
		   success: function(msg){
                if(msg==1){
                	alert("删除成功!");
        		 window.location.reload()
                }else{
                	alert("删除失败!");
                }
		   },
		   error: function(){
		   	 alert("ajax请求失败")
		   }
		});
	})
	//添加财务科目类别
	$(".add_this_sort").click(function(){
		$(".modal-accountsubject3").show();
	});
	$(".addacc").click(function(){
		$(".modal-accountsubject3").hide();
		var name = $(".addname").val();
		var comment = $(".addcomment").val();
		$.ajax({
		   type: "POST",
		   url: "/money/addaccounttype.php",
		   data:{name:name,comment:comment},
		   success: function(msg){
                if(msg==1){
                	alert("添加成功!");
        		 window.location.reload();
                }else{
                	alert("添加失败!");
                }
		   },
		   error: function(){
		   	 alert("ajax请求失败")
		   }
		});
	})
	//修改科目类别
	$(".sort_edit").click(function(){
		$(".modal-accountsubject4").show();
		var id = $(this).attr("uid");
		$.ajax({
		   type: "POST",
		   url: "/money/findaccounttype.php",
		   data:{id:id},
		   success: function(msg){
		   		var json = eval('('+msg+')');
              	$(".editname").val(json.typename);
              	$(".editcomment").val(json.remark);
		   },
		});
		edit = $(this);
	});
	$(".editacc").click(function(){
		$(".modal-accountsubject4").hide();
		var id = edit.attr("uid");
		var name = $(".editname").val();
		var comment = $(".editcomment").val();
		$.ajax({
		   type: "POST",
		   url: "/money/editaccounttype.php",
		   data:{id:id,name:name,comment:comment},
		   success: function(msg){
                if(msg==1){
                	alert("修改成功!");
        		 window.location.reload()
                }else{
                	alert("修改失败!");
                }
		   },
		   error: function(){
		   	 alert("ajax请求失败")
		   }
		});
	})
});
