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
$staff_id = $_SESSION['_application_info_']['staff_id'];

if(!empty($_GET)){
	$id = intval($_GET['id']);
}

if(!empty($_POST))
{
	header("Content-Type: text/html; charset=UTF-8");
	$invoice_number = intval($_POST['invoice_number']);
	$date = date('Y-m-d');
	$sql = "INSERT INTO finance_tax_logs SET company_id='$company_id',order_id='$id',`number`='$invoice_number',action_date='$date',staff_id='$staff_id' ";
	mysql_query($sql,$_mysql_link_);
	$sql = "UPDATE order_receiver SET tax_status='Y' WHERE id='$id' ";
	mysql_query($sql,$_mysql_link_);
	
	echo "<script>\n";
	echo "parent.$('#MessageBox').modal('hide');\n";
	echo "parent.location.replace(parent.location.href);";
	echo "</script>\n";
	echo "<center><br/><br/><br/><br/>删除完成！<br/><br/><br/><br/></center>";
	exit;
}

$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");

