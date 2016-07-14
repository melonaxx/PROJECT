$(function(){
	/*-----------------添加客户信息---------------------------*/
	$('.custom-submit').click(function(){
		var flag = true;
		//客户名称不能为空
		if (!$('.customername').val()) {
			$('.customername').focus();
			$('.customername').css('border-color','red');
			return flag = false;
		}
		//客户电话不能为空
		if (!$('.customerphone').val()) {
			$('.customerphone').focus();
			$('.customerphone').css('border-color','red');
			return flag = false;
		}
		//省市县不能为空
		if ($('.pro option:selected').val() == -1 || $('.city option:selected').val() == -1 || $('.county option:selected').val() == -1) {
			alert('请选择省、市、县！');
			return flag = false;
		}

		var customerobj = {
			customername  : $('.customername').val(),					//客户名称
			customernick  : $('.customernick').val(),					//客户昵称
			customerphone : $('.customerphone').val(),					//手机号码
			clienttel     : $('.clienttel').val(),						//固定电话
			payment       : $('.payment').val(),						//结算金额
			customercom   : $('.customercom').val(),					//公司名称
			cusemail      : $('.cusemail').val(),						//邮箱
			cuspostcode   : $('.cuspostcode').val(),					//邮编
			cusaddress    : $('.cusaddress').val(),						//详细地址
			cusQQ    	  : $('.cusQQ').val(),							//QQ
			cuscomment    : $('.cuscomment').val(),						//备注
			customertype  : $('.customertype option:selected').val(),	//客户类型
			province      : $('.pro option:selected').val(),			//省份
			city          : $('.city option:selected').val(),			//市区
			town          : $('.county option:selected').val()			//区县
		}

		if (flag) {
			var addcussuccess = function(msg)
			{
				alert('添加客户信息成功！');
				location.reload();
			}
			var addcusfail = function()
			{
				alert('添加客户信息失败！');
				location.reload();
			}
			util.ajax_post('/crm/addcustomertotable.php',{customerobj:customerobj},addcussuccess,addcusfail);
		}
	});
	searchpcc();

	//重置
	$('.cusreset').click(function(){
		location.reload();
	});
	//地区
	// addressInit("select1","select2","select3");
	//添加客户;
	// $(".custom-submit").click(function(){
	// 	var $this=$(this);
	// 	if($(".custom-kehu").val()!=""){
	// 		var $kehu=$(".custom-kehu").val();
	// 		var $kehu1=$(".custom-kehu1").val();
	// 		var $phone=$(".custom-phone").val();
	// 		var $area1=$(".province").val();
	// 		var $area2=$(".city").val();
	// 		var $area3=$(".town").val();
	// 		var $mark=$(".custom-mark").val();
	// 		var $ctr=$('<tr class="custom-tr"></tr>');
	// 		var $ctd1=$('<td class="custom-td"></td>');
	// 		var $ctd2=$('<td><span class="customer-see">查看</span><span class="customer-del">删除</span></td>');
	// 		var $ctd3=$('<td class="custom-name">'+$kehu+'</td>');
	// 		var $ctd4=$('<td class="custom-name1">'+$kehu1+'</td>');
	// 		var $ctd5=$('<td class="custom-province">'+$area1+'</td>');
	// 		var $ctd6=$('<td class="custom-city">'+$area2+'</td>');
	// 		var $ctd7=$('<td class="custom-town">'+$area3+'</td>');
	// 		var $ctd8=$('<td class="custom-phonenum">'+$phone+'</td>');
	// 		var $ctd9=$('<td>123</td>');
	// 		var $ctd10=$('<td>123</td>');
	// 		var $ctd11=$('<td class="custom-remark">'+$mark+'</td>');
	// 		$ctr.append($ctd1);
	// 		$ctr.append($ctd2);
	// 		$ctr.append($ctd3);
	// 		$ctr.append($ctd4);
	// 		$ctr.append($ctd5);
	// 		$ctr.append($ctd6);
	// 		$ctr.append($ctd7);
	// 		$ctr.append($ctd8);
	// 		$ctr.append($ctd9);
	// 		$ctr.append($ctd10);
	// 		$ctr.append($ctd11);
	// 		alert($(".custom-tbody").text());
	// 		window.location="customerlist.php";
	// 		$(".custom-tbody").prepend($ctr);
	// 		$(".custom-td").each(function(i){
	// 		   	 $(this).html(i+1);
	// 		});
	// 		DelKeHu();
	// 		SeeKeHu();
	// 	}
	// });
})
function SeeKeHu(){
	$(".customer-see").click(function(){
		var $this=$(this);
	    $this.parent().parent().addClass("click").siblings().removeClass("click");
		$(".active2").show().addClass("active").siblings().removeClass("active");
		$(".active1").hide();
		$(".status3").show().siblings(".status").hide();
		var $name=$(".click").children(".custom-name").html();
		var $name1=$(".click").children(".custom-name1").html();
		var $prov=$(".click").children(".custom-province").html();
		var $city=$(".click").children(".custom-city").html();
		var $town=$(".click").children(".custom-town").html();
		var $phonenum=$(".click").children(".custom-phonenum").html();
		var $remark=$(".click").children(".custom-remark").html();
		$(".custom-custom").val($name);
		$(".custom-custom1").val($name1);
		$(".custom-phone1").val($phonenum);
		$(".province1 option[value='"+$prov+"']").attr("selected",true);
		$(".province1").change();
		$(".city1 option[value='"+$city+"']").attr("selected",true);
		$(".city1").change();
		$(".town1 option[value='"+$town+"']").attr("selected",true);
		$(".custom-mark1").val($remark);
	});
}