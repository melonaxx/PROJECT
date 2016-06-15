$(function () {
	
	$('.addition').click(function(){
		var add= '<div class="input-group form-group float_left btn-group margin_left_9" role="group" aria-label="..."><input type="text" name="number" class="form-control float_left" style="width:506px;" aplaceholder="Search for..."><span class="input-group-btn float_left"><button class="btn btn-default addition" type="button">✚</button><button class="btn btn-default delete" type="button">✖</button></span></div>'
		var aa= $(this).parent().parent().parent().append(add);

	})


// $(".table-accessories").on('keyup','.seach',function(){

	$('.tianjia').on('click','.delete',function(){
		var a=$(this).parent().parent().remove();
	})
});
