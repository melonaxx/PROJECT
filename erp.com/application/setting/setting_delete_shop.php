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

$id		= intval($_REQUEST['id']);
$sql	= "SELECT name, bind_type, user_id FROM company_shop WHERE company_id='".$_SESSION['_application_info_']['company_id']."' AND id='$id'";
$result	= mysql_query($sql, $_mysql_link_);
if(mysql_num_rows($result) < 1)
{
	header("Location: /setting/setting_shop.php");
	exit;
}
$ShopInfo	= mysql_fetch_object($result);
$sql	= "SELECT related_type FROM company_related WHERE company_id='".$_SESSION['_application_info_']['company_id']."' AND user_id='$ShopInfo->user_id'";
$result	= mysql_query($sql, $_mysql_link_);
if(mysql_num_rows($result))
{
	$related_type	= mysql_result($result, 0, 'related_type');
	if($related_type == "Create")
	{
		$_SESSION['error']	= "公司的创建者不能删除";
		header("Location: /setting/setting_shop.php");
		exit;
	}
	$sql	= "DELETE FROM company_related WHERE user_id='$ShopInfo->user_id'";
	mysql_query($sql, $_mysql_link_);
}

$sql	= "DELETE FROM company_shop WHERE id='$id'";
mysql_query($sql, $_mysql_link_);

header("Location: /setting/setting_shop.php");
exit;
