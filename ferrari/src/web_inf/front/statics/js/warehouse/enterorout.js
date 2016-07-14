$(function(){

	/*模态窗添加商品*/
     $(".enterorout-add").click(function(){
		var $tr=$('<tr class="enterorout-tr"></tr>');
		var $td1=$('<td class="enterorout-td"></td>');
	    var $td2=$('<td><label class="checkbox-all"><input class="checkbox-choice" type="checkbox" value=""></label></td>');
		var $td3=$('<td><select class="form-control enterorout-ware no-border selectstore"><option value="-1">请选择仓库</option></select></td>');
		var $td4=$('<td><input type="text" class="form-control no-border proname"></td>');
		var $td5=$('<td><select class="form-control no-border pronamelist"><option value="-1">请选择商品</option></select></td>');
		var $td6=$('<td><input type="text" readonly class="form-control no-border pronumbers"></td>');
		var $td7=$('<td><select class="form-control no-border inoutstore"><option value="-1">请选择</option><option  value="Input">入库</option><option  value="Output">出库</option></select></td>');
		var $td8=$('<td><select class="form-control no-border inouttype"><option value="-1">请选择</option>\
			      						<option value="M">生产</option>\
			      						<option value="P">进货</option>\
			      						<option value="L">盘点</option>\
			      						<option value="S">销售</option>\
			      						<option value="W">损耗</option></select></td>');
		var $td9=$('<td><input type="text" name="pronumber" class="form-control no-border productnum" onkeyup="value=value.replace(\/[\^\\d\\\.]\/g,\'\')"></td>');
		var $td10=$('<td><input type="text" name="comment" class="form-control no-border"></td>');
		$tr.append($td1);
		$tr.append($td2);
		$tr.append($td3);
		$tr.append($td4);
		$tr.append($td5);
		$tr.append($td6);
		$tr.append($td7);
		$tr.append($td8);
		$tr.append($td9);
		$tr.append($td10);
		$(".enterorout-tbody") .append($tr);
		if($(".enterorout-tr").length==1){
			$td1.html(1);
		}else{
			$td1.html(Number($tr.prev().children().eq(0).html())+1);
		}

		/*列出所有的仓库*/
		var optiondata = '';
		var liststoresuccess = function(msg)
		{
			//清空
			$tr.find('.selectstore').empty();
			var storetype = '';
			optiondata +="<option value=\"-1\">请选择仓库</option>";
			$.each(msg,function(i,v){
				if (v.storetype == "Sales")
				{
					storetype = '销售仓';
				} else if (v.storetype == 'Defective') {
					storetype = '次品仓';
				} else if (v.storetype == 'Customer') {
					storetype = '销售仓';
				} else if (v.storetype == 'Purchase') {
					storetype = '采购仓';
				}
				optiondata += "<option value='"+v.id+"'>"+v.name+"("+storetype+")"+"</option>"
			});

			$tr.find('.selectstore').append(optiondata);;
		}
		var liststorefail = function()
		{
			console.log('list store fail!');
		}
		util.ajax_post('/warehouse/liststoredata.php',{list:'ok'},liststoresuccess,liststorefail);
	});

	/*仓库变化时重置商品信息*/
	$('.enterorout-tbody').on('change','.selectstore',function(){
		$(this).closest('tr').find('.proname').val(''); //搜索名
		$(this).closest('tr').find('.pronumbers').val(''); //实际库存总数量
		$(this).closest('tr').find('.pronamelist').empty().append('<option value="-1">请选择商品</option>');
	})

	/*通过商品名称进行搜索*/
	$('.enterorout-tbody').on('blur','.proname',function(){
		var proname = $(this).val();
		var searchinput = $(this);

		/*判断有没有选择仓库*/
		var storeid = searchinput.closest('tr').find('.selectstore option:selected').val();

		if (storeid == -1 || !storeid)
		{
			alert('请选择仓库！');
			return false;
		}

		/*是否有要搜索的商品名称*/
		if (proname)
		{
			var searchProByIdsuccess = function(msg)
			{
				searchinput.closest('tr').find('.pronamelist').empty();
				var pronamedata = '';

				$.each(msg,function(i,v){
					pronamedata += "<option totalreal='"+v.totalreal+"' value='"+v.productid+"'>"+v.name+"("+v.format+")</option>";
				});
				searchinput.closest('tr').find('.pronamelist').append(pronamedata);

				var totalreal = searchinput.closest('tr').find('.pronamelist option:selected').attr('totalreal');//获取单个商品总数
				searchinput.closest('tr').find('.pronumbers').val(totalreal);//商品的总数量
			}
			var searchProByIdfail = function()
			{
				searchinput.closest('tr').find('.pronamelist').empty();
				console.log('search productinfo by proid fail!');
			}
			util.ajax_post('/warehouse/listprobystoreid.php',{proname:proname,storeid:storeid},searchProByIdsuccess,searchProByIdfail);
		}
	});

	/*填写商品的数量*/
	$('.enterorout-tbody').on('blur','.productnum',function(){
		//如果是出库的话
		var inouttype = $(this).closest('tr').find('.inoutstore').val();
		if (inouttype == 'Output')
		{
			var productnum = $(this);
			var totalreal = productnum.closest('tr').find('.pronumbers').val();
			if (!totalreal)
			{
				totalreal = 0;
			}

			var productnumber = $(this).val();
			if (parseInt(productnumber) > parseInt(totalreal))
			{
				$(this).val(0);
			}
		}

	});

	/*入库类型改时*/
	$('.enterorout-tbody').on('change','.inoutstore',function(){
		$(this).closest('tr').find('.productnum').val('');
	});

	/*商品名称改变时*/
	$('.enterorout-tbody').on('change','.pronamelist',function(){
		//商品的数量
		var pronum = $(this).parent().find('.pronamelist option:selected').attr('totalreal');
		//数量同步
		$(this).closest('tr').find('.pronumbers').val(pronum);
	});

    //全选商品;
	$(".allcheck").bind("click",function(){
		if(this.checked){
			$("input[type='checkbox']").each(function(){
				this.checked=true;
			});
		}else{
			$("input[type='checkbox']").each(function(){
				this.checked=false;
			});
		}
	});
	//删除商品;
	$(".enterorout-del").click(function(){
		$("input[type='checkbox']").each(function(){
			if(this.checked==true){
				$(this).parent().parent().parent(".enterorout-tr").remove();
				$(".allcheck").attr("checked",false);
			}
		});
		$(".enterorout-td").each(function(i){
		   	 $(this).html(i+1);
		 });
	});

	/**手动出入库提交btn**/
	$(".enterorout-form").on('click','.enterorout-sub',function(){
		//商品名称
		var flag = true;
		$.each($('.selectstore'),function(i){
			if ($('.selectstore').eq(i).val() == -1)
			{
				alert('第'+(i+1)+'个商品的商品名不能为空！');
				return flag = false;
			}

			//出入库
			if ($('.inoutstore').eq(i).val() == -1)
			{
				alert('第'+(i+1)+'个商品出入库不能为空！');
				return flag = false;
			}

			//类型
			if ($('.inouttype').eq(i).val() == -1)
			{
				alert('第'+(i+1)+'个商品类型不能为空！');
				return flag = false;
			}
		});

		if (flag) {
			var inoutstrarr = new Object(); //出入库数组

			/**获取商品的数据**/
			$.each($('.productnum'),function(i){

				var inarr = new Object(); //内层数组

				//商品数量
				if ($('.productnum').eq(i).val() !='0')
				{
					//仓库ID
					var strid = $('.selectstore').eq(i).closest('tr').find('.selectstore option:selected').val();
					//商品ID
					var proid = $('.pronamelist').eq(i).closest('tr').find('.pronamelist option:selected').val();
					//用途类型
					var purposetype = $('.inouttype').eq(i).closest('tr').find('.inouttype option:selected').val();
					//商品数量
					var pronum = $('.productnum').eq(i).val();
					//商品备注
					var comment = $('input[name=comment]').eq(i).val();

					//商品出入库
					if ($('.inoutstore').eq(i).val() == 'Input')
					{
						//商品入库
						var type = 'Input';

					} else if ($('.inoutstore').eq(i).val() == 'Output') {
						//商品出库
						var type = 'Output';
					}

					inarr['strid'] 			= strid;
					inarr['proid'] 			= proid;
					inarr['purposetype'] 	= purposetype;
					inarr['pronum'] 		= pronum;
					inarr['comment'] 		= comment;
					inarr['type'] 			= type;

					inoutstrarr[i]  = inarr;
				}
			});

			var strinoutsuccess = function(msg)
			{
				alert('手动出入库成功！');
				window.location = 'warehouse/storagerecord.php';
			}
			var strinoutfail = function()
			{
				alert('手动出入库失败！');
				window.location.reload();
			}
			util.ajax_post('/warehouse/addinoutstore.php',{inoutstrarr:inoutstrarr},strinoutsuccess,strinoutfail);
		}

	});

	/*重置*/
	$('.resetbtn').click(function(){
		var url = location.href.substring(0,location.href.indexOf('?'));
		window.location.href = url;
	});
})