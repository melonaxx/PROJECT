/*
* 	后台登陆时的检测
*/
$('.mageLogin').click(function(){

	if ($(this).data('disabled')) {
		return;
	}

	var data = verifyInput();

	if (!data) {
		return false;
	}

	//清除错误信息
	clearErrMsg();

	//防止多次点击
	$(this).val('登陆中···').data('disabled',true);
	setTimeout(function(){
		util.ajax_post('verify_login.php',data,mLoginSuccess,mLoginFail,function(){
			$('.mageLogin').val('登陆').removeData('disabled');
		});
	},1000)

});

function verifyInput(){
	var mageLogin = $('input[name=mageName]').val();
	var pwd = $('input[name=password]').val();
	return {
		name:mageLogin,
		pwd:pwd
	};
}

function mLoginSuccess(msg){
	window.location.href = 'sensorindex.php';
}

function mLoginFail(errno){
	if(errno < 0) {
		showErrMsg('网络连接失败，请检查网络');
	}else {
		var errMsg = '';
		switch (errno) {
			case 404:
				errMsg = '用户名或密码错误！';
				break;
		}

		showErrMsg(errMsg);
	}
}

function showErrMsg(msg) {
	$('.errmsg_container').css('display','block');
	$('.errmsg_container').text(msg);
}

function clearErrMsg() {
	$('.errmsg_container').css('display','none');
	$('.errmsg_container').text('');
}