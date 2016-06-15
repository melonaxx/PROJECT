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


$chaxun[] = "m.company_id = '".$company_id."'";
$chaxun[] = "(m.status_audit = 'Y')";
$chaxun[] = "(m.status_refund != 'Y')";

if(!empty($_REQUEST['date'])){
	$date 				 = replace_safe($_REQUEST['date']);
	$chaxun[] 			 = "m.action_date = '".$date."'";
	$main['date'] 	 	 = $date;
	$page_param			 = array();
	$page_param['date']  = replace_safe($_REQUEST['date'], 20, false, false);
}
if(!empty($_REQUEST['staff'])){
	$staff = replace_safe($_REQUEST['staff']);
	if(!empty($staff)){
		$chaxun[] 			 = "INSTR(s.nick,'$staff')";
		$main['staff']		 = $staff;
		$page_param			 = array();
		$page_param['staff'] = replace_safe($_REQUEST['staff'], 20, false, false);
	}
}

$where = "";
if(count($chaxun) > 0)
{
	$where	= "WHERE ".implode(" AND ", $chaxun);
}


//分页
$sql = "SELECT COUNT(*) AS total FROM purchase_main_info AS m LEFT JOIN company_staff_info AS s ON s.id = m.staff_id ".$where;
$result	= mysql_query($sql, $_mysql_link_);
$main['total']		= mysql_result($result, 0, 'total');

//---- 处理分页 ----
if(!is_array($page_param))
{
	$page_param			= array();
}
$main['page_info']	= erp_page_info($main['total'], $page, $page_param);
$limit	= ($page - 1) * $_SESSION["_application_info_"]["page_size"].", ".$_SESSION["_application_info_"]["page_size"];

$no  = 1;
$sql = "SELECT s.nick,m.id,m.number,m.total,m.price,m.brief,m.store_id,m.staff_id,m.supplier_id,m.status_receipt,m.status_audit,m.action_date,m.body FROM purchase_main_info AS m LEFT JOIN company_staff_info AS s ON s.id = m.staff_id ".$where." ORDER BY m.action_date DESC LIMIT ".$limit;
$result = mysql_query($sql,$_mysql_link_);
while($dbRow = mysql_fetch_object($result)){
	$purchase 					= array();
	$purchase['no']				= $no++;
	$purchase['id']				= $dbRow->id;
	$purchase['staff_id'] 		= $dbRow->nick;
	$purchase['body']			= $dbRow->body;
	$purchase['total']			= $dbRow->total;
	$purchase['price']			= $dbRow->price;
	$purchase['brief']			= $dbRow->brief;
	$purchase['number']			= $dbRow->number;
	$purchase['action_date']	= $dbRow->action_date;
	$purchase['status_audit']	= $dbRow->status_audit;
	$purchase['status_receipt']	= $dbRow->status_receipt;

	$sql = "SELECT name FROM store_info WHERE company_id = '$company_id' AND store_status <> 'Delete' AND id = '{$dbRow->store_id}'";
		$result_store = mysql_query($sql,$_mysql_link_);
		while($store_info = mysql_fetch_object($result_store)){
			$purchase['store_id']	= $store_info->name;
		}
	$sql = "SELECT name FROM purchase_supplier WHERE is_delete = 'N' AND company_id = '$company_id' AND id = '{$dbRow->supplier_id}'";
		$result_supplier = mysql_query($sql,$_mysql_link_);
		while($supplier = mysql_fetch_object($result_supplier)){
			$purchase['supplier_id'] = $supplier->name;
		}

	$xtpl->assign('purchase',$purchase);
	$xtpl->parse('main.purchase');
}



$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");

