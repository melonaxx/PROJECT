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


$company_id = $_SESSION['_application_info_']['company_id'];

if(!empty($_GET['id'])){
	$template_id = intval($_GET['id']);

	$sql1 = "DELETE FROM company_deliver_template_info WHERE company_id='$company_id' AND id='$template_id'";
	mysql_query($sql1,$_mysql_link_);

	$sql2 = "DELETE FROM company_deliver_template_position WHERE company_id='$company_id' AND template_id='$template_id'";
	mysql_query($sql2,$_mysql_link_);	
}

header("Location: /setting/setting_deliver_template.php");
exit;