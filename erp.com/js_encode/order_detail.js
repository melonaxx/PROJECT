
$(function(){
	areaSelect.createSelect('.supplierMsg .shengDiv','.supplierMsg .shiDiv','.supplierMsg .xianDiv','<label class="margin_left_1" style="margin-left:25px;">省份：</label>','<label>市（区）：</label>','<label>区（县）：</label>');
	$('.supplierMsg .sheng').attr('disabled','disabled');
	$('.supplierMsg .shi').attr('disabled','disabled');
	$('.supplierMsg .xian').attr('disabled','disabled');

	$('.supplierMsg .sheng option').each(function (index, value) {
		if ($(value).val() == sheng) {
			$(value).attr('selected', 'selected');
			$('.supplierMsg .sheng').change();
		}
	});
	$('.supplierMsg .shi option').each(function (index, value) {
		if ($(value).val() == shi) {
			$(value).attr('selected', 'selected');
			$('.supplierMsg .shi').change();
		}
	});
	$('.supplierMsg .xian option').each(function (index, value) {
		if ($(value).val() == xian) {
			$(value).attr('selected', 'selected');
			$('.supplierMsg .xian').change();
		}
	});

	$('.supplierMsg .express option').each(function(){
		if($(this).val() == express){
			$(this).attr('selected','selected');
		}
	});

    $('.supplierMsg .bank option').each(function(){
        if($(this).val() == bank_id){
            $(this).attr('selected','selected');
        }
    });

	$('.supplierMsg .store option').each(function(){
		if($(this).val() == store){
			$(this).attr('selected','selected');
		}
	});

    $('.supplierMsg .shop option').each(function(){
        if($(this).val() == store){
            $(this).attr('selected','selected');
        }
    });


	$('.supplierMsg .shouju option').each(function(){
		if($(this).val() == invoice){
			$(this).attr('selected','selected');
		}
		if(invoice == "Y"){
			$('#fapiao').show();
		}
	});
	$(".none option").each(function(){
		var aa = $(".none option:checked").text();
		if(aa == '增值税'){
			$("#block").css("display","block");
		}else{
			$("#block").css("display","none");
		}
	});



	$('.form-inline').validate({
		highlight:function (element,errorClass) {
			$(element).addClass('error_color');
			$(element).tooltip('show');
		},

		unhighlight:function (element,errorClass) {
			$(element).removeClass('error_color');
			$(element).tooltip('hide');
		},

		rules:{
			name:{
				required:true,
			},

		},
		messages:{
			name:{
				required:'',
			},

		},
	});




	//图片显示
    $('.images').hover(function(){
		var t = $(this).position().top - 90 + 'px';
		var l = $(this).position().left + 20 + 'px';
		$(this).next().css("display","block");
		$(this).next().css("top",t);
		$(this).next().css("left",l);
	},function(){
		$(this).next().css("display","none");
	})



    if(status){
    	$('.customizeMsg').css('display','block');
        $('.customizeMsg input[name=need_dz]').val('Y');
        $('.need').html('取消定制');
    }else{
    	$('.customizeMsg').css('display','none');
        $('.customizeMsg input[name=need_dz]').val('N');
        $(this).html('需要定制');
    }

   //财务
    $('.financeMsg .real').keyup(function(){
    	//判断实收金额是否为空
    	if($(this).val()){
			var v = parseFloat($(this).val().replace(/[^0-9\.]/ig,''));
    	}else{
    		var v = 0.00;
    	}

        v = parseFloat(v);
        //应收金额
        var total = $('.financeMsg .theory').val().replace(/[^0-9\.]/ig,'');
        total = parseFloat(total);
        //欠款
        var remain = total-v;
        remain = parseFloat(remain.toFixed(2));

        $('.financeMsg .remain').val(remain);
        if(remain <= 0){
            remain = 0;
            $('.financeMsg .pa').val("已付款");
            $('.financeMsg .payment_status').val("Y");
            $(this).val(total);
            $('.financeMsg .remain').val(remain);

        }else if(remain == total){
            $('.financeMsg .pa').val("未付款");
            $('.financeMsg .payment_status').val("N");

        }else{
            $('.financeMsg .pa').val("部分付款");
            $('.financeMsg .payment_status').val("P");

        }
    });
    //---- 实收金额验证 ----
    var real_amount = $('.commonMsg input[name=real_amount]').val();

    //---- 表单的提交 ----
    $('.form-inline').submit(function () {
    	var after_real = $('.commonMsg input[name=real_amount]').val();
    	//存放增加前的金额
    	$('.commonMsg input[name=beforAmount]').val(real_amount);
    	//存放增加后的金额
    	$('.commonMsg input[name=afterAmount]').val(after_real);
    	if (parseFloat(after_real) < parseFloat(real_amount)) {
    		$('#confirm .number').text(real_amount);
			$('#confirm').modal('show');
			$('#print-menu').hide();
    		return false;
    	}
    	return true;
    });

});