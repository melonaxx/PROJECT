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

$addon[] = "m.company_id = '$company_id'";
$addon[] = "(m.status_audit = 'N' OR m.status_audit = 'R')";
if(!empty($_REQUEST['application_date'])){
	$application_date = replace_safe($_REQUEST['application_date']);
	if(!empty($application_date)){
		$addon[] = "INSTR(m.action_date,'$application_date')";
		$main['application_date']	= $application_date;
		$page_param		= array();
		$page_param['application_date']		= replace_safe($_REQUEST['application_date'], 20, false, false);
	}
}
if(!empty($_REQUEST['staff'])){
	$staff = replace_safe($_REQUEST['staff']);
	if(!empty($staff)){
		$addon[] 				= "INSTR(s.nick,'$staff')";
		$main['staff']			= $staff;
		$page_param				= array();
		$page_param['staff']	= replace_safe($_REQUEST['staff'], 20, false, false);
	}
}
$where = "";
if(count($addon) > 0)
{
	$where	= "WHERE ".implode(" AND ", $addon);
}
//分页
$sql = "SELECT COUNT(*) AS total FROM purchase_main_info AS m ".$where;
$result	= mysql_query($sql, $_mysql_link_);
$main['total']		= mysql_result($result, 0, 'total');

//---- 处理分页 ----
if(!is_array($page_param))
{
	$page_param			= array();
}
$main['page_info']	= erp_page_info($main['total'], $page, $page_param);
$limit	= ($page - 1) * $_SESSION["_application_info_"]["page_size"].", ".$_SESSION["_application_info_"]["page_size"];

$purchase_status['N'] 	= "待审核";
$purchase_status['R'] 	= "待修改";
if($main['total'] > 0)
{
	$sql = "SELECT s.nick,m.store_id,m.id,m.number,m.total,m.price,m.staff_id,m.brief,m.status_audit,m.action_date,m.supplier_id FROM purchase_main_info AS m LEFT JOIN company_staff_info AS s ON s.id = m.staff_id ".$where." ORDER BY m.action_date desc LIMIT ".$limit;
	$result = mysql_query($sql,$_mysql_link_);
	while($purchase = mysql_fetch_object($result)){
		$purchase_main = array();
		$purchase_main['id']			= $purchase->id;
		$purchase_main['staff_id'] 		= $purchase->nick;
		$purchase_main['total']			= $purchase->total;
		$purchase_main['brief']			= $purchase->brief;
		$purchase_main['price']			= $purchase->price;
		$purchase_main['number']		= $purchase->number;
		$purchase_main['action_date']	= $purchase->action_date;
		$purchase_main['status_audit'] 	= $purchase_status[$purchase->status_audit];

		$sql = "SELECT name FROM store_info WHERE company_id = '$company_id' AND store_status <> 'Delete' AND id = '{$purchase->store_id}'";
		$result_store = mysql_query($sql,$_mysql_link_);
		while($store_info = mysql_fetch_object($result_store)){
			$purchase_main['store_id']	= $store_info->name;
		}
		$sql = "SELECT name FROM purchase_supplier WHERE is_delete = 'N' AND company_id = '$company_id' AND id = '{$purchase->supplier_id}'";
		$result_supplier = mysql_query($sql,$_mysql_link_);
		while($supplier = mysql_fetch_object($result_supplier)){
			$purchase_main['supplier_id'] = $supplier->name;
		}
		$xtpl->assign("purchase_main", $purchase_main);
		$xtpl->parse("main.purchase_main");
	}
}
$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");
