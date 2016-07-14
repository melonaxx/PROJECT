
// $(".num").change(function(){
// 	$(".num").each(function(i){
// 		sum += Number($(".num").eq(i).val());
// 	})
// 	$("#allnum").html(sum);
// })
$(".num").each(function(i){
	$(this).change(function(){
		var waynum = $("#ontheway").html();
		var sum=0;
		if(Number($(this).val())>Number($(".totalway").eq(i).html())){
			$(this).val($(".totalway").eq(i).html());
		}else if(Number($(this).val())<0){
			$(this).val(0);
		}
		$(".num").each(function(i){
			sum += Number($(".num").eq(i).val());
		})
		$("#allnum").html(sum);
		if(Number(sum)==Number(waynum)){
			$("#status").val('Y');
		}else{
			$("#status").val('P');
		}
	});
})
	
	
