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
$addon = array();
$addon[] = "company_id = '$company_id'";
if(!empty($_GET['id'])){
	$product_id = intval($_GET['id']);
	$addon[] 	= "product_id = '".$product_id."'";
}
if(count($addon) > 0)
{
	$where	= "WHERE ".implode(" AND ", $addon);
}
	$sql = "SELECT store_id,real_total,product_id FROM store_related ".$where;
	$result = mysql_query($sql,$_mysql_link_);
	$num = 1;
	while($Store_realted = mysql_fetch_object($result)){
		$store = array();
		//查询仓库
		$sql_2 = "SELECT name FROM store_info WHERE company_id = '$company_id' AND id = '{$Store_realted->store_id}'";
		$result_2 = mysql_query($sql_2,$_mysql_link_);
		while($company = mysql_fetch_object($result_2)){
			$store['store_id']	=	$company->name;
		}
		//查询价格
		$sqll = "SELECT price_purchase FROM product_detail WHERE id='$product_id'";
		$res = mysql_query($sqll,$_mysql_link_);
		while($rows = mysql_fetch_object($res)){
			$store['price']	=	$rows->price_purchase;
		}
		//查询数量
		$store['num']			=	$num++;
		$store['total']			= 	$Store_realted->real_total;
		// $store['price']         =   $rows->price_purchase;
		$xtpl->assign("store", $store);
		$xtpl->parse("main.store");
}

$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");
