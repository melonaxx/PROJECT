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
	$(".datetimepicker").val(year+"-"+month+"-"+day);
	var flag1=true;
	var flag2=true;
	/*--------------------table添加一行-------------------*/
	$(".btn-add").on("click",function(){
		var $onetr=$("<tr class='onetr'></tr>");
		var $td1=$('<td class="onetd1">1</td>');
		var $td2=$('<td><label><input class="checkbox-choice" type="checkbox" value=""readonly="readonly"></label></td>');
		var $td3=$('<td class="warestatus-tbody-img"><img src="" class="img1"><img src="" class="img2"></td>');
		var $td4=$('<td><input type="text" class="form-control searchbox"placeholder="请搜索商品名称"readonly="readonly"></td>');
		var $td5=$('<td><select class="form-control searchname"readonly="readonly"><option></option></select></td>');
		var $td6=$('<td></td>');
		var $td7=$('<td><label class="labelname" style="float:left;display:block;width:20px;height:30px;line-height:30px;">￥</label><input type="text" class="form-control singleprice"readonly="readonly"></td>');
		var $td8=$('<td><input type="text" class="form-control goodsnum"placeholder="必填"readonly="readonly"></td>');
		var $td9=$('<td><label class="labelname" style="float:left;display:block;width:20px;height:30px;line-height:30px;">￥</label><input type="text" class="form-control singleprice"readonly="readonly"></td>');
		var $td10=$('<td><label class="labelname" style="float:left;display:block;width:20px;height:30px;line-height:30px;">￥</label><input type="text" class="form-control singleprice"readonly="readonly"></td>');
		var $td11=$('<td><input type="text" class="form-control"readonly="readonly"></td>');
		$onetr.append($td1);
		$onetr.append($td2);
		$onetr.append($td3);
		$onetr.append($td4);
		$onetr.append($td5);
		$onetr.append($td6);
		$onetr.append($td7);
		$onetr.append($td8);
		$onetr.append($td9);
		$onetr.append($td10);
		$onetr.append($td11);
		$("#tbody1").append($onetr);
		if($(".onetr").length==1){
			$td1.html(1);
		}else{
			$td1.html(Number($onetr.prev().children().eq(0).html())+1);			
		}
		Big();
	});
	/*图片放大效果*/
	Big();
	/*-----------------全选按钮------------------*/
	$(".allCheck").on("click",function(){
		if(flag1){
			$(".checkbox-choice").prop("checked",true);
			flag1=false;
		}else{
			$(".checkbox-choice").prop("checked",false);
			flag1=true;
		}
	});
	/*-----------------table点击删除按钮------------*/
	$(".btn-del").on("click",function(){
		$(".onetr").find(".checkbox-choice").each(function(){
			if($(this).is(':checked')){
				$(this).parent().parent().parent().remove();
				$(".onetd1").each(function(i){
					$(this).html(i+1);
				});
			}
		});
		$(".allCheck").prop("checked",false);
		flag1=true;
	});	
	/*------------------点击条码添加显示条形码输入框----------------*/
	$(".btn-add-bar").on("click",function(){
		if(flag2){
			$("#bar-code").show();
			flag2=false;
		}else{
			$("#bar-code").hide();
			flag2=true;
		}
	});
	/*添加售后留言*/
	$(".add-shou").click(function(){
		var $tr=$('<tr></tr>');
		var $td1=$('<td></td>');
		var $td2=$('<td></td>');
		var $td3=$('<td></td>');
		var $td4=$('<td><span class="del-shouhou">删除</span></td>');
		$tr.append($td1);
		$tr.append($td2);
		$tr.append($td3);
		$tr.append($td4);
		$(".shou-tbody").append($tr);
		$(".del-shouhou").click(function(){
			$(this).closest("tr").remove();
		})
	})
})
