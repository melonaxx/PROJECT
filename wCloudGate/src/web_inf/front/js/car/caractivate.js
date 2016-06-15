$(function($) {
    $(".nav li:eq(2)").addClass("navactive");

    // 左侧列表二级菜单的显示隐藏
    $(".wllevel1").hover(
        function(){
            $(this).find(".wllevel2").show();
        },
        function(){
            $(this).find(".wllevel2").hide();
    });

    $(".activebtn").on("click" , function() {
        var num = $("textarea[name=active]").val();
        if(/^\s*$/.test(num) ) return false;
        $(".modalsure").show();
    });

    $(".add").on("click",function(){
        $(".modalsure").hide();        
        var num = $("textarea[name=active]").val();
        var data= {num: num };

        var $that = $(".activebtn");
        $that.html("添加中...");
        $that.prop("disabled" , true);
        $that.attr("style" , "cursor:no-drop");
        util.ajax_post("/platform/docaractive.php" , data , activesuccess , activefail);
    });

    $(".cancela").on("click",function(){
        $(".modalsure").hide();
    });
         
    function activesuccess(data) {
        var $this = $(".activebtn");
        $this.html("添加");
        $this.prop("disabled" , false);
        $this.attr("style" , "cursor:pointer");
        $(".acmsg").html('');
        var succ = data['success'] ,
        fa = data['fail'] ,
        succlen = 0,
        falen = 0,
        activetr = '' ,
        s = 1,
        f;
        if(succ != null){
            succlen = succ.length;
            $.each(succ , function(i , v){
                activetr += '<tr>'+'<td>'+s+'</td>'+'<td>'+v+'</td>'+'<td>添加成功</td></tr>';
                s++;
            });
        }

        if(fa != null){
            falen = fa.length;
            f = succlen + 1;
            $.each(fa , function(i , k){
                activetr += '<tr>'+'<td>'+f+'</td>'+'<td>'+k+'</td>'+'<td class="activastatic">添加失败</td></tr>';
                f++;
            });
        }

        $(".activenum span:eq(0)").html(succlen);
        $(".activenum span:eq(1)").html(falen);
        $(".activemsg").show();
        $(".ctablediv tbody").html(activetr);
           
    }   

    function activefail(errno, errmsg) {
        var $this = $(".activebtn");
        $this.html("添加");
        $this.prop("disabled" , false);
        $this.attr("style" , "cursor:pointer");
        var msg = "";
        switch(errno) {
            case 400:
                msg = "请填写电动车序列号以回车隔开";
                break;
            case 4001:
                msg = "请填写正确格式电动车序列号";
                break;
            case 4002:
                msg = "添加失败";
                break;
            case 500:
            default:
                msg = "服务器异常";
                break;
        }
        $(".acmsg").show().html('<img src="/image/main2/error.png"/>\
                    <span>'+msg+'</span>');
        util.goTop();
    }

    /*--------------解除激活--------------*/
    $(".deactivebtn").on("click" , function() {
        $(".modalsured").show();
    });

    $(".deadd").on("click" , function() {
        $(".modalsured").hide();
        var num = $("textarea[name=deactive]").val();
        var data= {num: num };
        var $that = $(".deactivebtn");
        $that.html("解除中...");
        $that.prop("disabled" , true);
        $that.attr("style" , "cursor:no-drop");
        util.ajax_post("/platform/docardeactive.php" , data , deactivesuccess , deactivefail);
    });

    $(".canceld").on("click",function(){
        $(".modalsured").hide();
    });

    function deactivesuccess(data) {
        var $that = $(".deactivebtn");
        $that.html("解除激活");
        $that.prop("disabled" , false);
        $that.attr("style" , "cursor:pointer");
        $(".deacmsg").html('');
        var succ = data['success'] ,
        fa = data['fail'] ,
        succlen = 0,
        falen = 0,
        activetr = '' ,
        s = 1,
        f;
        if(succ != null){
            succlen = succ.length;
            $.each(succ , function(i , v){
                activetr += '<tr>'+'<td>'+s+'</td>'+'<td>'+v+'</td>'+'<td>解除激活成功</td></tr>';
                s++;
            });
        }

        if(fa != null){
            falen = fa.length;
            f = succlen + 1;
            $.each(fa , function(i , k){
                activetr += '<tr>'+'<td>'+f+'</td>'+'<td>'+k+'</td>'+'<td class="activastatic">解除激活失败</td></tr>';
                f++;
            });
        }

        $(".deactivenum span:eq(0)").html(succlen);
        $(".deactivenum span:eq(1)").html(falen);
        $(".deactivemsg").show();
        $(".detablediv tbody").html(activetr);
    }   
                
    function deactivefail(errno, errmsg) {
        var $that = $(".deactivebtn");
        $that.html("解除激活");
        $that.prop("disabled" , false);
        $that.attr("style" , "cursor:pointer");
        var msg = "";
        switch(errno) {
            case 400:
                msg = "请填写电动车序列号以回车隔开";
                break;
            case 4001:
                msg = "请填写正确格式电动车序列号";
                break;
            case 4002:
                msg = "解除激活失败";
                break;
            case 500:
            default:
                msg = "服务器异常";
                break;
        }
        $(".deacmsg").show().html('<img src="/image/main2/error.png"/>\
                    <span>'+msg+'</span>');
        util.goTop();
    } 


});
