$(function(){
    $(document).on("click",".addblack",function(){
        $(".modalr").show();
    });
    $(document).on("click",".surer",function(){
        $(".modalr").hide();
    });
    $(document).on("click",".cancelr",function(){
        $(".modalr").hide();
    });

    $('.tabs-labor').on('click',function(){
        var data = {status:2};
        util.ajax_post('/userlisttype.php',data,laborSuccess,laborFail);
    });
    function laborSuccess(res){
        var str = "";
        var data = res;
        for(var i=0;i<data.length;i++){
            str += "<tr>";
            str += "<td>"+(i+1)+"</td>";
            str += "<td>"+data[i]['id']+"</td>";
            str += "<td>"+data[i]['name']+"</td>";
            str += "<td>"+data[i]['phone']+"</td>";
            str += "<td>"+data[i]['company']['email']+"</td>";
            str += "<td>"+data[i]['company']['name']+"</td>";
            str += "<td>"+data[i]['company']['seat']+"</td>";
            str += "<td>"+data[i]['company']['registerid']+"</td>";
            str += "<td><a target='view_window' href='https://o7ntuivxz.qnssl.com/"+data[i]['company']['licence']+"'><img src='https://o7ntuivxz.qnssl.com/"+data[i]['company']['licence']+"' width='50' /></a></td>";
            str += "<td>"+data[i]['company']['createtime']+"</td>";
            str += "<td><a class='addblack'>加入黑名单</a></td>";
            str += "</tr>";
        }
        $(".table-labor tbody").empty().append(str);
    }
    function laborFail(res){}
    $('.tabs-employee').on('click',function(){
        var data = {status:0};
        util.ajax_post('/userlisttype.php',data,empSuccess,empFail);
    });
    function empSuccess(res){
        var str = "";
        var data = res;
        for(var i=0;i<data.length;i++){
            str += "<tr>";
            str += "<td>"+(i+1)+"</td>";
            str += "<td>"+data[i]['id']+"</td>";
            str += "<td>"+data[i]['name']+"</td>";
            str += "<td>"+data[i]['phone']+"</td>";
            //str += "<td>"+data[i]['company']['email']+"</td>";
            str += "<td>"+data[i]['company']['name']+"</td>";
            str += "<td>"+data[i]['company']['seat']+"</td>";
            str += "<td>"+data[i]['company']['createtime']+"</td>";
            str += "<td><a class='addblack'>加入黑名单</a></td>";
            str += "</tr>";
        }
        $(".table-employee tbody").empty().append(str);
    }
    function empFail(res){}
    $('.tabs-knight').on('click',function(){
        var data = {status:4};
        util.ajax_post('/userlisttype.php',data,kntSuccess,kntFail);
    });
    function kntSuccess(res){
        var str = "";
        var data = res;
        for(var i=0;i<data.length;i++){
            str += "<tr>";
            str += "<td>"+(i+1)+"</td>";
            str += "<td>"+data[i]['id']+"</td>";
            str += "<td>"+data[i]['name']+"</td>";
            str += "<td>"+data[i]['phone']+"</td>";
            //str += "<td>"+data[i]['company']['email']+"</td>";
            str += "<td>"+data[i]['company']['name']+"</td>";
            str += "<td>"+data[i]['company']['seat']+"</td>";
            str += "<td>"+data[i]['company']['createtime']+"</td>";
            str += "<td><a class='addblack'>加入黑名单</a></td>";
            str += "</tr>";
        }
        $(".table-knight tbody").empty().append(str);
    }
    function kntFail(res){}
});
