$(function($) {
    var DOMAIN = "1sheng.COM";

    $("#imgCaptcha").on("click", function() {
        this.src = "/captcha.php?_=" + Math.random();
        $("#inputVerifycode").val("").focus();
    });

    $("#btnLogin").on("click", function() {
        if ($(this).data("disabled")) {
            return;
        }

        var data = checkInput();

        if (!data) {
            return false;
        }

        // 防止多次点击
        $(this).text("登录中...").data("disabled", true);

        // 隐藏所有的错误提示
        hideAllErrmsg();

        util.ajax_post("/dologin.php", data, loginSuccess, loginFail, function() {
            $("#btnLogin").text("登录").removeData("disabled");
        });
    });

    $("#inputVerifycode").on("keydown", function(e) {
        var keyCode = e.keyCode;
        if (keyCode == 13) {
            $("#btnLogin").click();
            return false;
        }
    });

    function loginSuccess(data) {
        // 登录成功，跳转到首页
        window.location.href = "/index.php";
    }

    function loginFail(errno, errmsg) {
        $("#imgCaptcha").attr("src", "/captcha.php?_=" + Math.random());
        $("#inputVerifycode").val("");

        if (errno < 0) {
            showErrmsg("网络连接失败，请检查网络");
        } else {
            var showMsg = "";
            switch(errno) {
                case 400:
                    showMsg = "参数不合法";
                    break;
                case 40301:
                    showMsg = "验证码输入错误，请重新输入";
                    $("#inputVerifycode").focus().closest(".form-group").addClass("has-error");
                    break;
                case 404:
                case 403:
                    showMsg = "用户不存在/密码错误，请重新输入";
                    break;
                case 500:
                    showMsg = "服务器错误";
                    break;
                case 4041:
                    showMsg = "用户被停用";
                    break;
                default:
                    showMsg = errmsg;
                    break;
            }

            showErrmsg(showMsg);
        }
    }

    function checkInput() {
        var nameInput = $("#inputName"), name = $.trim(nameInput.val());
        if (!name) {
            nameInput.closest(".form-group").addClass("has-error");
            nameInput.select();
            return null;
        }

        var passwdInput = $("#inputPassword"), passwd = $.trim(passwdInput.val());
        if (!passwd) {
            passwdInput.closest(".form-group").addClass("has-error");
            passwdInput.select();
            return null;
        }
        passwd = hex_md5(passwd + "@" + DOMAIN);      // 不要明文传送密码

        var captchaInput = $("#inputVerifycode"), captcha = $.trim(captchaInput.val());
        if (!captcha) {
            captchaInput.closest(".form-group").addClass("has-error");
            captchaInput.select();
            return null;
        }

        return {
            name: name,
            pwd: passwd,
            c: captcha
        };
    }

    function showErrmsg(msg) {
        var errmsg_container = $(".alert-danger"), errmsg = $("#errmsg");

        errmsg_container.show();
        errmsg.text(msg);
    }

    function hideAllErrmsg() {
        var errmsg_container = $(".alert-danger"), errmsg = $("#errmsg");

        errmsg_container.hide();
        errmsg.text("");

        $(".has-error").removeClass("has-error");
    }
});
