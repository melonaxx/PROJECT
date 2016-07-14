	for(var i=0;i<1;i++){
		var $protr=$('<tr class="onetr1 arrange-tr"></tr>');
		var $protd1=$('<td class="onetd1 arrange-td">'+(i+1)+'</td>');
		var $protd2=$('<td><input class="Check" type="checkbox" value=""></td>');
		var $protd3=$('<td><input class="search form-control" name="searchoem" type="text"placeholder="请搜索代工户名称"></td>');
		var $protd4=$('<td style="text-align:left;"><select class="form-control searchname" name="oemid[]"></select></td>');
		var $protd5=$('<td><input type="number" class="form-control goodsnum" placeholder="必填" value="0" name="number[]"/></td>');
		var $protd6=$('<td><input type="text" class="form-control table-mark" name="remarks[]"></td>');
		var $protd7=$('<td class="arrange-opeartion" ><a href="/product/product_allocate.php" target="_blank">领取原料</a></td>');
		$protr.append($protd1);
		$protr.append($protd2);
		$protr.append($protd3);
		$protr.append($protd4);
		$protr.append($protd5);
		$protr.append($protd6);
		$protr.append($protd7);
		$("#arrange-tbody").append($protr);
	}
	$(".btn-add").on("click",function(){
		var $protr=$('<tr class="onetr1 arrange-tr"></tr>');
		var $protd1=$('<td class="onetd1 arrange-td"></td>');
		var $protd2=$('<td><input class="Check" type="checkbox" value=""></td>');
		var $protd3=$('<td><input class="search form-control" type="text" name="searchoem" placeholder="请搜索代工户名称"></td>');
		var $protd4=$('<td style="text-align:left;"><select class="form-control searchname" name="oemid[]"></select></td>');
		var $protd5=$('<td><input type="number" class="form-control goodsnum" placeholder="必填" name="number[]" value="0"/></td>');
		var $protd6=$('<td><input type="text" class="form-control table-mark" name="remarks[]"></td>');
		var $protd7=$('<td class="arrange-opeartion" ><a href="/product/product_allocate.php" target="_blank">领取原料</a></td>');
		$protr.append($protd1);
		$protr.append($protd2);
		$protr.append($protd3);
		$protr.append($protd4);
		$protr.append($protd5);
		$protr.append($protd6);
		$protr.append($protd7);
		$("#arrange-tbody").append($protr);
		if($(".arrange-tr").length==1){
			$protd1.html(1);
		}else{
			$protd1.html(Number($protr.prev().children().eq(0).html())+1);			
		}
		Keyup();
		startNumber();
	});
	//全选商品;
	$(".allCheck").on("click",function(){
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
	$(".btn-del").on("click",function(){
		$("input[type='checkbox']").each(function(){
			if(this.checked==true){
				$(this).parent().parent(".arrange-tr").remove();
				$(".allCheck").attr("checked",false);
				
			}
	   });
	   $(".arrange-td").each(function(i){
	   	 $(this).html(i+1);
	   });
	});

    Keyup();
	function Keyup(){
	    $("input[name='searchoem']").addClass("sendajax");
	    $(".sendajax").keyup(function(){
	    	var ob = $(this);
	    	if(ob.val() == ""){
	    		ob.parent("td").next("td").find("select").val("");
	    	}
		 	var search_content = $(this).val();
		 	$.ajax({
			   type: "POST",

			   url: "/product/product_oneoem.php",

			   data: {
			   			"search_content":search_content
			  		 },
			   dataType: "json",

			   success: function(data){
			        var str = "";
		              for(var i = 0;i<data.length;i++){
			            str += "<option value='"+data[i].id+"'>"+data[i].name+"</option>";
			            }
			        	select=ob.parent("td").next("td").find("select");
			        	select.empty().append(str);
			   }

			});
		})
	}

startNumber();
function startNumber(){

	$("input[type='number']").keyup(function(){
		 var num = parseInt($(this).val());

		 var total = parseInt($("#total").text());
		 
 	     var allcount = 0;

	     $("input[type='number']").each(function(){
    		allcount += parseInt($(this).val());
	     })
         if (allcount > total) {
         	$(this).val(0);
         }

		 startCount();
	})
}

startCount();
function startCount(){
	var allcount = 0;
	var type_number = $("input[type='number']");
    $("input[type='number']").each(function(){
    	allcount += parseInt($(this).val());
    })
	$("#produce_num").text(allcount ? allcount : 0);
}

function fun(){
	var total = document.getElementById("total").innerHTML;
	var num = document.getElementById("produce_num").innerHTML;

	if(parseInt(total) != parseInt(num)){
		return false;
	}else{
		return true;
	}

}