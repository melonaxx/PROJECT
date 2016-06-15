$(function($){

	// 左侧列表二级菜单的显示隐藏
    $(".wllevel1").hover(
        function(){
            $(this).find(".wllevel2").show();
        },
        function(){
            $(this).find(".wllevel2").hide();
    });

    //查询条件
    $(".cdeactive").on("click" , function() {
    	location.href = "/platform/caractive.php?__=";
    });

	$("select[name=pagenum]").on("change", function() {
		var num = 1;
		var pagenum = $("select[name=pagenum] option:selected").html();
		location.href = pageurl(num , pagenum);
	});
	
	$(".carpagetext").on("keydown", function(e) {
		var num = $(this).val();
		var keyCode = e.keyCode;
        if(!isNumber(keyCode)) {
			return false;
		}

		if (keyCode == 13) {
            location.href = pageurl(num);
            return false;
        }				   
		 
    });

    // 仅能输入数字
	function isNumber(keyCode) {
	    // 数字
	    if (keyCode >= 48 && keyCode <= 57 ) 
	    	return true;
	    // 小数字键盘
	    if (keyCode >= 96 && keyCode <= 105) 
	    	return true;
	    // Backspace, del, 左右方向键
	    if (keyCode == 8 || keyCode == 46 || keyCode == 37 || keyCode == 39) 
	    	return true;	    
	    // Enter
	    if (keyCode == 13) 
	    	return true;
	    return false;
	}

	function pageurl(num , pagenum) {
		var carsearch = $("input[name=carsearch]").val() || 0;
		var abnormal = $("select[name=abnormal] option:selected").val() || 0;
		var allocation = $("select[name=allocation] option:selected").val() || 0;
		var belong = $("select[name=belong] option:selected").val() || 0;
		var labour = $("select[name=labour] option:selected").val() || 0;
		var status = $("select[name=status] option:selected").val() || 0;
		var pagenum = pagenum || $("select[name=pagenum] option:selected").val();
		var pageall = $(".pageallnum").html();
			
    	var pagehref = "/platform/carmanagement.php?";
    	pagehref += "carsearch="+ carsearch +"&";
    	pagehref += "abnormal="+ abnormal +"&";
    	pagehref += "allocation="+ allocation +"&";
    	pagehref += "belong="+ belong +"&";
    	pagehref += "labour="+ labour +"&";
    	pagehref += "status="+ status +"&";
    	pagehref += "num="+ num +"&";
    	pagehref += "pageall="+ pageall +"&";
    	pagehref += "page="+ pagenum;

    	return pagehref;
	}

	//删除车辆
	$(".deactive").on("click" , function() {
		var $that=$(this);
		$(".modalunwrap").show();
		var seqno = $that.parent().parent().parent().find(".carseqno").html();
		$(".delcar").removeAttr("seqno");
		$(".delcar").attr("seqno" , seqno);
	});
	
	$(".addunw").on("click",function(){
		var seqno = $(".delcar").attr("seqno");
		var data = {seqno: seqno};
		util.ajax_post("/platform/carmanagementdeactive.php" , data , deactivesuccess);
	});

	$(".cancela").on("click",function(){
		$(".modalunwrap").hide();
	});

	function deactivesuccess() {
		location.href = "/platform/carmanagement.php";
	}

	//平台查看
	$(".lookplatform").click(function(){
	    $(".platmodal").show();
	    var seqno = $(".deactive").parent().parent().parent().find(".carseqno").html();
	    var ebikeid = $(this).parent().attr("ebikeid");
	    var sdata = {seqno: seqno , ebikeid: ebikeid};
	    util.ajax_post("/platform/carmanagementplatforminfo.php" , sdata , platforminfo);
	});	

	$(".pfinish").on("click" , function() {
		location.href = "/platform/carmanagement.php";
	});

	function platforminfo(data) {
		if(data) {
			var $opt = '';
			$opt += "<option ebikeid=" + data[0]['ebikeid'];
			$opt += " >无</option>";
			for(var i = 0; i < data.length; i++) {				
				$opt += "<option ";
				$opt += " platformid=" + data[i]['platformid'];
				$opt += " >" + data[i]['name'] + "</option>";
			}
			$("select[name=staff]").html($opt);
		}
	}

	$(".sureemp").on("click" , function(){
		var platformid = $("select[name=staff] option:selected").attr("platformid") || 0;
		var ebikeid = $("select[name=staff] option:eq(0)").attr("ebikeid");
		if($("select[name=staff] option").length == 1) {
			return;
		}
		var data = {};
		if(platformid) {
			data = {platformid: platformid , ebikeid: ebikeid};
			util.ajax_post("/platform/carmanagementplatform.php" , data , deactivesuccess);
		} else{
			data = {ebikeid: ebikeid};
			util.ajax_post("/platform/carmanagementunplatform.php" , data , deactivesuccess);
		}

	});

	$(".cancelemp").on("click" , function() {
		$(".platmodal").hide();
	});

	//修改传感器
	$(".updatesensor").on("click" , function() {
		$(".sensor").show();
		var ebikeid = $(this).attr("ebikeid");
		$(".sensorattr").attr("ebikeid" , ebikeid);
	});

	$(".editsensor").on("click" , function() {
		var ebikeid = $(".sensorattr").attr("ebikeid");
		var sensor = $("input[name=sensor]").val();
		if(/^\s*$/.test(sensor)) {
			$(".sensormsg").show().html("请填写定位器");
			return fasle;
		}
		var data = {ebikeid: ebikeid , sensor: sensor};
		util.ajax_post("/careditsensor.php" , data , deactivesuccess , sensorfail);
	});

	function sensorfail(error , errmsg) {
		$(".sensormsg").show().html("定位器错误，修改失败");
	}

	$(".cancela").on("click" , function() {
		$(".sensor").hide();
	});

	//查看备注
	$(".lookremark").on("click" , function() {
		$(".remark").show();
		var remark = $(this).attr("remark");
		$(".ebikeremark").html(remark);
	});

	$(".cancelnote").on("click" , function() {
		$(".remark").hide();
	});

});
