$(function(){
	$("input").val("");
	$("textarea").val("");
	$("input").prop("checked",false);
	$("select").each(function(i){
		$("select").get(i).selectedIndex=0;
	})
	var flag1=true;
	var flag2=true;
	var flag3=true;
	$(".combin-sub").click(function(){
		$(".modal-tip").show();
	})
	/*----------------组合商品添加table行-----------*/
	$(".cgaddbtn").on("click",function(){
		var $onetr=$('<tr class="glonetr"></tr>');
		var $td1=$('<td class="gltd1">1</td>');
		var $td2=$('<td class="gltd2"><input class="allCheck checkboxChoice" type="checkbox" value=""></td>');
		var $td3=$('<td class="gltd3"><input type="text" class="form-control" placeholder="搜索"></td>');
		var $td4=$('<td><select class="form-control"></select></td>');
		var $td5=$('<td class="contain-imgtd"><div class="input-group"><div class="input-group-addon">￥</div><input type="text" class="form-control" id="exampleInputAmount"></div></td>');
		var $td6=$('<td><input type="text" class="form-control"></td>');
		var $td7=$('<td><select class="form-control"></select></td>');
		$onetr.append($td1);
		$onetr.append($td2);
		$onetr.append($td3);
		$onetr.append($td4);
		$onetr.append($td5);
		$onetr.append($td6);
		$onetr.append($td7);
		$("#cgtbody1").append($onetr);
		if($(".glonetr").length==1){
			$td1.html(1);
		}else{
			$(".gltd1").each(function(i){
				$(this).html(i+1);
			})		
		}
	})
	/*-----------------组合商品table全选按钮------------------*/
	$(".choice-all").on("click",function(){
		if(flag1){
			$(".checkboxChoice").prop("checked",true);
			flag1=false;
		}else{
			$(".checkboxChoice").prop("checked",false);
			flag1=true;
		}
	})
	/*-----------------组合商品table点击删除按钮------------*/
	$(".cgdelbtn").on("click",function(){
		$(".glonetr").find(".checkboxChoice").each(function(){
			if($(this).is(':checked')){
				$(this).parent().parent().remove();
				$(".gltd1").each(function(i){
					$(this).html(i+1);
				})
			}
		})
	})	
	/*---------------组合商品重置表单-----------------*/
	$(".cgresetbtn").on("click",function(){
		$("input").val("");
		$("textarea").val("");
		$("input").prop("checked",false);
		$("select").each(function(i){
			$("select").get(i).selectedIndex=0;
		})
	})
})