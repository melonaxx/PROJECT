$(function(){
    $('.circle').each(function() {
        var num = $(this).find('.percent').text() * 3.6;
        if (num<=180) {
            $(this).find('.right').css({'transform':"rotate(" + num + "deg)"});
        } else {
            $(this).find('.right').css('transform', "rotate(180deg)");
            $(this).find('.left').css('transform', "rotate(" + (num - 180) + "deg)");
        };
    });

    $.fn.extend({
        Scroll:function(opt,callback){

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
