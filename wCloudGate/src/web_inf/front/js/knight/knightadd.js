$(function($) {

	//防多次提交变量
	var flg = false;

	$(".nav li:eq(4)").addClass("navactive");
	$(".searchbtn").on("click" , function() {
		var mobileno = $("input[name=ksearch]").val();
		if(mobileno.match(/^1\d{10}$/) == null) {
			$(".searchresult").html('');
			$(".number").html('0');
			return false;
		}
		var data = {mobileno: mobileno};
		util.ajax_post("/knightaddsearch.php" , data , knightaddsuccess , searchfail);
		
	});
	
	$(".addaccount").on("click" , function(){
		$(".addcheck").show();
	});

	function knightaddsuccess(data) {
		if(data) {
			var td = '';
			td += '<td>1</td>';
			td += '<td>'+ data.name +'</td>';
			td += '<td>'+ data.mobileno +'</td>';
			td += '<td>'+ data.email +'</td>';
			td += '<td class="addtd"><a class="adda">添加</a></td>';
			$(".searchresult").html(td);
			$(".number").html('1');
			$(".searchnum").show();
			$(".outtab").show();
			$(".adda").on("click",function(){
				$(".addcheck").show();
			});
		} 

	}

	function searchfail(errno , errmsg) {
		$(".searchnum").show();
		$(".outtab").hide();
	 	if(errno == 404) {
			var mobileno = $("input[name=ksearch]").val();
			$(".addmobileno").html(mobileno);
			$(".zeroinf").show();
		}
	}

	$(".add").on("click",function(){
		var mobileno = $("input[name=ksearch]").val();
		var data = {mobileno: mobileno};
		if(flg) {
			return false;
		}
		flg = true;
		util.ajax_post("/knightaddinsert.php" , data , knightaddinsertsuccess);
	});
	$(".cancela").on("click",function(){
		$(".modaladd").hide();
	});

	function knightaddinsertsuccess(data) {
		$(".succkeck").show();		
	}

	util.getkeydown( $(".carsearch") , $(".searchbtn") );

});
