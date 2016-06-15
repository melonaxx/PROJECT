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

$company_id = $_SESSION['_application_info_']['company_id'];
$sql = "SELECT id,name FROM store_info WHERE company_id = '$company_id' AND store_status <> 'Delete'";
$result = mysql_query($sql,$_mysql_link_);
while($StoreInfo = mysql_fetch_object($result)){
	$list_location  = array();
	$list_location['id']		= "-".$StoreInfo->id;
	$list_location['name']		= $StoreInfo->name;
	$list_location['parent_id']	= 0;
	$xtpl->assign("list_location", $list_location);
	$xtpl->parse("main.list_location");
}
$sql	= "SELECT id, store_id, name, parent_id FROM store_location WHERE company_id='$company_id'";
$result	= mysql_query($sql, $_mysql_link_);
while($LocationInfo = mysql_fetch_object($result))
{
	$list_location	= array();
	$list_location['id']			= $LocationInfo->id;
	$list_location['store_id']		= $LocationInfo->store_id;
	$list_location['name']			= $LocationInfo->name;
	$list_location['parent_id']		= $LocationInfo->parent_id;
	if($list_location['parent_id'] == 0)
	{
		$list_location['parent_id']	= "-".$list_location['store_id'];
	}

	$xtpl->assign("list_location", $list_location);
	$xtpl->parse("main.list_location");
}
if(!empty($_GET['id'])){
	$main['id'] = $_GET['id'];
}
$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");