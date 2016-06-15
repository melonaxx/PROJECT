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

    $('.totalMsg .audit option').each(function(){
        if($(this).val() == is_audit){
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
    var trStr='<tr><td class="center"></td><td class="center"><input type="checkbox" name="select_one"></td><td class="center"><img src=""></td><td class="order_add_td_2"><input type="text" name="search" class="form-control input-sm form_no_border search"></td><td class="order_add_td_2" style="width:200px;"><select class="form-control input-sm form_no_border find" name="good_name[]" placeholder="必填" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="最少选择一个商品"></select></td><td></td><td class="order_add_td_2" style="width:80px;"><div class="input-group"><div class="input-group-addon danjia">￥</div><input type="hidden" name="price[]" class="price" value=""></div></td><td class="order_add_td_3"><input type="text" name="number[]" style="width:70px" data-toggle="tooltipx"  data-placement="bottom" data-original-title="商品数量至少为1" class="form-control input-sm form_no_border num"></td><td class="order_add_td_2" style="width:100px;"><div class="input-group"><div class="input-group-addon">￥</div><input type="text" name="discount[]" style="width:70px" class="form-control input-sm form_no_border yh"></div></td><td class="order_add_td_2" style="width:80px;"><div class="input-group"><div class="input-group-addon total_one">￥</div><input type="hidden" name="pay[]" class="pay" value="pay"></div></td><td class="order_add_td_2"><input type="text" name="content[]" class="form-control input-sm form_no_border number"></td></tr>';
    $('.goodsAdd').click(function () {
        var post_fee = $('.commonMsg input[name=freight_buyer]').val();
        if(post_fee == ""){
            post_fee = 0;
        }
        var v = $('.tiao').css('display');
        if(v == 'inline'){
            $('.tiao').css('display','none');

        }
        $('.goodsMsg table tr').last().before(trStr);

        //重置下标
        $('.goodsMsg table tr').each(function (index, value) {
            if (index > 0) {
                $(value).find('td').eq(0).html(index);
            }
        });
        $(".goodsMsg table tr").last().children().first().html("合计");
        var sum = 0;
        var sum_num = 0;
        //获取总优惠
        var sum_yh = 0;
        $('.goodsMsg table tr td:nth-child(9)').find('.yh').each(function(){
            var value = $(this).val();
            if(value == ""){
                value = 0;
            }
            sum_yh += parseFloat(value);
        });
        $('.goodsMsg .total .yh').html("￥"+sum_yh);
        $('.goodsMsg .total td:eq(4)').find('input[name=total_yh]').val(sum_yh);

       //获取总数量
        $('.goodsMsg table tr td:nth-child(8)').find('.num').each(function(){
            var value = $(this).val().replace(/[^0-9]/ig,"");
            if(value == ""){
                value = 0;
            }
            sum_num =parseFloat(sum_num) + parseFloat(value);
        });
        $('.goodsMsg .total .num').html(sum_num);
        $('.goodsMsg .total td:eq(3)').find('input[name=total_num]').val(sum_num);
        $('.goodsMsg table tr td:nth-child(10)').each(function(){
            var value = $(this).text().replace(/[^0-9\.]/ig,"");
            if(value == ""){
                value = 0;
            }
            sum = parseFloat(value) + parseFloat(sum);
        });
        $('.total .zong').html("￥"+sum);
        $('.goodsMsg .total td:eq(5)').find('input[name=total_pay]').val(sum);
        $('.totalMsg .total').html(sum+sum_yh);//总合计
        $('.totalMsg .yh').html(sum_yh);//总优惠
        $('.totalMsg .end').html(sum+parseFloat(post_fee));             //实际付得钱
        $('.financeMsg .theory').val(sum+parseFloat(post_fee));         //应付金额
        $('.financeMsg .remain').val(sum+parseFloat(post_fee));         //欠款金额

    });

    $('.goodsDelete').click(function () {
        var post_fee = $('.commonMsg input[name=freight_buyer]').val();
        if(post_fee == ""){
            post_fee = 0;
        }
        $('.goodsMsg input[name="select_one"]:checked').parent().parent().remove();
        $('.goodsMsg table tr').each(function (index, value) {
            if (index > 0) {
                $(value).find('td').eq(0).html(index);
            }
        });
        $(".goodsMsg table tr").last().children().first().html("合计");
        var sum = 0;
        var sum_num = 0;
        //获取总优惠
        var sum_yh = 0;
        $('.goodsMsg table tr td:nth-child(9)').find('.yh').each(function(){
            var value = $(this).val();
            if(value == ""){
                value = 0;
            }
            sum_yh += parseFloat(value);
        });
        $('.goodsMsg .total .yh').html("￥"+sum_yh);
        $('.goodsMsg .total td:eq(4)').find('input[name=total_yh]').val(sum_yh);

        //获取总数量
        $('.goodsMsg table tr td:nth-child(8)').find('.num').each(function(){
            var value = $(this).val().replace(/[^0-9]/ig,"");
            if(value == ""){
                value = 0;
            }
            sum_num =parseFloat(sum_num) + parseFloat(value);
        });

        $('.goodsMsg .total .num').html(sum_num);
        $('.goodsMsg .total td:eq(3)').find('input[name=total_num]').val(sum_num);
        $('.goodsMsg table tr td:nth-child(10)').each(function(){
            var value = $(this).text().replace(/[^0-9\.]/ig,"");
            if(value == ""){
                value = 0;
            }
            sum = parseFloat(value) + parseFloat(sum);
        });
        $('.total .zong').html("￥"+sum);
        $('.goodsMsg .total td:eq(5)').find('input[name=total_pay]').val(sum);

        $('.totalMsg .total').html(sum+sum_yh);//总合计
        $('.totalMsg .yh').html(sum_yh);//总优惠
        $('.totalMsg .end').html(sum+parseFloat(post_fee));               //实际付得钱
        $('.financeMsg .theory').val(sum+parseFloat(post_fee));           //应付金额
        $('.financeMsg .remain').val(sum+parseFloat(post_fee));           //欠款金额

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
                max:1000000,
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
                max:jQuery.validator.format(""),
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
        //商品名称搜索
        $(document).on('blur','.search',function(){
            var t = $(this);
            var v = t.val();
            val = new Array();
            var value = $(this).parents('tr').prev().find('th:eq(3)').html();
            var post_fee = $('.commonMsg input[name=freight_buyer]').val();
            if(post_fee == ""){
                post_fee = 0;
            }
            $('.goodsMsg table tr').find('select').each(function(){
                var a = $(this).val();
                if(a != null){
                    val.push(a);
                }
            })
            arr = val.join(",");
            $.ajax({
                url:"order_add.php",
                type:'post',
                data:{'aa':v,'b':arr},
                dataType:'json',
                success:function(data){
                    if(data){
                        t.parent().next().find('select:first').empty();
                        var str = "";
                        for(var i=0;i<data.length;i++){
                            str += "<option value="+data[i]['id']+">"+data[i]['name']+","+data[i]['value_id_1']+","+data[i]['value_id_2']+","+data[i]['value_id_3']+","+data[i]['value_id_4']+","+data[i]['value_id_5']+"</option>";
                        }
                        t.parents('tr').find('.images').attr("src",data[0]['image']);
                        t.parents('tr').find('.bigimg').attr("src",data[0]['image']);
                        t.parent().next().find('select:first').append(str);
                        t.parents('tr').find('td:nth-child(6)').html(data[0]['part_name']);
                        t.parents('tr').find('td:nth-child(7)').find('.danjia').html("￥"+data[0]['price_display']);
                        t.parents('tr').find('td:nth-child(7)').find('.price').val(data[0]['price_display']);

                        t.parents('tr').find('td:nth-child(7)').find('input[name=price]').val(data[0]['price_display']);
                         t.parents('tr').find('td:nth-child(8)').find('input[name="number[]"]').val('0');

                        var num = t.parents('tr').find('.num').val();
                        var yh = t.parents('tr').find('.yh').val();
                        var danjia = t.parents('tr').find('td:nth-child(7)').text().replace(/[^0-9\.]/ig,"");
                        if(num == ""){
                            num = 0;
                        }
                        if(yh == ""){
                            yh = 0;
                        }
                        var total = danjia*num-yh;
                        t.parents('tr').find('td:nth-child(10)').find('.total_one').html("￥"+total);
                        t.parents('tr').find('td:nth-child(10)').find('.pay').val(total);


                        if(total<0){
                            total = 0;
                        }
                        var sum     = 0;
                        var sum_num = 0;
                        //获取总优惠
                        var sum_yh  = 0;
                        $('.goodsMsg table tr td:nth-child(9)').find('.yh').each(function(){
                            var value = $(this).val();
                            if(value == ""){
                                value = 0;
                            }
                            sum_yh += parseFloat(value);
                        });
                        //获取总数量
                        $('.goodsMsg table tr td:nth-child(8)').find('.num').each(function(){
                            var value = $(this).val().replace(/[^0-9]/ig,"");
                            if(value == ""){
                                value = 0;
                            }
                            sum_num =parseFloat(sum_num) + parseFloat(value);
                        });

                        $('.goodsMsg .total .num').html(sum_num);
                        $('.goodsMsg .total td:eq(3)').find('input[name=total_num]').val(sum_num);
                        $(this).parents('tr').find('td:nth-child(10)').find('.total_one').html("￥"+total);
                        $(this).parents('tr').find('td:nth-child(10)').find('.pay').val(total);
                        $('.goodsMsg table tr td:nth-child(10)').each(function(){
                            var value = $(this).text().replace(/[^0-9\.]/ig,"");
                            if(value == ""){
                                value = 0;
                            }
                            sum = parseFloat(value) + parseFloat(sum);
                        });
                        $('.total .zong').html("￥"+sum);

                        $('.goodsMsg .total td:eq(5)').find('input[name=total_pay]').val(sum);

                        $('.totalMsg .total').html(sum+sum_yh);                   //总合计
                        $('.totalMsg .yh').html(sum_yh);                          //总优惠
                        $('.totalMsg .end').html(sum+parseFloat(post_fee));       //实际付得钱
                        $('.financeMsg .theory').val(sum+parseFloat(post_fee));   //应付金额
                        $('.financeMsg .remain').val(sum+parseFloat(post_fee));   //欠款金额
                    }

                }
            })

        });
        //商品名称下拉框改变时
        $(document).on('change','.find',function(){
            var t = $(this);
            var v = $(this).val();
            var post_fee = $('.commonMsg input[name=freight_buyer]').val();
            if(post_fee == ""){
                post_fee = 0;
            }
            $.ajax({
                url:"order_add.php",
                type:'post',
                data:{'find':v},
                dataType:'json',
                success:function(data){
                    if(data){
                        t.parent().next().find('select:first').empty();
                        var str = "<option value="+data['id']+">"+data['name']+"</option>";
                        t.parent().next().find('select:first').append(str);
                        t.parents('tr').find('td:nth-child(3)').find('.images').attr("src",data['image']);
                        t.parents('tr').find('td:nth-child(3)').find('.bigimg').attr("src",data['image']);
                        t.parents('tr').find('td:nth-child(6)').html(data['part_name']);
                        t.parents('tr').find('td:nth-child(7)').find('.danjia').html("￥"+data['price_display']);
                        t.parents('tr').find('td:nth-child(10)').html();
                        t.parents('tr').find('td:nth-child(7)').find('input[name=price]').val(data['price_display']);//隐藏单价

                        var num = t.parents('tr').find('.num').val();
                        var yh = t.parents('tr').find('.yh').val();
                        var danjia = t.parents('tr').find('td:nth-child(7)').text().replace(/[^0-9/.]/ig,"");
                        if(num == ""){
                            num = 0;
                        }
                        if(yh == ""){
                            yh = 0;
                        }
                        var total = danjia*num-yh;
                        t.parents('tr').find('td:nth-child(10)').find('.total_one').html("￥"+total);
                        t.parents('tr').find('td:nth-child(10)').find('.pay').val(total);

                        if(total<0){
                            total = 0;
                        }
                        var sum = 0;
                        var sum_num = 0;
                        //获取总优惠
                        var sum_yh = 0;
                        $('.goodsMsg table tr td:nth-child(9)').find('.yh').each(function(){
                            var value = $(this).val();
                            if(value == ""){
                                value = 0;
                            }
                            sum_yh += parseFloat(value);
                        });
                        //获取总数量
                        $('.goodsMsg table tr td:nth-child(8)').find('.num').each(function(){
                            var value = $(this).val().replace(/[^0-9]/ig,"");
                            if(value == ""){
                                value = 0;
                            }
                            sum_num =parseFloat(sum_num) + parseFloat(value);
                        });

                        $('.goodsMsg .total .num').html(sum_num);
                        $('.goodsMsg .total td:eq(3)').find('input[name=total_num]').val(sum_num);
                        $(this).parents('tr').find('td:nth-child(10)').find('.total_one').html("￥"+total);
                        $(this).parents('tr').find('td:nth-child(10)').find('.pay').val(total);

                        $('.goodsMsg table tr td:nth-child(10)').each(function(){
                            var value = $(this).text().replace(/[^0-9]/ig,"");
                            if(value == ""){
                                value = 0;
                            }
                            sum = parseFloat(value) + parseFloat(sum);
                        });
                        $('.total .zong').html("￥"+sum);
                        $('.goodsMsg .total td:eq(5)').find('input[name=total_pay]').val(sum);

                        $('.totalMsg .total').html(sum+sum_yh);                          //总合计
                        $('.totalMsg .yh').html(sum_yh);                                 //总优惠
                        $('.totalMsg .end').html(sum + parseFloat(post_fee));            //实际付得钱
                        $('.financeMsg .theory').val(sum+parseFloat(post_fee));          //应付金额
                        $('.financeMsg .remain').val(sum+parseFloat(post_fee));          //欠款金额

                    }
                }
            })
        });
        //输入数量后值发生变化
        $(document).on('keyup','.num',function(){
            var v = $(this).val();
            var post_fee = $('.commonMsg input[name=freight_buyer]').val();
            if(post_fee == ""){
                post_fee = 0;
            }
            if(v > 1000000){
                $('input[name="number[]"]').addClass('error_color');
                $('input[name="number[]"]').tooltip('show');
                return false;
            }else{
                $('input[name="number[]"]').removeClass('error_color');
                $('input[name="number[]"]').tooltip('hide');
            }
            var danjia = $(this).parents('tr').find('td:nth-child(7)').text().replace(/[^0-9.]/ig,"");
            var yh = $(this).parents('tr').find('.yh').val();
            if(danjia == ""){
                danjia = 0;
            }
            //判断数量值
            if(v == "" || v < 0){
                v = 0;
            }
            if(yh == ""){
                yh = 0;
            }
            var total = parseFloat(danjia)*v-yh;
            if(total<0){
                total = 0;
            }
            var sum = 0;
            var sum_num = 0;
            //获取总优惠
            var sum_yh = 0;
            $('.goodsMsg table tr td:nth-child(9)').find('.yh').each(function(){
                var value = $(this).val();
                if(value == ""){
                    value = 0;
                }
                sum_yh += parseFloat(value);
            });
            //获取总数量
            $('.goodsMsg table tr td:nth-child(8)').find('.num').each(function(){
                var value = $(this).val().replace(/[^0-9]/ig,"");
                if(value == ""){
                    value = 0;
                }
                sum_num =parseFloat(sum_num) + parseFloat(value);
            });

            $('.goodsMsg .total .num').html(sum_num);
            $('.goodsMsg .total td:eq(3)').find('input[name=total_num]').val(sum_num);
            $(this).parents('tr').find('td:nth-child(10)').find('.total_one').html("￥"+total);
            $(this).parents('tr').find('td:nth-child(10)').find('.pay').val(total);
            $('.goodsMsg table tr td:nth-child(10)').each(function(){
                var value = $(this).text().replace(/[^0-9\.]/ig,"");
                if(value == ""){
                    value = 0;
                }
                sum = parseFloat(value) + parseFloat(sum);
            });
            $('.total .zong').html("￥"+sum);
            $('.goodsMsg .total td:eq(5)').find('input[name=total_pay]').val(sum);
            $('.totalMsg .total').html(sum+sum_yh);                  //总合计
            $('.totalMsg .yh').html(sum_yh);                         //总优惠
            $('.totalMsg .end').html(sum+parseFloat(post_fee));      //实际付得钱
            $('.financeMsg .theory').val(sum+parseFloat(post_fee));  //应付金额
            $('.financeMsg .remain').val(sum+parseFloat(post_fee));  //欠款金额

        });
        //输入优惠后值发生变化
        $(document).on('keyup','.yh',function(){
            var danjia   = $(this).parents('tr').find('td:nth-child(7)').text().replace(/[^0-9.]/ig,"");
            var post_fee = $('.commonMsg input[name=freight_buyer]').val();
            var v        = $(this).val();
            var num      = $(this).parents('tr').find('.num').val();
            if(post_fee == ""){
                post_fee = 0;
            }
            if(danjia == ""){
                danjia = 0;
            }
            if(v == "" || danjia ==""){
                v = 0;
            }
            if(num == "" || num < 0){
                num =1;
            }
            var total = danjia*num-v;
            if(total<0){
                total = 0;
            }

            var sum = 0;
            var sum_yh = 0;
            $('.goodsMsg table tr td:nth-child(9)').find('.yh').each(function(){
                var value = $(this).val();
                if(value == ""){
                    value = 0;
                }
                sum_yh = parseFloat(sum_yh) + parseFloat(value);
            });
            $('.total .yh').html("￥"+sum_yh);
            $('.total td:eq(4)').find('input[name=total_yh]').val(sum_yh);
            $(this).parents('tr').find('td:nth-child(10)').find('.total_one').html("￥"+total);
            $(this).parents('tr').find('td:nth-child(10)').find('.pay').val(total);

            $('.goodsMsg table tr td:nth-child(10)').each(function(){
                var value = $(this).text().replace(/[^0-9\.]/ig,"");
                if(value == ""){
                    value = 0;
                }
                sum = parseFloat(value) + parseFloat(sum);

            });
            $('.total .zong').html("￥"+sum);
            $('.goodsMsg .total td:eq(5)').find('input[name=total_pay]').val(sum);
            $('.totalMsg .total').html(sum+sum_yh);                                //总合计
            $('.totalMsg .yh').html(sum_yh);                                       //商品总优惠
            $('.totalMsg .end').html(sum+parseFloat(post_fee));                    //实际付得钱
            $('.financeMsg .theory').val(sum+parseFloat(post_fee));                //应付金额
            $('.financeMsg .remain').val(sum+parseFloat(post_fee));                //欠款金额


        });


    $('.supplierMsg .shouju').change(function(){
        var v = $(this).val();
        if(v == "Y"){
            $('#fapiao').show();
        }else if(v == "N"){
            $('#fapiao').hide();
        }
    });

    $('input[name=recog]').blur(function(){

        var v = $(this).val();
        $.ajax({
            url:'order_add.php',
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
    $('.supplierMsg').on('change','.crm',function(){
        var v = $(this).val();
        $.ajax({
            url:'order_add.php',
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

    $('.bar').click(function(){
        var v = $('.tiao').css('display');
        if(v == "none"){
            $('.tiao').css('display','inline');
             $('.goodsMsg input[name=bar]').focus();
            $('.goodsMsg input[name=bar]').select();
        }else if(v == 'inline'){
            $('.tiao').css('display','none');

        }

    })

        //扫码添加
    $('.goodsMsg').on('keypress','input[name=bar]',function(e){
        if(e.keyCode == 13){
            var post_fee = $('.commonMsg input[name=freight_buyer]').val();
            if(post_fee == ""){
                post_fee = 0;
            }
            val = [];
            var v = $(this).val();
            reg = /[a-zA-Z0-9]/g;
            va = new Array();
            var value = $(this).parents('tr').prev().find('th:eq(3)').html();
            $('.goodsMsg table tr').find('select').each(function(){
                var a = $(this).val();
                if(a != null){
                    va.push(a);
                }
            })
            arr = va.join(",");
            if(reg.test(v)){
                $.ajax({
                    url:"order_add.php",
                    type:'get',
                    data:{'bar':v},
                    dataType:'json',
                    success:function(data){
                        if(data){
                            $('.goodsMsg table tr').find('select').each(function(){
                                var v = $(this).val();
                                if(v == null){
                                    $(this).parents('tr').remove();
                                }
                                val.push(v);
                            })

                            if(in_array(data['id'],val)){
                                $('.goodsMsg table tr').find('select').each(function(){
                                    var t = $(this);
                                    var v = t.val();
                                    var number = t.parents('tr').find('td:nth-child(8)').find('input[name="number[]"]').val();
                                    if(v == data['id']){
                                        number++;
                                        t.parents('tr').find('td:nth-child(8)').find('input[name="number[]"]').val(number);
                                        var num = t.parents('tr').find('.num').val();
                                        var yh = t.parents('tr').find('.yh').val();
                                        var danjia = t.parents('tr').find('td:nth-child(7)').text().replace(/[^0-9\.]/ig,"");
                                        if(num == ""){
                                            num = 0;
                                        }
                                        if(yh == ""){
                                            yh = 0;
                                        }
                                        var total = danjia*num-yh;
                                        t.parents('tr').find('td:nth-child(10)').find('.total_one').html("￥"+total);
                                        t.parents('tr').find('td:nth-child(10)').find('.pay').val(total);


                                        if(total<0){
                                            total = 0;
                                        }
                                        var sum = 0;
                                        var sum_num = 0;
                                        //获取总优惠
                                        var sum_yh = 0;
                                        $('.goodsMsg table tr td:nth-child(9)').find('.yh').each(function(){
                                            var value = $(this).val();
                                            if(value == ""){
                                                value = 0;
                                            }
                                            sum_yh += parseFloat(value);
                                        });
                                       //获取总数量
                                        $('.goodsMsg table tr td:nth-child(8)').find('.num').each(function(){
                                            var value = $(this).val().replace(/[^0-9]/ig,"");
                                            if(value == ""){
                                                value = 0;
                                            }
                                            sum_num =parseFloat(sum_num) + parseFloat(value);
                                        });
                                        $('.goodsMsg .total .num').html(sum_num);
                                        $('.goodsMsg .total td:eq(3)').find('input[name=total_num]').val(sum_num);
                                        $('.goodsMsg table tr td:nth-child(10)').each(function(){
                                            var value = $(this).text().replace(/[^0-9\.]/ig,"");
                                            if(value == ""){
                                                value = 0;
                                            }
                                            sum = parseFloat(value) + parseFloat(sum);
                                        });
                                        $('.total .zong').html("￥"+sum);
                                        $('.goodsMsg .total td:eq(5)').find('input[name=total_pay]').val(sum);
                                        $('.totalMsg .total').html(sum+sum_yh);                  //总合计
                                        $('.totalMsg .yh').html(sum_yh);                         //总优惠
                                        $('.totalMsg .end').html(sum+parseFloat(post_fee));      //实际付得钱
                                        $('.financeMsg .theory').val(sum+parseFloat(post_fee));  //应付金额
                                        $('.financeMsg .remain').val(sum+parseFloat(post_fee));  //欠款金额
                                    }
                                        $('.goodsMsg input[name=bar]').val('');
                                        $('.goodsMsg input[name=bar]').focus();
                                })

                            }else{
                                    $('.goodsMsg input[name=bar]').val('');
                                    $('.goodsMsg input[name=bar]').focus();
                                    var trstr = '<tr><td class="center"></td><td class="center"><input type="checkbox" name="select_one"></td><td class="center"><img src=""></td><td class="order_add_td_2"><input type="text" name="search" class="form-control input-sm form_no_border search"></td><td class="order_add_td_2" style="width:200px;"><select class="form-control input-sm form_no_border find" name="good_name[]" placeholder="必填" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="最少选择一个商品"><option value="'+data['id']+'">'+data['name']+'</option></select></td><td>'+data['part_name']+'</td><td class="order_add_td_2" style="width:80px;"><div class="input-group"><div class="input-group-addon danjia">￥</div><input type="hidden" name="price[]" class="price" value="'+data['price_display']+'"></div>'+data['price_display']+'</td><td class="order_add_td_3"><input type="text" name="number[]" value="'+1+'" style="width:70px" data-toggle="tooltipx" data-placement="bottom" data-original-title="商品数量至少为1" class="form-control input-sm form_no_border num"></td><td class="order_add_td_2" style="width:100px;"><div class="input-group"><div class="input-group-addon">￥</div><input type="text" name="discount[]" style="width:70px" class="form-control input-sm form_no_border yh"></div></td><td class="order_add_td_2" style="width:80px;"><div class="input-group"><div class="input-group-addon total_one">￥'+data['price_display']+'</div><input type="hidden" name="pay[]" class="pay" value="'+data['price_display']+'"></div></td><td class="order_add_td_2"><input type="text" name="content[]" class="form-control input-sm form_no_border number"></td></tr>';
                                    $('.goodsMsg table tr').last().before(trstr);

                                    //重置下标
                                    $('.goodsMsg table tr').each(function (index, value) {
                                        if (index > 0) {
                                            $(value).find('td').eq(0).html(index);
                                        }
                                    });

                                    $(".goodsMsg table tr").last().children().first().html("合计");
                                    var num = t.parents('tr').find('.num').val();
                                    var yh = t.parents('tr').find('.yh').val();
                                    var danjia = t.parents('tr').find('td:nth-child(7)').text().replace(/[^0-9\.]/ig,"");
                                    if(num == ""){
                                        num = 0;
                                    }
                                    if(yh == ""){
                                        yh = 0;
                                    }
                                    var total = danjia*num-yh;
                                    t.parents('tr').find('td:nth-child(10)').find('.total_one').html("￥"+total);
                                    t.parents('tr').find('td:nth-child(10)').find('.pay').val(total);


                                    if(total<0){
                                        total = 0;
                                    }
                                    var sum = 0;
                                    var sum_num = 0;
                                    //获取总优惠
                                    var sum_yh = 0;
                                    $('.goodsMsg table tr td:nth-child(9)').find('.yh').each(function(){
                                        var value = $(this).val();
                                        if(value == ""){
                                            value = 0;
                                        }
                                        sum_yh += parseFloat(value);
                                    });
                                    $('.goodsMsg .total .yh').html("￥"+sum_yh);
                                    $('.goodsMsg .total td:eq(4)').find('input[name=total_yh]').val(sum_yh);

                                   //获取总数量
                                    $('.goodsMsg table tr td:nth-child(8)').find('.num').each(function(){
                                        var value = $(this).val().replace(/[^0-9]/ig,"");
                                        if(value == ""){
                                            value = 0;
                                        }
                                        sum_num =parseFloat(sum_num) + parseFloat(value);
                                    });
                                    $('.goodsMsg .total .num').html(sum_num);
                                    $('.goodsMsg .total td:eq(3)').find('input[name=total_num]').val(sum_num);
                                    $('.goodsMsg table tr td:nth-child(10)').each(function(){
                                        var value = $(this).text().replace(/[^0-9\.]/ig,"");
                                        if(value == ""){
                                            value = 0;
                                        }
                                        sum = parseFloat(value) + parseFloat(sum);
                                    });
                                    $('.total .zong').html("￥"+sum);
                                    $('.goodsMsg .total td:eq(5)').find('input[name=total_pay]').val(sum);
                                    $('.totalMsg .total').html(sum+sum_yh);                  //总合计
                                    $('.totalMsg .yh').html(sum_yh);                         //总优惠
                                    $('.totalMsg .end').html(sum+parseFloat(post_fee));      //实际付得钱
                                    $('.financeMsg .theory').val(sum+parseFloat(post_fee));  //应付金额
                                    $('.financeMsg .remain').val(sum+parseFloat(post_fee));  //欠款金额


                            }
                        }
                    }
                })
            }else{
                return false;
            }
        return false;
        }
    })

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
		//判断实收金额是否为空
        if($(this).val()){
            var v = $(this).val().replace(/[^0-9\.]/ig,'');
        }else{
            var v = 0.00;
        }
        //应付金额
		var total = $('.financeMsg .theory').val().replace(/[^\d\.]/,'');
		var remain = parseFloat(total)-parseFloat(v);
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
    //---- 增加实收金额前的数量 ----
    var beforeAmount = $('.commonMsg input[name=real_amount]').val();
	//固定电话和手机号码验证
	$('.form-inline').submit(function(){
        var afterAmount = $('.commonMsg input[name=real_amount]').val();
        $('.commonMsg input[name=beforeAmount]').val(beforeAmount);
        $('.commonMsg input[name=afterAmount]').val(afterAmount);
        if (parseFloat(beforeAmount) > parseFloat(afterAmount)) {
            $('#confirm .number').text(beforeAmount);
            $('#confirm').modal('show');
            $('#print-menu').hide();
            return false;
        }

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
            url:'order_list_audit_detail.php',
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
            url:'order_list_audit_detail.php',
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

    //买家运费
    $('.commonMsg input[name=freight_buyer]').keyup(function(){
        var post_fee = $(this).val();
        var end      = $('.table .total .zong').html().replace(/[^0-9.]/ig,"");
        if(post_fee == ""){
            post_fee = 0
        }
        if(end == ""){
            end = 0;
        }
        $('.totalMsg .end').html(parseFloat(end)+parseFloat(post_fee));      //实际付得钱
        $('.financeMsg .theory').val(parseFloat(end)+parseFloat(post_fee));  //应付金额
        $('.financeMsg .remain').val(parseFloat(end)+parseFloat(post_fee));  //欠款金额
    })


});