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


if(!empty($_GET['id']))
{
	$id	= intval($_REQUEST['id']);
}
if($_REQUEST['delete']=='1'){
	//---- 查询该规格值 是否属于当前公司 ----
	$sql	= "SELECT format_id FROM product_format_value WHERE company_id='$company_id' AND id='$id'";
	$result	= mysql_query($sql, $_mysql_link_);
	if(mysql_num_rows($result))
	{
		//---- 规格名ID ----
		$format_id	= mysql_result($result, 0, 'format_id');
		// ---- 删除该规格值 ----
		$sql	= "UPDATE product_format_value SET is_delete='Y' WHERE id='$id'";
		mysql_query($sql, $_mysql_link_);
	}
	echo "<script>\n";
	echo "parent.$('#MessageBox').modal('hide');\n";
	echo "parent.$(\"#".$id."\").remove()";
	echo "</script>\n";
	exit;
}

$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");

