$(function(){
	 //全选商品;
	 var flag=false;
	$(".allcheck").bind("click",function(){
		if(this.checked){
			$("input[type='checkbox']").each(function(){
				this.checked=true;
				flag=true;
			});
		}else{
			$("input[type='checkbox']").each(function(){
				this.checked=false;
				flag=false;
			});
		}
	});
	//审核通过
	$(".check-pass").click(function(){
		$("input[type='checkbox']").each(function(){
			if(this.checked==true){
				$(".modal-purchasetip1").show();
				flag=true;
			}
		});
		if(flag){
			$(".modal-purchasetip1").show();
		}else{
			$(".modal-purchasetip").show();
		}
	});
	//打回修改
	$(".check-return").click(function(){
		$("input[type='checkbox']").each(function(){
			if(this.checked==true){
				$(".modal-purchasetip2").show();
				flag=true;
			}
		});
		if(flag){
			$(".modal-purchasetip2").show();
		}else{
			$(".modal-purchasetip").show();
		}
	});
	//拒绝
	$(".check-refuse").click(function(){
		$("input[type='checkbox']").each(function(){
			if(this.checked==true){
				$(".modal-purchasetip3").show();
				flag=true;
			}
		});
		if(flag){
			$(".modal-purchasetip3").show();
		}else{
			$(".modal-purchasetip").show();
		}
	});

    $("#sure_repel").click(function(){
	     var input = $("input:checkbox[name='check_sure']:checked");
	     var array = new Array();
	     for(var i = 0;i<input.length;i++){
	     	 array[i]=input[i].value;
	     }
	     $.ajax({
		   type: "POST",
		   url: "/product/check_change.php",
		   data: {
		   		  "array" : array,
		   		  "statusaudit" : 'R'
		   		  },
		   success: function(msg){
		       if(msg == "yes"){
		       	   alert("操作成功");
		       	   window.location.href="/product/product_checklist.php";
		       }
		   }
		});
    })
//拒绝生产单
    $("#sure_repel1").click(function(){
	     var input = $("input:checkbox[name='check_sure']:checked");
	     var array = new Array();
	     for(var i = 0;i<input.length;i++){
	     	 array[i]=input[i].value;
	     }
	     $.ajax({
		   type: "POST",
		   url: "/product/check_change.php",
		   data: {
		   		  "array" : array,
		   		  "statusaudit" : 'F'
		   		  },
		   success: function(msg){
		       if(msg == "yes"){
		       	   alert("操作成功");
		       	   window.location.href="/product/product_checklist.php";
		       }
		   }
		});
    })

//生产单通过
    $("#sure").click(function(){
	     var input = $("input:checkbox[name='check_sure']:checked");
	     var array = new Array();
	     for(var i = 0;i<input.length;i++){
	     	 array[i]=input[i].value;
	     }
	     $.ajax({
		   type: "POST",
		   url: "/product/check_change.php",
		   data: {
		   		  "array" : array,
		   		  "statusaudit" : 'Y'
		   		  },
		   success: function(msg){
		       if(msg == "yes"){
		       	   alert("操作成功");
		       	   window.location.href="/product/product_checklist.php";
		       }
		   }
		});
    })
})

