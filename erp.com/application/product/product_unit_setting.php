<?
//---- UTF8 编码 ----
session_start();
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Content-Type: application/xml; charset=UTF-8");
header("X-UA-Compatible: IE=EmulateIE7");

include "../../config.php";
include "../../xtpl.php";
include "../detect_permit.php";
include "../function.php";

$total	= 0;
$sql	= "SELECT id, name FROM product_parts_name WHERE company_id='".$_SESSION['_application_info_']['company_id']."' AND is_delete='N' ORDER BY id";
$result	= mysql_query($sql, $_mysql_link_);
while($PartsInfo = mysql_fetch_object($result))
{
	$list_parts			= array();
	$list_parts['id']	= $PartsInfo->id;
	$list_parts['name']	= $PartsInfo->name;
	$xtpl->assign("list_parts", $list_parts);
	$xtpl->parse("main.list_parts");
	$total++;
}
$main['total']	= $total;

$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");