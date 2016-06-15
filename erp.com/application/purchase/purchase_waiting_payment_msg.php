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

//获取账户名称
$sql = "SELECT id,name FROM finance_bank WHERE company_id='$company_id' ";
$result 	 = mysql_query($sql,$_mysql_link_);
$bankInfo    = array();
while($dbRow = mysql_fetch_object($result))
{
	$bankInfo[$dbRow->id] = $dbRow->name;
}
$type = array('Output'=>'支出单据','Input'=>'收入单据');
if(!empty($_GET['id']))
{
	$id  = intval($_GET['id']);
	$sql = "SELECT c.amount_date,c.type,c.subject,c.money,c.bank_id,c.body,p.payment_already,p.payment_remain,p.payment_return,m.number FROM  finance_cash_logs AS c LEFT JOIN finance_purchase AS p ON c.info_id=p.purchase_id LEFT JOIN purchase_main_info AS m ON m.id=c.info_id WHERE c.company_id='$company_id' AND c.info_type='Purchase' AND c.id='$id' ";
	$result = mysql_query($sql,$_mysql_link_);
	$dbRow  = mysql_fetch_object($result);
	$main['id']					= $id;
	$main['body'] 				= $dbRow->body;
	$main['money'] 				= $dbRow->money;
	$main['number'] 			= $dbRow->number;
	$main['subject'] 			= $dbRow->subject;
	$main['type'] 				= $type[$dbRow->type];
	$main['payment_remain']  	= $dbRow->payment_remain;
	$main['payment_return']  	= $dbRow->payment_return;
	$main['payment_already'] 	= $dbRow->payment_already;
	$main['bank_id'] 			= $bankInfo[$dbRow->bank_id];
	$main['amount_date'] 		= substr($dbRow->amount_date,0,16);
}

//修改备注
if(!empty($_POST['body']))
{
	header("Content-Type: text/html; charset=UTF-8");
	$id = intval($_POST['id']);
	$body = replace_safe($_POST['body']);
	if(!empty($body)){
		$sql="UPDATE finance_cash_logs SET body='$body' WHERE company_id='$company_id' AND info_type='Purchase' AND id='$id' ";
		mysql_query($sql,$_mysql_link_);
	}
	exit;
}
$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");