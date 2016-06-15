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



//分页
$sql	= "SELECT COUNT(*) AS total FROM finance_spending_topic WHERE company_id='".$_SESSION['_application_info_']['company_id']."' AND parent_id=0 AND status = 'Y'";

$result	= mysql_query($sql, $_mysql_link_);

$main['total']		= mysql_result($result, 0, 'total');

$page_param			= array();
$main['page_info']	= erp_page_info($main['total'], $page, $page_param);
$limit	= ($page - 1) * $_SESSION["_application_info_"]["page_size"].", ".$_SESSION["_application_info_"]["page_size"];

$GroupList	= array();
$GroupInfo	= array();
$no	= 1;//定义序号变量
$sql = "SELECT id, name, parent_id, sort, level FROM finance_spending_topic WHERE company_id='".$_SESSION['_application_info_']["company_id"]."' AND status='Y' LIMIT ".$limit;
$result	= mysql_query($sql, $_mysql_link_);
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
	$xtpl->assign("list_group", $list_group);
	$xtpl->parse("main.list_group");
	$no++;
}
// var_dump($list_group);die;
$main['total']	= $no - 1;

$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");
