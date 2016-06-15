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

$chaxun[] = "i.company_id = '".$company_id."'";
if(!empty($_REQUEST['date'])){
	$date 				 = replace_safe($_REQUEST['date']);
	if(!empty($date)){
		$chaxun[] 			 = "INSTR(i.action_date,'$date')";
		$main['date'] 	 	 = $date;
		$page_param			 = array();
		$page_param['date']  = replace_safe($_REQUEST['date'], 20, false, false);
	}
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
if(!empty($_REQUEST['type'])){
	$type = replace_safe($_REQUEST['type']);
	if(!empty($type)){
		$chaxun[]			= "i.store_type = '".$type."'";
		$main['type']		= $type;
		$page_param			= array();
		$page_param['type']= replace_safe($_REQUEST['type'], 20, false, false);
	}
}
if(!empty($_GET['purchase_id']))
{
	$purchase_id 			= intval($_GET['purchase_id']);
	if(!empty($purchase_id))
	{
		$chaxun[] 			= "i.purchase_id='".$purchase_id."'";
		$main['purchase_id']		= $purchase_id;
		$page_param			= array();
		$page_param['purchase_id']= replace_safe($_REQUEST['purchase_id'], 20, false, false);
	}
}

$where = "";
if(count($chaxun) > 0)
{
	$where	= "WHERE ".implode(" AND ", $chaxun);
}

//分页
$sql = "SELECT COUNT(*) AS total FROM store_input_info AS i LEFT JOIN company_staff_info AS s ON s.id = i.input_staff_id ".$where;
$result			= mysql_query($sql, $_mysql_link_);
$main['total']	= mysql_result($result, 0, 'total');

//---- 处理分页 ----
if(!is_array($page_param))
{
	$page_param			= array();
}
$main['page_info']	= erp_page_info($main['total'], $page, $page_param);
$limit	= ($page - 1) * $_SESSION["_application_info_"]["page_size"].", ".$_SESSION["_application_info_"]["page_size"];

$store_type = array('Input'=>'入库单据','Output'=>'出库单据');

$sql = "SELECT s.nick,i.id,i.purchase_id,i.supplier_id,i.store_id,i.number,i.total,i.price,i.store_type,i.input_staff_id,i.action_date FROM store_input_info AS i LEFT JOIN company_staff_info AS s ON s.id = i.input_staff_id ".$where." ORDER BY i.action_date DESC LIMIT ".$limit;
$result = mysql_query($sql,$_mysql_link_);
$no = 1;
while($dbRow = mysql_fetch_object($result)){
	$store = array();
	$store['no']				= $no++;
	$store['id']				= $dbRow->id;
	$store['input_staff_id']    = $dbRow->nick;
	$store['number']			= $dbRow->number;
	$store['total']				= $dbRow->total;
	$store['price']				= $dbRow->price;
	$store['action_date']		= $dbRow->action_date;
	$store['store_type']		= $store_type[$dbRow->store_type];
	// $sql = "SELECT `number` FROM purchase_main_info WHERE company_id='$company_id' AND id='{$dbRow->purchase_id}' ";
	// var_dump($sql);exit;
	// $res 					= mysql_query($sql,$_mysql_link_);
	// $purchase 				= mysql_fetch_object($res);
	$store['purchase_id'] 	= $dbRow->purchase_id;

	$sql = "SELECT name FROM store_info WHERE company_id = '$company_id' AND store_status <> 'Delete' AND id = '{$dbRow->store_id}'";
	$result_store 		= mysql_query($sql,$_mysql_link_);
	$store_info 		= mysql_fetch_object($result_store);
	$store['store_id']	= $store_info->name;

	$sql = "SELECT name FROM purchase_supplier WHERE is_delete = 'N' AND company_id = '$company_id' AND id = '{$dbRow->supplier_id}'";
	$result_supplier 		= mysql_query($sql,$_mysql_link_);
	$supplier 				= mysql_fetch_object($result_supplier);
	$store['supplier_id'] 	= $supplier->name;

	$xtpl -> assign('store',$store);
	$xtpl -> parse('main.store');
}


$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");
