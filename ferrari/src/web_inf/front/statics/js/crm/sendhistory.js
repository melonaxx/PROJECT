$(function(){
	$(".message-add").click(function(){
		$(".modal-messageset").show();
		$(".mode-type").val("");
		$(".mode-name").val(" ");
		$(".mode-content").val("");
		$(".mode-sign").val("");
	});
	$(".messageset-change").click(function(){
		$(".modal-messageset").show();
		$(".mode-type option[value='"+$(this).parent().siblings(".mess-type").html()+"']").prop("selected",true);
		$(".mode-name").val($(this).parent().siblings(".mess-name").html());
		$(".mode-content").val($(this).parent().siblings(".mess-content").html());
		$(".mode-sign").val($(this).parent().siblings(".mess-sign").html());
	});
	$(".messageset-del").click(function(){
		var $this=$(this);
		$(".modal-messageset1").show();
		$(".message-sure1").click(function(){
			$(".modal-messageset1").hide();
			$this.parent().parent().remove();
		});
	});
    $(".message-change1").click(function(){
        $(".modal-messageset2").show();
    });

})