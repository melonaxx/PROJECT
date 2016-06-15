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
	$id = intval($_GET['id']);
	$main['id'] = $id;
	$sql = "SELECT name FROM product_brand WHERE company_id = '$company_id' AND id = '$id'";
	$result = mysql_query($sql,$_mysql_link_);
	$product_brand = mysql_fetch_object($result);
	$main['name'] = $product_brand->name;
}
if(!empty($_POST['send'])){
	$id = intval($_POST['id']);
	$name = replace_safe($_POST['name']);
	$sql = "UPDATE product_brand SET name = '$name' WHERE id = '$id' AND company_id = '$company_id'";
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