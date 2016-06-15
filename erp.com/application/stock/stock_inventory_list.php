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

//-- 按仓库查询 --
$sql = "SELECT id,name FROM store_info WHERE company_id = '$company_id' AND store_status <> 'Delete'";
$result = mysql_query($sql,$_mysql_link_);
while($store_info = mysql_fetch_object($result)){
	$storeinfo = array();
	$storeinfo['id'] = $store_info->id;
	$storeinfo['name'] = $store_info->name;
	$xtpl->assign("storeinfo", $storeinfo);
	$xtpl->parse("main.storeinfo");
}
//设置查询条件
$addon[] = "l.company_id = '$company_id'";
if(!empty($_REQUEST['store_id'])){
	$store_id = intval($store_id);
	if(!empty($store_id)){
		$addon[] = "l.store_id = $store_id";
		$main['store_id']	= $store_id;
		$page_param		= array();
		$page_param['store_id']		= replace_safe($_REQUEST['store_id'], 20, false, false);
	}
}
// if(!empty($_REQUEST['product_id'])){
// 	$product_id = replace_safe($_REQUEST['product_id'], 20);
// 	if(!empty($product_id)){
// 		$addon[] = "INSTR(i.name,'$product_id')";
// 		$main['product_id']	= $product_id;
// 		$page_param		= array();
// 		$page_param['product_id']		= replace_safe($_REQUEST['product_id'], 20, false, false);
// 	}
// }
if(!empty($_REQUEST['date_state']) && empty($_REQUEST['date_end'])){
	$date_state = $_REQUEST['date_state'];
	if(!empty($date_state)){
		$addon[] = "l.action_date >= '".$date_state."'";
		$main['date_state'] = $date_state;
		$page_param		= array();
		$page_param['date_state']		= intval($_REQUEST['date_state']);
	}
}
if(!empty($_REQUEST['date_end']) && empty($_REQUEST['date_state'])){
	$date_end = $_REQUEST['date_end'];
	if(!empty($date_end)){
		$addon[] = "l.action_date <= '$date_end'";
		$main['date_end'] = $date_end;
		$page_param		= array();
		$page_param['date_end']		= intval($_REQUEST['date_end']);
	}
}
if(!empty($_REQUEST['date_end']) && !empty($_REQUEST['date_state'])){
	$date_state = replace_safe($_REQUEST['date_state']);
	$date_end   = replace_safe($_REQUEST['date_end']);
	if(!empty($date_end) && !empty($date_state)){
		if($date_state > $date_end){
			header("Content-Type: text/html; charset=UTF-8");
			echo "<script>alert('查询结束时间不能小于开始时间');window.location.href='/stock/stock_inventory_list.php'</script>";
			exit;
		}
		if($date_end == $date_state){
			$addon[] = "INSTR(l.action_date,'$date_state')";
			$main['date_state'] = $date_state;
			$main['date_end']   = $date_end;
			$page_param		    = array();
			$page_param['date_state']	= intval($_REQUEST['date_state']);
			// $page_param['date_end']		= intval($_REQUEST['date_end']);
		}else{
			$addon[] = "l.action_date >= '$date_state'";
			$addon[] = "l.action_date <= '$date_end'";
			$main['date_state'] = $date_state;
			$main['date_end']   = $date_end;
			$page_param		    = array();
			$page_param['date_state']	= intval($_REQUEST['date_state']);
			$page_param['date_end']		= intval($_REQUEST['date_end']);
		}

	}
}
$where = "";
if(count($addon) > 0)
{
	$where	= "WHERE ".implode(" AND ", $addon);
}
//查询数量
$sql = "SELECT COUNT(*) AS total FROM store_inventory_list AS l LEFT JOIN product_info AS i ON i.id = l.product_id ".$where;
$result	= mysql_query($sql, $_mysql_link_);
$main['total']		= mysql_result($result, 0, 'total');

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
	//获取规格值
	$sql = "SELECT id,body FROM product_format_value WHERE company_id = '$company_id' ";
	$result = mysql_query($sql,$_mysql_link_);
	$format_value = array();
	while($dbRow = mysql_fetch_object($result)){
		$format_value[$dbRow->id] = $dbRow->body;
	}
	$sql = "SELECT l.id,l.store_id,l.product_id,l.staff_id,l.bill_number,l.total,l.action_date,l.body,i.name,d.value_id_1,d.value_id_2,d.value_id_3,d.value_id_4,d.value_id_5 FROM store_inventory_list AS l LEFT JOIN product_info AS i ON i.id = l.product_id LEFT JOIN product_detail AS d ON l.product_id = d.id ".$where." ORDER BY l.action_date DESC LIMIT ".$limit;
	$result = mysql_query($sql,$_mysql_link_);
	while($store_inventory = mysql_fetch_object($result)){
		$storeinventory = array();
		$storeinventory['id']  			= $store_inventory->id;
		$storeinventory['body']			= $store_inventory->body;
		$storeinventory['name']   		= $store_inventory->name;
		$storeinventory['total']		= $store_inventory->total;
		$storeinventory['product_id']   = $store_inventory->product_id;
		$storeinventory['bill_number']  = $store_inventory->bill_number;
		$storeinventory['action_date']  = $store_inventory->action_date;
		$value_1						= $format_value[$store_inventory->value_id_1];
		$value_2						= $format_value[$store_inventory->value_id_2];
		$value_3						= $format_value[$store_inventory->value_id_3];
		$value_4						= $format_value[$store_inventory->value_id_4];
		$value_5						= $format_value[$store_inventory->value_id_5];
		$format = $value_1.",".$value_2.",".$value_3.",".$value_4.",".$value_5;
		$storeinventory['format']		= rtrim($format,',');
		$sql = "SELECT name FROM store_info WHERE company_id = '$company_id' AND id = '{$store_inventory->store_id}' AND store_status <> 'Delete'";
		$result_info = mysql_query($sql,$_mysql_link_);
		while($storeinfo = mysql_fetch_object($result_info)){
			$storeinventory['store_id'] = $storeinfo->name;
		}
		$product_company = "SELECT nick FROM company_staff_info WHERE company_id = '$company_id' AND is_valid = 'Y' AND id = '{$store_inventory->staff_id}'";
		$result_company = mysql_query($product_company,$_mysql_link_);
		while($companystaff = mysql_fetch_object($result_company)){
			$storeinventory['staff_id'] = $companystaff->nick;
		}
		$xtpl->assign("storeinventory", $storeinventory);
		$xtpl->parse("main.storeinventory");
	}
}

$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");