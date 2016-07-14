$(function(){
	/*删除*/
	weightdel();
	function weightdel(){
		
		$(".billweight-del").click(function(){
			$this = $(this);
			trtr = $(this).closest("tr");
			$(".modal-weightdel").show();
		});
		
	}

	$("#del").click(function(){
		$(".modal-weightdel").hide();
		var id = $this.attr('uid');
		$.ajax({
			type: "POST",
			url: "/admin/delbillweight.php",
			data:"id="+id,
			success: function(msg){
                if(msg==1){
                	alert("删除成功!");	
                	trtr.remove();
                }else{
                	alert("删除失败!");	
                }
		   },
		   error: function(){
		   	 alert("ajax请求失败");
		   }
		})
	})
})