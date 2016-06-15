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
$main['id']	=	$_GET['id'];
$company_id = $_SESSION['_application_info_']['company_id'];
if(!empty($_GET['id'])){
	$id = $_GET['id'];
	$sql = "SELECT name FROM product_format_name WHERE id = '$id'";
	$result = mysql_query($sql,$_mysql_link_);
	$array = mysql_fetch_object($result);
	$main['name'] = $array->name;
}
if(!empty($_POST['made'])){
	$id = intval($_POST['id']);
	$name = $_POST['name'];
	$sql = "UPDATE product_format_name SET name = '$name' WHERE id = '$id'";
	mysql_query($sql,$_mysql_link_);
	echo "<script>\n";
	echo "parent.$('#MessageBox').modal('hide');\n";
	echo "parent.location.replace(parent.location.href);";
	echo "</script>\n";
	echo "<center><br/><br/><br/><br/>修改完成！<br/><br/><br/><br/></center>";
	exit;
}
if(!empty($_POST['aa'])){
	$value = $_POST['aa'];
	$sql = "SELECT name FROM product_format_name WHERE name = '$value' AND company_id = '$company_id'";
	$result = mysql_query($sql,$_mysql_link_);
	if(mysql_num_rows($result) == 0){
		echo json_encode("ok");
	}else{
		echo json_encode("no");
	}
	exit;
}
$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");