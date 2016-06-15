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

$sql = "SELECT id,name FROM finance_bank WHERE company_id='$company_id'";
$result = mysql_query($sql,$_mysql_link_);
while($dbRow = mysql_fetch_object($result))
{
	$bank[$dbRow->id] = $dbRow->name;
}

$id = $_GET['id'];
$sql = "SELECT business_date,output_bank_id,input_bank_id,`money`,body FROM finance_cash_allocation WHERE company_id='{$company_id}' AND id='{$id}'";
$result = mysql_query($sql,$_mysql_link_);
while($dbRow = mysql_fetch_object($result))
{
	$allocation['business_date'] = $dbRow->business_date;
	$allocation['output_bank_id'] = $bank[$dbRow->output_bank_id];
	$allocation['input_bank_id'] = $bank[$dbRow->input_bank_id];
	$allocation['money'] = $dbRow->money;
	$allocation['body'] = $dbRow->body;
	$xtpl->assign('allocation',$allocation);
	$xtpl->parse('main.allocation');
}
$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");
