$(function(){
	$(".cgaddbrandbtn").click(function(){
		$("#myModaladdb").show();
	})
	/*----------------添加品牌---------------------*/
	$(".cgsureaddbbtn").on("click",function(){
		var brandname = $('input[name=brandname]').val();
		if (!brandname) {
			$('input[name=brandname]').focus();
			return false;
		}
		var addbrandsuccess = function(msg){
			$("#myModaladdb").hide();
			location.reload();
		}
		var addbrandfail = function(){
			$("#myModaladdb").hide();
			console.log('add brand fail!');
			alert('添加商品品牌失败！');
		}
		util.ajax_post('addgoodsbrand.php',{brandname:brandname},addbrandsuccess,addbrandfail);
	});
	/*----------------总品牌修改----------------*/
	$('.gcbmodify3').on("click",function(){
		//商品ID
		var brandid = $(this).attr("uid");
		$("#myModalmodsonb").show();
		$('input[name=editbrandid]').val(brandid);
		var brandsname = $(this).parent().next().attr("rename");
		$('input[name=editbrandname]').val(brandsname);

		$(".cgsuremodbbtn").on("click",function(){
			//修改后的商品品牌名称
			var brandaeditname = $('input[name=editbrandname]').val();
			// 商品的品牌ID
			var aeditbrandid = $('input[name=editbrandid]').val();
			var editbrandsuccess = function(msg){
				$("#myModalmodsonb").hide();
				location.reload();
			}
			var editbrandfail = function(){
				alert('修改商品品牌名称失败！');
				console.log('edit good brand fail!');
			}
			util.ajax_post('editbrandbyid.php',{brandname:brandaeditname,brandid:aeditbrandid},editbrandsuccess,editbrandfail);
			$("#myModalmodsonb").hide();
		})
		$(".cgclosemodbbtn").on("click",function(){
			$(".gcbmodify3").removeAttr("cid5");
		})
	})
	/*----------------总品牌删除----------*/
	$('.gcbdel3').off('click').on("click",function(){
		var $that=$(this);
		brandid = $(this).attr("uid");
		$(".myModaldelb").show();
	})
	//确认删除sub
	$(".gbsuredelbbtn").on("click",function(){
		var delbrandsuccess = function(msg){
			$(".myModaldelb").hide();
			location.reload();
		}
		var delbrandfail = function(errno){
			$(".myModaldelb").hide();
			if (errno == 4001) {
				$('.myModaldelbsure').show();
			}else if(errno == 500) {
				alert('删除商品品牌失败！');
			}

		}
		util.ajax_post('delbrandbyid.php',{brandid:brandid},delbrandsuccess,delbrandfail);

	})
	/*----------------点击尖角号实现收起展开---------------*/
	$(".arrow-top").on("click",function(){
		$(this).attr("cid3","nowobj");
		if($("i[cid3=nowobj]").hasClass("arrow-top")){
			$("i[cid3=nowobj]").removeClass("arrow-top");
			$("i[cid3=nowobj]").addClass("arrow-left");
			$("i[cid3=nowobj]").parent().parent().siblings().hide();
		}else{
			$("i[cid3=nowobj]").addClass("arrow-top");
			$("i[cid3=nowobj]").removeClass("arrow-left");
			$("i[cid3=nowobj]").parent().parent().siblings().show();
		}
		$("i").removeAttr("cid3");
	});
	/*--------------添加子品牌---------------*/
	$('.gcbaddsonl3').on("click",function(){
		$addchildbtn =  $(this);
		$("#myModaladdob").show();
 	})
 	//提交添加子品牌btn
	$(".cgsureaddobbtn").on("click",function(){
		var bparentid = $addchildbtn.attr("uid");
		var bchildname = $('input[name=brandchildname]').val();
		if (!bchildname) {
			$('input[name=brandchildname]').focus();
			return false;
		}
		var addbchildsuccess = function(msg){
			$("#myModaladdob").hide();
			location.reload();
		}
		var addchilfail = function(){
			$("#myModaladdob").hide();
			console.log('add brand child fail!');
			alert('添加子品牌失败！');
		}
		util.ajax_post('addgoodschildbrand.php',{bparentid:bparentid,bchildname:bchildname},addbchildsuccess,addchilfail);
		// var $onetr23=$('<tr></tr>');
		// var $td12i3=$('<i class="gbbranch3"></i>');
		// var $td12input3=$('<input type="text" class="form-control gbbrandnames" disabled="disabled">');
		// var $td123=$('<td></td>');
		// var $td22am3=$('<a class="gcbmodify3">修改</a>');
		// var $td22ad3=$('<a class="gcbdel3">删除</a>');
		// var $td223=$('<td class="gcbtd13"></td>');
		// $td123.append($td12i3);
		// $td123.append($td12input3);
		// $td223.append($td22am3);
		// $td223.append($td22ad3);
		// $onetr23.append($td123);
		// $onetr23.append($td223);
		// $("tbody[cid3=nowobj]").append($onetr23);
		// $("#myModaladdob").hide();
		// $("tbody[cid3=nowobj]").children().last().children().children().eq(1).val($(".gcbbrandnameis").val());
		// $(".gcbbrandnameis").val("");
		// $(".gcbaddsonl3").parent().parent().parent().parent().removeAttr("cid3");
		// /*------------子品牌删除---------------*/
		// $td22ad3.on("click",function(){
		// 	$(".myModaldelb2").show();
		// 	$(".gbsuredelbbtn1").on("click",function(){
		// 		$td22ad3.parent().parent().remove();
		// 		$(".myModaldelb2").hide();
		// 	});
		// })
		// /*------------子品牌修改----------------*/
		// $td22am3.on("click",function(){
		// 	$(this).attr("cid4","nowobj");
		// 	$("#myModalmodsonb").show();
		// 	$(".cgsuremodbbtn").on("click",function(){
		// 		$(".gcbmodify3[cid4=nowobj]").parent().prev().children().eq(1).val($(".gcbbrandnameims").val());
		// 		$(".gcbbrandnameims").val("");
		// 		$(".gcbmodify3").removeAttr("cid4");
		// 		$("#myModalmodsonb").hide();
		// 	})
		// 	$(".cgclosemodbbtn").on("click",function(){
		// 		$(".gcbmodify3").removeAttr("cid4");
		// 	})
		// })
	})
	//关闭btn
	$(".cgcloseaddobbtn").on("click",function(){
		$(".gcbaddsonl3").parent().parent().parent().parent().removeAttr("cid");
	})
})
