$(function(){
	//添加商品窗口显示
	$('.add-waregood').click(function(){
		$('#waregood-modal').show();
	});
	// 模态窗添加商品
	$(".waregood-add").click(function(){
		var $tr=$('<tr class="modal-tr"></tr>');
		var $td1=$('<td class="modal-td"></td>');
		var $td2=$('<td><label class="checkbox-all"><input class="checkbox-choice" type="checkbox" value=""></label></td>');
		var $td3=$('<td><input type="text" class="form-control modal-noborder searchgoods"></td>');
		var $td4=$('<td><img class="goodsimg" src="" style="width:20px;height:20px;"></td>');
		var $td5=$('<td><select class="form-control modal-noborder goodselectlist"><option>请选择商品</option></select></td>');
		var $td6=$('<td></td>');
		$tr.append($td1);
		$tr.append($td2);
		$tr.append($td3);
		$tr.append($td4);
		$tr.append($td5);
		$tr.append($td6);
		$(".waregood-modaltbody") .append($tr);
		if($(".modal-tr").length==1){
			$td1.html(1);
		}else{
			$td1.html(Number($tr.prev().children().eq(0).html())+1);
		}
	});
	//全选商品;
	$(".allcheck").on("click",function(){
		if(this.checked){
			$("input[type='checkbox']").each(function(){
				this.checked=true;
			})
		}else{
			$("input[type='checkbox']").each(function(){
				this.checked=false;
			})
		}
	});
	//删除商品;
	$(".waregood-del").click(function(){
		$("input[type='checkbox']").each(function(){
			if(this.checked==true){
				$(this).parent().parent().parent(".modal-tr").remove();
				$(".allcheck").prop("checked",false);
			}
		});
		$(".modal-td").each(function(i){
			$(this).html(i+1);
		});
	})
	//进行商品搜索
	$('.waregood-table').on('blur','.searchgoods',function(){
		var searchcontents = $(this).val();
		var searchgoodsinput = $(this);
		if (searchcontents) {
			var searchgoodsbynamesuccess = function(msg) {
				//清空option
				searchgoodsinput.closest('tr').find('.goodselectlist').empty();
				var selectoptions = '';
				$.each(msg,function(i,v){
					selectoptions += "<option unitname='"+v.unitname+"' image='"+v.image+"' value='"+v.productid+"'>"+v.name+"</option>";
				});
				//追加option
				searchgoodsinput.closest('tr').find('.goodselectlist').append(selectoptions);
				//图片
				if (selimage) {
					var selimage = searchgoodsinput.closest('tr').find('.goodselectlist option:selected').attr('image');
					searchgoodsinput.closest('tr').find('.goodsimg').attr('src','http://img.1sheng.com/'+selimage);
				}

			}
			var searchgoodsbynamefail = function(){
				console.log('searchgoodsbynamefail!');
			}
			util.ajax_post('/warehouse/searchgoodbyname.php',{goodname:searchcontents},searchgoodsbynamesuccess,searchgoodsbynamefail);
		} else {
			$(this).focus();
			return false;
		}
	});

	//select 选择商品名称
	$('.waregood-table').on('change','.goodselectlist',function(){
		var selgoodsimage = $(this).closest('tr').find('.goodselectlist option:selected').attr('image');
		var unitname = $(this).closest('tr').find('.goodselectlist option:selected').attr('unitname');
		$(this).parent().prev().find('.goodsimg').attr('src','http://img.1sheng.com/'+selgoodsimage);
		$(this).parent().closest('tr').find('.unitname').html(unitname);
	});

	//确认提交btn
	$('.sure-addgood').click(function(){
		var searchcon = $('.searchgoods').val();
		if (!searchcon) {
			$('.searchgoods').focus();
			return false;
		}
		//获取要添加商品的信息；
		var addgoodslist = $('.goodselectlist option:selected');
		//商品ID数组
		var goodidarr = new Object();
		$.each(addgoodslist,function(i,v){
			goodidarr[i] = v.value;
		});
		//仓库、区、架、位的ID
		var mypathdata = $('input[name=locstr]').val();
		goodidarr['path'] = mypathdata;
		//添加商品信息到数据库中
		var addstrrelatesuccess = function(msg){
			$('#waregood-modal').hide();
			location.reload();
		}
		var addstrrelatefail = function(){
			$('#waregood-modal').hide();
			location.reload();
		}
		util.ajax_post('/warehouse/addstrrelated.php',{goodidarr:goodidarr},addstrrelatesuccess,addstrrelatefail);
	});

	//删除仓库对应关系中的商品
	$('.delstrrelated').click(function(){
		//商品ID
 		var productid 	= $(this).parent().find('input[name=productid]').val();
		//仓库ID
		var storeid 	= $(this).parent().find('input[name=storeid]').val();
		//货位ID
		var locationid 	= $(this).parent().find('input[name=locationid]').val();

		var delproformstrsuccess = function(msg)
		{
			location.reload();
		}
		var delproformstrfail = function()
		{
			history.go(0);
			console.log('del pro from str fail!');
		}
		util.ajax_post('/warehouse/deloneproformstr.php',{productid:productid,storeid:storeid,locationid:locationid},delproformstrsuccess,delproformstrfail);
	});
})
