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

$StoreLocation	= array();
$StoreLocation['Area']		= "库区";
$StoreLocation['Shelves']	= "货架";
$StoreLocation['Location']	= "货位";
$company_id		= $_SESSION['_application_info_']['company_id'];

//---- 当前库区/货架/货位信息 ----
$id		= intval($_REQUEST['id']);
$sql	= "SELECT store_id, location_type, name, body FROM store_location WHERE company_id='$company_id' AND id='$id'";
$result	= mysql_query($sql, $_mysql_link_);
if(mysql_num_rows($result) < 1)
{
	illegal_operation();
}
$LocationInfo	= mysql_fetch_object($result);

$main['store_name']		= "";
$main['location_name']	= $StoreLocation[$LocationInfo->location_type];
$main['location_type']	= $LocationInfo->location_type;
$main['name']			= $LocationInfo->name;
$main['body']			= $LocationInfo->body;
$main['id']				= $id;

if(!empty($_POST['name']))
{
	$name	= replace_safe($_POST['name']);
	$body	= replace_safe($_POST['body']);

	$sql = "UPDATE store_location SET name='$name', body='$body' WHERE id='$id'";
	mysql_query($sql, $_mysql_link_);

	echo "<script>\n";
	echo "parent.$('#MessageBox').modal('hide');\n";
	echo "parent.location.replace(parent.location.href);";
	echo "</script>\n";
	echo "<center><br/><br/><br/><br/>修改完成！<br/><br/><br/><br/></center>";
	exit;
}
//---- 所属仓库 ----
$sql	= "SELECT name FROM store_info WHERE company_id='$company_id' AND id='$LocationInfo->store_id'";
$result	= mysql_query($sql, $_mysql_link_);
if(mysql_num_rows($result))
{
	$main['store_name']	= mysql_result($result, 0, 'name');
}

$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");
