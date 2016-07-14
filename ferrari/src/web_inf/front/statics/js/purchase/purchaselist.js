$(function(){
	$(".btn-add").on("click",function(){
		var $onetr1=$("<tr class='onetr1'></tr>");
		var $td1=$('<td class="onetd1"></td>');
		var $td2=$('<td><label class="checkbox-all"><input class="checkbox-choice" type="checkbox" value=""></label></td>');
		var $td3=$('<td><input type="text" class="form-control searchbox seachpro" placeholder="请搜索商品名称"/></td>');
		var $td4=$('<td><select class="form-control nameorsize productname" name="shang[]"></select></td>');
		var $td5=$('<td class="danwei"><span></span><input type="hidden" name="danweiid[]" class="dwid" value=""></td>');
		var $td6=$('<td class="danjia"><label class="labelname">￥</label><input type="text" class="form-control singleprice" name="danjia[]"/></td>');
		var $td7=$('<td><input type="text" class="form-control goodsnum shuliang" placeholder="必填" name="shuliang[]"/></td>');
		var $td8=$('<td >￥<input type="text" class="zongjia form-control" style="border:none;width:116px;" readonly="readonly" name="zongjia[]"/></td>');
		var $td9=$('<td ><input type="text" class="form-control shuilv" name="shuilv[]"/></td>');
		var $td10=$('<td >￥<input  type="text" class="shuie form-control" style="border:none;width:76px;" readonly="readonly" name="shuie[]"></td>');
		var $td11=$('<td >￥<input  type="text" class="shuijia form-control" style="border:none;width:76px;" readonly="readonly" name="shuijia[]"></td>');
		$onetr1.append($td1);
		$onetr1.append($td2);
		$onetr1.append($td3);
		$onetr1.append($td4);
		$onetr1.append($td5);
		$onetr1.append($td6);
		$onetr1.append($td7);
		$onetr1.append($td8);
		$onetr1.append($td9);
		$onetr1.append($td10);
		$onetr1.append($td11);
		$("#tbody1").append($onetr1);
		if($(".onetr1").length==1){
			$td1.html(1);
		}else{
			$td1.html(Number($onetr1.prev().children().eq(0).html())+1);			
		}
		seachpur();
		namechange();
		shuchange();
		taxchange();
	});
	seachpur();
	function seachpur(){
		$(".seachpro").keyup(function(){
		var comment=$.trim($(this).val());
		var $this=$(this);
		if(comment){
			$.ajax({
				type: "POST",
				url: "/purchase/findproduct.php",
				data:{comment:comment},
				success: function(msg){
					$this.parent().next().children("select").empty();
					var json=eval(msg);
					$.each(json,function(idx,item){ 
						var id = item.productid;
						var name = item.name;
						var formatid1 = item.guigename.formatid1;
						var formatid2 = item.guigename.formatid2;
						var formatid3 = item.guigename.formatid3;
						var formatid4 = item.guigename.formatid4;
						var formatid5 = item.guigename.formatid5;
						var valueid1  = item.zhiname.valueid1;
						var valueid2  = item.zhiname.valueid2;
						var valueid3  = item.zhiname.valueid3;
						var valueid4  = item.zhiname.valueid4;
						var valueid5  = item.zhiname.valueid5;
						$this.parent().next().children("select").append("<option value="+id+">"+name+"-"+valueid1+"-"+valueid2+"-"+valueid3+"-"+valueid4+"-"+valueid5+"</option>");		
						
						aaa = $this.parent().next().children("select");
						ppp=aaa.find("option:selected").val();
						
					})
					$.ajax({
						type: "POST",
						url: "/purchase/finddanwei.php",
						data:{proid:ppp},
						success: function(msgs){
							var data = eval('('+msgs+')');
							$this.parent().siblings(".danwei").children("span").html(data['dwname']);
							$this.parent().siblings(".danwei").children("input").val(data['unitid']);
							$this.parent().siblings(".danjia").children("input").val(data['pricepurchase']);
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
				url: "/purchase/finddanwei.php",
				data:{proid:ppid},
				success: function(msgs){
					var data = eval('('+msgs+')');
					sele.parent().siblings(".danwei").children("span").html(data['dwname']);
					sele.parent().siblings(".danwei").children("input").val(data['unitid']);
					sele.parent().siblings(".danjia").children("input").val(data['pricepurchase']);
				}
			});

		});	
	}
	shuchange();
	function shuchange(){
		$(".shuliang").change(function(){
		var price = $(this).parent().prev().children("input").val();
		var num = $(this).val();
		$(this).parent().next().children("input").val((price*num).toFixed(2));
	});
	}

	//全选商品;
	$(".allCheck").bind("click",function(){
		if(this.checked){
			$("input[type='checkbox']").each(function(){
				this.checked=true;
			});
		}else{
			$("input[type='checkbox']").each(function(){
				this.checked=false;
			});
		}
	});
	//删除商品;
	$(".btn-del").bind("click",function(){
		$("input[type='checkbox']").each(function(){
			if(this.checked==true){
				$(this).parents(".onetr1").remove();
				$(".allCheck").attr("checked",false);
				
			}
		});
		$(".onetd1").each(function(i){
			$(this).html(i+1);
		});
	});
	//搜索供应商
	$(".seach").keyup(function(){
		var comment=$.trim($(this).val());
		if(comment){
			$.ajax({
				type: "POST",
				url: "/purchase/findsupplier.php",
				data:{comment:comment},
				success: function(msg){
					$("#gys").empty();
					var json=eval(msg);
					$.each(json,function(idx,item){ 
						var id=item.id;
						var name=item.name;
						var level=item.level;
						if(level=='Primary'){
							var status='主选供货商';
						}else if(level=='Alternative'){
							var status='备选供货商';
						}else if(level=='Eliminate'){
							var status='淘汰供货商';
						}
						$("#gys").append("<option value="+id+">"+name+"　("+status+")"+"</option>");		
					});
				}
			});
		}
	});
	taxchange();
	function taxchange(){
		$(".shuilv").change(function(){
			var shuilv =$(this).val();
			var zongjia = $(this).parent().prev().children().val();
			console.log(zongjia);
			var shuijia = (zongjia/(1+(shuilv/100))).toFixed(2);
			// $(".shuijia").val(shuijia);
			$(this).parent().next().next().children().val(shuijia);
			$(this).parent().next().children().val((zongjia-shuijia).toFixed(2));
			// $(".shuie").val();
		})
	}
	
})