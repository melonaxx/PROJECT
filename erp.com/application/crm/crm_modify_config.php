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

if(!empty($_GET)){
	$id = intval($_GET['id']);
}
$sql = "SELECT id,strategy_status,strategy,type FROM crm_message_template WHERE company_id = '$company_id' AND id = '$id' ";
$result = mysql_query($sql,$_mysql_link_);
$dbRow  = mysql_fetch_object($result);
$res 					=	array();
$res['id']				= $dbRow->id;
$res['type']			= $dbRow->type;
$strategy 				= $dbRow->strategy;
$arr 					= explode('-', $strategy);
$res['strat']			= $arr[0];
$res['money']			= $arr[1];
$res['strategy_status']	= $dbRow->strategy_status;

$xtpl->assign('res',$res);
$xtpl->parse('main.res');

if(!empty($_POST)){
	$type 				= replace_safe($_POST['type']);
	$money 				= replace_safe($_POST['money']);
	$strat 				= replace_safe($_POST['strat']);
	$strategy 			= $strat.'-'.$money;
	$strategy_status 	= replace_safe($_POST['strategy_status']);

	$sql = "UPDATE crm_message_template SET type = '$type', strategy_status='$strategy_status', strategy='$strategy' WHERE company_id='$company_id' AND id='$id' ";
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