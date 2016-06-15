$(function($) {
	$(".finishdiv").click(function(){
        window.location.reload();
    });
    
    $(".close").click(function(){
        window.location.reload();
    });
	$("input").prop("checked",false);
    $(".add").on("click",function(){
        $(".laborid").html($(this).attr('attr'));
    	$(".modall").show();
        //分配车辆
        var data = {};
        util.ajax_post("/distribute.php",data,disSuccess,disFail);
    });
    function disSuccess(data){
        if(data != ""){
            var str = "";
            for(var i=0;i<data.length;i++){
                str += "<tr>";
                str += "<td><input class='checktd' type='checkbox'/></td>";
                str += "<td>"+(i+1)+"</td>";
                str += "<td>"+data[i]['seqno']+"</td>";
                str += "<td></td>";
                str += "</tr>";
            }
            $("#modaldistribute table tbody").empty().append(str);
        }
    }
    function disFail(data){
        $("#modaldistribute table tbody").empty();
    }
    $(".close").on("click",function(){
    	$(".modal").hide();
    });
    $(".addfinish").on("click",function(){
    	$(".modal").hide();
    });
    $(".addsweep").on("click",function(){
    	$(".modalleft").hide();
    	$(".modalright").show();
    	$(".addmanual").removeClass("addact");
    	$(this).addClass("addact");
    });
    $(".addmanual").on("click",function(){
    	$(".modalright").hide();
    	$(".modalleft").show();
    	$(".addsweep").removeClass("addact");
    	$(this).addClass("addact");
    });
    $(".checkall").on("click",function(){
    	if($(this).prop("checked")){
    		$(".checktd").prop("checked",true);
            $(".checktd").parent().next().next().next().html("已选中");
    	}else{
    		$(".checktd").prop("checked",false);
            $(".checktd").parent().next().next().next().html("");
    	}
    });

    $(document).on("click",".checktd",function(){
        if($(this).prop("checked")){
            $(this).parent().next().next().next().html("已选中");
        }else{
            $(this).parent().next().next().next().html("");
        }
    });
//    $(".distribtn").on("click",function(){
//        $(".checktd").each(function(){
//            if($(this).prop("checked")){
//                $(this).parent().parent().children().eq(3).html("成功");
//                $(this).parent().parent().children().eq(4).children().html("取消");
//            }
//        });
//    });

    $("#searchbtn").click(function(){
        $(".subsearch").click();
    });


//    $(".operatea").on("click",function(){
//        var serialnum = $(this).parent().prev().prev().html()
//        var laborid = $(".laborid").html();
//        var data= {                                                    
//            serialnum: serialnum ,                                               
//            laborid: laborid                                                                                         
//        }; 
//        if($(this).html()=="分配"){
//            util.ajax_get("doallowebike.php" , data , labSuccess, labFail);
//            $(this).html("取消");
//            $(this).parent().prev().html("成功");
//         }else{
//            util.ajax_get("docancleebike.php" , data , labSuccess, labFail);
//            $(this).html("分配");             
//            $(this).parent().prev().html(""); 
//        }
//    });

    $(document).on("click",".suredistcar",function(){
        var arr = [];
        var laborid = $(".laborid").html();
        $(".checktd").each(function(){
            if($(this).prop("checked") == true){
                arr.push($(this).parent().next().next().html());
            }
        });
        
        if(arr.length==0){
            alert("请选择要分配的车辆");
            return false;
        }
       var data = {
           serialnum :arr,
           laborid : laborid
       }
       $(".suredistcar").html("分配中...");
       $(".suredistcar").attr("disabled",true);
       util.ajax_get("doallowebike.php" , data , labSuccess, labFail); 
    });
    $(".canceldistcar").click(function(){
        window.location.reload();
    });
    /*---------------劳务方管理删除操作------------*/
    $(".del").on("click",function(){
        $(".laborid").html($(this).attr('attr'));
    	$(".modaldel").show();
    });
    $(".suredel").on("click",function(){
        var laborid = $(".laborid").html();
        var data = {laborid:laborid}
        util.ajax_get("/labor_dellabor.php" , data , delSuccess, labFail); 
        $(".modaldel").hide();
    });
    $(".canceldel").on("click",function(){
    	$(".modaldel").hide();
    });
    /*---------------劳务方管理修改操作------------*/
    $(".empnum").on("click",function(){
        $(".laborid").val($(this).parent().prev().prev().attr('attr'));
        $(".userrid").val($(this).parent().attr('attr'));
        var userid = $(".userrid").val();
        $("select[name='staff'] option").each(function(){
            if($(this).attr("value") == userid){
                $(this).prop("selected",true); 
            }
        })
        $(".modalmodnum").show();
    });
    $(".sureemp").on("click",function(){
        $(".updatelabor").click();
        $(".modalmodnum").hide();
    });
    $(".cancelemp").on("click",function(){
        $(".modalmodnum").hide();
    });
    function delSuccess(data){
       if(data == 0){
            window.location = "labormanage.php";
        }else{
            alert("删除失败！");
        }
    }
    function labSuccess(){
        window.location.reload();
    }
    function labFail(data){
        alert("操作失败");
    }
    
    //查看车辆
    $(".see").click(function(){
        var laborid = $(this).attr('attr');
        $(".laborid").html(laborid);
        var data = {laborid:laborid};
        util.ajax_post("/labor/seeebike.php",data,ebiSuccess,ebiFail);
        $(".modalcar").show();
        
    });
    function ebiSuccess(data){
        if(data.length>0){
            var str = "";
            for(var i=0;i<data.length;i++){
                if(data[i]['companyid'] === ""){
                    var ope = "可查看";
                }else{
                    var ope = "<a class='stata candis'>取消分配</a>";
                }
                str += "<tr>";
                str += "<td>"+(i+1)+"</td>";
                str += "<td>"+data[i]['seqno']+"</td>";
                str += "<td>已分配</td>";
                str += "<td>"+ope+"</td>";
                str += "</tr>";
            }
            $(".see-ebike tbody").empty().append(str);
        }else{
            $(".see-ebike tbody").empty();
        }
    }
    function ebiFail(data){
        $(".see-ebike tbody").empty();
    }

    $(document).on("click",".candis",function(){
        var laborid = $(".laborid").html(); 
        var seqno = $(this).parent().prev().prev().html();
        if($(this).html()==="取消分配"){
            var data = {userid:laborid,serialnum:seqno};
            //取消分配
            util.ajax_get("/labor_docancleebike.php",data,canSuccess,canFail);
            $(".stata").removeClass("candis");
            $(this).html("分配");
            $(this).parent().prev().html("未分配");
            $(".laborid").html(laborid); 
        }else{
            var data = {laborid:laborid,serialnum:seqno};
            util.ajax_get("/labor_doallowebike.php",data,canSuccess,canFail);
            $(".stata").removeClass("candis");
            $(this).html("取消分配");
            $(this).parent().prev().html("已分配");
        }
    });
    function canSuccess(data){
        $(".stata").addClass("candis");
    }
    function canFail(data){
        alert("操作失败");
    }

    $(document).ready(function(e) {
        $(".nowpage").keydown(function (e){
            if(e.which == "13"){
                var num  = $(this).val();
                var url = window.location.href;
                var numm = parseInt(url.replace(/[^\d]/g,""));
                if(numm>0){
                    window.location = url+"&num="+num;
                }else{
                    window.location = url+"?num="+num;
                }
            }
        }) 
    });
    $(".nowpage").keyup(function (){
        this.value = this.value.replace(/[^\d]/g,""); 
        var now = $(this).val();
        var num = $(".pageallnum").html();
        if(parseInt(now)>parseInt(num)){
            $(this).val(num);
        }
        if(parseInt(now) === 0){
            $(this).val(1);
        }
    });
    
    $("select[name=pagenum]").on("change", function() {
       var data = {page:$("select[name=pagenum] option:selected").html()};
       util.ajax_post("/setpage.php",data,setSuccess,setFail);
    });
    function setSuccess(data){window.location.reload();}
    function setFail(data){window.location.reload();}            
});
