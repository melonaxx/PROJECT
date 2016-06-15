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
$company_id	= $_SESSION['_application_info_']['company_id'];

$sql = "SELECT id,name FROM  store_info WHERE company_id='$company_id' order by id";
$result = mysql_query($sql,$_mysql_link_);
while($storeInfo = mysql_fetch_object($result)){
	$storeList = array();
	$storeList['id'] = $storeInfo->id;
	$storeList['name'] = $storeInfo->name;
	
	$xtpl->assign("storeList", $storeList);
	$xtpl->parse("main.storeList");	
}
$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");