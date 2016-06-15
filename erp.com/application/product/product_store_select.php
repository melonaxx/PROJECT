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
//---- 发货仓库 ----
$ListStore  	= array();
$sql = "SELECT id, name, store_status FROM store_info WHERE (store_status = 'Normal' OR store_status = 'Default') AND company_id='$company_id'";
$result	= mysql_query($sql, $_mysql_link_);
while($StoreInfo = mysql_fetch_object($result))
{
	$list_store	= array();
	$list_store['id']			= $StoreInfo->id;
	$list_store['name']			= $StoreInfo->name;
	$ListStore[$StoreInfo->id]   = $StoreInfo->name;
	$xtpl->assign("list_store", $list_store);
	$xtpl->parse("main.list_store");
}

if(!empty($_GET['id'])){
	$pro_id = $_GET["id"]; 	
	$sql = "SELECT store_id FROM store_product WHERE company_id='$company_id' AND product_id='$pro_id' AND is_frozen='N'";
	$res = mysql_query($sql, $_mysql_link_);
	
	while($rows = mysql_fetch_object($res)){
		$store_arr = array();
		$store_arr['id'] = $rows->store_id;
		$xtpl->assign("store_arr", $store_arr);
		$xtpl->parse("main.store_arr");
	}
}

$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");