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

$sql	= "SELECT id, name FROM product_attrib_name WHERE company_id='".$_SESSION['_application_info_']['company_id']."' AND is_delete='N' ORDER BY id";
$result	= mysql_query($sql, $_mysql_link_);
while($AttribInfo = mysql_fetch_object($result))
{
	$list_attrib			= array();
	$list_attrib['id']		= $AttribInfo->id;
	$list_attrib['name']	= $AttribInfo->name;
	$xtpl->assign("list_attrib", $list_attrib);
	$xtpl->parse("main.list_attrib");
	$total++;
}
$main['total']	= $total;

$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");