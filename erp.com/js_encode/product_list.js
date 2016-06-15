$(function () {
	$('#confirm').modal('hide');
	//全选
	$('.supplierMsg input[name="select_all"]').click(function () {
	    if(this.checked){   
    		$('.supplierMsg input[name="select_one"]').each(function () {
    			$(this).prop("checked",true);
    		});
	    }else{   
    		$('.supplierMsg input[name="select_one"]').each(function () {
    			$(this).prop("checked",false);
    		});
	    }
	});
	
	//添加删除
	//这里弄一段比较通用的代码
//	var trStr = $('.goodsMsg table tr').eq(1).prop("outerHTML");
//	$('.goodsAdd').click(function () {
//		$('.goodsMsg table').append(trStr);
//		//重置下标
//		$('.goodsMsg table tr').each(function (index, value) {
//			if (index > 0) {
//				$(value).find('td').eq(1).html(index);
//			}
//		});
//	});
	$('.images').hover(function(){
		var t = $(this).position().top - 90 + 'px';
		var l = $(this).position().left + 20 + 'px';
		$(this).next().css("display","block");
		$(this).next().css("top",t);
		$(this).next().css("left",l);
	},function(){
		$(this).next().css("display","none");
	})
	$('.supplierMsg table tr').each(function (index, value) {
		if (index > 0) {
			$(value).find('td').eq(0).html(index);
		}
	});
	$('.supplierDelete').click(function () {
		var length = $('.supplierMsg input[name="select_one"]:checked').length;
		if (length > 0) {
			$('#confirm .number').html(length);
			$('#confirm').modal('show');
			$('#confirm .ok').click(function(){
				var idArr = [];
				$('.supplierMsg input[name="select_one"]:checked').each(function(index, value){
					idArr.push($(this).parent().next().children().attr('id'));

				});
				// location.href = '/product/product_list.php?m=deleteAll&idArr=' + idArr.join(',');
                 $.ajax({
                    url:"/product/product_list.php",
                   type:"get",
                   data:"idArr="+idArr.join(','),
               dataType:"json",
                  async:true,
                success:function(res){
                        var cha = parseFloat(idArr.length) - parseFloat(res[0]);
                        if(cha>0){   
                            alert("成功删除"+res[0]+"条数据，失败"+cha+"条数据。\n失败原因:商品"+res[1]+"在订单中未处理。");
                        }else{
                            alert("成功删除"+res[0]+"条数据，失败"+cha+"条数据。");
                        } 
                        location.href = '/product/product_list.php';
                    }
                })
			});
		}
	});
	$('.table').on('click','.delete',function () {
		$('#confirm').modal('show');
		$('#confirm .number').html("1");
		var thisHref = $(this).attr('href');
		var id	=$(this).attr('id');
		var idbody	=$(this).parent().next().html();
		$('.supplierMsg input[name="select_one"]').prop("checked",false);
		$(this).parent().prev().children().prop("checked",true);
		$('#confirm .ok').click(function () {
			// $.post("/product/product_list.php",{"id":id,},function(data){		
			// }, "json");
			// history.go(0)
			 $.ajax({
                    url:"/product/product_list.php",
                   type:"post",
                   data:"id="+id,
               dataType:"json",
                  async:true,
                success:function(res){
                   if(res == "1"){
                   	   location.href = '/product/product_list.php';
                	}else{
                		alert("对不起，无法删除该商品！");
                		location.href = '/product/product_list.php';
                	}
                },
             
            });

		});
		return false;
	});

	//取消选中
	$(".cancel").click(function(){
		$('.supplierMsg input[name="select_one"]').prop("checked",false);
	});

	// //在售、下架
	// $(".sale").click(function(){
	// 	if($(this).val()=="在售"){
	// 		$(this).val("下架");
	// 	}else{
	// 		$(this).val("在售");
	// 	}
	// });
});


