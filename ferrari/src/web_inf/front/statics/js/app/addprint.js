$(function(){
	/*计算印刷成本*/
	$('.printcost').click(function(){
		var countdata = {
			platemaking : $('.platemaking').val(),//制版数
			punitid     : $('.punitid').val(),//印刷单位ID
			pmethodid   : $('.pmethodid').val(),//印刷方式ID
			pronumber   : $('.pronumber').val(),//产品数量
			printtimes  : $('.printtimes').val()//印刷次数
		}

		//单位和方式不能为空
		if (countdata.punitid == -1) {
			$('.punitid').css('border','1px solid red');
			return false;
		}
		if (countdata.pmethodid == -1) {
			$('.pmethodid').css('border','1px solid red');
			return false;
		}

		var getcostsuccess = function(msg)
		{
			$('.printcount').val(msg);
		}
		var getcostfail = function()
		{
			console.log('get cost fail!');
		}
		util.ajax_post('/app/countcost.php',{countdata:countdata},getcostsuccess,getcostfail);
	});

	/*印刷方式和单位改变是的状态变化*/
	$('.punitid').change(function(){
		var punitid = $(this).val();
		if (punitid == -1) {
			$(this).css('border','1px solid red');
		} else {
			$(this).css('border','');
		}
	});
	$('.pmethodid').change(function(){
		var pmethodid = $(this).val();
		if (pmethodid == -1) {
			$(this).css('border','1px solid red');
		} else {
			$(this).css('border','');
		}
	});

	/*选择了印刷单位后列出印刷单位列表*/
	$('.punitid').change(function(){
		var puid = $(this).val(); 	//印刷单位ID

		var methodoption = '';
		methodoption = "<option value=\"-1\">--请选择--</option>";
		var getpulistsuccess = function(msg)
		{
			$.each(msg,function(i,v){
				methodoption += '<option value="'+v.id+'">'+v.name+'</option>';
			})
			//添加列表
			$('.pmethodid').empty().append(methodoption);
		}
		var getpulistfail = function()
		{
			console.log('list print method list fail!');
		}
		util.ajax_post('/app/listpmbypuid.php',{puid:puid},getpulistsuccess,getpulistfail);

	});

	/*进行印刷单的添加*/
	$('.addprintsub').click(function(){
		var printdata = {
			printmethodid: $('.pmethodid').val(),
			printunitid  : $('.punitid').val(),
			content      : $('.printcontent').val(),
			vnumber      : $('.platemaking').val(),
			pnumber      : $('.pronumber').val(),
			frequency    : $('.printtimes').val(),
			position     : $('.printlocation').val(),
			orderid      : $('.orderid').val(),
			stylename    : $('.stylename').val(),
			loadaddress  : $('.loadaddress').val(),
			tpsetstatus  : $('.tpsetstatus').val(),
			verifystatus : $('.verifystatus').val(),
			printcost    : $('.printcount').val(),
			comment      : $('.comment').val(),
			printimage   : $('input[name=printimage]').val()
		};
		var addprintsuccess = function(msg)
		{
			alert('添加印刷单成功！');
			location.reload();
		}
		var addprintfail = function()
		{
			alert('添加印刷单失败！');
			location.reload();
		}
		util.ajax_post('/app/addprintbill.php',{printdata:printdata},addprintsuccess,addprintfail);
	});

	/*进行图片添加*/
	uploadimgs();
	function uploadimgs(){
		$(".fileupload").on('change',function(){
			$uploadinput = $(this);
	    	$uploadinput.wrap("<form class='uploadform' action='/app/addprintimage.php' method='post' enctype='multipart/form-data'></form>");
	    	var picbox = '';

			//图片
			picbox = '\
				<div class="Upload-img imagebox">\
					<div style="text-align:center;line-height:200px;">图片上传中···</div>\
				</div>';

			//追加图片信息
			$('.imagearea').append(picbox);

	    	$uploadinput.closest('.uploadform').ajaxSubmit({
	    		dataType:  'json',
	    		beforeSend: function() {

	    		},
	    		success: function(data) {
	    			switch (data.errno) {
	    				case 421:
		    				alert('图片大小大于800Kb!');
		    				location.reload();
	    					break;
	    				case 432:
	    					alert('图片类型不存在！');
		    				location.reload();
	    					break;
						case 433:
							alert('请选择图片！');
		    				location.reload();
							break;
						case 434:
							alert('图片上传失败！');
		    				location.reload();
							break;
						case 435:
							alert('图片存储失败！');
		    				location.reload();
							break;
	    			}

					var imagename = data['data'].imagename;
					var picpath   = data['data'].picpath;

	    			//显示图片
	    			var picsrc = '<img class="modalImg modalImg1"src="'+picpath+imagename+'"/>\
									<div class="modal-div">\
										<span class="modal-Del delimage">删除</span>\
									</div>';
					//追加图片
	    			$('.imagebox').empty().append(picsrc);

	    			//存放图片信息
	    			$('input[name=printimage]').val(imagename);
				},
				error:function(xhr){
					console.log('add img fail!');
				}
			});
	    });
	}

	/*删除印刷图片*/
	$('.imagearea').on('click','.delimage',function(){
		$('.imagebox').remove();
		//清除图片名称
		$('input[name=printimage]').val('');
	});
})

