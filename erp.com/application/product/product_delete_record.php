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
if(!empty($_GET['addon'])){
	$main['id'] = $_GET['addon'];
}
if(!empty($_POST['send'])){
	$num = explode(",",$_POST['id']);
	$addon = array();
	for($i=0;$i<count($num);$i++){
		$addon[] = intval($num[$i]);	
	}
	for($i=0;$i<count($addon);$i++){
	$sql = "UPDATE product_info  SET is_delete = 'Y' WHERE company_id = '$company_id' AND id = '{$addon[$i]}' AND have_combination = 'Y'";
		mysql_query($sql,$_mysql_link_);
		if(mysql_affected_rows($_mysql_link_) == 1){
			$sql_combination = "DELETE FROM product_combination WHERE product_id = '{$addon[$i]}'";
			$sql_detail = "DELETE FROM product_detail WHERE id = '{$addon[$i]}'";
			mysql_query($sql_combination,$_mysql_link_);
			mysql_query($sql_detail,$_mysql_link_);
		}
	}
	echo "<script>\n";
	echo "parent.$('#MessageBox').modal('hide');\n";
	echo "parent.location.replace(parent.location.href);";
	echo "</script>\n";
	echo "<center><br/><br/><br/><br/>删除完成！<br/><br/><br/><br/></center>";
	exit;
}
	
$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");