$(function(){
    $(".search").on("click",function(){
        $(".wmainlistall").hide();
        $(".wmainlistsearch").show();
        $(document).ready(function(e) {
            $(".searchinput").keydown(function (e){
                if(e.which == "13"){
                    var name = $(".searchinput").val();
                    if(name===""){
                        $(".searchinput").attr("style","border:0.1px solid red;");
                        return false;
                    }

                    var laborname = $(".searchinput").val();
                    var data = {name:laborname}
                    util.ajax_post("/searchlabor.php" , data , seaSuccess, seaFail);
                }
            })
        });
    })
    $(".abnstadiv li").hover(function(){
        $(this).css({"box-shadow":"0 0 4px #a9a9a9"});
        $(".hideope a").removeClass("opeactive");
        $(this).find(".abnstatp").hide();
        $(this).find(".hidediv").stop().animate({top:100},100);
    },
    function(){
        $(this).css({"box-shadow":"none"});
        $(this).find(".abnstatp").show();
        $(this).find(".hidediv").stop().animate({top:200},100);
    })
    $('.circle').each(function() {
        var num = $(this).find('.percent').text() * 3.6;
        if (num<=180) {
            $(this).find('.right').css({'transform':"rotate(" + num + "deg)"});
        } else {
            $(this).find('.right').css('transform', "rotate(180deg)");
            $(this).find('.left').css('transform', "rotate(" + (num - 180) + "deg)");
        };
    });
    $(".searchbtn").click(function(){
        var name = $(".searchinput").val();
        if(name===""){
            $(".searchinput").attr("style","border:0.1px solid red;");
            return false;
        }
        var data = {name:name};
        util.ajax_post("/searchlabor.php",data,seaSuccess,seaFail);
    });
    function seaSuccess(data){
        if(data == null || data == ""){
            $(".wmainlist li:eq(2)").nextAll().empty();
        }else{
            var str = "";
            for(var i=0;i<data.length;i++){
                str += "<li class='wllevel'><a href='/main.php?status=search&laborid="+data[i]['laborid']+"'><span>"+(i+1)+".</span><span class='laborname'>"+data[i]['name']+"</span><p></p></a> </li>";
            }
            $(".wmainlist li:eq(2)").nextAll().empty();
            $(".wmainlist li:eq(2)").next().append(str);
        }
    }
    function seaFail(data){
        $(".wmainlist li:eq(2)").nextAll().empty();
    }
    $(".default").click(function(){
        window.location='/main.php';
    });

    $(".hideope a").hover(function(){
       $(this).addClass("opeactive");
       $(this).prev().removeClass("opeactive");
       $(this).next().removeClass("opeactive");
    });

    $(".ebknum").click(function(){
        $(this).addClass("sortmethodactive");
        $(".default").removeClass("sortmethodactive");
        var data = {sort:'SORT_ASC'};
        util.ajax_post("/ebikenum.php",data,sortSuccess,sortFail);
    });
    function sortSuccess(data){
        if(data === null){
            $(".companylist").empty();
            return false;
        }

        var url = window.location.href;
        var id =  url.replace(/[^0-9]/ig,""); 
        var str = "";
        for(var i=0;i<data.length;i++){
            if(id === data[i]['laborid']){var sta="wllevel1active";}else{var sta="";}
            str += "<li class='wllevel "+sta+"'>";
            str += "<a href='/main.php?status=sort&laborid="+data[i]['laborid']+"'><span>"+(i+1)+".</span><span class='laborname'>"+data[i]['name']+"</span><p></p></a>";
            str += "</li>";
        }
        $(".companylist").empty().append(str); 
        if(data.length>=12){
            $(".scroltit").show();
        }    
    }
    function sortFail(data){
        $(".companylist").empty(); 
    }
    
    var name = "status=sort";
    var url = window.location.href;
    if(url.indexOf(name)>0){
        $(".ebknum").click();
    }
    $.fn.extend({
        Scroll:function(opt,callback){
            //参数初始化
            if(!opt) var opt={};
            var _btnUp = $("#"+ opt.up);//Shawphy:向上按钮
            var _btnDown = $("#"+ opt.down);//Shawphy:向下按钮
            var timerID;
            var _this=this.eq(0).find("ul:first");
            var     lineH=_this.find("li:first").height(), //获取行高
            line=opt.line?parseInt(opt.line,10):parseInt(this.height()/lineH,10), //每次滚动的行数，默认为一屏，即父容器高度
            speed=opt.speed?parseInt(opt.speed,10):500; //卷动速度，数值越大，速度越慢（毫秒）
            if(line==0) line=1;
            var upHeight=0-line*lineH;
            //滚动函数
            var scrollUp=function(){
                _btnUp.unbind("click",scrollUp); //Shawphy:取消向上按钮的函数绑定
                _this.animate({
                    marginTop:upHeight
                },speed,function(){
                    for(i=1;i<=line;i++){
                        _this.find("li:first").appendTo(_this);
                    }
                    _this.css({marginTop:0});
                    _btnUp.bind("click",scrollUp); //Shawphy:绑定向上按钮的点击事件
                });

            }
            //Shawphy:向下翻页函数
            var scrollDown=function(){
                _btnDown.unbind("click",scrollDown);
                for(i=1;i<=line;i++){
                    _this.find("li:last").show().prependTo(_this);
                }
                _this.css({marginTop:upHeight});
                _this.animate({
                    marginTop:0
                },speed,function(){
                    _btnDown.bind("click",scrollDown);
                });
            }
            //鼠标事件绑定
            _btnUp.css("cursor","pointer").click( scrollUp );//Shawphy:向上向下鼠标事件绑定
            _btnDown.css("cursor","pointer").click( scrollDown );

        }
    })
    $("#scrollDiv").Scroll({line:1,speed:300,up:"but_up",down:"but_down"});
    // 通知
    var notWid=$(".notice").width();

    if(notWid<720){
        $("marquee").width(0);
        $(".noticedivm").width(0);
        $(".noticedivm").hide();
        $(".noticediv").show();
    }else{
        $(".noticedivm").show();
        $(".noticediv").hide();
    }
    $(".noticedivm").on("mouseover",function(){
        $(".closenoticem").show();
    })
    $(".noticedivm").on("mouseout",function(){
        $(".closenoticem").hide();
    })
    $(".noticediv").on("mouseover",function(){
        $(".closenotice").show();
    })
    $(".noticediv").on("mouseout",function(){
        $(".closenotice").hide();
    })
    $(".closenoticem").on("click",function(){
        $(".wmnotice").hide();
        $(".topcartitle").css("margin-top","20px");
    });
    $(".closenotice").on("click",function(){
        $(".wmnotice").hide();
        $(".topcartitle").css("margin-top","20px");
    });
});
