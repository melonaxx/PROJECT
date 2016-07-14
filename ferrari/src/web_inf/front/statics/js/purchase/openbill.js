$(function(){
	// aja();
	// function aja(){
	// 	$(".kemu").empty();
	// 	$.ajax({
	// 		type: "POST",
	// 		url: "/purchase/findallkemu.php",
	// 		dataType:"json",
	// 		success: function(msg){
	// 			$.each(msg,function(idx,item){
	// 				kid = item.id;
	// 				kname = item.name;
	// 				$(".kemu").append("<option value='"+kid+"'>"+kname+"</option>")
	// 			});
	// 		}
	// 	})
	// }
	aja();
 function aja(){

  $(".kemu").focus(function(){
   ob = $(this);
   $.ajax({
    type: "POST",
    url: "/purchase/findallkemu.php",
    dataType:"json",
    success: function(msg){
     var str = "";
     $.each(msg,function(idx,item){
      kid = item.id;
      kname = item.name;
      str += "<option value='"+kid+"'>"+kname+"</option>";
     });
     ob.empty().append(str);
    }
   })
  })
 }
	$(".add-sub1").click(function(){
		$(this).parent().append('<div class="form-group"> <label for="" class="labelname">财务科目(借)：</label> <select class="form-control kemu" name="jkemuid[]"></select> <label for="" class="labelname" style="margin-left: 30px;">金额：</label> <div class="input-group"><div class="input-group-addon">￥</div><input type="text" class="form-control" style="width:110px;" name="jkemujine[]"></div> <a href="javascript:;" class="del-sub" style="margin-left: 30px;color:blue;">删除</a></div>');
		$(".del-sub").click(function(){
			$(this).parent().remove();
		});
		aja();
	});
	$(".add-sub2").click(function(){
		$(this).parent().append('<div class="form-group"> <label for="" class="labelname">财务科目(贷)：</label> <select class="form-control kemu" name="dkemuid[]"></select> <label for="" class="labelname" style="margin-left: 30px;">金额：</label> <div class="input-group"><div class="input-group-addon">￥</div><input type="text" class="form-control" style="width:110px;" name="dkemujine[]"></div> <a href="javascript:;" class="del-sub" style="margin-left: 30px;color:blue;">删除</a></div>');
		$(".del-sub").click(function(){
			$(this).parent().remove();
		});
		aja();
	});
	$(".del-sub").click(function(){
		$(this).parent().remove();
	});
	$("#shuilv").change(function(){
		var hanshui = $("#hanshui").val();
		var shuilv = $("#shuilv").val();
		var noshui = (hanshui/(1+shuilv/100)).toFixed(2);
		var shuie = (hanshui-noshui).toFixed(2);
		$("#noshui").val(noshui);
		$("#shuie").val(shuie);
	})
	flag = false;
	$("#number").change(function(){
		var number = $(this).val();
		$.ajax({
			type: "POST",
			url: "/purchase/checkwaynumber.php",
			data:{"number":number},
			success: function(msg){
				if(msg=="ok"){
					flag = true;
					$("#number").removeClass("sousou");
				}else{
					flag = false;
					$("#number").addClass("sousou");
				}
			}
		})
	})
})
function fun(){
	return flag;
}




