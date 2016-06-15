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
if(!empty($_GET['id'])){
	$id = intval($_GET['id']);
	$sql = "DELETE FROM  company_sales WHERE id = '$id' AND company_id='$company_id'";
	mysql_query($sql,$_mysql_link_);
	header('Location: /crm/crm_company_sales.php');
	exit;
}

$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");