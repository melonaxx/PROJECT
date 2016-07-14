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
$("#password").keyup(function(){
	var passwd = $("#password").val();
	if(passwd.match(/^\w{6,16}$/)==null){
	    	$('#passcuo').show();
			$("#passkong").hide();
			password = false;	
	    }else{
	    	$('#passcuo').hide();
	    	$("#passkong").hide();
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
	return repassword&&password;
}
$("#sub").click(function(){
	var passwd = $("#password").val();
	var repasswd = $("#repassword").val();
	if(passwd==""){
		$("#passkong").show();
		$('#passcuo').hide();
	}
	if(repasswd==""){
		$("#recuo").show();
	}
})
