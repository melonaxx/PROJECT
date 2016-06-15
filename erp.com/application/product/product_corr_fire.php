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
	$id     = intval($_GET['id']);
	$sql    = "SELECT COUNT(*) AS total from product_info WHERE company_id='$company_id' AND id='$id' AND is_delete='N'";
	$result = mysql_query($sql,$_mysql_link_);
	$res    = mysql_fetch_object($result);
	if($res->total > 0){
		$sql    = "UPDATE product_info SET is_delete = 'Y' WHERE company_id='$company_id' AND id='$id'";
		mysql_query($sql,$_mysql_link_);
	}
	header("Location: product_correspondence.php");
	exit;
}
$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");
