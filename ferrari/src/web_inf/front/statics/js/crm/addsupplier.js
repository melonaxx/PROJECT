$(function(){
	//地区
	// addressInit("custom-prov1","custom-city1","custom-town1");
	searchpcc();
	//添加客户;
	$(".custom-sub").click(function(){
		var $this=$(this);
		if($(".custom-name").val()!=""){
			var $c_code=$(".custom-code").val();
			var $c_name=$(".custom-name").val();
			var $c_type=$(".custom-type").val();
			var $c_url=$(".custom-url").val();
			var $c_linkman=$(".custom-linkman").val();
			var $c_phone=$(".custom-phone").val();
			var $c_mark=$(".custom-mark").val();
			var $ctr=$('<tr class="custom-tr"></tr>');
			var $ctd1=$('<td class="custom-td"></td>');
			var $ctd2=$('<td><span class="customer-see">查看</span><span class="customer-del">删除</span></td>');
			var $ctd3=$('<td class="supplier1-code">'+$c_code+'</td>');
			var $ctd4=$('<td class="supplier1-name">'+$c_name+'</td>');
			var $ctd5=$('<td class="supplier1-type">'+$c_type+'</td>');
			var $ctd6=$('<td class="web-url">'+$c_url+'</td>');
			var $ctd7=$('<td class="linkman">'+$c_linkman+'</td>');
			var $ctd8=$('<td class="supplier1-phone">'+$c_phone+'</td>');
			var $ctd9=$('<td class="supplier1-mark">'+$c_mark+'</td>');
			$ctr.append($ctd1);
			$ctr.append($ctd2);
			$ctr.append($ctd3);
			$ctr.append($ctd4);
			$ctr.append($ctd5);
			$ctr.append($ctd6);
			$ctr.append($ctd7);
			$ctr.append($ctd8);
			$ctr.append($ctd9);
			window.location="supplier.php";
			$(".custom-tbody1").prepend($ctr);
			$(".custom-td").each(function(i){
			   	 $(this).html(i+1);
			});
			DelKeHu();
			SeeCustom();
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
function SeeCustom(){
	$(".customer-see").click(function(){
		var $this=$(this);
	    $this.parent().parent().addClass("click").siblings().removeClass("click");
		$(".active2").show().addClass("active").siblings().removeClass("active");
		$(".active1").hide();
		$(".status3").show().siblings(".status").hide();
		var $code=$(".click").children(".supplier1-code").html();
		var $name=$(".click").children(".supplier1-name").html();
		var $type1=$(".click").children(".supplier1-type").html();
		var $url=$(".click").children(".web-url").html();
		var $linkman=$(".click").children(".linkman").html();
		var $phone=$(".click").children(".supplier1-phone").html();
		var $mark=$(".click").children(".supplier1-mark").html();
		$(".custom-code1").val($code);
		$(".custom-name1").val($name);
		$(".custom-url1").val($url);
		$(".custom-linkman1").val($linkman);
		$(".custom-phone1").val($phone);
		$(".custom-mark1").val($mark);
		$(".custom-type1 option[value='"+$type1+"']").prop("selected",true);
	});
}