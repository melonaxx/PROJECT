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
//设置查询条件
$chaxun   = array();
$chaxun[] = "m.company_id 	  = '".$company_id."'";
$chaxun[] = "m.status_refund != 'N'";
$chaxun[] = "p.payment_return > 0";
if(!empty($_REQUEST['date'])){
	$date = replace_safe($_REQUEST['date']);
	if(!empty($date)){
		$chaxun[] 				= "INSTR(m.action_date,'$date')";
		$main['date']			= $date;
		$page_param				= array();
		$page_param['date']		= replace_safe($_REQUEST['date'], 20, false, false);
	}
}
if(!empty($_REQUEST['is_return']) && $_REQUEST['is_return']=='Y'){
	$is_return = replace_safe($_REQUEST['is_return']);
	if(!empty($is_return)){
		$chaxun[]					= "p.payment_return >0";
		$main['is_return']			= $is_return;
		$page_param					= array();
		$page_param['is_return']	= replace_safe($_REQUEST['is_return'], 20, false, false);
	}
}
if(!empty($_REQUEST['is_return']) && $_REQUEST['is_return']=='N'){
	$is_return = replace_safe($_REQUEST['is_return']);
	if(!empty($is_return)){
		$chaxun[]					= "p.payment_return <= 0";
		$main['is_return']			= $is_return;
		$page_param					= array();
		$page_param['is_return']	= replace_safe($_REQUEST['is_return'], 20, false, false);
	}
}


$where = '';
if(count($chaxun)>0)
{
	$where = "WHERE ".implode(' AND ',$chaxun);
}
// ----  查询数量 ----
$sql = "SELECT COUNT(*) as total FROM purchase_main_info AS m LEFT JOIN finance_purchase AS p ON p.purchase_id = m.id ".$where;
$result	= mysql_query($sql, $_mysql_link_);
$main['total']	= mysql_result($result, 0, 'total');

//---- 处理分页 ----
if(!is_array($page_param))
{
	$page_param			= array();
}
$main['page_info']	= erp_page_info($main['total'], $page, $page_param);
$limit	= ($page - 1) * $_SESSION["_application_info_"]["page_size"].", ".$_SESSION["_application_info_"]["page_size"];

$status_receipt = array('N'=>'未到货','P'=>'部分到货','Y'=>'全部到货');
$status_refund  = array('N'=>'未退货','P'=>'部分退货','Y'=>'全部退货');
$pay_method 	= array('After'=>'后付款','Deposit'=>'订金加尾款','New'=>'先付款');
$status_audit   = array('Y'=>'通过审核','N'=>'待审核','R'=>'待修改','F'=>'已拒绝');

//---- 数量大于0 ----
if($main['total'] > 0)
{
	$sql = "SELECT m.id,m.number,m.total,m.price,m.store_id,m.staff_id,m.brief,m.supplier_id,m.status_receipt,m.status_refund,m.status_audit,m.action_date,m.body,p.pay_method,p.payment_remain FROM purchase_main_info AS m  LEFT JOIN finance_purchase AS p ON m.id = p.purchase_id ".$where." ORDER BY action_date desc LIMIT ".$limit;
	$result = mysql_query($sql,$_mysql_link_);
	$no = 1;
	while($purchase_main = mysql_fetch_object($result)){
		$purchasemain = array();
		$purchasemain['no'] 			= $no++;
		$purchasemain['id'] 			= $purchase_main->id;
		$purchasemain['total'] 			= $purchase_main->total;
		$purchasemain['price']			= $purchase_main->price;
		$purchasemain['number'] 		= $purchase_main->number;
		$purchasemain['brief'] 			= $purchase_main->brief;
		$purchasemain['payment_remain'] = $purchase_main->payment_remain;
		$purchasemain['pay_method']		= $pay_method[$purchase_main->pay_method];
		$purchasemain['status_receipt']	= $status_receipt[$purchase_main->status_receipt];
		$purchasemain['status_refund']	= $status_refund[$purchase_main->status_refund];
		$purchasemain['status_audit']	= $status_audit[$purchase_main->status_audit];
		$purchasemain['action_date']	= substr($purchase_main->action_date,0,16);

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
		$xtpl->assign("purchasemain", $purchasemain);
		$xtpl->parse("main.purchasemain");
	}
}


$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");