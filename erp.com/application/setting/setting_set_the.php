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
$sql = "SELECT nick,id FROM company_staff_info WHERE company_id = '$company_id' AND is_valid = 'Y'";
$result = mysql_query($sql,$_mysql_link_);
while($company_staff_info = mysql_fetch_object($result)){
	$companystaffinfo = array();
	$companystaffinfo['name'] 	= $company_staff_info->nick;
	$companystaffinfo['id']	= $company_staff_info->id;
	$xtpl->assign("companystaffinfo",$companystaffinfo);
	$xtpl->parse("main.companystaffinfo");
}

$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");