$(function($) {

	$(".smscodebtn").on("click" , function() {

		var message;

		var phoneobj = $("input[name=phone]");
		var phone = phoneobj.val();
		if(phone.match(/^\s*$/) !== null) {
			message = "请填写手机号";
			msg(phoneobj , message);
			return false;
		}
		if(phone.match(/^1\d{10}$/) == null) {
			message = "手机号格式错误";
			msg(phoneobj , message);
			return false;;
		}		

		var captchaobj = $("input[name=captcha]");
		var captcha = captchaobj.val();
		if(captcha.match(/^\s*$/) !== null) {
			message = "请填写验证码";
			msg(captchaobj , message);
			return false;
		}
		if(captcha.match(/^[0-9a-zA-Z]{4}$/) == null) {
			message = "验证码格式错误";
			msg(captchaobj , message);
			return false;;
		}

        var data= {
                phone: phone ,
                captcha: captcha 
            };

        util.ajax_post("dologinphonesmscode.php" , data , loginsmscodesuccess);

        function loginsmscodesuccess(result) {
            var msg;
            if(result.errno == "400") {
                msg = "请填写手机号、验证码";
                $(".msg").html(msg);
                return;
            }           
            if(result.errno == "4001") {
                msg = "请填写&nbsp;11&nbsp;位手机号";
                var phoneobj = $("input[name=phone]");
                message = "请填写手机号";
                msg(phoneobj , msg);
                return;
            }           
            if(result.errno == "4002") {
                msg = "请填写&nbsp;4&nbsp;位验证码";
                $(".msg").html(msg);
                return;
            }            
            if(result.errno == "4003") {
                msg = "输入的验证码不正确";
                $(".msg").html(msg);
                $(".imgCaptcha").attr("src" , "/captcha.php?l=loginphone&_=" + Math.random());
                $("input[name=captcha]").val("");
                return;
            }
            if(result.errno == "404") {
                msg = "您输入的手机号未注册，\
                请核对后<a href='register.php' >\
                &nbsp;注册&nbsp;</a>或重新输入";
                $(".msg").html(msg);
                return;
            }
            if(result.errno == "500") {
                msg = "服务器异常";
                $(".msg").html(msg);
                return;
            }            
            if(result.errno == "5001") {
                msg = "短信发送失败";
                $(".msg").html(msg);
                return;
            }
            if(result.errno == 0) {
                $(".smscodebtn").hide();
                $(".codetimes").show();
                $(".codetimes").css("color" , "#666");
                var begin = null;
                var time = 60;
                begin = setInterval(function() {
                    if(time == 1) {
                        clearInterval(begin);
                        time = 60;
                        $(".codetimes").hide();
                        $(".codetimes").css("color" , "#666");
                        $(".smscodebtn").show();
                    }
                    time--;
                    $(".nums").text(time);
                } , 1000);
                return;
            }     

        }


    });

    function msg(obj , message) {
        var text = obj.parent().next();
        if(obj.attr("name") == "password"){
            text = obj.parent().next().next();
        }
        text.removeClass("hide-content1");
        text.addClass("hide-content");
        text.html(message);
        return ;
    }

    $("input").on("focus" , function() {
        var text = $(this).parent().next();
        if($(this).attr("name") == "password"){
            text = $(this).parent().next().next();
        }
        text.removeClass("hide-content");
        text.addClass("hide-content1");
        text.html("");
    });

});