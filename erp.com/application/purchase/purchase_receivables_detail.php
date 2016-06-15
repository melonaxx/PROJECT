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
$staff_id   = $_SESSION['_application_info_']['staff_id'];

$sql = "SELECT id,name FROM finance_bank WHERE company_id='$company_id' AND status='Y' ";
$result = mysql_query($sql,$_mysql_link_);
$bankInfo 	 = array();
while($dbRow = mysql_fetch_object($result))
{
	$bank 				  = array();
	$bank['id']			  = $dbRow->id;
	$bank['name']		  = $dbRow->name;
	$xtpl->assign('bank',$bank);
	$xtpl->parse('main.bank');
	$bankInfo[$dbRow->id] = $dbRow->name;
}

$status_receipt = array('N'=>'未到货','P'=>'部分到货','Y'=>'全部到货');
$status_refund 	= array('N'=>'未退货','P'=>'部分退货','Y'=>'全部退货');
$status_audit 	= array('Y'=>'通过审核','N'=>'待审核','R'=>'待修改','F'=>'拒绝');
$freight_side 	= array('Supplier'=>'供应商','Company'=>'本公司');
$status 		= array('D'=>'部分付款','N'=>'未付款','Y'=>'完成付款');
$pay_method 	= array('After'=>'后付款','Deposit'=>'订金加尾款','New'=>'先付款');

