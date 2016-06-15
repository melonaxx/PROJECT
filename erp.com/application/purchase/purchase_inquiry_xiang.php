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


$status_receipt = array('N'=>'未到货','P'=>'部分到货','Y'=>'全部到货');
$status_refund = array('N'=>'未退货','P'=>'部分退货','Y'=>'全部退货');
$status_audit = array('Y'=>'通过审核','N'=>'待审核','R'=>'待修改','F'=>'拒绝');
$pay_method = array('After'=>'后付款','New'=>'先付款','Deposit'=>'订金加尾款');
$freight = array('Supplier'=>'供应商','Company'=>'本公司');
if(!empty($_GET['id'])){
	$id = intval($_GET['id']);
	$no = 1;
	$sql = "SELECT m.id,m.number,m.total,m.price,m.store_id,m.staff_id,m.brief,m.supplier_id,m.status_receipt,m.status_audit,m.status_refund,m.action_date,m.body,f.freight_side,f.freight_amount,m.shipping_company,m.waybill_number,f.pay_method,m.total_finish,m.total_way,m.total_refund FROM purchase_main_info AS m LEFT JOIN finance_purchase AS f ON m.id=f.purchase_id WHERE m.company_id = '$company_id' AND m.id = '$id' ";
	$result = mysql_query($sql,$_mysql_link_);
	while($dbRow = mysql_fetch_object($result)){
		$details = array();
		$details['id']				= $dbRow->id;
		$details['body']			= $dbRow->body;
		$details['total']			= $dbRow->total;
		$details['price']			= $dbRow->price;
		$details['brief']			= $dbRow->brief;
		$details['number']			= $dbRow->number;
		$details['staff_id']		= $dbRow->staff_id;
		$details['total_way']		= $dbRow->total_way;
		$details['action_date']		= $dbRow->action_date;
		$details['total_finish']	= $dbRow->total_finish;
		$details['total_refund']	= $dbRow->total_refund;
		$details['waybill_number']	= $dbRow->waybill_number;
		$details['freight_amount']	= $dbRow->freight_amount;
		$details['shipping_company']= $dbRow->shipping_company;
		$details['freight_side']	= $freight[$dbRow->freight_side];
		$details['pay_method']		= $pay_method[$dbRow->pay_method];
		$details['status_audit']	= $status_audit[$dbRow->status_audit];
		$details['status_refund']	= $status_refund[$dbRow->status_refund];
		$details['status_receipt']	= $status_receipt[$dbRow->status_receipt];
		$store_id					= $dbRow->store_id;
		$supplier_id				= $dbRow->supplier_id;
		$sql = "SELECT name FROM store_info WHERE company_id = '$company_id' AND id='$store_id' ";
		$result = mysql_query($sql,$_mysql_link_);
		while($storeinfo = mysql_fetch_object($result)){
			$details['store_id']	= $storeinfo->name;
		}
		$sql = "SELECT name FROM purchase_supplier WHERE company_id = '$company_id' AND id = '$supplier_id' ";
		$result = mysql_query($sql,$_mysql_link_);
		while($purchase = mysql_fetch_object($result)){
			$details['supplier_id']	=	$purchase->name;
		}

		$xtpl->assign('details',$details);
		$xtpl->parse('main.details');
	}
	$sql = "SELECT id,product_id,format,parts_id,total,price,content,total_way,total_refund,total_finish FROM purchase_product WHERE company_id = '$company_id' AND purchase_id = '$id' ";
	$result = mysql_query($sql,$_mysql_link_);
	$no = 1;
	while($dbRow = mysql_fetch_object($result)){
		$product 		= array();
		$product['no']			= $no++;
		$product['id']			= $dbRow->id;
		$product['product_id']	= $dbRow->product_id;
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
		$product['format']		= $dbRow->format;
		$product['parts_id']	= $dbRow->parts_id;
		$product['total']		= $dbRow->total;
		$product['price']		= $dbRow->price;
		$product['content']		= $dbRow->content;
		$zongjia 				= $product['total']*$product['price'];
		$product['zongjia']		= $zongjia;
		$product['total_way']	= $dbRow->total_way;
		$product['total_refund']= $dbRow->total_refund;
		$product['total_finish']= $dbRow->total_finish;

		$product_info[] = $product;
		$xtpl->assign('product',$product);
		$xtpl->parse('main.product');

	}
}

if($_GET['action']=='print'){
	$arr['details'] = $details;
	$arr['product_info'] = $product_info;
	header("Content-Type: text/html; charset=UTF-8");
	echo json_encode($arr);
	exit;
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
//修改运单号
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
