var status = $("#success").val();
if(status==1){
	$("#tishi").html("修改成功!");
	$(".modal-success").show();
}else if(status==2){
	$("#tishi").html("修改失败!");
	$(".modal-success").show();
}
$(".queding").click(function(){
	$("#success").val("3");
})
var password = false;
var repassword = false;
var oldpasss  = false;
$("#old").keyup(function(){
	var oldpass = $("#old").val();
	$.ajax({
		type: "POST",
		url: "/admin/checkoldpass.php",
		data:{oldpass:oldpass},
		success: function(msg){
			if(msg==1){
				$("#oldcuo").hide();
				oldpasss = true;
			}else{
				$("#oldcuo").show();
				oldpasss = false;
			}
		},
	});
})
$("#password").keyup(function(){
	var passwd = $("#password").val();
	if(passwd.match(/^\w{6,16}$/)==null){
			$("#passkong").hide();
	    	$('#newcuo').show();
			password = false;	
	    }else{
	    	$("#passkong").hide();
	    	$('#newcuo').hide();
			password = true;
	    }
})
$("#repassword").keyup(function(){
	var passwd = $("#password").val();
	var repass = $("#repassword").val();
	if(passwd==repass){
		repassword = true;
		$("#recuo").hide();
	}else{
		repassword = false;
		$("#recuo").show();
	}
})
function fun(){
	return repassword&&password&&oldpasss;
}
$("#sub").click(function(){
	var passwd = $("#password").val();
	var oldpass = $("#old").val();
	var repass = $("#repassword").val();
	if(passwd==""){
		$("#passkong").show();
		$('#newcuo').hide();
	}
	if(oldpass==""){
		$("#oldcuo").show();
	}
	if(repass==""){
		$("#recuo").show();
	}
})