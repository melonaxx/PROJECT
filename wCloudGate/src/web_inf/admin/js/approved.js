$(function(){
    $(".refused").on("click",function(){
        var data = {status:5};
        util.ajax_post('/approved_refused.php',data,refSuccess,refFail);
    });
    function refSuccess(res){
        var data = res;
        var str = "";
        for(var i=0;i<data.length;i++){
            if(data[i]['companytype']==="1"){
                var type = "平台";
            }else{
                var type="劳务方"
            }
            str += "<tr>";
            str += "<td class='id'>"+(i+1)+"</td>";
            str += "<td>"+data[i]['userid']+"</td>";
            str += "<td>"+data[i]['linkman']+"</td>";
            str += "<td>"+data[i]['mobileno']+"</td>";
            str += "<td>"+data[i]['email']+"</td>";
            str += "<td class='companyid' companyid="+data[i]['id']+">"+data[i]['name']+"</td>";
            str += "<td>"+data[i]['seat']+"</td>";
            str += "<td>"+data[i]['registerid']+"</td>";
            str += "<td><a target='view_window' href='https://o7ntuivxz.qnssl.com/"+data[i]['licence']+"'><img src='https://o7ntuivxz.qnssl.com/"+data[i]['licence']+"' width='50' /></a></td>";
            str += "<td>"+data[i]['createtime']+"</td>";
            str += "<td>"+type+"</td>";
            str += "<td><a class='pass'>通过</a><br/><a class='addblack'>加入黑名单</a></td>";
            str += "</tr>";
        }
        $(".table-refused tbody").empty().append(str); 
    }
    function refFail(res){}

    $(document).on("click",".pass",function(){
        $('.modal').show();
        var companyid =  $(this).parent().prev().prev().prev().prev().prev().prev().attr("companyid");
        $(".passid").val(companyid);
    });
    $(document).on("click",".sure",function(){
        $('.modal').hide();
        var companyid = $(".passid").val();                           
        var data={companyid:companyid,status:0};
        util.ajax_post('/users_docheck.php',data,passSuccess,passFail);
    });
    function passSuccess(res){
        var companyid = $(".passid").val();                           
        $(".companyid").each(function(i){                  
            if($(this).attr("companyid")===companyid)     
            {
                $(this).parent().remove();
            }   
        })
        $(".id").each(function(i){
            $(this).html(i+1);
        })
    }
    function passFail(res){alert('操作失败');}

    $(document).on("click",".cancel",function(){
       $('.modal').hide();
    });
    $(document).on("click",".addblack",function(){
        var blackid =  $(this).parent().prev().prev().prev().prev().prev().prev().attr("companyid");
        $(".blackid").val(blackid);
        $(".modalr").show();
    });
    $(document).on("click",".cancelr",function(){
       $('.modalr').hide();
    });
    $(document).on("click",".surer",function(){
       $('.modalr').hide();
    })
})
