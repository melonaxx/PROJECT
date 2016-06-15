<?
//---- UTF8 ±àÂë ----
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
if(!empty($_REQUEST['id'])){
	$id = intval($_REQUEST['id']);
	$sql = "SELECT sub_id,total FROM product_combination WHERE product_id = '$id'";
	$result = mysql_query($sql,$_mysql_link_);
	while($productcombination = mysql_fetch_object($result)){
		$product_combination = array();
		$sql = "SELECT i.name,d.price_display,d.parts_id FROM product_info AS i LEFT JOIN product_detail AS d ON d.id = i.id WHERE i.company_id = '$company_id' AND i.is_delete = 'N' AND i.id = '{$productcombination->sub_id}'";
		$result_product = mysql_query($sql,$_mysql_link_);
		while($product_info = mysql_fetch_object($result_product)){
			$product_combination['sub_id'] 			= $product_info->name;
			$product_combination['price_display']	= $product_info->price_display;
			$sql = "SELECT name FROM product_parts_name WHERE company_id = '$company_id' AND id = '{$product_info->parts_id}'";
			$result_parts = mysql_query($sql,$_mysql_link_);
			while($product_parts = mysql_fetch_object($result_parts)){
				$product_combination['parts_id'] = $product_parts->name;
			}
		}
		$product_combination['total']	= $productcombination->total;
		$xtpl->assign("product_combination", $product_combination);
		$xtpl->parse("main.product_combination");
	}
}

$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");