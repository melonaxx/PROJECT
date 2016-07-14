$(function(){

	 //全选商品;
	 var flag=false;
	$(".allcheck").bind("click",function(){
		if(this.checked){
			$(".onlyche").each(function(){
				this.checked=true;
			});
		}else{
			$(".onlyche").each(function(){
				this.checked=false;
			});
		}
	});
	//审核通过
	$(".check-pass").click(function(){
		flag = false;
		num = 0;
		pass = {};
		$(".onlyche").each(function(){
			if(this.checked==true){
				$(".modal-purchasetip1").show();
				pass[num] = $(this).val();
				flag=true;
			}
			num++;
		});
		if(flag){
			$(".modal-purchasetip1").show();
		}else{
			$(".modal-purchasetip").show();
		}
	});
	$(".subsub").click(function(){
		$(".modal-purchasetip1").hide();
		$.ajax({
			type: "POST",
			url: "/purchase/passpurchase.php",
			data:{pass:pass},
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
	//打回修改
	$(".check-return").click(function(){
		flag = false;
		num = 0;
		edit = {};
		$(".onlyche").each(function(){
			if(this.checked==true){
				$(".modal-purchasetip2").show();
				edit[num] = $(this).val();
				flag=true;
			}
			num++;
		});
		if(flag){
			$(".modal-purchasetip2").show();
		}else{
			$(".modal-purchasetip").show();
		}
	});
	$(".subedit").click(function(){
		$(".modal-purchasetip2").hide();
		$.ajax({
			type: "POST",
			url: "/purchase/editpurchase.php",
			data:{edit:edit},
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
	//拒绝
	$(".check-refuse").click(function(){
		flag = false;
		num = 0;
		del = {};
		$(".onlyche").each(function(){
			if(this.checked==true){
				$(".modal-purchasetip3").show();
				del[num] = $(this).val();
				flag=true;
			}
			num++;
		});
		if(flag){
			$(".modal-purchasetip3").show();
		}else{
			$(".modal-purchasetip").show();
		}
	});
	$(".subdel").click(function(){
		$(".modal-purchasetip3").hide();
		$.ajax({
			type: "POST",
			url: "/purchase/delpurchase.php",
			data:{del:del},
			success: function(msg){
				if (msg==1) {
					alert("操作成功!");
					window.location.reload();
				}else {
					alert("操作失败!");
				}
			}
		});
	});
})
$(".rrow").change(function(){
	var p=$(this).val();
	var pps=$("#pps").html();
	var name = String($(".name").val());
	var data = String($(".data").val());
	if(name&&data){
		window.location.href = "/purchase/checkpurchase.php?num="+p+"&page="+pps+"&name="+name+"&data="+data; 	
	}else{
		window.location.href = "/purchase/checkpurchase.php?num="+p+"&page="+pps;
	}
	
})

var flag1 = false;
var flag2 = false;
function fun(){
	return flag1&&flag2;
}
$("#sou").click(function(event){
	event.stopPropagation();
	var $name= $(".name").val();
	var $data = $(".data").val();
	if($name){
		flag1 = true;
	}else{
		flag1 =false;
	}
	if($data){
		flag2 = true;
	}else{
		flag2 =false;
	}
	if(!flag2){
		$(".data").addClass("sousou");
	}else{
		$(".data").removeClass("sousou");
	}
	if(!flag1){
		$(".name").addClass("sousou");
	}else{
		$(".name").removeClass("sousou");
	}

});
$("body").click(function(){
		$(".name").removeClass("sousou");
		$(".data").removeClass("sousou");
});
/*数据为空时显示*/
if($(".checkpur-tbody tr").length==0){
	$(".none").show();
}else{
	$(".none").hide();
}
