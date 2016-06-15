$(function($) {

    $(".imgCaptcha").on("click", function() {
        this.src = "/captcha.php?l=register&_=" + Math.random();
        $("input[name=captcha]").val("").focus();
        return false;
    });

    $(".regbtn").on("click" , function() {
        var $this = $(this);
        var data = checkInput();
        if (!data) {
            return false;
        }

        $this.html("注册中...");
        $this.prop("disabled" , true);
        $this.attr("style" , "cursor:no-drop");
        util.ajax_post("/doregister.php" , data , regSuccess, regFail);

        return false;
    });

    $("input").on("focus" , function() {
        var $this = $(this);
        var text = $this.parent().next(".errmsg:first");
        if(!JPlaceHolder.check()){
            text = $this.parent().parent().next(".errmsg:first");
        }

        text.html("");
        if($this.attr("name") == "captcha") {
            $(".dynamiccode").css("margin-top" , 0);
        }
    }).on("blur", function() {
        var name = $(this).attr("name");

        switch(name) {
            case "phone":
                checkMobile();
                break;
            case "captcha":
                checkCaptch();
                break;
            case "smscode":
                checkSmscode();
                break;
            case "password":
                checkPasswd();
                break;
            case "repassword":
                checkRepasswd();
                break;
            default:
                break;
        }
    });

    $("input[name=repassword]").on("keyup" , function() {
        var p = $("input[name=password]").val();
        var re = $(this).val();

        if(p) {	
            var msg ;
            var text = $(this).parent().nextAll(".errmsg:first");				
            if(p !== re) {
                msg = "两次密码输入不一致";
                text.html('<img src="/image/main2/error.png"/>\
                <span>'+msg+'</span>');
            }else {
                msg = "";
                text.html('');
            }
        }
    }).on("click", function(e) {
        var keycode = e.keyCode;
        if (keycode == 13) {
            $(".regbtn").click();
            return false;
        }
    });

    $(".smscodebtn").on("click" , function() {

        var $this = $(this);

        var phone = checkMobile();
        if(!phone) {
            return false;
        }

        var captcha = checkCaptch();
        if(!captcha) {
            return false;
        }

        var data = {
            phone: phone ,
            captcha: captcha
        };

        $this.html("发送中...");
        $this.prop("disabled" , true);
        $this.attr("style" , "cursor:no-drop");

        util.ajax_post("doregistersmscode.php" , data , regSmscodeSuccess, regSmscodeFail);
        return false;
    });

    function checkMobile() {
        var message = "";

        var phoneobj = $("input[name=phone]");
        var phone = phoneobj.val();
        if(!phone) {
            message = "请填写手机号";
            showErrmsg(phoneobj , message);
            return null;
        }
        if(!/^1\d{10}$/.test(phone)) {
            message = "请输入正确的手机号码";
            showErrmsg(phoneobj , message);
            return null;;
        }		

        return phone;
    }

    function checkCaptch() {
        var message = "";

        var captchaobj = $("input[name=captcha]");
        var captcha = captchaobj.val();
        if(!captcha) {
            message = "请填写验证码";
            showErrmsg(captchaobj , message);
            $(".dynamiccode").css("margin-top" , "10px");
            return null;
        }

        $(".dynamiccode").css("margin-top" , 0);
        return captcha;
    }

    function checkSmscode() {
        var message = "";

        var smscodeobj = $("input[name=smscode]");
        var smscode = smscodeobj.val();
        if(!smscode) {
            message = "请填写手机动态码";
            showErrmsg(smscodeobj, message);
            return null;
        }		

        return smscode;
    }

    function checkPasswd() {
        var message = "";

        var passwordobj = $("input[name=password]");
        var password = passwordobj.val();
        if(!password) {
            message = "请填写密码";
            showErrmsg(passwordobj, message);
            return null;
        }
        if(password.length < 6 || password.length > 18) {
            message = "密码应为6-18位";
            showErrmsg(passwordobj, message);
            return null;
        }

        return password;
    }

    function checkRepasswd(password) {
        var message = "";

        password = password || $("input[name=password]").val();

        var repasswordobj = $("input[name=repassword]");
        var repassword = repasswordobj.val();
        if(!repassword) {
            message = "请再输入一次密码";
            showErrmsg(repasswordobj, message);
            return null;
        }
        if(repassword !== password) {
            message = "两次密码不一致";
            showErrmsg(repasswordobj, message);
            return null;
        }

        return repassword;
    }

    function checkInput() {
        var phone = checkMobile();
        if(!phone) {
            return null;
        }

        var captcha = checkCaptch();
        if(!captcha) {
            return null;
        }

        var smscode = checkSmscode();
        if(!smscode) {
            return null;
        }

        var password = checkPasswd();
        if(!password) {
            return null;
        }

        var repassword = checkRepasswd(password);
        if(!repassword) {
            return null;
        }

        return {
            phone: phone ,
            password: password ,
            repassword: repassword ,
            captcha: captcha ,
            smscode: smscode
        };
    }

    function regFail(errno, errmsg) {
        var regbtn = $(".regbtn");
        var msg = null;
        regbtn.html("同意以下协议并注册");
        regbtn.prop("disabled" , false);
        regbtn.attr("style","cursor:pointer");
        switch(errno) {
            case 400:
                msg = "请完善注册信息";
                break;
            case 4001:
                msg = "请填写&nbsp;11&nbsp;位手机号";
                break;
            case 4002:
                msg = "请填写&nbsp;4&nbsp;位短信动态码";
                break;
            case 4003:
                msg = "密码长度应为&nbsp;6-18&nbsp;位";
                break;
            case 4004:
                msg = "两次密码输入不一致";
                break;
            case 403:
                msg = "手机号或短信动态码不正确";
                break;
            case 500:
                msg = "服务内部错误";
                break;            
            case 4005:
                msg = "服务错误";
                break;
            case 5001:
                msg = "内部错误";
                break;
            default:
                msg = "注册失败";
                // msg = "您输入的手机号已注册，\
                    //请核对后<a href='login.php' >\
                    //&nbsp;登录&nbsp;</a>或重新输入";
                break;
        }
        
        $(".msg").html('<img src="/image/main2/error.png"/>\
                <span>'+msg+'</span>').show();
        util.goTop();
    }

    function regSuccess(data) {
        window.location.href = "/registerdetail.php";
    }

    function showErrmsg(obj , message) {
        var text = obj.parent().next(".errmsg:first");
        if(!JPlaceHolder.check()){            
            text = obj.parent().parent().next(".errmsg:first");
        }

        text.html('<img src="/image/main2/error.png"/>\
                <span>'+message+'</span>');
    }

    function regSmscodeSuccess(data) {
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

    function regSmscodeFail(errno, errmsg) {
        var smscodebtn = $(".smscodebtn");
        var msg = "";
        $(".msg").show();
        $(".codetimes").hide();
        smscodebtn.html("获取动态码");
        smscodebtn.prop("disabled" , false);
        smscodebtn.attr("style","cursor:pointer");
        $(".dynamiccode").css("margin-top" , "10px");
        switch(errno) {
            case 400:
                msg = "请填写手机号和图片验证码";
                $(".msg").html('<img src="/image/main2/error.png"/>\
                <span>'+msg+'</span>');
                break;
            case 4001:
                var phoneobj = $("input[name=phone]");
                msg = "请填写手机号";
                showErrmsg(phoneobj, msg);
                break;
            case 4002:
                msg = "请填写图片验证码";
                showErrmsg($("input[name=captcha]"), msg);
                break;
            case 4003:
                msg = "输入的验证码不正确";
                showErrmsg($("input[name=captcha]"), msg);
                $(".dynamiccode").css("margin-top" , "10px");
                break;
            case 5001:
                msg = "短信发送失败";
                $(".msg").html('<img src="/image/main2/error.png"/>\
                <span>'+msg+'</span>');
                break;
            case 500:
            default:
                msg = "服务器异常";
                $(".msg").html('<img src="/image/main2/error.png"/>\
                <span>'+msg+'</span>');
                break;
            
        }
    }

    util.getkeydown($("input[name=repassword]") , $(".regbtn") );

});
