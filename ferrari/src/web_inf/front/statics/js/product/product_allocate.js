$(function(){
	for(var i=0;i<1;i++){
		var $mtr=$('<tr class="manage-tr"></tr>');
		var $mtd1=$('<td class="manage-td">'+(i+1)+'</td>');
		var $mtd2=$('<td><label><input type="checkbox"></label></td>');
		var $mtd3=$('<td><select name="store_outid[]" id="" class="form-control choice store_out"><option value="">--请选择--</option></select></td>');
		var $mtd4=$('<td><input type="text" class="form-control searchbox" name="searchname" placeholder="请搜索商品名称"></td>');
		var $mtd5=$('<td><select class="form-control searchname" name="goods_info[]"></select></td>');
		var $mtd6=$('<td><span class="realy_num"></span></td>');
		var $mtd7=$('<td><select name="store_putnum[]" id="" class="form-control choice store_put"></td>');
		var $mtd8=$('<td><input type="text" class="form-control number" name="number[]"></td>');
		var $mtd9=$('<td><input type="text" class="form-control remark" name="remarks[]"></td>');
		$mtr.append($mtd1);
		$mtr.append($mtd2);
		$mtr.append($mtd3);
		$mtr.append($mtd4);
		$mtr.append($mtd5);
		$mtr.append($mtd6);
		$mtr.append($mtd7);
		$mtr.append($mtd8);
		$mtr.append($mtd9);
		$(".manage-tbody").append($mtr);
	}
	$(".manage-add").on("click",function(){
		var $mtr=$('<tr class="manage-tr"></tr>');
		var $mtd1=$('<td class="manage-td">'+(i+1)+'</td>');
		var $mtd2=$('<td><label><input type="checkbox"></label></td>');
		var $mtd3=$('<td><select name="store_outid[]" id="" class="form-control choice store_out"><option value="">--请选择--</option></select></td>');
		var $mtd4=$('<td><input type="text" class="form-control searchbox" name="searchname" placeholder="请搜索商品名称"></td>');
		var $mtd5=$('<td><select class="form-control searchname" name="goods_info[]"></select></td>');
		var $mtd6=$('<td><span class="realy_num"></span></td>');
		var $mtd7=$('<td><select name="store_putnum[]" id="" class="form-control choice store_put"></td>');
		var $mtd8=$('<td><input type="text" class="form-control number" name="number[]"></td>');
		var $mtd9=$('<td><input type="text" class="form-control remark" name="remarks[]"></td>');
		$mtr.append($mtd1);
		$mtr.append($mtd2);
		$mtr.append($mtd3);
		$mtr.append($mtd4);
		$mtr.append($mtd5);
		$mtr.append($mtd6);
		$mtr.append($mtd7);
		$mtr.append($mtd8);
		$mtr.append($mtd9);
		$(".manage-tbody").append($mtr);
		if($(".manage-tr").length==1){
			$mtd1.html(1);
		}else{
			$mtd1.html(Number($mtr.prev().children().eq(0).html())+1);			
		}
		choice();
	});
	//全选商品;
	$(".allcheck").on("click",function(){
		if(this.checked){
			$("input[type='checkbox']").each(function(){
				this.checked=true;
			});
		}else{
			$("input[type='checkbox']").each(function(){
				this.checked=false;
			});
		}
	});
	//删除商品;
	$(".manage-del").on("click",function(){
		$("input[type='checkbox']").each(function(){
			if(this.checked==true){
				$(this).parent().parent().parent(".manage-tr").remove();
				$(".allcheck").attr("checked",false);
				
			}
	   });
	   $(".manage-td").each(function(i){
	   	 $(this).html(i+1);
	   });
	});

choice();

	function choice(){
		$(".store_out").bind("focus",function(){
			ob=$(this);
			ob.parent("td").next("td").find("input").val("");
			ob.parent("td").next("td").next("td").find("select").empty();
			ob.parent("td").next("td").next("td").next("td").find("span").text("");
			 //请求仓库
 		 	$.ajax({

			   type: "POST",

			   url: "/product/allstorehouse.php",

			   dataType: "json",

			   success: function(data){
			    console.log(data);
			        var str = "";
			            str += "<option value=''>--请选择--</option>";
	              for(var i = 0;i<data.length;i++){
		            str += "<option value='"+data[i].id+"'>"+data[i].name+'('+data[i].storetype+')'+"</option>";
	              }
	        	  ob.empty().append(str);

			   }

			});
		})
	$("input[name='searchname']").keyup(function(){
         var ob =$(this);
		 var store_id = $(this).parent("td").prev("td").find("select option:selected").val();

		 if(store_id != ""){
  		 	$.ajax({

			   type: "GET",

			   url: "/product/product_goodsinfo.php",

			   data: {

			   		"storeid":store_id,
			   		"store_name":$(this).val()

			   },

			   dataType: "json",

			   success: function(data){
			        var str = "";
	              for(var i = 0;i<data.length;i++){
		            str += "<option value='"+data[i].productid+"'>"+data[i].formats+"</option>";
		            var totalreal = data[i].totalreal;
	              }
		        	select=ob.parent("td").next("td").find("select");
	        		select.empty().append(str);
	        		select.find("option:last").attr("selected",true);
	        		ob.parent("td").next("td").next("td").find("span").text(totalreal);
			   }

			});
		 }

	})

 	 $("select[name='goods_info[]']").change(function(){
 	 	 var ob = $(this);
 		 var store_id = $(this).parent("td").prev("td").prev("td").find("select option:selected").val();
 		 $.ajax({
             type: "GET",

             url: "/product/product_goodsinfo.php",

             data: {
             		"storeid":store_id,
             		"productid":$(this).val()
         			},

             dataType: "json",

             success:function(data){
             	var totalreal = data[0].totalreal;
             	ob.parent("td").next("td").find("span").text(totalreal);
             }
		})

	 })


 	 $("select[name='store_putnum[]']").focus(function(){

 	 	 var ob = $(this);

 		 $.ajax({
             type: "GET",

             url: "/product/product_alloem.php",

             dataType: "json",

             success:function(data){
			          var str = "";
	              for(var i = 0;i<data.length;i++){
		                  str += "<option value='"+data[i].id+"'>"+data[i].name+"</option>";
	              }
	              ob.empty().append(str);
             }
		})

	 })

  	 $("input[name='number[]']").keyup(function(){
 	 	 var ob = $(this);
 	 	 var number = ob.parent("td").prev("td").prev("td").find("span").text();

 	 	 if (parseInt(ob.val())>parseInt(number)) {
 	 	 	 ob.val(number);
 	 	 }
	 })
}


})