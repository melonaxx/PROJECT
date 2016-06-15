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

$company_id   = $_SESSION['_application_info_']["company_id"];
$staff_id     = $_SESSION['_application_info_']["staff_id"];

$id = replace_safe($_GET['notice_id']);
$sql = "SELECT name,body,nick,begin_date,end_date,person,sign FROM company_notice_info WHERE company_id ='{$company_id}' AND id = '{$id}'";

$result = mysql_query($sql,$_mysql_link_);
$notice = array();
while($dbRow = mysql_fetch_object($result))
{
	$notice['name'] = $dbRow->name;
	$notice['body'] = $dbRow->body;
	$notice['nick'] = $dbRow->nick;
	$notice['begin_date'] = $dbRow->begin_date;
	$notice['end_date'] = $dbRow->end_date;
	$notice['person'] = $dbRow->person;
	$notice['sign'] = $dbRow->sign;

	$xtpl -> assign('notice',$notice);
	$xtpl -> parse('main.notice');
}

$sql = "SELECT body FROM company_notice_item WHERE company_id = '{$company_id}' AND notice_id ='{$id}'";
$result = mysql_query($sql,$_mysql_link_);
$not = array();
while($res = mysql_fetch_object($result))
{
	$not['body'] = $res->body;
	$xtpl->assign('not',$not);
	$xtpl->parse('main.not');
}




$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");


