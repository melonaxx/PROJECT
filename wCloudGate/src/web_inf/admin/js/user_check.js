$(function(){
    $(document).on("click",".pass",function(){
        var companyid =  $(this).parent().prev().prev().prev().prev().prev().attr("companyid");
        $(".passid").val(companyid);
        $(".modal").show();
    });
    $(document).on("click",".sure",function(){
        $(".modal").hide();
        var companyid = $(".passid").val();
        var data={companyid:companyid,status:0};
        util.ajax_post("/users_docheck.php",data ,passSuccess , passFail);
    });
    function passSuccess(res){
        var companyid = $(".passid").val();
        $(".companyid").each(function(){
            if($(this).attr("companyid")===companyid)
            {
                $(this).parent().remove();
            }
        })
        $(".id").each(function(i){
            $(this).html(i+1);
        })
    }
    function passFail(res){
        alert("操作失败");
    }

    $(document).on("click",".cancel",function(){
        $(".modal").hide();
    });

    $(document).on("click",".refuse",function(){
        var companyid =  $(this).parent().prev().prev().prev().prev().prev().attr("companyid");
        $(".refuseid").val(companyid);
        $(".modalr").show();
    });
    $(document).on("click",".surer",function(){
        $(".modalr").hide();
        var companyid = $(".refuseid").val();
        var data={companyid:companyid,status:5}
        util.ajax_post("/users_docheck.php" ,data , refuseSuccess,refuseFail);
    });
    function refuseSuccess(res){
        var companyid = $(".refuseid").val();
        $(".companyid").each(function(){
            if($(this).attr("companyid")===companyid)
            {
                $(this).parent().remove();
            }
        })
        $(".id").each(function(i){
            $(this).html(i+1);
        })
    }
    function refuseFail(res){
        alert("操作失败");
    }
    $(document).on("click",".cancelr",function(){
        $(".modalr").hide();
    });

    $(".usersnav li:eq(1)").on("click",function(){
        var data={status:2};
        util.ajax_post("/labor_check.php" , data ,laborSuccess , laborFail);
    });
    function laborSuccess(res){
        if(res.length>0){
            var arr = res;
            var str = "";
            for(var i=0;i<arr.length;i++){
                str += "<tr>";
                str += "<td class='id'>"+(i+1)+"</td>";
                str += "<td>"+arr[i]['userid']+"</td>";
                str += "<td>"+arr[i]['linkman']+"</td>";
                str += "<td>"+arr[i]['mobileno']+"</td>";
                str += "<td>"+arr[i]['email']+"</td>";
                str += "<td class='companyid' companyid="+arr[i]['id']+">"+arr[i]['name']+"</td>";
                str += "<td>"+arr[i]['seat']+"</td>";
                str += "<td>"+arr[i]['registerid']+"</td>";
                str += "<td><a target='view_window' href='https://o7ntuivxz.qnssl.com/"+arr[i]['licence']+"'><img src='https://o7ntuivxz.qnssl.com/"+arr[i]['licence']+"' width='50'/></a></td>";
                str += "<td>"+arr[i]['createtime']+"</td>";
                str += "<td><a class='pass'>通过</a><br/><a class='refuse'>拒绝</a></td>";
                str += "</tr>";
            }

            $(".labor_check tbody").empty().append(str);
        }
    }
    function laborFail(res){}
});
