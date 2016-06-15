$(function() {

	$(".nav li:eq(2)").addClass("navactive");
	// 左侧列表二级菜单的显示隐藏
    $(".wllevel1").hover(
        function(){
            $(this).find(".wllevel2").show();
        },
        function(){
            $(this).find(".wllevel2").hide();
    });

    
    $(".caraddlist").on("click" , ".caradd" , function() {
    	var $this = $(this);

    	var carnum = $(".caraddlist li").length;
    	if(carnum > 4 ) {
    		$(".msg").html("最多添加五条车辆信息");
    		$this.addClass("cardel").removeClass("caradd").html("删除");
    		return false;
    	}

		$this.addClass("cardel").removeClass("caradd").html("删除");

    	var carli = "";
		carli += '<li>\
					<h2>1</h2>\
					<div class="carinf">\
						<div class="input-groupc">\
							<label>车辆品牌&nbsp;:&nbsp;&nbsp;</label>\
							<input type="text" />\
						</div>\
						<div class="input-groupc">\
							<label>车辆号码&nbsp;:&nbsp;&nbsp;</label>\
							<input type="text" />\
						</div>\
						<div class="input-groupc">\
							<label>定位器号&nbsp;:&nbsp;&nbsp;</label>\
							<input type="text" />\
						</div>\
						<div class="input-groupc">\
							<label>车辆型号&nbsp;:&nbsp;&nbsp;</label>\
							<input type="text" />\
						</div>\
						<div class="input-groupc">\
							<label>车辆备注&nbsp;:&nbsp;&nbsp;</label>\
							<input type="text" />\
						</div>\
					</div>\
					<a class="operate caradd">继续添加</a>\
				</li>';
		$this.parent().parent().append(carli);

		showkptable();
	});

    $(".caraddlist").on("click" , ".cardel" , function() {
    	$(".msg").html("");	
    	var $this = $(this);
    	var lidel = $this.parent();
    	lidel.remove("li");
		showkptable();
		var carnum = $(".caraddlist li").length;
		if(carnum == 4 ) {
    		$(".caraddlist").find("li:last a").addClass("caradd").removeClass("cardel").html("继续添加");
    		return false; 
    	}
    });

	function showkptable() {
		var std = $(".caraddlist li").children("h2");
		var j = 1;
		$.each(std , function() {
			$(this).html(j);
			j++;
		});
	}

	var flag = false; 
	$(".addcarbtn").on("click" , function() {
		var input,
			$input,
			data  = {},
			strig = "",
			eq0 = "",
			eq1 = "",
			eq3 = "",
			state = false,
			brand = [],
			seqno = [],
			senso = [],
			model = [],
			commt = []
		;
		input = $(".caraddlist").find("li");
		$.each(input , function() {
			var $this = $(this);

			eq0 = $this.find("input:eq(0)").val();
			state = checknull(eq0);
			if(!state ) {
				showmsg();
				return false;
			}
			brand.push(eq0);			

			eq1 = $this.find("input:eq(1)").val();
			state = checknull(eq1);
			if(!state ) {
				showmsg();
				return false;
			}
			seqno.push(eq1);			

			eq3 = $this.find("input:eq(3)").val();
			state = checknull(eq3);
			if(!state ) {
				showmsg();
				return false;
			}
			model.push(eq3);

			senso.push($this.find("input:eq(2)").val());
			commt.push($this.find("input:eq(4)").val());
		});

		if(!state ) {
			showmsg();
			return false;
		}

		data = {brand: brand , seqno: seqno , senso: senso , model: model , commt: commt};
		strig = "string=" + encodeURIComponent(JSON.stringify(data));
		
		if(flag) {
			return false;
		}
		
		flag = true;
		util.ajax_post("/docaradd.php" , strig , addsuccess , addfail);
	});

	function addsuccess() {
		$(".modal").show();
	}

	$(".goaddcar").on("click" , function() {
		location.href = "/caronceadd.php";
	});
	$(".carmanagement").on("click" , function() {
		location.href = "/platform/carmanagement.php";
	});

	function addfail(errno , errmsg) {
		var msg = "";
		flag = false;
		if(errno == 404) {
			msg = "请完善车辆品牌、车辆号码、车辆型号信息";
		}
		$(".msg").show().html(msg);
	}

	function checknull(str) {
		if(/^\s*$/.test(str)) {
			return false;
		}
		return true;
	}

	function showmsg() {
		$(".msg").show().html("请完善车辆品牌,车辆号码,车辆型号信息");
	}

	$(".caraddlist").on("focus" , "input" , function() {
		$(".msg").hide();
	});


});