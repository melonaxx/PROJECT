$(function(){
	//初始化
	if($(".inoroutset-tr").length==0){
		$(".empty-tr").show();
	}else{
		$(".empty-tr").hide();
	}
	$(".inoroutset-add").click(function(){
		$(".modal-inoroutset").show();
	});
	//添加类型
	$(".inoroutset-sure").click(function(){
		if($(".typename").val()!=""){
			var $tr=$('<tr class="inoroutset-tr"></tr>');
			var $td1=$('<td class="inoroutset-td">1</td>');
			var $td2=$('<td><span class="inoroutset-change">修改</span><span class="inoroutset-del">删除</span></td>');
			var $td3=$('<td class="inoroutset-name">'+$(".typename").val()+'</td>');
			$tr.append($td1);
			$tr.append($td2);
			$tr.append($td3);
			$(".inoroutset-tbody").append($tr);
			$(".modal-inoroutset").hide();
			$(".inoroutset-td").each(function(i){
			   	 $(this).html(i+1);
			 });
			DelType();
			ChangeType();
			if($(".inoroutset-tr").length==0){
				$(".empty-tr").show();
			}else{
				$(".empty-tr").hide();
			}
		}
	});
	//删除类型
	DelType();
	function DelType(){
		$(".inoroutset-del").click(function(){
			$(".modal-inoroutset1").show();
			var $this=$(this);
			$(".inoroutset-sure1").click(function(){
				$this.parent().parent().remove();
				$(".modal-inoroutset1").hide();
				$(".inoroutset-td").each(function(i){
				   	 $(this).html(i+1);
				 });
				if($(".inoroutset-tr").length==0){
					$(".empty-tr").show();
				}else{
					$(".empty-tr").hide();
				}
			});
		});
	}
	//修改类型
	ChangeType();
	function ChangeType(){
		$(".inoroutset-change").click(function(){
			$(this).parent().addClass("click");
			$(".modal-inoroutset2").show();
			$(".typename1").val($(this).parent().siblings(".inoroutset-name").html());
		});
		$(".inoroutset-sure2").click(function(){
			$(".click").siblings(".inoroutset-name").html($(".typename1").val());
			$(".modal-inoroutset2").hide();
			$(".click").removeClass("click");
		});
		$(".close-btn").click(function(){
			$(".click").removeClass("click");
		})
	}
})