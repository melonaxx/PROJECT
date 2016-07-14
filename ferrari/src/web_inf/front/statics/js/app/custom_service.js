$(function(){
	/*切换*/
	$(".row-left li").click(function(){
		$(this).addClass("li-color").siblings().removeClass("li-color");
	});
	/*答案出现*/
	$(".service-question li").click(function(){
		$(this).addClass("question-color").siblings("li").removeClass("question-color");
		$(".question-color").children(".question-answer").css("color","black").show();
		$(".question-color").children(".question-list").children(".question-edit").css("color","black");
		$(".question-color").siblings("li").children(".question-answer").hide();
		/*复制答案*/
		$(".copy-answer").each(function(i){
	        var clip=null;
	        ZeroClipboard.setMoviePath("/js/app/ZeroClipboard.swf" );
	        function $(id) { return document.getElementById(id+i);}
	        clip = new ZeroClipboard.Client();
	        clip.setHandCursor(true);
	        var $content=document.getElementById("copy_txt"+i).innerHTML;
	        clip.addEventListener('mouseOver', function (client) {
				// update the text on mouse over
				clip.setText($content);
			});
			clip.addEventListener('complete', function (client, text) {
				debugstr("Copied text to clipboard: " + text );
			});
				
			clip.glue('copy_btn'+i);
			
		});
	});
	/*编辑问题和答案*/
	$(".question-edit").click(function(event){
		event.stopPropagation();
		var pid=$(this).attr('uid');
		var question=$(this).prev().html();
		var answer=$(this).parent().siblings(".question-answer").find("#copy_txt0").html();
		var id=$(this).attr("selfid");
		$("#platname").val(pid);
		$("#question").val(question);
		$("#answer").val(answer);
		$("#selfid").val(id);
		$(".modal-question").show();
	});
	
	$(".question-del").click(function(event){
		event.stopPropagation();
		var id = $(this).attr("selfid");
		$(".modal-delquestion").show();
		$(".del").attr("id",id);
		trtr = $(this).closest("li");
	});
	$(".del").click(function(){
		$(".modal-delquestion").hide();
		var id = $(this).attr("id");
		$.ajax({
		   type: "POST",
		   url: "/app/delquestion.php",
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
