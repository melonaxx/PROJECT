$(function(){
	/*三级联动*/
	var stateid = $(".pro").attr("pro");
	var cityid = $(".city").attr("city");
	var districtid = $(".county").attr("county");
	searchpcc("pro","city","county",stateid,cityid,districtid);

	//修改
	$(".custom-change").click(function(){
		if($(".custom-custom").val()!=""){
			var cusobj = {
				id			  : $('input[name=cusid]').val(),
				customertype  : $('.customertype option:selected').val(),
				pro           : $('.pro option:selected').val(),
				city          : $('.city option:selected').val(),
				county        : $('.county option:selected').val(),
				customername  : $('.customername').val(),
				customernick  : $('.customernick').val(),
				customerphone : $('.customerphone').val(),
				clienttel     : $('.clienttel').val(),
				payment       : $('.payment').val(),
				customercom   : $('.customercom').val(),
				cusemail      : $('.cusemail').val(),
				cusQQ         : $('.cusQQ').val(),
				cuspostcode   : $('.cuspostcode').val(),
				cusaddress    : $('.cusaddress').val(),
				cuscomment    : $('.cuscomment').val()
			}

			var editcussuccess =  function(msg)
			{
				alert('修改客户信息成功！');
				location.reload();
			}
			var editcusfail = function()
			{
				alert('修改客户信息失败！');
				location.reload();
			}
			util.ajax_post('/crm/editcusdata.php',{cusobj:cusobj},editcussuccess,editcusfail);
		}
	});
})
