$(function () {
	$('.left_hand ul li').click(function(){
		$(this).css('background','#FBF1E8');
		$(this).siblings().css('background','');
		$('.right_hand ul li').css('background','');
	})

	$('.Sign_in').click(function(){
		$(this).html('已签到');

	})

	$('.right_hand ul li').click(function(){
		$(this).css('background','#FBF1E8');
		$(this).siblings().css('background','');
		$('.left_hand ul li').css('background','');
	})

	$('input[name=date]').datetimepicker({
		language:'zh-CN',
		format:'yyyy-mm-dd',
		autoclose:true,
		minView:2,
    });
});

