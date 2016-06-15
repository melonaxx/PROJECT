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
if(!empty($_REQUEST['date'])){
	$year = substr($_GET['date'],0,4);
	$main['year'] = $year;
	$mouth = substr($_GET['date'],5,7);
	$main['mouth']	= $mouth;
}else{
	$main['year'] 	= date("Y");
	$year = date("Y");
	$mouth = date("m");
	$main['mouth']	= date("m");
}
$date = "$year-$mouth";
$main['date'] = "$year-$mouth";
$status['1'] = '√';
$status['2'] = '●';
$status['3'] = '○';
$status['4'] = '☆';
$status['5'] = '△';
$status['6'] = '×';
$status['7'] = '※';
$status['8'] = '◇';
$status['9'] = '◆';
$status['10'] = '▲';

//查询人数
if(!empty($_POST['department'])){
	$group_id = $_POST['department'];
}

//查询人数的where条件
$addon[] = "company_id = '$company_id'";
$addon[] = "is_valid = 'Y'";

if(!empty($_REQUEST['group_id'])){
	$group_id = intval($_REQUEST['group_id']);
	if(!empty($group_id)){
		$main['group_id'] = $group_id;

	}

	$sql="SELECT id FROM company_staff_group WHERE parent_id= $group_id";
		$result = mysql_query($sql,$_mysql_link_);
		$arr=array();
		while($aaa = mysql_fetch_object($result)){
			$arr[]=$aaa->id;
		}
		if(count($arr)<1){
			$addon[]		= "(group_id = '$group_id')";
		}
		else
		{
			$arr[]=$group_id;
			$addon[]="group_id in (".implode($arr,',').")";
		}
}else{
	$sql = "SELECT max(id) AS maxid FROM company_staff_group WHERE company_id = '$company_id' AND is_valid = 'Y'";
	$result = mysql_query($sql,$_mysql_link_);
	$company_staff_group = mysql_fetch_object($result);
	$group_id = $company_staff_group -> maxid;
	$addon[] = "(group_id ='$group_id')";
}
$where  = "";
if(count($addon) > 0)
{
	$where	= "WHERE ".implode(" AND ", $addon);
}
$sql = "SELECT COUNT(*) AS total FROM company_staff_info $where";
$result = mysql_query($sql,$_mysql_link_);
$total = mysql_fetch_object($result);
$main['total'] = $total->total;
$sql = "SELECT id,nick FROM company_staff_info $where";
$result_staff_info = mysql_query($sql,$_mysql_link_);
while($company_staff_info = mysql_fetch_object($result_staff_info)){
	//查询考勤记录
	$sql = "SELECT action_date, staff_id, am, pm FROM company_attendance WHERE INSTR(action_date, '$date') AND staff_id IN ('{$company_staff_info->id}')";
	$result = mysql_query($sql,$_mysql_link_);
	while($company_attendance = mysql_fetch_object($result)){
		$list_record = array();
		$list_record['day'] 		= intval(substr($company_attendance->action_date, -2));
		if($list_record['day'] == 0){
			continue;
		}
		$list_record['id']			= $company_attendance->id;
		$list_record['nick']		= $company_attendance->nick;
		$list_record['staff_id']	= $company_attendance->staff_id;
		$list_record['am']			= $status[$company_attendance->am];
		$list_record['pm']			= $status[$company_attendance->pm];
		$xtpl->assign("list_record",$list_record);
		$xtpl->parse("main.list_record");
	}
}
$sql = "SELECT id,nick FROM company_staff_info $where";
$result = mysql_query($sql,$_mysql_link_);
while($company = mysql_fetch_object($result)){
	$companystaff_info = array();
	$companystaff_info['id'] 	= $company->id;
	$companystaff_info['nick']	= $company->nick;
	$xtpl->assign("companystaff_info", $companystaff_info);
	$xtpl->parse("main.companystaff_info");
}

$time = strtotime("$year/$mouth/1");
$t = date('t', $time);
$w = date('w', strtotime(date('Y-m-1', $time)));
$week = array('日', '一', '二', '三', '四', '五', '六');
$th = '';
$td = '';
for($i = 1; $i <= $t; $i++) {
	if($w%7==0){
		$w = 0;
	}
	$nink = array();
	$nink['data'] 	= $week[$w];
	$nink['i']		= $i;
	$xtpl->assign("nink",$nink);
	$xtpl->parse("main.nink");
	$w++;
}
//查询部门
$sql = "SELECT id,name FROM company_staff_group WHERE company_id = '$company_id'AND is_valid = 'Y'  ORDER BY id desc";
$result = mysql_query($sql,$_mysql_link_);
while($company_staff = mysql_fetch_object($result)){
	$companystaff = array();
	$companystaff['id']		= $company_staff->id;
	$companystaff['name']	= $company_staff->name;
	$xtpl->assign("companystaff", $companystaff);
	$xtpl->parse("main.companystaff");
}
//查询考勤员
// $sql = "SELECT id,nick FROM company_staff_info WHERE company_id = '$company_id' AND is_valid = 'Y'";
// $result = mysql_query($sql,$_mysql_link_);
// while($company_staff_two = mysql_fetch_object($result)){
	// $companystaffinfo = array();
	// $companystaffinfo['id']		= $company_staff_two->id;
	// $companystaffinfo['nick']	= $company_staff_two->nick;
	// $xtpl->assign("companystaffinfo", $companystaffinfo);
	// $xtpl->parse("main.companystaffinfo");
// }
$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");