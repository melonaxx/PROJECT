$(function(){
	// 添加子商品
     $(".childgood-add").click(function(){
		var $tr=$('<tr class="childgood-tr"></tr>');
		var $td1=$('<td class="childgood-td"></td>');
	    var $td2=$('<td><label><input type="checkbox"></label></td>');         
		var $td3=$('<td><input placeholder="搜索" class="form-control input-sm seach" type="text"></td>');         
		var $td4=$('<td><select name="product[]" style="width:280px" class="form-control input-sm product"><option selected="selected">组合子商品A</option></select></td>'); 
		var $td5=$('<td>100.00</td>');         
		var $td6=$('<td><input class="input-sm form-control" value="1" name="total[]" type="text"></td>'); 
		var $td7=$('<td></td>'); 
		$tr.append($td1);   
		$tr.append($td2);   
		$tr.append($td3);   
		$tr.append($td4);   
		$tr.append($td5);   
		$tr.append($td6);   
		$tr.append($td7);   
		$(".childgood-tbody") .append($tr);
		if($(".childgood-tr").length==1){
			$td1.html(1);
		}else{
			$td1.html(Number($tr.prev().children().eq(0).html())+1);			
		}
	});
     //全选商品;
	$(".allcheck").bind("click",function(){
		if(this.checked){
			$("input[type='checkbox']").each(function(){
				this.checked=true;
			});
		}else{
			$("input[type='checkbox']").each(function(){
				this.checked=false;
			});
		}
	});
	//删除商品;
	$(".childgood-del").click(function(){
		$("input[type='checkbox']").each(function(){
			if(this.checked==true){
				$(this).parent().parent().parent(".childgood-tr").remove();
				$(".allcheck").attr("checked",false);
			}
		});
		$(".childgood-td").each(function(i){
		   	 $(this).html(i+1);
		 });
	});
	$(".childgood-sub").click(function(){
		$(".modal-childgood").show();
	});
})