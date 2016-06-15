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

$rq = date('Y-m-d H:i:s');
$main['rq'] = $rq;

$company_id   = $_SESSION['_application_info_']["company_id"];
$staff_id     = $_SESSION['_application_info_']["staff_id"];

$id  = replace_safe($_GET['id']);
$sql = "SELECT name,body,end_date,person FROM company_schedule WHERE company_id ='{$company_id}' AND id = '{$id}' ";
$result = mysql_query($sql,$_mysql_link_);
$notice = array();
while($dbRow = mysql_fetch_object($result))
{
	$schedule['name'] 	  = $dbRow->name;
	$schedule['body'] 	  = $dbRow->body;
	$schedule['end_date'] = $dbRow->end_date;
	$schedule['person']   = $dbRow->person;

	$xtpl -> assign('schedule',$schedule);
	$xtpl -> parse('main.schedule');
}


$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");


