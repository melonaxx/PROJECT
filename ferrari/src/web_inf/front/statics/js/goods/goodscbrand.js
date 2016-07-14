$(function(){
	$(".cgaddbtn").click(function(){
		$("#myModaladds").show();
	})
	/*----------------添加分类---------------------*/
	$(".cgsureaddsbtn").on("click",function(){

		var catename = $('input[name=catename]').val();
		if (!catename) {
			$('input[name=catename]').focus();
			return false;
		}
		var addcatesuccess = function(msg){
			$("#myModaladds").hide();
			location.reload();
		}
		var addcatefail = function(){
			$("#myModaladds").hide();
			console.log('add category fail!');
			alert('添加商品分类失败！');
		}
		util.ajax_post('addgoodscate.php',{catename:catename},addcatesuccess,addcatefail);
	})
	/*--------------添加子分类---------------*/
	$('.addchildcate').on("click",function(){
		$("#myModaladdo").show();
		$addchildbtn = $(this);
	})
	//确认添加子分类btn
	$(".cgsureaddobtn").on("click",function(){
		//父类ID
		var pcateid = $addchildbtn.attr("uid");
		//子分类名称
		var childcatename = $('input[name=catechildname]').val();
		if (!childcatename) {
			$('input[name=catechildname]').focus();
			return false;
		}
		var addchildcatesuccess = function(msg) {
			$("#myModaladdo").hide();
			location.reload();
		}
		var addchildcatefail = function(){
			alert('添加子分类失败！');
			location.reload();
		}
		util.ajax_post('addgoodschildcate.php',{pcateid:pcateid,childcatename:childcatename},addchildcatesuccess,addchildcatefail);
	})
	/*------------子分类删除---------------*/
	$('.delcate').off('click').on("click",function(){
		$(".promptwindow").show();
		$deletebtn = $(this);
	})
	//删除子分类sub
	$(".gbsuredelbtn2").off('click').on("click",function(){
		$(".promptwindow").hide();
		//分类ID
		var cateid = $deletebtn.attr("uid");
		var delcatesuccess = function(msg){
			location.reload();
		}
		var delcatefail = function(errno){
			switch (errno) {
				case 4002:
					$('.myModaldelsure').show();
					break;
				case 4001:
					alert('删除分类失败！');
					break;
			}
		}
		util.ajax_post('delgoodscate.php',{cateid:cateid},delcatesuccess,delcatefail);
	});
	// /*------------分类修改----------------*/
	$('.gcbmodify').on("click",function(){
		$("#myModalmodson").show();
		$eidtcatebtn = $(this);
		//当前分类名称
		var catename = $(this).parent().next().attr("rename");
		$('input[name=editcatename]').val(catename);

		$(".cgsuremodsbtn").off('click').on("click",function(){
			//当前分类的ID
			var cateid = $eidtcatebtn.attr("uid");
			var catecname = $('input[name=editcatename]').val();
			var editcatesuccess = function(msg){
				$("#myModalmodson").hide();
				location.reload();
			}
			var editcatefail = function(){
				$("#myModalmodson").hide();
				alert('修改分类信息失败！');
				location.reload();
			}
			util.ajax_post('eidtgoodscate.php',{cateid:cateid,catecname:catecname},editcatesuccess,editcatefail);
		})
		//关闭
		$(".cgclosemodsbtn").on("click",function(){
			$(".gcbmodify").removeAttr("cid1");
			$("#myModalmodson").hide();
		})
	})
	// $(".cgcloseaddobtn").on("click",function(){
	// 	$(".gcbaddsonl").parent().parent().parent().parent().removeAttr("cid");
	// })
	// /*----------------总分类删除----------*/
	// $td2ad.on("click",function(){
	// 	var $that=$(this);
	// 	$("#myModaldel").show();
	// 	$(".gbsuredelbtn").on("click",function(){
	// 		if($that.parent().parent().parent().children().length==1){
	// 			$that.parent().parent().parent().remove();
	// 			$("#myModaldel").hide();
	// 		}else{
	// 			$(".myModaldelsure").show();
	// 		}
	// 	})
	// })
	// /*----------------总分类修改----------------*/
	// $td2am.on("click",function(){
	// 	$("#myModalmodson").show();
	// 	$(this).attr("cid2","nowobj");
	// 	$(".cgsuremodsbtn").on("click",function(){
	// 		$(".gcbmodify[cid2=nowobj]").parent().prev().children().eq(1).val($(".gcbclassnameims").val());
	// 		$(".gcbmodify").removeAttr("cid2");
	// 		$(".gcbclassnameims").val("");
	// 		$("#myModalmodson").hide();
	// 	})
	// 	$(".cgclosemodsbtn").on("click",function(){
	// 		$(".gcbmodify").removeAttr("cid2");
	// 	})
	// })
	// /*----------------点击尖角号实现收起展开---------------*/
	$('.arrow-top').on("click",function(){
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
	})

})
