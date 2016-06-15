$(function(){
//全选/全不选
	$(".tab_select input[name='sel_all']").click(function(){
		if(this.checked){
			$("input[type='checkbox']").prop("checked", true);
		}else{
			$("input[type='checkbox']").prop("checked", false);
		}
	});
//重置下标
$(".add").click(function(){
	$(".tab_select tr").each(function(index,value){
			if(index >0){
				$(value).find('td').eq(0).html(index);
			}
		});
});
		//删除
	$(".delete").click(function(){
		$('.tab_select input[name="select_one"]:checked').parent().parent().remove();
		//重置下标
		$(".tab_select tr").each(function(index,value){
			if(index >0){
				$(value).find('td').eq(0).html(index);
			}
		});
	});
	});