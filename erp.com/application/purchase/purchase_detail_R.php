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

//---- 获取供应商 ----
$sql = "SELECT id,name FROM purchase_supplier WHERE is_delete = 'N' AND company_id = '$company_id'";
$result = mysql_query($sql,$_mysql_link_);
$purchaseInfo = array();
while($purchase = mysql_fetch_object($result)){
	$purchase_add 			= array();
	$purchase_add['id']		= $purchase->id;
	$purchase_add['name']	= $purchase->name;
	$xtpl->assign("purchase_add", $purchase_add);
	$xtpl->parse("main.purchase_add");
	$purchaseInfo[$purchase->id] = $purchase->name;
}
//--获取仓库--
$sql = "SELECT id,name FROM store_info WHERE company_id = '$company_id' AND store_status != 'Delete'";
$result = mysql_query($sql,$_mysql_link_);
$storeInfo   = array();
while($dbRow = mysql_fetch_object($result)){
	$store 			= array();
	$store['id']	=	$dbRow->id;
	$store['name']	=	$dbRow->name;
	$xtpl->assign("store", $store);
	$xtpl->parse("main.store");
	$storeInfo[$dbRow->id]  = $dbRow->name;
}

//按搜索获取商品
if(!empty($_POST['value'])){
	$value 		= replace_safe($_POST['value']);
	$rows  		= intval($_POST['cc']);
	$array 		= explode(",",replace_safe($_POST['bb']));
	$addon		= array();
	$addon[]	= "product_info.company_id = '$company_id'";
	$addon[]	= "product_info.is_delete='N'";
	$addon[]	= "INSTR(product_info.name,'$value')";
	if($rows > 3){
		for($j=0;$j<count($array);$j++){
			if($array[$j]){
				$addon[] = "product_info.id <>".$array[$j];
			}
		}
	}
	$where  = "";
	$where .= "WHERE ".implode(" AND ", $addon);
	//获取规格名
	$sql = "SELECT body FROM product_format_value WHERE company_id = '$company_id' ";
	$res = mysql_query($sql,$_mysql_link_);
	$format_value = array();
	while($dbRow = mysql_fetch_object($res))
	{
		$format_value[$dbRow->id] = $dbRow->body;
	}
	//获取商品信息
	$sql = "SELECT product_info.id,product_info.name,
		product_detail.parts_id ,product_detail.price_purchase,product_detail.value_id_1,product_detail.value_id_2,product_detail.value_id_3,product_detail.value_id_4,product_detail.value_id_5
		FROM product_info
		LEFT JOIN product_detail on product_info.id = product_detail.id ".$where." LIMIT 15";
	$this = mysql_query($sql,$_mysql_link_);

	$arr = array();
	while($StoreInfo = mysql_fetch_object($this)){
		$sql = "SELECT name FROM product_parts_name WHERE id='$StoreInfo->parts_id' AND company_id='$company_id'";
		$result = mysql_query($sql,$_mysql_link_);
		$res = mysql_fetch_object($result);
		$value_1 = $format_value[$StoreInfo->value_id_1];
		$value_2 = $format_value[$StoreInfo->value_id_2];
		$value_3 = $format_value[$StoreInfo->value_id_3];
		$value_4 = $format_value[$StoreInfo->value_id_4];
		$value_5 = $format_value[$StoreInfo->value_id_5];
		$format  = ','.$value_1.','.$value_2.','.$value_3.','.$value_4.','.$value_5;
		$arr[] = array(
		'name' 		     => $StoreInfo->name,
		'id'   		     => $StoreInfo->id,
		'part_name'	   	 => $res->name,
		'format'     	 => rtrim($format,','),
		'price_purchase' => $StoreInfo->price_purchase,
		);
	}
	echo json_encode($arr);
	exit;
}

