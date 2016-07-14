$(function(){
	/*行数*/
	var number = 0;

	/*添加商品*/
     $(".wareallocate-add").click(function(){
		addTableTr();
	});

     /*全选商品*/
	$(".allcheck").bind("click",function(){
		if(this.checked){
			$("input[type='checkbox']").each(function(){
				this.checked=true;
			})
		}else{
			$("input[type='checkbox']").each(function(){
				this.checked=false;
			})
		}
	})

	//删除商品;
	$(".wareallocate-del").click(function(){
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

	/*显示tr的条数*/
	function addTableTr()
	{
		number++;
		var addtrsuccess = function(msg)
		{
			var $wtbody=$(".wareallocate-tbody");
			var $tr=$('<tr class="enterorout-tr"></tr>');
			var $td1=$('<td class="enterorout-td">'+number+'</td>');
			var $td2=$('<td><label class="checkbox-all"><input class="checkbox-choice" type="checkbox" value=""></label></td>');

			var $td3='<td><select class="form-control storeoutlist"><option value="-1">请选择</option>';
			$.each(msg,function(i,v){
				$td3+='<option value="'+v.id+'">'+v.name+'</option>';
			});
			$td3+='</select></td>';

			var $td4=$('<td><input type="text" class="form-control no-border searchpro"></td>');
			var $td5=$('<td><select class="form-control no-border pronamelist"><option value="-1">请选择商品</option></select></td>');
			var $td6=$('<td><input class="form-control no-border pronumbers" readonly></td>');
			var $td7=$('<td><select class="form-control no-border allocate"><option value="-1">请选择</option><option value="Product">仅产品本身</option><option value="Accessory">和配件一起</option></select></td>');

			var $td8='<td><select class="form-control storeinlist"><option value="-1">请选择</option>';
			$.each(msg,function(i,v){
				$td8+='<option value="'+v.id+'">'+v.name+'</option>';
			});
			$td8+='</select></td>';

			var $td9=$('<td><input type="text" class="form-control noborder writenum" onkeyup="value=value.replace(\/[\^\\d\\\.]\/g,\'\')"></td>');
			var $td10=$('<td><input type="text" class="form-control noborder comment"></td>');
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
			$wtbody.append($tr);
		}
		var addtrfail = function()
		{
			console.log('add tr fail!');
		}
		util.ajax_post('/warehouse/liststoredata.php',{flag:'ok'},addtrsuccess,addtrfail);
	}
	addTableTr();

	/**选定仓库后搜索商品**/
	$('.wareallocate-table').on('blur','.searchpro',function(){
		var searchinput = $(this);
		//仓库ID
		var storeid = $(this).closest('tr').find('.storeoutlist option:selected').val();
		if (storeid == -1 || !storeid)
		{
			alert('请选择仓库！');
			return false;
		}

		//商品名称
		var proname = $(this).val();

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

	/*仓库修改时*/
	$('.wareallocate-table').on('change','.storeoutlist',function(){
		$(this).closest('tr').find('.searchpro').val('');
		$(this).closest('tr').find('.pronamelist').empty().append('<option value="-1">请选择商品</option>');
		$(this).closest('tr').find('.pronumbers').val('');
	});

	/*商品名称改变时*/
	$('.wareallocate-table').on('change','.pronamelist',function(){
		//商品的数量
		var pronum = $(this).parent().find('.pronamelist option:selected').attr('totalreal');
		//数量同步
		$(this).closest('tr').find('.pronumbers').val(pronum);
	});

	/*判断数量的变化*/
	$('.wareallocate-table').on('blur','.writenum',function(){
		//总数量
		var totalreal = $(this).closest('tr').find('.pronumbers').val();
		if (parseInt($(this).val()) >= parseInt(totalreal))
		{
			$(this).val(totalreal);
		}
	});

	/**调拨单提交btn**/
	$('.enterorout-sub').click(function(){

		var allocatearr   = new Object();
		var flag = true;
		//进行判断
		$.each($('.pronamelist'),function(i){
			if ($('.pronamelist').eq(i).val() == -1)
			{
				alert('第'+(i+1)+'行的商品名不能为空！');
				return flag = false;
			}

			//调拨类型
			if ($('.allocate').eq(i).val() == -1)
			{
				alert('第'+(i+1)+'行调拨类型不能为空！');
				return flag = false;
			}

			//仓库
			if ($('.storeoutlist').eq(i).val() == -1 || $('.storeinlist').eq(i).val() == -1)
			{
				alert('第'+(i+1)+'行仓库不能为空！');
				return flag = false;
			}

			//数量
			if (!$('.writenum').eq(i).val())
			{
				alert('第'+(i+1)+'行数量不能为空！');
				return flag = false;
			}

			/*获取数据*/
			var allocateinarr = new Object();
			//调出仓库
			allocateinarr['moveoutid'] = $('.storeoutlist').eq(i).val();
			//调入仓库
			allocateinarr['moveinid']  = $('.storeinlist').eq(i).val();
			//商品ID
			allocateinarr['productid'] = $('.pronamelist').eq(i).val();
			//调拨类型
			allocateinarr['movetype']  = $('.allocate').eq(i).val();
			//调拨数量
			allocateinarr['total']     = $('.writenum').eq(i).val();
			//备注
			allocateinarr['comment']   = $('.comment').eq(i).val();

			allocatearr[i] = allocateinarr;
		});

		if (flag) {
			//求长度
			var count=0;
			for(var key in allocatearr){
				count++;
			}

			if (count > 0)
			{
				var	addallocatesuccess = function(msg)
				{
					alert('添加调拨单成功！');
					location.reload();
				}
				var addallocatefail = function()
				{
					alert('添加调拨单失败！');
					// location.reload();
				}
				util.ajax_post('/warehouse/addwareallocate.php',{allocatearr:allocatearr},addallocatesuccess,addallocatefail);
			}
		}
	});

	/*重置*/
	$('.resetbtn').click(function(){
		var url = location.href.substring(0,location.href.indexOf('?'));
		window.location.href = url;
	});

})