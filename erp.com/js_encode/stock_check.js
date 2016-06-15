$(function(){
	//修改文本框中的内容
	var cc = '';
	$(document).on('click', '.dianji', function() {
		var td = $(this);
		var txt = td.text();
		var input = $("<input style='width:133px;' class='form-control input-sm aa' name='bb' type='text'value='" + txt + "'/>");
		td.html(input);
		input.click(function() { return false; });
		//获取焦点
		input.trigger("focus");
		//文本框失去焦点后提交内容，重新变为文本
		input.blur(function() {
			var newtxt = $(this).val();
			//判断文本有没有修改
			if (newtxt != txt) {
				td.html(newtxt);
			}else{
				td.html(newtxt);
			}
		});
	});

	//数量不能为非数字
	$(document).on('keyup', '.aa', function() {
		this.value=this.value.replace(/\D/g,'');
	});	

	//生成盘点单
	var a = $("select[name='ware'] option:selected").val();
	$(".danji").click(function(){
		var addon = new Array();//定义盘点数数组
		var condition = new Array();//定义商品id数组
		$('.tab-sel').find('.dianji').each(function(){
			var num = $(this).text().replace(/[^0-9]/ig,"");
			if(num.length>0){
				addon.push(num);
				condition.push($(this).next().val());
			}
		});
		MessageBox('/stock/stock_add_inventory_list.php?value='+a+'&total='+addon+'&condition='+condition, '生成盘点单', 1000, 237); return false;
	});
	//鼠标放在图片上显示大图
	$('.smallimg').hover(function(){
		var t = $(this).position().top - 90 + 'px';
		var l = $(this).position().left + 20 + 'px';
		$(this).next().css("display","block");
		$(this).next().css("top",t);
		$(this).next().css("left",l);
	},function(){
		$(this).next().css("display","none");
	})
});