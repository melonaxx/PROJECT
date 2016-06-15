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


//---- 查询该属性值 是否属于当前公司 ----
$id		= intval($_REQUEST['id']);
$sql	= "SELECT attrib_id FROM product_attrib_value WHERE company_id='$company_id' AND id='$id'";
$result	= mysql_query($sql, $_mysql_link_);
if(!empty($_GET['id']))
{
	$id	= intval($_REQUEST['id']);
}
if($_REQUEST['delete']=='1'){
	//---- 查询该属性值 是否属于当前公司 ----
	$sql	= "SELECT attrib_id FROM product_attrib_value WHERE company_id='$company_id' AND id='$id'";
	// var_dump ($sql);
	$result	= mysql_query($sql, $_mysql_link_);
	if(mysql_num_rows($result))
	{
	//---- 属性名ID ----
	$attrib_id	= mysql_result($result, 0, 'attrib_id');
	//---- 删除该属性值 ----
	$sql	= "UPDATE product_attrib_value SET is_delete='Y' WHERE id='$id'";
	mysql_query($sql, $_mysql_link_);
	//---- 删除商品相关属性 ----
	$sql	= "DELETE FROM product_attrib_list WHERE company_id='".$_SESSION['_application_info_']['company_id']."' AND attrib_id='$attrib_id' AND value_id='$id'";
	mysql_query($sql, $_mysql_link_);
	}
	echo "<script>\n";
	echo "parent.$('#MessageBox').modal('hide');\n";
	// echo "parent.location.replace(parent.location.href);";
	echo "parent.$(\"#".$id."\").remove()";
	echo "</script>\n";
	exit;
}
$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");




