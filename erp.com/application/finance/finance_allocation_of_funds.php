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


//日期
$rq = date("Y-m-d");
$main['rq'] = $rq;

$company_id = $_SESSION['_application_info_']['company_id'];
$staff_id 	= $_SESSION['_application_info_']['staff_id'];

$sql 	= "SELECT id,nick FROM company_staff_info WHERE company_id ='$company_id'";
$result = mysql_query($sql,$_mysql_link_);
while($dbRow = mysql_fetch_object($result)){
	$nick[$dbRow->id] = $dbRow->nick;
}

$sql 	= "SELECT id,name FROM finance_bank WHERE company_id ='$company_id'";
$result = mysql_query($sql,$_mysql_link_);
while($dbRow = mysql_fetch_object($result)){
	$bank[$dbRow->id] = $dbRow->name;
}
//显示账户

$sql = "SELECT id,name FROM finance_bank WHERE company_id='$company_id' AND status='Y'";
$result = mysql_query($sql,$_mysql_link_);
while ($dbRow = mysql_fetch_object($result)) {
	$list_bank 				= array();
	$list_bank1 			= array();
	$list_bank['id'] 		= $dbRow->id;
	$list_bank['name'] 		= $dbRow->name;
	$list_bank1['id'] 		= $dbRow->id;
	$list_bank1['name'] 	= $dbRow->name;
	$xtpl->assign("list_bank", $list_bank);
	$xtpl->parse("main.list_bank");
	$xtpl->assign("list_bank1", $list_bank1);
	$xtpl->parse("main.list_bank1");
}

//查询
$chaxun = array();
$where = "";
$chaxun[] = "company_id = '".$company_id."'";
if(!empty($_REQUEST['input_bank'])){
	$input_bank = replace_safe($_REQUEST['input_bank']);
	if(!empty($input_bank))
	{
		$chaxun[] 				  	= "input_bank_id = '".$input_bank."'";
		$main['input_bank'] 	  	= $input_bank;
		$page_param				  	= array();
		$page_param['input_bank'] 	= replace_safe($_REQUEST['input_bank'], 20, false, false);
	}
}
if(!empty($_REQUEST['output_bank'])){
	$output_bank = replace_safe($_REQUEST['output_bank']);
	if(!empty($output_bank))
	{
		$main['output_bank']  		= $output_bank;
		$chaxun[] 					= "output_bank_id = '".$output_bank."'";
		$page_param					= array();
		$page_param['output_bank'] 	= replace_safe($_REQUEST['output_bank'], 20, false, false);
	}
}
//判断只有开始时间没有结束时间
if(!empty($_REQUEST['begin_date']) && empty($_REQUEST['end_date'])){
	$begin_date = replace_safe($_REQUEST['begin_date']);
	if(!empty($begin_date)){
		$chaxun[]					= "Date(action_date) >= '".$begin_date."'";
		$main['begin_date'] 		= $begin_date;
		$page_param					= array();
		$page_param['begin_date']	= replace_safe($_REQUEST['begin_date'], 20, false, false);
	}
}
//判断只有结束时间没有开始时间
if(empty($_REQUEST['begin_date']) && !empty($_REQUEST['end_date'])){
	$end_date = replace_safe($_REQUEST['end_date']);
	if(!empty($end_date)){
		$chaxun[]					= "Date(action_date) <= '".$end_date."'";
		$main['end_date'] 			= $end_date;
		$page_param					= array();
		$page_param['end_date']		= replace_safe($_REQUEST['end_date'], 20, false, false);
	}
}
if(!empty($_REQUEST['begin_date']) && !empty($_REQUEST['end_date'])){
	$begin_date = replace_safe($_REQUEST['begin_date']);
	$end_date	= replace_safe($_REQUEST['end_date']);
	//判断结束时间大与起始时间，从新填写
	if(Date($begin_date) > Date($end_date)){
		header("Content-Type: text/html; charset=UTF-8");
		echo "<script>alert('查询结束时间不能小于开始时间！');window.location.href='/finance/finance_allocation_of_funds.php';</script>";
		exit;
	}
	if(Date($begin_date) == Date($end_date)){
		$chaxun[]					= "Date(action_date) = '".$end_date."'";
		$main['begin_date'] 		= $begin_date;
		$main['end_date']			= $end_date;
		$page_param					= array();
		$page_param['begin_date']	= replace_safe($_REQUEST['begin_date'], 20, false, false);
		$page_param['end_date']		= replace_safe($_REQUEST['end_date'], 20, false, false);
	}

	if(!empty($begin_date) && !empty($end_date)){
		$chaxun[]					= "Date(action_date) >= '".$begin_date."'";
		$chaxun[]					= "Date(action_date) <= '".$end_date."'";
		$main['begin_date'] 		= $begin_date;
		$main['end_date']			= $end_date;
		$page_param					= array();
		$page_param['begin_date']	= replace_safe($_REQUEST['begin_date'], 20, false, false);
		$page_param['end_date']		= replace_safe($_REQUEST['end_date'], 20, false, false);
	}
}
if(count($chaxun)>0)
{
	$where = "WHERE ".implode(" AND ", $chaxun);
}

//---分页---
$sql	= "SELECT COUNT(*) AS total FROM finance_cash_allocation ".$where;
$result	= mysql_query($sql, $_mysql_link_);
$main['total']		= mysql_result($result, 0, 'total');

$page_param			= array();
$main['page_info']	= erp_page_info($main['total'], $page, $page_param);
$limit	= ($page - 1) * $_SESSION["_application_info_"]["page_size"].", ".$_SESSION["_application_info_"]["page_size"];

//划拨列表
$a = 1;//定义序号
$sql = "SELECT id,action_date,business_date,output_bank_id,input_bank_id,`money`,body,staff_id FROM finance_cash_allocation ".$where." ORDER BY action_date DESC LIMIT ".$limit;
$result = mysql_query($sql,$_mysql_link_);
while($dbRow = mysql_fetch_object($result))
{
	$allocation['no'] 				= $a++;
	$allocation['id'] 				= $dbRow->id;
	$allocation['body'] 			= $dbRow->body;
	$allocation['number'] 			= $dbRow->number;
	$allocation['action_date'] 		= $dbRow->action_date;
	$allocation['business_date'] 	= $dbRow->business_date;
	$allocation['nick'] 			= $nick[$dbRow->staff_id];
	$allocation['input_bank_id'] 	= $bank[$dbRow->input_bank_id];
	$allocation['output_bank_id'] 	= $bank[$dbRow->output_bank_id];
	$allocation['money'] 			= number_format($dbRow->money,2);
	$xtpl->assign('allocation',$allocation);
	$xtpl->parse('main.allocation');
}

$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");
