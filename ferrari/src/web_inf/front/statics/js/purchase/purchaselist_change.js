$(function(){
	/*默认三条*/
	for(var i=0;i<3;i++){
		var $onetr1=$("<tr class='onetr1'></tr>");
		var $td1=$('<td class="onetd1">'+(i+1)+'</td>');
		var $td2=$('<td><label class="checkbox-all"><input class="checkbox-choice" type="checkbox" value=""></label></td>');
		var $td3=$('<td><input type="text" class="form-control searchbox seachpro" placeholder="请搜索商品名称"/></td>');
		var $td4=$('<td><select class="form-control nameorsize productname" name="shang"></select></td>');
		var $td5=$('<td class="danwei"><span></span></td>');
		var $td6=$('<td class="danjia"><label class="labelname">￥</label><input type="text" class="form-control singleprice" name="danjia"/></td>');
		var $td7=$('<td><input type="text" class="form-control goodsnum shuliang" placeholder="必填" name="shuliang"/></td>');
		var $td8=$('<td >￥<input type="text" class="zongjia form-control" style="border:none;width:118px;" readonly="readonly" name="zongjia"/></td>');
		var $td9=$('<td ><input type="text" class="form-control shuilv" name="shuilv"/></td>');
		var $td10=$('<td >￥<input  type="text" class="shuie form-control" style="border:none;width:78px;" readonly="readonly" name="shuie"></td>');
		var $td11=$('<td >￥<input  type="text" class="shuijia form-control" style="border:none;width:78px;" readonly="readonly" name="shuijia"></td>');
		$onetr1.append($td1);
		$onetr1.append($td2);
		$onetr1.append($td3);
		$onetr1.append($td4);
		$onetr1.append($td5);
		$onetr1.append($td6);
		$onetr1.append($td7);
		$onetr1.append($td8);
		$onetr1.append($td9);
		$onetr1.append($td10);
		$onetr1.append($td11);
		$("#tbody1").append($onetr1);
	}
	$(".btn-add").on("click",function(){
		var $onetr1=$("<tr class='onetr1'></tr>");
		var $td1=$('<td class="onetd1"></td>');
		var $td2=$('<td><label class="checkbox-all"><input class="checkbox-choice" type="checkbox" value=""></label></td>');
		var $td3=$('<td><input type="text" class="form-control searchbox seachpro" placeholder="请搜索商品名称"/></td>');
		var $td4=$('<td><select class="form-control nameorsize productname" name="shang"></select></td>');
		var $td5=$('<td class="danwei"><span></span></td>');
		var $td6=$('<td class="danjia"><label class="labelname">￥</label><input type="text" class="form-control singleprice" name="danjia"/></td>');
		var $td7=$('<td><input type="text" class="form-control goodsnum shuliang" placeholder="必填" name="shuliang"/></td>');
		var $td8=$('<td >￥<input type="text" class="zongjia form-control" style="border:none;width:118px;" readonly="readonly" name="zongjia"/></td>');
		var $td9=$('<td ><input type="text" class="form-control shuilv" name="shuilv"/></td>');
		var $td10=$('<td >￥<input  type="text" class="shuie form-control" style="border:none;width:78px;" readonly="readonly" name="shuie"></td>');
		var $td11=$('<td >￥<input  type="text" class="shuijia form-control" style="border:none;width:78px;" readonly="readonly" name="shuijia"></td>');
		$onetr1.append($td1);
		$onetr1.append($td2);
		$onetr1.append($td3);
		$onetr1.append($td4);
		$onetr1.append($td5);
		$onetr1.append($td6);
		$onetr1.append($td7);
		$onetr1.append($td8);
		$onetr1.append($td9);
		$onetr1.append($td10);
		$onetr1.append($td11);
		$("#tbody1").append($onetr1);
		if($(".onetr1").length==1){
			$td1.html(1);
		}else{
			$td1.html(Number($onetr1.prev().children().eq(0).html())+1);			
		}
	});
	//全选商品;
	$(".allCheck").bind("click",function(){
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
	$(".btn-del").bind("click",function(){
		$("input[type='checkbox']").each(function(){
			if(this.checked==true){
				$(this).parents(".onetr1").remove();
				$(".allCheck").attr("checked",false);
				
			}
	   });
	   $(".onetd1").each(function(i){
	   	 $(this).html(i+1);
	   });
	});

})