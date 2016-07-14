$(function(){
	//初始化
	if($(".scanhandle-tr").length==0){
		$(".empty-img").show();
	}else{
		$(".empty-img").hide();
	}
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
	// //扫单发货
	// $(".scan-fahuo").click(function(){
	// 	$(".check[type='checkbox']").each(function(){
	// 		if(this.checked==true){
	// 			flag=true;
	// 			$(".modal-saodan").show();

	// 		}
	// 	});
	// 	if(flag){
	// 		$(".modal-saodan").show();
	// 	}else{
	// 		$(".modal-tip").show();
	// 	}
	// });
	// //提交异常;
	// $(".submit-statu").click(function(){
	// 	$(".check[type='checkbox']").each(function(){
	// 		if(this.checked==true){
	// 			flag=true;
	// 			$(".modal-subabnormal").show();
	// 		}
	// 	});
	// 	if(flag){
	// 		$(".modal-subabnormal").show();
	// 	}else{
	// 		$(".modal-tip").show();
	// 	}
	// });
	// //订单审核
	// $(".shen").click(function(){
	// 	$(".check[type='checkbox']").each(function(){
	// 		if(this.checked==true){
	// 			flag=true;
	// 			$(".modal-returncheck").show();
	// 		}
	// 	});
	// 	if(flag){
	// 		$(".modal-returncheck").show();
	// 	}else{
	// 		$(".modal-tip").show();
	// 	}
	// });
	// //打单配货
	// $(".pei").click(function(){
	// 	$(".check[type='checkbox']").each(function(){
	// 		if(this.checked==true){
	// 			flag=true;
	// 			$(".modal-peihuo").show();
	// 		}
	// 	});
	// 	if(flag){
	// 		$(".modal-peihuo").show();
	// 	}else{
	// 		$(".modal-tip").show();
	// 	}
	// });
	// //打单配货
	// $(".yan").click(function(){
	// 	$(".check[type='checkbox']").each(function(){
	// 		if(this.checked==true){
	// 			flag=true;
	// 			$(".modal-yanhuo").show();
	// 		}
	// 	});
	// 	if(flag){
	// 		$(".modal-yanhuo").show();
	// 	}else{
	// 		$(".modal-tip").show();
	// 	}
	// });


})