$(function(){
	/*==========================仓库start==================================*/
	store = new Array();
	//新增仓库;
	$(".addstorebtn").click(function(event){
		event.stopPropagation();
		//----获取省市县----
		searchpcc();

		//默认仓库名称
		$('.accountdefname').text($('.defstorename').text());
		//仓库添加窗口显示
		$(".addstorewindow").show();
		var $fillstrname;
		var $newtr=$('<tr class="mouse-hover">');
		var $td1=$('<td class="strflag"></td>');
		var $td2=$('<td class="td-right td-right0"></td>');
		var $td21=$('<span class="btn2 glyphicon glyphicon-list-alt" style="margin-right:5px;"></span>');
		var $td22=$('<span class="btn1 glyphicon glyphicon-trash" style="margin-right:5px;"></span>');
		var $td23=$('<a class="warehouse-method" target="_blank" href="shippingset.html">发货设置</a>');
		$newtr.append($td1);
		$(".storename").change(function(){
			$fillstrname=$(this).val();
			$newtr.children(".strflag").html($fillstrname);
		});
		//添加仓库后点击提交按钮;
		$(".addstoresub").off('click').click(function(){
			if($(".storename").val()==""){
				$(".storename").focus();
			}else{
				$newtr.children().eq(0).removeClass("strflag");
				$td2.append($td21);
				$td2.append($td22);
				$td2.append($td23);
				$newtr.append($td2);
				//添加一条仓库信息
				$(".storelist").append($newtr);
				$(".addstorewindow").hide();

				var storedata = {
					'adddefstore': 		$('input:radio[name=adddefstore]:checked').val(),
					'storetype': 		$('#storetype').val(),
					'storenumber': 		$('#storenumber').val(),
					'storename': 		$('#storen').val(),
					'contactname': 		$('#contactname').val(),
					'moblie': 			$('#moblie').val(),
					'telphone': 		$('#telphone').val(),
					'loc_province': 	$('#loc_province option:selected').val(),
					'loc_city': 		$('#loc_city option:selected').val(),
					'loc_town': 		$('#loc_town option:selected').val(),
					'storeaddress': 	$('#storeaddress').val(),
					'describes': 		$('#describes').val()
				}
				//ajax成功
				var addstrsuccess = function(msg){
					window.location.href = location.href;
				}
				var addstrfail = function(){
					alert('仓库添加失败！');
				}
				util.ajax_post('addwarehouse.php',storedata,addstrsuccess,addstrfail);
			}
		});
	});
	//仓库的修改btn
	$('.stredit').click(function(){
		$streditbtn = $(this);

		//显示修改窗口
		$(".editstorewindow").show();

		var storesuccess = function(msg){
			//判断是否是默认的仓库
			if (msg.data.storestatus == 'Default') {
				$('.default-name').show();
				$('.defult-ware').hide();
			} else {
				$('.default-name').hide();
				$('.defult-ware').show();
				//默认仓库
				var defstorename = $('.defstorename').text();
				$('.account-name').text(defstorename);
			}
			//仓库类型
			var strtype = $('#strtype option');
			$.each(strtype,function(){
				if($(this).val() == msg.data.storetype) {

					$(this).prop('selected',true);
				}
			})
			//仓库编码
			$('input[name=storenumber]').val(msg.data.number);
			//仓库名称
			$('input[name=storename]').val(msg.data.name);
			//联系人
			$('input[name=contactname]').val(msg.data.contactname);
			//手机
			$('input[name=mobile]').val(msg.data.mobile);
			//电话
			$('input[name=telphone]').val(msg.data.telphone);
			//省
			var stateid = msg.data.stateid;
			//市
			var cityid = msg.data.cityid;
			//县
			var districtid = msg.data.districtid;
			//显示省市县
			searchpcc('editpro','editcity','editcounty',stateid,cityid,districtid);

			if (!stateid) {
				$('.editpro').empty().append('<option value="-1">--请选择--</option>');
			}
			//详细地址
			$('input[name=address]').val(msg.data.address);
			//仓库描述
			$('input[name=describes]').val(msg.data.describes);
		}
		var storefail = function(){
			console.log('getstoreinfo fail');
		}
		//获取仓库ID
		var storeid = $(this).parent().find('input').val();
		var sdata = {storeid:storeid};
		util.ajax_post('getstoreinfo.php',sdata,storesuccess,storefail);
	})
	//仓库修改后提交
	$('.editstrsub').click(function(){
		//关闭仓库修改窗口
		$('.editstorewindow').hide();
		//获取数据
		var storedail = {
			'editdefstore': $('input:radio[name=editdefstore]:checked').val(),
			'id': 			$streditbtn.parent().find('input[name=storeid]').val(),
			'strtype': 		$('#strtype option:selected').val(),
			'storenumber': 	$('input[name=storenumber]').val(),
			'storename': 	$('input[name=storename]').val(),
			'contactname': 	$('input[name=contactname]').val(),
			'mobile': 		$('input[name=mobile]').val(),
			'telphone': 	$('input[name=telphone]').val(),
			'prov': 		$('.editpro option:selected').val(),
			'city1': 		$('.editcity option:selected').val(),
			'countys': 		$('.editcounty option:selected').val(),
			'address': 		$('input[name=address]').val(),
			'describes': 	$('input[name=describes]').val()
		}
		var editsuccess = function(msg){
			window.location.href = 'warehouse.php';
		}
		var editfail = function(){
			console.log('edit storeinfo fail!');
		}
		util.ajax_post('editstoreinfo.php',{strinfo:storedail},editsuccess,editfail);
	})
	//默认仓库的修改
	$('.defstrdel').click(function(){
		alert('默认仓库不能删除！');
	})
	//删除的仓库btn
	$('.strdel').click(function(event){
		$delstrbtn = $(this);
		event.stopPropagation();
		$(".deletetrwindow").show();
		$(".del-sure").html("您确定要删除该仓库吗?");
	})
	//确认进行删除btn
	$('.deletesub').click(function(){
		//删除行
		$(this).parent().parent().parent().hide();

		var storeid = $delstrbtn.parent().find('input').val();
		var delsuccess = function(){
			location.reload();
		}
		var delfail = function(errno){
			switch (errno) {
				case 401:
				alert('该仓库下有库区不能删除该仓库！');
				break;
				case 500:
				alert('删除仓库失败！');
				break;
			}
		}
		util.ajax_post('delstoreinfo.php',{storeid:storeid},delsuccess,delfail);
	});
	//显示库区列表
	$('.storetd').click(function(){
		$storetr = $(this).closest('tr');
		//仓库名称
		var $warename=$storetr.children().eq(0).html();
		$storetr.addClass("active").siblings().removeClass("active");
		//传递storeid到库区
		var storeid = $storetr.find('input[name=storeid]').val();
		store['store'] = $warename;
		store['storeid'] = storeid;
		$('.addareabtn').parent().find('input[name=strareaid]').val(storeid);
		//清空库区列表
		$('.arealist').empty();
		//显示库区窗口
		$('.ware-areas').show();
		$('.areatable').hide();
		$('.goodslocation').hide();

		//显示该库下的所有库区
		var searchareasuccess = function(msg){
			//显示库区
			$.each(msg,function(i,v){
				var $areastr=$('<tr class="table-child-click areatr"></tr>');
				var $td11=$('<td class="td-right aname"></td>');
				var $td12=$('<td class="td-right td-right1"><input type="hidden" name="areaid"/><span class="areaedit glyphicon glyphicon-list-alt"></span> <span class="areadel glyphicon glyphicon-trash"></span></td>');
				$areastr.append($td11);
				$areastr.append($td12);
				$areastr.children('.aname').html(v.placeno);
				$areastr.find('input[name=areaid]').val(v.id);
				$('.arealist').append($areastr);
			})
			//为库区列表添加删除操作
			$('.areadel').click(function(){
				event.stopPropagation();
				$delareabtn = $(this);
				$(".deleteareawindow").show();
			});
			//编辑单个库区信息
			$('.areaedit').on('click',function(){
				$editareabtn = $(this);
				$(".editareawindow").show();
				//库区编码号
				$(".placenum").change(function(){
					$placenum=$(this).val();
				});

				var getshesuccess = function(msg){
					//所属仓库
					$('.editareawindow').find('input[name=pstore]').val($warename);
					$('.editareawindow').find('input[name=areano]').val(msg.placeno);
					$('.editareawindow').find('input[name=areacomment]').val(msg.comment);
				}
				var getshefail = function(){
					console.log('getGoodsShelevs fail!');
				}
				//库区ID
				var areaid = $editareabtn.parent().find('input[name=areaid]').val();
				util.ajax_post('getstorearea.php',{areaid:areaid},getshesuccess,getshefail);
			})
			//通过area tr显示货架列表
			$('.areatr').on('click',function(){
				$areatrthis=$(this);
				//库区名称
				$areaname = $(this).children().eq(0).html();
				store['area'] = $areaname;
				$(this).addClass("active").siblings().removeClass("active");
				//显示货架表
				$(".areatable").show();
				$('.goodslocation').hide();
				//获取areaid
				var areaid = $(this).find('input[name=areaid]').val();

				//存放areaid
				$('.Shelves').parent().find('input[name=Areaid]').val(areaid);
				//清空货架列表
				$('.shelvelist').empty();
				var showshelvesuccess = function(msg){
					//显示货架
					$.each(msg,function(i,v){
						var $shelvestr=$('<tr class="table-child1-click shelvetr"></tr>');
						var $td11=$('<td class="sname"></td>');
						var $td12=$('<td class="td-right td-right2"><input type="hidden" name="shelveid"/><span class="shelveedit glyphicon glyphicon-list-alt"></span> <span class="shelvedel glyphicon glyphicon-trash"></span></td>');
						$shelvestr.append($td11);
						$shelvestr.append($td12);
						$shelvestr.children('.sname').html(v.placeno);
						$shelvestr.find('input[name=shelveid]').val(v.id);
						$('.shelvelist').append($shelvestr);
					})
					//删除货架btn
					$('.shelvedel').on('click',function(){
						$delshelvebtn = $(this);
						$(".deleteshelvewindow").show();
					});
					//编辑单个货架btn
					$('.shelveedit').on('click',function(){
						$editshelvebtn = $(this);
						$(".editshelvewindow").show();
						//库架编码号
						$(".shelveeditnum").change(function(){
							$shelveeditnum=$(this).val();
						});
						var getshesuccess = function(msg){
							//所属仓库
							$('.editshelvewindow').find('.sbestore').val(store['store']);
							$('.editshelvewindow').find('.sbearea').val(store['area']);
							$('.editshelvewindow').find('.shelveeditnum').val(msg.placeno);
							$('.editshelvewindow').find('.editshelvecomment').val(msg.comment);
						}
						var getshefail = function(){
							console.log('getOneShelevs fail!');
						}
						//库架ID
						var shelveid = $editshelvebtn.parent().find('input[name=shelveid]').val();
						util.ajax_post('getstoreshelve.php',{shelveid:shelveid},getshesuccess,getshefail);
					})
					//通过货架显示货位列表
					$('.shelvetr').on('click',function(){
						$shelvetrthis = $(this);
						store['shelve'] = $(this).children().eq(0).html();
						$(this).addClass("active").siblings().removeClass("active");
						//获取货架ID并存放
						var sheid = $(this).find('input[name=shelveid]').val();
						$('.Location').parent().find('input[name=Shelveid]').val(sheid);
						//显示货位表
						$('.goodslocation').show();
						//清空货架列表
						$('.locationlist').empty();
						var listloctionsuccess = function(msg){
							$.each(msg,function(i,v){
								var $locationstr=$('<tr class="table-child1-click locationtr"></tr>');
								var $td11=$('<td class="sname"></td>');
								var $td12=$('<td class="td-right td-right3"><input type="hidden" name="locationid"/><span class="locationedit glyphicon glyphicon-list-alt"></span> <span class="locationdel glyphicon glyphicon-trash"></span> <a class="warehouse-method" target="_blank" href="waregoodset.php?storeid='+storeid+'&location='+v.id+'">商品设置</a></td>');
								$locationstr.append($td11);
								$locationstr.append($td12);
								$locationstr.children('.sname').html(v.placeno);
								$locationstr.find('input[name=locationid]').val(v.id);
								$('.locationlist').append($locationstr);
							})
							//删除单个货位信息btn
							$('.locationdel').on('click',function(){
								$dellocationbtn = $(this);
								$(".deletelocationwindow").show();
							})
							//编辑单个货位信息btn
							$('.locationedit').on('click',function(){
								$editlocationbtn = $(this);
								$(".editlocationwindow").show();
								//货位编码号
								$(".lbslocation").change(function(){
									$lbslocation=$(this).val();
								});
								var getlocsuccess = function(msg){
									//所属仓库
									$('.editlocationwindow').find('.lbastore').val(store['store']);
									$('.editlocationwindow').find('.lbaarea').val(store['area']);
									$('.editlocationwindow').find('.lbashelve').val(store['shelve']);
									$('.editlocationwindow').find('.lbslocation').val(msg.placeno);
									$('.editlocationwindow').find('.lbacomment').val(msg.comment);
								}
								var getlocfail = function(){
									console.log('getonelocation fail!');
								}
								//货位ID
								var locationid = $editlocationbtn.parent().find('input[name=locationid]').val();
								util.ajax_post('getlocationinfo.php',{locationid:locationid},getlocsuccess,getlocfail);
							})
						}
						var listlocationfail = function(){
							console.log('listlocation fail!');
						}
						util.ajax_post('listlocationinfo.php',{shelveid:sheid},listloctionsuccess,listlocationfail);
					})
				}
				var showshelvefail = function(){
					console.log('showShelves fail!');
				}
				util.ajax_post('liststoreshelve.php',{areaid:areaid},showshelvesuccess,showshelvefail);
			})
}
var searchareafail = function(){
	console.log('search areastore fail!');
}
util.ajax_post('liststorearea.php',{storeid:storeid},searchareasuccess,searchareafail);
});
/*=============================仓库end========================================*/
/*==============================库区start=====================================*/
	//添加库区btn
	$('.addareabtn').click(function(){
		$areabtnthis = $(this);
		//显示库区添加窗口
		$('.addareawindow').show();
		//获取父级名称
		$(".followstrname").val($storetr.children().eq(0).html());
		//库区编码号
		$(".placenumber").change(function(){
			$placenumber=$(this).val();
		});
	});
	//添加库区后提交btn
	$('.addareasub').click(function(event){
		event.stopPropagation();
		if($(".placenumber").val() == ""){
			$(".placenumber").focus();
		}else{
			//获取父级storeid
			var storeid = $areabtnthis.parent().find('input[name=strareaid]').val();
			var placenumber = $(".placenumber").val();

			var $newareatr =$('<tr class="table-child-click areatr"></tr>');
			var $td11=$('<td class="click"></td>');
			var $td12=$('<td class="td-right td-right1"><input type="hidden" name="areaid"/><span class="areaedit glyphicon glyphicon-list-alt"></span> <span class="areadel glyphicon glyphicon-trash"></span></td>');
			$newareatr.append($td11);
			$newareatr.append($td12);
			$('.arealist').append($newareatr);
			$('.addsuccess').css('display','block');
			$('.addsuccess').html('数据提交中······');
			//显示库区名
			$newareatr.children().eq(0).html($placenumber);
			var strdata = {
				'storeid': 		storeid,
				'placenumber': 	$('.addareawindow input[name=placenumber]').val(),
				'comment': 		$('.addareawindow input[name=comment]').val(),
				'locationtype': 'Area'
			}
			var sareasuccess = function(msg){
				//存放areaid
				// $newareatr.find('input[name=areaid]').val(msg.id);
				$('.addsuccess').html('数据添加成功！');
				setTimeout(function(){
					window.location.href = location.href;
				},2000)
			}
			var sareafail = function(){
				alert('库区添加失败！');
			}
			util.ajax_post('addstrlocation.php',{strdata:strdata},sareasuccess,sareafail);

			$newareatr.children().eq(0).removeClass("click");
			$(".addareawindow").hide();
		}
	});
	//确认删除库区btn
	$('.deleteareasub').click(function(){
		//删除行
		$(this).parent().parent().parent().hide();

		var areaid = $delareabtn.parent().find('input').val();
		var delsuccess = function(){
			alert('删除库区成功！');
			history.go(0);
		}
		var delfail = function(errno){
			switch (errno) {
				case 401:
				alert('该库区下有货架不能删除该库区！');
				break;
				case 500:
				alert('删除库区失败！');
				break;
			}
		}
		util.ajax_post('delsal.php',{salid:areaid},delsuccess,delfail);
	});
	//编辑库区后的提交btn
	$('.eidtabtn').on('click',function(){
		var editareasuccess = function(msg){
			console.log(msg);
		}
		var editareafail = function(){
			console.log('editdata fail!');
		}
		var editdata = {
			'areaid': 		$editareabtn.parent().find('input[name=areaid]').val(),
			'areano': 		$('.modal4').find('input[name=areano]').val(),
			'areacomment': 	$('.modal4').find('input[name=areacomment]').val()
		}
		util.ajax_post('updatearea.php',{editdata:editdata},editareasuccess,editareafail);

		$(".editareawindow").hide();
		if (typeof($placenum) != 'undefined') {
			$editareabtn.parent().parent().children().eq(0).html($placenum);
		}
	})
	/*==============================库区end=========================================*/
	/*==============================货架start=========================================*/
	//添加货架btn
	$('.Shelves').click(function(){
		$addshelvebtn = $(this);
		$(".addshelvewindow").show();
		$(".sbstore").val(store['store']);
		$('.sbarea').val(store['area']);
		//获取货架名称
		$(".shelvenum").change(function(){
			$shelvenum=$(this).val();
		});
	});
	//添加货架后提交btn
	$('.addshelvesub').on('click',function(){
		if($(".goodsshelf-code").val()==""){
			$(".goodsshelf-code").focus();
		}else{
			var $tr2=$('<tr class="table-child1-click shelvetr"></tr>');
			var $td21=$('<td class="click"></td>');
			var $td22=$('<td class="td-right td-right2"><input type="hidden" name="shelveid"/><span class="shelveedit glyphicon glyphicon-list-alt"></span> <span class="shelvedel glyphicon glyphicon-trash"></span></td>');
			$tr2.append($td21);
			$tr2.append($td22);
			//追加货架
			$('.shelvelist').append($tr2);
			//显示到列表
			$tr2.children(".click").html($shelvenum);
			$tr2.children().eq(0).removeClass("click");
			$('.addsuccess').css('display','block');
			$('.addsuccess').html('数据提交中······');

			//添加货架到数据库
			var addshelvesuccess = function(msg){
				$('.addsuccess').html('数据添加成功！');
				setTimeout(function(){
					window.location.href = location.href;
				},2000)
			}
			var addshelvefail = function(){
				alert('货架添加失败！');
			}
			var shelvedata = {
				'storeid': 		store['storeid'],
				'areaid': 		$addshelvebtn.parent().find('input[name=Areaid]').val(),
				'placeno': 		$('.modal6 input[name=shelveplaceno]').val(),
				'comment': 		$('.modal6 input[name=shelvecomment]').val()
			}
			util.ajax_post('addstrshelve.php',{shelvedata:shelvedata},addshelvesuccess,addshelvefail);

			$(".addshelvewindow").hide();
		}
	})
	//确认删除btn
	$('.deleteshelvesub').on('click',function(){
		//删除行
		$(this).parent().parent().parent().hide();

		var shelveid = $delshelvebtn.parent().find('input').val();
		var delsuccess = function(){
			alert('货架删除成功！');
			location.reload();
		}
		var delfail = function(errno){
			switch (errno) {
				case 401:
				alert('该货架下有货位不能删除该货架！');
				break;
				case 500:
				alert('删除货架失败！');
				break;
			}
		}
		util.ajax_post('delsal.php',{salid:shelveid},delsuccess,delfail);
	});
	//编辑货架后进行提交btn
	$('.editshelvesub').on('click',function(){
		var editshelvesuccess = function(msg){
			console.log(msg);
		}
		var editshelvefail = function(){
			console.log('editdata fail!');
		}
		var editdata = {
			'shelveid': 		$editshelvebtn.parent().find('input[name=shelveid]').val(),
			'shelveno': 		$('.editshelvewindow').find('.shelveeditnum').val(),
			'shelvecomment': 	$('.editshelvewindow').find('.editshelvecomment').val()
		}
		util.ajax_post('updatestoreshelve.php',{editdata:editdata},editshelvesuccess,editshelvefail);

		$(".editshelvewindow").hide();
		if(typeof($shelveeditnum) != 'undefined') {
			$editshelvebtn.parent().parent().children().eq(0).html($shelveeditnum);
		}
	})

	/*==============================货架end=========================================*/
	/*==============================货位start=========================================*/
	//添加货位;
	$('.Location').on('click',function(){
		$addlocationbtnthis = $(this);
		//显示货位添加窗口
		$('.addlocationwindow').show();
		$(".lbastore").val(store['store']);
		$('.lbaarea').val(store['area']);
		$('.lbashelve').val(store['shelve']);
		//获取货架名称
		$(".lbslocation").change(function(){
			$lbslocation=$(this).val();
		});
	})
	//添加货位后的提交btn
	$('.alocationsub').on('click',function(){

		if($(".lbslocation").val()==""){
			$(".lbslocation").focus();
		}else{
			var $tr2=$('<tr class="table-child1-click locationtr"></tr>');
			var $td21=$('<td class="click"></td>');
			var $td22=$('<td class="td-right td-right3"><input type="hidden" name="locationid"/><span class="locationedit glyphicon glyphicon-list-alt"></span> <span class="locationdel glyphicon glyphicon-trash"></span> <a class="warehouse-method" target="_blank" href="waregoodset.php">商品设置</a></td>');
			$tr2.append($td21);
			$tr2.append($td22);
			//追加货位
			$('.locationlist').append($tr2);
			//显示到列表
			$tr2.children(".click").html($lbslocation);
			$tr2.children().eq(0).removeClass("click");
			$('.addsuccess').css('display','block');
			$('.addsuccess').html('数据提交中······');

			//添加货位到数据库
			var addlocationsuccess = function(msg){
				$('.addsuccess').html('数据添加成功！');
				setTimeout(function(){
					window.location.href = location.href;
				},2000)
			}
			var addlocationfail = function(){
				console.log('addshelve fail!');
			}
			var locationdata = {
				'storeid': 			store['storeid'],
				'shelveid': 		$addlocationbtnthis.parent().find('input[name=Shelveid]').val(),
				'placeno': 			$('.lbslocation').val(),
				'comment': 			$('.lbacomment').val()
			}
			util.ajax_post('addlocationinfo.php',{locationdata:locationdata},addlocationsuccess,addlocationfail);

			$(".addlocationwindow").hide();
		}
	})
	//确认删除货位btn
	$('.deletelocationsub').on('click',function(){
		//删除行
		$dellocationbtn.parent().parent().remove();
		$(this).parent().parent().parent().hide();

		var locationid = $dellocationbtn.parent().find('input').val();
		var delsuccess = function(){
			console.log('delete storelocation success!');
		}
		var delfail = function(){
			console.log('delete storeinfo fail!');
		}
		util.ajax_post('delsal.php',{salid:locationid},delsuccess,delfail);
	})
	//修改货位后提交btn
	$('.elocationsub').on('click',function(){
		var editlocsuccess = function(msg){
			console.log(msg);
		}
		var editlocfail = function(){
			console.log('editdata fail!');
		}
		var editdata = {
			'locationid': 		$editlocationbtn.parent().find('input[name=locationid]').val(),
			'locationno': 		$('.editlocationwindow').find('.lbslocation').val(),
			'locationcomment': 	$('.editlocationwindow').find('.lbacomment').val()
		}
		util.ajax_post('updatestorelocation.php',{editdata:editdata},editlocsuccess,editlocfail);

		$(".editlocationwindow").hide();
		if(typeof($lbslocation) != 'undefined') {
			$editlocationbtn.parent().parent().children().eq(0).html($lbslocation);
		}
	})

	/*==============================货位end=========================================*/

	//关闭窗口；
	$(".close-btn").click(function(){
		$(this).parent().parent().parent().hide();
	});
});
