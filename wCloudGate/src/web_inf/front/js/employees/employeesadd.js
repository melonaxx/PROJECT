$(function($) {
	//防多次提交变量
	var flg = false;
	$(".nav li:eq(4) a").css("color" , "#358dcc");
	$(".nav li:eq(4) p").css("display" , "block");

	$(".searchbtn").on("click" , function() {
		var employeid = $("input[name=carsearch]").val();
		if(employeid.match(/^1\d{10}$/) == null) {
			$(".searchresult").html('');
			$(".number").html('0');
			return false;
		}
		var data = {mobileno: employeid};
		util.ajax_post("/employeesaddsearch.php" , data , employeidaddsuccess , searchfail);
		$(".searchnum").show();

	});
	
	$(".addaccount").on("click" , function(){
		$(".addcheck").show();
	});

	function employeidaddsuccess(data) {
		if(data) {
			var td = '';
			td += '<td>1</td>';
			td += '<td>'+ data.name +'</td>';
			td += '<td>'+ data.mobileno +'</td>';
			td += '<td>'+ data.qq +'</td>';
			td += '<td class="addtd"><a class="adda">添加</a></td>';
			$(".searchresult").html(td);
			$(".number").html('1');
			$(".adda").on("click",function(){	
				$(".addcheck").show();
			});
			$(".outtab").show();
		} 

	}

	function searchfail(errno , errmsg) {
		$(".outtab").hide();
		if(errno == 404) {
			var mobileno = $("input[name=carsearch]").val();
			$(".addmobileno").html(mobileno);
			$(".zeroinf").show();
		}
	}

	$(".add").on("click",function(){
		var mobileno = $("input[name=carsearch]").val();
		var data = {mobileno: mobileno};
		if(flg) return false;flg = true;
		util.ajax_post("/employeesinsert.php" , data , insertaddsuccess);
	});
	$(".cancela").on("click",function(){
		location.href = "/employeesadd.php";
	});	
	$(".addempa").on("click",function(){
		location.href = "/employees.php";
	});

	function insertaddsuccess(data) {
		$(".succkeck").show();		
	}

	util.getkeydown( $(".carsearch") , $(".searchbtn") );

});
