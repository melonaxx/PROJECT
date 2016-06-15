<?php

pylon_include_sdk("/home/q/php/sdk_base/", "sdk_base.php");
pylon_include_sdk("/home/q/php/bridge_sdk/", "bridge_sdk.php");

/**
 * @brief   图片上传
 */
class UploadLicense
{
	/**
 	* @brief   七牛云
 	*/
	// const PICURL = "7xsicf.com1.z0.glb.clouddn.com";
	const UPLOADPIC = "o7ntuivxz.qnssl.com";
	
	public static function license($temfile) {
		$result = BridgeSvc::ins()->callbridge($temfile);
		foreach ($result as $key => $value) {
			$hash = $value['hash'];
		}

		return $hash;
	}
}

/**
 * @brief   营业执照图片上传
 */
class Action_upload extends XAction
{
	public function _run($request , $xcontext)
	{
		$error = $_FILES["file"]["error"];
		$tmp = $_FILES['file']['tmp_name'];
		if ($error || !is_uploaded_file($tmp)) {
			echo ResultSet::jfail(4001 , "Image upload error" );
            return XNext::nothing();
		}

        $imgtype = array("image/gif","image/jpg","image/jpeg","image/bmp","image/png");
        $type = $_FILES['file']['type'];
        if(!in_array($type , $imgtype)) {
			echo ResultSet::jfail(4002 , "Image upload type error" );
            return XNext::nothing();
        }

		$size = $_FILES['file']['size'];
		if($size > 2*1024*1024) {
			echo ResultSet::jfail(4003 , "Image size is no more than 2M" );
            return XNext::nothing();
		}

		$imgname = UploadLicense::license($tmp);

		if($tmp && $imgname) {
			echo ResultSet::jsuccess($imgname);
            return XNext::nothing();
		}
	
	}
}



                                                            