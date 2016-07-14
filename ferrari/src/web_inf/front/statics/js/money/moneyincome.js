$(function(){
	/*时间显示*/
	var time=new Date();
	var year=time.getFullYear();
	var month=time.getMonth()+1;
	var day=time.getDate();
	var hour=time.getHours();
	var min=time.getMinutes();
	var sec=time.getSeconds();
	if(month<10){
		month="0"+month;
	}
	if(day<10){
		day="0"+day;
	}
	if(hour<10){
		hour="0"+hour;
	}
	if(min<10){
		min="0"+min;
	}
	if(sec<10){
		sec="0"+sec;
	}
	$(".datetimepicker").val(year+"-"+month+"-"+day+" "+hour+":"+min+":"+sec);
	var flag1=true;
	var flag2=true;
	$(".btn-add").on("click",function(){
		var $onetr=$("<tr class='onetr'></tr>");
		var $td1=$('<td class="onetd"></td>');
		var $td2=$('<td><input class="check" type="checkbox" value=""></td>');
		var $td3=$('<td><input type="text" class="form-control datetimepicker"></td>');
		var $td4=$('<td><select class="form-control"><option></option></select></td>');
		var $td5=$('<td><select class="form-control"><option></option></select></td>');
		var $td6=$('<td><select class="form-control"><option value="">收入</option><option value="">支出</option></select></td>');
		var $td7=$('<td><input type="text" class="form-control"></td>');
		var $td8=$('<td><input type="text" class="form-control"></td>');
		$onetr.append($td1);
		$onetr.append($td2);
		$onetr.append($td3);
		$onetr.append($td4);
		$onetr.append($td5);
		$onetr.append($td6);
		$onetr.append($td7);
		$onetr.append($td8);
		$(".tbody").append($onetr);
		if($(".onetr").length==1){
			$td1.html(1);
		}else{
			$td1.html(Number($onetr.prev().children().eq(0).html())+1);			
		}
		$(".datetimepicker").val(year+"-"+month+"-"+day+" "+hour+":"+min+":"+sec);
	});
	/*-----------------全选按钮------------------*/
	$(".allcheck").on("click",function(){
		if(flag1){
			$(".check").prop("checked",true);
			flag1=false;
		}else{
			$(".check").prop("checked",false);
			flag1=true;
		}
	});
	/*-----------------table点击删除按钮------------*/
	$(".btn-del").on("click",function(){
		$(".onetr").find(".check").each(function(){
			if($(this).is(':checked')){
				$(this).parent().parent().remove();
				$(".onetd").each(function(i){
					$(this).html(i+1);
				});
			}
		});
		$(".allcheck").prop("checked",false);
		flag1=true;
	});
})