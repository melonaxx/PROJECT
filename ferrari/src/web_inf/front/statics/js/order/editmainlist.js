$(function(){
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
	khid=$("#khid").find("option:selected").val();
	findoneinfo(khid);
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


	$("#shishou").change(function(){
		var shishou = $(this).val();
		var yingshou = $("#yingshou").val();
		var qiankuan = $("#qiankuan").val();
		if(isNaN(shishou)){
			shishou = 0;
		}
		if(Number(shishou)<0){
			shishou = 0;
		}
		if(Number(shishou)>Number(yingshou)){
			shishou = yingshou;
		}
		$("#shishou").val((shishou-0).toFixed(2));
		$("#qiankuan").val((yingshou-shishou).toFixed(2));
	})

	$("#addshouhou").click(function(){
		var connect = $("#shouhou").val();
		var orderid = $("#orderid").val();
		if(connect){
			$.ajax({
				type: "POST",
				url: "/order/doaddshouhou.php",
				data:{"connect":connect,"orderid":orderid},
				success: function(msgs){
					if(msgs == 1){
						alert("添加成功!");
						$("#shouhou").val("");
						show();
					}else{
						alert("添加失败");
					}
				}
			})
		}else{
			alert("请输入内容");
		}
	})
	show();
	function show(){
		var orderid = $("#orderid").val();
		$.ajax({
			type: "POST",
			url: "/order/showshouhou.php",
			data:{"orderid":orderid},
			dataType:"json",
			success: function(msgs){
				$("#tb").empty();
				$.each(msgs,function(idx,item){ 
					var time = item.createtime;
					var content = item.contents;
					var name = item.username;
					var id = item.id;
					$("#tb").append("<tr><td>"+time+"</td><td>"+content+"</td><td>"+name+"</td><td><span class='del-shouhou' uid="+id+">删除</span></td></tr>");
					
				})
				del();
			}
		})
		
	}
	function del(){
		$(".del-shouhou").click(function(){
			var ssid = $(this).attr("uid");
			$.ajax({
				type:"POST",
				url:"/order/delshouhou.php",
				data:{"id":ssid},
				success:function(msg){
					if(msg == 1){
						alert("删除成功!");
						show();
					}else{
						alert("删除失败");
					}
				}
			})
		})
	}
	
})
