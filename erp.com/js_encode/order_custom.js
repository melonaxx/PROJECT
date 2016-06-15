$(function() {
	//待确认
	//全选
	$('.waitConfirmMsg input[name="select_all"]').click(function () {
	    if(this.checked){   
    		$('.waitConfirmMsg input[name="select_one"]').each(function () {
    			$(this).prop("checked",true);
    		});
	    }else{   
    		$('.waitConfirmMsg input[name="select_one"]').each(function () {
    			$(this).prop("checked",false);
    		});
	    }
	});
	
	
	
	//待定制
	$('.waitCustomizeMsg input[name="select_all"]').click(function () {
	    if(this.checked){   
    		$('.waitCustomizeMsg input[name="select_one"]').each(function () {
    			$(this).prop("checked",true);
    		});
	    }else{   
    		$('.waitCustomizeMsg input[name="select_one"]').each(function () {
    			$(this).prop("checked",false);
    		});
	    }
	});
	
	
	
	
	
	//已完成
	$('.completedMsg input[name="select_all"]').click(function () {
	    if(this.checked){   
    		$('.completedMsg input[name="select_one"]').each(function () {
    			$(this).prop("checked",true);
    		});
	    }else{
    		$('.completedMsg input[name="select_one"]').each(function () {
    			$(this).prop("checked",false);
    		});
	    }
	});
	
	
})