$(function(){
	/*-----------------全选按钮------------------*/
	var flag1=true;
	var flag2=true;
	$(".allcheck").on("click",function(){
		if(flag1){
			$(".checkbox-choice").prop("checked",true);
			flag1=false;
		}else{
			$(".checkbox-choice").prop("checked",false);
			flag1=true;
		}
	});
	$(".allcheck1").on("click",function(){
		if(flag2){
			$(".checkbox-choice1").prop("checked",true);
			flag2=false;
		}else{
			$(".checkbox-choice1").prop("checked",false);
			flag2=true;
		}
	});

})