if(!empty($_REQUEST['id'])){
	$id = intval($_REQUEST['id']);
	$sql = "SELECT m.id,m.number,m.total,m.price,m.store_id,m.staff_id,m.brief,m.supplier_id,m.status_audit,m.status_refund,m.status_receipt,m.action_date,m.body,m.shipping_company,m.waybill_number,m.total_finish,m.total_way,m.total_refund,f.freight_side,f.freight_amount,f.payment_already,f.payment_remain,f.payment_return,f.pay_method,f.status FROM purchase_main_info AS m LEFT JOIN finance_purchase AS f ON m.id=f.purchase_id WHERE m.company_id = '$company_id' AND m.id = '$id' ";
	$result = mysql_query($sql,$_mysql_link_);
	while($dbRow = mysql_fetch_object($result)){
		$purchase 					= array();
		$purchase['id'] 			= $dbRow->id;
		$purchase['body'] 				= $dbRow->body;
		$purchase['total'] 				= $dbRow->total;
		$purchase['price'] 				= $dbRow->price;
		$purchase['brief'] 				= $dbRow->brief;
		$purchase['number'] 			= $dbRow->number;
		$purchase['staff_id'] 			= $dbRow->staff_id;
		$purchase['store_id'] 			= $dbRow->store_id;
		$purchase['total_way'] 			= $dbRow->total_way;
		$purchase['action_date'] 		= $dbRow->action_date;
		$purchase['supplier_id'] 		= $dbRow->supplier_id;
		$purchase['total_finish'] 		= $dbRow->total_finish;
		$purchase['total_refund'] 		= $dbRow->total_refund;
		$purchase['waybill_number'] 	= $dbRow->waybill_number;
		$purchase['freight_amount'] 	= $dbRow->freight_amount;
		$purchase['payment_remain'] 	= $dbRow->payment_remain;
		$purchase['payment_return'] 	= $dbRow->payment_return;
		$purchase['status'] 			= $status[$dbRow->status];
		$purchase['payment_already'] 	= $dbRow->payment_already;
		$purchase['shipping_company'] 	= $dbRow->shipping_company;
		$purchase['pay_method'] 		= $pay_method[$dbRow->pay_method];
		$purchase['status_audit'] 		= $status_audit[$dbRow->status_audit];
		$purchase['freight_side'] 		= $freight_side[$dbRow->freight_side];
		$purchase['status_refund'] 		= $status_refund[$dbRow->status_refund];
		$purchase['status_receipt'] 	= $status_receipt[$dbRow->status_receipt];

		$sql = "SELECT name FROM store_info WHERE company_id = '$company_id' AND store_status <> 'Delete' AND id = '{$dbRow->store_id}'";
		$result_store = mysql_query($sql,$_mysql_link_);
		while($store_info = mysql_fetch_object($result_store)){
			$purchase['sto']	= $store_info->name;
		}
		$sql = "SELECT nick FROM company_staff_info WHERE company_id = '$company_id' AND is_valid = 'Y' AND id = '{$dbRow->staff_id}'";
		$result_staff = mysql_query($sql,$_mysql_link_);
		while($company_staff = mysql_fetch_object($result_staff)){
			$purchase['staff'] = $company_staff->nick;
		}
		$sql = "SELECT name FROM purchase_supplier WHERE is_delete = 'N' AND company_id = '$company_id' AND id = '{$dbRow->supplier_id}'";
		$result_supplier = mysql_query($sql,$_mysql_link_);
		while($supplier = mysql_fetch_object($result_supplier)){
			$purchase['sup'] = $supplier->name;
		}

		$xtpl ->assign('purchase',$purchase);
		$xtpl ->parse('main.purchase');
	}
	$sql = "SELECT finance_bank.name FROM finance_cash_logs LEFT JOIN finance_bank ON finance_cash_logs.bank_id=finance_bank.id WHERE finance_cash_logs.company_id='$company_id' AND finance_cash_logs.info_type='Purchase' AND finance_cash_logs.info_id='$id' ";
	$result = mysql_query($sql,$_mysql_link_);
	$arr 	= array();
	while($dbRow   = mysql_fetch_object($result))
	{
		$arr[]   = $dbRow->name;
	}
	$bank 	 = array_unique($arr);
	$tol  	 = count($bank);
	$bankId = '';
	if($tol == 1)
	{
		$bankId = $bank[0];
	}else if($tol<1){
		$bankId = '';
	}else if($tol>1){
		$bankId = '多账户';
	}
	$main['bankId'] = $bankId;
	$sql = "SELECT id,product_id,format,parts_id,total,price,content,total_way,total_refund,total_finish FROM purchase_product WHERE company_id = '$company_id' AND purchase_id = '$id' ";
	$result = mysql_query($sql,$_mysql_link_);
	$no = 1;
	while($dbRow = mysql_fetch_object($result)){
		$product 					= array();
		$product['no']				= $no++;
		$product['id']				= $dbRow->id;
		$product['total']			= $dbRow->total;
		$product['price']			= $dbRow->price;
		$product['format']			= $dbRow->format;
		$product['content']			= $dbRow->content;
		$product['parts_id']		= $dbRow->parts_id;
		$product['total_way']		= $dbRow->total_way;
		$product['product_id']		= $dbRow->product_id;
		$product['total_refund']	= $dbRow->total_refund;
		$product['total_finish']	= $dbRow->total_finish;
		$zongjia 					= $product['total']*$product['price'];
		$product['zongjia']			= $zongjia;

		$sql = "SELECT name FROM product_info WHERE company_id = '$company_id' AND id = '$dbRow->product_id' ";
		$re = mysql_query($sql,$_mysql_link_);
		while($pro = mysql_fetch_object($re)){
			$product['pro'] = $pro->name;
		}
		$sql = "SELECT name FROM product_parts_name WHERE company_id = '$company_id' AND id = '$dbRow->parts_id' ";
		$re = mysql_query($sql,$_mysql_link_);
		while($parts = mysql_fetch_object($re)){
			$product['par'] = $parts->name;
		}

		$xtpl->assign('product',$product);
		$xtpl->parse('main.product');
	}
}

