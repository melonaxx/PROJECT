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


//供应商
$sql = "SELECT id,name FROM purchase_supplier WHERE company_id = '$company_id' ";
$result = mysql_query($sql,$_mysql_link_);
$purchasesupplier = array();
while($purchase = mysql_fetch_object($result)){

	$purchasesupplier[$purchase->id]	=	$purchase->name;
}
//仓库
$sql = "SELECT id,name FROM store_info WHERE company_id = '$company_id' ";
$result = mysql_query($sql,$_mysql_link_);
$store_info = array();
while($storeinfo = mysql_fetch_object($result)){

	$store_info[$storeinfo->id]	= $storeinfo->name;
}


$status_audit = array('Y'=>'通过审核','N'=>'待审核','R'=>'待修改','F'=>'已拒绝');
$pay_method = array('After'=>'后付款','New'=>'先付款','Deposit'=>'订金加尾款');
$freight = array('Supplier'=>'供应商','Company'=>'本公司');
if(!empty($_GET['id'])){
	$id = intval($_GET['id']);
	$no = 1;
	$sql = "SELECT m.id,m.number,m.total,m.price,m.store_id,m.staff_id,m.brief,m.supplier_id,m.status_audit,m.action_date,m.body,f.freight_side,f.freight_amount,m.shipping_company,m.waybill_number,f.pay_method FROM purchase_main_info AS m LEFT JOIN finance_purchase AS f ON m.id=f.purchase_id WHERE m.company_id = '$company_id' AND m.id = '$id' ";
	$result = mysql_query($sql,$_mysql_link_);
	while($dbRow = mysql_fetch_object($result)){
		$details = array();
		$details['id']				= $dbRow->id;
		$details['number']			= $dbRow->number;
		$details['total']			= $dbRow->total;
		$details['price']			= $dbRow->price;
		$details['store_id']		= $store_info[$dbRow->store_id];
		$details['staff_id']		= $dbRow->staff_id;
		$details['brief']			= $dbRow->brief;
		$details['supplier_id']		= $purchasesupplier[$dbRow->supplier_id];
		$details['status_audit']	= $status_audit[$dbRow->status_audit];
		$details['action_date']		= $dbRow->action_date;
		$details['body']			= $dbRow->body;
		$details['freight_side']	= $freight[$dbRow->freight_side];
		$details['freight_amount']	= $dbRow->freight_amount;
		$details['shipping_company']= $dbRow->shipping_company;
		$details['waybill_number']	= $dbRow->waybill_number;
		$details['pay_method']		= $pay_method[$dbRow->pay_method];

		$xtpl->assign('details',$details);
		$xtpl->parse('main.details');
	}
	$sql = "SELECT i.name,p.product_id,p.parts_id,p.format,p.total,p.price,p.content FROM purchase_product AS p LEFT JOIN product_info AS i ON p.product_id=i.id WHERE p.company_id = '$company_id' AND p.purchase_id = '$id' ";
	$result = mysql_query($sql,$_mysql_link_);
	while($dbRow = mysql_fetch_object($result)){
		$product 		= array();
		$product['no']			= $no++;
		$product['product_id']	= $dbRow->product_id;
		$product['pro']			= $dbRow->name;
		$product['format']		= $dbRow->format;
		$sql = "SELECT name FROM product_parts_name WHERE company_id = '$company_id' AND id = '$dbRow->parts_id' ";
		$re = mysql_query($sql,$_mysql_link_);
		while($parts = mysql_fetch_object($re)){
			$product['parts_id'] = $parts->name;
		}
		$product['total']		= $dbRow->total;
		$product['price']		= $dbRow->price;
		$product['content']		= $dbRow->content;
		$zongjia 				= $product['total']*$product['price'];
		$product['zongjia']		= $zongjia;
		$xtpl->assign('product',$product);
		$xtpl->parse('main.product');
	}
}
//修改托运公司
if(!empty($_POST['shipping_company']))
{
	header("Content-Type: text/html; charset=UTF-8");
	$shipping_company = replace_safe($_POST['shipping_company']);
	$purchase_id    = intval($_POST['purchase_id']);
	if(!empty($shipping_company))
	{
		$sql = "UPDATE purchase_main_info SET shipping_company='$shipping_company' WHERE company_id='$company_id' AND id='$purchase_id'";
		mysql_query($sql,$_mysql_link_);
		$sql = "UPDATE finance_purchase SET shipping_company='$shipping_company' WHERE company_id='$company_id' AND purchase_id='$purchase_id'";
		mysql_query($sql,$_mysql_link_);
	}
	exit;
}
//修改运单号
if(!empty($_POST['waybill_number']))
{
	header("Content-Type: text/html; charset=UTF-8");
	$waybill_number = replace_safe($_POST['waybill_number']);
	$purchase_id    = intval($_POST['purchase_id']);
	if(!empty($waybill_number))
	{
		$sql = "UPDATE purchase_main_info SET waybill_number='$waybill_number' WHERE company_id='$company_id' AND id='$purchase_id'";
		mysql_query($sql,$_mysql_link_);
		$sql = "UPDATE finance_purchase SET `number`='$waybill_number' WHERE company_id='$company_id' AND purchase_id='$purchase_id'";
		mysql_query($sql,$_mysql_link_);
	}
	exit;
}
//修改采购摘要
if(!empty($_POST['brief']))
{
	header("Content-Type: text/html; charset=UTF-8");
	$brief = replace_safe($_POST['brief']);
	$purchase_id    = intval($_POST['purchase_id']);
	if(!empty($brief))
	{
		$sql = "UPDATE purchase_main_info SET brief='$brief' WHERE company_id='$company_id' AND id='$purchase_id'";
		mysql_query($sql,$_mysql_link_);
	}
	exit;
}
//修改采购摘要
if(!empty($_POST['body']))
{
	header("Content-Type: text/html; charset=UTF-8");
	$body = replace_safe($_POST['body']);
	$purchase_id    = intval($_POST['purchase_id']);
	if(!empty($body))
	{
		$sql = "UPDATE purchase_main_info SET body='$body' WHERE company_id='$company_id' AND id='$purchase_id'";
		mysql_query($sql,$_mysql_link_);
	}
	exit;
}
//修改采购商品备注
if(!empty($_POST['content']))
{
	header("Content-Type: text/html; charset=UTF-8");
	$content = replace_safe($_POST['content']);
	$purchase_id    = intval($_POST['purchase_id']);
	$product_id    = intval($_POST['product_id']);
	if(!empty($content))
	{
		$sql = "UPDATE purchase_product SET content='$content' WHERE company_id='$company_id' AND purchase_id='$purchase_id' AND product_id='$product_id' ";
		mysql_query($sql,$_mysql_link_);
	}
	exit;
}
$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");
