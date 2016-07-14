$(function(){
	/*------------------------添加新属性----------------------*/
	$(".gpstopr2").click(function(){
		$("#Modaladdspe2").show();
	});
	//添加商品后的确认sub
	$(".gpsureaddbtn2").on("click",function(){
		if($(".addfattrs").val()!=""){
			//新增的商品属性值
			var fattrname = $('.addfattrs').val();
			var addfattrsuccess = function(msg) {
				if (msg == 1) {
					$("#Modaladdspe2").hide();
					location.reload();
				}
			}
			var addfattrsfail = function(){
				console.log('add fattrname fail!');
			}
			util.ajax_post('addgoodsproperty.php',{fattrname:fattrname},addfattrsuccess,addfattrsfail);

			$(".addfattrs").val("");

		}
	})
	/*-------------删除属性-----------*/
	$(".delattrname").on("click",function(){
		$delattrnamebtn=$(this);
		$(".modaldelspe2").show();
	})
	// 确认删除商品属性名称sub
	$(".gpsuredelsbtn2").on("click",function(){
		var pattrnameid = $delattrnamebtn.parent().find('input').val();

		var delattrnamesuccess = function(msg) {
			history.go(0);
		}
		var delattrnamefail = function(errno){
			switch (errno) {
				case 401:
					alert('该商品属性有属性值，不能进行删除！');
					break;
				case 434:
					alert('删除商品属性失败！');
					break;
			}
		}
		util.ajax_post('delgoodsproperty.php',{pattrnameid:pattrnameid},delattrnamesuccess,delattrnamefail);
		// $delattrnamebtn.parent().parent().remove();
		$(".modaldelspe2").hide();
	})
	// /*-------------修改属性------------*/
	$(".editattrname").on("click",function(){
		$(this).parent().parent().addClass("nowobj");
		// 显示添加窗口
		$("#Modalmodspe2").show();
		$editattrbtn = $(this);
		$('.gpsnamei222').val($(this).parent().prev().text());

	})
	//修商品属性名称后的sub
	$("#gpmodsbtn2").on("click",function(){
		//获取商品属性名称ID
		var attrnameid = $editattrbtn.parent().find('input[name=fattrnameid]').val();
		var eattrname = $('.gpsnamei222').val();

		if(eattrname!=""){
			var editattrnamesuccess = function(msg) {
				console.log(msg);
			}
			var editattrnamefail = function(){
				console.log('edit attrname fail!');
			}
			util.ajax_post('editgoodsproperty.php',{attrnameid:attrnameid,eattrname:eattrname},editattrnamesuccess,editattrnamefail);
			$(".nowobj").children().eq(0).html($(".gpsnamei222").val());
			$("#Modalmodspe2").hide();
		}
		$(".editattrname").parent().parent().removeClass("nowobj");
		$(".gpsnamei222").val("");
	})
	// /*----------------li划过-------------------*/
	$('.gspmainli2').hover(function(){
		$(this).toggleClass("gpliactive");
	})
	$('.gspmainli2').click(function(){
		$(this).addClass("gpliactive2");
		$(this).siblings().removeClass("gpliactive2");
	})
	// /*----------------点击li-------------------*/
	$('.gsplil2').on("click",function(){
		var panameid = $(this).next().find('input[name=fattrnameid]').val();
		$(".gpspevalue2").show();
		$('.gpsvtop2').find('input[name=panameid]').val(panameid);

		var listpavaluesuccess = function(msg) {
			$('.gspmainulv2').empty();
			$.each(msg,function(i,v){
				var $gspvmainli2=$('<li class="gpsvmainli2"></li>');
				var $gspvlil2=$('<div class="gpsvlil2">'+v.optional+'</div>');
				var $gpvdelpic2=$('<span class="glyphicon glyphicon-trash delpattrvalue" aria-hidden="true"></span>');
				var $gpvmodpic2=$('<span class="glyphicon glyphicon-list-alt editpattrvalue" aria-hidden="true"></span><input type="hidden" name="pattrvalueid" value="'+v.id+'"/>');
				var $gspvlir2=$('<div class="gpsvlir2"></div>');
				$gspvlir2.append($gpvdelpic2);
				$gspvlir2.append($gpvmodpic2);
				$gspvmainli2.append($gspvlil2);
				$gspvmainli2.append($gspvlir2);
				$(".gspmainulv2").append($gspvmainli2);
			})
		}
		var listpavaluefail = function() {
			$('.gspmainulv2').empty();
			console.log('list pattr value list fail!');
		}
		util.ajax_post('listpattrvalues.php',{panameid:panameid},listpavaluesuccess,listpavaluefail);

	})
	/*--------------新增属性值--------------*/
	$(".gpsvtopr2").click(function(){
		$("#Modalmodspev2").show();
		$addpavaluebtn = $(this);
	});
	//新增商品属性值确认sub
	$(".gpsureaddvbtn2").on("click",function(){
		if($(".gpsvaluei22").val()!=""){
			var pattrnid = $addpavaluebtn.parent().find('input[name=panameid]').val();
			var pattrvalue = $(".gpsvaluei22").val();
			var addpvaluesuccess = function(msg){
				history.go(0);
			}
			var addpvaluefail = function(){
				console.log('add pro attr fail!');
			}
			util.ajax_post('addpattrvalues.php',{pattrnameid:pattrnid,pattrvalue:pattrvalue},addpvaluesuccess,addpvaluefail);
			var $gspvmainli2=$('<li class="gpsvmainli2"></li>');
			var $gspvlil2=$('<div class="gpsvlil2"></div>');
			var $gpvdelpic2=$('<span class="glyphicon glyphicon-trash gpvdelpic2"></span>');
			var $gpvmodpic2=$('<span class="glyphicon glyphicon-list-alt gpvmodpic2"></span>');
			var $gspvlir2=$('<div class="gpsvlir2"></div>');
			$gspvlir2.append($gpvdelpic2);
			$gspvlir2.append($gpvmodpic2);
			$gspvmainli2.append($gspvlil2);
			$gspvmainli2.append($gspvlir2);
			$(".gspmainulv2").append($gspvmainli2);
			$("#Modalmodspev2").hide();
			$gspvlil2.html($(".gpsvaluei22").val());
			$(".gpsvaluei22").val("");

		}
	})
	/*-------------修改属性值------------*/
	$('.gpsvmain2').on("click",'.editpattrvalue',function(){
		$editpabtn = $(this);
		$(this).parent().parent().addClass("nowobj");
		$("#Modalmodspevv2").show();
		//取值并显示
		var editname = $(this).parent().prev().text();
		$('.gpsvaluei222').val(editname);
	})
	//修改商品属性值后sub
	$(".gpmodsvbtn2").on("click",function(){
		if($(".gpsvaluei222").val()!=""){
			var pattrname = $('.gpsvaluei222').val();
			var pattrvid = $editpabtn.parent().find('input[name=pattrvalueid]').val();
			var editpavaluesuccess = function(msg){
				console.log(msg);
			}
			var editpavaluefail = function(){
				console.log('edit pro attr value fail!');
			}
			util.ajax_post('editpattrvalues.php',{pattrname:pattrname,pattrvid:pattrvid},editpavaluesuccess,editpavaluefail);
			$("#Modalmodspevv2").hide();
			$(".nowobj").children().eq(0).html($(".gpsvaluei222").val());
		}
		$(".gpvmodpic2").parent().parent().removeClass("nowobj");
		$(".gpsvaluei222").val("");
	})

	/*-------------删除属性值-----------*/
	$(".gpsvmain2").on("click",'.delpattrvalue',function(){
		$delpattrbtn = $(this);
		$(this).parent().parent().addClass("nowobj");
		$("#Modaldelspev2").show();
	})
	//删除属性值后的sub
	$(".gpsuredelsvbtn2").on("click",function(){
		var pattrvalueid = $delpattrbtn.parent().find('input').val();
		var delpavaluesuccess = function(msg) {
			console.log(msg);
		}
		var delpavaluefail = function(){
			console.log('del pro attr fail!');
		}
		util.ajax_post('delpattrvalues.php',{pattrvalueid:pattrvalueid},delpavaluesuccess,delpavaluefail);
		$(".nowobj").remove();
		$("#Modaldelspev2").hide();
		$(".delpattrvalue").parent().parent().removeClass("nowobj");
	})
	/*----------------li划过-------------------*/
	$('.gpsvmain2').on("mouseover mouseout",'.gpsvmainli2',function(){
		$(this).toggleClass('gpliactive');
	})
})