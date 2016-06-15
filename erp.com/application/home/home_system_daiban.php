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


$company_id   = $_SESSION['_application_info_']["company_id"];
$staff_id     = $_SESSION['_application_info_']["staff_id"];

//接受查询参数
$chaxun   = array();
$where    = '';
$chaxun[] = "company_id = '".$company_id."'";
$chaxun[] = "status <> 'D'";
if(!empty($_POST['date'])){
	$date 		  = replace_safe($_POST['date']);
	$main['date'] = $date;
	$chaxun[] 	  = " INSTR(end_date,'$date')";
}
if(!empty($_POST['status'])){
	$status			= replace_safe($_POST['status']);
	$main['status'] = $status;
	$chaxun[]		= "status = '".$status."'";
}
// if(!empty($_POST['name'])){
// 	$name 		  = replace_safe($_POST['name']);
// 	$main['name'] = $name;
// 	$chaxun[]	  = "INSTR(staff_id,'$name')";
// }
$where	= "WHERE ".implode(" AND ", $chaxun);

//获取发布人名字
$sql 	= "SELECT id,nick FROM company_staff_info WHERE company_id = '{$company_id}'";
$result = mysql_query($sql,$_mysql_link_);
while($dbRow = mysql_fetch_object($result)){
	$fabu[$dbRow->id] = $dbRow->nick;
}

//分页
$sql = "SELECT count(*) AS total FROM company_schedule WHERE company_id = '{$company_id}' AND status <> 'D'";
$result 			= mysql_query($sql,$_mysql_link_);
$main['total']  	= mysql_result($result, 0, 'total');
$page_param			= array();
$main['page_info']	= erp_page_info($main['total'], $page, $page_param);
$limit	= ($page - 1) * $_SESSION["_application_info_"]["page_size"].", ".$_SESSION["_application_info_"]["page_size"];


//获取列表数据
$sql 	= "SELECT id,notice_id,name,body,source,staff_id,end_date,action_date,status FROM company_schedule ".$where;
$result = mysql_query($sql,$_mysql_link_);

$no = 1;
$status = array('Y'=>'完成','N'=>'未完成','D'=>'删除');
while($dbRow = mysql_fetch_object($result)){
	$schedule 				 = array();
	$schedule['id'] 		 = $dbRow->id;
	$schedule['notice_id']   = $dbRow->notice_id;
	$schedule['name']		 = $dbRow->name;
	$schedule['body'] 		 = $dbRow->body;
	$schedule['source'] 	 = $dbRow->source;
	$schedule['staff_id']    = $fabu[$dbRow->staff_id];
	$schedule['end_date'] 	 = $dbRow->end_date;
	$schedule['action_date'] = $dbRow->action_date;
	$schedule['status'] 	 = $status[$dbRow->status];
	$schedule['no'] 		 = $no++;

	$xtpl->assign("schedule", $schedule);
	$xtpl->parse("main.schedule");
}


$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");


