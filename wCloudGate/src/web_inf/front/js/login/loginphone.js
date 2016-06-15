$(function($) {

	$(".imgCaptcha").on("click", function() {
        this.src = "/captcha.php?l=loginphone&_=" + Math.random();
        $("input[name=captcha]").val("").focus();
        return false;
    });

	$(".login_phone input").on("focus" , function() {
        var $this = $(this);
        var text = $this.parent().next();
        if(!JPlaceHolder.check()){ 
            text = $this.parent().parent().next();

        }
        if($this.attr("name") == "captcha") {
            $(".dynamiccode").css("margin-top" , "-4px");
		}
        text.html("");
    });

	$(".loginphonebtn").on("click" , function() {
        var $this = $(this);

		var phone = checkMobile();
		if(!phone) {
			return false;
		}

        var smscode = checkSmscode();
        if (!smscode) {
            return false;
        }
		var times = $("input[name=checkbox2]").prop("checked") ? 1 : 0;

        var data= {
            phone: phone ,
            smscode: smscode ,
            times: times
        };

        $this.html("登录中...");
        $this.prop("disabled" , true);
        $this.attr("style" , "cursor:no-drop");
		util.ajax_post("/dologinphone.php" , data , logSuccess, logFail);
	    return false;
    });

	$(".smscodebtn").on("click" , function() {
        var $this = $(this);
        
		var phone = checkMobile();
		if(!phone) {
			return false;
		}

		var captcha = checkCaptcha();
		if(!captcha) {
			return false;
		}

        var data= {
            phone: phone ,
            captcha: captcha
        };

        $this.html("发送中...");
        $this.prop("disabled" , true);
        $this.attr("style" , "cursor:no-drop");
        util.ajax_post("dologinphonesmscode.php" , data , getSmscodeSuccess, getSmscodeFail);
        return false;
    });

    function checkMobile() {
        var message;

		var phoneobj = $("input[name=phone]");
		var phone = phoneobj.val();
		if(!phone) {
			message = "请填写手机号";
			showErrmsg(phoneobj , message);
			return null;
		}
		if(phone.match(/^1\d{10}$/) == null) {
			message = "手机号格式错误";
			showErrmsg(phoneobj , message);
			return null;;
		}		

        return phone;
    }

    function checkCaptcha() {
        var message;

		var captchaobj = $("input[name=captcha]");
		var captcha = captchaobj.val();
		if(!captcha) {
			message = "请填写验证码";
			showErrmsg(captchaobj , message);
            $(".dynamiccode").css("margin-top" , "10px");
			return null;
		}

        $(".dynamiccode").css("margin-top" , "-4px");
        return captcha;
    }

    function checkSmscode() {
        var message;

		var smscodeobj = $("input[name=smscode]");
		var smscode = smscodeobj.val();
		if(!smscode) {
			message = "请填写手机动态码";
			showErrmsg(smscodeobj , message);
			return null;
		}		
		if(smscode.match(/^\d{4}$/) == null) {
			message = "手机动态码格式错误";
	    }

        return smscode;
    }

    function showErrmsg(obj , message) {
        var text = obj.parent().next();
        if(!JPlaceHolder.check()){ 
            text = obj.parent().parent().next();
        }
        text.html('<img src="/image/main2/error.png"/>\
                <span>'+message+'</span>');
    }

    function logSuccess(data) {
        if (!data) {
            location.href = "/main.php";
            return;
        }

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
        } else if(data == "7") {
            location.href = "/carmanagement.php";
        } else {
            location.href = "/main.php";
        }
    }

    function logFail(errno, errmsg) {
        var msg;        
        var loginbtn = $(".loginphonebtn");
        loginbtn.html("登录");
        loginbtn.prop("disabled" , false);
        loginbtn.attr("style" , "cursor:pointer");
        switch(errno) {
            case 400:
                msg = "请填写手机号和短信动态码";
                break;
            case 4001:
                msg = "请填写&nbsp;11&nbsp;位手机号";
                break;
            case 4002:
                msg = "请填写短信动态码";
                break;
            case 403:
                msg = "手机号或短信动态码不正确";
                break;
            case 404:
                msg = "您输入的手机号未注册，\
                    请核对后<a href='register.php' >\
                    &nbsp;注册&nbsp;</a>或重新输入";
                break;
            case 500:
            case 5001:
            default:
                msg = "服务器内部错误";
                break;
        }

        $(".msg").show().html('<img src="/image/main2/error.png"/>\
                <span>'+msg+'</span>');
        util.goTop();
    }

    function getSmscodeSuccess(data) {
        var smscodebtn = $(".smscodebtn");
        $(".codetimes").show();
        smscodebtn.html("获取动态码");

        var begin = null, time = 60, nums = $(".nums");
        nums.text(time);

        begin = setInterval(function() {
            if(time < 1) {
                clearInterval(begin);
                $(".codetimes").hide();                
                smscodebtn.prop("disabled" , false);
                smscodebtn.attr("style","cursor:pointer");
            } else {
                nums.text(--time);
            }
        } , 1000);
    }

    function getSmscodeFail(errno, errmsg) {
        var smscodebtn = $(".smscodebtn");
        var msg = "";
        smscodebtn.html("获取动态码");
        smscodebtn.prop("disabled" , false);
        smscodebtn.attr("style","cursor:pointer");
        $(".dynamiccode").css("margin-top" , "-4px");
        switch(errno) {
            case 400:
                msg = "请填写手机号、验证码";
                break;
            case 4001:
                msg = "请填写手机号";
                break;
            case 4002:
                msg = "请填写图片验证码";
                break;
            case 4003:
                msg = "输入的图片验证码不正确";
                break;
            case 404:
                msg = "您输入的手机号未注册，\
                    请核对后<a href='register.php' >\
                    &nbsp;注册&nbsp;</a>或重新输入";
                break;
            case 5001:
                msg = "短信发送失败";
                break;
            case 500:
            default:
                msg = "服务器内部错误";
                break;
        }
        $(".codetimes").hide();
        $(".msg").show().html('<img src="/image/main2/error.png"/>\
                <span>'+msg+'</span>');
        util.goTop();
    }

    util.getkeydown( $("input[name=smscode]") , $(".loginphonebtn") );
});
