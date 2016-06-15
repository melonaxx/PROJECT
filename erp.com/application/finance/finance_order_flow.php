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
//设置查询条件
$chaxun	= array();
$chaxun[] = "f.company_id = '".$company_id."'";
$chaxun[] = "f.payment_status != 'N'";
//判断只有开始时间没有结束时间
if(!empty($_REQUEST['begin_date']) && empty($_REQUEST['end_date'])){
	$begin_date = replace_safe($_REQUEST['begin_date']);
	if(!empty($begin_date)){
		$chaxun[]					= "Date(i.order_date) >= '".$begin_date."'";
		$main['begin_date'] 		= $begin_date;
		$page_param					= array();
		$page_param['begin_date']	= replace_safe($_REQUEST['begin_date'], 20, false, false);
	}
}
//判断只有结束时间没有开始时间
if(empty($_REQUEST['begin_date']) && !empty($_REQUEST['end_date'])){
	$end_date = replace_safe($_REQUEST['end_date']);
	if(!empty($end_date)){
		$chaxun[]				= "Date(i.order_date) <= '".$end_date."'";
		$main['end_date'] 		= $end_date;
		$page_param				= array();
		$page_param['end_date']	= replace_safe($_REQUEST['end_date'], 20, false, false);
	}
}

if(!empty($_REQUEST['begin_date']) && !empty($_REQUEST['end_date'])){
	$begin_date = replace_safe($_REQUEST['begin_date']);
	$end_date	= replace_safe($_REQUEST['end_date']);
	//判断结束时间大与起始时间，从新填写
	if(Date($begin_date) > Date($end_date)){
		header("Content-Type: text/html; charset=UTF-8");
		echo "<script>alert('查询结束时间不能小于开始时间！');window.location.href='/finance/finance_order_flow.php';</script>";
		exit;
	}
	if(Date($begin_date) == Date($end_date)){
		$chaxun[]					= "INSTR(i.order_date ,'$end_date')";
		$main['begin_date'] 		= $begin_date;
		$main['end_date']			= $end_date;
		$page_param					= array();
		$page_param['begin_date']	= replace_safe($_REQUEST['begin_date'], 20, false, false);
		$page_param['end_date']		= replace_safe($_REQUEST['end_date'], 20, false, false);
	}

	if(!empty($begin_date) && !empty($end_date)){
		$chaxun[]					= "Date(i.order_date) >= '".$begin_date."'";
		$chaxun[]					= "Date(i.order_date) <= '".$end_date."'";
		$main['begin_date'] 		= $begin_date;
		$main['end_date']			= $end_date;
		$page_param					= array();
		$page_param['begin_date']	= replace_safe($_REQUEST['begin_date'], 20, false, false);
		$page_param['end_date']		= replace_safe($_REQUEST['end_date'], 20, false, false);
	}
}

$where = "";
if(count($chaxun) > 0)
{
	$where	= "WHERE ".implode(" AND ", $chaxun);
}

//获取银行账户名
$bank = array();
$sql = "SELECT id,name FROM finance_bank WHERE company_id = '$company_id' AND status = 'Y'";
$result = mysql_query($sql,$_mysql_link_);
while($dbRow = mysql_fetch_object($result)){
	$bank[$dbRow->id]	= $dbRow->name;
}
//获取客户名
$crm = array();
$sql = "SELECT crm_user_id,name FROM crm_user_related WHERE company_id = '$company_id'";
$result = mysql_query($sql,$_mysql_link_);
while($dbRow = mysql_fetch_object($result)){
	$crm[$dbRow->crm_user_id]= $dbRow->name;
}
//获取店铺名
$dianpu = array();
$sql = "SELECT id,shop_name FROM user_register_info";
$result = mysql_query($sql,$_mysql_link_);
while($dbRow = mysql_fetch_object($result)){
	$dianpu[$dbRow->id] = $dbRow->shop_name;
}

//---分页---
$sql	= "SELECT COUNT(*) AS total
		FROM finance_order AS f
		LEFT JOIN order_info As i ON f.order_id = i.id
		LEFT JOIN finance_cash_logs AS l ON f.order_id=l.info_id
		LEFT JOIN order_source AS s ON f.order_id = s.id ".$where;
$result	= mysql_query($sql, $_mysql_link_);

$main['total']		= mysql_result($result, 0, 'total');

$page_param			= array();
$main['page_info']	= erp_page_info($main['total'], $page, $page_param);
$limit	= ($page - 1) * $_SESSION["_application_info_"]["page_size"].", ".$_SESSION["_application_info_"]["page_size"];

//查询订单流水
$sql = "SELECT
		i.order_date,
		s.user_id,s.bind_number,s.crm_user_id,
		f.id,f.order_id,f.bank_id,f.theory_amount,
		l.money,l.amount_date
		FROM finance_order AS f
		LEFT JOIN order_info As i ON f.order_id = i.id
		LEFT JOIN finance_cash_logs AS l ON f.order_id=l.info_id
		LEFT JOIN order_source AS s ON f.order_id = s.id ".$where." ORDER BY i.order_date DESC,i.id DESC,l.amount_date DESC LIMIT ".$limit;
		// var_dump($sql);
		// die();
$result = mysql_query($sql,$_mysql_link_);
$no = 1;
while($dbRow = mysql_fetch_object($result)){
	$fin_order 					= array();
	$fin_order['no'] 			= $no++;
	$fin_order['id'] 			= $dbRow->id;
	$fin_order['order_id'] 		= $dbRow->order_id;
	$fin_order['theory_amount'] = $dbRow->theory_amount;			//订单总额
	$fin_order['money'] 		= $dbRow->money;					//实收金额
	$fin_order['amount_date'] 	= $dbRow->amount_date;
	$fin_order['bind_number'] 	= $dbRow->bind_number;				//订单编号
	$fin_order['bank_id'] 		= $bank[$dbRow->bank_id];			//到账账户
	$fin_order['crm_user_id'] 	= $crm[$dbRow->crm_user_id];		//客户
	$fin_order['user_id'] 		= $dianpu[$dbRow->user_id];
	$xtpl->assign('fin_order',$fin_order);
	$xtpl->parse('main.fin_order');
}


$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");