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
$chaxun[] = "i.company_id = '".$company_id."'";
// $chaxun[] = "i.type = 'Output'";
// $chaxun[] = "f.payment_status != 'N'";
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

//获取店铺名
$dianpu = array();
$sql = "SELECT id,shop_name FROM user_register_info";
$result = mysql_query($sql,$_mysql_link_);
while($dbRow = mysql_fetch_object($result)){
	$dianpu[$dbRow->id] = $dbRow->shop_name;
}

//---分页---
$sql = "SELECT COUNT(*) AS total FROM finance_refund AS i ".$where."AND i.status='Y'";
$result	= mysql_query($sql, $_mysql_link_);

$main['total']		= mysql_result($result, 0, 'total');

$page_param			= array();
$main['page_info']	= erp_page_info($main['total'], $page, $page_param);
$limit	= ($page - 1) * $_SESSION["_application_info_"]["page_size"].", ".$_SESSION["_application_info_"]["page_size"];

//查询订单流水
$tsql = "SELECT i.payment,i.order_id,i.refund,i.action_date,i.status,i.bank_id,i.staff_id,
	s.user_id
	FROM finance_refund AS i
	LEFT JOIN order_receiver AS r ON i.order_id=r.id
	LEFT JOIN order_source AS s ON i.order_id=s.id ".$where." ORDER BY i.action_date DESC LIMIT ".$limit;
$result = mysql_query($tsql,$_mysql_link_);
$no = 1;
while($dbRow = mysql_fetch_object($result)){
	$fin_order 					= array();
	$fin_order['no'] 			= $no++;

	//订单编号
	$oid = $dbRow->order_id;
	$order_sql = "SELECT bind_number FROM order_source WHERE id=$oid AND company_id=$company_id";
	$order_res = mysql_query($order_sql,$_mysql_link_);
	$bind_data = mysql_fetch_object($order_res);
	$bind_number = $bind_data->bind_number;
	$fin_order['bind_number'] = $bind_number;

	//客户
	$receiver_sql = "SELECT name FROM order_receiver WHERE id=$oid AND company_id=$company_id";
	$receiver_res = mysql_query($receiver_sql,$_mysql_link_);
	$name_data = mysql_fetch_object($receiver_res);
	$receiver_name = $name_data->name;
	$fin_order['receiver_name'] = $receiver_name;

	$fin_order['after_date'] = $dbRow->action_date;
	$fin_order['payment'] = $dbRow->payment;
	$fin_order['refund'] = $dbRow->refund;
	$fin_order['status'] = $dbRow->status;
	$fin_order['user_id'] = $dianpu[$dbRow->user_id];
	$fin_order['order_id'] = $dbRow->order_id;
	$fin_order['bank_id'] = $bank[$dbRow->bank_id];
	$xtpl->assign('fin_order',$fin_order);
	$xtpl->parse('main.fin_order');
}


$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");