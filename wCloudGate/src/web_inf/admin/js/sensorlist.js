$(function(){
    $('.unbind').click(function(){
        var data = {};
        util.ajax_post('/unbindsensor.php',data,unbindSuccess,unbindFail);
    });
    function unbindSuccess(data){
        var str = "";
       for(var i=0;i<data.length;i++){
           str += "<tr>";
           str += "<td>"+(i+1)+"</td>";
           str += "<td class='sensorid'>"+data[i]['id']+"</td>";
           str += "<td>"+data[i]['imei']+"</td>";
           str += "<td>"+data[i]['mobileno']+"</td>";
           str += "<td>"+data[i]['createtime']+"</td>";
           str += "<td><a class='bind'>绑定电动车</a></td>";
           str += "</tr>";
       }
       $('.table-unbind tbody').empty().append(str); 
    }
    function unbindFail(data){}
    $(".inbind").click(function(){
        window.location.reload();
    });

    $(document).on("click",".bind",function(){
        $(".imei").val($(this).parent().prev().prev().prev().prev().html());
        $(".modal").show();
    });
    $(".sure").click(function(){
        $(".error").html("");
        var seqno = $("input[name='seqno']").val();
        if(seqno === ""){
            $("input[name='seqno']").attr("style","border:1px solid red");
            return false;
        }
        if(seqno.length > 16 || seqno.length <15){
            $("input[name='seqno']").attr("style","border:1px solid red");
            $(".error").html("请输入15~16位电动车序列号");
            return false;
        }
        var imei = $(".imei").val();
        var data = {imei:imei,seqno:seqno};
        util.ajax_post("/dobindsensor.php",data,opSuccess,opFail);
    });
    function opSuccess(data){
        var sensorid = $(".imei").val();
        $(".sensorid").each(function(){
            if($(this).html()===sensorid){
                $(this).parent().remove();
            }
        });
        $(".sensorid").each(function(i){
           $(this).prev().html(i+1);
        });
        $(".modal").hide();
    }
    function opFail(data){
        alert("绑定失败");
        $(".modal").hide();
        $("input[name='seqno']").val("");
    }
    $("input[name='seqno']").keyup(function(){
         this.value = this.value.replace(/[^\d]/g,""); 
    });
    $(".cancel").click(function(){
        $(".modal").hide();
    });
});
