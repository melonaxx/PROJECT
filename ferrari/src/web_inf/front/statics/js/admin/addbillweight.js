$(function(){
	//仓库设置
	billwareset();
	function billwareset(){
		$(".billware-set").click(function(){
			$(".ck").parent().next().empty();
			cangku = $(this);
			$.ajax({
				type: "POST",
				url: "/admin/findallstore.php",
				success: function(msg){
					var json = eval(msg)
					$.each(json,function(idx,item){
						var id = item.id;
						var name = item.name;
						$(".ck").parent().next().append("<tr><td><input class='check' type='checkbox' value='"+item.id+"'></td><td>"+item.name+"</td></tr>")
					});
				},
			});
			$(".modal-wareset").show();
		});
	}
	//点击仓库确定
	$("#store_confirm").click(function(){
		var store_str_text 	= "";
		var store_str_id 	= "";
		$(".check").each(function(){
			if(this.checked){
				store_str_text += $(this).parent().next().html()+":";
				store_str_id  += $(this).val()+":";
			}
		})
		store_str_text = store_str_text.substring(0,store_str_text.length-1);
		store_str_id = store_str_id.substring(0,store_str_id.length-1);
		$(".modal-wareset").hide();
		cangku.prev().val(store_str_text);
		cangku.prev().attr('storeid',store_str_id);
	});

	//地区设置
	billaddressset();
	function billaddressset(){
		$(".billaddress-set").click(function(){
			diqu=$(this);
			$(".modal-address").show();
		});
	}
	//地区确定键
	$("#area_confirm").click(function(){
		var area_str_text 	= "";
		var area_str_id 	= "";
		$(".dropdown .dropdown_header .checkbox").each(function(){
			if(this.checked){
				area_str_text += $(this).parent().text()+";";
				area_str_id  += $(this).attr("id")+":";
				$(this).parents(".dropdown").find(".dropdown_body .checkbox").each(function(){
					if(this.checked){
						area_str_id  += $(this).attr("id")+":";
					}
				});
			}else{
				$(this).parents(".dropdown").find(".dropdown_body .checkbox").each(function(){
					if(this.checked){
						area_str_text += $(this).parent().text()+";";
						area_str_id  += $(this).attr("id")+":";
					}
				});
			}

		});
		area_str_text = area_str_text.substring(0,area_str_text.length-1);
		area_str_id = area_str_id.substring(0,area_str_id.length-1);
		$(".modal-address").hide();
		diqu.prev().val(area_str_text);
		diqu.prev().attr('areaid',area_str_id);
	});
	//last设置
	billweightset();
	function billweightset(){
		zhongliang = $(this);
		$(".billweight-set").click(function(){
			if($(this).parent().siblings().children(".billweight-address").html()!=""){
				$(".modal-billweightset").show();
			}else{
				$(".modal-tip").show();
			}
		});
	}
	// 地区全选
	$(".car_left").click(function(){
		$(this).parents(".dropdown").find(".dropdown_body").slideToggle();
	});
	$(".dropdown_header .checkbox").click(function(){
		if(this.checked){
			$(this).parents(".dropdown").find(".dropdown_body .checkbox").prop("checked",true);
		}else{
			$(this).parents(".dropdown").find(".dropdown_body .checkbox").prop("checked",false);
		}
	});
	$(".dropdown_body .checkbox").click(function(){
		var  sheng = $(this).parents(".dropdown").find(".dropdown_header .checkbox");
		var  shi = $(this).parents(".dropdown").find(".dropdown_body .checkbox");
		var  all_checked = true;
		shi.each(function(){
			if(this.checked){
				return true;
			}else{
				all_checked = false;
				return false;
			}
		});
		if(all_checked==false){
			sheng.prop("checked",false);
		}else{
			sheng.prop("checked",true);
		}
	});
	$(".close-btn").click(function(){
		$(this).parent().siblings(".modal-bd").find(".dropdown input").prop("checked",false);
		$(this).parent().siblings(".modal-bd").find(".dropdown_body").hide();
		$(this).parent().siblings(".modal-bd").find(".wareset-table input").prop("checked",false);

	});
	// 仓库全选
	$("#all_checked").click(function(){
		if(this.checked){
			$(".check").prop("checked",true);
		}else{
			$(".check").prop("checked",false);
		}
	});
	$(".first-add").click(function(){
		if($(".first-tr").length==0){
			var $ftr=$('<tr class="first-tr"></tr>');
			var $ftd1=$('<td style="text-align: center"><a href="javascript:;" class="delete_one">删除</a></td>');
			var $ftd2=$('<td style="text-align: center"></td>');
			var $ftd21=$('<input class="form-control input-sm input-price input-all" value="0">');
			var $ftd22=$('<p class="priceunit"></p>');
			var $ftd3=$('<td style="text-align: center"><input class="form-control input-sm input-price input-all" value=""><p class="priceunit"></p></td>');
			var $ftd4=$('<td style="text-align: center"><p class="weightunit"></p><input class="form-control input-sm input-weight input-all" value=""></td>');
			$ftd2.append($ftd21);
			$ftd2.append($ftd22);
			$ftr.append($ftd1);
			$ftr.append($ftd2);
			$ftr.append($ftd3);
			$ftr.append($ftd4);
			$(".first-tbody").append($ftr);
			$ftd22.show();
			$ftd21.hide();
			$ftd22.html((Number($ftd21.val())).toFixed(2)+"kg");
			Delfirst();
			ChangeInput();
		}
		else if($(".first-tr").length<5){
			var $ftr=$('<tr class="first-tr"></tr>');
			var $ftd1=$('<td style="text-align: center"><a href="javascript:;" class="delete_one">删除</a></td>');
			var $ftd2=$('<td style="text-align: center"></td>');
			var $ftd21=$('<input class="form-control input-sm input-price input-all">');
			var $ftd22=$('<p class="priceunit"></p>');
			var $ftd3=$('<td style="text-align: center"><input class="form-control input-sm input-price input-all" value=""><p class="priceunit"></p></td>');
			var $ftd4=$('<td style="text-align: center"><p class="weightunit"></p><input class="form-control input-sm input-weight input-all" value=""></td>');
			$ftd2.append($ftd21);
			$ftd2.append($ftd22);
			$ftr.append($ftd1);
			$ftr.append($ftd2);
			$ftr.append($ftd3);
			$ftr.append($ftd4);
			$(".first-tbody").append($ftr);
			var pvalue =$ftr.prev().children("td").eq(2).find(".priceunit").text();
			$ftd21.hide();
			$ftd22.show();
			$ftd22.addClass("comcss");
			$ftd22.html(pvalue);
			$ftd22.on("dblclick",function(){
				$(this).siblings("input").show().focus();
				$(this).siblings("input").val((Number($(this).html().replace("kg",""))).toFixed(2));
				$(this).hide();
			});
			Delfirst();
			ChangeInput();
		}else{
			alert("首重部分不能超过5条");
		}

	});
	/*删除首重*/
	Delfirst();
	function Delfirst(){
		$(".delete_one").click(function(){
			$(this).parent().parent(".first-tr").remove();
		});
	}
	/*文本框的输入变化*/
	ChangeInput();
	function ChangeInput(){
		$(".input-all").on("keyup",function(){
			$(this).val($(this).val().replace(/[^\d.]/g,""));
		});
		$(".input-price").on("blur",function(){
			if($(this).val()!=""){
				$(this).next().show();
				$(this).hide();
				$(this).next().html((Number($(this).val())).toFixed(2)+"kg");
				$(this).next().on("dblclick",function(){
					$(this).siblings("input").show().focus();
					$(this).siblings("input").val((Number($(this).siblings("input").val())).toFixed(2));
					$(this).hide();
				});
			}
		});
		$(".input-weight").on("blur",function(){
			if($(this).val()!=""){
				$(this).prev().show();
				$(this).hide();
				$(this).prev().html("￥"+(Number($(this).val())).toFixed(2));
				$(this).prev().on("dblclick",function(){
					$(this).siblings("input").show().focus();
					$(this).siblings("input").val((Number($(this).siblings("input").val())).toFixed(2));
					$(this).hide();
				});
			}
		});
	}
	/*续重部分的重量增加*/
	$(".kg").on("blur",function(){
		if($(this).val()!=""){
			$(this).val((Number($(this).val())).toFixed(2)+"kg");
		}
	});
	$(".kg").on("focus",function(){
		$(this).val($(this).val().replace("kg",""));
		$(this).on("keyup",function(){
			$(this).val($(this).val().replace(/[^\d.]/g,""));
		});
	});
	/*续重部分的价格增加*/
	$(".money").on("blur",function(){
		if($(this).val()!=""){
			$(this).val("￥"+(Number($(this).val())).toFixed(2));
		}
	});
	$(".money").on("focus",function(){
		$(this).val($(this).val().replace("￥",""));
	});
	$("#weightre").click(function(){
		var fee_default = "";
		var aaa = "";
		var bbb = "";
		if($(".first-tr").length >0){
			fee_default="首重部分:"
		}
		for(var i=0;i<$(".first-tr").length;i++){
			var weight_1	 = $(".first-tr:eq("+i+") td:eq(1)").children("p").html();
			var weight_2 	 = $(".first-tr:eq("+i+") td:eq(2)").children("p").html();
			var weightval = weight_2.match(/^\d+[\.]?\d+/);
			var fee  = $(".first-tr:eq("+i+") td:eq(3)").children("p").html();
			var price = fee.replace("￥","");
			fee_default += "从"+weight_1+"-"+weight_2+","+"费用为"+fee+";";
			aaa += weightval+":";
			bbb += price+":";
		}
		aaa = aaa.substring(0,aaa.length-1);
		bbb = bbb.substring(0,bbb.length-1);
		var follw_weight 	= $("input[name=every_weight]").val();
		var follw_fee 		= $("input[name=every_fee]").val();
		if(follw_weight!="" && follw_fee !=""){
			fee_default += "续重部分：重量每增加"+follw_weight+",增加费用"+follw_fee+";";
		}
		weightincrease = follw_weight.match(/^\d+[\.]?\d+/);
		priceincrease = follw_fee.replace("￥","");
		$(".modal-billweightset").hide();
		$("#role").val(fee_default);
		$("#role").attr('firstweight',aaa);
		$("#role").attr('firstprice',bbb);
		$("#role").attr('weightincrease',weightincrease);
		$("#role").attr('priceincrease',priceincrease);
	})
	$("#subid").click(function(){
		var address = $("#address").attr("areaid");
		var store = $("#store").attr("storeid");
		var firstweight = $("#role").attr("firstweight");
		var firstprice = $("#role").attr("firstprice");
		var weightincrease = $("#role").attr("weightincrease");
		var priceincrease = $("#role").attr("priceincrease");
		var companyid = $("#address").attr("cid");
		$.ajax({
		   type: "POST",
		   url: "/admin/doaddbillweight.php",
		   data:{expressid:companyid,arealist:address,storeid:store,firstweight:firstweight,firstprice:firstprice,weightincrease:weightincrease,priceincrease:priceincrease},
		   success: function(msg){
                if(msg==1){
                	alert("添加成功!");
        		window.location.href = "/admin_billweight.php?id="+companyid; 	
                }else if(msg==0){
                	alert("添加失败!");
                }else{
                	console.log(msg);
                	
                }
		   },
		   error: function(){
		   	 alert("ajax请求失败")
		   }
		});
	})
})