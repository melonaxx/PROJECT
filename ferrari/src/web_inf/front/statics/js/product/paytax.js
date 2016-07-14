	$(".add-sub1").click(function(){
		$(this).parent().append('<div class="form-group"><label for="" class="labelname">财务科目(借)：</label> <select class="form-control kemu" name="subject_lend[]"><option value=""></option></select> <label for="" class="labelname" style="margin-left: 30px;">金额：</label> <div class="input-group"><div class="input-group-addon">￥</div><input type="text" class="form-control" style="width:110px;" name="jie_price[]"></div> <a href="javascript:;" class="del-sub" style="margin-left: 30px;color:blue;">删除</a></div>');
		$(".del-sub").click(function(){
			$(this).parent().remove();
		});
		subject();
	});
	$(".add-sub2").click(function(){
		$(this).parent().append('<div class="form-group"><label for="" class="labelname">财务科目(贷)：</label> <select class="form-control kemu" name="subject_loan[]"><option value=""></option></select> <label for="" class="labelname" style="margin-left: 30px;">金额：</label> <div class="input-group"><div class="input-group-addon">￥</div><input type="text" class="form-control" style="width:110px;" name="dai_price[]"></div> <a href="javascript:;" class="del-sub" style="margin-left: 30px;color:blue;">删除</a></div>');
		
		$(".del-sub").click(function(){
			$(this).parent().remove();
		});
		subject();
	});
	$(".del-sub").click(function(){
		$(this).parent().remove();
	});


	  $("input[name='req_tax']").keyup(function(){
	 	 	var val = $(this).val() ? $(this).val():0;
	 	 if($("input[name='tax_rate']").val() != ""){

	 	 	 var tax_brow = parseInt(val) / (1 + parseInt($("input[name='tax_rate']").val()) / 100 );

	 	 $("input[name='noreq_tax']").val(tax_brow.toFixed(2));

	 	 // 税额：
	 	 var tax_br = parseInt(val) - tax_brow.toFixed(2);

	 	 $("input[name='tax_brow']").val(tax_br.toFixed(2));

	 	}
	 })

 	 $("input[name='tax_rate']").keyup(function(){
 	 		// 税率(%)
	 	 	var val = $(this).val() ? $(this).val():0;
	 	 	// 金额(含税)
	 	 	var req_tax = parseInt($("input[name='req_tax']").val()?$("input[name='req_tax']").val():0);

	 	 if(req_tax != ""){

	 	 	 var tax_brow = req_tax / (1 + parseInt(val) / 100);

	 	 	 var tax_br = req_tax - tax_brow;

 			$("input[name='noreq_tax']").val(tax_brow.toFixed(2));

 			$("input[name='tax_brow']").val(tax_br.toFixed(2));
	 	 }
	 	
	 })


	 
 	subject();
	function subject(){

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