$(function(){
	//只读模式;
	$(".form-control").attr("readonly","readonly");
	$("select").attr("disabled","disabled");
	$(".modalImg1").show();
	$(".Upload-text").hide();
	$(".edit").click(function(){
		$(this).hide();
		$(".form-control").removeAttr("readonly");
		$("select").removeAttr("disabled");
		$(".btn-add1").show();
		$(".btn-add2").show();
		$(".btn-change").show();
		$(".waregoodsdetail-btn").show();
		$(".waredetail-operate").show();
		$(".waredetail-clear").show();
		$(".modalImg1").show();
		$(".Upload-text").show();
		$(".Upload-text1").hide();
		var flag=false;
		$(".Imgsrc").click(function(){
			flag=true;
		});
		//模态框出现；
		$(".Upload-text").each(function(i){
			$(".Upload-text").eq(i).click(function(){
				var $this=$(this);
				$(".modal-upload").show();
				$(".Imgsrc"+i).show().siblings(".Imgsrc").hide();
				$this.parent().addClass("hover").siblings("td").removeClass("hover");
				$(".btn-sure0").click(function(){
					if(flag){
						$(".modal-upload").hide();
						$(".hover").children(".modalImg").show();
						$(".hover").children(".modal-div").hide();
						$(".hover").children(".Upload-text").hide();
					}

				})
			});
		});

		//上传图片切换导航;
		$(".modal-img").on("click",function(){
			$(this).addClass("active").siblings().removeClass("active");
			$(".modal-form").eq($(this).index()).show().siblings(".modal-form").hide();
		});
		var $mymodal=$(".modal-manager");
		var $person=$(".person-name");
		$person.on("focus",function(){
			$mymodal.show();
		});
		/*上传图片*/
		$(".modalImg").hover(
			function(){
				$(this).siblings(".modal-div").show();
				$(this).siblings(".modal-div").hover(
					function(){
						$(this).show();
						//编辑图片;
						$(".modal-Edit").each(function(i){
							$(".modal-Edit").eq(i).on("click",function(){
								var $this=$(this);
								$(".modal-upload").show();
								$(".Imgsrc"+i).show().siblings(".Imgsrc").hide();
								$(".btn-sure0").on("click",function(){
									$(".modal-upload").hide();
									$this.parent().hide();
									$(".hover").children(".modalImg").show();
									$(".hover").children(".Upload-text").hide();
									$this.parent().parent().removeClass("hover");
								});
							});
						});
						//删除图片;
						$(".modal-Del").on("click",function(event){
							event.stopPropagation();
				            $(this).parent().parent().children(".modalImg").hide();
				            $(this).parent().parent().children(".Upload-text").show();
				            $(this).parent().hide();
		      				$(this).parent().parent().removeClass("hover");
						});
					},
					function(){
						$(this).hide();
					}
				);
			},
			function(){
				$(this).siblings(".modal-div").hide();
			}
		);
		var $mymodal=$(".modal-manager");
		var $person=$(".person-name");
		$person.on("focus",function(){
			$mymodal.show();
		});
		//选择产品经理;
		$(".btn-sure1").on("click",function(){
			$("input[type='radio']").each(function(){
				if(this.checked){
					$(".modal-manager").hide();
					$person.val($(this).parent().parent().parent().next().html());
				}
			});
		});
		//取消产品经理：
		$(".btn-cancle").bind("click",function(){
			$("input[type='radio']").each(function(){
				if(this.checked){
					this.checked=false;
				}
			});
		});
		//清除商品规格;
		$(".waredetail-clear").on("click",function(){
			$(".select1").val($(".opt1").val());
			$(".select2").val("");
		});
		//添加商品属性;
		$(".btn-add1").on("click",function(){
			var $onetr1=$("<tr class='onetr1'></tr>");
			var $td1=$('<td class="onetd1"></td>');
			var $td2=$('<td><a class="btn-del" href="javascript:;">删除</a></td>');
			var $td3=$('<td><select class="form-control" id="exampleInputEmail2"><option>--无--</option><option>内存</option><option>容量</option><option>尺寸</option><option>颜色</option></select></td>');
			var $td4=$('<td><select class="form-control" id="exampleInputEmail2"></select></td>');
			$onetr1.append($td1);
			$onetr1.append($td2);
			$onetr1.append($td3);
			$onetr1.append($td4);
			$("#tbody1").append($onetr1);
			if($(".onetr1").length==1){
				$td1.html(1);
			}else{
				$td1.html(Number($onetr1.prev().children().eq(0).html())+1);			
			}
			//删除商品;
			Delete("one");
		});
		Delete("one");
		//添加商品配件;
		$(".btn-add2").on("click",function(){
			var $onetr2=$("<tr class='onetr2'></tr>");
			var $td11=$('<td class="onetd2"></td>');
			var $td22=$('<td><a class="btn-del1 " href="javascript:;">删除</a></td>');
			var $td33=$('<td><input type="text" class="form-control searchbox" placeholder="搜索"/></td>');
			var $td44=$('<td><select class="form-control" id="exampleInputEmail2"><option>请选择商品</option></select></td>');
			var $td55=$('<td></td>');
			var $td66=$('<td></td>');
			var $td77=$('<td>￥0.00</td>');
			var $td88=$('<td><input type="text" class="form-control searchbox"/></td>');
			$onetr2.append($td11);
			$onetr2.append($td22);
			$onetr2.append($td33);
			$onetr2.append($td44);
			$onetr2.append($td55);
			$onetr2.append($td66);
			$onetr2.append($td77);
			$onetr2.append($td88);
			$("#tbody2").append($onetr2);
			if($(".onetr2").length==1){
				$td11.html(1);
			}else{
				$td11.html(Number($onetr2.prev().children().eq(0).html())+1);			
			}
	        Delete("two");
		});
		Delete("two");
		//鼠标划过出现删除;
		$(".modal-house").hover(
			function(){
			  $(this).children().eq(1).show();
			  $(this).children().eq(0).hide();
		   },
			function(){
			  $(this).children().eq(0).show();
			  $(this).children().eq(1).hide();
		    }
		);
		//点击删除仓库;
		$(".detail-house-del").on("click",function(){
			$(this).parent().parent().remove();
		});
		//添加仓库;
		$(".btn-change").click(function(){
			$(".modal-addware").show();
			//全选；
			$(".modal-allcheck").bind("click",function(){
				$("input[type='checkbox']").each(function(){
					this.checked=true;
				});
			});
			//全不选仓库;
			$(".modal-uncheck").bind("click",function(){
				$("input[type='checkbox']").each(function(){
					this.checked=false;
				});
			});
			//反选仓库;
			$(".modal-reverse").bind("click",function(){
				$("input[type='checkbox']").each(function(){
					if(this.checked=true){
						this.checked=false;
					}else{
						this.checked=true;
					}
				});
			});
		});
		var $mymodal2=$(".modal-addware");
		var $rowhouse=$(".detail-house");
		var $rowhouse=$(".detail-house");
		$(".btn-sure2").on("click",function(){
			$(".detail-house").empty();
			$("input[type='checkbox']").each(function(){
				if(this.checked){
					var $house=$('<div class="modal-house"><span class="modal-house-name">'+$(this).next().html()+'</span><span class="detail-house-del">删除</span></div>');
					$rowhouse.append($house);
					$mymodal2.hide();
					// this.checked=false;
					Hover();
					$(".detail-house-del").on("click",function(){
						$(this).parent().remove();
					});
				}
			});
		});

	});
	//划入划出;
	function Hover(){
		$(".modal-house").hover(
			function(){
			  $(this).children().eq(1).show();
			  $(this).children().eq(0).hide();
		   },
			function(){
			  $(this).children().eq(0).show();
			  $(this).children().eq(1).hide();
		    }
		);
	}
	//删除商品;
	function Delete(obj){
		if(obj=="one"){
			var $del=$(".btn-del");
			var $td1=$(".onetd1");
			$del.each(function(i){
				$del.eq(i).click(function(){
					$(this).parent().parent().remove();
					$td1.each(function(i){
					   	 $(this).html(i+1);
					});
				});
			});
		}
		if(obj=="two"){
			var $del=$(".btn-del1");
			var $td1=$(".onetd2");
			$del.each(function(i){
				$del.eq(i).click(function(){
					$(this).parent().parent().remove();
					$td1.each(function(i){
					   	 $(this).html(i+1);
					});
				});
			});
		}
	}
	
})