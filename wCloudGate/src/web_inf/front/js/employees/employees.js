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
		var name = $("input[name=name]").val() || 0;
		var pagenum = pagenum || $("select[name=pagenum] option:selected").val();
		var pageall = $(".pageallnum").html();
			
    	var pagehref = "/employees.php?";
    	pagehref += "name="+ name +"&";
    	pagehref += "num="+ num +"&";
    	pagehref += "pageall="+ pageall +"&";
    	pagehref += "page="+ pagenum;

    	return pagehref;
	}

	//员工删除
	$(".del").on("click",function(){
		$(".delmodal").show();
		var employeid = $(this).parent().find("li:eq(2)").attr("employeid");
		$(".suredel").attr("employeid" , employeid);
	});
	$(".suredel").on("click",function(){
		var employeid = $(this).attr("employeid");
		var data = {employeid: employeid};
		util.ajax_post("/employeesdel.php" , data , delsuccess);
	});
	$(".canceldel").on("click",function(){
		$(".delmodal").hide();
	});
	
	$(".close").on("click" , function() {
		window.location.reload();
	});

	function delsuccess() {
		location.href = "/employees.php";
	}

	//员工分配劳务方
	$(".addlabor").on("click"  , function() {
		$(".labormodal").show();
		var employeid = $(this).parent().attr("employeid");
		$(".disttab").attr("employeid" , employeid);
		util.ajax_post("/employeesundislabor.php" , '' , undislaborsuccess);
	});

	function undislaborsuccess(data) {
		var ltr = "";
		if(data) {
			for(var i = 0; i < data.length; i++){
	         	ltr += "<tr laborid="+data[i]['id']+">";
	           	ltr += "<td><input name='checkbox' type='checkbox' /></td>";
	           	ltr += "<td>"+(i+1)+"</td>";
				ltr += "<td>"+data[i]['name']+"</td>";
				ltr += "<td></td></tr>";
			}
		}
		$(".labortab tbody").html(ltr);
	}

	$(".finishdistlabor").on("click" , function() {
		delsuccess();
	});

	$(".close").on("click" , function() {
		$(".labormodal").hide();
	});

	$(".distribtn").on("click" , function() {
		var larr = [];
		var che = 0;
		$.each($(".labortab tbody input[name=checkbox]") , function() {
			if($(this).prop("checked")) {
				che = 1;
				larr.push( $(this).parent().parent().attr("laborid") );
			}
		});

		if(!che) {
			$(".labormodal").hide();
			return ;
		}
		var employeid = $(".disttab").attr("employeid");
		var data = {
			larr: larr,
			employeid: employeid
		};

		if(flg) return false;flg = true;
	
		util.ajax_post("/employeeslabor.php" , data , delsuccess);
	});

	$(".checkall").on("click" , function() {
		if($(this).prop("checked") ) {
			$("input[name=checkbox]").prop("checked" , true);
		}else {
			$("input[name=checkbox]").prop("checked" , false);
		}			
	});

	$(".finish").on("click" , function() {
		location.href = "/employees.php";
	});
	
	//员工权限
	$(".distperm").on("click" , function() {
		$(".permission").show();
		var permission = $(this).parent().next().next().attr("permission");		
		var data = {permission: permission};

		util.ajax_post("/employeeshowpermission.php" , data , permissionsuccess);
		
		$(".permission").attr("permission" , permission);
		var employeid = $(this).parent().next().next().attr("employeid");
		$(".permission").attr("employeid" , employeid);
	});

	function permissionsuccess(data) {
		var checkbox = $(".distrcheck div").children(":checkbox") , 
		i = 0;

		$.each(checkbox , function() {
			var $this = $(this);
			if(data[i]['state']) {
				$this.prop("checked" , true);
			}
			$this.val(data[i]['permission']);
			i++;
		});

	}

	$(".cancel").on("click" , function() {
		location.href = "/employees.php";
	});

	$(".permissionbtn").on("click" , function() {
		var checkbox = $(".distrcheck div").children(":checkbox") , 
		car = [] ,
		labor = [] ,
		employee = [] ,
		arrchecked = [] ,
		data = {};
		$.each(checkbox , function() {
			var $this = $(this);
			var name = $this.attr("name");
			var val = $this.val();
			if($this.prop("checked")) {
				if(name == 1)
					car.push(val);
				if(name == 2)
					labor.push(val);
				if(name == 4)
					employee.push(val);
			}
		});

		arrchecked["1"] = car;
		arrchecked["2"] = labor;
		arrchecked["4"] = employee;

		var employeid = $(".permission").attr("employeid");
		data = {arrchecked: arrchecked , employeeid: employeid};
		util.ajax_post("/employeepermissionadd.php" , data , delsuccess);
	});

	//查看车辆
	$(".emcar").on("click" , function() {
		$(".modalcar").show();
		var employeeid = $(this).parent().parent().parent().next().find("li:eq(2)").attr("employeid");
		$(".lookemcar").attr("employeeid" , employeeid);
		var data = {employeeid: employeeid };
		util.ajax_post("/employeeebike.php" , data , carsuccess);
	});

	function carsuccess(data) {
		if(data) {
			var ltr = "";
			for(var i = 0; i < data.length; i++){
	         	ltr += "<tr ebikeid="+data[i]['ebikeid']+">";
	           	ltr += "<td>"+(i+1)+"</td>";
				ltr += "<td>"+data[i]['seqno']+"</td>";
				ltr += "<td>已分配</td>";
				if(data[i]['distribute']) {
					ltr += "<td>无</td></tr>";
				}else {
					ltr += "<td><a class='uncarem'>取消</a></td></tr>";
				}
				
			}
			$(".tablecheck tbody").html(ltr);
		}
	}

	$(".tablecheck").on("click" , ".uncarem" , function() {
		var sta = $(this).html();
		var employeid = $(".lookemcar").attr("employeeid");
		var larr = [];
		larr.push($(this).parent().parent().attr("ebikeid") );
		if(sta == "分配") {
			$(this).html("取消");
			$(this).parent().prev().html("已分配");
			var data = {
				larr: larr,
				employeid: employeid
			};
			util.ajax_post("/employeedistributeebike.php" , data );
		} else {
			$(this).html("分配");
			$(this).parent().prev().html('未分配');
			var seqno = $(this).parent().prev().prev().html();
			var data = {
				larr: seqno,
				employeeid: employeid
			};
			util.ajax_post("/employeeundistribute.php" , data );
		}
	});

	$(".finishcheck").on("click" , function() {
		location.href = "/employees.php";
	});

    //给员工分配车辆
    $(".showcar").on("click",function(){
        $(".modall").show();
        var employeid = $(this).parent().next().attr("employeid");
		$(".showcartab").attr("employeid" , employeid);
    });

	$(".emdistribtn").on("click" , function() {
		var larr = [];
		var che = 0;
		$.each($(".laborcartab tbody :checkbox") , function() {		
			if($(this).prop("checked")) {
				che = 1;
				larr.push( $(this).parent().parent().attr("ebikeid") );
			}
		});

		if(!che) {
			$(".modall").hide();
			return ;
		}
		var employeid = $(".showcartab").attr("employeid");
		var data = {
			larr: larr,
			employeid: employeid
		};
		if(flg) return false;flg = true;
		util.ajax_post("/employeedistributeebike.php" , data , delsuccess);
	});

	$(" thead ").on("click" , ":checkbox" , function() {
		if($(this).prop("checked") ) {
			$(":checkbox").prop("checked" , true);			
			$.each($(" tbody tr ") , function() {
				$(this).find("td:eq(3)").html("已选中");
			});		
		}else {
			$(":checkbox").prop("checked" , false);
			$.each($(" tbody tr ") , function() {
				$(this).find("td:eq(3)").html("");
			});	
		}			
	});

	$(" tbody").on("click" , ":checkbox" , function() {
		var $this = $(this);
		if($this.prop("checked") ) {
			$this.parent().next().next().next().html("已选中");
		}else {
			$this.parent().next().next().next().html("");
		}			
	});


});