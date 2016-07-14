$(function(){
	/*----------添加新规格-------------*/
	$(".gpstopr").click(function(){
		$("#Modaladdspe").show();
	})
	var $pro=window.location.href;
	if($pro.indexOf("profile")!=-1){
		$(".homeul").removeClass("active");
		$(".profileul").addClass("active");
		$("#home").removeClass("active");
		$("#profile").addClass("active");
	}
	$(".gpsureaddbtn").on("click",function(){
		$addformatesub = $(this);
		if($(".gpsnamei").val()!=""){
			//添加商品分类名称
			var formatename = $('.addformatname').val();
			var addformatesuccess = function(msg){
				$("#Modaladdspe").hide();
				location = location;
			}
			var addformatefail = function(){
				alert('添加商品分类失败！');
			}
			util.ajax_post('addgoodsformate.php',{formatename:formatename},addformatesuccess,addformatefail);


		}
	})
	/*----------------li划过-------------------*/
	$('.gspmainli').hover(function(){
		$(this).toggleClass("gpliactive");
	})
	/*-------------删除规格-----------*/
	$(".gpdelpic").on("click",function(){
		$delformatebtn=$(this);
		$(".modaldelspe").show();
	})
	//删除商品规格sub
	$(".gpsuredelsbtn").on("click",function(){
		$(".modaldelspe").hide();
		//商品规格ID
		var fnameid = $delformatebtn.parent().find('input').val();
		var delfnamesuccess = function(msg) {
			location.reload();
		}
		var delfnamefail = function(errno){
			switch (errno) {
				case 4001:
					alert('该规格下面有规格值不能进行删除！');
					break;
				case 434:
					alert('删除规格名称失败！');
					break;
			}
		}
		util.ajax_post('delgoodsformate.php',{fnameid:fnameid},delfnamesuccess,delfnamefail);
	})
	/*-------------修改规格------------*/
	$(".gpmodpic").on("click",function(){
		$(this).parent().parent().addClass("nowobj");
		$("#Modalmodspe").show();
		//商品规格值
		$eformatename = $(this).parent().prev().html();
		// 商品规格ID
		$eformateid = $(this).closest('div').find('input').val();
		$('.gpsnamei2').val($eformatename);

	})
	//修改后的提交btn
	$(".gpsuremodsbtn").off('click').on("click",function(){
		if($(".gpsnamei2").val()!=""){
			//修改后的规格名
			var efname = $('.gpsnamei2').val();
			var editformatesuccess = function(msg){
				// console.log(msg);
			}
			var editformatefail = function(){
				alert('修改商品规格信息失败！');
			}
			util.ajax_post('editgoodsformate.php',{eformatename:efname,eformateid:$eformateid},editformatesuccess,editformatefail);
			$(".nowobj").children().eq(0).html($(".gpsnamei2").val());
			$("#Modalmodspe").hide();
		}
		$(".gpmodpic").parent().parent().removeClass("nowobj");
		$(".gpsnamei2").val("");
	})
	/*----------------点击li-------------------*/
	$('.gspmainli').on("click",function(){
		$(this).addClass("gpliactive2");
		$(this).siblings().removeClass("gpliactive2");
	})
	$(".gsplil").on("click",function(){
		$(".gpspevalue").show();
		//清空ul
		$('.gspmainulv').empty();
		$fnameid = $(this).next().find('input').val();
		//设置fnameid
		$('input[name=fvaluesid]').val($fnameid);
		var showfvaluesuccess = function(msg) {
			console.log(msg);
			//显示对应的规格属性值
			$.each(msg,function(i,v){
				var $gspvmainli=$('<li class="gpsvmainli"></li>');
				var $gspvlil=$('<div class="gpsvlil"></div>');
				var $gpvdelpic=$('<span class="glyphicon glyphicon-trash gpvdelpic" title="删除"></span>');
				var $gpvmodpic=$('<span class="glyphicon glyphicon-list-alt gpvmodpic" title="修改"></span><input type="hidden" name="editfvalueid" value="'+v.id+'" />');
				var $gspvlir=$('<div class="gpsvlir"></div>');
				$gspvlir.append($gpvdelpic);
				$gspvlir.append($gpvmodpic);
				$gspvmainli.append($gspvlil);
				$gspvmainli.append($gspvlir);
				$(".gspmainulv").append($gspvmainli);
				$gspvlil.html(v.choice);
				/*-------------修改规格值------------*/
				$(".gpvmodpic").off('click').on("click",function(){
					$eidtfvaluebtn = $(this);
					$(this).parent().parent().addClass("nowobj");
					$("#Modalmodspevv").show();
					$('.gpsvaluei2').val($(this).closest('li').children().eq(0).text());
				})
				/*----------------li划过-------------------*/
				$gspvmainli.hover(function(){
					$(this).toggleClass("gpliactive");
				})
			})

		}
		var showfvaluefail = function(){
			console.log('show fvalue fail!');
		}
		util.ajax_post('listgoodsformatevalue.php',{fnameid:$fnameid},showfvaluesuccess,showfvaluefail);
	})
	/*--------------新增规格值--------------*/
	$(".gpsvtopr").click(function(){
		$("#Modalmodspev").show();

	})
	$(".gpsureaddvbtn").on("click",function(){
		if($(".gpsvaluei").val()!=""){
			//新增规格值
			var afvalue = $('.gpsvaluei').val();
			var addfvaluesuccess = function(msg){
				location.reload();
			}
			var	addfvaluefail = function(){
				alert('添加商品规格值失败！');
			}
			util.ajax_post('addgoodsformatevalue.php',{addfvalue:afvalue,afnameid:$fnameid},addfvaluesuccess,addfvaluefail);
			var $gspvmainli=$('<li class="gpsvmainli"></li>');
			var $gspvlil=$('<div class="gpsvlil"></div>');
			var $gpvdelpic=$('<span class="glyphicon glyphicon-trash gpvdelpic" title="删除"></span>');
			var $gpvmodpic=$('<span class="glyphicon glyphicon-list-alt gpvmodpic" title="修改"></span>');
			var $gspvlir=$('<div class="gpsvlir"></div>');
			$gspvlir.append($gpvdelpic);
			$gspvlir.append($gpvmodpic);
			$gspvmainli.append($gspvlil);
			$gspvmainli.append($gspvlir);
			$(".gspmainulv").append($gspvmainli);
			$("#Modalmodspev").hide();
			$gspvlil.html($(".gpsvaluei").val());
			$(".gpsvaluei").val("");
		}
	})

	//修改值后的sub
	$(".gpmodsvbtn").on("click",function(){
		if($(".gpsvaluei2").val()!=""){
			//规格值
			var editfvalue = $('.gpsvaluei2').val();
			var feditvalueid = $eidtfvaluebtn.next().val();
			$(".nowobj").children().eq(0).html($(".gpsvaluei2").val());
			$("#Modalmodspevv").hide();
			var editfvaluesuccess = function(msg){
				console.log(msg);
			}
			var editfvaluefail = function(){
				console.log('edit fvalue fail!');
			}
			util.ajax_post('editgoodsformatevalue.php',{fvalue:editfvalue,fvalueid:feditvalueid},editfvaluesuccess,editfvaluefail);
		}
		$(".gpvmodpic").parent().parent().removeClass("nowobj");
		$(".gpsvaluei2").val("");
	})
	/*-------------删除规格值-----------*/
	$(".gpspevalue").on("click",'.gpvdelpic',function(){
		$(this).parent().parent().addClass("nowobj");
		$("#Modaldelspev").show();
		$delfvaluebtn = $(this);
	})
	//删除规格后确定sub
	$(".gpsuredelsvbtn").on("click",function(){
		//规格值ID
		var fvalueid = $delfvaluebtn.parent().find('input').val();
		var delfvaluesuccess = function(msg) {
			console.log(msg);
		};
		var delfvaluefail = function(){
			alert('删除规格失败！');
		}
		util.ajax_post('delgoodsformatevalue.php',{fvalueid:fvalueid},delfvaluesuccess,delfvaluefail);
		$(".nowobj").remove();
		$("#Modaldelspev").hide();
		$(".gpvdelpic").parent().parent().removeClass("nowobj");
	})
})