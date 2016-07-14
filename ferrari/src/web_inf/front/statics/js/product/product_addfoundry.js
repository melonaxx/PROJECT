$(function(){
	//地区
	addressInit("custom-prov1","custom-city1","custom-town1");
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
			var $ctd3=$('<td class="foundry1-code">'+$c_code+'</td>');
			var $ctd4=$('<td class="foundry1-name">'+$c_name+'</td>');
			var $ctd5=$('<td class="foundry1-type">'+$c_type+'</td>');
			var $ctd6=$('<td class="web-url">'+$c_url+'</td>');
			var $ctd7=$('<td class="linkman">'+$c_linkman+'</td>');
			var $ctd8=$('<td class="foundry1-phone">'+$c_phone+'</td>');
			var $ctd9=$('<td class="foundry1-mark">'+$c_mark+'</td>');
			$ctr.append($ctd1);
			$ctr.append($ctd2);
			$ctr.append($ctd3);
			$ctr.append($ctd4);
			$ctr.append($ctd5);
			$ctr.append($ctd6);
			$ctr.append($ctd7);
			$ctr.append($ctd8);
			$ctr.append($ctd9);
			window.location="product_foundry.php";
			$(".custom-tbody1").prepend($ctr);
			$(".custom-td").each(function(i){
			   	 $(this).html(i+1);
			});
			DelKeHu();
			SeeCustom();
		}
	});
})
function SeeCustom(){
	$(".customer-see").click(function(){
		var $this=$(this);
	    $this.parent().parent().addClass("click").siblings().removeClass("click");
		$(".active2").show().addClass("active").siblings().removeClass("active");
		$(".active1").hide();
		$(".status3").show().siblings(".status").hide();
		var $code=$(".click").children(".foundry1-code").html();
		var $name=$(".click").children(".foundry1-name").html();
		var $type1=$(".click").children(".foundry1-type").html();
		var $url=$(".click").children(".web-url").html();
		var $linkman=$(".click").children(".linkman").html();
		var $phone=$(".click").children(".foundry1-phone").html();
		var $mark=$(".click").children(".foundry1-mark").html();
		$(".custom-code1").val($code);
		$(".custom-name1").val($name);
		$(".custom-url1").val($url);
		$(".custom-linkman1").val($linkman);
		$(".custom-phone1").val($phone);
		$(".custom-mark1").val($mark);
		$(".custom-type1 option[value='"+$type1+"']").prop("selected",true);
	});
}