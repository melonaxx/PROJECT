$(function(){
	//初始化
	if($(".classify-tr").length==0){
		$(".empty-tr").show();
	}else{
		$(".empty-tr").hide();
	}

	/*添加分类信息*/
	$(".classify-add").click(function(){
		$(".modal-classify").show();
	});
	$('.addclosebtn').click(function(){
		$(".modal-classify").hide();
	});

	/*确认添加商品信息*/
	$('.addcatebtn').click(function(){
		$(".modal-classify").hide();
		var catename    = $('.addcatename').val();//分类名称；
		var catecomment = $('.addcatecom').val();//分类备注；
		var addcatesuccess = function(msg)
		{
			location.reload();
		}
		var addcatefail = function()
		{
			alert('添加分类信息失败！');
			location.reload();
		}
		util.ajax_post('/order/addsaleservice.php',{catename:catename,catecomment:catecomment},addcatesuccess,addcatefail);
	});

	/*删除类型*/
	DelType();
	function DelType(){
		$(".del").click(function(){
			$(".modal-classify1").show();
			$delbtn = $(this);
			//分类确认btn
			$('.delcatebtn').click(function(){
				$(".modal-classify1").hide();
				var cateid = $delbtn.attr('cateid');
				var delcatesuccess = function(msg)
				{
					location.reload();
				}
				var delcatefail = function()
				{
					location.reload();
				}
				util.ajax_post('/order/delsaleservice.php',{cateid:cateid},delcatesuccess,delcatefail);
			});
		});
		$('.delclosebtn').click(function(){
			$(".modal-classify1").hide();
		});
	}

	//修改类型
	ChangeType();
	function ChangeType(){
		$(".classify-change").off('click').click(function(){
			$(this).parent().addClass("click");
			$editcatebtn = $(this);
			$(".modal-classify2").show();
			$(".typename1").val($(this).parent().siblings(".classify-name").html());
			$(".typetext1").val($(this).parent().siblings(".classify-text").html());
			$(".typehidden").val($(this).attr("uid"));

			/*修改订单分类确认btn*/
			$('.editcatesub').click(function(){
				$(".modal-classify2").hide();
				var catename = $('.editcatename').val();//分类名称
				var comment  = $('.editcatecomment').val();//分类备注
				var cateid = $editcatebtn.attr('cateid'); //分类ID

				var editcatesuccess = function(msg)
				{
					console.log(msg);
					location.reload();
				}
				var editcatefail = function()
				{
					alert('修改订单分类确失败！');
					location.reload();
				}
				util.ajax_post('/order/editsaleservice.php',{cateid:cateid,catename:catename,comment:comment},editcatesuccess,editcatefail);
			});
		});
		$(".editclosebtn").off('click').click(function(){
			$(".modal-classify2").hide();
		})

	}
})