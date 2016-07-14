$("#sub").click(function(){
	var platid=$("#platid").val();
	var question=$("#question").val();
	var answer=$("#answer").val();
	$.ajax({
		   type: "POST",
		   url: "/app/doaddquestion.php",
		   data:{platid:platid,question:question,answer:answer},
		   success: function(msg){
                if(msg==1){
                	alert("添加成功!");
                	$("#question").val("");
                	$("#answer").val("");
                }else{
                	alert("添加失败!");
                }
		   },
		   error: function(){
		   	 alert("ajax请求失败")
		   }
		});
})