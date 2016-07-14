<?php
pylon_include_sdk("/home/q/php/sdk_base/", "sdk_base.php");
pylon_include_sdk("/home/q/php/bridge_sdk/", "bridge_sdk.php");
/**
 * 订单中的图片上传
 */
class Action_order_orderimagequery extends XAction
{
    public function _run($request, $xcontext)
    {
    	$orderid = $request->orderid;
    	$picname = $_FILES['goodspic']['name'];
        $picsize = $_FILES['goodspic']['size'];
        if ($picname != "") {
        	//图片大小
            if ($picsize > 800000) {
                echo ResultSet::jfail(421, "picture size > 800kb!");
                return XNext::nothing();
            }
            //图片类型
            $type = strstr($picname, '.');
            if ($type != ".gif" && $type != ".jpg" && $type != ".png" && $type != ".jpeg") {
                echo ResultSet::jfail(432, "image type not fond!");
                return XNext::nothing();
            }

            //图片路径
            $tmp_file = $_FILES['goodspic']['tmp_name'];
            if (!is_uploaded_file($tmp_file)) {
                echo ResultSet::jfail(433, "not fond upload file!");
                return XNext::nothing();
            }

            //上传图片
            $qn = BridgeSvc::ins()->callbridge($tmp_file);

            if ($qn[0] != 0) {
                echo ResultSet::jfail(434, "upload file fail!");
                return XNext::nothing();
            }

            /*进行图片的添加*/
            $imgflag = OrderMsgImgSvc::ins()->addmsgimg($orderid,$qn[1]['key']);

            $picpath = ProImage::IMAGEPATH;
            $imgarr = array('qn'=>$qn,'picsize'=>$picsize,'picpath'=>$picpath,'ordermsgid'=>$imgflag['id']);

            if (!$imgflag) {
            	echo ResultSet::jfail(435, "image save fail!");
                return XNext::nothing();
            }

	        echo ResultSet::jsuccess($imgarr);
	        return XNext::nothing();
        }
    }
}

/**
 * 订单中的图片删除
 */
class Action_order_delordermsg extends XAction
{
    public function _run($request, $xcontext)
    {
    	$ordermsgid = $request->ordermsgid;

    	$msgflag = XDao::query('OrderMsgImgWriter')->delOrderImg($ordermsgid);

    	if (!$msgflag) {
        	echo ResultSet::jfail(435, "delordermsg fail!");
            return XNext::nothing();
        }

        echo ResultSet::jsuccess(1);
        return XNext::nothing();
    }
}