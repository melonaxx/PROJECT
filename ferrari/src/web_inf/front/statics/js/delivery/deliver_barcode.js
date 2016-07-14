$(function(){
	/*通过订单编号或快递单号进行订单信息的查询*/
    $(".numberid").on("keydown", function(e) {
        var keyCode = e.keyCode;
        if (keyCode == 13) {
            searchorderpro();
            return false;
        }
    });
    function searchorderpro()
    {
    	var prodata = {
			numberid   : $('.numberid').val(), //订单或快递编号
			searchtype : $('.searchtype').val() //获取查询的类型
    	}

    	var getoprosuccess = function(msg)
    	{
    		console.log(msg); return false;
    		$('.comment').text(msg.orderdata.comment);
			$('.cusmsg').text(msg.orderdata.cusmsg);
			$('.cusname').text(msg.orderdata.cusname);
			$('.onlineid').text(msg.orderdata.onlineid);
			$('.transportid').text(msg.orderdata.transportname);
			$('.waybill').text(msg.orderdata.waybill);

			var protr = '';
			$.each(msg.productdata,function(i,v){
				$protr += '\
						<tr style="text-align:center;">\
							<td>1</td>\
							<td>2345</td>\
							<td>2345</td>\
							<td>2345</td>\
							<td>2345</td>\
							<td>2345</td>\
							<td>2345</td>\
						</tr>';
			});
    	}
    	var getoprofail = function()
    	{
    		console.log('get orderpro data fail!');
    	}
    	util.ajax_post('/delivery/getoprodata.php',{prodata:prodata},getoprosuccess,getoprofail);
    }


	$(".btn-strong").click(function(){
		$(".modal-barcode1").show();
	});
	var flag=true;
	$(".open-barcode").click(function(){
		if(flag){
			$(".good-barcode").focus();
			$(".open-method").html("关闭");
			flag=false;
		}else{
			flag=true;
			$(".open-method").html("开启");
		}
	})
})