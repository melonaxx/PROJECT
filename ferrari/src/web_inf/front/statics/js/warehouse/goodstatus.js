$(function(){

	/*商品图片放大*/
	$(".img1").hover(
		function(){
			$(this).next().show();
		},
		function(){
			$(this).next().hide();
		}
	);

	/*通过商品名称进行搜索*/
	$('.proname').blur(function(){
		var proname = $(this).val();

		if (proname)
		{
			var searchProByIdsuccess = function(msg)
			{
				$('.pronamelist').empty();
				var pronamedata = '';

				$.each(msg,function(i,v){
					pronamedata += "<option value='"+v.productid+"'>"+v.name+"("+v.format+")</option>";
				});
				$('.pronamelist').append(pronamedata);
			}
			var searchProByIdfail = function()
			{
				$('.pronamelist').empty();
				console.log('search productinfo by proid fail!');
			}
			util.ajax_post('/warehouse/getgoodnamelist.php',{proname:proname},searchProByIdsuccess,searchProByIdfail);
		}

	});

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

	/*初始化当前页大小*/
	if (!GetRequest().pagesize)
	{
		$.each($('.rrow option'),function(i){
			if ($('.rrow option').eq(i).val() == 5)
			{
				$('.rrow option').eq(i).attr('selected',true);
			}
		});
	}

	/**获取参数列表**/
	function getparamlist()
	{

		//商品ID
		var productid = $('.pronamelist option:selected').val();
		//商品状态
		var prostatus = $('.prostatus option:selected').val();
		//当前页数
		var page = GetRequest().page;
		//页大小
		var pagesize = $('.rrow option:selected').val();

		if (!productid)
		{
			productid = '';
		}
		if (!prostatus || prostatus =='All')
		{
			prostatus = '';
		}
		if (!page)
		{
			page = 1;
		}
		if (!pagesize)
		{
			pagesize = 5;
		}

		var paramlist = new Array();
		paramlist['productid'] 	= productid;
		paramlist['prostatus'] 	= prostatus;
		paramlist['page'] 		= page;
		paramlist['pagesize'] 	= parseInt(pagesize);

		return paramlist;
	}

	/*保持商品状态*/
	if (GetRequest().salesstatus)
	{
		$.each($('.prostatus option'),function(i){
			if ($('.prostatus option').eq(i).val() == GetRequest().salesstatus)
			{
				$('.prostatus option').eq(i).attr('selected',true);
			}
		});
	}

	/*保持搜索分页状态*/
	function keepstatusdata(){
		var paramlist = getparamlist();
		var page 			= paramlist['page'];
		var productid 		= paramlist['productid'];
		var prostatus 		= paramlist['prostatus'];
		var pagesize 		= paramlist['pagesize'];

		var url = location.href.substring(0,location.href.indexOf('?'));
		window.location.href=url+"?page="+page+"&productid="+productid+"&salesstatus="+prostatus+"&pagesize="+pagesize;
	}

	/**进行搜索商品信息**/
	$('.searchproinfo').click(function(){
		keepstatusdata();
	});

	/*选择页大小*/
	$('.rrow').change(function(){
		keepstatusdata();
	});

	/**重置**/
	$('.resetbtn').click(function(){
		var url = location.href.substring(0,location.href.indexOf('?'));
		window.location.href = url;
	});

	/**位置数量详情**/
	$(".goodstatus-detail").click(function(){
		//商品ID
		var productid = $(this).closest('tr').find('input[name=productid]').val();

		var getlocdetailsuccess = function(msg)
		{
			if (msg)
			{
				var numbers = 0;	//序号
				var sumreal = 0;//实际数量和
				var sumlock = 0;//锁定数量和
				var sumway = 0;//在途数量和
				var sumproduction = 0;//生产中数量和
				var sumavailable = 0;//可用数量和
				var moneytotal = 0;//总价和

				//清空
				$('.datatr').empty();
				$.each(msg,function(i,v){
					numbers++;
					sumreal+=parseInt(v.totalreal);
					sumlock+=parseInt(v.totallock);
					sumway+=parseInt(v.totalway);
					sumproduction+=parseInt(v.totalproduction);
					sumavailable+=parseInt(v.totalavailable);
					moneytotal+=parseInt(v.totalmoney);

					var trdata = "\
						<tr>\
			    			<td>"+numbers+"</td>\
			    			<td>"+v.storename+"</td>\
			    			<td>"+v.totalreal+"</td>\
			    			<td>"+v.totallock+"</td>\
			    			<td>"+v.totalway+"</td>\
			    			<td>"+v.totalproduction+"</td>\
			    			<td>"+v.totalavailable+"</td>\
			    			<td>"+v.total+"</td>\
			    			<td>"+v.totalmoney+"</td>\
			    		</tr>";


			    	$('.datatr').append(trdata);
				});
		    	var sumtr = "\
			    	<tr>\
		    			<td>合计</td>\
		    			<td></td>\
		    			<td>"+sumreal+"</td>\
		    			<td>"+sumlock+"</td>\
		    			<td>"+sumway+"</td>\
		    			<td>"+sumproduction+"</td>\
		    			<td>"+sumavailable+"</td>\
		    			<td></td>\
		    			<td>"+moneytotal+"</td>\
		    		</tr>";

		    	$('.sumtr').empty().append(sumtr);
			}
		}

		var getlocdetailfail = function(){
			//清空
			$('.datatr').empty();
			$('.sumtr').empty();
			console.log('get loction detail fail!');
		}
		util.ajax_post('/warehouse/showproinstroe.php',{productid:productid},getlocdetailsuccess,getlocdetailfail);
		$(".modal-detail").show();

	});
})