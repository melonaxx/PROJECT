<?php
pylon_include_sdk("/home/q/php/sdk_base/", "sdk_base.php");
pylon_include_sdk("/home/q/php/bridge_sdk/", "bridge_sdk.php");
/**
 * @brief 进行文件上传。
 *
 * @param 上传表单的名称
 *
 * @return bool
 **/
class Action_goods_uploadpicfromgoods extends XAction
{
	const IMAGEDOMAIN = 'http://img.1sheng.com';
    public function _run($request, $xcontext)
    {
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
            $picpath = Action_goods_uploadpicfromgoods::IMAGEDOMAIN;
            $imgarr = array('qn'=>$qn,'picsize'=>$picsize,'picpath'=>$picpath);
	        echo ResultSet::jsuccess($imgarr);
	        return XNext::nothing();
        }
    }
}
