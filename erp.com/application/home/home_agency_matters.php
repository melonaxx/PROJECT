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

//设置查询条件
$chaxun = array();
$chaxun[] = "company_id = '".$company_id."'";
if(!empty($_GET['date'])){
	$date = replace_safe($_GET['date']);
	if(!empty($date)){
		$chaxun[] = "INSTR(action_date,'$date')";
		$main['date'] = $date;
		$page_param		= array();
		$page_param['date']		= replace_safe($_GET['date'], 20, false, false);
	}
}
$where = '';
if(count($chaxun)>0){
	$where = "WHERE ".implode(' AND ',$chaxun);
}

//分页
$sql = "SELECT count(*) AS total FROM company_notice_info ".$where;
$result 			= mysql_query($sql,$_mysql_link_);
$main['total']  	= mysql_result($result, 0, 'total');
$page_param			= array();
$main['page_info']	= erp_page_info($main['total'], $page, $page_param);
$limit	= ($page - 1) * $_SESSION["_application_info_"]["page_size"].", ".$_SESSION["_application_info_"]["page_size"];


//获取最新通知内容
$no = 1;
$sql = "SELECT id,name,nick,begin_date,end_date,body,person,sign,action_date FROM company_notice_info ".$where." ORDER BY action_date DESC LIMIT ".$limit;
$result = mysql_query($sql,$_mysql_link_);
while($dbRow = mysql_fetch_object($result)){
	$notice = array();
	$notice['id'] 			= $dbRow->id;
	$notice['name'] 		= $dbRow->name;
	$notice['nick'] 		= $dbRow->nick;
	$notice['begin_date']   = substr($dbRow->begin_date,0,16);
	$notice['end_date'] 	= substr($dbRow->end_date,0,16);
	$notice['body'] 		= $dbRow->body;
	$notice['person'] 		= $dbRow->person;
	$notice['sign'] 		= $dbRow->sign;
	$notice['action_date']  = substr($dbRow->action_date,0,16);
	$notice['no'] 			= $no++;
	$xtpl->assign("notice", $notice);
	$xtpl->parse("main.notice");
}



$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");