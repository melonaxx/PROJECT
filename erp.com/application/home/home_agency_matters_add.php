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

if(!empty($_POST)){
	$name		= replace_safe($_POST['name']);
	$body		= replace_safe($_POST['body']);
	$person		= replace_safe($_POST['person']);
	$end_date	= replace_safe($_POST['end_date']);

	$sql 	= "INSERT INTO company_schedule SET company_id = '{$company_id}', name = '{$name}', body = '{$body}', person = '{$person}', source = 'Person', end_date = '{$end_date}', status = 'N', action_date = NOW(), staff_id = '{$staff_id}'";
	mysql_query($sql,$_mysql_link_);

	echo "<script>\n";
	echo "parent.$('#MessageBox').modal('hide');\n";
	echo "parent.location.replace(parent.location.href);";
	echo "</script>\n";
	echo "<center><br/><br/><br/><br/>添加完成！<br/><br/><br/><br/></center>";
	exit;
}




$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");


