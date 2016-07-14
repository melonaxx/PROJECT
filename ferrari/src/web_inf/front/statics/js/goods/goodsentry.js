$(function(){
	var uploadHtml = '<span class="Upload-text Upload-text1">\
	<span class="plan">上传</span>\
	<input class="fileupload" type="file" name="goodspic">\
	</span>\
	<img class="modalImg modalImg1"/>\
	<div class="modal-div">\
	<span class="modal-Edit"></span>\
	<span class="modal-Del">删除</span>\
	<input type="hidden" name="imgpath">\
	</div>';

	//图片上传
	var plan = $(".plan");
	uploadimgs();
	function uploadimgs(){
		$(".fileupload").off("change").on('change',function(){
			$uploadinput = $(this);
	    	$uploadinput.wrap("<form class='uploadform' action='uploadpicfromgoods.php' method='post' enctype='multipart/form-data'></form>");
	    	$uploadinput.closest('.uploadform').ajaxSubmit({
	    		dataType:  'json',
	    		beforeSend: function() {
	    			$uploadinput.closest('form').prev().html('上传中···');
	    		},
	    		success: function(data) {
	    			switch (data.errno) {
	    				case 421:
		    				alert('文件大小大于800Kb!');
		    				location.reload();
	    					break;
	    				case 432:
	    					alert('文件类型不存在！');
		    				location.reload();
	    					break;
						case 433:
							alert('请选择图片！');
		    				location.reload();
							break;
						case 434:
							alert('文件上传失败！');
		    				location.reload();
							break;
	    			}
	    			$uploadinput.closest('form').prev().html('上传');
	    			var picpath = data['data'].picpath;
	    			var picsize = data['data'].picsize;
	    			var imagedata = data['data'].qn[1].key;
	    			var iarr = new Array();
	    			iarr['picpath'] = picpath;
	    			iarr['picsize'] = picsize;
	    			iarr['picdata'] = imagedata;
	    			var imgsarr = iarr['picpath']+','+iarr['picsize']+','+iarr['picdata'];
					//存放图片信息
	    			$uploadinput.closest('td').find('input[name=imgpath]').val(imgsarr);
					iarr.lenght = 0;

					$uploadinput.closest('span').hide();
					$uploadinput.closest('span').next().show();
					$uploadinput.closest('span').next().prop('src',picpath+'/'+imagedata);
					//编辑btn显示
					$uploadinput.parent().parent().parent().mouseover(function(){
						$(this).find('.modal-div').show();
					});
					$uploadinput.parent().parent().parent().mouseout(function(){
						$(this).find('.modal-div').hide();
					});
				},
				error:function(xhr){
					console.log('add img fail!');
				}
			});
	    });
	}
	//删除图片
	$("#waregoods-table-img").on("click", ".modal-Del", function() {
		var td = $(this).closest("td");
		td.off('mouseout').off('mouseover').empty().html(uploadHtml);
		uploadimgs();
		return false;
	});


	//获取单个商品的所有信息
	$('.addprosub').on('click',function(){
		var addflag = true;
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
		var imagenumbers = 0;
		$.each($('input[name=imgpath]'),function(i,v){
			picarrs.push(v.value);
			if (v.value && v.value.length > 0) {
				imagenumbers++;
			}
		})
		if (imagenumbers <= 0) {
			alert('商品图片不能为空!');
			return addflag = false;
		}

		var productinfo = {
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

		if (addflag) {
			$('.addprowindow').show();
			var addproductsuccess = function(msg){
				if(msg == 0) {
					$('.addprowindow').hide();
					alert('添加商品成功！')
					location.reload();
				}
			}
			var addproductfail = function(){
				$('.addprowindow').hide();
				alert('添加商品失败！');
			}
			util.ajax_post('addproductinfo.php',{productinfo:productinfo},addproductsuccess,addproductfail);
		}
	});

	var $mymodal=$(".modal-manager");
	var $person=$(".person-name");
	$person.on("focus",function(){
		$mymodal.show();
	});
	//选择产品经理;
	$(".btn-sure1").on("click",function(){
		$("input[type='radio']").each(function(){
			if(this.checked){
				$(".modal-manager").hide();
				$person.val($(this).parent().parent().parent().next().html());
			}
		});
	});
	//取消产品经理：
	$(".btn-cancle").on("click",function(){
		$("input[type='radio']").each(function(){
			if(this.checked){
				this.checked=false;
			}
		});
	});
	//选择商品规格后显示对应的规格值。
	$('.formatenamelist0').on('change',function(){
		var $fornameselect = $(this);
		//清空规格值列表
		$fornameselect.parent().parent().siblings('.fvalues').find('select').eq(0).empty();
		//规格的ID
		var fnameid = $(this).val();
		var fnamesuccess = function(msg){
			$foption = '';
			$.each(msg,function(i,v){
				var $fstr = "<option value="+v.id+">"+v.choice+"</option>";
				$foption +=$fstr;
			})
			$fornameselect.parent().parent().siblings('.fvalues').find('select').eq(0).append($foption);
		}
		var fnamefail = function(){
			console.log('fname fail!');
		}
		util.ajax_post('getfvalue.php',{fnameid:fnameid},fnamesuccess,fnamefail);
	})
	$('.formatenamelist1').on('change',function(){
		var $fornameselect = $(this);
		//清空规格值列表
		$fornameselect.parent().parent().siblings('.fvalues').find('select').eq(1).empty();
		//规格的ID
		var fnameid = $(this).val();
		var fnamesuccess = function(msg){
			$foption = '';
			$.each(msg,function(i,v){
				var $fstr = "<option value="+v.id+">"+v.choice+"</option>";
				$foption +=$fstr;
			})
			$fornameselect.parent().parent().siblings('.fvalues').find('select').eq(1).append($foption);
		}
		var fnamefail = function(){
			console.log('fname fail!');
		}
		util.ajax_post('getfvalue.php',{fnameid:fnameid},fnamesuccess,fnamefail);
	})
	$('.formatenamelist2').on('change',function(){
		var $fornameselect = $(this);
		//清空规格值列表
		$fornameselect.parent().parent().siblings('.fvalues').find('select').eq(2).empty();
		//规格的ID
		var fnameid = $(this).val();
		var fnamesuccess = function(msg){
			$foption = '';
			$.each(msg,function(i,v){
				var $fstr = "<option value="+v.id+">"+v.choice+"</option>";
				$foption +=$fstr;
			})
			$fornameselect.parent().parent().siblings('.fvalues').find('select').eq(2).append($foption);
		}
		var fnamefail = function(){
			console.log('fname fail!');
		}
		util.ajax_post('getfvalue.php',{fnameid:fnameid},fnamesuccess,fnamefail);
	})
	$('.formatenamelist3').on('change',function(){
		var $fornameselect = $(this);
		//清空规格值列表
		$fornameselect.parent().parent().siblings('.fvalues').find('select').eq(3).empty();
		//规格的ID
		var fnameid = $(this).val();
		var fnamesuccess = function(msg){
			$foption = '';
			$.each(msg,function(i,v){
				var $fstr = "<option value="+v.id+">"+v.choice+"</option>";
				$foption +=$fstr;
			})
			$fornameselect.parent().parent().siblings('.fvalues').find('select').eq(3).append($foption);
		}
		var fnamefail = function(){
			console.log('fname fail!');
		}
		util.ajax_post('getfvalue.php',{fnameid:fnameid},fnamesuccess,fnamefail);
	})
	$('.formatenamelist4').on('change',function(){
		var $fornameselect = $(this);
		//清空规格值列表
		$fornameselect.parent().parent().siblings('.fvalues').find('select').eq(4).empty();
		//规格的ID
		var fnameid = $(this).val();
		var fnamesuccess = function(msg){
			$foption = '';
			$.each(msg,function(i,v){
				var $fstr = "<option value="+v.id+">"+v.choice+"</option>";
				$foption +=$fstr;
			})
			$fornameselect.parent().parent().siblings('.fvalues').find('select').eq(4).append($foption);
		}
		var fnamefail = function(){
			console.log('fname fail!');
		}
		util.ajax_post('getfvalue.php',{fnameid:fnameid},fnamesuccess,fnamefail);
	})
	//清除商品规格;
	$(".waredetail-clear").on("click",function(){
		$(".select1").val($(".opt1").val());
		$(".select2").val("");
	});
	//添加商品属性;
	$(".btn-add1").on("click",function(){
		var addattrsuccess = function(msg){
			var $onetr1=$("<tr class='onetr1'></tr>");
			var $td1=$('<td class="onetd1"></td>');
			var $td2=$('<td><a class="btn-del delproattr" href="javascript:;">删除</a></td>');

			var $td3=$('<td></td>');
			var selects = '<select class="form-control attrselectname" id="exampleInputEmail2"><option>--无--</option>';
			$.each(msg,function(i,v){
				selects+='<option value="'+v.id+'">'+v.name+'</option>'
			})
			selects+='</select>';
			var $td4=$('<td><select class="form-control attrselectvalue" id="exampleInputEmail2"></select></td>');
			$onetr1.append($td1);
			$onetr1.append($td2);
			$onetr1.append($td3);
			$td3.append(selects);
			$onetr1.append($td4);
			$("#tbody1").append($onetr1);
			//显示序号
			if($(".onetr1").length==1){
				$td1.html(1);
			}else{
				$td1.html(Number($onetr1.prev().children().eq(0).html())+1);
			}
		}
		var addattrfail = function(){
			console.log('addattrfail!');
		}
		util.ajax_post('getattrname.php',{data:'ok'},addattrsuccess,addattrfail);
	});
	//删除商品btn;
	$('.onetr1').find('.delproattr').on('click',function(){
		$(this).parent().parent().remove();
	});
	//获取商品属性名称对应的属性值
	$('#tbody1').on('change','.attrselectname',function(){
		$attrnamesele = $(this);
		var attrnameid = $(this).val();
		var getattrvaluesuccess = function(msg){
			$attrnamesele.parent().siblings().find('.attrselectvalue').empty();
			var $attrvaluelist = '';
			$.each(msg,function(i,v){
				$attrvaluelist += '<option value="'+v['id']+'">'+v['optional']+'</option>';
			})
			$attrnamesele.parent().siblings().find('.attrselectvalue').append($attrvaluelist);
		}
		var getattrvaluefail = function(){
			console.log('get attr list!');
		}
		util.ajax_post('getattrvaluebyattrname.php',{attnameid:attrnameid},getattrvaluesuccess,getattrvaluefail);
	})
	//添加商品配件;
	$(".btn-add2").on("click",function(){
		var $onetr2=$("<tr class='onetr2'></tr>");
		var $td11=$('<td class="onetd2"></td>');
		var $td22=$('<td><a class="btn-del1 " href="javascript:;">删除</a></td>');
		var $td33=$('<td><input type="partskey" class="form-control searchbox searchparts" placeholder="搜索"/></td>');
		var $td44=$('<td><select class="form-control partsopt" id="exampleInputEmail2"><option>请选择商品</option></select></td>');
		var $td55=$('<td><input type="text" name="partnumber" class="form-control searchbox"/></td>');
		$onetr2.append($td11);
		$onetr2.append($td22);
		$onetr2.append($td33);
		$onetr2.append($td44);
		$onetr2.append($td55);
		$("#tbody2").append($onetr2);
		if($(".onetr2").length==1){
			$td11.html(1);
		}else{
			$td11.html(Number($onetr2.prev().children().eq(0).html())+1);
		}
		Delete("two");
	});
	//搜索商品的配件
	$('#tbody2').on('blur','.searchparts',function(){
		$searchparts = $(this);
		//get searchkey
		var partskey = $(this).val();
		var addpartssuccess = function(msg){
			$searchparts.closest('tr').find('.partsopt').empty();
			var partsvalue = '';
			$.each(msg,function(i,v){
				var partsopt = "<option value="+v.productid+">"+v.name+"</option>";
				partsvalue+=partsopt;
			})
			$searchparts.closest('tr').find('.partsopt').append(partsvalue);
		}
		var addpartsfail = function(){
			$searchparts.closest('tr').find('.partsopt').empty();
			console.log('add parts fail!');
		}
		util.ajax_post('searchpartsbypartskey.php',{searchparts:partskey},addpartssuccess,addpartsfail);
	})
	$(".img1").hover(
		function(){
			$(this).next().show();
		},
		function(){
			$(this).next().hide();
		}
		);
	Delete("two");
	//鼠标划过出现删除;
	$(".modal-house").hover(
		function(){
			$(this).children().eq(1).show();
			$(this).children().eq(0).hide();
		},
		function(){
			$(this).children().eq(0).show();
			$(this).children().eq(1).hide();
		}
		);
	//点击删除仓库;
	$(".detail-house-del").on("click",function(){
		$(this).parent().parent().remove();
	});
	//添加仓库;
	$(".btn-change").click(function(){
		$(".modal-addware").show();
		//全选；
		$(".modal-allcheck").on("click",function(){
			$("input[type='checkbox']").each(function(){
				this.checked=true;
			});
		});
		//全不选仓库;
		$(".modal-uncheck").on("click",function(){
			$("input[type='checkbox']").each(function(){
				this.checked=false;
			});
		});
		//反选仓库;
		$(".modal-reverse").on("click",function(){
			$("input[type='checkbox']").each(function(){
				if(this.checked=true){
					this.checked=false;
				}else{
					this.checked=true;
				}
			});
		});
	});
	var $mymodal2=$(".modal-addware");
	var $rowhouse=$(".detail-house");
	var $rowhouse=$(".detail-house");
	$(".btn-sure2").on("click",function(){
		$(".detail-house").empty();
		$("input[type='checkbox']").each(function(){
			if(this.checked){
				var $house=$('<div class="modal-house"><span class="modal-house-name">'+$(this).next().html()+'</span><span class="detail-house-del">删除</span></div>');
				$rowhouse.append($house);
				$mymodal2.hide();
				this.checked=false;
				Hover();
				$(".detail-house-del").on("click",function(){
					$(this).parent().remove();
				});
			}
		});
	});

	//划入划出;
	function Hover(){
		$(".modal-house").hover(
			function(){
				$(this).children().eq(1).show();
				$(this).children().eq(0).hide();
			},
			function(){
				$(this).children().eq(0).show();
				$(this).children().eq(1).hide();
			}
			);
	}
	//删除商品;
	function Delete(obj){
		if(obj=="one"){
			var $del=$(".btn-del");
			var $td1=$(".onetd1");
			$del.each(function(i){
				$del.eq(i).click(function(){
					$(this).parent().parent().remove();
					$td1.each(function(i){
						$(this).html(i+1);
					});
				});
			});
		}
		if(obj=="two"){
			var $del=$(".btn-del1");
			var $td1=$(".onetd2");
			$del.each(function(i){
				$del.eq(i).click(function(){
					$(this).parent().parent().remove();
					$td1.each(function(i){
						$(this).html(i+1);
					});
				});
			});
		}
	}
})