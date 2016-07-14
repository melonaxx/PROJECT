$(function(){
	//序号
	$.each($('.sequence'),function(i,v){
		$(this).text(i+1);
	})
	/*-------------------单位设置---------------*/
	/*---------------添加一行------------*/
	$(".unitadd").click(function(){
		$(" #Modaladdunit").show();
	});
	$(".gpaddunitp").click(function(){
		$(" #Modaladdunit").show();
	});
	//添加单位后的sub
	$(".gpsureunitnbtn").on("click",function(){
		if($(".gpunitnamei").val()!=""){
			var unitname = $('.gpunitnamei').val();
			var addunitsuccess = function(msg) {
				$(" #Modaladdunit").hide();
				location.reload();
			}
			var addunitfail = function(){
				location.reload();
				$(" #Modaladdunit").hide();
				console.log('add unit fail!');
			}
			util.ajax_post('addgoodsunitset.php',{unitname:unitname},addunitsuccess,addunitfail);
			$(".gpunitnamei").val("");

		}
	})
	// /*-------------修改单位名称------------*/
	$(".table").on("click",'.gpmodifytd',function(){
		$(this).parent().parent().addClass("nowobj");
		$("#Modalmodunit").show();
		$editunitbtn = $(this);
		var unitname = $(this).parent().next().text();
		$('.gpsunitni').val(unitname);
	})
	//修改后的提交sub
	$(".gpmodunitbtn").on("click",function(){
		if($(".gpsunitni").val()!=""){
			var unitid = $editunitbtn.parent().find('input[name=unitid]').val();
			var unitname = $('.gpsunitni').val();
			var editprounitsuccess = function(msg){
				console.log(msg);
			}
			var editprounitfail = function(){
				console.log('edit pro unit fail!');
			}
			util.ajax_post('editgoodsunitset.php',{unitid:unitid,unitname:unitname},editprounitsuccess,editprounitfail);
			$(".nowobj").children().eq(2).html($(".gpsunitni").val());
			$("#Modalmodunit").hide();
		}
		$(".gpmodifytd").parent().parent().removeClass("nowobj");
		$(".gpsunitni").val("");
	})
	// /*-------------删除单位名称-------------*/
	$(".table").on("click",'.gpdeltd',function(){
		$delunitbtn = $(this);
		$(this).parent().parent().addClass("nowobj");
		$("#Modaldelunit").show();
	})
	//提交删除
	$(".gpdelunitbtn").on("click",function(){
		$(".nowobj").remove();
		$(".gpdeltd").parent().parent().removeClass("nowobj");
		//单位ID
		var unitid = $delunitbtn.parent().find('input[name=unitid]').val();
		var delunitsuccess = function(msg) {
			location.reload();
		}
		var delunitfail = function(){
			console.log('del unit fail!');
		}
		util.ajax_post('delgoodsunitset.php',{unitid:unitid},delunitsuccess,delunitfail);
		$("#Modaldelunit").hide();
	})

	// /*--------------提示行的显示隐藏------------*/
	// if($(".gponetr").length==0){
	// 	$(".psfirsttr").show();
	// }else{
	// 	$(".psfirsttr").hide();
	// }
})