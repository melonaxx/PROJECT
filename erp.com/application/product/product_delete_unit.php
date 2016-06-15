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

//---- 查询该属性 是否属于当前公司 ----
// $id		= intval($_REQUEST['id']);
// $sql	= "SELECT name FROM product_parts_name WHERE company_id='".$_SESSION['_application_info_']['company_id']."' AND id='$id'";
// $result	= mysql_query($sql, $_mysql_link_);
// if(mysql_num_rows($result))
// {
// 	//---- 删除该属性 ----
// 	$sql	= "UPDATE product_parts_name SET is_delete='Y' WHERE id='$id'";
// 	mysql_query($sql, $_mysql_link_);
// }

// header("Location: /product/product_unit_setting.php");
$company_id = $_SESSION['_application_info_']['company_id'];
if(!empty($_GET['id']))
{
	$id		= intval($_REQUEST['id']);
}
if($_REQUEST['delete']=='1'){
	//---- 查询该规格值 是否属于当前公司 ----
	$sql	= "SELECT name FROM product_parts_name WHERE company_id='".$_SESSION['_application_info_']['company_id']."' AND id='$id'";
$result	= mysql_query($sql, $_mysql_link_);
	$result	= mysql_query($sql, $_mysql_link_);
	if(mysql_num_rows($result))
	{
	//---- 删除该属性 ----
	$sql	= "UPDATE product_parts_name SET is_delete='Y' WHERE id='$id'";
	mysql_query($sql, $_mysql_link_);

	}
	echo "<script>\n";
	echo "parent.$('#MessageBox').modal('hide');\n";
	echo "parent.location.replace(parent.location.href);";
	echo "</script>\n";
	exit;
}

$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");


