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

$staff_info['staff_id'] = $_SESSION['_application_info_']['staff_id'];
$staff_info['staff_nick']= $_SESSION['_application_info_']['nick'];

$sql = "SELECT name FROM company_staff_info WHERE company_id='$company_id' AND id='{$staff_info['staff_id']}'";
$result = mysql_query($sql,$_mysql_link_);
if(mysql_num_rows($result) > 0){
	$staff_info['staff_name'] = mysql_result($result,0,0);
}

$sql1 = "SELECT name FROM company_name WHERE id='$company_id'";
$result1 = mysql_query($sql1,$_mysql_link_);
if(mysql_num_rows($result1) > 0){
	$staff_info['company_name'] = mysql_result($result1,0,0);
}

$xtpl->assign("staff_info",$staff_info);
$xtpl->parse("main.staff_info");

$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");
