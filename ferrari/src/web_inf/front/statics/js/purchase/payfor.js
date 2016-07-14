$(function(){
	$(".pay-money").change(function(){
		if(Number($(this).val())>Number($(".qian-money").val())){
			$(this).val($(".qian-money").val());
		}else if(Number($(this).val())<0){
			$(this).val(0);
		}
	})
})