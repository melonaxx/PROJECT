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
$staff_id = $_SESSION['_application_info_']['staff_id'];

$status_receipt = array('N'=>'未到货','P'=>'部分到货','Y'=>'全部到货');
$status_refund = array('N'=>'未退货','P'=>'部分退货','Y'=>'全部退货');
$status_audit = array('Y'=>'通过审核','N'=>'待审核','R'=>'待修改','F'=>'拒绝');

if(!empty($_GET['id'])){
	$id = intval($_GET['id']);
	$sql = "SELECT m.id,m.number,m.total,m.price,m.store_id,m.staff_id,m.brief,m.supplier_id,m.status_audit,m.status_refund,m.status_receipt,m.action_date,m.body,f.freight_side,f.freight_amount,m.shipping_company,m.waybill_number,f.pay_method,m.total_finish,m.total_way,m.total_refund FROM purchase_main_info AS m LEFT JOIN finance_purchase AS f ON m.id=f.purchase_id WHERE m.company_id = '$company_id' AND m.id = '$id' ";
	$result = mysql_query($sql,$_mysql_link_);
	while($dbRow = mysql_fetch_object($result)){
		$purchase 					= array();
		$purchase['id'] 			= $dbRow->id;
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
		$purchase['store_id'] 		= $dbRow->store_id;
		$purchase['number'] 		= $dbRow->number;
		$purchase['total'] 			= $dbRow->total;
		$purchase['price'] 			= $dbRow->price;
		$purchase['staff_id'] 		= $dbRow->staff_id;
		$purchase['brief'] 			= $dbRow->brief;
		$purchase['status_receipt'] = $status_receipt[$dbRow->status_receipt];
		$purchase['status_refund'] 	= $status_refund[$dbRow->status_refund];
		$purchase['status_audit'] 	= $status_audit[$dbRow->status_audit];
		$purchase['action_date'] 	= $dbRow->action_date;
		$purchase['supplier_id'] 	= $dbRow->supplier_id;
		$purchase['body'] 			= $dbRow->body;
		$purchase['freight_side'] 	= $dbRow->freight_side;
		$purchase['freight_amount'] = $dbRow->freight_amount;
		$purchase['shipping_company'] = $dbRow->shipping_company;
		$purchase['waybill_number'] = $dbRow->waybill_number;
		$purchase['pay_method'] 	= $dbRow->pay_method;
		$purchase['total_finish'] 	= $dbRow->total_finish;
		$purchase['total_way'] 		= $dbRow->total_way;
		$purchase['total_refund'] 	= $dbRow->total_refund;

		$xtpl ->assign('purchase',$purchase);
		$xtpl ->parse('main.purchase');
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

		$xtpl->assign('product',$product);
		$xtpl->parse('main.product');
	}
}

