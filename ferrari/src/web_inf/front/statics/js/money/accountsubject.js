$(function () {
	//删除
	Mdel();
	function Mdel(){
		$('.sort_delete').click(function () {
			$(".modal-tip").show();
			del = $(this);
		});
	}
	$(".delsub").click(function(){
		$(".modal-tip").hide();
		var id = del.attr("uid");
		$.ajax({
			type: "POST",
			url: "/money/delsubject.php",
			data:{id:id},
			success: function(msg){
				if(msg==1){
					alert("删除成功!");
					window.location.reload()
				}else if(msg==0){
					alert("删除失败!");
				}else{
					alert(msg);
				}
			},
			error: function(){
				alert("ajax请求失败")
			}
		});
	})
	//添加同级科目
	$(".add_this_sort").click(function(){
		$(".modal-accountsubject").show();
	});
	//修改科目
	Mchange();
	function Mchange(){
		$(".sort_edit").click(function(){
			$(".modal-accountsubject1").show();

			edit = $(this);
			var id=$(this).attr("uid");
			$.ajax({
				type: "POST",
				url: "/money/findsubject.php",
				data:{id:id},
				success: function(msg){
					json = eval('('+msg+')');
					$("#code").val(json.code);
					$("#parent").val(json.parent);
					$("#sel1").val(json.acctypeid);
					s2id = json.accgoryid;
					$("#parentname").val(json.parentname);
					$("#name").val(json.name);
					$("#comment").val(json.remark);
					if(json.balance=='J'){
						$("#radio1").prop("checked",true);
					}else if(json.balance="D"){
						$("#radio2").prop("checked",true);
					}
					var selid = $("#sel1").val();
			$.ajax({
				type: "POST",
				url: "/money/typeforclass.php",
				data:{id:selid},
				success: function(data){
					json = eval('('+data+')');
					$("#sel2").empty();
					$.each(json,function(i,v){
						$("#sel2").append("<option value='"+v.id+"'>"+v.goryname+"</option>")
						console.log(v.goryname);
					})
					$(".sel2").val(s2id);
				},
				error: function(){
					alert("ajax请求失败")
				}
			});
				},
				error: function(){
					alert("ajax请求失败")
				}
			});
		});
	}
	$(".editboth").click(function(){
		var id = edit.attr("uid");
		$("#hide").val(id);
		str = $("#form3").serialize(); 
		$.ajax({
			type: "POST",
			url: "/money/editsubject.php",
			data:str,
			success: function(msg){
				if(msg==1){
					alert("修改成功!");
					window.location.reload()
				}else{
					alert("修改失败!");
				}
			},
			error: function(){
				alert("ajax请求失败")
			}
		});
	})
	//添加子科目
	Madd();
	function Madd(){
		$(".add_child_sort").click(function(){
			$(".modal-accountsubject2").show();
			var id= $(this).attr("uid");
			$.ajax({
				type: "POST",
				url: "/money/findparent.php",
				data:{id:id},
				success: function(msg){
					json = eval('('+msg+')');
					if(json.balance=='J'){
						$("#ra1").prop("checked",true);
					}else if(json.balance="D"){
						$("#ra2").prop("checked",true);
					}
					$("#parentnames").val(json.name);
					$("#parentid").val(json.id);
					console.log(json.name)
				},
				error: function(){
					alert("ajax请求失败")
				}
			});
			
		});
	}
	//选择类别
	$(".sel1").change(function(){
		$(".sel2").empty();
		var id = $(this).val();
		$.ajax({
			type: "POST",
			url: "/money/typeforclass.php",
			data:{id:id},
			success: function(msg){
				json = eval('('+msg+')');
				$.each(json,function(i,v){
					$(".sel2").append("<option value='"+v.id+"'>"+v.goryname+"</option>")
				})
			},
			error: function(){
				alert("ajax请求失败")
			}
		});
	})
	//添加一级科目
	$(".addfirst").click(function(){
		var str=$("#form1").serialize(); 
		$.ajax({
			type: "POST",
			url: "/money/addaccfirst.php",
			data:str,
			success: function(msg){
				if(msg==1){
					alert("添加成功!");
					window.location.reload()
				}else{
					alert("添加失败!");
				}
			},
			error: function(){
				alert("ajax请求失败")
			}
		});
	})
	//添加子科目
	$(".addchild").click(function(){
		var str=$("#form2").serialize(); 
		$.ajax({
			type: "POST",
			url: "/money/addaccfirst.php",
			data:str,
			success: function(msg){
				if(msg==1){
					alert("添加成功!");
					window.location.reload()
				}else{
					alert("添加失败!");
				}
			},
			error: function(){
				alert("ajax请求失败")
			}
		});
	})

	$("#bigsel").change(function(){
		var id=$(this).val();
		if(id>0){
			window.location.href = "/money/accountsubject.php?id="+id; 	
		}else{
			window.location.href = "/money/accountsubject.php";
		}
       
		
	})
	
});
