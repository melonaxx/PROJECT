//编辑按钮绑定
$(".edit").on("click" , function(){
	var obj = this.name;
	edit(obj);
});

//收起按钮绑定
$(".hidde").on("click" , function(){
	var obj = this.name;
	hide(obj);
});

//编辑
function edit(obj) {
	var ha = $(".h" + obj);
	ha.hide();
	var sa = $(".s" + obj);
	sa.show();
	if(obj == "mobileno") {
		var div1 = '<div class="userinput showk">';
		div1 += '<input type="text" class="form-control v' + obj + '" placeholder="请输入有效手机号"/></div>';
		div1 += '<div class="userinput capt">';
		div1 += '<input class="form-control inw fl" name="captcha" placeholder="验证码"/>';
		div1 += '<a href="javascript:void(0);"><img class="imgCaptcha" class="form-verify-captcha" src="/captcha.php?_=<?=time()?>" title="点击刷新"></a>';
		div1 += '</div>';
		div1 += '<div class="userinput showk">';
		div1 += '<input class="form-control inw fl" name="text" placeholder="动态码"/>';
		div1 += '<button class="MapSeaBtn inb fl">获取动态码</button></div>';
		div1 += '<div class="userinput">';
		div1 += '<button class="MapSeaBtn fl" name="' + obj + '" onclick="sub(this.name);">保存</button>';
		div1 += '<button class="MapSeaBtn fl" name="' + obj + '" onclick="hide(this.name);">取消</button></div>';
		sa.html(div1);

		$(".imgCaptcha").on("click", function() {
		    this.src = "/captcha.php?_=" + Math.random();
			$(".icaptcha").val("").focus();
		});

	}else if(obj == "domain") {
		if($(".h" + obj).val() == "未填写") {
			var name = $("." + obj).parent().parent().children('td:first').text();
			var div2 = '<div class="userinputd showk">';
			div2 += '<input type="text" class="fontrold v' + obj + '" placeholder="域名"/><div class="dol">.yun.waimaiw.com</div></div>';
			div2 += '<div class="userinputdd">';
			div2 += '<button class="MapSeaBtn fl" name="' + obj + '"  onclick="sub(this.name);">保存</button>';
			div2 += '<button class="MapSeaBtn fl" name="' + obj + '" onclick="hide(this.name);">取消</button></div>';
			sa.html(div2);
		}else {
			return false;
		}
		
	}else {
		var name = $("." + obj).parent().parent().children('td:first').text();
		var div2 = '<div class="userinput showk">';
		div2 += '<input type="text" class="form-control v' + obj + '" placeholder="请输入有效的' + name + '"/></div>';
		div2 += '<div class="userinput">';
		div2 += '<button class="MapSeaBtn fl" name="' + obj + '"  onclick="sub(this.name);">保存</button>';
		div2 += '<button class="MapSeaBtn fl" name="' + obj + '" onclick="hide(this.name);">取消</button></div>';
		sa.html(div2);
	}

	var hha = $("." + obj);
	hha.hide();
	var ssa = $(".u" + obj);
	ssa.show();
	var m = $(".m" + obj);
	if(m != []) {
		m.hide();
	}
}

//保存
function sub(obj) {
	var v = $(".v" + obj);
	val = v.val();

	var nu = val.match(/^\s*$/);
	if(nu !== null) {
		var msg = $(".msg" + obj + ">span>font");	
		msg.text("请填写");
		return false;
	}

	var p = false;
	var o = true;

	switch (obj) {
		case 'mobileno':
			p = val.match(/^\d{11}$/);
			if(p !== null) 
				p = true;
			break;
		case 'name':
			p = true;
			break;
		case 'email':
			p = val.match(/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/); 
				p = true;
			break;
		case 'company':
			p = true;
			break;
		case 'domain':
			p = val.match(/^\w+([-+.]\w+)*$/);
			if(p !== null)
				p = true;
			break;
		case 'logo':
			p = true;
			break;
		
		default:
			o = false;
			break;
	}

	if(o == false) {
		return false;
	}

	if(p == false || p == null) {
		var msg = $(".msg" + obj + ">span>font");	
		msg.text("格式错误");
		return false;
	}

	var url = "edituser.php";
	var data = "val=" + encodeURIComponent(val) + "&key=" + encodeURIComponent(obj);

	if(obj == "mobileno") {
		var captcha = $("input[name=captcha]").val();
		data += "&captcha=" + encodeURIComponent(captcha);
	}

	ajax(url , data , obj);
}

//收起
function hide(obj) {
	var ha = $(".h" + obj);
	ha.show();
	var sa = $(".s" + obj);
	sa.hide();
	var ssa = $(".u" + obj);
	ssa.hide();
	var hha = $("." + obj);
	hha.show();
	var v = $(".v" + obj);
	v.val("");
	var msg = $(".msg" + obj + ">span>font");
	msg.text("");
	var m = $(".m" + obj);
	if(m != []) {
		m.show();
	}
}

//修改密码按钮绑定
$(".sp").on("click" , function() {
	var obj = this.name;
	sp(obj);
})

//修改密码界面
function sp(obj) {
	var xp = $(".msg" + obj + ">span>font");
	xp.text("");

	$(".rank").css("background" , "#ccc");

	var val = $("." + obj).val("");
	var va = $(".a" + obj).val("");
	var sp = $(".h" + obj);
	sp.show();
}

//验证
function subp(obj) {
	var v = $("." + obj);
	var val = v.val();
	var msg = $(".msg" + obj + ">span>font");

	var p = val.match(/^\s*$/);
	if(p !== null) {
		msg.text("密码不能为空");
		return false;
	}
	if(val.length < 6 || val.length > 18) {
		msg.text("密码长度应为6-18位");
		return false;
	}

	var va = $(".a" + obj).val();
	if(va.match(/^\s*$/) !== null) {
		msg.text("请再输入一次密码");
		return false;
	}
	if(va !== val) {
		msg.text("两次密码不一致");
		return false;
	}

	$.ajax({
		url: "upasswd.php",
		data: "password=" + encodeURIComponent(val),
		dataType: "json",
		type: "post",
		success: function(result) {
		    if(result.state == false) {
            	var msg = $(".msg" + obj + ">span>font");	
				msg.text(result.msg);
            }
            if(result.state == true) {
            	alert("密码修改成功，请重新登录...");
            	location.href = "index.php";
            }
   
		}
	});
}

//密码收起
function hidep(obj) {
	var hp = $(".h" + obj);
	hp.hide();
}

//ajax
function ajax(url , data , obj) {
    return $.ajax({
        url: url, 
        cache: false,
        type: "post", 
        data: data,
        dataType: "json",
        success: function(result){
            if(result.state == false) {
            	var msg = $(".msg" + obj + ">span>font");	
				msg.text(result.msg);
            }
            if(result.state == true) {
            	location.href = "user.php";
            }
        }
    });
}