$(function(){
     //详细模态窗;
    $(".listenter-detail").click(function(){
        $(".modal-listenter").show();
        $(".listentermodal-code1").val($(this).parent().siblings(".listenter-code1").html());
        $(".listentermodal-man").val($(this).parent().siblings(".listenter-man1").html());
        $(".listentermodal-ware").val($(this).parent().siblings(".listenter-ware1").html());
        $(".listentermodal-code2").val($(this).parent().siblings(".listenter-code2").html());
        $(".listentermodal-time").val($(this).parent().siblings(".listenter-time1").html());

        alloem_info($(this).parent().siblings(".listenter-code1").html());
    });
    //infoid 出入库单号的id
    function alloem_info(infoid){
       $.ajax({
           type: "POST",
           url: "/product/product_dopro.php",
           data: {
                   "infoid":infoid
                 },
           dataType:"json",
           success: function(data){
               var count = 0;
               var str = "<tr class='active'><td>id</td><td>代工户名称</td><td>数量</td><td>备注</td></tr>";
               $(".listentermodal-table").find("tr").remove();
                                    
              for(var i = 0;i<data.length;i++){
                   str += "<tr>";
                   str += "<td>"+(i+1)+"</td>";
                   str += "<td>"+(data[i].name)+"</td>";
                   str += "<td>"+(data[i].total)+"</td>";
                   str += "<td>"+(data[i].comment)+"</td>";
                   str += "</tr>";
                   count += parseInt(data[i].total);
               }
                   str += "<tr><td>合计</td>";
                   str += "<td colspan='3'>";
                   str += "<span>数量:<b style='color:red;margin-right: 10px;' class='modalnum'>"+count+"</b></span>";
                   str += "</td></tr>";

                $(".listentermodal-table").append(str);
           }
        });
    }
})