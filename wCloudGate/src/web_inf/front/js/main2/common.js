$(function(){

    //导航栏选中
    var typeobj = $(".nav").children();
    typeobj.removeClass("navactive");
    var href = typeobj.children("a");
    var activeurl = window.location.href;
    $.each(href , function() {
        if(activeurl.indexOf($(this).attr("href")) > -1){
            $(this).parent().addClass("navactive");
        }
    }); 
    // 页脚位置
	$(window).on("load", function() {
        var footerHeight = 0,
            footerTop = 0,
            footer = $("#footer");
        positionFooter();
        function positionFooter() {
            footerHeight = footer.height();
            footerTop = ($(window).scrollTop()+$(window).height()-footerHeight)+"px";
            if ( ($(document.body).height()+footerHeight) < $(window).height()) {
                footer.css({
                    position: "absolute",
                    top: footerTop
                })
            } else {
                footer.css({
                    position: "static"
                });
            }
        }
        $(window).scroll(positionFooter).resize(positionFooter);
    });

    // 页首下拉菜单显示隐藏
    $(".useroper").hover(
        function(){
            $(".useroperul").show();
            $(".useroper").css({"border":"1px solid #ccc"});
        },
        function(){
        $(".useroperul").hide();
        $(".useroper").css("border","1px solid #fff");
    });

    // input框获得焦点
    $(document).on({ 
        focus: function() { 
            $(this).css({
                "background":"#def0fc",
                "border":"1px solid #5daae2",
                "box-shadow":"0 0 2px #5daae2"
            })
        }, 
        blur: function() { 
            $(this).css({
                "background":"#fff",
                "border":"1px solid #ccc",
                "box-shadow":"none"
            })
        }
    },"input[type='text'],input[type='password']");

    //首页搜索
    $(".inputother").on({ 
        focus: function() { 
            $(this).css({
                "background":"#fff",
                "border":"none",
                "box-shadow":"none"
            })
        }, 
        blur: function() { 
            $(this).css({
                "background":"#fff",
                "border":"none",
                "box-shadow":"none"
            })
        }
    });

    // 模态框点击关闭按钮
    $(".close").on("click",function(){
        $(".modal").hide();
    });

    // placeholder兼容
    var JPlaceHolder = {};
    $.extend(JPlaceHolder, {
        //检测
        check : function(){
            return 'placeholder' in document.createElement('input');
        },
        //初始化
        init : function(){
            if(!this.check()){
                this.fix();
            }
        },
        //修复
        fix : function(){
            jQuery(':input[placeholder]').each(function(index, element) {
                var self = $(this), txt = self.attr('placeholder');
                self.wrap($('<div></div>').css({position:'relative', zoom:'1', border:'none', background:'none', padding:'none', margin:'none'}));
                var h = self.outerHeight(true), paddingleft = self.css('padding-left');
                var holder = $('<span></span>').text(txt).css({position:'absolute', left:0, top:0, height:h+"px", lineHeight:h+"px", paddingLeft:paddingleft, color:'#aaa'}).appendTo(self.parent());
                self.on("focusin", function(e) {
                    holder.hide();
                }).on("focusout", function(e) {
                    if(!self.val()){
                        holder.show();
                        holder.text(txt);
                    }
                });
                holder.on("click", function(e) {
                    holder.hide();
                    self.focus();
                });
            });
        }
    });
    //执行
    jQuery(function(){
        JPlaceHolder.init();    
    });

    window.JPlaceHolder = JPlaceHolder;

    //小模块table内容超出时 右边选择条变化同高
    $(".wmainlist").height($(".wmaincontent").height()+40);
    $(".wmainheight").height($(".wmaincontent").height());

    //用户ID
    var userid = $('input[name=mapuserid]').val();
    //劳务方ID
    var laborid = $('input[name=maplaborid]').val();
    //用户类型
    var usertype = $('input[name=mapusertype]').val();
    // 平台ID
    var platformid = $('input[name=mapplatformid]').val();
    //骑士序列号
    var knightseqno = $('input[name=knightseqno]').val();

    //改变查看定位的URL地址
    var GetReques = function() {
        var url = location.search; //获取url中"?"符后的字串
        var theRequest = new Object();
        if (url.indexOf("?") != -1) {
            var str = url.substr(1);
            strs = str.split("&");
            for(var i = 0; i < strs.length; i ++) {
                theRequest[strs[i].split("=")[0]]=decodeURIComponent(strs[i].split("=")[1]);
            }
        }
        return theRequest;
    }
    var getskey, counter = 0;
    for(getskey in GetReques()) counter++;
    switch (usertype) {
        case '0': //员工
            if (counter <= 0) {
                $('.mapall').attr('href','/gotomap.php?inttype=uipandectall ');
                $('.maprun').attr('href','/gotomap.php?inttype=uipandectrun');
                $('.mapunusual').attr('href','/gotomap.php?inttype=uipandectunusual');
                $('.maprest').attr('href','/gotomap.php?inttype=uipandectrest');
            } else {
                $('.mapall').attr('href','/gotomap.php?inttype=uiplatall&laborid='+laborid);
                $('.maprun').attr('href','/gotomap.php?inttype=uiplatrun&laborid='+laborid);
                $('.mapunusual').attr('href','/gotomap.php?inttype=uiplatunusual&laborid='+laborid);
                $('.maprest').attr('href','/gotomap.php?inttype=uiplatrest&laborid='+laborid);
            }

            break;
        case '1': //平台
            if (counter <= 0) {
                $('.mapall').attr('href','/gotomap.php?inttype=pipandectall');
                $('.maprun').attr('href','/gotomap.php?inttype=pipandectrun');
                $('.mapunusual').attr('href','/gotomap.php?inttype=pipandectunusual');
                $('.maprest').attr('href','/gotomap.php?inttype=pipandectrest');
            } else {
                $('.mapall').attr('href','/gotomap.php?inttype=pilabortall&laborid='+laborid);
                $('.maprun').attr('href','/gotomap.php?inttype=pilaborrun&laborid='+laborid);
                $('.mapunusual').attr('href','/gotomap.php?inttype=pilaborunusual&laborid='+laborid);
                $('.maprest').attr('href','/gotomap.php?inttype=pilaborrest&laborid='+laborid);
            }

            break;

        case '2'://劳务方
            if (counter <= 0) {
                $('.mapall').attr('href','/gotomap.php?inttype=lipandectall ');
                $('.maprun').attr('href','/gotomap.php?inttype=lipandectrun');
                $('.mapunusual').attr('href','/gotomap.php?inttype=lipandectunusual');
                $('.maprest').attr('href','/gotomap.php?inttype=lipandectrest');
            } else {
                $('.mapall').attr('href','/gotomap.php?inttype=liplatall&platformid='+platformid);
                $('.maprun').attr('href','/gotomap.php?inttype=liplatrun&platformid='+platformid);
                $('.mapunusual').attr('href','/gotomap.php?inttype=liplatunusual&platformid='+platformid);
                $('.maprest').attr('href','/gotomap.php?inttype=liplatrest&platformid='+platformid);
            }
            break;

        case '4'://骑士
            if (counter == 0) {
                $('.gotomap').attr('href','/gotomap.php?inttype=mymap&seqno='+knightseqno+'&usertype='+usertype);
            }

            break;
    }
    // 回到顶部                  
    $(".scrolltop").on("click",function(){
        $('html,body').animate({scrollTop:0},100);
    });

    $(".useroperul li:eq(0)").css("display" , "none");
    $(".useroperul").css("height" , "86px");
    if( $(".useroperul li").length == 3 ) {
        $(".useroperul").css("height" , "54px");
    }
});