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
if(!empty($_POST['made'])){
	$name = replace_safe($_POST['name']);
	$sql = "INSERT INTO   after_sale_topic  SET company_id = '$company_id',name = '$name',is_delete='N'";
	mysql_query($sql,$_mysql_link_);
	echo "<script>\n";
	echo "parent.$('#MessageBox').modal('hide');\n";
	echo "parent.location.replace(parent.location.href);";
	echo "</script>\n";
	echo "<center><br/><br/><br/><br/>添加完成！<br/><br/><br/><br/></center>";
	exit;
}
if(!empty($_POST['aa'])){
	$value = replace_safe($_POST['aa']);
	$sql = "SELECT name FROM   after_sale_topic  WHERE name = '$value' AND company_id = '$company_id' AND is_delete='N'";
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