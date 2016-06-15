$(function(){
	$(".topright").on("mouseover",function(){
		$(".selectul").show();
	})
	$(".topright").on("mouseout",function(){
		$(".selectul").hide();
	})
	//用户ID
	var userid = $('input[name=mapuserid]').val();
	//劳务方ID
	var laborid = $('input[name=maplaborid]').val();
	//用户类型
	var usertype = $('input[name=mapusertype]').val();
	// 平台ID
	var platformid = $('input[name=mapplatformid]').val();
	//骑士序列号
	var knightseqno = $('input[name=knightseqno]').val();

	//改变查看定位的URL地址
	var GetReques = function() {
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
	var getskey, counter = 0;
	for(getskey in GetReques()) counter++;
	switch (usertype) {
		case '0': //员工
        	if (counter <= 0) {
        		$('.mapall').attr('href','/gotomap.php?inttype=uipandectall ');
        		$('.maprun').attr('href','/gotomap.php?inttype=uipandectrun');
        		$('.mapunusual').attr('href','/gotomap.php?inttype=uipandectunusual');
        		$('.maprest').attr('href','/gotomap.php?inttype=uipandectrest');
        	} else {
        		$('.mapall').attr('href','/gotomap.php?inttype=uiplatall&laborid='+laborid);
        		$('.maprun').attr('href','/gotomap.php?inttype=uiplatrun&laborid='+laborid);
        		$('.mapunusual').attr('href','/gotomap.php?inttype=uiplatunusual&laborid='+laborid);
        		$('.maprest').attr('href','/gotomap.php?inttype=uiplatrest&laborid='+laborid);
        	}

			break;
		case '1': //平台
        	if (counter <= 0) {
        		$('.mapall').attr('href','/gotomap.php?inttype=pipandectall');
        		$('.maprun').attr('href','/gotomap.php?inttype=pipandectrun');
        		$('.mapunusual').attr('href','/gotomap.php?inttype=pipandectunusual');
        		$('.maprest').attr('href','/gotomap.php?inttype=pipandectrest');
        	} else {
        		$('.mapall').attr('href','/gotomap.php?inttype=pilabortall&laborid='+laborid);
        		$('.maprun').attr('href','/gotomap.php?inttype=pilaborrun&laborid='+laborid);
        		$('.mapunusual').attr('href','/gotomap.php?inttype=pilaborunusual&laborid='+laborid);
        		$('.maprest').attr('href','/gotomap.php?inttype=pilaborrest&laborid='+laborid);
        	}

			break;

		case '2'://劳务方
			if (counter <= 0) {
        		$('.mapall').attr('href','/gotomap.php?inttype=lipandectall ');
        		$('.maprun').attr('href','/gotomap.php?inttype=lipandectrun');
        		$('.mapunusual').attr('href','/gotomap.php?inttype=lipandectunusual');
        		$('.maprest').attr('href','/gotomap.php?inttype=lipandectrest');
        	} else {
        		$('.mapall').attr('href','/gotomap.php?inttype=liplatall&platformid='+platformid);
        		$('.maprun').attr('href','/gotomap.php?inttype=liplatrun&platformid='+platformid);
        		$('.mapunusual').attr('href','/gotomap.php?inttype=liplatunusual&platformid='+platformid);
        		$('.maprest').attr('href','/gotomap.php?inttype=liplatrest&platformid='+platformid);
        	}
			break;

		case '4'://骑士
			if (counter == 0) {
        		$('.gotomap').attr('href','/gotomap.php?inttype=mymap&seqno='+knightseqno+'&usertype='+usertype);
        	}

			break;
	}
})