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
$chaxun = array();
$chaxun[] = "c.company_id = '".$company_id."'";
$chaxun[] = "c.info_type = 'Purchase'";

if(!empty($_REQUEST['type'])){
	$type = replace_safe($_REQUEST['type']);
	if(!empty($type)){
		$chaxun[] = "c.type = '".$type."'";
		$main['type']	= $type;
		$page_param		= array();
		$page_param['type']		= replace_safe($_REQUEST['type'], 20, false, false);
	}
}
if(!empty($_REQUEST['date'])){
	$date = replace_safe($_REQUEST['date']);
	if(!empty($date)){
		$chaxun[]			= "INSTR(c.amount_date,'$date')";
		$main['date']		= $date;
		$page_param			= array();
		$page_param['date']= replace_safe($_REQUEST['date'], 20, false, false);
	}
}
if(!empty($_REQUEST['purchase_id']))
{
	$info_id = intval($_REQUEST['purchase_id']);
	if(!empty($info_id))
	{
		$chaxun[] = "c.info_id = '".$info_id."'";
	}
}
if(!empty($_REQUEST['subject']) && $_REQUEST['subject']=='FF')
{
	$chaxun[] = "(c.subject = '欠款尾款' OR c.subject = '订金')";
}
if(!empty($_REQUEST['subject']) && $_REQUEST['subject']=='YF')
{
	$chaxun[] = "c.subject = '运费'";
}
if(!empty($_REQUEST['subject']) && $_REQUEST['subject']=='SS')
{
	$chaxun[] = "c.subject = '采购退货'";
}

$where = '';
if(count($chaxun)>0)
{
	$where = "WHERE ".implode(" AND ", $chaxun);
}
// ----  查询数量 ----
$sql = "SELECT COUNT(*) as total FROM finance_cash_logs AS c ".$where;
$result	= mysql_query($sql, $_mysql_link_);
$main['total']		= mysql_result($result, 0, 'total');

//---- 处理分页 ----
if(!is_array($page_param))
{
	$page_param			= array();
}
$main['page_info']	= erp_page_info($main['total'], $page, $page_param);
$limit	= ($page - 1) * $_SESSION["_application_info_"]["page_size"].", ".$_SESSION["_application_info_"]["page_size"];

//获取银行账户名
$sql = "SELECT id,name FROM finance_bank WHERE company_id='$company_id' ";
$result 	 = mysql_query($sql,$_mysql_link_);
$bankInfo 	 = array();
while($dbRow = mysql_fetch_object($result))
{
	$bankInfo[$dbRow->id] = $dbRow->name;
}

$status_receipt = array('N'=>'未到货','P'=>'部分到货','Y'=>'全部到货');
$status_refund = array('N'=>'未退货','P'=>'部分退货','Y'=>'全部退货');
$status_audit = array('Y'=>'通过审核','N'=>'待审核','R'=>'待修改','F'=>'已拒绝');
$type 			= array('Input'=>'收入','Output'=>'支出');
//---- 数量大于0 ----
if($main['total'] > 0)
{
	$sql = "SELECT c.id,c.type,c.subject,c.money,c.bank_id,c.amount_date,c.body,c.info_id,p.payment_already,p.payment_remain,p.payment_return,m.number FROM finance_cash_logs AS c LEFT JOIN finance_purchase AS p ON c.info_id=p.purchase_id LEFT JOIN purchase_main_info AS m ON c.info_id=m.id ".$where." ORDER BY c.amount_date DESC LIMIT ".$limit;
	$result = mysql_query($sql,$_mysql_link_);
	$no = 1;
	while($dbRow = mysql_fetch_object($result)){
		$logs 						= array();
		$logs['no'] 				= $no++;
		$logs['id'] 				= $dbRow->id;
		$logs['body'] 				= $dbRow->body;
		$logs['money'] 				= $dbRow->money;
		$logs['number'] 			= $dbRow->number;
		$logs['info_id'] 			= $dbRow->info_id;
		$logs['subject'] 			= $dbRow->subject;
		$logs['type'] 				= $type[$dbRow->type];
		$logs['payment_remain'] 	= $dbRow->payment_remain;
		$logs['payment_return'] 	= $dbRow->payment_return;
		$logs['payment_already'] 	= $dbRow->payment_already;
		$logs['bank_id'] 			= $bankInfo[$dbRow->bank_id];
		$logs['amount_date'] 		= substr($dbRow->amount_date,0,16);

		$xtpl->assign("logs", $logs);
		$xtpl->parse("main.logs");
	}
}




$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");