$(function(){
	/*删除部门*/
	$(".department-del").click(function(){
		var $this=$(this);
		$(".modal-department").show();
		$(".department-sure").on("click",function(){
			$(".modal-department").hide();
			$this.parent().parent().remove();
			$(".department-td").each(function(i){
			   	 $(this).html(i+1);
			});
		})
	});
})