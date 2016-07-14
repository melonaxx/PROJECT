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

	/*获取url中的数据*/
	function getParam()
	{
		var paramarr = new Object();
		//页大小
		if ($('.rrow option:selected').val())
		{
			var pagesize = $('.rrow option:selected').val();
		} else {
			var pagesize = 5;
		}
		paramarr['pagesize'] = pagesize;

		//仓库ID
		if ($('.storelist option:selected').val())
		{
			var storeid = $('.storelist option:selected').val();
		}
		paramarr['storeid'] = storeid;

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
		var storeid 		= getParam().storeid;
		var pagesize 		= getParam().pagesize;
		var url = location.href.substring(0,location.href.indexOf('?'));
		window.location.href=url+"?page="+page+"&storeid="+storeid+"&pagesize="+pagesize;
	}

	/*通过仓库ID进行查询*/
	$('.searchbystr').click(function(){
		keepPageSearch('search');
	});

	/*修改页大小时*/
	$('.rrow').change(function(){
		keepPageSearch();
	});

	/*保持仓库选项的选中状态*/
	if (GetRequest().storeid)
	{
		$.each($('.storelist option'),function(i){
			if ($('.storelist option').eq(i).val() == GetRequest().storeid)
			{
				$('.storelist option').eq(i).attr('selected',true);
			}
		});
	}

	/*查看记录详情*/
	$(".storagerecord-see").click(function(){
		//获取商品ID
		var productid = $(this).parent().find('input[name=productid]').val();
		//获取strmanual表中的id
		var id = $(this).parent().find('input[name=id]').val();
		//获取仓库ID
		var storeid = $('.storelist option:selected').val();

		var showdetailsuccess = function(msg)
		{
			$('.iostore').val(msg.type);
			$('.purposetype').val(msg.purposetype);
			$('.storename').val(msg.storename);
			$('.manualdate').val(msg.date);
			$('.operator').val(msg.staffid);
			$('.comments').val(msg.comment);
			$('.datatr').children().eq(1).text(msg.proname);
			$('.datatr').children().eq(2).text(msg.formatstr);
			$('.datatr').children().eq(3).text(msg.total);
		}
		var showdetailfail = function()
		{
			console.log('showdetail fail!');
		}
		util.ajax_post('/warehouse/listmanualdata.php',{productid:productid,id:id,storeid:storeid},showdetailsuccess,showdetailfail);
		$(".modal-storage").show();
	})

})