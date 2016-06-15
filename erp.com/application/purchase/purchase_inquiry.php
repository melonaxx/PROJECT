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
		$addon[] = "INSTR(i.nick,'$staff')";
		$main['staff']	= $staff;
		$page_param		= array();
		$page_param['staff']		= replace_safe($_REQUEST['staff'], 20, false, false);
	}
}
if(!empty($_REQUEST['status_refund'])){
	$status_refund = replace_safe($_REQUEST['status_refund']);
	if(!empty($status_refund)){
		$addon[]			= "m.status_refund = '".$status_refund."'";
		$main['status_refund']		= $status_refund;
		$page_param			= array();
		$page_param['status_refund']= replace_safe($_REQUEST['status_refund'], 20, false, false);
	}
}
if(!empty($_REQUEST['status_audit'])){
	$status_audit = replace_safe($_REQUEST['status_audit']);
	if(!empty($status_audit)){
		$addon[]			= "m.status_audit = '".$status_audit."'";
		$main['status_audit']		= $status_audit;
		$page_param			= array();
		$page_param['status_audit']= replace_safe($_REQUEST['status_audit'], 20, false, false);
	}
}
if(!empty($_REQUEST['status_receipt'])){
	$status_receipt = replace_safe($_REQUEST['status_receipt']);
	if(!empty($status_receipt)){
		$addon[]			= "m.status_receipt = '".$status_receipt."'";
		$main['status_receipt']		= $status_receipt;
		$page_param			= array();
		$page_param['status_receipt']= replace_safe($_REQUEST['status_receipt'], 20, false, false);
	}
}
$where = "";
if(count($addon) > 0){
	$where = "WHERE ".implode(" AND ",$addon);
}
// ----  查询数量 ----
$sql = "SELECT COUNT(*) as total FROM purchase_main_info AS m LEFT JOIN company_staff_info AS i ON i.id = m.staff_id $where";
$result	= mysql_query($sql, $_mysql_link_);
$main['total']		= mysql_result($result, 0, 'total');

//---- 处理分页 ----
if(!is_array($page_param))
{
	$page_param			= array();
}
$main['page_info']	= erp_page_info($main['total'], $page, $page_param);
$limit	= ($page - 1) * $_SESSION["_application_info_"]["page_size"].", ".$_SESSION["_application_info_"]["page_size"];

$status_receipt = array('N'=>'未到货','P'=>'部分到货','Y'=>'全部到货');
$status_refund = array('N'=>'未退货','P'=>'部分退货','Y'=>'全部退货');
$status_audit = array('Y'=>'通过审核','N'=>'待审核','R'=>'待修改','F'=>'已拒绝');

//---- 数量大于0 ----
if($main['total'] > 0)
{
	$sql = "SELECT m.id,m.number,m.total,m.price,m.store_id,m.staff_id,m.brief,m.supplier_id,m.status_receipt,m.status_refund,m.status_audit,m.action_date,m.body FROM purchase_main_info AS m  LEFT JOIN company_staff_info AS i ON i.id = m.staff_id ".$where." ORDER BY action_date desc LIMIT ".$limit;
	$result = mysql_query($sql,$_mysql_link_);
	while($purchase_main = mysql_fetch_object($result)){
		$purchasemain = array();
		$purchasemain['id'] 		= $purchase_main->id;
		$purchasemain['number'] 	= $purchase_main->number;
		$purchasemain['total'] 		= $purchase_main->total;
		$purchasemain['price']		= $purchase_main->price;

		//查询仓库
		$sql = "SELECT name FROM store_info WHERE store_status <> 'Delete' AND company_id = '$company_id' AND id = '{$purchase_main->store_id}'";
		$result_store = mysql_query($sql,$_mysql_link_);
		while($store_info = mysql_fetch_object($result_store)){
			$purchasemain['store_id']= $store_info->name;
		}
		//查询操作人
		$sql = "SELECT nick FROM company_staff_info WHERE is_valid <> 'D' AND company_id = '$company_id' AND id = '{$purchase_main->staff_id}'";
		$result_staff = mysql_query($sql,$_mysql_link_);
		while($company_staff = mysql_fetch_object($result_staff)){
			$purchasemain['staff_id']= $company_staff->nick;
		}
		//查询供应商
		$sql = "SELECT name FROM purchase_supplier WHERE is_delete = 'N' AND company_id = '$company_id' AND id = '{$purchase_main->supplier_id}'";
		$result_purchase = mysql_query($sql,$_mysql_link_);
		while($purchase_supplier = mysql_fetch_object($result_purchase)){
			$purchasemain['supplier_id']= $purchase_supplier->name;
		}
		$purchasemain['brief'] 				= $purchase_main->brief;
		$purchasemain['status_receipt']		= $status_receipt[$purchase_main->status_receipt];
		$purchasemain['status_refund']		= $status_refund[$purchase_main->status_refund];
		$purchasemain['status_audit']		= $status_audit[$purchase_main->status_audit];
		$purchasemain['action_date']		= substr($purchase_main->action_date,0,16);

		$xtpl->assign("purchasemain", $purchasemain);
		$xtpl->parse("main.purchasemain");
	}
}

$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");