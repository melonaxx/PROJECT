<?
//---- UTF8 ±à?? ----
session_start();
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Content-Type: application/xml; charset=UTF-8");
header("X-UA-Compatible: IE=EmulateIE7");

include "../../config.php";
include "../../xtpl.php";
include "../detect_permit.php";
include "../function.php";

$id		= intval($_REQUEST['id']);
$sql	= "SELECT name FROM product_format_name WHERE id='$id' AND company_id='".$_SESSION['_application_info_']['company_id']."'";
$result	= mysql_query($sql,$_mysql_link_);
if(mysql_num_rows($result) < 1)
{
	header("Location: /product/product_specifications_properties.php");
	exit;
}
$main['name']	= mysql_result($result, 0, 'name');

$sql	= "SELECT id, body FROM product_format_value WHERE company_id='".$_SESSION['_application_info_']['company_id']."' AND format_id='$id' AND is_delete='N'";
$result = mysql_query($sql, $_mysql_link_);
$list_format	= array();
while($dbRow = mysql_fetch_object($result))
{
	$list_format[]=array(
		'id'   =>$dbRow->id,
		'name' =>$dbRow->body,
		);
}
echo json_encode($list_format);
exit;
$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");