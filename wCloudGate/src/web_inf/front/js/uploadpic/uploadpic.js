$(function() {
// 图片上传demo
    var $ = jQuery,
        $list = $('#fileList'),

        // 优化retina, 在retina下这个值是2
        ratio = window.devicePixelRatio || 1,

        // 缩略图大小
        thumbnailWidth = 120 * ratio,
        thumbnailHeight = 120 * ratio,

        // 所有文件的进度信息，key为file id
        percentages = {},

        // Web Uploader实例
        uploader;

    // 初始化Web Uploader
    uploader = WebUploader.create({

        // 自动上传。
        auto: true,
            
        // swf文件路径
		swf: '/js/uploadpic/Uploader.swf',

        // 文件接收服务端。
        server: '/upload.php',

        // 选择文件的按钮。可选。
        // 内部根据当前运行是创建，可能是input元素，也可能是flash.
        pick: '#filePicker',

        // 只允许选择文件，可选。
        accept: {
            title: 'Images',
            extensions: 'gif,jpg,jpeg,bmp,png',
            mimeTypes: 'image/*'
        },
        //禁掉整个页面的拖拽功能 chrome不兼容
        disableGlobalDnd: true,
        //图片个数
        fileNumLimit: 1,
        //图片大小
        fileSizeLimit: 2 * 1024 * 1024

    });


    // 当有文件添加进来的时候
    uploader.on( 'fileQueued', function( file ) {

    	$(".picarea p").hide();
    	$(".imagemsg").html("上传中...").show();
        var $li = $(
                '<div id="' + file.id + '" class="file-item thumbnail">' +
                    '<img>' +
                '</div>'
                ),
            $img = $li.find('img');

        $list.html( $li );

        // 创建缩略图
        uploader.makeThumb( file, function( error, src ) {
            if ( error ) {
                $img.replaceWith('<span>不能预览</span>');
                return;
            }

            $img.attr( 'src', src );
        }, thumbnailWidth, thumbnailHeight );
    });

    // 文件上传成功，给item添加成功class, 用样式标记上传成功。
    uploader.on( 'uploadSuccess', function( file ) {
        $(".imagemsg").html("上传成功");
        uploader.reset();
    });

    // 文件上传失败，现实上传出错。
    uploader.on( 'uploadError', function( file ) {
        $(".imagemsg").hide().html("上传失败");
    });

    uploader.on('error', function(handler) {
        var msg = "";
        switch(handler) {
            case "Q_EXCEED_SIZE_LIMIT":
            msg = "图片大小不能超过2M";
            break;            
            case "Q_EXCEED_NUM_LIMIT":
            msg = "只能上传一个图片";
            break;            
            case "Q_TYPE_DENIED":
            msg = "图片类型错误";
            break;
            default:
            break;
        }
        $(".imagemsg").html(msg).show();
    });

    uploader.on( 'uploadAccept', function( obj , ret ) {
        var imgname = ret.data;
        $("input[name=imgname]").val(imgname);
    });


});