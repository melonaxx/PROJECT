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

$key = $_POST['nick'];
if(!empty($key)){
	$sql = "SELECT i.id,i.group_id,i.nick,i.number,i.is_valid,g.namei,i.number FROM company_staff_info as i LEFT JOIN company_staff_group as g ON g.id=i.group_id WHERE i.is_valid='Y' AND i.company_id='$company_id' AND i.nick LIKE '%$key%'";
	$result = mysql_query($sql,$_mysql_link_);
	$ii = 0;
	while($row = mysql_fetch_object($result)){
		$ii++;
		$manager_list = array();
		
		$manager_list['ii'] = $ii;
		$manager_list['id'] = $row->id;
		$manager_list['number'] = $row->number;
		$manager_list['nick'] = $row->nick;
		$manager_list['name'] = $row->name;
		$xtpl->assign("manager_list", $manager_list);
		$xtpl->parse("main.manager_list");
	}
}else{
	$sql = "SELECT i.id,i.group_id,i.nick,i.number,i.is_valid,g.name,i.number FROM company_staff_info as i LEFT JOIN company_staff_group as g ON g.id=i.group_id WHERE i.is_valid='Y' AND i.company_id='$company_id'";
	$result = mysql_query($sql,$_mysql_link_);
	$ii = 0;
	while($row = mysql_fetch_object($result)){
		$ii++;
		$manager_list = array();
		
		$manager_list['ii'] = $ii;
		$manager_list['id'] = $row->id;
		$manager_list['number'] = $row->number;
		$manager_list['nick'] = $row->nick;
		$manager_list['name'] = $row->name;
		$xtpl->assign("manager_list", $manager_list);
		$xtpl->parse("main.manager_list");
	}
}

$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");