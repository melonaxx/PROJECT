$(function(){
	//设置模态窗出现
	$(".set-ship").click(function(){
		$(".modal-shipset").show();
	})
	//点击三角号实际城市出现；
	$(".car_left").click(function(){
		$(this).parents(".dropdown").find(".dropdown_body").slideToggle();
	});

	// 点击省选择框所辖市全选
	$(".dropdown_header .checkbox").click(function(){
		if(this.checked){
			$(this).parents(".dropdown").find(".dropdown_body .checkbox").prop("checked",true);
		}else{
			$(this).parents(".dropdown").find(".dropdown_body .checkbox").prop("checked",false);
		}
	});
	//点击全选
	$(".modal-allcheck").click(function(){
		$('.dropdown_header .checkbox').prop('checked',true);
		$('.dropdown_body .checkbox').prop('checked',true);
	})
	//点击全不选
	$(".modal-uncheck").click(function(){
		$('.dropdown_header .checkbox').prop('checked',false);
		$('.dropdown_body .checkbox').prop('checked',false);
	})
	//点击反选
	$(".modal-reverse").click(function(){
		$('.dropdown_header .checkbox').each(function(){
			var ttt = $(this);
			var all_checked = true;
			ttt.parents('.dropdown').find('.dropdown_body .checkbox').each(function(){
				if(this.checked)
				{
					$(this).prop('checked',false);
					all_checked = false;
				}else{
					$(this).prop('checked',true);
				}
			});
			if(all_checked==false)
			{
				ttt.prop('checked',false);
			}else{
				ttt.prop('checked',true);
			}
		});

	})
	//单击单个多选框
	$(".dropdown_body .checkbox").off('click').click(function(){
		var  sheng = $(this).parents(".dropdown").find(".dropdown_header .checkbox");
		var  shi = $(this).parents(".dropdown").find(".dropdown_body .checkbox");
		var  all_checked = true;
		//选中checkbox的个数
		var citynumber = 0;
		shi.each(function(i){
			if (shi.eq(i).prop('checked') == true) {
				citynumber++;
			}
			// if(shi.eq(i).context.checked == true){
			// 	citynumber++;
			// 	all_checked = true;
			// 	return true;
			// }else{
			// 	all_checked = false;
			// 	return false;
			// }
		});
		if(citynumber<=0){
			sheng.prop("checked",false);
		}else{
			sheng.prop("checked",true);
		}
	});
	//点击确定创建
	var $body=$(".shipping-tbody");
	//存放省市的数组
	var procityarr = new Object();
	$(".sure-city").click(function(){
		$(this).prop('disabled',true);
		$(".modal-shipset").hide();
		$('.addaddresswindow').show();
		var storeid = $('input[name=storeid]').val();
		$('.dropdown_header').each (function(){
			//省的ID号
			if ($(this).find('input').prop('checked') == true) {
				var pronumber = $(this).find('input:checked').val();
				//市的ID号
				var citylist = $(this).next().find('input:checked');
				var cityarr = new Array();
				$(citylist).each(function(){
					//市数组
					var onecity = $(this).val();
					cityarr.push(onecity);
				})
				procityarr[pronumber] = cityarr;
			}
		});
		//首先的循环所选城市的个数;
		// $(".dropdown_header input[type='checkbox']").each(function(i){
		// 	var $number=i+1;
		// 	if(this.checked){
		// 		var $city=$(this).parents(".dropdown_header").next().children().text();
		// 		var $tr=$('<tr class="shipping-tr"></tr>');
		// 		var $td1=$('<td>'+i+'</td>');
		// 		var $td2=$('<td>'+$(this).parent().text()+'</td>');
		// 		var $td3=$('<td>'+$city.replace(/^\s*/, '').split("  ")+'</td>');
		// 		$tr.append($td1);
		// 		$tr.append($td2);
		// 		$tr.append($td3);
		// 		$body.append($tr);
		// 		this.checked=false;
		// 		if($(".shipping-tr").length==1){
		// 			$td1.html(1);
		// 		}else{
		// 			$td1.html(Number($tr.prev().children().eq(0).html())+1);
		// 		}
		// 		// $(".modal-shipset").show();
		// 	}
		// });

	// console.log(procityarr);
		//仓库发货信息存入数据库中
		var addshipaddrsuccess = function(msg){
			if (msg == 1) {
			$('.addaddresswindow').hide();
				alert('添加仓库发货地址成功！');
				location.reload();
			}
		}
		var addshipaddrfail = function(){
			$('.addaddresswindow').hide();
			// alert('添加仓库发货地址失败！');
			// location.reload();
		}
		util.ajax_post('/warehouse/addshipaddress.php',{procityarr:procityarr,storeid:storeid},addshipaddrsuccess,addshipaddrfail);

	});
})
