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
//---- 删除单条数据 ----

$store_id	= intval($_REQUEST['store_id']);
$location	= intval($_REQUEST['location_id']);
$product_id	= intval($_REQUEST['product_id']);


if($_POST['delete'] == 1)
{

	//删除
	$sql = "DELETE FROM store_related WHERE company_id='$company_id' AND store_id='$store_id' AND location_id='$location_id' AND product_id='$product_id' ";
	mysql_query($sql, $_mysql_link_);
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

