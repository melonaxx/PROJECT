<?
//---- UTF8 编码 ----
session_start();
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Content-Type: application/xml; charset=UTF-8");
header("X-UA-Compatible: IE=EmulateIE7");

include "../../config.php";
include "../../xtpl.php";
include "../detect_permit.php";
include "../function.php";

//---- 只有公司创建者，才可以进行人员管理 ----
if($_SESSION['_application_info_']["admin_id"] == 0 || $_SESSION['_application_info_']["company_id"] == 0)
{
	$main['permit']	= 0;
	$xtpl->assign("main", $main);
	$xtpl->parse("main");
	$xtpl->out("main");
	exit;
}

$GroupList	= array();
$GroupInfo	= array();
$no			= 1;
$sql		= "SELECT id, name, parent_id, level, is_valid, body FROM company_staff_group WHERE company_id='".$_SESSION['_application_info_']["company_id"]."'";
$result		= mysql_query($sql, $_mysql_link_);
while($dbRow = mysql_fetch_object($result))
{
	$GroupList[$dbRow->parent_id][$dbRow->id]	= $dbRow->name;
	$GroupInfo[$dbRow->id]	= $dbRow;
}

$GroupList	= get_sort_by_array($GroupList);

foreach($GroupList as $ix => $dat)
{
	$gid	= $dat['id'];
	$list_group	= array();
	$list_group['no']		= $no;
	$list_group['id']		= $dat['id'];
	$list_group['name']		= str_repeat("　", $dat['level'] - 1).$dat['name'];
	$list_group['text']		= $GroupInfo[$gid]->body;
	$list_group['valid']	= $GroupInfo[$gid]->is_valid;
	$xtpl->assign("list_group", $list_group);
	$xtpl->parse("main.list_group");
	$no++;
}
$main['total']	= $no - 1;

$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");
