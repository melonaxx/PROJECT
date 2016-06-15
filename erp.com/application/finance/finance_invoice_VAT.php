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

if(!empty($_GET)){
	$id = intval($_GET['id']);

	//获取员工姓名
	$sql = "SELECT id,name FROM company_staff_info WHERE company_id='$company_id' ";
	$result = mysql_query($sql,$_mysql_link_);
	$staff  = array();
	while($dbRow = mysql_fetch_object($result)){
		$staff[$dbRow->id] = $dbRow->name;
	}
	$type = array('Normal'=>'普通发票','VAT'=>'增值税发票');
	$sql = "SELECT l.number,l.action_date,l.staff_id,r.tax_type,r.tax_title,r.tax_text,r.tax_bank_name,r.tax_number,r.tax_bank_number FROM finance_tax_logs AS l LEFT JOIN order_receiver AS r ON l.order_id=r.id WHERE l.company_id='$company_id' AND l.order_id = '$id' ";
	$result  = mysql_query($sql,$_mysql_link_);
	$dbRow   = mysql_fetch_object($result);
	$billing = array();
	$billing['number'] 			= $dbRow->number;
	$billing['tax_text'] 		= $dbRow->tax_text;
	$billing['tax_title'] 		= $dbRow->tax_title;
	$billing['tax_number'] 		= $dbRow->tax_number;
	$billing['action_date'] 	= $dbRow->action_date;
	$billing['tax_bank_name'] 	= $dbRow->tax_bank_name;
	$billing['tax_bank_number'] = $dbRow->tax_bank_number;
	$billing['tax_type'] 		= $type[$dbRow->tax_type];
	$billing['staff_id'] 		= $staff[$dbRow->staff_id];

	$xtpl->assign('billing',$billing);
	$xtpl->parse('main.billing');
}


$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");

