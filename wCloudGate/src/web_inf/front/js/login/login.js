$(function($) {		

	$(".lline").on("click" , function() {
		$(".login").show();
		$(".login_phone").hide();
		$(".pline").removeClass("lmactive");
		$(this).addClass("lmactive");
		$("input").val("");
		$("div.errmsg").html("");
        $(".msg").html("");
        $(".error-message").html("");
        $(":checkbox").prop("checked" , false);
	});	

	$(".pline").on("click" , function() {
		$(".login").hide();
		$(".login_phone").show();		
		$(".lline").removeClass("lmactive");
		$(this).addClass("lmactive");
		$("input").val("");
		$("div.errmsg").html("");
        $(".msg").html("");
        $(".error-message").html("");
        $(":checkbox").prop("checked" , false);
	});

	$(".loginbtn").on("click" , function() {
        var $this = $(this);

        var phone = checkMobile();
        if (!phone) {
            return false;
        }
		var password = checkPasswd();
		if(!password) {
			return false;
		}

 		var times = $(".login input[name=checkbox1]").prop("checked") ? 1 : 0;

        var data= {
            phone: phone ,
            password: password ,
            times: times
        };

        $this.html("登录中...");
        $this.prop("disabled" , true);
        $this.attr("style","cursor:no-drop");
		util.ajax_post("/dologin.php" , data , logSuccess, logFail);
	    return false;
    });

    $(".login input").on("focus" , function() {
        var $this = $(this);
        var text = $this.next();
        if(!JPlaceHolder.check()){ 
            text = $this.parent().next();
        }
        text.html("");
    });

    function checkMobile() {
        var message;

		var phoneobj = $("input[name=pphone]");
		var phone = phoneobj.val();
		if(!phone) {
			message = "请填写手机号";
			showErrmsg(phoneobj , message);
			return null;
		}
		if(phone.match(/^1\d{10}$/) == null) {
			message = "手机号格式错误";
			showErrmsg(phoneobj , message);
			return null;
		}		

        return phone;
    }

    function checkPasswd() {
        var message;

		var passwordobj = $("input[name=ppassword]");
		var password = passwordobj.val();
		if(!password) {
			message = "请填写密码";
			showErrmsg(passwordobj , message);
			return null;
		}
		if(password.length < 6 || password.length > 18) {
			message = "密码应为6-18位";
			showErrmsg(passwordobj , message);
			return null;
		}
        
        return password;
    }


    function showErrmsg(obj , message) {
        var text = obj.next();
        if(!JPlaceHolder.check()){ 
            text = obj.parent().next();
        }
        text.html('<img src="/image/main2/error.png"/>\
                <span>'+message+'</span>');
    }
    
    function logSuccess(data) {
        if(data == "1") {
            location.href = "/registerdetail.php";
        } else if(data == "2") {
            location.href = "/registerdetailafter.php";
        } else if(data == "3") {
            location.href = "/registertype.php";
        } else if(data == "4") {
            location.href = "/registeraudit.php";
        } else if(data == "5") {
            location.href = "/registerfailure.php";
        } else if(data == "6") {
            location.href = "/registersuccess.php";
        } else{
            location.href = "/main.php";        
        }
    }

    function logFail(errno, errmsg) {
        var msg = "";
        var loginbtn = $(".loginbtn");
        var msg = "";
        loginbtn.html("登录");
        loginbtn.prop("disabled" , false);
        loginbtn.attr("style","cursor:pointer");
        switch(errno) {
            case 400:
                msg = "请填写手机号、密码";
                break;
            case 4001:
                msg = "请填写&nbsp;11&nbsp;位手机号";
                break;
            case 403:
                msg = "手机号或密码不正确";
                break;
            case 404:
                msg = "您输入的手机号未注册<br/>\
                    请核对后<a href='register.php' >\
                    &nbsp;注册&nbsp;</a>或重新输入";
                break;
            case 500:
            case 5001:
            default:
                msg = "服务器异常";
                break;
        }

        $(".msg").show().html('<img src="/image/main2/error.png"/>\
                <span>'+msg+'</span>');
        util.goTop();
    }

    util.getkeydown($("input[name=ppassword]") , $(".loginbtn") );

    
});
