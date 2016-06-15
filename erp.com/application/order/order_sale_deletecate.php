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

	$company_id=$_SESSION['_application_info_']['company_id'];
	if(!empty($id)){
		$id = intval($_GET['id']);
		$sql = "UPDATE after_sale_topic SET is_delete='Y' WHERE id='$id' AND company_id='$company_id'";
		mysql_query($sql,$_mysql_link_);
		header("Location: order_sale_cate.php");
		exit;
	}
	
$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");