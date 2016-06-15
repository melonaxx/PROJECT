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
if(!empty($_GET['store_id'])){
	$store_id = intval($_GET['store_id']);
	if(!empty($store_id)){
		$addon[] = "store_id = $store_id";
		$page_param		= array();
		$page_param['store_id']		= replace_safe($_REQUEST['store_id'], 20, false, false);
	}
}
if(!empty($_GET['id'])){
	$product_id = intval($_GET['id']);
	if(!empty($product_id)){
		$addon[] = "product_id = $product_id";
		$page_param		= array();
		$page_param['store_id']		= replace_safe($_REQUEST['store_id'], 20, false, false);
	}
}
if(count($addon) > 0)
{
	$where	= "WHERE ".implode(" AND ", $addon);
}
	$sql = "SELECT store_id,area_id,shelves_id,location_id,total FROM store_related $where";
	$result = mysql_query($sql,$_mysql_link_);
	while($Store_realted = mysql_fetch_object($result)){
		$store = array();
		//查询仓库
		$sql_2 = "SELECT name FROM store_info WHERE company_id = '$company_id' AND id = '{$Store_realted->store_id}'";
		$result_2 = mysql_query($sql_2,$_mysql_link_);
		while($company = mysql_fetch_object($result_2)){
			$store['store_id']	=	$company->name;
		}
		//查询库区
		$sql_3 = "SELECT name FROM store_location WHERE company_id = '$company_id' AND id = '{$Store_realted->area_id}' AND location_type = 'Area'";
		$result_3 = mysql_query($sql_3,$_mysql_link_);
		while($company = mysql_fetch_object($result_3)){
			$store['area_id']	=	$company->name;
		}
		//查询货架
		$sql_4 = "SELECT name FROM store_location WHERE company_id = '$company_id' AND id = '{$Store_realted->shelves_id}' AND location_type = 'shelves'";
		$result_4 = mysql_query($sql_4,$_mysql_link_);
		while($company = mysql_fetch_object($result_4)){
			$store['shelves_id']=	$company->name;
		}
		//查询货位
		$sql_5 = "SELECT name FROM store_location WHERE company_id = '$company_id' AND id = '{$Store_realted->location_id}' AND location_type  = 'Location'";
		$result_5 = mysql_query($sql_5,$_mysql_link_);
		while($company = mysql_fetch_object($result_5)){
			$store['location_id']=	$company->name;
		}
		//查询数量
		$store['total']			= 	$Store_realted->total;
		$xtpl->assign("store", $store);
		$xtpl->parse("main.store");
}

$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");
