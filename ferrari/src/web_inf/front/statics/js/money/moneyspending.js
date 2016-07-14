$(function(){
	$(".spending-add").click(function(){
		$(".modal-spending").show();
	});
	//添加科目
	$(".spending-sub").click(function(){
		if($(".spending-kmmc").val()!=""){
			var $option=$('<option>'+$(".spending-kmmc").val()+'</option>');
			$option.insertAfter(".none");
			var $mitr=$('<tr class="spending-tr"></tr>');
			var $mitd1=$('<td class="spending-td"></td>');
			var $mitd2=$('<td><span class="moneyincome-change spending-change">修改</span><span class="moneyincome-del spending-del">删除</span></td>');
			var $mitd3=$('<td class="spending-subname">'+$(".spending-kmmc").val()+'</td>');
			$mitr.append($mitd1);
			$mitr.append($mitd2);
			$mitr.append($mitd3);
			$(".spending-tbody").prepend($mitr);
			$(".spending-td").each(function(i){
			   	 $(this).html(i+1);
			});
			$(".modal-spending").hide();
			ChangeSubject();
			DelSubject();
	    }
	});
	//修改科目
	ChangeSubject();
	function ChangeSubject(){
		// var $this;
		$(".spending-change").click(function(){
			$(".modal-spending1").show();
			$(this).parent().parent().addClass("click").siblings().removeClass("click");
			$(".spending-kmmc1").val($(".click").children(".spending-subname").html());
			/*var $size=$(this).parent().parent(".mi-tr").prevAll().size();
			var $sib=$(this).parent().parent(".mi-tr").prevAll();
	       	var $op1;
	       	$sib.each(function(i){
	        	$op1=$('<option class="shang">'+$(this).children(".mi-subname").html()+'</option>');
	            $op1.insertAfter(".none1");
	       	});*/
		});
	   	$(".spending-sub1").click(function(){
	   		$(".modal-spending1").hide();
	   		$(".click").children(".spending-subname").html($(".spending-kmmc1").val());
	   	    $(".click").removeClass("click");
	   	});
	   	$(".close-btn").click(function(){
	   		$(".click").removeClass("click");
	   	})
	}
   	//删除科目；
   	DelSubject();
   	function DelSubject(){
	   	$(".spending-del").click(function(){
	   		var $thedel=$(this);
	   		$(".modal-spending2").show();
		   	$(".shang").remove();
		   	$(".spending-sure").click(function(){
		   		$thedel.parent().parent().remove();
		   		$(".modal-spending2").hide();
		   		$(".spending-td").each(function(i){
				   	 $(this).html(i+1);
				});
		   	});
	   	});
   	}
})