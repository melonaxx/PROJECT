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
$id		= intval($_REQUEST['id']);
$sql	= "SELECT id,location_type FROM store_location WHERE id='$id' AND company_id='$company_id'";
$result	= mysql_query($sql, $_mysql_link_);
$type = mysql_fetch_object($result);
if($type->location_type == 'Area')
{
	$main['type'] = '库区';
}else if($type->location_type=='Shelves')
{
	$main['type'] = '货架';
}else if($type->location_type=='Location')
{
	$main['type'] = '货位';
}
if(mysql_num_rows($result) < 1)
{
	echo "<html>\n";
	echo "<head>\n";
	echo "<link type='text/css' rel='stylesheet' href='/style/bootstrap.min.css?ver=".$main['doc_version']."'/>\n";
	echo "</head>\n";
	echo "<body>\n";
	echo "该库位不存在<br/><br/>\n";
	echo "<input type='button' class='btn btn-default btn-sm cancel' value='确定' onclick=\"parent.$('#MessageBox').modal('hide')\" />\n";
	echo "</body>\n";
	echo "</html>\n";
	exit;
}

$main['id']	= $id;

if($_POST['delete'] == 1)
{
	$sql  	= "SELECT count(id) AS total FROM store_location WHERE company_id = '$company_id' AND parent_id = '$id' ";
	$result = mysql_query($sql,$_mysql_link_);
	$total  = mysql_result($result,0,'total');
	if($total >0 ){
		header("Content-Type: text/html; charset=UTF-8");
		echo "<script>\n";
		echo "parent.$('#MessageBox').modal('hide');\n";
		echo "</script>\n";
		echo "<script>alert('设有子库位，不能删除！');</script>";
		echo "<center><br/><br/><br/><br/>删除完成！<br/><br/><br/><br/></center>";
		exit;
	}
	$sql	= "UPDATE store_location SET is_delete='Y' WHERE id='$id'";
	mysql_query($sql, $_mysql_link_);
	echo "<script>\n";
	echo "parent.$('#MessageBox').modal('hide');\n";
	echo "parent.$('#L".$id."').parent().remove();";
	echo "</script>\n";
	echo "<center><br/><br/><br/><br/>删除完成！<br/><br/><br/><br/></center>";
	exit;
}

$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");

