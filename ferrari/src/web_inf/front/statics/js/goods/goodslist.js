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
	/*--------------------------------初始化----------------------------*/
	$("input").val("");
	$("textarea").val("");
	$("input").prop("checked",false);

	// 添加序号
	$.each($('.proseqno'),function(i){
		$(this).text(i+1);
	})

	// $(".gl-goodsstatus").get(0).selectedIndex=1;
	var flagchoiceall=true;
	$(".gladdbtn").on("click",function(){
		window.location.href="goodsentry.php";
	})
	if($(".pstbody1 tr").length==0){
		$(".norecord").show();
	}else{
		$(".norecord").hide();
	}
	/*---------------选择每页显示的页数(默认每页十条)----------------*/
	// var $pseverypagel1=$(".pseverypagel1");
	// var $everypage=Number($pseverypagel1.find("option:selected").text());
	// var $pstbody1=$(".pstbody1");
	/*----------------------在获得数据条数大于十的条件下----------------------*/
	// for(var i=0;i<10;i++){
	// 	var $ps1tr=$('<tr class="glonetr"></tr>');
	// 	var $ps1td1=$('<td class="gltd1 gltdcom"></td>');
	// 	var $ps1td2input=$('<input class="allCheck checkbox-choice" type="checkbox" name="glcheckbox" value="">');
	// 	var $ps1td2=$('<td class="gltd2 gltdcom"></td>');
	// 	var $ps1td3a=$('<a href="#" class="glonetrdel" data-toggle="modal" data-target="#myModal">删除</a>');
	// 	var $ps1td3=$('<td class="gltd3 gltdcom"></td>');
	// 	var $ps1td4divimg1=$('<img class="glsmallpic" src="/images/smile.png"/>');
	// 	var $ps1td4divimg2=$('<img class="glbigpic" src="/images/smile.png"/>');
	// 	var $ps1td4=$('<td class="gltd4"></td>');
	// 	var $ps1td5=$('<td><a href="goodsentry.php" class="glgoodsname">商品名称</a></td>');
	// 	var $ps1td6=$('<td></td>');
	// 	var $ps1td7=$('<td></td>');
	// 	var $ps1td8=$('<td></td>');
	// 	// var $ps1td9divdiv=$('<div class="input-group-addon">￥</div>');
	// 	// var $ps1td9divinp=$('<input type="text" class="form-control" id="exampleInputAmount">');
	// 	// var $ps1td9div=$('<div class="input-group"></div>');
	// 	var $ps1td9=$('<td></td>');
	// 	/*$ps1td1.index=i;*/
	// 	$ps1td2.append($ps1td2input);
	// 	$ps1td3.append($ps1td3a);
	// 	$ps1td4.append($ps1td4divimg1);
	// 	$ps1td4.append($ps1td4divimg2);
	// 	// $ps1td9div.append($ps1td9divdiv);
	// 	// $ps1td9div.append($ps1td9divinp);
	// 	// $ps1td9.append($ps1td9div);
	// 	$ps1tr.append($ps1td1);
	// 	$ps1tr.append($ps1td2);
	// 	$ps1tr.append($ps1td3);
	// 	$ps1tr.append($ps1td4);
	// 	$ps1tr.append($ps1td5);
	// 	$ps1tr.append($ps1td6);
	// 	$ps1tr.append($ps1td7);
	// 	$ps1tr.append($ps1td8);
	// 	$ps1tr.append($ps1td9);
	// 	$pstbody1.append($ps1tr);
	// 	$ps1td1.html(i+1);
	// 	/*$ps1td4divimg1.on("mouseover",function(){
	// 		$ps1td4divimg2.eq(i).show();
	// 	})*/
	// }
	/*-----------------选择每页显示的页数(点击下拉菜单选项选择每页显示条数)----------------*/
	// $(".pseverypagel1").on("change",function(){
	// 	$(this).parents($(".psheet-content1")).find($pstbody1).empty();
	// 	$everypage=Number($(this).parent().find("option:selected").text());
	// 	for(var i=0;i<$everypage;i++){
	// 		var $ps1tr=$('<tr class="glonetr"></tr>');
	// 		var $ps1td1=$('<td class="gltd1 gltdcom">1</td>');
	// 		var $ps1td2input=$('<input class="allCheck checkbox-choice" type="checkbox" name="glcheckbox" value="">');
	// 		var $ps1td2=$('<td class="gltd2 gltdcom"></td>');
	// 		var $ps1td3a=$('<a href="#" class="glonetrdel" data-toggle="modal" data-target="#myModal">删除</a>');
	// 		var $ps1td3=$('<td class="gltd3 gltdcom"></td>');
	// 		var $ps1td4divimg1=$('<img class="glsmallpic" src="/images/smile.png"/>');
	// 		var $ps1td4divimg2=$('<img class="glbigpic" src="/images/smile.png"/>');
	// 		var $ps1td4=$('<td class="gltd4"></td>');
	// 		var $ps1td5=$('<td><a href="goodsentry.html" class="glgoodsname">商品名称</a></td>');
	// 		var $ps1td6=$('<td><input type="text" class="form-control"></td>');
	// 		var $ps1td7=$('<td><input type="text" class="form-control"></td>');
	// 		var $ps1td8=$('<td><input type="text" class="form-control"></td>');
	// 		var $ps1td9divdiv=$('<div class="input-group-addon">￥</div>');
	// 		var $ps1td9divinp=$('<input type="text" class="form-control" id="exampleInputAmount">');
	// 		var $ps1td9div=$('<div class="input-group"></div>');
	// 		var $ps1td9=$('<td></td>');
	// 		$ps1td2.append($ps1td2input);
	// 		$ps1td3.append($ps1td3a);
	// 		$ps1td4.append($ps1td4divimg1);
	// 		$ps1td4.append($ps1td4divimg2);
	// 		$ps1td9div.append($ps1td9divdiv);
	// 		$ps1td9div.append($ps1td9divinp);
	// 		$ps1td9.append($ps1td9div);
	// 		$ps1tr.append($ps1td1);
	// 		$ps1tr.append($ps1td2);
	// 		$ps1tr.apnd($ps1td3);
	// 		$ps1tr.append($ps1td4);
	// 		$ps1tr.append($ps1td5);
	// 		$ps1tr.append($ps1td6);
	// 		$ps1tr.append($ps1td7);
	// 		$ps1tr.append($ps1td8);
	// 		$ps1tr.append($ps1td9);
	// 		$pstbody1.append($ps1tr);
	// 		$ps1td1.html(i+1);
	// 	}
	// 	// var event = event || window.event;
	// 	var iheight = document.body.offsetHeight;
	// 	console.log(iheight);
	// 	$(".glsmallpic").each(function(i){
	// 		$(this).on("mouseover",function(){
	// 			$(".glbigpic").eq(i).show();
	// 			// var oBig=$(".glbigpic");
	// 			// oBig.style.top = (iWidth < oBig.offsetHeight + 10 ? event.clientY - oBig.offsetHeight - 10 : event.clientY+ 10) + "px";
	// 			alert("哈哈");
	// 		})
	// 	})
	// 	$(".glsmallpic").each(function(i){
	// 		$(this).on("mouseout",function(){
	// 			$(".glbigpic").eq(i).hide();
	// 		})
	// 	})
	// })
	/*-----------------全选按钮------------------*/
	$(".choice-all").on("click",function(){
		if(flagchoiceall){
			$(".checkbox-choice").prop("checked",true);
			flagchoiceall=false;
		}else{
			$(".checkbox-choice").prop("checked",false);
			flagchoiceall=true;
		}
	})
	/*--------------------------------图片放大镜----------------------------*/
	$(".glsmallpic").each(function(i){
		$(this).on("mouseover",function(){
			$(".glbigpic").eq(i).show();
			var height=$(this).siblings(".glbigpic").height();
			var height1=$(this).height();
			var top=$(this).siblings(".glbigpic").position().top;
			$(this).on("mousemove",function(event){
				var event = event || window.event;
				var gao=$(window).height();
				var iheight =gao -(event.clientY+height1+10);
				var top1 = (iheight < height?-height:top) + "px";
				$(this).siblings(".glbigpic").css("top",top1);
			});
		});
	});
	$(".glsmallpic").each(function(i){
		$(this).on("mouseout",function(){
			$(".glbigpic").eq(i).hide();
		})
	});
	/*------------------------------删除商品数目---------------------------*/
	$(".gldelbtn").on("click",function(){
		if($('input[type=checkbox][name=glcheckbox]:checked').length==0){
			$(".glmodal").hide();
		}else{
			$(".glmodal").show();
		}
		$(".gldelnum").html($('input[type=checkbox][name=glcheckbox]:checked').length);

	})
	/*------------------------------点击table中每一行中的删除---------------------------*/
	$(".glonetrdel").on("click",function(){
		$delproductbtn=$(this);
		$(".glmodal").show();
		$(".gldelnum").html(1);
	})
	//确认删除sub
	$(".glsuredel1").on("click",function(){
		//商品ID
		var proid = $.trim($delproductbtn.closest('tr').find('.productid').text());
		//图片地址
		var picaddr = $delproductbtn.parent().next().find('.glsmallpic').prop('src');
		var picflag = picaddr.lastIndexOf('/');
		var picname = picaddr.substr(picflag+1);
		var picnamelen = picname.length;
		var delprosuccess = function(msg){
			console.log(msg);
		}
		var delprofail = function(){
			console.log('del por fail!');
		}
		util.ajax_post('deletegood.php',{proid:proid,picnamelen:picnamelen},delprosuccess,delprofail);
		$delproductbtn.parent().parent().remove();
	});
	/*------------------------------执行删除操作---------------------------*/
	$(".glsuredel1").on("click",function(){
		$('input[type=checkbox][name=glcheckbox]:checked').parent().parent().remove();
		$(".glmodal").hide();
		$(".allCheck").prop("checked",false);
		if($(".pstbody1 tr").length==0){
			$(".norecord").show();
		}else{
			$(".norecord").hide();
		}
	})
	/*------------------------------执行取消删除操作---------------------------*/
	$(".glcanceldel").on("click",function(){
		$(".allCheck").prop("checked",false);
	})

	/*----------------------------搜索分页-------------------------------------------*/
	/*获取参数*/
	function getParam(){
		var paramarr = new Object();
		//页大小
		if ($('.rrow option:selected').val() !='undefined' || $('.rrow option:selected').val())
		{
			var pagesize = $('.rrow option:selected').val();
		} else {
			var pagesize = '';
		}
		paramarr['pagesize'] = pagesize;

		//商品状态
		if ($('.goodsstatus option:selected').val() !=-1 && $('.goodsstatus option:selected').val() != 'undefined')
		{
			var goodsstatus = $('.goodsstatus option:selected').val();
		} else {
			var goodsstatus = '';
		}
		paramarr['goodsstatus'] = goodsstatus;

		//商品名称
		if ($('.searchproname').val())
		{
			var proname = $('.searchproname').val();
		} else {
			var proname = '';
		}
		paramarr['proname'] = proname;

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
		var proname      = getParam().proname;
		var goodsstatus = getParam().goodsstatus;
		var pagesize    = getParam().pagesize;
		var url = location.href.substring(0,location.href.indexOf('?'));
		window.location.href=url+"?page="+page+"&proname="+proname+"&goodsstatus="+goodsstatus+"&pagesize="+pagesize;
	}
	/*切换页大小*/
	$('.rrow').change(function(){
		keepPageSearch();
	});

	/*进行搜索*/
	$('.searchbtn').click(function(){
		keepPageSearch('search');
	});

	/*保持商品状态选中状态*/
	if (GetRequest().goodsstatus)
	{
		$.each($('.goodsstatus option'),function(i){
			if ($('.goodsstatus option').eq(i).val() == GetRequest().goodsstatus)
			{
				$('.goodsstatus option').eq(i).attr('selected',true);
			}
		});
	}

	/*保持商品名称*/
	if (GetRequest().proname)
	{
		$('.searchproname').val(GetRequest().proname);
	}
})