$(".customer-del").on("click",function(){
	ob = $(this);
	oemid = ob.closest("tr").attr("oemid");
    $(".modal-customer").show();
});

$(".custom-sure").click(function(){
    $.ajax({
	  type: "POST",
	  url: "/product/product_delfoundry.php",
      data:{
      	   "oemid" : oemid
      },
      success:function(msg){
      	  if(msg == "yes"){
      	  	    ob.closest("tr").remove();
    			$("#modal-customer").hide();
      	  }
      }
	});
});

$(".close-btn").click(function(){
    $("#modal-customer").hide();
});
