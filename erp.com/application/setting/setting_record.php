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

$company_id = $_SESSION['_application_info_']['company_id'];
$condition[] = "s.company_id = '$company_id'";
$condition[] = "s.is_valid = 'Y'";
if(!empty($_REQUEST['name'])){
	$name = replace_safe($_REQUEST['name']);
	if(!empty($name)){
		$condition[] 			= "INSTR(s.nick,'$name')";
		$main['name']			= $name;	 
		$page_param				= array();
		$page_param['name']		= replace_safe($_REQUEST['name']);
	}
}

if(!empty($group_id)){
	$group_id = intval($_REQUEST['group_id']);
	if(!empty($group_id)){
		$condition[]		= "(group_id = '$group_id')";
		$main['group_id'] = $group_id;
	}
}else{
	$sql = "SELECT max(id) AS maxid FROM company_staff_group WHERE company_id = '$company_id' AND is_valid = 'Y'";
	$result = mysql_query($sql,$_mysql_link_);
	$company_staff_group = mysql_fetch_object($result);
	$group_id = $company_staff_group -> maxid;
	$condition[] = "(group_id = '$group_id')";
}
if(count($condition) > 0)
{
	$where_two	= "WHERE ".implode(" AND ", $condition);
}
//查询数量
$sql = "SELECT COUNT(*) AS total FROM company_staff_info AS s $where_two";
$result	= mysql_query($sql, $_mysql_link_);
$main['total']		= mysql_result($result, 0, 'total');

//---- 处理分页 ----
if(!is_array($page_param)) 
{
	$page_param			= array();
}
$main['page_info']	= erp_page_info($main['total'], $page, $page_param);
$limit	= ($page - 1) * $_SESSION["_application_info_"]["page_size"].", ".$_SESSION["_application_info_"]["page_size"];

//---- 数量大于0 ----
if($main['total'] > 0)
{
	$sql = "SELECT s.id,s.nick FROM company_staff_info AS s  $where_two LIMIT ".$limit;
	$result = mysql_query($sql,$_mysql_link_);
	while($company_staff_info = mysql_fetch_object($result)){
		$list_staff = array();
		$list_staff['id'] 			= $company_staff_info->id;
		$list_staff['name']			= $company_staff_info->nick;
		$xtpl->assign("list_staff", $list_staff);
		$xtpl->parse("main.list_staff");
	}
}
$status['1'] = '出勤';
$status['2'] = '休假';
$status['3'] = '事假';
$status['4'] = '病假';
$status['5'] = '外地出差';
$status['6'] = '旷工';
$status['7'] = '迟到';
$status['8'] = '早退';
$status['9'] = '中途脱岗';
$status['10']= '市内出差';
//水
$addon[] = "a.company_id = '$company_id'";
if(!empty($_REQUEST['action_date'])){
	$action_date = replace_safe($_REQUEST['action_date']);
	if(!empty($action_date)){
		
		$addon[] = "INSTR(a.action_date,'$action_date')";
	}
}else{
	$year = date("Y");
	$month = date("m");
	$action_date = "$year-$month";
	$addon[] = "INSTR(a.action_date,'$action_date')";
}
$main['action_date'] = $action_date;
if(!empty($_REQUEST['group_id'])){
	$group_id = intval($_REQUEST['group_id']);
	$sql = "SELECT id FROM company_staff_info WHERE company_id = '$company_id' AND group_id = '$group_id'";
	$result = mysql_query($sql,$_mysql_link_);
	$array = array();
	while($company_staff_info = mysql_fetch_object($result)){
		$array[] = $company_staff_info->id;
	}
		foreach($array as $key => $value){
			$bb .= $value.",";
		}
		$cc = rtrim($bb,",");
		if(!empty($cc)){
			$addon[] = "a.staff_id IN ($cc)";
		}
}
$where  = "";
if(!empty($_REQUEST['name'])){
	$name = replace_safe($_REQUEST['name']);
	if(!empty($name)){
		$addon[] = "s.nick = '$name'";
	}
}
if(count($addon) > 0)
{
	$where	= "WHERE ".implode(" AND ", $addon);
}
$sql = "SELECT a.staff_id,a.am,a.pm FROM company_attendance AS a LEFT JOIN company_staff_info AS s ON s.id = a.staff_id $where";
$result = mysql_query($sql,$_mysql_link_);
$attend_ance = array();
while($attendance = mysql_fetch_object($result)){
	$attend_ance['staff_id']		= $attendance->staff_id;
	$attend_ance['am_id'] 			= $attendance->am;
	$attend_ance['pm_id'] 			= $attendance->pm;
	$attend_ance['am_text']			= $status[$attendance->am];
	$attend_ance['pm_text']			= $status[$attendance->pm];
	$xtpl->assign("attend_ance", $attend_ance);
	$xtpl->parse("main.attend_ance");	
}
//查询部门
$sql = "SELECT id,name FROM company_staff_group WHERE company_id = '$company_id' AND is_valid = 'Y' ORDER BY id DESC";
$result = mysql_query($sql,$_mysql_link_);
while($company_staff_group = mysql_fetch_object($result)){
	$companystaffgroup = array();
	$companystaffgroup['id'] 	= $company_staff_group->id;
	$companystaffgroup['name']	= $company_staff_group->name;
	$xtpl->assign("companystaffgroup", $companystaffgroup);
	$xtpl->parse("main.companystaffgroup");
}
$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");