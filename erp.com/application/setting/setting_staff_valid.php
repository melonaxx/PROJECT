<?
//---- UTF8 编码 ----
session_start();
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Content-Type: text/html; charset=UTF-8");
header("X-UA-Compatible: IE=EmulateIE7");

include "../../config.php";
include "../detect_permit.php";

//---- 没有登录 ----
if($_SESSION['_application_info_']["admin_id"] == 0 || $_SESSION['_application_info_']["company_id"] == 0)
{
	echo "<br/><br/>您无权访问该页面<br/><br/><br/><br/>";
	exit;
}
if($_SERVER['REQUEST_METHOD'] != 'POST')
{
	echo "<br/><br/>您无权访问该页面<br/><br/><br/><br/>";
	exit;
}

$is_valid	= ($_POST['is_valid'] == "Y") ? "Y" : "N";
$id			= intval($_POST['id']);
$sql		= "UPDATE company_staff_info SET is_valid='$is_valid' WHERE company_id='".$_SESSION['_application_info_']["company_id"]."' AND id='$id'";
mysql_query($sql, $_mysql_link_);

echo "<script>\n";
if($is_valid == "Y")
{
	$txt	= "停用";
	$st		= "正常";
	$nv		= "N";
}
else
{
	$txt	= "启用";
	$st		= "已停用";
	$nv		= "Y";
}
echo "parent.getObject('staff_".$id."').innerHTML	= '$txt';\n";
echo "parent.getObject('status_".$id."').innerHTML	= '$st';\n";
echo "parent.getObject('staff_".$id."').onclick	= function(){parent.valid_staff('".$id."', '".$nv."'); return false};\n";

echo "</script>\n";

