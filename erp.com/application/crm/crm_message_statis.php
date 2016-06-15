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

$company_id = $_SESSION['_application_info_']['company_id'];
//查看短信剩余条数
$sql = "SELECT message_remain FROM company_name WHERE id='$company_id' ";
$res = mysql_query($sql,$_mysql_link_);
if($res)
{
	$message_remain         = mysql_result($res,0,0);
	$main['message_remain'] = $message_remain;
}


$chaxun = array();
$chaxun[] = "company_id = '".$company_id."'";
//判断只有开始时间没有结束时间
if(!empty($_REQUEST['date_start']) && empty($_REQUEST['date_end'])){
	$date_start = replace_safe($_REQUEST['date_start']);
	if(!empty($date_start)){
		$chaxun[] = "Date(action_date) >= '".$date_start."'";
		$main['date_start'] 		= $date_start;
		$page_param					= array();
		$page_param['date_start']	= replace_safe($_REQUEST['date_start'], 20, false, false);
	}
}
//判断只有结束时间没有开始时间
if(empty($_REQUEST['date_start']) && !empty($_REQUEST['date_end'])){
	$date_end = replace_safe($_REQUEST['date_end']);
	if(!empty($date_end)){
		$chaxun[] = "Date(action_date) <= '".$date_end."'";
		$main['date_end'] 		= $date_end;
		$page_param					= array();
		$page_param['date_end']	= replace_safe($_REQUEST['date_end'], 20, false, false);
	}
}
if(!empty($_REQUEST['date_start']) && !empty($_REQUEST['date_end'])){
	$date_start = replace_safe($_REQUEST['date_start']);
	$date_end	= replace_safe($_REQUEST['date_end']);
	//判断结束时间大与起始时间，从新填写
	if(Date($date_start) > Date($date_end)){
		header("Content-Type: text/html; charset=UTF-8");
		echo "<script>alert('查询结束时间不能小于开始时间！');window.location.href='/crm/crm_message_statis.php';</script>";
		exit;
	}
	if(Date($date_start) == Date($date_end)){
		$chaxun[]				= "Date(action_date) = '".$date_end."'";
		$main['date_start'] 	= $date_start;
		$main['date_end']		= $date_end;
		$page_param				= array();
		$page_param['date_start']		= replace_safe($_REQUEST['date_start'], 20, false, false);
		$page_param['date_end']		= replace_safe($_REQUEST['date_end'], 20, false, false);
	}

	if(!empty($date_start) && !empty($date_end)){
		$chaxun[]				= "Date(action_date) >= '".$date_start."'";
		$chaxun[]				= "Date(action_date) <= '".$date_end."'";
		$main['date_start'] 	= $date_start;
		$main['date_end']		= $date_end;
		$page_param				= array();
		$page_param['date_start']		= replace_safe($_REQUEST['date_start'], 20, false, false);
		$page_param['date_end']		= replace_safe($_REQUEST['date_end'], 20, false, false);
	}
}

$where = '';
if(count($chaxun) > 0){
	$where = "WHERE ".implode(' AND ', $chaxun);
}

//---分页---
$sql    = "SELECT COUNT(*) AS total FROM crm_message_count ".$where;
$result = mysql_query($sql, $_mysql_link_);
$main['total']      = mysql_result($result, 0, 'total');
//处理分页
$page_param         = array();
$main['page_info']  = erp_page_info($main['total'], $page, $page_param);
$limit  = ($page - 1) * $_SESSION["_application_info_"]["page_size"].", ".$_SESSION["_application_info_"]["page_size"];
//获取店铺名称
$sql1 = "SELECT id,shop_name FROM user_register_info ";

$res1 = mysql_query($sql1,$_mysql_link_);
$shop = array();
while($dbRow = mysql_fetch_object($res1)){
	$shop[$dbRow->id] = $dbRow->shop_name;
}

$sql = "SELECT id,shop_id,action_date,total FROM crm_message_count ".$where." LIMIT ".$limit;
$result = mysql_query($sql,$_mysql_link_);
$no = 1;
while($dbRow = mysql_fetch_object($result)){
	$number = array();
	$number['no'] 			= $no++;
	$number['id'] 			= $dbRow->id;
	$number['total'] 		= $dbRow->total;
	$number['shop_id'] 		= $shop[$dbRow->shop_id];
	$number['action_date'] 	= $dbRow->action_date;

	$xtpl->assign('number',$number);
	$xtpl->parse('main.number');
}



$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");