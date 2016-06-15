<?
//---- UTF8 编码 ----
session_start();
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Content-Type: text/html; charset=UTF-8");
header("X-UA-Compatible: IE=EmulateIE7");

include "../../config.php";
include "../../xtpl.php";
include "../detect_permit.php";
include "../function.php";

$index			= intval($_REQUEST['index']);
$product_id		= intval($_REQUEST['product_id']);

if($index < 1 || $index > 4)
{
	$index		= 1;
}

$main['index']		= $index;
$main['product_id']	= $product_id;
$company_id = $_SESSION['_application_info_']['company_id'];

if(!empty($_POST))
{
	$p_id	= 0;
	$p_url	= "";
	if(!empty($_POST['remote_url']))
	{
		$remote_url	= replace_safe($_POST['remote_url']);
		$p_url	= $remote_url;
		$md5	= md5($remote_url);
		$sql	= "SELECT id FROM company_photo_info WHERE company_id='$company_id' AND file_md5='$md5'";
		$result	= mysql_query($sql, $_mysql_link_);
		if(mysql_num_rows($result))
		{
			$p_id	= mysql_result($result, 0, 'id');
		}
		else
		{
			$sql	= "INSERT INTO company_photo_info SET company_id='$company_id', url='$remote_url', file_md5='$md5'";
			mysql_query($sql, $_mysql_link_);
			if(mysql_affected_rows($_mysql_link_) == 1)
			{
				$p_id	= mysql_insert_id($_mysql_link_);
			}
		}
	}
	if(!empty($_FILES['uploadFile']))
	{
		$UploadInfo		= upload_photo('uploadFile');
		if($UploadInfo['error'] == "")
		{
			$p_id		= $UploadInfo['id'];
			$p_url		= $UploadInfo['url'];
		}
	}
	echo "<script>parent.getObject('product_image_".$index."').value='$p_id'; parent.change_product_image(".$index.", '$p_url'); parent.$('#MessageBox').modal('hide');</script>";
	exit;
}
if($product_id > 0)
{
	$sql	= "SELECT photo_id FROM product_photo WHERE company_id='$company_id' AND product_id='$product_id' AND sort='$index'";
	$result	= mysql_query($sql, $_mysql_link_);
	if(mysql_num_rows($result))
	{
		$p_id	= mysql_result($result, 0, 'photo_id');
		$sql	= "SELECT file_name, url FROM company_photo_info WHERE company_id='$company_id' AND id='$p_id'";
		$result	= mysql_query($sql, $_mysql_link_);
		if(mysql_num_rows($result))
		{
			$PhotoInfo	= mysql_fetch_object($result);
			if(!empty($PhotoInfo->url))
			{
				$main['remote_url']	= $PhotoInfo->url;
			}
		}
	}
}

$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");


