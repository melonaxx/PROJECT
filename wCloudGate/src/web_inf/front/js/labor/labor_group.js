$(function(){

    $(".nav li:eq(0) a").css("color" , "#358dcc");
    $(".nav li:eq(0) p").css("display" , "block");
    $(".search").on("click",function(){
        $(".wmainlistall").hide();
        $(".wmainlistsearch").show();
    });
    $(".abnstadiv li").hover(function(){
        $(this).css({"box-shadow":"0 0 4px #a9a9a9"});
        $(this).find(".abnstatp").hide();
        $(".hideope a").removeClass("opeactive");
        $(this).find(".hidediv").animate({top:100},100);
    },
    function(){
        $(this).css({"box-shadow":"none"});
        $(this).find(".abnstatp").show();
        $(this).find(".hidediv").animate({top:200},100);
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

    $(".default").click(function(){
        window.location.href="/main.php";
    });

    $(".searchbtn").click(function(){
        var val = $('.inputother').val();
        if(val === ""){
            $(".inputother").css("border","0.1px solid red");
            return false;
        }
        var data = {
            name:val,
        };
        
        util.ajax_post("searchbygroup.php",data,searSuccess,searFail);
    });
    $(document).ready(function(e) {
        $(".inputother").keydown(function (e){
            if(e.which == "13"){
                var val = $('.inputother').val();
                if(val === ""){
                    $(".inputother").css("border","0.1px solid red");
                    return false;
                }
                var userid = $("input[name='mapuserid']").val();
                var data = {
                    name:val,
                };

                util.ajax_post("searchbygroup.php",data,searSuccess,searFail);
            }
        })
    });
    function searSuccess(data){
        if(data == null || data == ""){
            $(".wmainlist li:eq(2)").nextAll().empty();
        }else{
            var str = "";
            for(var i=0;i<data.length;i++){
                str += "<li class='wllevel'><a href='/groupsort.php?status=search&groupid="+data[i]['id']+"'><span>"+(i+1)+"</span><span>"+data[i]['name']+"</span><p></p></a> </li>";
            }
            $(".wmainlist li:eq(2)").nextAll().empty();
            $(".wmainlist li:eq(2)").next().append(str);
        }
    }
    function searFail(data){
        $(".wmainlist li:eq(2)").nextAll().empty(); 
    }

    $(".hideope a").hover(function(){
        $(this).addClass("opeactive");
        $(this).prev().removeClass("opeactive");
        $(this).next().removeClass("opeactive");
    });

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
            speed=opt.speed?parseInt(opt.speed,10):500; 
            if(line==0) line=1;
            var upHeight=0-line*lineH;

            var scrollUp=function(){
                _btnUp.unbind("click",scrollUp);
                _this.animate({
                    marginTop:upHeight
                },speed,function(){
                    for(i=1;i<=line;i++){
                        _this.find("li:first").appendTo(_this);
                    }
                    _this.css({marginTop:0});
                    _btnUp.bind("click",scrollUp); 
                });

            }

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

            _btnUp.css("cursor","pointer").click( scrollUp );
            _btnDown.css("cursor","pointer").click( scrollDown );

        }
    })
     $("#scrollDiv").Scroll({line:1,speed:300,up:"but_up",down:"but_down"});

     if($(".notice").width()<740 || $(".notices").width()<740){
         $(".noticedivm").hide();
         $(".noticediv").show();         
     }else{
         $("noticedivm").show();
         $(".noticediv").hide();
     }
     $(".noticedivm").hover(function(){
         $(".closenoticem").show();
     },function(){
         $(".closenoticem").hide();
     });
     $(".noticediv").hover(function(){
         $(".closenotice").show();
     },function(){
         $(".closenotice").hide();
     });
     $(".closenoticem").on("click",function(){
         $(".wmnotice").hide();
         $(".topcartitle").css("margin-top","20px");
     });
     $(".closenotice").on("click",function(){
         $(".wmnotice").hide();
         $(".topcartitle").css("margin-top","20px");
     });

     $(".group").click(function(){
        //$(this).addClass("sortmethodactive");
        //$(".default").removeClass("sortmethodactive");
        //$(".companylist").empty();
        window.location = "/groupsort.php";
     });

     var url  = window.location.href;
     var name = "groupsort.php";
     var sta  = url.indexOf(name);
     if(sta != -1 ){
        $(".group").addClass("sortmethodactive");
        $(".default").removeClass("sortmethodactive")
     }
});
