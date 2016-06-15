$(function(){
	areaSelect.createSelect('.supplierMsg .shengDiv','.supplierMsg .shiDiv','.supplierMsg .xianDiv','<label class="margin_left_1" style="margin-left:25px;">省份：</label>','<label>市（区）：</label>','<label>区（县）：</label>');
	
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

    //全选
    $('.goodsMsg input[name="select_all"]').click(function () {
        
        if(this.checked){   
            $('.goodsMsg input[name="select_one"]').each(function () {
                $(this).prop("checked",true);
            });
        }else{   
            $('.goodsMsg input[name="select_one"]').each(function () {
                $(this).prop("checked",false);
            });
        }
    });
    
    $('.form-inline').submit(function(){
        var m = $('input[name=mobile]').val();
        var p = $('input[name=phone]').val();
        if(m == "" && p == ""){
            $('input[name=mobile]').addClass('error_color');
            $('input[name=mobile]').tooltip('show');
            return false;
        }else{
            $('input[name=mobile]').removeClass('error_color');
            $('input[name=mobile]').tooltip('hide');
            return true; 
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
            'good_name[]':{
                required:true,
            },
            'number[]':{
                required: true,
                min:1,
            },
        },
        messages:{
            name:{
                required:'',
            },
            'good_name[]':{
                required:'',
            },
            'number[]':{
                required:'',
                min:jQuery.validator.format(""),
            }
        },
    });
    

    //日期
    $('.supplierMsg input[name="order_date"]').datetimepicker({
        language:'zh-CN',
        format:'yyyy-mm-dd hh:ii:ss',
        autoclose:true,
        minView:0,
    });



    

    //财务入账
    $('.financeMsg .real').keyup(function(){
        var v = $(this).val();
        var total = $('.financeMsg .theory').val();
        var remain = total-v;
        $('.financeMsg .remain').val(remain);
        if(remain <= 0){
            remain = 0;
            $('.financeMsg .payment_status').val("已付款");
            $(this).val(total);
            $('.financeMsg .remain').val(remain);

        }else if(remain == total){
            $('.financeMsg .payment_status').val("未付款");
        }else{
            $('.financeMsg .payment_status').val("部分付款");
        }
    });

    $('.supplierMsg .shouju').change(function(){
        var v = $(this).val();
        if(v == "Y"){
            $('#fapiao').show();
        }else if(v == "N"){
            $('#fapiao').hide();
        }
    });



    function in_array(search,array){
        for(var i in array){
            if(array[i]==search){
                return true;
            }
        }
        return false;
    }

	$('.supplierMsg .shouju option').each(function(){
		if($(this).val() == invoice){
			$(this).attr('selected','selected');
		}
		if(invoice == "Y"){
			$('#fapiao').show();
		}
        if(tax_type="VAT"){
            $("#block").show();
        }
	});
	// $(".none option").each(function(){
	// 	var aa = $(".none option:checked").text();
	// 	if(aa == '增值税'){
	// 		$("#block").css("display","block");
	// 	}else{
	// 		$("#block").css("display","none");
	// 	}
	// });
	$('.supplierMsg .shouju').change(function(){
		var v = $(this).val();
		if(v == "Y"){
			$('#fapiao').show();
		}else if(v == "N"){
			$('#fapiao').hide();
		}
	});


	//发票切换
	$(".none").change(function(){
		var aa = $(this).val();
		if(aa == 'Normal'){
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

	//财务
	$('.financeMsg .real').keyup(function(){
		var v = $(this).val();
		var total = $('.financeMsg .theory').val();
		var remain = total-v;
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

	//固定电话和手机号码验证
	$('.form-inline').submit(function(){
        var m = $('input[name=mobile]').val();
        var p = $('input[name=phone]').val();
        if(m == "" && p == ""){
            $('input[name=mobile]').addClass('error_color');
            $('input[name=mobile]').tooltip('show');
            return false;
        }else{
            $('input[name=mobile]').removeClass('error_color');
            $('input[name=mobile]').tooltip('hide');
            return true; 
        }

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

    //定制
    $('.need').click(function(){
        var v = $(this).html();
        if(v == "需要定制"){
            $('.customizeMsg').css('display','block');
            $('.customizeMsg input[name=need_dz]').val('Y');
            $('.need').html('取消定制');
        }else{
            $('.customizeMsg').css('display','none');
            $('.customizeMsg input[name=need_dz]').val('N');
            $(this).html('需要定制');
        }
    });

    //客户识别
    $('input[name=recog]').blur(function(){

        var v = $(this).val();
        $.ajax({
            url:'deliver_edit_order.php',
            data:{'recog':v},
            type:'post',
            dataType:'json',
            success:function(data){
                if(data){
                    $('input[name=name]').val(data[0]['name']);
                    $('input[name=mobile]').val(data[0]['mobile']);
                    $('input[name=phone]').val(data[0]['telphone']);
                    $('input[name=company_name]').val(data[0]['company_name']);
                    $('input[name=post_code]').val(data[0]['post_code']);
                    $('input[name=address]').val(data[0]['address']);
                    $('input[name=nick_name]').val(data[0]['nick_name']);
                    $('.supplierMsg .sheng option').each(function (index, value) {
                        if ($(value).val() == data[0]['state_id']) {
                            $(value).attr('selected', 'selected');
                            $('.supplierMsg .sheng').change();
                        }
                    });
                    $('.supplierMsg .shi option').each(function (index, value) {
                        if ($(value).val() == data[0]['city_id']) {
                            $(value).attr('selected', 'selected');
                            $('.supplierMsg .shi').change();
                        }
                    });
                    $('.supplierMsg .xian option').each(function (index, value) {
                        if ($(value).val() == data[0]['district_id']) {
                            $(value).attr('selected', 'selected');
                            $('.supplierMsg .xian').change();
                        }
                    });
                    var str = '';
                    for(var i=0;i<data.length;i++){
                        str += '<option value="'+data[i]['name']+'">'+data[i]['name']+'</option>'
                    }
                    $('.supplierMsg .crm').empty();
                    $('.supplierMsg .crm').append(str);
                    $('input[name=recog]').val("");

                }
            }
        });
    });
	//客户选择
	$('.supplierMsg').on('change','.crm',function(){
        var v = $(this).val();
        $.ajax({
            url:'deliver_edit_order.php',
            data:{'change':v},
            type:'post',
            dataType:'json',
            success: function(data){
                if(data){
                    $('input[name=name]').val(data['name']);
                    $('input[name=mobile]').val(data['mobile']);
                    $('input[name=phone]').val(data['telphone']);
                    $('input[name=company_name]').val(data['company_name']);
                    $('input[name=post_code]').val(data['post_code']);
                    $('input[name=address]').val(data['address']);
                    $('input[name=nick_name]').val(data['nick_name']);
                    $('.supplierMsg .sheng option').each(function (index, value) {
                        if ($(value).val() == data['state_id']) {
                            $(value).attr('selected', 'selected');
                            $('.supplierMsg .sheng').change();
                        }
                    });
                    $('.supplierMsg .shi option').each(function (index, value) {
                        if ($(value).val() == data[0]['city_id']) {
                            $(value).attr('selected', 'selected');
                            $('.supplierMsg .shi').change();
                        }
                    });
                    $('.supplierMsg .xian option').each(function (index, value) {
                        if ($(value).val() == data[0]['district_id']) {
                            $(value).attr('selected', 'selected');
                            $('.supplierMsg .xian').change();
                        }
                    });
                }
            }
        })
    })



});