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

//---- 公司的员工部门分组 ----
$GroupInfo	= array();
$sql		= "SELECT id, name FROM company_staff_group WHERE company_id='".$_SESSION['_application_info_']["company_id"]."'";
$result		= mysql_query($sql, $_mysql_link_);
while($dbRow = mysql_fetch_object($result))
{
	$GroupInfo[$dbRow->id]	= $dbRow->name;
}
//---- 公司的员工数量 ----
$sql	= "SELECT COUNT(id) AS c FROM company_staff_info WHERE company_id='".$_SESSION['_application_info_']["company_id"]."'";
$result	= mysql_query($sql, $_mysql_link_);
$main['total']		= mysql_result($result, 0, 'c');

//---- 处理分页 ----
$page_param			= array();
$main['page_info']	= erp_page_info($main['total'], $page, $page_param);
$limit	= ($page - 1) * $_SESSION["_application_info_"]["page_size"].", ".$_SESSION["_application_info_"]["page_size"];

if($main['total'] > 0)
{
	//---- 公司的员工数量大于0 ----
	$no			= $start + 1;
	$sql		= "SELECT id, group_id, role_id, is_admin, name, passwd, mobile, number, nick, register_date, ip, is_valid, body FROM company_staff_info WHERE company_id='".$_SESSION['_application_info_']["company_id"]."' LIMIT ".$limit;
	$result		= mysql_query($sql, $_mysql_link_);
	while($dbRow = mysql_fetch_object($result))
	{
		$list_person	= array();
		$list_person['no']			= $no;
		$list_person['group_name']	= $GroupInfo[$dbRow->group_id];
		$list_person['mobile']		= $dbRow->mobile;
		$list_person['number']		= $dbRow->number;
		$list_person['nick']		= $dbRow->nick;
		$list_person['text']		= $dbRow->body;
		$list_person['ip']			= $dbRow->ip;
		if($dbRow->register_date != '0000-00-00 00:00:00')
		{
			$list_person['date']	= $dbRow->register_date;
		}
		$list_person['id']			= $dbRow->id;
		$list_person['admin']		= $dbRow->is_admin;
		$list_person['valid']		= $dbRow->is_valid;
		$list_person['name']		= $dbRow->name;
		$xtpl->assign("list_person", $list_person);
		$xtpl->parse("main.list_person");
		$no++;
	}
}

$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");
