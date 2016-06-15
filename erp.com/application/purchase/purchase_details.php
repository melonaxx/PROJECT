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

$company_id = $_SESSION['_application_info_']['company_id'];
if(!empty($_GET['id'])){
	$sql = "SELECT p.store_id,m.supplier_id,m.name,m.action_date FROM purchase_main_info AS m LEFT JOIN purchase_product AS p ON p.purchase_id = m.id WHERE m.id = '$id'";
	$result = mysql_query($sql,$_mysql_link_);
	$purchase_main = mysql_fetch_object($result);
	$main['store_id']	=	$purchase_main->store_id;
	$main['supplier']	=	$purchase_main->supplier_id;
	$main['name']		=	$purchase_main->name;
	$main['action_date']=	$purchase_main->action_date;
}
//供应商
$sql = "SELECT id,name FROM purchase_supplier WHERE is_delete = 'N'";
$result = mysql_query($sql,$_mysql_link_);
while($purchase = mysql_fetch_object($result)){
	$purchasesupplier = array();
	$purchasesupplier['id']		=	$purchase->id;
	$purchasesupplier['name']	=	$purchase->name;
	$xtpl->assign("purchasesupplier", $purchasesupplier);
	$xtpl->parse("main.purchasesupplier");
}
//仓库
$sql = "SELECT id,name FROM store_info WHERE store_status <> 'Delete'";
$result = mysql_query($sql,$_mysql_link_);
while($storeinfo = mysql_fetch_object($result)){
	$store_info = array();
	$store_info['id']	=	$storeinfo->id;
	$store_info['name']	=	$storeinfo->name;
	$xtpl->assign("store_info", $store_info);
	$xtpl->parse("main.store_info");
}
$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");
