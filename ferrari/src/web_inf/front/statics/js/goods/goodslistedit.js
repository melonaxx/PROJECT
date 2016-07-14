$(function(){
	//商品规格名称
	var formatenamelist0 = $('.formatenamelist0 option:selected').val();
	var formatenamelist1 = $('.formatenamelist1 option:selected').val();
	var formatenamelist2 = $('.formatenamelist2 option:selected').val();
	var formatenamelist3 = $('.formatenamelist3 option:selected').val();
	var formatenamelist4 = $('.formatenamelist4 option:selected').val();
	var fnamearr = new Array();
	fnamearr.push(formatenamelist0);
	fnamearr.push(formatenamelist1);
	fnamearr.push(formatenamelist2);
	fnamearr.push(formatenamelist3);
	fnamearr.push(formatenamelist4);
	var GetReques = function() {
		var url = location.search; //获取url中"?"符后的字串
		var theRequest = new Object();
		if (url.indexOf("?") != -1) {
			var str = url.substr(1);
			strs = str.split("&");
			for(var i = 0; i < strs.length; i ++) {
				theRequest[strs[i].split("=")[0]]=decodeURIComponent(strs[i].split("=")[1]);
			}
		}
		return theRequest;
	}
	var productid = GetReques().productid;
	//通过规格值ID查询规格名称
	function getfvaluebyid (formateid) {

		var getfvaluesuccess = function(data){
			var fvid0 = data.fnvid.valueid1;
			var fvid1 = data.fnvid.valueid2;
			var fvid2 = data.fnvid.valueid3;
			var fvid3 = data.fnvid.valueid4;
			var fvid4 = data.fnvid.valueid5;

			fvaluelist0('fvaluelist0',data[0],fvid0);
			fvaluelist0('fvaluelist1',data[1],fvid1);
			fvaluelist0('fvaluelist2',data[2],fvid2);
			fvaluelist0('fvaluelist3',data[3],fvid3);
			fvaluelist0('fvaluelist4',data[4],fvid4);
		}
		var getfvaluefail = function(){
			console.log('get formate value fail!');
		}

		util.ajax_post('findgoodsfvalue.php',{fnamearr:fnamearr,productid:productid},getfvaluesuccess,getfvaluefail);
	}
	getfvaluebyid(fnamearr);

	//规格值
	function fvaluelist0(fvselector,fvalulist,fvid){
		// console.log(fvid);
		$('.'+fvselector).empty();
		var option = '';
		$.each(fvalulist,function(i,v){
		var selected = (v.id == fvid)? ' selected ': '';
			option+="<option value="+v.id+selected+">"+v.choice+"</option>";
		})
		$('.'+fvselector).append(option);
	};

	//商品属性值
	var pattrnamearr = new Array();
	$.each($('.attrselectname option:selected'),function(i,v){
		pattrnamearr.push(v.value)
	})
	var findproattrsuccess = function(data){
		$('.attrselectvalue').empty();
		$.each($('.attrselectvalue'),function(key,value){
			var option = '';
			$.each(data[key],function(i,v){
				var selected = (v.id == data['fvid'][key]) ? ' selected ' : '';
				option += "<option value='"+v.id+"'"+selected+">"+v.optional+"</option>";
			})
			$('.attrselectvalue').eq(key).append(option);
		})

	};
	var findproattrfail = function() {
		console.log('find proattr fail!');
	};
	util.ajax_post('findgoodsfattr.php',{pattrnamearr:pattrnamearr,productid:productid},findproattrsuccess,findproattrfail);

	//商品图片的删除效果
	$.each($('.Upload-img'),function(i,v){
		var imgsrc = $('.Upload-img').eq(i).find('img').prop('src');
		if (imgsrc.length>0) {
			$('.Upload-img').eq(i).find('img').on({
				mouseover:function(){
					$('.Upload-img').eq(i).find('.modal-div').show();
					$('.Upload-img').eq(i).find('.modal-div').on({mouseover:function(){
						$(this).show()},mouseout:function(){$(this).hide()}
					})
				},
				mouseout:function(){$('.Upload-img').eq(i).find('.modal-div').hide()}
			});
		}
	});

	//重置商品信息
	$('button[type=reset]').on('click',function(){
		location.reload();
	})
	//修改商品信息后的提交
	$('.editprosub').on('click',function(){
		$('.addprowindow').show();
		//获取商品的属性
		var proattrs = $('.attrselectname option:selected');
		var proattrarr = new Array();
		$.each(proattrs,function(i){
			var attrkey = $(this).val();
			var attrvalue = $(this).parent().parent().next().find('.attrselectvalue option:selected').val();
			proattrarr[i] = [attrkey,attrvalue];
		})
		//获取商品中的配件信息
		var partsopts = $('.partsopt option:selected');
		var partsarr = new Array();
		$.each(partsopts,function(i){
			var partskey = $(this).val();
			var partsvalue = $(this).parent().parent().parent().find('input[name=partnumber]').val();
			partsarr[i] = [partskey,partsvalue];
		});
		//仓库列表
		// var storelist = $('input[name="stores[]"]:checked');
		// var storearr = new Array();
		// $.each(storelist,function(i){
		// 	storearr.push($(this).val());
		// })

		//获取图片名称
		var picarrs = new Array();
		$.each($('input[name=imgpath]'),function(i,v){
			picarrs.push(v.value);
		})

		var productinfo = {
			'productid': 		productid,
			'pronumber': 		$('.pronumber').val(),
			'proname': 			$('.proname').val(),
			'goodsbrand': 		$('.goodsbrand option:selected').val(),
			'proshop': 			$('.proshop option:selected').val(),
			'goodsclassify': 	$('.goodsclassify option:selected').val(),
			'goodstype': 		$('.goodstype option:selected').val(),
			'goodsused': 		$('.goodsused option:selected').val(),
			'goodsunit': 		$('.goodsunit option:selected').val(),
			'serialnumber': 	$('.serialnumber').val(),
			'barcode': 			$('.barcode').val(),
			'prototal': 		$('.prototal').val(),

			'volume': 			$('.volume').val(),
			'pricetag':      	$('.pricetag').val(),
			'pricepurchase': 	$('.pricepurchase').val(),
			'pricesell':     	$('.pricesell').val(),
			'pricetotal':    	$('.pricetotal').val(),
			'weight':        	$('.weight').val(),
			'volume':        	$('.volume').val(),
			'formatenamelist0': $('.formatenamelist0 option:selected').val(),
			'formatenamelist1': $('.formatenamelist1 option:selected').val(),
			'formatenamelist2': $('.formatenamelist2 option:selected').val(),
			'formatenamelist3': $('.formatenamelist3 option:selected').val(),
			'formatenamelist4': $('.formatenamelist4 option:selected').val(),
			'fvaluelist0': 		$('.fvaluelist0 option:selected').val(),
			'fvaluelist1': 		$('.fvaluelist1 option:selected').val(),
			'fvaluelist2': 		$('.fvaluelist2 option:selected').val(),
			'fvaluelist3': 		$('.fvaluelist3 option:selected').val(),
			'fvaluelist4': 		$('.fvaluelist4 option:selected').val(),

			'proattrlist': 		proattrarr,

			'partslist': 		partsarr,

			// 'storelist': 		storearr,

			'goodsstatus': 		$('.goodsstatus option:selected').val(),
			'procomment': 		$('.procomment').val(),
			'procomment': 		$('.procomment').val(),
			'picarrs': 			picarrs

		}

		var addproductsuccess = function(msg){
			if(msg == 0) {
				$('.addprowindow').hide();
				alert('修改商品成功！');
				location.reload();
			}
		}
		var addproductfail = function(){
			$('.addprowindow').hide();
			alert('修改商品失败！');
		}
		util.ajax_post('editgoodslist.php',{productinfo:productinfo},addproductsuccess,addproductfail);
	});


})