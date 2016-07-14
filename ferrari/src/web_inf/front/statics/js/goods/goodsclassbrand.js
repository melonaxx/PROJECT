$(function(){
	/*-----------------收起展开切换-------------*/
	$(".gcbpackup").on("click",function(){
		$(this).hide();
		$(".gcbunfold").show();
	})
	$(".gcbunfold").on("click",function(){
		$(this).hide();
		$(".gcbpackup").show();
	})
	/*-------------------添加同级分类---------------*/
	$(".cgsureaddsbtn").on("click",function(){
		var $onetr=$('<tr></tr>');
		var $td1=$('<td class="gcbtd1"><a class="gcbview">修改</a><a class="gcbdel" data-toggle="modal" data-target="#myModaldel">删除</a></td>');
		var $td2=$('<td class="gcbtd2"><a class="gcbpackup">收起</a><a class="gcbunfold">展开</a></td>');
		var $td3=$('<td><div class="gcbblank">&nbsp;</div><div class="gcbunclass">上衣</div><div class="gcbtright"><a class="gcbaddsonl"  data-toggle="modal" data-target="#myModaladdo">添加子分类</a><a class="gcbaddsamel" data-toggle="modal" data-target="#myModaladds">添加同级分类</a></div></td>');
		$onetr.append($td1);
		$onetr.append($td2);
		$onetr.append($td3);
		$("#gcbtbody").append($onetr);
	})
	$(".cgsureaddobtn").on("click",function(){
		var $onetr=$('<tr></tr>');
		var $td1=$('<td class="gcbtd1"><a class="gcbview">修改</a><a class="gcbdel" data-toggle="modal" data-target="#myModaldel">删除</a></td>');
		var $td2=$('<td class="gcbtd2"><a class="gcbpackup">收起</a><a class="gcbunfold">展开</a></td>');
		var $td3=$('<td><div class="gcbblank">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</div><div class="gcbunclass">上衣</div><div class="gcbtright"><a class="gcbaddsonl"  data-toggle="modal" data-target="#myModaladdo">添加子分类</a><a class="gcbaddsamel" data-toggle="modal" data-target="#myModaladds">添加同级分类</a></div></td>');
		$onetr.append($td1);
		$onetr.append($td2);
		$onetr.append($td3);
		$("#gcbtbody").append($onetr);
	})
})