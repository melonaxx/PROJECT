$(function(){
	// 查出省市县id
	var id=$("#hidden").val();
	$.ajax({
		type: "POST",
		url: "/app/findcity.php",
		data:{id:id},
		success: function(msg){
			var json = eval('('+msg+')');
			stateid = json.stateid;
			cityid = json.cityid;
			districtid = json.districtid;
			searchpcc("pro","city","county",stateid,cityid,districtid);

		}
	});
	//修改
	$(".custom-change").click(function(){
		if($(".custom-name1").val()!=""){
			var $c_code1=$(".custom-code1").val();
			var $c_name1=$(".custom-name1").val();
			var $c_type1=$(".custom-type1").val();
			var $c_url1=$(".custom-url1").val();
			var $c_linkman1=$(".custom-linkman1").val();
			var $c_phone1=$(".custom-phone1").val();
			var $c_mark1=$(".custom-mark1").val();
			$(".click").children(".supplier1-code").html($c_code1);
			$(".click").children(".supplier1-name").html($c_name1);
			$(".click").children(".supplier1-type").html($c_type1);
			$(".click").children(".web-url").html($c_url1);
			$(".click").children(".linkman").html($c_linkman1);
			$(".click").children(".supplier1-phone").html($c_phone1);
			$(".click").children(".supplier1-mark").html($c_mark1);
			window.location="supplier.php";
			$(".click").removeClass("click");
		}
	});
	 // 添加商品
	 $(".custom-add").click(function(){
	 	var $tr=$('<tr class="cu-tr"></tr>');
	 	var $td1=$('<td class="cu-td"></td>');
	 	var $td2=$('<td><input class="allCheck" type="checkbox" value=""></td>');         
	 	var $td3=$('<td><input class="noborder form-control input-sm" type="text" value=""></td>');         
	 	var $td4=$('<td><div style="float:left;with:16px;height:30px;line-height:30px;">￥</div><input type="text" class="noborder form-control input-sm" style="float: left;width:220px;"></td>'); 
	 	$tr.append($td1);   
	 	$tr.append($td2);   
	 	$tr.append($td3);   
	 	$tr.append($td4);   
	 	$(".custom-tbody2") .append($tr);
	 	if($(".cu-tr").length==1){
	 		$td1.html(1);
	 	}else{
	 		$td1.html(Number($tr.prev().children().eq(0).html())+1);			
	 	}
	 });
	 //全选商品;
	 $(".checkall").on("click",function(){
	 	if(this.checked){
	 		$("input[type='checkbox']").each(function(){
	 			this.checked=true;
	 		})
	 	}else{
	 		$("input[type='checkbox']").each(function(){
	 			this.checked=false;
	 		})
	 	}
	 });
	//删除商品;
	$(".custom-del").click(function(){
		$("input[type='checkbox']").each(function(){
			if(this.checked==true){
				$(this).parent().parent(".cu-tr").remove();
				$(".checkall").prop("checked",false);
			}
		});
		$(".cu-td").each(function(i){
			$(this).html(i+1);
		});
	});
	$(".custom-add1").click(function(){
		var $tr1=$('<tr class="cu-tr1"></tr>');
		var $td11=$('<td class="cu-td1"></td>');
		var $td21=$('<td><input class="allCheck" type="checkbox" value=""></td>');         
		var $td31=$('<td><input class="noborder form-control input-sm" type="text" value=""></td>');         
		var $td41=$('<td><div style="float:left;with:16px;height:30px;line-height:30px;">￥</div><input type="text" class="noborder form-control input-sm" style="float: left;width:220px;"></td>'); 
		$tr1.append($td11);   
		$tr1.append($td21);   
		$tr1.append($td31);   
		$tr1.append($td41);   
		$(".custom-tbody3") .append($tr1);
		if($(".cu-tr1").length==1){
			$td11.html(1);
		}else{
			$td11.html(Number($tr1.prev().children().eq(0).html())+1);			
		}
	});
	 //全选商品;
	 $(".checkall1").on("click",function(){
	 	if(this.checked){
	 		$("input[type='checkbox']").each(function(){
	 			this.checked=true;
	 		})
	 	}else{
	 		$("input[type='checkbox']").each(function(){
	 			this.checked=false;
	 		})
	 	}
	 });
	//删除商品;
	$(".custom-del1").click(function(){
		$("input[type='checkbox']").each(function(){
			if(this.checked==true){
				$(this).parent().parent(".cu-tr1").remove();
				$(".checkall1").prop("checked",false);
			}
		});
		$(".cu-td1").each(function(i){
			$(this).html(i+1);
		});
	});

})
