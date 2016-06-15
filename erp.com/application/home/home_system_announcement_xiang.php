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

//判断是否有点击的通知，有则显示点击通知信息，没有则获取最新通知内容
if(!empty($_GET['id'])){
	$id = intval($_GET['id']);
	$sql = "SELECT id,name,nick,begin_date,end_date,body,person,sign,action_date FROM company_notice_info WHERE company_id = '{$company_id}' AND id = '{$id}'";
	$result = mysql_query($sql,$_mysql_link_);
	while($dbRow = mysql_fetch_object($result)){
		$notice 			   = array();
		$notice['id'] 		   = $dbRow->id;
		$notice['name'] 	   = $dbRow->name;
		$notice['nick'] 	   = $dbRow->nick;
		$notice['begin_date']  = substr($dbRow->begin_date,0,16);
		$notice['end_date']    = substr($dbRow->end_date,0,16);
		$notice['body'] 	   = $dbRow->body;
		$notice['person'] 	   = $dbRow->person;
		$notice['sign'] 	   = $dbRow->sign;
		$notice['action_date'] = substr($dbRow->action_date,0,16);
		$xtpl->assign("notice", $notice);
		$xtpl->parse("main.notice");
	}
}

$notice_id = intval($notice['id']);
//从通知事项表获取对应事项
$sql = "SELECT id,body FROM company_notice_item WHERE company_id = '{$company_id}' AND notice_id = '{$notice_id}'";

$result = mysql_query($sql,$_mysql_link_);
while($dbRow = mysql_fetch_object($result)){
	$shixiang = array();
	$shixiang['id'] = $dbRow->id;
	$shixiang['body'] = $dbRow->body;
	$xtpl->assign("shixiang", $shixiang);
	$xtpl->parse("main.shixiang");
}



$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");