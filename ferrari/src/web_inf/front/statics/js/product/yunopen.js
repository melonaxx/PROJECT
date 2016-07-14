$(function(){
	$(".add-sub1").click(function(){
		$(this).parent().append('<div class="form-group"><label for="" class="labelname">财务科目(借)：</label> <select class="form-control"><option value=""></option></select> <label for="" class="labelname" style="margin-left: 30px;">金额：</label> <div class="input-group"><div class="input-group-addon">￥</div><input type="text" class="form-control" style="width:110px;"></div> <a href="javascript:;" class="del-sub" style="margin-left: 30px;color:blue;">删除</a></div>');
		$(".del-sub").click(function(){
			$(this).parent().remove();
		});
	});
	$(".add-sub2").click(function(){
		$(this).parent().append('<div class="form-group"><label for="" class="labelname">财务科目(贷)：</label> <select class="form-control"><option value=""></option></select> <label for="" class="labelname" style="margin-left: 30px;">金额：</label> <div class="input-group"><div class="input-group-addon">￥</div><input type="text" class="form-control" style="width:110px;"></div> <a href="javascript:;" class="del-sub" style="margin-left: 30px;color:blue;">删除</a></div>');
		
		$(".del-sub").click(function(){
			$(this).parent().remove();
		});
	});
	$(".del-sub").click(function(){
		$(this).parent().remove();
	});
})