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

$company_type = array('Others'=>'其他','Custom'=>'客户','Supplier'=>'供应商','Express'=>'快递公司');
$id = replace_safe($_GET['id']);
$sql = "SELECT bank_id,amount_date,business_date,type,subject,`money`,company_type,company_name,body FROM finance_cash_logs WHERE company_id='{$company_id}' AND id='{$id}'";
$result = mysql_query($sql,$_mysql_link_);
while($dbRow = mysql_fetch_object($result))
{
	$running['bank'] = $bank[$dbRow->bank_id];
	$running['amount_date'] = $dbRow->amount_date;
	$running['type'] = $dbRow->type;
	$running['business_date'] = $dbRow->business_date;
	$running['subject'] = $dbRow->subject;
	$running['money'] = $dbRow->money;
	$running['company_type'] = $company_type[$dbRow->company_type];
	$running['company_name'] = $dbRow->company_name;
	$running['body'] = $dbRow->body;
	$xtpl->assign('running',$running);
	$xtpl->parse('main.running');
}
$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");
