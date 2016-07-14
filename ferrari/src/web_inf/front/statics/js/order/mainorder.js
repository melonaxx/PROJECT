$(function(){
	/*时间显示*/
	$(".khinfo").attr("disabled",false);
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
	/*--------------------table添加一行-------------------*/
	$(".btn-add").on("click",function(){
		var $onetr=$("<tr class='onetr'></tr>");
		var $td1=$('<td class="onetd1">1</td>');
		var $td2=$('<td><label><input class="checkbox-choice" type="checkbox" value=""></label></td>');
		var $td3=$('<td class="warestatus-tbody-img"></td>');
		var $td4=$('<td><input type="text" class="form-control searchbox"placeholder="请搜索商品名称"></td>');
		var $td5=$('<td><select class="form-control productname" name="proid[]"><option></option></select></td>');
		var $td6=$('<td></td>');
		var $td7=$('<td><label class="labelname" style="float:left;display:block;width:20px;height:30px;line-height:30px;">￥</label><input type="text" class="form-control singleprice" name="singleprice[]"></td>');
		var $td8=$('<td><input type="text" class="form-control goodsnum"placeholder="必填" name="goodsnum[]"></td>');
		var $td9=$('<td><label class="labelname" style="float:left;display:block;width:20px;height:30px;line-height:30px;">￥</label><input type="text" class="form-control singleprice youhui" name="youhui[]"></td>');
		var $td10=$('<td><label class="labelname" style="float:left;display:block;width:20px;height:30px;line-height:30px;">￥</label><input type="text" class="form-control singleprice pay" name="pay[]" readonly="readonly"></td>');
		var $td11=$('<td><input type="text" class="form-control" name="procomment[]"></td>');
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
		seachpur();
		namechange();
		goodsnum();
		youhui();
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

	/*-------------------------商品搜索-------------------*/
	seachpur();
	function seachpur(){
		$(".searchbox").keyup(function(){
		var comment=$.trim($(this).val());
		var $this=$(this);
		if(comment){
			$.ajax({
				type: "POST",
				url: "/report/findproduct.php",
				data:{comment:comment},
				success: function(msg){
					$this.parent().next().children("select").empty();
					var json=eval(msg);
					$.each(json,function(idx,item){ 
						var id = item.productid;
						var name = item.name;
						var guige = item.guige;
						$this.parent().next().children("select").append("<option value="+id+">"+name+"（"+guige+"）</option>");		
						aaa = $this.parent().next().children("select");
						ppp=aaa.find("option:selected").val();
						
					})
					$.ajax({
						type: "POST",
						url: "/order/findproinfo.php",
						data:{proid:ppp},
						dataType:"json",
						success: function(msgs){
							$this.parent().prev().empty();
							$this.parent().next().next().html(msgs.dwname);
							$this.parent().next().next().next().children("input").val(msgs.price);
							var src = "http://img.1sheng.com/"+msgs.image;
							$this.parent().prev().append("<img src='"+src+"' class='img1'><img src='"+src+"' class='img2'>");
							Big();
						}
					})
				}
			})
		}
	});
	}
	namechange();
	function namechange(){
		$(".productname").change(function(){
			sele = $(this);
			var ppid = $(this).val();
			$.ajax({
				type: "POST",
				url: "/order/findproinfo.php",
				data:{proid:ppid},
				dataType:"json",
				success: function(msgs){
					sele.parent().prev().prev().empty();
					sele.parent().next().html(msgs.dwname);
					sele.parent().next().next().children("input").val(msgs.price);
					var src = "http://img.1sheng.com/"+msgs.image;
					sele.parent().prev().prev().append("<img src='"+src+"' class='img1'><img src='"+src+"' class='img2'>");
					Big();
				}
			});

		});	
	}
	goodsnum();
	function goodsnum(){
		$(".goodsnum").keyup(function(){
			var singleprice = $(this).parent().prev().children("input").val();
			var youhui = $(this).parent().next().children("input").val();
			var num = $(this).val();
			var pay = singleprice*num-youhui;
			$(this).parent().next().next().children("input").val(pay);
			pays();
		})
	}

	youhui();
	function youhui(){
		$(".youhui").keyup(function(){
			var singleprice = $(this).parent().prev().prev().children("input").val();
			var youhui = $(this).val();
			var num = $(this).parent().prev().children("input").val();
			var pay = singleprice*num-youhui;
			$(this).parent().next().children("input").val(pay);
			youhuis();
			pays();
		})
	

	}
	youhuis();
	function youhuis(){
	var youhuis = 0;
	$(".youhui").each(function(){
		var aaa = $(this).val();
		youhuis += Number(aaa);
	})
	$("#youhuis").val(youhuis);		
	}

	pays();
	function pays(){
	var pays = 0;
	$(".pay").each(function(){
		var bbb = $(this).val();
		pays += Number(bbb);
	})
	$("#pays").val(pays);		
	}

	/*客户信息部分--------------------------------------------------------------*/
	$("#seachkh").keyup(function(){
		var khname = $(this).val();
		if(khname){
			$.ajax({
				type: "POST",
				url: "/order/findkehu.php",
				data:{name:khname},
				dataType:"json",
				success: function(msg){
					$("#seachkh").parent().next().children("select").empty();
					khid = "";
					$.each(msg,function(idx,item){ 
						var id = item.id;
						var name = item.name;
						$("#seachkh").parent().next().children("select").append("<option value="+id+">"+name+"</option>");		
						khid=$("#khid").find("option:selected").val();
					})
					findoneinfo(khid);
				}
			});
		}
	})
	$("#khid").change(function(){
		var gusid = $("#khid").find("option:selected").val();
		findoneinfo(gusid);
	})
	function findoneinfo(khid){
		if(khid){
			$(".khinfo").attr("disabled",true);
			$.ajax({
				type: "POST",
				url: "/order/findkehuinfo.php",
				data:{khid:khid},
				dataType:"json",
				success: function(msgs){
					$("#nick").val(msgs.nick);
					$("#mobile").val(msgs.mobile);
					$("#telphone").val(msgs.telphone);
					$("#companyname").val(msgs.companyname);
					$("#postcode").val(msgs.postcode);
					$("#address").val(msgs.address);
					stateid = msgs.stateid;
					cityid = msgs.cityid;
					districtid = msgs.districtid;
					searchpcc("pro","city","county",stateid,cityid,districtid);
				}
			})
			
		}else{
			$(".khinfo").attr("disabled",false);
			$("#nick").val("");
			$("#mobile").val("");
			$("#telphone").val("");
			$("#companyname").val("");
			$("#postcode").val("");
			$("#address").val("");
			stateid = "";
			cityid = "";
			districtid = "";
			searchpcc("pro","city","county",stateid,cityid,districtid);
		}
	}


	var stateid = $(".pro").val();
	var cityid = $(".city").val();
	var districtid = $(".county").val();
	if(!cityid){
		cityid = "";
	}
	if(!stateid){
		stateid = "";
	}
	if(!districtid){
		districtid = "";
	}
	searchpcc("pro","city","county",stateid,cityid,districtid);
})