//商品改变
if(!empty($_POST['guige'])){
	$guige = replace_safe($_POST['guige']);
	$sql = "SELECT product_info.id,product_info.name,product_detail.parts_id,product_detail.price_purchase FROM product_info LEFT JOIN product_detail ON product_info.id = product_detail.id WHERE product_info.id= '$guige' AND product_info.company_id='$company_id'";
	$result = mysql_query($sql,$_mysql_link_);
	while($StoreInfo = mysql_fetch_object($result)){
		$arr = array();
		$sql = "SELECT name FROM product_parts_name WHERE id='$StoreInfo->parts_id' AND company_id='$company_id'";
		$result2 = mysql_query($sql,$_mysql_link_);
		$res = mysql_fetch_object($result2);
		$arr['id'] 				= $StoreInfo->id;
		$arr['name'] 			= $StoreInfo->name;
		$arr['price_purchase'] 	= $StoreInfo->price_purchase;
		$arr['unit'] 			= $res->name;
	}
	echo json_encode($arr);
	exit;
}


$status_audit = array('Y'=>'通过审核','N'=>'待审核','R'=>'待修改','F'=>'已拒绝');
if(!empty($_GET['id'])){
	$id = intval($_GET['id']);
	$no = 1;
	$sql = "SELECT m.id,m.number,m.total,m.price,m.store_id,m.staff_id,m.brief,m.supplier_id,m.status_audit,m.action_date,m.body,f.freight_side,f.freight_amount,f.shipping_company,m.waybill_number,f.pay_method FROM purchase_main_info AS m LEFT JOIN finance_purchase AS f ON m.id=f.purchase_id WHERE m.company_id = '$company_id' AND m.id = '$id' ";
	// var_dump($sql);die;
	$result = mysql_query($sql,$_mysql_link_);
	while($dbRow = mysql_fetch_object($result)){
		$details = array();
		$details['id']				= $dbRow->id;
		$details['number']			= $dbRow->number;
		$details['total']			= $dbRow->total;
		$details['price']			= $dbRow->price;
		$details['store_id']		= $dbRow->store_id;
		$details['staff_id']		= $dbRow->staff_id;
		$details['brief']			= $dbRow->brief;
		$details['supplier_id']		= $dbRow->supplier_id;
		$details['status_audit']	= $status_audit[$dbRow->status_audit];
		$details['action_date']		= $dbRow->action_date;
		$details['body']			= $dbRow->body;
		$details['freight_side']	= $dbRow->freight_side;
		$details['freight_amount']	= $dbRow->freight_amount;
		$details['shipping_company']= $dbRow->shipping_company;
		$details['waybill_number']	= $dbRow->waybill_number;
		$details['pay_method']		= $dbRow->pay_method;

		$xtpl->assign('details',$details);
		$xtpl->parse('main.details');
	}
	$sql = "SELECT i.name,p.product_id,p.parts_id,p.format,p.total,p.price,p.content FROM purchase_product AS p LEFT JOIN product_info AS i ON p.product_id=i.id  WHERE p.company_id = '$company_id' AND p.purchase_id = '$id' ";
	$result = mysql_query($sql,$_mysql_link_);
	while($dbRow = mysql_fetch_object($result)){
		$product 				= array();
		$product['no']			= $no++;
		$product['product_id']	= $dbRow->product_id;
		$product['pro']			= $dbRow->name;
		$product['format']		= $dbRow->format;
		$product['parts_id']	= $dbRow->parts_id;
		if($dbRow->parts_id){
			$sql = "SELECT name FROM product_parts_name WHERE company_id = '$company_id' AND id = '$dbRow->parts_id' ";
			$re = mysql_query($sql,$_mysql_link_);
			while($parts = mysql_fetch_object($re)){
				$product['parts'] = $parts->name;
			}
		}else{
			$product['parts'] = '';
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

$freightInfo	= array();
$freightInfo['Supplier'] = '供应商';
$freightInfo['Company']  = '本公司';
$payInfo				 = array();
$payInfo['After']        = '后付款';
$payInfo['Deposit']      = '订金加尾款';
$payInfo['New']          = '后付款';

if(!empty($_POST['send']) && !empty($_POST['id'])){
	$id 				= intval($_POST['id']);
	$number 			= intval($_POST['number']);
	$supplier_id 		= intval($_POST['supplier_id']);
	$store_id			= intval($_POST['store_id']);
	$brief				= replace_safe($_POST['brief']);
	$body				= replace_safe($_POST['body']);
	$product_id			= $_POST['product_id'];
	$price				= $_POST['price'];
	$total				= $_POST['total'];
	$content			= $_POST['content'];
	$sum				= intval($_POST['sum']);
	$price_main			= floatval($_POST['price_main']);
	$freight_side		= replace_safe($_POST['freight_side']);
	$freight_amount		= replace_safe($_POST['freight_amount']);
	$pay_method			= replace_safe($_POST['pay_method']);

	if(!isset($purchaseInfo[$supplier_id]))
	{
		$supplier_id  = 0;
	}
	if(!isset($storeInfo[$store_id]))
	{
		$store_id 	  = 0;
	}
	if(!isset($freightInfo[$freight_side]))
	{
		$freight_side = 'Supplier';
	}
	if(!isset($payInfo[$pay_method]))
	{
		$pay_method   = 'After';
	}
	$sql = "UPDATE purchase_main_info SET
			company_id 		 = '$company_id',
			supplier_id 	 = '$supplier_id',
			store_id 		 = '$store_id',
			brief 			 = '$brief',
			body 			 = '$body',
			total 			 = '$sum',
			price 		 	 = '$price_main',
			status_audit 	 = 'N',
			action_date 	 =  NOW(),
			`number` 		 = '$number',
			staff_id 		 = '$staff_id'
			WHERE company_id = '$company_id' AND id ='$id' ";

	mysql_query($sql,$_mysql_link_);
	$sql = "UPDATE finance_purchase SET
			freight_side	 = '$freight_side',
			freight_amount	 = '$freight_amount',
			pay_method 		 = '$pay_method',
			payment_total	 = '$price_main'
			WHERE company_id='$company_id' AND purchase_id='$id' ";
	mysql_query($sql,$_mysql_link_);

	$sql = "DELETE FROM purchase_product WHERE company_id = '$company_id' AND purchase_id = '$id' ";
	mysql_query($sql,$_mysql_link_);

	for($i=0;$i<count($total);$i++){
		$sql = "SELECT parts_id,value_id_1,value_id_2,value_id_3,value_id_4,value_id_5 FROM product_detail WHERE id = '$product_id[$i]' ";
			$res = mysql_query($sql,$_mysql_link_);
			$arr = mysql_fetch_object($res);
			$parts_id 				= $arr->parts_id;
			$value_id 				= array();
			$value_id['value_id_1'] = $arr->value_id_1;
			$value_id['value_id_2'] = $arr->value_id_2;
			$value_id['value_id_3'] = $arr->value_id_3;
			$value_id['value_id_4'] = $arr->value_id_4;
			$value_id['value_id_5'] = $arr->value_id_5;
			$format = array();
			for($j=1;$j<=5;$j++){
				$va = $value_id['value_id_'.$j];
				$sql = "SELECT body FROM product_format_value WHERE id = '$va' ";
				$result = mysql_query($sql,$_mysql_link_);
				while($dbRow = mysql_fetch_object($result)){
					$format['value_id_'.$j] = $dbRow->body;
				}
			}
			$format = implode(',',$format);
		$sql = "INSERT INTO purchase_product SET
		company_id  = '$company_id',
		purchase_id = '$id',
		product_id  = '$product_id[$i]',
		format 		= '$format',
		parts_id 	= '$parts_id',
		total   	= '$total[$i]',
		price 		= '$price[$i]',
		content 	= '$content[$i]' ";
		mysql_query($sql,$_mysql_link_);
	}
	header('Location:/purchase/purchase_list.php');
}
if(!empty($_POST['shipping_company']))
{
	header("Content-Type: text/html; charset=UTF-8");
	$shipping_company = replace_safe($_POST['shipping_company']);
	$purchase_id    = replace_safe($_POST['purchase_id']);
	if(!empty($shipping_company))
	{
		$sql = "UPDATE purchase_main_info SET shipping_company='$shipping_company' WHERE company_id='$company_id' AND id='$purchase_id'";
		mysql_query($sql,$_mysql_link_);
		$sql = "UPDATE finance_purchase SET shipping_company='$shipping_company' WHERE company_id='$company_id' AND purchase_id='$purchase_id'";
		mysql_query($sql,$_mysql_link_);
	}
	exit;
}
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

$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");
