$(function(){
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

	$(".newbtn1").click(function(){
		$(".modal-moneyallocation").show();
		$(".businessdate").val(year+"-"+month+"-"+day);
		$(".money-je").blur(function(){
			if($(this).val()==""){
				$(this).css("border-color","red");
			}else{
				$(this).removeClass("red");
				$(this).css("border-color","");
			}
		});
		$(".money-zc").blur(function(){
			if($(this).val()==""){
				$(this).addClass("red");
			}else{
				$(this).css("border-color","");
			}
		});
		$(".money-zc").click(function(){
				$(this).css("border-color","");
		});

		$(".money-zr").blur(function(){
			if($(this).val()==""){
				$(this).css("border-color","red");
			}else{
				$(this).css("border-color","");
			}
		});
		$(".money-zr").click(function(){
				$(this).css("border-color","");
		});
	});
	$(".money-sure").click(function(){
		if($(".money-zc").val()==""||$(".money-zr").val()==""||$(".money-je").val()==""){
			$(".money-zc").css("border-color","red");
			$(".money-zr").css("border-color","red");
			$(".money-je").css("border-color","red");
		}else{
	        var $mtr=$('<tr class="money-tr"></tr>');
	        var $mtd1=$('<td class="money-td"></td>');
	        var $mtd2=$('<td><a class="money-detail">详细</a></td>');
	        var $mtd3=$('<td class="money-time">'+year+"-"+month+"-"+day+" "+hour+":"+min+":"+sec+'</td>');
	        var $mtd4=$('<td class="money-date">'+year+"-"+month+"-"+day+'</td>');
	        var $mtd5=$('<td class="money-zhuanchu">'+$(".money-zc").val()+'</td>');
	        var $mtd6=$('<td class="money-zhuanru">'+$(".money-zr").val()+'</td>');
	        var $mtd7=$('<td class="money-money">'+$(".money-je").val()+'</td>');
	        var $mtd8=$('<td class="money-remark">'+$(".money-mark").val()+'</td>');
	        var $mtd9=$('<td></td>');
	        $mtr.append($mtd1);
	        $mtr.append($mtd2);
	        $mtr.append($mtd3);
	        $mtr.append($mtd4);
	        $mtr.append($mtd5);
	        $mtr.append($mtd6);
	        $mtr.append($mtd7);
	        $mtr.append($mtd8);
	        $mtr.append($mtd9);
		    $(".money-tbody").prepend($mtr);
			$(".money-td").each(function(i){
			   	 $(this).html(i+1);
			});
			$(".modal-moneyallocation").hide();
			See();

		}
	});
	See();
	function See(){
		$(".money-detail").click(function(){
			$(".modal-moneyallocation1").show();
			$(".businessdate1").val($(this).parent().siblings(".money-date").html());
			$(".money-je1").val($(this).parent().siblings(".money-money").html());
			$(".money-zc1").val($(this).parent().siblings(".money-zhuanchu").html());
			$(".money-zr1").val($(this).parent().siblings(".money-zhuanru").html());
			$(".money-mark1").val($(this).parent().siblings(".money-remark").html());
		});
		
	}

})