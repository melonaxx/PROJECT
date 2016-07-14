$(function(){
	var name = $("input[name='goods_name']");
	name.keyup(function(){
		if($(this).val()==""){
			$('#goods_info').empty();
            $(".img1").attr("src","");
            $(".img2").attr("src","");
            $("#proflats_name").text("");
		}else{
			$.ajax({
	             type: "GET",

	             url: "/product/product_goodsinfo.php",

	             data: {
	             		"store_name":$(this).val()
	         			},

	             dataType: "json",

	             success:function(data){
						var str = "";
						var info = "";
	             	 for(var i = 0; i < data.length; i++){
	             	 	 var productid = data[i].productid;
	             	 	 var goods_info = data[i].formats;
	             	 	 var img = data[i].img;
	             	 	 var proflats_name = data[i].proflats_name;
	                     str += "<option value="+productid+">"+goods_info+"</option>";
	             	 }

                     $(".img1").attr("src",img ? img : "");
                     $(".img2").attr("src",img ? img : "");
                     $("#proflats_name").text(proflats_name ? proflats_name : "");
	             	 $('#goods_info').empty().append(str);
	             	 $('#goods_info option:last').attr("selected","selected");
	             }
			})
		}
	})
 	 $('#goods_info').change(function(){
 		 $.ajax({
             type: "GET",

             url: "/product/product_goodsinfo.php",

             data: {
             		"productid":$(this).val()
         			},

             dataType: "json",

             success:function(data){
             	 var str = "";
             	 for(var i = 0; i < data.length; i++){
             	 	 var productid = data[i].productid;
             	 	 var goods_info = data[i].formats;
             	 	 var img = data[i].img;
             	 	 var proflats_name = data[i].proflats_name;
                     str += "<option value="+productid+">"+goods_info+"</option>";
                     $(".img1").attr("src",img);
                     $(".img2").attr("src",img);
                     $("#proflats_name").text(proflats_name);
             	 }
             }
		})

	 })

	 $("#form_order").submit(function(){
	 	 //仓库
	 	 var storehouse = $("#exampleInputName2 option:selected").val();
	 	 // 生产单摘要
	 	 var pro_order = $("#pro_order").val();
	 	 //商品id
	 	 var goods_info = $("#goods_info option:selected").val();
	 	 //数量
	 	 var number = $("input[name='number']").val();
	 	 console.log(goods_info);
         if(storehouse == "" || goods_info == undefined){
     		 return false;
         }else{
		   return true;
         }
	 })
})