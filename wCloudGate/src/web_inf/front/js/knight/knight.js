$(function($){

	//防多次提交变量
	var flg = false;

	//搜索分页
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
		var gid = $("select[name=gid] option:selected").val() || 0;
		var name = $("input[name=name]").val() || 0;
		var pagenum = pagenum || $("select[name=pagenum] option:selected").val();
		var pageall = $(".pageallnum").html();
			
    	var pagehref = "/knight.php?";
    	pagehref += "gid="+ gid +"&";
    	pagehref += "name="+ name +"&";
    	pagehref += "num="+ num +"&";
    	pagehref += "pageall="+ pageall +"&";
    	pagehref += "page="+ pagenum;

    	return pagehref;
	}

	//删除
	$(".del").click(function(){
        $(".delmodal").show();
	    var knightid = $(this).parent().attr("knightid");
        var ebikeid  = $(this).parent().attr("ebikeid");
        var suredel = $(".suredel");
        suredel.removeAttr("knightid");
        suredel.removeAttr("ebikeid");
	    suredel.attr("knightid" , knightid);
	    suredel.attr("ebikeid" , ebikeid);
    });

	$(".suredel").on("click",function(){
		var knightid = $(this).attr("knightid");
		var ebikeid = $(this).attr("ebikeid");
		var data = {knightid: knightid,ebikeid:ebikeid};
		util.ajax_post("/knightdel.php" , data , delsuccess );
	});
	$(".canceldel").on("click",function(){
		location.href = "/knight.php";
	});

	function delsuccess(data) {
        location.href = "/knight.php";
	}

    function delfail(data){
        // location.href = "/knight.php";
    }

	$(".empcheck").click(function(){
	    $(".modald").show();
	    var knightid = $(this).parent().attr("knightid");
	    $(".outtable").removeAttr("knightid");
	    $(".outtable").attr("knightid" , knightid);
	    util.ajax_post("/knightcarshow.php" , '' , carshowsuccess);
    });

	function carshowsuccess(data) {
		var ltr = '';
		if(data) {
			var j = 0;
			for(var i = 0; i < data.length; i++){
                if(data[i]['allot'] == 1)
					continue;
             	ltr += "<tr ebikeid="+data[i]['ebikeid']+">";
              	ltr += "<td>"+(j+1)+"</td>";
				ltr += "<td>"+data[i]['seqno']+"</td>";
				ltr += "<td class='cancel'></td> <td><a class='addla'>分配</a></td></tr>";
            	j++;
            }
		}

        $(".outtable tbody").html(ltr);
	}

	$(".close").on("click" , function() {
		$(".modald").hide();
		location.href = "/knight.php";
	});	

	$(".finish").on("click" , function() {
		$(".modald").hide();
		location.href = "/knight.php";
	});

	$(".outtable").on("click" , ".addla" , function() {
		var sta = $(this).html();
		var knightid = $(".outtable").attr("knightid");
		var ebikeid = $(this).parent().parent().attr("ebikeid");
		var data = {
				knightid: knightid,
				ebikeid: ebikeid
			};
		if(sta == "分配") {
			var state = 0;
			$.each($(".addla") , function() {
				if($(this).html() ==  "取消")
					state = 1;
			});
			if(state) {
				$(".kmsg").html('请先取消后再分配新车辆');
				return;
			}		
			$.each($(".cancel") , function() {
				$(this).html('');
				$(this).next().children().html("分配");
			});
			$(this).html("取消");
			$(this).parent().prev().html("成功");

			util.ajax_post("/knightcar.php" , data );
		} else {
			$(".kmsg").html('');
			$(this).html("分配");
			$(this).parent().prev().html('');		
			util.ajax_post("/knightuncar.php" , data );
		}
	});

	$(".unempcheck").on("click" , function() {
		$(".carmodal").show();
		var knightid = $(this).parent().attr("knightid");
		var ebikeid = $(this).parent().attr("ebikeid");
		var cardelbtns = $(".cardelbtns");
		cardelbtns.removeAttr("knightid");
		cardelbtns.removeAttr("ebikeid");
		cardelbtns.attr("knightid" , knightid );
		cardelbtns.attr("ebikeid" , ebikeid );		
	});

	$(".carsuredel").on("click" , function() {
		var knightid = $('.cardelbtns').attr("knightid");
		var ebikeid = $(".cardelbtns").attr("ebikeid");
		var data = {
				knightid: knightid,
				ebikeid: ebikeid
			};
		util.ajax_post("/knightuncar.php" , data , uncarsuccess);
	});


	function uncarsuccess() {
		location.href = "/knight.php";
	}


	$(".gmanageadd").on("click" , function(){
		$(".modalgmanage").hide();
		$(".kaddgroup").show();
	});
	$(".groupaddcancel").on("click" , function(){
		$(".kaddgroup").hide();
	});

	$(".groupadd").on("click" , function(){
		var name = $("input[name=groupname]").val();
		if(/^\s*$/.test(name)){
			$(".kaddgroup").hide();
			return ;
		}
		var data = {name: name};

		if(flg) {
			return false;
		}
		flg = true;

		util.ajax_post("/knightgroupadd.php" , data , uncarsuccess);
	});
	
	$(".gmanage").on("click" , function(){
		$(".modalgmanage").show();
		util.ajax_post("/knightgroupshow.php" , '' , showkpsuccess);
	});

	function showkpsuccess (data) {
		var ktr = '';
		if(data) {
			var j = 1;
			for(var i = 0; i < data.length; i++) {
				ktr += '<tr gid='+ data[i]['id'] +' name='+ data[i]['name'] +'><td>'+ j +'</td>';
				ktr += '<td>'+ data[i]['name'] +'</td>';
				ktr += '<td>'+ data[i]['cnt']+'</td>';
				ktr += '<td><a class="modga">修改</a>';
				ktr += '<a class="dela">删除</a></td></tr>';
				j++;
			}
		}
		
		$(".showkp").html(ktr);
	}

	$(".showkp").on("click" , ".modga" , function(){
		var $this = $(this);
		$this.parent().prev().prev().html('<input style="width:77px;" type="text" class="lgnameinp" name="lgname" value="" />');
		$this.parent().html('<a class="lgname">确定</a><a class="nolgname" >取消</a>');
	});

	$(".showkp").on("click" , ".nolgname" , function(){
		var $this = $(this);
		var name = $this.parent().parent().attr("name");
		$this.parent().prev().prev().html(name);
		$this.parent().html('<a class="modga">修改</a><a class="dela">删除</a>');
	});	

	$(".showkp").on("click" , ".lgname" , function(){
		var $this = $(this);
		var name = $this.parent().prev().prev().find("input").val();
		var id = $this.parent().parent().attr("gid");
		$this.parent().prev().prev().html(name);
		$this.parent().html('<a class="modga">修改</a><a class="dela">删除</a>');

		if(flg) {
			return false;
		}
		flg = true;
		if(/^\s*$/.test(name)){
			return ;
		}
		var data = {id: id , name: name};
		util.ajax_post("/knightgroupedit.php" , data);
	});

	$(".showkp").on("click" , ".dela" , function(){
		var $this = $(this);
		var id = $this.parent().parent().attr("gid");
		$this.parent().parent().remove("tr");
		showkptable ();
		var data = {id: id};
		util.ajax_post("/knightgroupdel.php" , data);
	});
			 
	function showkptable() {
		var std = $(".showkp tr ").find("td:eq(0)");
		var j = 1;
		$.each(std , function() {
			$(this).html(j);
			j++;
		});
	}

	$(".gmsure").on("click" , function(){
		uncarsuccess();
	});


	$(".modgroup").on("click" , function(){
		var kid = $(this).parent().parent().parent().next().attr("knightid");
		$(".knighttogsel").removeAttr("knightid");
		$(".knighttogsel").attr("knightid" , kid);
		util.ajax_post("/knightgroupshow.php" , '' , ktopsuccess);
		$(".addknighttog").show();
	});

	function ktopsuccess(data) {
		var ktopopt = '<option value="0">无</option>'
		if(data) {
			for(var i = 0; i <data.length; i++ ) {
				ktopopt += "<option value="+data[i]['id']+">"+data[i]['name']+"</option>";
			}
		}
		$("select[name=knighttog]").html(ktopopt);
	}

	$(".togroupcancel").on("click" , function(){
		$(".addknighttog").hide();
	});

	$(".togroup").on("click" , function(){
		var id = $("select[name=knighttog] option:selected").val();
		var kid = $(".knighttogsel").attr("knightid");
		var data = {id: id , kid: kid};

		if(flg) {
			return false;
		}
		flg = true;
		util.ajax_post("/knighttogroup.php" , data , uncarsuccess);
		
	});

});


