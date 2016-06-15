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

//---- 只有公司创建者，才可以进行人员管理 ----
if($_SESSION['_application_info_']["admin_id"] == 0 || $_SESSION['_application_info_']["company_id"] == 0)
{
	$main['permit']	= 0;
	$xtpl->assign("main", $main);
	$xtpl->parse("main");
	$xtpl->out("main");
	exit;
}
$total	= 0;
$no		= 0;
$sql	= "SELECT id, name, body, action_date FROM company_staff_role WHERE company_id='".$_SESSION['_application_info_']["company_id"]."'";
$result	= mysql_query($sql, $_mysql_link_);
while($dbRow = mysql_fetch_object($result))
{
	$no++;
	$list_role	= array();
	$list_role['no']	= $no;
	$list_role['id']	= $dbRow->id;
	$list_role['name']	= $dbRow->name;
	$list_role['date']	= $dbRow->action_date;
	$list_role['text']	= $dbRow->body;
	$xtpl->assign("list_role", $list_role);
	$xtpl->parse("main.list_role");
	$total++;
}
$main['total']	= $total;

$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");
