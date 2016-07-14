$(function(){
	$(".addcount-btn").click(function(){
		$(".modal-accountmanage").show();
	});
	//修改;
	$(".account-change").click(function(){
		$(".modal-accountmanage1").show();
		$(".zhang-name1").val($(this).parent().siblings(".account-name").html());
		$(".zhang-num1").val($(this).parent().siblings(".account-num").html());
		$(".zhang-mark1").val($(this).parent().siblings(".account-remark").html());
		$(".zhang-id").val($(this).attr("uid"));
		if($(this).next().html()=="默认"){
			$(".default-account").show();
			$(".default-account").next().hide();
		}
		if($(this).next().html()=="删除"){
			$(".default-account").hide();
			$(".default-account").next().show();
		}
		var $con=$(this).next(".account-del");
		var $default=$(this).parent().next(".account-name").html();
		var $aname=$(this).parent().next(".account-name");
		var $anum=$(this).parent().next(".account-num");
		var $aremark=$(this).parent().next(".account-remark");
		
	});
	//删除
	$(".account-del").click(function(){
		var id=$(this).attr("uid");
		$(".modal-accountmanage2").show();
		$(".account-sure2").attr("id" , id);
		$this = $(this);
	});
	$(".account-sure2").click(function(){
		$(".modal-accountmanage2").hide();

		var id = $(".account-sure2").attr("id");
		$.ajax({
		   type: "POST",
		   url: "/money/delfinancebank.php",
		   data:{id:id},
		   success: function(msg){
                if(msg==1){
                	alert("删除成功!");
                	$this.closest("tr").remove();
                }else{
                	alert("删除失败!");
                }
                // alert(msg);
		   },
		   error: function(){
		   	 alert("ajax请求失败")
		   }
		});
	})
})