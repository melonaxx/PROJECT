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
	if($(".ortbody tr").length==0){
		$(".empty-img").show();
	}else{
		$(".empty-img").hide();
	}
	/*---------------------点击确认审核---------------------*/
	$(".sure-check").on("click",function(){
		$(".check[type='checkbox']").each(function(){
			if(this.checked==true){
				flag=true;
				$(".modal-check").show();
			}
		});
		if(flag){
			$(".modal-check").show();
		}else{
			$(".modal-tip").show();
		}
	});
	/*---------------------点击提交异常---------------------*/
	$(".submit-statu").on("click",function(){
		$(".check[type='checkbox']").each(function(){
			if(this.checked==true){
				flag=true;
				$(".modal-abnormal").show();
			}
		});
		if(flag){
			$(".modal-abnormal").show();
		}else{
			$(".modal-tip").show();
		}
	});


	/*---------------------点击订单拆合手工合单---------------------*/
	$(".chai").on("click",function(){
		var number1;
		var number=0;
		$(".check").each(function(){
			if($(this).is(":checked")){
				number+=1;
				number1=$(this).parent().siblings(".review-num").html();
			}
		});
		if(number==0){
			$(".modal-tip").show();
			$(".tip-content").html("请至少选择一个订单");
		}else if(number==1){
			if(number1<=2){
				$(".modal-tip").show();
				$(".tip-content").html("订单的商品数量必须大于2");
			}
			if(number1>2){
				$(".modalsplits3").show();
			}
		}else if(number>1){
			$(".modal-tip").show();
			$(".tip-content").html("所选订单数量必须为1");
		}
	});
	$(".he").on("click",function(){
		var number=0;
		$(".input-check").each(function(){
			if($(this).is(":checked")){
				number+=1;
			}
		});
		if(number<2){
			$(".modal-tip").show();
			$(".tip-content").html("请至少选择2个订单");
		}else if(number>=2){
			$(".modalmerge").show();
		}
	});


	/*---------------------订单拆合下拉菜单的显示隐藏------------------*/
	/*$(".ordownbtn1").on("blur",function(){
		$(".ordownul1").hide();
		flag3=true;
	})*/
	$(".ordown1").on("click",function(){
		if($(".ordownul1").is(':hidden')){
			$(".ordownul1").show();
			$(".ordownul2").hide();
		}else{
			$(".ordownul1").hide();
		}
	})
	/*---------------------点击批量修改备注--------------------*/
	$(".beizhu").on("click",function(){
		$(".check[type='checkbox']").each(function(){
			if(this.checked==true){
				flag=true;
				$(".change-mark").show();
			}
		});
		if(flag){
			$(".change-mark").show();
		}else{
			$(".modal-tip").show();
		}
	});
	/*批量修改快递*/
	$(".express").on("click",function(){
		$(".check[type='checkbox']").each(function(){
			if(this.checked==true){
				flag=true;
				$(".change-express").show();
			}
		});
		if(flag){
			$(".change-express").show();
		}else{
			$(".modal-tip").show();
		}
	});
	/*批量修改仓库*/
	$(".beizhu").on("click",function(){
		$(".check[type='checkbox']").each(function(){
			if(this.checked==true){
				flag=true;
				$(".change-mark").show();
			}
		});
		if(flag){
			$(".change-mark").show();
		}else{
			$(".modal-tip").show();
		}
	});
	/*下载订单*/
	$(".down-order").on("click",function(){
		// $("input[type='checkbox']").each(function(){
		// 	if(this.checked==true){
		// 		flag=true;
				$(".choice-shop").show();
		// 	}
		// });
		// if(flag){
		// 	$(".choice-shop").show();
		// }else{
		// 	$(".modal-tip").show();
		// }
	});
})