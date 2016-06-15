$(function($) {

	$("button[name=btn]").on("click" , function() {
		var $this = $(this);
        var data = checkInput();
        if (!data) {
            return false;
        }

        $this.html("提交中...");
        $this.prop("disabled" , true);
        $this.attr("style","cursor:no-drop");
        util.ajax_post("/doregisterdetail.php" , data , detailsuccess , detailfail);

        return false;
    });

    function checkInput() {
        var name = checkName();
        if(!name) {
            return null;
        }

        var email = checkEmail();
        if(!email) {
            return null;
        }

        // var qq = checkQQ();
        // if(typeof qq == "string") {
	       //  if(qq.match(/^\s*$/) == null) {
		      //   if(!qq) {
		      //       return null;
		      //   }
	       //  }
        // }else{
        // 	return null;
        // }

        // var wechat = checkWechat();
        // if(typeof wechat == "string") {
	       //  if(wechat.match(/^\s*$/) == null) {
		      //   if(!wechat) {
		      //       return null;
		      //   }
	       //  }
        // }else{
        // 	return null;
        // }

        return {
            name: name ,
			email: email 
			// ,
			// qq: qq ,
			// wechat: wechat
        };
    }

    function checkName() {
    	var message = "";

    	var nameobj = $("input[name=name]");
		var name = nameobj.val();
		if(name.match(/^\s*$/) !== null) {
			message = "请输入姓名可以用汉字、英文或数字";
			showErrmsg(nameobj , message);
			return null;
		}
		if(name.match(/^[\u4E00-\u9FA5\uF900-\uFA2Da-zA-Z0-9]{1,}$/) == null) {
			message = "姓名格式错误";
			showErrmsg(nameobj , message);
			return null;
		}		

		return name;
    }

    function checkEmail() {
    	var message = "";

		var emailobj = $("input[name=email]");
		var email = emailobj.val();
		if(email.match(/^\s*$/) !== null) {
			message = "请输入邮箱";
			showErrmsg(emailobj , message);
			return false;
		}
		if(email.length > 21) {
			message = "邮箱不能超过20个字符";
			showErrmsg(emailobj , message);
			return false;
		}
		if(email.match(/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/) == null) {
			message = "邮箱格式错误";
			showErrmsg(emailobj , message);
			return false;
		}

    	return email;
    }

  //   function checkQQ() {
		// var message = "";

		// var qqobj = $("input[name=qq]");
		// var qq = qqobj.val() ;
		// if(qq.match(/^\s*$/) == null) {
		// 	if(qq.match(/^[1-9]\d{2,18}$/) == null) {
		// 		message = "qq号码格式错误";
		// 		showErrmsg(qqobj , message);
		// 		return false;
		// 	}
		// }

		// return qq;
  //   }
    
  //   function checkWechat() {
  //   	var message = "";

  //   	var wechatobj = $("input[name=wechat]");
		// var wechat = wechatobj.val() ;
  //   	if(wechat.match(/^\s*$/) == null) {
		// 	if(wechat.match(/^[a-zA-Z\d_]{3,}$/) == null) {
		// 		message = "微信格式错误";
		// 		showErrmsg(wechatobj , message);
		// 		return false;
		// 	}
		// }

  //   	return wechat;
  //   }

    function showErrmsg(obj , message) {
        var text = obj.next();
        text.html('<img src="/image/main2/error.png"/>\
                <span>'+message+'</span>');
    }

	function detailfail(errno , errmsg) {
		var msg = '';

		var btn = $("button[name=btn]");
		btn.html("下一步");
        btn.prop("disabled" , false);
        btn.attr("style","cursor:pointer");
		switch(errno) {
			case 400:
				msg = "请完善用户信息";
				break;
			case 4001:
				msg = "请填写姓名可以用汉字、英文或数字";
				break;
			case 4002:
				msg = "请填写邮箱";
				break;
			case 4003:
				msg = "qq格式错误";
				break;
			case 4004:
				msg = "微信格式错误";
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
	
	function detailsuccess(data) {
		location.href = "/registerdetailafter.php";
	}

	$("input").on("focus" , function() {
		var text = $(this).next();
		text.html("");
	}).on("blur", function() {
        var name = $(this).attr("name");

        switch(name) {
            case "name":
                checkName();
                break;
            case "email":
                checkEmail();
                break;
            default:
                break;
        }
    });


});