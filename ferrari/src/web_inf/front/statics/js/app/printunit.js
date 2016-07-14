$(function(){
	/*添加印刷分类*/
	$(".print-add").click(function(){
		$(".modal-print").show();
	});

	//删除类型
	$(".del").click(function(){
		var printunitid = $(this).attr('printunitid');
		$(".modal-print1").show();

		$('.delpusub').off('click').click(function(){
			$(".modal-print1").hide();
			var delpusuccess = function(msg)
			{
				location.reload();
			}
			var delpufail = function()
			{
				alert('删除印刷单位失败！');
				location.reload();
			}
			util.ajax_post('/app/delprintunit.php',{puid:printunitid},delpusuccess,delpufail);
		});
	});

	/*修改类型*/
	$(".print-change").click(function(){
		var printunitid  = $(this).attr('printunitid');
		var printname    = $(this).closest('tr').find('.print-name').text();
		var printcomment = $(this).closest('tr').find('.print-text').text();

		$('input[name=printunitid]').val(printunitid);
		$('.puname').val(printname);
		$('.pucomment').val(printcomment);
		$(".modal-print2").show();

	});

	/*修改时的确认btn*/
	$('.editpusub').off('click').click(function(){
		var printunitid  = $('input[name=printunitid]').val();
		var printname    = $('.puname').val();
		var printcomment = $('.pucomment').val();

		$(".modal-print2").hide();
		var editpusuccess = function(msg)
		{
			location.reload();
		}
		var editpufail = function()
		{
			alert('修改印刷类型失败！');
			locatioin.reload();
		}
		util.ajax_post('/app/editprintunit.php',{puid:printunitid,puname:printname,pucomment:printcomment},editpusuccess,editpufail);
	});
})