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
if(!empty($_GET['id'])){
	$main['id'] = $_GET['id'];
}
$sql = "SELECT total,cost FROM store_related WHERE company_id = '$company_id' AND id = '{$_GET['id']}'";
$result = mysql_query($sql,$_mysql_link_);
$store_related = mysql_fetch_object($result);
$main['total'] = $store_related->total;
$main['cost']  = $store_related->cost;
if(!empty($_POST['send'])){
	$related_id = intval($_POST['related_id']);
	$cost = intval($_POST['cost']);
	$total = intval($_POST['total']);
	$sql = "UPDATE store_related SET cost = $cost,total = $total WHERE id = $related_id AND company_id = '$company_id'";
	mysql_query($sql,$_mysql_link_);
	echo "<script>\n";
	echo "parent.$('#MessageBox').modal('hide');\n";
	echo "parent.location.replace(parent.location.href);";
	echo "</script>\n";
	echo "<center><br/><br/><br/><br/>修改完成！<br/><br/><br/><br/></center>";
	exit;
}
$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");