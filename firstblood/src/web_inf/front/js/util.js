(function($) {
    var util = {};
    $.extend(util, {
        htmlEncode: function(str) {
            var div = document.createElement("div");
            var text = document.createTextNode(str);
            div.appendChild(text);
            return div.innerHTML;
        },

        htmlDecode: function(input) {
            var div = document.createElement("div");
            div.innerHTML = input;
            return div.innerText || div.textContent;
        },

        subString: function(str, length) {
            if (str.length <= length)
                return str;

            return str.substr(0, length) + "...";
        },

        template:function(/*args*/){
            var t = [].shift.call(arguments);
            if(typeof arguments[0] === 'object' && !$.isFunction(arguments[0])){
                return $.each(arguments[0], function(name, value){
                    t = t.replace(new RegExp('{'+name+'}', 'g'), this);
                }) && t.replace(/{.*?}/g, "");
            }else{
                return $.each(arguments, function(){
                    t = t.replace(/{.*?}/, this);
                }) && t.replace(/{.*?}/g, "");
            }
        },

        formatDate : function(date, fmt) {
            var o = {
                "M+" : date.getMonth()+1, //月份
                "d+" : date.getDate(), //日
                "h+" : date.getHours(), //小时
                "H+" : date.getHours(), //小时
                "m+" : date.getMinutes(), //分
                "s+" : date.getSeconds(), //秒
                "q+" : Math.floor((date.getMonth()+3)/3),
                "S" : date.getMilliseconds() //毫秒
            };
            var week = {
                "0" : "\u65e5",
                "1" : "\u4e00",
                "2" : "\u4e8c",
                "3" : "\u4e09",
                "4" : "\u56db",
                "5" : "\u4e94",
                "6" : "\u516d"
            };
            if(/(y+)/.test(fmt)){
                fmt=fmt.replace(RegExp.$1, (date.getFullYear()+"").substr(4 - RegExp.$1.length));
            }
            if(/(E+)/.test(fmt)){
                fmt=fmt.replace(RegExp.$1, ((RegExp.$1.length>1) ? (RegExp.$1.length>2 ? "\u661f\u671f" : "\u5468") : "")+week[date.getDay()+""]);
            }
            for(var k in o){
                if(new RegExp("("+ k +")").test(fmt)){
                    fmt = fmt.replace(RegExp.$1, (RegExp.$1.length==1) ? (o[k]) : (("00"+ o[k]).substr((""+ o[k]).length)));
                }
            }
            return fmt;
        },

        cookie: function(name, value, options) {
            if (typeof value != 'undefined') {
                options = options || {};
                if (value === null) {
                    value = '';
                    options.expires = -1;
                }
                var expires = '';
                if (options.expires && (typeof options.expires == 'number' || options.expires.toUTCString)) {
                    var date;
                    if (typeof options.expires == 'number') {
                        date = new Date();
                        date.setTime(date.getTime() + (options.expires * 24 * 60 * 60 * 1000));
                    } else {
                        date = options.expires;
                    }
                    expires = '; expires=' + date.toUTCString();
                }
                var path = options.path ? '; path=' + (options.path) : '';
                var domain = options.domain ? '; domain=' + (options.domain) : '';
                var secure = options.secure ? '; secure' : '';
                document.cookie = [name, '=', encodeURIComponent(value), expires, path, domain, secure].join('');
            } else {
                var cookieValue = null;
                if (document.cookie) {
                    var cookies = document.cookie.split(';');
                    for (var i = 0; i < cookies.length; i++) {
                        var cookie = $.trim(cookies[i]);
                        if (cookie.substring(0, name.length + 1) == (name + '=')) {
                            cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
                            break;
                        }
                    }
                }
                return cookieValue;
            }
        },

        getNextDay: function(date) {
            date = date || new Date();
            date.setHours(0);
            date.setMinutes(0);
            date.setSeconds(0);
            date.setDate(date.getDate()+1);

            return date;
        },

        addStyleText: function(cssStr) {
            var style = document.createElement("style");
            style.setAttribute("type", "text/css");
            if (style.styleSheet) { // IE
                style.styleSheet.cssText = cssStr;
            } else { // w3c
                var cssText = document.createTextNode(cssStr);
                style.appendChild(cssText);
            }

            var head = document.head || document.getElementsByTagName( "head" )[0] || document.documentElement;
            head.insertBefore(style, head.firstChild);

            return style;
        },

        getDayOfWeek: function(day, date) {
            if (!date) {
                date = new Date();
            } else if (typeof date == "string") {
                date = new Date(Date.parse(date));
            }

            if (day < 1 || day > 7) {
                return null;
            }

            var weekday = date.getDay();
            weekday = weekday == 0 ? 7 : weekday;

            var time = date.getTime();
            time += (day-weekday)*24*60*60*1000;

            return util.formatDate(new Date(time), "yyyy-MM-dd");
        },

        number_format: function(num, thousands_sep) {
            if(isNaN(num)) {
                return "";
            }

            thousands_sep = thousands_sep || ",";

            num = num + "";

            var re =/(-?\d+)(\d{3})(\.\d*)?/;
            while(re.test(num)) {
                num = num.replace(re, function($0, $1, $2, $3) {
                    return $1 + thousands_sep + $2 + ($3 ? $3 : "");
                });
            }

            return num;
        },

        // 格式化秒，将其表现成 0:49 这样的形式
        formatSeconds: function(seconds) {
            seconds = parseInt(seconds || 0) || 0;
            var s = seconds % 60;
            var m = (Math.floor(seconds/60)) % 60;
            var h = Math.floor(seconds/3600);

            function padNumber(n) {
                if (n < 10 && n >= 0) {
                    return "0" + n;
                }

                return n + "";
            }

            if (h > 0) {
                return h + ":" + padNumber(m) + ":" + padNumber(s);
            } else {
                return m + ":" + padNumber(s);
            }
        },

        ajax_get: function(url, data, success, error, complete) {
            return ajax(url, "GET", data, success, error, complete);
        },

        ajax_post: function(url, data, success, error, complete) {
            return ajax(url, "POST", data, success, error, complete);
        }
    });

    window.util = util;

    function ajax(url, type, data, success, error, complete) {
        return $.ajax({
            url: url,
            cache: false,
            type: type,
            data: data,
            dataType: "json",
            success: function(data, textStatus, xhr){
                if (!data) {
                    error && error(-1);
                    return;
                }

                if (data.errno !== 0) {
                    error && error(data.errno, data.errmsg);
                } else {
                    success && success(data.data);
                }
            },
            error: function() {
                error && error(-1);
            },
            complete: function() {
                complete && complete();
            }
        });
    }
})(jQuery);