$(function(){
	var flag=true;
	$("input").val("");
	$("textarea").val("");
	$("input").prop("checked",false);
	$("select").each(function(i){
		$("select").get(i).selectedIndex=0;
	})	
	/*-----------------选择每页显示的页数(默认每页十条)----------------*/
	var $pseverypagel1=$(".pseverypagel1");	
	var $everypage=Number($pseverypagel1.find("option:selected").text());
	var $pstbody1=$(".pstbody1");
	for(var i=0;i<10;i++){
		var $ps1tr=$('<tr class="psheet-tr"></tr>');
		var $ps1td1=$('<td><input type="text" class="form-control"></td>');
		var $ps1td2=$('<td><input type="text" class="form-control"></td>');
		var $ps1td3=$('<td><input type="text" class="form-control"></td>');
		var $ps1td4=$('<td><input type="text" class="form-control"></td>');
		var $ps1td5=$('<td><input type="text" class="form-control"></td>');
		var $ps1td6=$('<td><input type="text" class="form-control"></td>');
		var $ps1td7=$('<td><input type="text" class="form-control"></td>');
		var $ps1td8=$('<td><input type="text" class="form-control"></td>');
		var $ps1td9=$('<td><input type="text" class="form-control"></td>');
		var $ps1td10=$('<td><input type="text" class="form-control"></td>');
		var $ps1td11=$('<td><input type="text" class="form-control"></td>');
		var $ps1td12=$('<td><input type="text" class="form-control"></td>');
		/*var $ps1td13=$('<td><a href="#pseditsheet" aria-controls="pseditsheet" role="tab" data-toggle="tab" class="btn btn-default psedit" href="#" role="button">编辑</a></td>');*/
		var $ps1td13=$('<td><button class="btn btn-default psedit" type="submit">编辑</button></td>');
		$ps1tr.append($ps1td1);
		$ps1tr.append($ps1td2);
		$ps1tr.append($ps1td3);
		$ps1tr.append($ps1td4);
		$ps1tr.append($ps1td5);
		$ps1tr.append($ps1td6);
		$ps1tr.append($ps1td7);
		$ps1tr.append($ps1td8);
		$ps1tr.append($ps1td9);
		$ps1tr.append($ps1td10);
		$ps1tr.append($ps1td11);
		$ps1tr.append($ps1td12);
		$ps1tr.append($ps1td13);
		$pstbody1.append($ps1tr);
	}
	/*-----------------选择每页显示的页数(点击下拉菜单选项选择每页显示条数)----------------*/
	$(".pseverypagel1 option").on("click",function(){
		$(this).parents($(".psheet-content1")).find($pstbody1).empty();
		/*$pstbody1.empty();*/
		$everypage=Number($(this).parent().find("option:selected").text());
		for(var i=0;i<$everypage;i++){
			var $ps1tr=$('<tr class="psheet-tr"></tr>');
			var $ps1td1=$('<td><input type="text" class="form-control"></td>');
			var $ps1td2=$('<td><input type="text" class="form-control"></td>');
			var $ps1td3=$('<td><input type="text" class="form-control"></td>');
			var $ps1td4=$('<td><input type="text" class="form-control"></td>');
			var $ps1td5=$('<td><input type="text" class="form-control"></td>');
			var $ps1td6=$('<td><input type="text" class="form-control"></td>');
			var $ps1td7=$('<td><input type="text" class="form-control"></td>');
			var $ps1td8=$('<td><input type="text" class="form-control"></td>');
			var $ps1td9=$('<td><input type="text" class="form-control"></td>');
			var $ps1td10=$('<td><input type="text" class="form-control"></td>');
			var $ps1td11=$('<td><input type="text" class="form-control"></td>');
			var $ps1td12=$('<td><input type="text" class="form-control"></td>');
			/*var $ps1td13=$('<td><a href="#pseditsheet" aria-controls="pseditsheet" role="tab" data-toggle="tab" class="btn btn-default psedit" href="#" role="button">编辑</a></td>');*/
			var $ps1td13=$('<td><button class="btn btn-default psedit" type="submit">编辑</button></td>');
			$ps1tr.append($ps1td1);
			$ps1tr.append($ps1td2);
			$ps1tr.append($ps1td3);
			$ps1tr.append($ps1td4);
			$ps1tr.append($ps1td5);
			$ps1tr.append($ps1td6);
			$ps1tr.append($ps1td7);
			$ps1tr.append($ps1td8);
			$ps1tr.append($ps1td9);
			$ps1tr.append($ps1td10);
			$ps1tr.append($ps1td11);
			$ps1tr.append($ps1td12);
			$ps1tr.append($ps1td13);
			$(this).parents($(".psheet-content1")).find($pstbody1).append($ps1tr);
		}
	})
	/*-----------------点击编辑>提交印刷单>成功-----------*/
	$(".psedit").on("click",function(){
		$(".psallcontent").hide();
		$(".ps-querysuccess").hide();
		$("#pseditsheet").show();
		$(".ps-com").on("click",function(){
			$("#pseditsheet").hide();
			$(".ps-opsuccess").show();
		});
	});
})
