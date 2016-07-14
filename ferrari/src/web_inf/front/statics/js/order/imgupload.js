$(function(){
	/*订单编辑中的图片上传*/
	uploadimgs();

	//图片专区
	var imagearea = '图片专区(<a href="javascript:;" class="upload">上传图片<input class="fileupload" type="file"  name="goodspic"></a>)';
	//第几个图片进行标记
	var imgflag = 0;
	$.each($('.picnotice'),function(i){
		imgflag++;
	})

	function uploadimgs(){
		$(".imagearea").off("change").on('change','.fileupload',function(){
			$('.imagearea').empty().append(imagearea);
			$uploadinput = $(this);
	    	$uploadinput.wrap("<form class='uploadform' action='/order/orderimagequery.php' method='post' enctype='multipart/form-data'></form>");
	    	var picbox = '';
	    	var orderid = $('input[name=orderid]').val();

			//图片
			picbox = '\
			<div class="Upload-img" style="background:white;">\
				<span class="picnotice"><div style="text-align:center;line-height:200px;">图片上传中······</div></span>\
				<div class="modal-div oimageaction">\
				</div>\
			</div>';

			//追加图片信息
			$('.imagelist').append(picbox);

	    	$uploadinput.closest('.uploadform').ajaxSubmit({
	    		dataType:  'json',
	    		data: {orderid:orderid},
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
					var picpath    = data['data'].picpath;
					var picsize    = data['data'].picsize;
					var ordermsgid = data['data'].ordermsgid;
					var imagedata  = data['data'].qn[1].key;
	    			var iarr = new Array();
					iarr['picpath']    = picpath;
					iarr['picsize']    = picsize;
					iarr['picdata']    = imagedata;
					iarr['ordermsgid'] = ordermsgid;

	    			//显示图片
	    			var picsrc = '<a href="'+picpath+'/'+imagedata+'" target="blank"><img class="modalImg modalImg1"src="'+picpath+'/'+imagedata+'"/></a>';
	    			$('.picnotice').eq(imgflag).empty().append(picsrc);
	    			//删除操作
	    			var imagename = '<span class="modal-Del oimagedel">删除</span><input type="hidden" name="ordermsgid" value="'+ordermsgid+'"/>';
	    			$('.oimageaction').eq(imgflag).append(imagename);

	    			imgflag++;
				},
				error:function(xhr){
					console.log('add img fail!');
				}
			});
	    });
	}

	/*进行图片删除*/
	$('.imagelist').off('click').on('click','.oimagedel',function(){
		$(this).parent().parent().remove();
		var ordermsgid = $(this).parent().find('input[name=ordermsgid]').val();
		var delmsgsuccess = function(msg)
		{
			console.log(msg);
		}
		var delmsgfail = function()
		{
			alert('删除图片失败！');
		}
		util.ajax_post('/order/delordermsg.php',{ordermsgid:ordermsgid},delmsgsuccess,delmsgfail);
	});
})
