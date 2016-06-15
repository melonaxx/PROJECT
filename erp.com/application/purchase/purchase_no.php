<?
//---- UTF8 编码 ----
session_start();
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Content-Type: text/html; charset=UTF-8");
header("X-UA-Compatible: IE=EmulateIE7");

include "../../config.php";
// include "../strsub.php";
include "../../xtpl.php";
include "../detect_permit.php";
include "../function.php";


$company_id = $_SESSION['_application_info_']['company_id'];
if(!empty($_GET['addon'])){
	$main['addon'] = $_GET['addon'];
}
$main_id = array();
if(!empty($_POST['send'])){
	$id = explode(",",$_POST['main_id']);
	for($i=0;$i<count($id);$i++){
		$main_id[] = intval($id[$i]);
		$sql = "UPDATE purchase_main_info SET status_audit = 'F' WHERE company_id = '$company_id' AND id = '{$main_id[$i]}'";
		mysql_query($sql,$_mysql_link_);
	}
	echo "<script>\n";
	echo "parent.$('#MessageBox').modal('hide');\n";
	echo "parent.location.replace(parent.location.href);";
	echo "</script>\n";
	echo "<center><br/><br/><br/><br/>打回完成！<br/><br/><br/><br/></center>";
	exit;
}

$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");