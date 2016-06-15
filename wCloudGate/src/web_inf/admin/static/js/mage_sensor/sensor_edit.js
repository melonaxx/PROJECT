//修改信息
$(".btn").on("click", function() {

	var name = this.name;
	var self = $(this);
	if(name == "ebike") {

		var div1 = '<span class="tit">电动车修改</span>';
		div1 += '<div class="sond"><div class="sonh">';
		div1 += '<div class="soni">电动车</div>';
		div1 += '<select class="sell">';
		div1 += '<option>全部电动车</option><option>单辆电动车</option></select>';
		div1 += '</div><div class="sonhl">';
		div1 += '</div></div>';
		div1 += '<div class="modal-footer"><div class="nmsg"></div>';
		div1 += '<a href="" class="btn btn-danger" data-action="1">取消</a>';
		div1 += '<a href="" class="btn bg-primary sdd second" data-dismiss="modal">确定</a></div>';

	}else if(name == "second"){

		var editname = $(this).parent().children("font:first").text();
		var div1 = '<span class="tit">修改</span>';
		div1 += '<div class="sond"><div class="sonhf">';
		div1 += '<div class="freequency"><span class="fr">' + editname + '</span></div>';
		div1 += '<input class="sell sellw vsecond" type="text" value="" />';
		div1 += '<div class="stim">秒/次</div>';
		div1 += '<span class="addid"></span></div></div>';
		div1 += '<div class="modal-footer"><div class="emsg"></div>';
		div1 += '<a href="" class="btn btn-danger" data-action="1">取消</a>';
		div1 += '<a href="" class="btn bg-primary sdd second" data-dismiss="modal">确定</a></div>';

	}else if(name == "wi"){

		var editname = $(this).parent().children("font:first").text();
		var div1 = '<span class="tit">修改</span>';
		div1 += '<div class="sond"><div class="sonhf">';
		div1 += '<div class="freequency"><span class="fr">' + editname + '</span></div>';
		div1 += '<input class="sell sellw vsecond" type="text" value="" />';
		div1 += '<div class="stim">秒</div>';
		div1 += '<span class="addid"></span></div></div>';
		div1 += '<div class="modal-footer"><div class="emsg"></div>';
		div1 += '<a href="" class="btn btn-danger" data-action="1">取消</a>';
		div1 += '<a href="" class="btn bg-primary sdd second" data-dismiss="modal">确定</a></div>';

	}else if(name == "addr") {

		var addrname = $(this).parent().children("font:first").text();
		var div1 = '<span class="tit">修改</span>';
		div1 += '<div class="sond"><div class="sonhw">';
		div1 += '<div class="addr"><span>' + addrname + '</span></div>';
		div1 += '<input class="sell sellwww addr" type="text" value="" />';
		div1 += '</div></div>';
		div1 += '<div class="modal-footer"><div class="amsg"></div>';
		div1 += '<a href="" class="btn btn-danger" data-action="1">取消</a>';
		div1 += '<a href="" class="btn bg-primary sdd second" data-dismiss="modal">确定</a></div>';

	}else{
		return false;
	}

	$(".modal-content").html(div1);

	if(name == "ebike") {

		$(".sell").change(function() {
			var all = $(".sell option:selected").val();
			if(all == "单辆电动车") {
				$(".sonhl").html('<div class="soni">电动车ID</div><input class="sell selw ebike" type="text" placeholder="单辆电动车ID"  />');
			}else {
				$(".sonhl").hide();
			}
		});

		$(".second").on("click" , function() {

			var sall = $(".sell option:selected").val();
			if(sall == "单辆电动车") {
				var ebike = $(".ebike").val();
				if(ebike.match(/^\s*$/) != null) {
					$(".nmsg").text("不能为空");
					return false;
				}
				if(ebike == 0) {
					$(".nmsg").text("不能为零");
					return false;
				}
				if(ebike.match(/^\d+$/) == null) {
					$(".nmsg").text("请填写数字");
					return false;
				}
				self.parent().children("span:first").text(ebike);
			}else if(sall == "全部电动车"){
				self.parent().children("span:first").text(sall);
			}else{
				return false;
			}
		});
	}

	if(name == "second" || name == "wi") {
		$(".second").on("click" , function() {
			var timenum = $(".vsecond").val();
			if(timenum.match(/^\s*$/) != null) {
				$(".emsg").text("不能为空");
				return false;
			}
			if(timenum == 0) {
				$(".emsg").text("不能为零");
				return false;
			}
			if(timenum.match(/^\d+$/) == null) {
				$(".emsg").text("请填写数字");
				return false;
			}
			self.parent().children("span:first").text(timenum);
		});
	}

	if(name == "addr") {
		$(".second").on("click" , function() {
			var addr = $(".sellwww").val();
			if(addr.match(/^\s*$/) != null) {
				$(".amsg").text("不能为空");
				return false;
			}
			//验证地址
			// if(addr.match() == null) {
			// 	$(".amsg").text("请填写正确地址");
			// 	return false;
			// }
			self.parent().children("span:first").text(addr);
		});
	}

});

$(".submit").on("click" , function() {
	var span = $("table span");
	var dataval = [];
	for(var i = 0; i < span.length; i ++) {
		dataval[i] = span.eq(i).text();
	}

	var font = $("table td").find('font:first');
	var datakey = [];
	for(var i = 0; i < font.length; i ++) {
		datakey[i] = font.eq(i).attr("class");
	}

	var url = "sensorupdate.php";
	var data = {dataval: dataval , datakey: datakey};
	ajax(url , data);
})

//ajax
function ajax(url , data) {
    return $.ajax({
        url: url,
        cache: false,
        type: "post",
        data: data,
        dataType: "json",
        success: function(result){
        	if(result.state == true)
				location.href = "sensor.php";
			if(result.state == false)
				$(".msg").text(result.msg);

        }
    });
}