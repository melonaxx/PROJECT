  $(function(){
	  	   $("#fbut").click(function(){
	  	   	  $("#blo").css("display","none");
	  	   	  $("#non").css("display","block");
	  	   })
   	  	   // 入职日期
	  	   var hiredate=$("#hiredate");
	  	   // 合同时间
           var pacttime=$("#pacttime");
           // 合同到期
           var pactover=$("#pactover");
           $(hiredate).keyup(function(){
               //入职年
	           var year=parseInt($(hiredate).val().slice(0,4));
	           //入职日
	           var day=$(hiredate).val().slice(4,10);
	           //合同年
	           var ptime=parseInt($(pacttime).val());
	           // 合同结束时间
	           $(pactover).val(year+ptime+day);
           })
			 $("#update").click(function(){
			 	var birth = $("#birth").val();
			 	var result = birth.match(/^[0-9]{4}\/[0-2]{2}\/[0-9]{2}$/);
			 	console.log(result);
		      if(result==null){
		      	alert($("#birth").val());
		      }
	  	   	  var str=$("#form").serialize(); 
	  	   	  $.ajax({
				   type: "POST",
				   url: "updateinfo.php",
				   data: str,
				   success: function(msg){
				     // alert(msg);
				   },
				   error: function(){
				   	 alert("ajax请求失败")
				   }
				});
	  	   })
	  })