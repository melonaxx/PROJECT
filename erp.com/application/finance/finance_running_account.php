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
$rq = date('Y-m-d');
$main['rq'] = $rq;

$chaxun = array();
$chaxun[] = "company_id = '".$company_id."'";
if(!empty($_REQUEST['type'])){
	$type = replace_safe($_REQUEST['type']);
	if(!empty($_REQUEST['type'])){
		$main['type'] 			= $type;
		$chaxun['type'] 		= "type = '".$type."'";
		$page_param				= array();
		$page_param['type']		= replace_safe($_REQUEST['type'], 20, false, false);
	}
}
if(!empty($_REQUEST['name'])){
	$name = intval($_REQUEST['name']);
	if(!empty($name)){
		$main['name'] 			= $name;
		$chaxun[] 				= "bank_id = '".$name."'";
		$page_param				= array();
		$page_param['name']		= replace_safe($_REQUEST['name'], 20, false, false);
	}
}

//判断只有开始时间没有结束时间
if(!empty($_REQUEST['begin_date']) && empty($_REQUEST['end_date'])){
	$begin_date = replace_safe($_REQUEST['begin_date']);
	if(!empty($begin_date)){
		$chaxun[]					= "Date(amount_date) >= '".$begin_date."'";
		$main['begin_date'] 		= $begin_date;
		$page_param					= array();
		$page_param['begin_date']	= replace_safe($_REQUEST['begin_date'], 20, false, false);
	}
}

//判断只有结束时间没有开始时间
if(empty($_REQUEST['begin_date']) && !empty($_REQUEST['end_date'])){
	$end_date = replace_safe($_REQUEST['end_date']);
	if(!empty($end_date)){
		$chaxun[]					= "Date(amount_date) <= '".$end_date."'";
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
		echo "<script>alert('查询结束时间不能小于开始时间！');window.location.href='/finance/finance_running_account.php';</script>";
		exit;
	}

	if(Date($begin_date) == Date($end_date)){
		$chaxun[]					= "INSTR(amount_date ,'$end_date')";
		$main['begin_date'] 		= $begin_date;
		$main['end_date']			= $end_date;
		$page_param					= array();
		$page_param['begin_date']	= replace_safe($_REQUEST['begin_date'], 20, false, false);
		$page_param['end_date']		= replace_safe($_REQUEST['end_date'], 20, false, false);
	}

	if(!empty($begin_date) && !empty($end_date)){
		$chaxun[]					= "Date(amount_date) >= '".$begin_date."'";
		$chaxun[]					= "Date(amount_date) <= '".$end_date."'";
		$main['begin_date'] 		= $begin_date;
		$main['end_date']			= $end_date;
		$page_param					= array();
		$page_param['begin_date']	= replace_safe($_REQUEST['begin_date'], 20, false, false);
		$page_param['end_date']		= replace_safe($_REQUEST['end_date'], 20, false, false);
	}
}
$where = '';
if(count($chaxun)>0)
{
	$where = "WHERE ".implode(" AND ", $chaxun);
}

//显示账户
$bank = array();
$sql  = "SELECT id,name,`number`,is_default FROM finance_bank WHERE company_id='$company_id' AND status='Y'";
$result = mysql_query($sql,$_mysql_link_);
while ($dbRow = mysql_fetch_object($result))
{
	$list_bank 					= array();
	$list_bank['id'] 			= $dbRow->id;
	$list_bank['name'] 			= $dbRow->name;
	$list_bank['unmber'] 		= $dbRow->number;
	$list_bank['is_default'] 	= $dbRow->is_default;
	$bank[$dbRow->id] 			= $dbRow->name;

	$xtpl->assign("list_bank", $list_bank);
	$xtpl->parse("main.list_bank");
}

//---分页---
$sql	= "SELECT COUNT(*) AS total FROM finance_cash_logs ".$where;
$result	= mysql_query($sql, $_mysql_link_);

$main['total']		= mysql_result($result, 0, 'total');

$page_param			= array();
$main['page_info']	= erp_page_info($main['total'], $page, $page_param);
$limit	= ($page - 1) * $_SESSION["_application_info_"]["page_size"].", ".$_SESSION["_application_info_"]["page_size"];

//资金流水列表
$danju['Input']     = "收入单据";
$danju['Output']	= "支出单据";
$a = 1;//定义序号
$sql = "SELECT id,amount_date,business_date,type,subject,`money`,bank_id FROM finance_cash_logs ".$where." ORDER BY amount_date DESC LIMIT ".$limit;
$result = mysql_query($sql,$_mysql_link_);
while($dbRow = mysql_fetch_object($result))
{
	$run 					= array();
	$run['no'] 				= $a++;
	$run['id'] 				= $dbRow->id;
	$run['money'] 			= $dbRow->money;
	$run['subject'] 		= $dbRow->subject;
	$run['amount_date'] 	= $dbRow->amount_date;
	$run['type'] 			= $danju[$dbRow->type];
	$run['business_date'] 	= $dbRow->business_date;
	$run['bank_id'] 		= $bank[$dbRow->bank_id];
	$xtpl->assign('run',$run);
	$xtpl->parse('main.run');

}


$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");
