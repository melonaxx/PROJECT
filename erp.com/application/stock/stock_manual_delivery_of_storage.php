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

//出库入库记录中的仓库搜索
$sql = "SELECT id,name FROM store_info WHERE company_id = '$company_id' AND store_status <> 'Delete'";
$result = mysql_query($sql,$_mysql_link_);
while($store_warehouse = mysql_fetch_object($result)){
		$store_ware = array();
		$store_ware['id']	= $store_warehouse->id;
		$store_ware['name']	= $store_warehouse->name;
		$xtpl->assign("store_ware", $store_ware);
		$xtpl->parse("main.store_ware");
}
// --- 设置查询条件 ---
$addon[] = "o.company_id = '$company_id'";
if(!empty($_REQUEST['store'])){
	//---- 按照仓库查询 ----
	$store = intval($_REQUEST['store']);
		if(!empty($store)){
			$addon[] = "o.store_id = '".$store."'";
			$main['store']	= $store;
			$page_param		= array();
			$page_param['store']		= replace_safe($_REQUEST['store'], 20, false, false);
		}
}
// if(!empty($_REQUEST['product'])){
// 	//---- 按照商品名称查询 ----
// 	$product = replace_safe($_REQUEST['product'],15);
// 	if(!empty($product)){
// 		// ---- 设置查询条件 ----
// 		$addon[] = "( INSTR(i.name, '$product'))";
// 		$main['product']	= $product;
// 		$page_param		= array();
// 		$page_param['product']		= replace_safe($_REQUEST['product'], 20, false, false);
// 	}
// }
// ---- 处理查询条件 ----
$where = "";
if(count($addon) > 0){
	$where	= "WHERE ".implode(" AND ", $addon);
}
//查询数量
$sql = "SELECT COUNT(*) AS total FROM store_operation_logs AS o LEFT JOIN product_info AS i ON i.id = o.product_id $where";
$result	= mysql_query($sql, $_mysql_link_);
$main['total']		= mysql_result($result, 0, 'total');

//---- 处理分页 ----
if(!is_array($page_param))
{
	$page_param			= array();
}
$main['page_info']	= erp_page_info($main['total'], $page, $page_param);
$limit	= ($page - 1) * $_SESSION["_application_info_"]["page_size"].", ".$_SESSION["_application_info_"]["page_size"];
$type['Input']	=	"入库";
$type['Output']	=	"出库";
if($main['total'] > 0){
	$sql = "SELECT o.id,o.action_date,o.staff_id,o.product_id,o.store_id,o.total,o.type,o.body FROM store_operation_logs AS o LEFT JOIN product_info AS i ON i.id = o.product_id $where ORDER BY action_date DESC LIMIT ".$limit;
	$result = mysql_query($sql,$_mysql_link_);
	while($storeOperation = mysql_fetch_object($result)){
		$store_operation = array();
		$store_operation['action_date']	=	$storeOperation->action_date;
		$sql_staff = "SELECT nick FROM company_staff_info WHERE id = '{$storeOperation->staff_id}'";
		$result_staff = mysql_query($sql_staff,$_mysql_link_);
		while($staff_info = mysql_fetch_object($result_staff)){
			$store_operation['staff_id']	= $staff_info->nick;
		}
		$sql_product = "SELECT name FROM product_info WHERE id = '{$storeOperation->product_id}' AND is_delete = 'N' AND company_id = '$company_id'";
		$result_product = mysql_query($sql_product,$_mysql_link_);
		while($product_info = mysql_fetch_object($result_product)){
			$store_operation['product_id']	=	$product_info->name;
		}
		$sql_store = "SELECT name FROM store_info WHERE id = '{$storeOperation->store_id}' AND store_status != 'Delete' AND company_id = '$company_id'";
		$result_store = mysql_query($sql_store,$_mysql_link_);
		while($store_info = mysql_fetch_object($result_store)){
			$store_operation['store_id']	=	$store_info->name;
		}
		$store_operation['id']			=	$storeOperation->id;
		$store_operation['total']		=	$storeOperation->total;
		$store_operation['type']		=	$type[$storeOperation->type];
		$store_operation['body']		=	$storeOperation->body;
		$xtpl->assign("store_operation", $store_operation);
		$xtpl->parse("main.store_operation");
	}
}
$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");