$(function(){
	//全选;
	var flag=false;
	$(".allcheck").on("click",function(){
		if(this.checked){
			$(".check[type='checkbox']").each(function(){
				this.checked=true;
				flag=true;
			});
		}else{
			$(".check[type='checkbox']").each(function(){
				this.checked=false;
				flag=false;
			});
		}
	});
	/*---------------------点击确认审核---------------------*/
	// $(".sure-check").on("click",function(){
	// 	$(".check[type='checkbox']").each(function(){
	// 		if(this.checked==true){
	// 			flag=true;
	// 			$(".modal-check").show();
	// 		}
	// 	});
	// 	if(flag){
	// 		$(".modal-check").show();
	// 	}else{
	// 		$(".modal-tip").show();
	// 	}
	// });
	$(".sure-check").click(function(){
		flag = false;
		num = 0;
		edit = {};
		$(".check[type='checkbox']").each(function(){
			if(this.checked==true){
				$(".modal-check").show();
				edit[num] = $(this).val();
				flag=true;
			}
			num++;
		});
		if(flag){
			$(".modal-check").show();
		}else{
			$(".modal-tip").show();
		}
	});
	$("#edit").click(function(){
		$(".modal-check").hide();
		$.ajax({
			type: "POST",
			url: "/order/docheckmain.php",
			data:{"edit":edit},
			success: function(msg){
				if (msg==1) {
					alert("操作成功!");
					window.location.reload();
				}else {
					alert("操作失败!");
				}
			}
		});
	})
	/*---------------------关闭---------------------*/
	// $(".order-close").click(function(){
	// 	$(".check[type='checkbox']").each(function(){
	// 		if(this.checked==true){
	// 			flag=true;
	// 			$(".modal-close").show();
	// 		}
	// 	});
	// 	if(flag){
	// 		$(".modal-close").show();
	// 	}else{
	// 		$(".modal-tip").show();
	// 	}
	// })
	$(".order-close").click(function(){
		flag = false;
		num = 0;
		del = {};
		$(".check[type='checkbox']").each(function(){
			if(this.checked==true){
				$(".modal-close").show();
				del[num] = $(this).val();
				flag=true;
			}
			num++;
		});
		if(flag){
			$(".modal-close").show();
		}else{
			$(".modal-tip").show();
		}
	});
	$("#del").click(function(){
		$(".modal-check").hide();
		$.ajax({
			type: "POST",
			url: "/order/dodelmain.php",
			data:{"del":del},
			success: function(msg){
				if (msg==1) {
					alert("操作成功!");
					window.location.reload();
				}else {
					alert("操作失败!");
				}
			}
		});
	})
	/*搜索分页---------------------------------*/
	$(".rrow").change(function(){
	var p=$(this).val();
	var pps=$("#pps").html();
	var seach = String($("#seach").val());
	if(seach){
		window.location.href = "/order/checkmain.php?num="+p+"&page="+pps+"&seach="+seach; 	
	}else{
		window.location.href = "/order/checkmain.php?num="+p+"&page="+pps;
	}
	
	})
	flags = false;

	$("#seach").change(function(){
		var seach = $("#seach").val();
		if(seach){
			flags = true;
			$("#seach").removeClass("sousou");
		}else{
			flags =false;
		}
	})
	
	$("#sou").click(function(event){
		event.stopPropagation();
		var seach = $("#seach").val();
		if(!seach){
		$("#seach").addClass("sousou");
	}
	})
	$("body").click(function(){
			$("#seach").removeClass("sousou");
	})
})
function fun(){
		return flags;
	}