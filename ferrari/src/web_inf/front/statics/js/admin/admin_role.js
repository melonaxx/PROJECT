$(function(){
	/*删除人员*/
	$(".role-del").click(function(){
		var $this=$(this);
		$(".modal-role").show();
		$(".role-sure").on("click",function(){
			$(".modal-role").hide();
			$this.parent().parent().remove();
			$(".role-td").each(function(i){
			   	 $(this).html(i+1);
			});
		})
	});
})