if(!empty($_POST['made']) && $_POST['zongshu_rk']>0){
	$number_rk 		= time();
	$id 			= intval($_POST['id']);
	$in_sum 		= $_POST['in_sum'];
	$in_sum2 		= $_POST['in_sum2'];
	$zt_sum 		= $_POST['zt_sum'];
	$xiaoji 		= $_POST['xiaoji'];
	$rk_body 		= $_POST['rk_body'];
	$out_sum 		= $_POST['out_sum'];
	$price_rk 		= $_POST['price_rk'];
	$in_sum_rk 		= $_POST['in_sum_rk'];
	$format_rk 		= $_POST['format_rk'];
	$product_id 	= $_POST['product_id'];
	$parts_id_rk 	= $_POST['parts_id_rk'];
	$product_id_rk 	= $_POST['product_id_rk'];
	$zaitu			= intval($_POST['zaitu']);
	$tuihuo			= intval($_POST['tuihuo']);
	$yiruku			= intval($_POST['yiruku']);
	$store_id 		= intval($_POST['store_id']);
	$zongshu_rk 	= intval($_POST['zongshu_rk']);
	$zongjia_rk 	= floatval($_POST['zongjia_rk']);
	$supplier_id 	= intval($_POST['supplier_id']);
	$number 		= replace_safe($_POST['number']);
	$freight_amount = floatval($_POST['freight_amount']);
	$freight_side 	= replace_safe($_POST['freight_side']);

	$sql = "UPDATE finance_purchase SET freight_side='$freight_side',freight_amount='$freight_amount' WHERE company_id='$company_id' AND purchase_id='$id' ";
	mysql_query($sql,$_mysql_link_);

	$sql = "UPDATE purchase_main_info SET total_finish = '$yiruku',total_way = '$zaitu', total_refund = '$tuihuo' WHERE company_id = '$company_id' AND id = '$id' ";
	mysql_query($sql,$_mysql_link_);
	for($i = 0;$i<count($product_id);$i++){
		$pro[$i]   = intval($product_id[$i]);
		$total_finish[$i] = intval($in_sum[$i])+intval($in_sum2[$i]);
		$total_way[$i] = intval($zt_sum[$i]);
		$total_refund[$i] = intval($out_sum[$i]);
		$sql = "UPDATE purchase_product SET total_finish = '$total_finish[$i]',total_way = '$total_way[$i]', total_refund = '$total_refund[$i]' WHERE company_id = '$company_id' AND purchase_id = '$id' AND product_id = '$pro[$i]'";
		mysql_query($sql,$_mysql_link_);
	}
	//判断采购商品是否已全部完成入库
	$sql = "SELECT id,total,total_finish,total_way,total_refund FROM purchase_main_info WHERE company_id = '$company_id' AND id = '$id' ";
	$result = mysql_query($sql,$_mysql_link_);
	while($dbRow = mysql_fetch_object($result)){
		$res 					= array();
		$res['id'] 				= $dbRow->id;
		$res['total'] 			= $dbRow->total;
		$res['total_finish'] 	= $dbRow->total_finish;
		$res['total_way'] 		= $dbRow->total_way;
		$res['total_refund'] 	= $dbRow->total_refund;
		if(intval($res['total']) == intval($res['total_finish']) + intval($res['total_refund'])){
			$sql = "UPDATE purchase_main_info SET status_receipt = 'Y' WHERE id = '{$res["id"]}' ";
			mysql_query($sql,$_mysql_link_);
		}

		if(intval($res['total']) > intval($res['total_way']) && intval($res['total_way']) > 0){
			$sql = "UPDATE purchase_main_info SET status_receipt = 'P' WHERE id = '{$res["id"]}' ";
			mysql_query($sql,$_mysql_link_);
		}
	}
	//添加入库单和入库商品
	$sql = "INSERT INTO store_input_info SET company_id = '$company_id', `number`='$number_rk', store_type = 'Input', supplier_id = '$supplier_id', store_id = '$store_id',action_date = NOW(), total = '$zongshu_rk', price = '$zongjia_rk', purchase_id = '$number',input_staff_id = '$staff_id' ";
	$result = mysql_query($sql,$_mysql_link_);
	if(mysql_affected_rows($_mysql_link_) == 1){
		$info_id = mysql_insert_id($_mysql_link_);
		for($i=0;$i<count($product_id_rk);$i++){
			$pro[$i] 	= intval($product_id_rk[$i]);
			$format[$i] = replace_safe($format_rk[$i]);
			$parts[$i] 	= replace_safe($parts_id_rk[$i]);
			$sum[$i] 	= intval($in_sum_rk[$i]);
			$pri[$i] 	= floatval($price_rk[$i]);
			$bod[$i] 	= replace_safe($rk_body[$i]);
			$pay[$i] 	= floatval($xiaoji[$i]);
			if($sum[$i] > 0){
				$sql = "INSERT INTO store_input_product SET info_id = '$info_id', product_id = '$pro[$i]',format = '$format[$i]', parts_id = '$parts[$i]', total = '$sum[$i]', price = '$pri[$i]',body = '$bod[$i]',payment = '$pay[$i]'";
				mysql_query($sql,$_mysql_link_);

				$sql = "UPDATE store_product SET total_real = total_real+'$sum[$i]',total_way = '$zaitu',total_available=total_available+'$sum[$i]' WHERE company_id = '$company_id' AND store_id = '$store_id' AND product_id = '$pro[$i]' ";
				mysql_query($sql,$_mysql_link_);

				$sql = "UPDATE store_related SET real_total = real_total+'$sum[$i]',way_total = '$zaitu',available_total=available_total+'$sum[$i]' WHERE company_id = '$company_id' AND store_id = '$store_id' AND product_id = '$pro[$i]' ";
				mysql_query($sql,$_mysql_link_);
			}
		}
	}
	header('location:/purchase/purchase_documents_List.php');
}else if(!empty($_POST['made']) && $_POST['zongshu_rk']<=0){
	header('location:/purchase/purchase_in_storage_detail.php');
}
//修改采购摘要
if(!empty($_POST['brief']))
{
	header("Content-Type: text/html; charset=UTF-8");
	$brief 	= replace_safe($_POST['brief']);
	$purchase_id    	= replace_safe($_POST['purchase_id']);
	if(!empty($brief))
	{
		$sql = "UPDATE purchase_main_info SET brief='$brief' WHERE company_id='$company_id' AND id='$purchase_id'";
		mysql_query($sql,$_mysql_link_);
	}
	exit;
}
//修改采购备注
if(!empty($_POST['body']))
{
	header("Content-Type: text/html; charset=UTF-8");
	$body 	= replace_safe($_POST['body']);
	$purchase_id    	= replace_safe($_POST['purchase_id']);
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
	$content 		= replace_safe($_POST['content']);
	$purchase_id    = intval($_POST['purchase_id']);
	$product_id    	= intval($_POST['product_id']);
	if(!empty($content))
	{
		$sql = "UPDATE purchase_product SET content='$content' WHERE company_id='$company_id' AND purchase_id='$purchase_id' AND product_id='$product_id'";
		mysql_query($sql,$_mysql_link_);
	}
	exit;
}
//修改托运公司
if(!empty($_POST['shipping_company']))
{
	header("Content-Type: text/html; charset=UTF-8");
	$shipping_company 	= replace_safe($_POST['shipping_company']);
	$purchase_id    	= replace_safe($_POST['purchase_id']);
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
	$purchase_id    = replace_safe($_POST['purchase_id']);
	if(!empty($waybill_number))
	{
		$sql = "UPDATE purchase_main_info SET waybill_number='$waybill_number' WHERE company_id='$company_id' AND id='$purchase_id'";
		mysql_query($sql,$_mysql_link_);
		$sql = "UPDATE finance_purchase SET `number`='$waybill_number' WHERE company_id='$company_id' AND purchase_id='$purchase_id'";
		mysql_query($sql,$_mysql_link_);
	}
	exit;
}


$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");