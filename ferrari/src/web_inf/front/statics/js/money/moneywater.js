$(function(){
	$(".income-btn").click(function(){
		$(".modal-moneywater").show();
		$(".businessdate").val(year+"-"+month+"-"+day);
		$(".money-je1").blur(function(){
			if($(".money-je1").val()==""){
				$(".money-je1").css("border-color","red");
			}else{
				$(".money-je1").css("border-color"," ");
			}
		});
		$(".money-je1").focus(function(){
			$(".money-je1").css("border-color"," ");
		});
	});
	$(".outcome-btn").click(function(){
		$(".modal-moneywater1").show();
		$(".businessdate1").val(year+"-"+month+"-"+day);
		$(".money-je1").blur(function(){
			if($(".money-je1").val()==""){
				$(".money-je1").css("border-color","red");
			}else{
				$(".money-je1").css("border-color"," ");
			}
		});
		$(".money-je2").focus(function(){
			$(".money-je2").css("border-color"," ");
		});
	});


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
	$(".moneywater-sure").click(function(){
		if($(".money-je1").val()==""){
			$(".money-je1").css("border-color","red");
		}else{
	        var $mwtr=$('<tr class="moneywater-tr"></tr>');
	        var $mwtd1=$('<td class="moneywater-td"></td>');
	        var $mwtd2=$('<td><a class="moneywater-detail">详细</a></td>');
	        var $mwtd3=$('<td class="jz-date">'+year+"-"+month+"-"+day+" "+hour+":"+min+":"+sec+'</td>');
	        var $mwtd4=$('<td class="yw-date">'+year+"-"+month+"-"+day+'</td>');
	        var $mwtd5=$('<td class="bill-type">收入单据</td>');
	        var $mwtd6=$('<td class="sz-subject">'+$(".modal-srsubject").val()+'</td>');
	        var $mwtd7=$('<td class="jine">'+$(".money-je1").val()+'</td>');
	        var $mwtd8=$('<td class="mw-account">'+$(".modal-accountname").val()+'</td>');
	        $mwtr.append($mwtd1);
	        $mwtr.append($mwtd2);
	        $mwtr.append($mwtd3);
	        $mwtr.append($mwtd4);
	        $mwtr.append($mwtd5);
	        $mwtr.append($mwtd6);
	        $mwtr.append($mwtd7);
	        $mwtr.append($mwtd8);
		    $(".moneywater-tbody").prepend($mwtr);
			$(".moneywater-td").each(function(i){
			   	 $(this).html(i+1);
			});
			$(".modal-moneywater").hide();
			SeeMoney();
		}
	});
	$(".moneywater-sure1").click(function(){
		if($(".money-je2").val()==""){
			$(".money-je2").css("border-color","red");
		}else{
	        var $mwtr=$('<tr class="moneywater-tr"></tr>');
	        var $mwtd1=$('<td class="moneywater-td"></td>');
	        var $mwtd2=$('<td><a class="moneywater-detail">详细</a></td>');
	        var $mwtd3=$('<td class="jz-date">'+year+"-"+month+"-"+day+" "+hour+":"+min+":"+sec+'</td>');
	        var $mwtd4=$('<td class="yw-date">'+year+"-"+month+"-"+day+'</td>');
	        var $mwtd5=$('<td class="bill-type">支出单据</td>');
	        var $mwtd6=$('<td class="sz-subject">'+$(".modal-srsubject1").val()+'</td>');
	        var $mwtd7=$('<td class="jine">'+$(".money-je2").val()+'</td>');
	        var $mwtd8=$('<td class="mw-account">'+$(".modal-accountname1").val()+'</td>');
	        $mwtr.append($mwtd1);
	        $mwtr.append($mwtd2);
	        $mwtr.append($mwtd3);
	        $mwtr.append($mwtd4);
	        $mwtr.append($mwtd5);
	        $mwtr.append($mwtd6);
	        $mwtr.append($mwtd7);
	        $mwtr.append($mwtd8);
		    $(".moneywater-tbody").prepend($mwtr);
			$(".moneywater-td").each(function(i){
			   	 $(this).html(i+1);
			});
			$(".modal-moneywater1").hide();
			SeeMoney();
		}
	});
	//查看详细
	SeeMoney();
	function SeeMoney(){
		$(".moneywater-detail").on("click",function(){
			$(".modal-moneywater2").show();
			$(".detail-ywrq").val($(this).parent().siblings(".yw-date").html());
			$(".detail-szkm").val($(this).parent().siblings(".sz-subject").html());
			$(".detail-jszh").val($(this).parent().siblings(".mw-account").html());
			$(".detail-money").val($(this).parent().siblings(".jine").html());
		});
		
	}


})