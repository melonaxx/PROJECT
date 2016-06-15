$(function(){
	if(navigator.userAgent.indexOf('Chrome') != -1 ){
		$(".btn-group .dropdown-toggle").attr("style","padding:12px 8px");
	}
		console.log(navigator.userAgent);


	$("input[name = 'select_all']").click(function(){
		if(this.checked){
			$(".tab_select input[type = 'checkbox']").prop("checked",true);
		}else{
			$(".tab_select input[type = 'checkbox']").prop("checked",false);
		}
	});

	$('#confirm').modal('hide');
	$(".order_select").click(function(){
		select_order($(this));
	});

	$(".order_select2").click(function(){
		select_order2($(this));
	});

	// 一般判断
	function select_order(item){
		var print_url = item.attr('href');
		if($(".tab_select input[type='checkbox']").is(':checked')){
			// 获取选中的id
			var order_id = "";
			$("input[name='select_one']").each(function(){
				if(this.checked){
					order_id += $(this).val() + ",";
				}
			});
			location.replace(print_url +"&order_id="+order_id);
			return true;
		}else{
			$('#confirm').modal('show');
			$('#print-menu').hide();
			return false;
		}

	}

	// 打印预览与设计(有弹窗)
	function select_order2(item){
		if($(".tab_select input[type='checkbox']").is(':checked'))
		{
			var order_id 	= "";
			var express 	= [];
			var express_eq 	= true; // 判断所选的快递单类型是否相同
			var express_id; // 快递公司id
			var tr_floor    = "";
			$("input[name='select_one']").each(function(index){
				if(this.checked)
				{
					order_id  += $(this).val() + ",";
					express_id = $(this).parents('tr').find('input[name=express_id]').val();
					if(item.hasClass("express"))
					{
						express.push(express_id);
						// 判断所选快递类型
						if(express[0] != express_id)
						{
							express_eq = false;
						}
						else if(express_id == "0")
						{
							express_eq = "no";
						}

						if($(this).parents('tr').find(".mark_express").text() != ""){
							tr_floor += parseInt(index+1)+',';
						}
						print_text = "快递单";
					}
					else if(item.hasClass("deliver"))
					{
						if($(this).parents('tr').find(".mark_deliver").text() != ""){
							tr_floor += index+1 +',';
						}
						print_text = "发货单";

					}
					else if(item.hasClass("order"))
					{
						if($(this).parents('tr').find(".mark_order").text() != ""){
							tr_floor += index+1 +',';
						}
						print_text = "配货单";

					}
				}
			});

			if(item.hasClass("express"))
			{
				if(express_eq == false)
				{
					// alert("所选订单的快递种类不同，不能同时打印！");
					$('#confirm .modal-body').html("所选订单的快递种类不同，不能同时打印！");
					$('#confirm .other').html("");
					$('#confirm').modal('show');
					return false;
				}
				else if(express_eq == "no")
				{
					// alert("请先选择快递！");
					$('#confirm .modal-body').html("请先选择快递！");
					$('#confirm .other').html("");
					$('#confirm').modal('show');
					return false;
				}

			}

			if(tr_floor != "")
			{
				$('#confirm .modal-body').html("第"+tr_floor+"行已经打印过"+print_text+"！");
				$('#confirm .other').html("您确定要打印吗?");
				$('#confirm').modal('show');
				$('.ok').click(function(){
					if(item.hasClass("go_print"))
					{
						sessionStorage.setItem("go_print","ok");
					}
					MessageBox(item.attr("go")+'&order_id='+order_id+'&express_id='+express_id+'&nocache='+Math.random(), '打印预览与设计',980,210);

					$('#confirm .other').html("");
				});
			}
			else
			{
				if(item.hasClass("go_print"))
				{
					sessionStorage.setItem("go_print","ok");
				}
				MessageBox(item.attr("go")+'&order_id='+order_id+'&express_id='+express_id+'&nocache='+Math.random(), '打印预览与设计',980,210);
			}

		}
		else
		{
			$('#confirm .modal-body').html("请至少选择1个订单");
			$('#confirm .other').html("");
			$('#confirm').modal('show');
			$('#print-menu').hide();
			return false;
		}


	}

	// 提交异常
	$(".exception").click(function(){
		if($(".tab_select input[type='checkbox']").is(':checked')){
			var a = getData();
			MessageBox('/deliver/deliver_commit_express.php?id='+a, '提交异常',412,185);
			return false;
		}else{
			$('#confirm .modal-body').html("请至少选择1个订单");
			$('#confirm .other').html("");
			$('#confirm').modal('show');
			$('#print-menu').hide();
			return false;
		}

	});


	$("#print").click(function(){
		$('#print-menu').show();
	});

	$("#print-menu").mouseleave(function(){
		$('#print-menu').hide();
		$('#print').blur();
	});

	//确认配货
	$('.pei').click(function(){
		if($(".tab_select input[type='checkbox']").is(':checked')){
			var b = true;
			var num = 0;
			$('.tab_select tr td:nth-child(2)').find('input[name=select_one]:checked').each(function(){
				var va = $(this).parents('tr').find('td:nth-child(8)').html();
				if(va == ""){
					num++;
					b = false;
				}
			})
			if(b){
				$('#confirm .modal-body').html("您确定要配货吗?");
				$('#confirm .other').html("");
				$('#confirm').modal('show');
				$('.ok').click(function(){
					var arr = "";
					$('table tr td:nth-child(2)').find('input[name=select_one]:checked').each(function(){
						var v = $(this).parent().find('input[name=id]').val();
						arr += v+",";
					})

					if(arr){
						$.ajax({
							url:'deliver_express_printing.php',
							type:'get',
							dataType:'json',
							data:{'status':arr},
							success:function(data){
								if(data == 1){
									window.location.href="deliver_express_printing.php";
								}else{
									alert('配货失败');
								}
							}
						});
					}
				});
			}else{
				$('#confirm .modal-body').html("有"+num+"笔订单没有填快递单号");
				$('#confirm .other').html("您确定要提交吗?");
				$('#confirm').modal('show');
				$('.ok').click(function(){
					var arr = "";
					$('table tr td:nth-child(2)').find('input[name=select_one]:checked').each(function(){
						var v = $(this).parent().find('input[name=id]').val();
						arr += v+",";
					})

					if(arr){
						$.ajax({
							url:'deliver_express_printing.php',
							type:'get',
							dataType:'json',
							data:{'status':arr},
							success:function(data){
								if(data == 1){
									window.location.href="deliver_express_printing.php";
								}else{
									alert('配货失败');
								}
							}
						});
					}
				})
			}


		}else{
			$('#confirm .modal-body').html("请至少选择1个订单");
			$('#confirm .other').html("");
			$('#confirm').modal('show');
			$('#print-menu').hide();
			return false;
		}
	})

	//打回审核
	$('.shen').click(function(){
			if($(".tab_select input[type='checkbox']").is(':checked')){

				$('#confirm .modal-body').html("您确定要打回审核吗?");
				$('#confirm .other').html("");
				$('#confirm').modal('show');
				$('.ok').click(function(){
					var arr = "";
					$('table tr td:nth-child(2)').find('input[name=select_one]:checked').each(function(){
						var v = $(this).parent().find('input[name=id]').val();
						arr += v+",";
					});
					if(arr){
						$.ajax({
							url:'deliver_express_printing.php',
							type:'get',
							dataType:'json',
							data:{'id':arr},
							success:function(data){
								if(data == 1){
									window.location.href="deliver_express_printing.php";
								}else{
									alert('打回审核失败');
								}
							}
						})
					}
				})
			}else{
				$('#confirm .modal-body').html("请至少选择1个订单");
				$('#confirm .other').html("");
				$('#confirm').modal('show');
				$('#print-menu').hide();
				return false;
			}
		})
	//生成快递单
	$('.express_add').click(function(){
		if($(".tab_select input[type='checkbox']").is(':checked')){
			var b = true;
			arr = [];

			$('.tab_select tr td:nth-child(2)').find('input[name=select_one]:checked').each(function(){
				var val = $(this).parents('tr').find('td:nth-child(7)').find('input[name=express_id]').val();
				arr.push(val);
			})

			for(var i=0;i<arr.length;i++){
				if(arr[0] != arr[i]){
					b = false;
				}
			}

			if(b){
				var a = '';
				$('.tab_select tr td:nth-child(2)').find('input[name=select_one]:checked').each(function(){
					a += $(this).next().val()+",";
				})
				MessageBox('/deliver/deliver_add_expressid.php?id='+a, '生成快递单',500,100);
				return false;
			}else{
				$('#confirm .modal-body').html("所选订单的快递种类不同，不能同时生成快递单！");
				$('#confirm .other').html("");
				$('#confirm').modal('show');
				$('#print-menu').hide();
				return false;

			}


		}else{
			$('#confirm .modal-body').html("请至少选择1个订单");
			$('#confirm .other').html("");
			$('#confirm').modal('show');
			$('#print-menu').hide();
			return false;
		}
	})


	//批量修改备注
		$('.bei').click(function(){
			var a = getData();
			if(a == ""){
				$('#confirm .modal-body').html("请至少选择1个订单");
				$('#confirm .other').html("");
				$('#confirm').modal('show');
				$('#print-menu').hide();
				return false;
			}
			MessageBox('/deliver/deliver_modify_message.php?id='+a, '批量修改备注',410,255);
			return false;

		});

	//批量修改快递
	$('.mod_express').click(function(){
		var a = getData();
		if(a == ""){
			$('#confirm .modal-body').html("请至少选择1个订单");
			$('#confirm .other').html("");
			$('#confirm').modal('show');
			$('#print-menu').hide();
			return false;
		}

		MessageBox('/deliver/deliver_modify_express.php?id='+a, '批量修改快递',265,80);
		return false;

	});
	//获取参数
	function getData(){
		var a = '';
		$('.table tr td:nth-child(2)').find('input[name=select_one]:checked').each(function(){
			a += $(this).next().val()+",";
		});
		return a;
	}


	// $(".close").click(function(){
	// 	alert(1);
	// 	parent.window.location.reload();
	// });

});