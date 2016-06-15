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

//查询发货仓库
$sql = "SELECT id,name FROM store_info WHERE company_id = '$company_id' AND store_status <> 'Delete'";
$result = mysql_query($sql,$_mysql_link_);
while($storeInfo = mysql_fetch_object($result)){
	$store_info = array();
	$store_info['id'] = $storeInfo->id;
	$store_info['name'] = $storeInfo->name;
	$xtpl->assign("store_info", $store_info);
	$xtpl->parse("main.store_info");
}
//查询发货仓库
$sql = "SELECT id,name FROM store_info WHERE company_id = '$company_id' AND store_status <> 'Delete'";
$result_nink = mysql_query($sql,$_mysql_link_);
while($storeInfo_nink = mysql_fetch_object($result_nink)){
	$store_info_ware = array();
	$store_info_ware['id'] = $storeInfo_nink->id;
	$store_info_ware['name'] = $storeInfo_nink->name;
	$xtpl->assign("store_info_ware", $store_info_ware);
	$xtpl->parse("main.store_info_ware");
}

//设置查询条件
$addon[] = "m.company_id = '$company_id'";
$addon[] = "m.status = 'Y'";

// if(!empty($_REQUEST['deliverywarehouse'])){
// 	$deliverywarehouse = intval($_REQUEST['deliverywarehouse']);
// 	if(!empty($deliverywarehouse)){
// 		$addon[] = "m.output_store_id = $deliverywarehouse";
// 		$main['deliverywarehouse'] = $deliverywarehouse;
// 		$page_param		= array();
// 		$page_param['deliverywarehouse']		= intval($_REQUEST['deliverywarehouse']);
// 	}
// }
// if(!empty($_REQUEST['warehousereceipt'])){
// 	$warehousereceipt = intval($_REQUEST['warehousereceipt']);
// 	if(!empty($warehousereceipt)){
// 		$addon[] = "m.input_store_id = $warehousereceipt";
// 		$main['warehousereceipt'] = $warehousereceipt;
// 		$page_param		= array();
// 		$page_param['warehousereceipt']		= intval($_REQUEST['warehousereceipt']);
// 	}
// }

if(!empty($_REQUEST['date_start']) && empty($_REQUEST['date_end'])){
	$date_start = $_REQUEST['date_start'];
	if(!empty($date_start)){
		$addon[] = "m.action_date >= '$date_start'";
		$main['date_start'] = $date_start;
		$page_param		= array();
		$page_param['date_start']		= intval($_REQUEST['date_start']);
	}
}
if(!empty($_REQUEST['date_end']) && empty($_REQUEST['date_start'])){
	$date_end = $_REQUEST['date_end'];
	if(!empty($date_end)){
		$addon[] = "m.action_date <= '$date_end'";
		$main['date_end'] = $date_end;
		$page_param		= array();
		$page_param['date_end']		= intval($_REQUEST['date_end']);
	}
}
if(!empty($_REQUEST['date_end']) && !empty($_REQUEST['date_start'])){
	header("Content-Type: text/html; charset=UTF-8");
	$date_end = replace_safe($_REQUEST['date_end']);
	$date_start = replace_safe($_REQUEST['date_start']);
	if($date_start > $date_end){
		echo "<script>alert('查询结束时间不能小于开始时间！');window.location.href='/stock/stock_allocation_list.php';</script>";
		exit;
	}
	if(!empty($date_end) && !empty($date_start)){
		$addon[] = "m.action_date >= '$date_start'";
		$addon[] = "m.action_date <= '$date_end'";
		$main['date_start'] = $date_start;
		$main['date_end'] = $date_end;
		$page_param		= array();
		$page_param['date_start']	= intval($_REQUEST['date_start'],20,false,false);
		$page_param['date_end']		= intval($_REQUEST['date_end'],20,false,false);
	}
}
// if(!empty($_REQUEST['product'])){
// 	$product = replace_safe($_REQUEST['product'],20);
// 	if(!empty($product)){
// 		$addon[] = "INSTR(i.name,'$product')";
// 		$main['product'] = $product;
// 		$page_param		= array();
// 		$page_param['product']		= replace_safe($_REQUEST['product'], 20, false, false);
// 	}
// }
$where  = "";
if(count($addon) > 0)
{
	$where	= "WHERE ".implode(" AND ", $addon);
}

// --- 查询数量 ---
$sql = "SELECT COUNT(*) AS total FROM store_move_logs AS m LEFT JOIN product_info AS i ON i.id = m.product_id $where";
$result = mysql_query($sql,$_mysql_link_);
$main['total'] = mysql_result($result,0,'total');
//---- 处理分页 ----
if(!is_array($page_param))
{
	$page_param			= array();
}
$main['page_info']	= erp_page_info($main['total'], $page, $page_param);
$limit	= ($page - 1) * $_SESSION["_application_info_"]["page_size"].", ".$_SESSION["_application_info_"]["page_size"];
//---- 数量大于0 ----
if($main['total'] > 0)
{
	$sql = "SELECT m.id,m.action_date,m.output_store_id,m.input_store_id,m.output_staff_id,m.body,m.total FROM store_move_logs AS m LEFT JOIN product_info AS i ON m.product_id = i.id $where ORDER BY id DESC LIMIT ".$limit;
	$result = mysql_query($sql,$_mysql_link_);
	while($store_move = mysql_fetch_object($result)){
		$storemove = array();
		$storemove['id']				= $store_move->id;
		$storemove['total'] 			= $store_move->total;
		$storemove['body']				= $store_move->body;
		$storemove['action_date'] 		= $store_move->action_date;
		$sql = "SELECT name FROM store_info WHERE id = '{$store_move->output_store_id}' AND store_status <> 'Delete'";
		$result_store = mysql_query($sql,$_mysql_link_);
		while($store_info = mysql_fetch_object($result_store)){
			$storemove['output_store_id'] = $store_info->name;
		}
		$sql = "SELECT name FROM store_info WHERE id = '{$store_move->input_store_id}' AND store_status <> 'Delete'";
		$result_store_info = mysql_query($sql,$_mysql_link_);
		while($store_info_ware = mysql_fetch_object($result_store_info)){
			$storemove['input_store_id'] = $store_info_ware->name;
		}
		$sql = "SELECT nick FROM company_staff_info WHERE id = '{$store_move->output_staff_id}' AND is_valid = 'Y'";
		$result_staff = mysql_query($sql,$_mysql_link_);
		while($company_staff = mysql_fetch_object($result_staff)){
			$storemove['output_staff_id'] = $company_staff->nick;
		}
		$xtpl->assign("storemove", $storemove);
		$xtpl->parse("main.storemove");
	}
}
$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");

