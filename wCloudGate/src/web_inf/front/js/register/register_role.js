$(function($) {	

	$(".authenticatedbtn").on("click" , function() {
		var $this = $(this);
        var data = checkInput();
        if (!data) {
            return false;
        }

        $this.html("提交中...");
        $this.prop("disabled" , true);
        $this.attr("style","cursor:no-drop");
		util.ajax_post("/doregistertype.php" , data , typesuccess , typefail);
		
		return false;
	});

	function checkName() {
		var message;

		var nameobj = $("input[name=name]");
		var name = nameobj.val();
		if(name.match(/^s*$/) !== null) {
			message = "请输入汉字";
			showErrmsg(nameobj , message);
			return false;
		}
		if(name.match(/^[\u4E00-\u9FA5\uF900-\uFA2D]{3,}$/) == null) {
			message = "公司名称格式错误";
			showErrmsg(nameobj , message);
			return false;
		}

		return name;
	}

		
	function checkCity() {
		var message;

		var cityobj = $("select[name=city]");
		var city = $("select[name=city] option:selected").val();
		if(city == "-请选择城市-") {
			message = "请选择省市";
			showErrmsg(cityobj , message);
			return false;
		}

		return city;
	}

	function checkLinkman() {
		var message;

		var linkmanobj = $("input[name=linkman]");
		var linkman = linkmanobj.val();
		if(linkman.match(/^\s*$/) !== null) {
			message = "请输入姓名可以用汉字、英文或数字";
			showErrmsg(linkmanobj , message);
			return false;
		}
		if(linkman.match(/^[\u4E00-\u9FA5\uF900-\uFA2Da-zA-Z0-9]{1,}$/) == null) {
			message = "姓名格式错误";
			showErrmsg(linkmanobj , message);
			return false;;
		}
		
		return linkman;
	}

	function checkPhone() {
		var message;

		var phoneobj = $("input[name=phone]");
		var phone = phoneobj.val();
		if(phone.match(/^\s*$/) !== null) {
			message = "请输入&nbsp;11&nbsp;手机号";
			showErrmsg(phoneobj , message);
			return false;
		}
		if(phone.match(/^1\d{10}$/) == null) {
			message = "手机号格式错误";
			showErrmsg(phoneobj , message);
			return false;
		}

		return phone;
	}

	function checkEmail() {
		var message;

		var emailobj = $("input[name=email]");
		var email = emailobj.val();
		if(email.match(/^\s*$/) == null) {
			if(email.match(/^\w+([-+.]\w+)*@\w+([-.]\w+)*\.\w+([-.]\w+)*$/) == null) {
				message = "邮箱格式错误";
				showErrmsg(emailobj , message);
				return false;
			}
		}

		return email;
	}	

	function checkImgname() {
		var message;

		var imgnameobj = $("input[name=imgname]");
		var imgname = imgnameobj.val();
		if(imgname.match(/^\s*$/) !== null) {
			message = "请上传营业执照";
			showErrmsg(imgnameobj , message);
			return false;
		}
		
		return imgname;
	}	

	function checkLicence() {
		var message;

		var licenceobj = $("input[name=licence]");
		var licence = licenceobj.val();
		if(licence.match(/^\s*$/) !== null) {
			message = "请输入营业执照注册号";
			showErrmsg(licenceobj , message);
			return false;
		}
		if(licence.match(/^[^\s]{2,30}$/) == null) {
			message = "请输入营业执照注册号";
			showErrmsg(licenceobj , message);
			return false;
		}
		
		return licence;
	}

	function checkInput() {
        var name = checkName();
        if(!name) {
            return null;
        }        

        var city = checkCity();
        if(!city) {
            return null;
        }

        var linkman = checkLinkman();
        if(!linkman) {
            return null;
        }

        var phone = checkPhone();
        if(!phone) {
            return null;
        }

        var email = checkEmail();
        if(typeof email == "string") {
	        if(email.match(/^\s*$/) == null) {
		        if(!email) {
		            return null;
		        }
	        }
        }else{
        	return null;
        }

		var imgname = checkImgname();
        if(!imgname) {
            return null;
        }		

        var licence = checkLicence();
        if(!licence) {
            return null;
        }

		var typeobj = $(".formtop").children();
		var type;
		$.each(typeobj , function(i) {
			if($(this).hasClass("active")){
				type = i+1;
			}
		});

		return {
			name: name ,
			city: city ,
			linkman: linkman ,
			phone: phone ,
			email: email ,
			imgname: imgname ,
			licence: licence ,
			type: type,
		};
	}

	function showErrmsg(obj , message) {
        var text = obj.next();
        text.html('<img src="/image/main2/error.png"/>\
                <span>'+message+'</span>');
    }

	$(".province").on("focus" , function() {
		var text = $(this).next().next();
		text.html("");
	}).on("blur" , function() {
		checkCity();
	});

	$(".city").on("focus" , function() {
		var text = $(this).next();
		text.html("");
	}).on("blur" , function() {
		checkCity();
	});

	$("input").on("focus" , function() {
		var text = $(this).next();
		text.html("");
	}).on("blur" , function() {
        var name = $(this).attr("name");
        switch(name) {
            case "name":
                checkName();
                break;
            case "linkman":
                checkLinkman();
                break;            
            case "phone":
                checkPhone();
                break;
            case "email":
                checkEmail();
                break;
            case "licence":
                checkLicence();
                break;
            default:
                break;
        }
    });

	function typefail(errno , errmsg) {
		var msg = "";

		var btn = $("button[name=btn]");
		btn.html("提交审核");
        btn.prop("disabled" , false);
        btn.attr("style","cursor:pointer");
        switch(errno) {
            case 400:
                msg = "请完善认证信息";
                break;
            case 4001:
                msg = "请选择省份、城市";
                break;
			case 4002:
				msg = "请填写公司名称只能使用汉字";
				break;
			case 4003:
				msg = "请填写负责人姓名可以用汉字、英文或数字";
				break;
			case 4004:
				msg = "请填写&nbsp;11&nbsp;位手机号";
				break;
			case 4005:
				msg = "邮箱格式错误";
				break;
			case 4006:
				msg = "没有找到公司类型";
				break;
			case 4007:
				msg = "请填写营业执照";
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

	function typesuccess(data) {
		location.href = "/registeraudit.php";
	}

	$(".city").on("click" , function() {
		var text = $(this).parent().next();
		text.html("");		
	});

	$(".province").on("click" , function() {

		var text = $(this).parent().next();
		text.html("");

		var city = $(".province option:selected").attr("name");
		if(typeof(city) == "undefined"){
			var check = "<option>-请选择城市-</option>";
			$(".city").first().html(check);
			return false;
		}else{
			var data = {city: city};
			util.ajax_post("/registerselected.php" , data , selectedsuccess);
		}
		
	});

	function selectedsuccess(data) {
		var check = "<option>-请选择城市-</option>";
		$(".city").first().html(check);
		var city = "";
		var msg = data;
		$.each(msg , function(i , v) {
			city += "<option name='" + i + "'>" + v + "</option>";
		});
		$(".city").first().append(city);		
	}

	$(".p").on("click" , function() {
		$(".d").removeClass("active");
		$(".l").removeClass("active");		
		$(this).addClass("active");
	});	

	$(".d").on("click" , function() {
		$(".p").removeClass("active");
		$(".l").removeClass("active");
		$(this).addClass("active");
	});	

	$(".l").on("click" , function() {
		$(".p").removeClass("active");
		$(".d").removeClass("active");
		$(this).addClass("active");
	});
});