if(!empty($_POST['made']) && $_POST['money']>0)
{
	$body 			= replace_safe($_POST['body']);
	$money 			= floatval($_POST['money']);
	$bank_id 		= intval($_POST['bank_id']);
	$purchase_id 	= intval($_POST['purchase_id']);
	$supplier 		= replace_safe($_POST['supplier']);
	$subject 		= replace_safe($_POST['subject']);
	if(!isset($bankInfo[$bank_id]))
	{
		$bank_id = 0;
	}
	//添加收款记录
	$sql = "INSERT INTO finance_cash_logs SET company_id='$company_id',info_type='Purchase',info_id='$purchase_id',amount_date= NOW(),business_date= NOW(),type='Input',subject='$subject',company_type='Supplier',company_name='$supplier',`money`='$money',bank_id='$bank_id',body='$body' ";
	mysql_query($sql,$_mysql_link_);
	//修改采购供应商欠款金额
	$sql = "UPDATE finance_purchase SET payment_return=payment_return-'$money' WHERE company_id='$company_id' AND purchase_id='$purchase_id' ";
	mysql_query($sql,$_mysql_link_);

	//修改账户金额
	$sql = "UPDATE finance_bank SET balance=balance+'$money' WHERE company_id='$company_id' AND id='$bank_id' ";
	mysql_query($sql,$_mysql_link_);

	header('location:/purchase/purchase_waiting_payment.php');
}else if(!empty($_POST['made']) && $_POST['money']<=0){
	header('location:/purchase/purchase_receivables_detail.php');
}
//修改采购摘要
if(!empty($_POST['brief']))
{
	header("Content-Type: text/html; charset=UTF-8");
	$brief = replace_safe($_POST['brief']);
	$purchase_id      = intval($_POST['purchase_id']);
	if(!empty($brief))
	{
		$sql="UPDATE purchase_main_info SET brief='$brief' WHERE company_id='$company_id' AND id='$purchase_id' ";
		mysql_query($sql,$_mysql_link_);
	}
	exit;
}
//修改采购备注
if(!empty($_POST['body']))
{
	header("Content-Type: text/html; charset=UTF-8");
	$body = replace_safe($_POST['body']);
	$purchase_id      = intval($_POST['purchase_id']);
	if(!empty($body))
	{
		$sql="UPDATE purchase_main_info SET body='$body' WHERE company_id='$company_id' AND id='$purchase_id' ";
		mysql_query($sql,$_mysql_link_);
	}
	exit;
}
//修改采购商品备注
if(!empty($_POST['content']))
{
	header("Content-Type: text/html; charset=UTF-8");
	$content = replace_safe($_POST['content']);
	$purchase_id      = intval($_POST['purchase_id']);
	$product_id      = intval($_POST['product_id']);
	if(!empty($content))
	{
		$sql="UPDATE purchase_product SET content='$content' WHERE company_id='$company_id' AND purchase_id='$purchase_id' AND product_id='$product_id' ";
		mysql_query($sql,$_mysql_link_);
	}
	exit;
}
//修改托运公司
if(!empty($_POST['shipping_company']))
{
	header("Content-Type: text/html; charset=UTF-8");
	$shipping_company = replace_safe($_POST['shipping_company']);
	$purchase_id      = intval($_POST['purchase_id']);
	if(!empty($shipping_company))
	{
		$sql="UPDATE purchase_main_info SET shipping_company='$shipping_company' WHERE company_id='$company_id' AND id='$purchase_id' ";
		mysql_query($sql,$_mysql_link_);
		$sql="UPDATE finance_purchase SET shipping_company='$shipping_company' WHERE company_id='$company_id' AND purchase_id='$purchase_id' ";
		mysql_query($sql,$_mysql_link_);
	}
	exit;
}
//修改运单号码
if(!empty($_POST['waybill_number']))
{
	header("Content-Type: text/html; charset=UTF-8");
	$waybill_number = replace_safe($_POST['waybill_number']);
	$purchase_id    = intval($_POST['purchase_id']);
	if(!empty($waybill_number))
	{
		$sql="UPDATE purchase_main_info SET waybill_number='$waybill_number' WHERE company_id='$company_id' AND id='$purchase_id' ";
		mysql_query($sql,$_mysql_link_);
		$sql="UPDATE finance_purchase SET `number`='$waybill_number' WHERE company_id='$company_id' AND purchase_id='$purchase_id' ";
		mysql_query($sql,$_mysql_link_);
	}
	exit;
}

$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");