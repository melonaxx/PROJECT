$(function(){
    /**
     * @brief 获取当前url地址后的所有参数
     * @return [object] 参数对象
     */
    function GetRequest() {
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

	/*图片放大效果*/
	$(".img1").each(function(i){
		$('.img1').eq(i).on("mouseover",function(){
			$(".img2").eq(i).show();
			var height=$(this).siblings(".img2").height();
			var height1=$(this).height();
			var top=$(this).siblings(".img2").position().top;
			$(this).on("mousemove",function(event){
				var event = event || window.event;
				var gao=$(window).height();
				var iheight =gao -(event.clientY+height1+10);
				var top1 = (iheight < height?-height:top) + "px";
				$(this).siblings(".img2").css("top",top1);
			});
		});
	});
	$(".img1").each(function(i){
		$(this).on("mouseout",function(){
			$(".img2").eq(i).hide();
		});
	})

	/*仓库的状态*/
	if (GetRequest().storeid) {
		$.each($('.storelist option'),function(i,v){
			if (v.value == GetRequest().storeid) {
				$('.storelist option').eq(i).prop('selected',true);
			}
		});
	}

	 //全选商品;
	 var flag=false;
	$(".allcheck").bind("click",function(){
		if(this.checked){
			$("input[type='checkbox']").each(function(){
				this.checked=true;
				flag=true;
			});
		}else{
			$("input[type='checkbox']").each(function(){
				this.checked=false;
				flag=false;
			});
		}
	});

	/*生成盘点单*/
	$(".create-check").click(function(){

		//判断是否进行了选择数据
		var flag = false;
		$("input[name=selectnum]").each(function(){
			if(this.checked==true){
				$(".modal-check").show();
				flag=true;
			}
		});
		if(flag){
			$(".modal-check").show();
		}else{
			$(".modal-warechecktip").show();
			return false;
		}

		//显示仓库
		var storename = $.trim($('.storelist option:selected').text()).replace(/\s+/g,'');
		var storeid = $.trim($('.storelist option:selected').val());
		$('.chekcstore').val(storename);

		//盘点后数量
		var checkafternum = new Object();

		//商品ID列表
		var productarr = new Object();
		$("input[name=selectnum]:checked").each(function(i,v){
			var productid = $("input[name=selectnum]:checked").eq(i).parent().find('input[name=productid]').val();
			productarr[i] = productid;

			//盘点后数量
			checkafternum[i] = $("input[name=selectnum]:checked").eq(i).closest('tr').find('.checkafternum').val();
		})

		var listinfosuccess = function(msg)
		{
			var strtr = '';
			if (msg.length > 0) {
				$.each(msg,function(i,v){
					//盈亏数量
					var prolossnum = parseInt(checkafternum[i]-v.totalreal);
					//要添加的行
					strtr += '<tr>\
									<td>'+(i+1)+'</td>\
									<td class="warestatus-tbody-img" style="text-align: center;">\
										<img src="'+v.path+v.image+'" class="img1"/>\
										<img src="'+v.path+v.image+'" class="img2"/>\
									</td>\
									<td><a href="/goods/goodsentry.php?productid='+v.productid+'" >'+v.name+'</a></td>\
									<td>'+v.format+'</td>\
									<td>'+v.number+'</td>\
									<td>'+v.totalreal+'</td>\
									<td>'+checkafternum[i]+'</td>\
									<td>'+prolossnum+'</td>\
									<td>\
										<input type="text" class="form-control aftercheckcomment" style="border:none;">\
									</td>\
								</tr>';
				});

				//进行追加
				$('.warestatus-modaltbody').empty().append(strtr);
			}
		}
		var listinfofail = function()
		{
			console.log('list info fail!');
		}
		util.ajax_post('/warehouse/showcheckbyproidstrid.php',{storeid:storeid,productarr:productarr},listinfosuccess,listinfofail);
	});

	/*获取参数*/
	function getParam(){
		var paramarr = new Object();
		//页大小
		if ($('.rrow option:selected').val() !='undefined')
		{
			var pagesize = $('.rrow option:selected').val();
		} else {
			var pagesize = '';
		}
		paramarr['pagesize'] = pagesize;

		//起始时间
		if ($('.storelist').val() != 'undefined' || $('.storelist').val())
		{
			var storelist = $('.storelist').val();
		} else {
			var storelist = '';
		}
		paramarr['storelist'] = storelist;

		//当前页数
		if (GetRequest().page)
		{
			var page = GetRequest().page;
		} else {
			var page = 1;
		}
		paramarr['page'] = page;

		return paramarr;
	}

	/*保持搜索分页状态*/
	function keepPageSearch(search)
	{
		var page 			= getParam().page;
		if (search)
		{
			page = 1;
		}
		var storeid 		= getParam().storelist;
		var pagesize 		= getParam().pagesize;
		var url = location.href.substring(0,location.href.indexOf('?'));
		window.location.href=url+"?page="+page+"&storeid="+storeid+"&pagesize="+pagesize;
	}

	/*进行仓库搜索*/
	$('.storesearchbtn').click(function(){
		keepPageSearch('search');
	});

	/*改变页大小*/
	$('.rrow').change(function(){
		keepPageSearch();
	});

	/*生成盘点单后的提交btn*/
	var storeid = $.trim($('.storelist option:selected').val());//仓库ID
	var checksuccobj = new Object();

	$('.addcheckbtn').click(function(){
		//获取备注
		var aftercheckcomment = new Object();
		$.each($('.aftercheckcomment'),function(i,v){
			aftercheckcomment[i] = v.value;
		});

		$(".modal-check").hide();

		var selectnum = $("input[name=selectnum]:checked");
		selectnum.each(function(i,v){
			var checkinobj   = new Object();
			checkinobj['storeid']   = storeid;
			checkinobj['productid'] = selectnum.eq(i).parent().find('input[name=productid]').val();
			checkinobj['number']    = selectnum.eq(i).closest('tr').find('td').eq(6).text(); //原来数量
			checkinobj['totalreal'] = selectnum.eq(i).closest('tr').find('.checkafternum').val(); //实际数量
			checkinobj['comment']   = aftercheckcomment[i];
			//盈亏数量
			checkinobj['total']     = parseInt(checkinobj['totalreal'])-parseInt(checkinobj['number']);

			checksuccobj[i] = checkinobj;
		});

		var addchecksuccess = function(msg)
		{
			alert('添加仓库调拨单成功！')
			location.reload();
		}
		var addcheckfail = function()
		{
			alert('添加仓库调拨单失败！')
			location.reload();
		}
		util.ajax_post('/warehouse/addstorecheckbill.php',{checksuccobj:checksuccobj},addchecksuccess,addcheckfail);
	});
})