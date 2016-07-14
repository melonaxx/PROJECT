$(function(){
	//初始化
	if($(".inoroutset-tr").length==0){
		$(".empty-tr").show();
	}else{
		$(".empty-tr").hide();
	}
	$(".inoroutset-add").click(function(){
		$(".modal-inoroutset").show();
	});
	//删除类型
	DelType();
	function DelType(){
		$(".del").click(function(){
			var id=$(this).attr("uid");
			$(".modal-inoroutset1").show();
			$this=$(this);
			$(".inoroutset-sure1").attr("id" , id);
		});
	}
	$(".inoroutset-sure1").click(function(){
		var id = $(".inoroutset-sure1").attr("id");
		$.ajax({
		   type: "POST",
		   url: "/purchase/delcompany.php",
		   data:{id:id},
		   success: function(msg){
                if(msg==1){
                	alert("删除成功!");
                	$this.closest("tr").remove();
                }else{
                	alert("删除失败!");
                }
		   },
		   error: function(){
		   	 alert("ajax请求失败")
		   }
		});
		$(".modal-inoroutset1").hide();
		$(".inoroutset-td").each(function(i){
	 			$(this).html(i+1);
	 		});
		if($(".inoroutset-tr").length==0){
			$(".empty-tr").show();
		}else{
			$(".empty-tr").hide();
		}
	});

	//修改类型
	ChangeType();
	function ChangeType(){
		$(".inoroutset-change").click(function(){
			$(this).parent().addClass("click");
			$(".modal-inoroutset2").show();
			$(".typename1").val($(this).parent().siblings(".inoroutset-name").html());
			$(".typetext1").val($(this).parent().siblings(".inoroutset-text").html());
			$(".typehidden").val($(this).attr("uid"));
		});
		$(".close-btn").click(function(){
			$(".click").removeClass("click");
		})
	}
})