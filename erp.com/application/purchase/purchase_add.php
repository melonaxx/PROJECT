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
$purchaseInfo 	= array();
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
$storeInfo 			= array();
while($store_info 	= mysql_fetch_object($result)){
	$store 			= array();
	$store['id']	=	$store_info->id;
	$store['name']	=	$store_info->name;
	$xtpl->assign("store", $store);
	$xtpl->parse("main.store");
	$storeInfo[$store_info->id] = $store_info->name;
}
//按搜索获取商品
if(!empty($_POST['value'])){
	$value = replace_safe($_POST['value']);
	$rows = intval($_POST['cc']);
	$array = explode(",",replace_safe($_POST['bb']));
	$addon	= array();
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
	$sql = "SELECT id,body FROM product_format_value WHERE company_id='$company_id' ";
	$res = mysql_query($sql,$_mysql_link_);
	$format_value = array();
	while($dbRow = mysql_fetch_object($res))
	{
		$format_value[$dbRow->id] = $dbRow->body;
	}

	$sql = "SELECT product_info.id,product_info.name,product_detail.price_purchase,product_detail.parts_id ,product_detail.value_id_1,product_detail.value_id_2,product_detail.value_id_3,product_detail.value_id_4,product_detail.value_id_5
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
		$format  = ",".$value_1.",".$value_2.",".$value_3.",".$value_4.",".$value_5;
		$arr[] = array(
		'name' 		     => $StoreInfo->name,
		'id'   		     => $StoreInfo->id,
		'price_purchase' => $StoreInfo->price_purchase,
		'part_name'	   	 => trim($res->name,' '),
		'format'  		 => rtrim($format,',')
		);
	}
	echo json_encode($arr);
	exit;
}
//商品改变
if(!empty($_POST['guige'])){
	$guige = replace_safe($_POST['guige']);
	$sql = "SELECT product_info.id,product_info.name,product_detail.parts_id,product_detail.price_purchase  FROM product_info LEFT JOIN product_detail ON product_info.id = product_detail.id WHERE product_info.id= '$guige' AND product_info.company_id='$company_id'";
	$result = mysql_query($sql,$_mysql_link_);
	while($StoreInfo = mysql_fetch_object($result)){
		$arr = array();
		$sql = "SELECT name FROM product_parts_name WHERE id='$StoreInfo->parts_id' AND company_id='$company_id'";
		$result2 = mysql_query($sql,$_mysql_link_);
		$res = mysql_fetch_object($result2);
		$arr['id'] 				= $StoreInfo->id;
		$arr['name'] 			= $StoreInfo->name;
		$arr['unit'] 			= $res->name;
		$arr['price_purchase'] 	= $StoreInfo->price_purchase;
	}
	echo json_encode($arr);
	exit;
}
$freightInfo 			 = array();
$freightInfo['Supplier'] = '供应商';
$freightInfo['Company']  = '本公司';
$payInfo				 = array();
$payInfo['After']	 	 = '后付款';
$payInfo['Deposit']	 	 = '订金加尾款';
$payInfo['New']		 	 = '先付款';
if(!empty($_POST['send'])){
	$number 			= time();
	$price				= $_POST['price'];
	$total				= $_POST['total'];
	$content			= $_POST['content'];
	$product_id			= $_POST['product_id'];
	$sum				= intval($_POST['sum']);
	$store_id			= intval($_POST['store_id']);
	$body				= replace_safe($_POST['body']);
	$price_main			= floatval($_POST['price_main']);
	$supplier_id 		= intval($_POST['supplier_id']);
	$brief				= replace_safe($_POST['brief']);
	$freight_side		= replace_safe($_POST['freight_side']);
	$freight_amount		= replace_safe($_POST['freight_amount']);
	$shipping_company	= replace_safe($_POST['shipping_company']);
	$waybill_number		= replace_safe($_POST['waybill_number']);
	$pay_method			= replace_safe($_POST['pay_method']);

	if(!isset($purchaseInfo[$supplier_id]))
	{
		$supplier_id  = 0;
	}
	if(!isset($storeInfo[$store_id]))
	{
		$store_id     = 0;
	}
	if(!isset($freightInfo[$freight_side]))
	{
		$freight_side = 'Supplier';
	}
	if(!isset($payInfo[$pay_method]))
	{
		$pay_method   = 'After';
	}
	//添加采购单信息
	$sql = "INSERT INTO purchase_main_info SET
			company_id 		 = '$company_id',
			supplier_id 	 = '$supplier_id',
			store_id 		 = '$store_id',
			brief 			 = '$brief',
			body 			 = '$body',
			total 			 = '$sum',
			price 		 	 = '$price_main',
			status_audit 	 = 'N',
			status_receipt 	 = 'N',
			status_refund 	 = 'N',
			shipping_company = '$shipping_company',
			waybill_number 	 = '$waybill_number',
			action_date 	 =  NOW(),
			`number` 		 = '$number',
			staff_id 		 = '$staff_id'";
	mysql_query($sql,$_mysql_link_);
	$id = mysql_insert_id($_mysql_link_);
	//添加采购财务信息
	$sql = "INSERT INTO finance_purchase SET
			company_id		 = '$company_id',
			purchase_id	 	 = '$id',
			freight_side	 = '$freight_side',
			freight_amount	 = '$freight_amount',
			payment_total 	 = '$price_main',
			shipping_company = '$shipping_company',
			`number`		 = '$waybill_number',
			pay_method 		 = '$pay_method',
			status 			 = 'N'";
	mysql_query($sql,$_mysql_link_);

	if(mysql_affected_rows($_mysql_link_) == 1){
		for($i=0;$i<count($total);$i++){
			$sql = "SELECT parts_id,value_id_1,value_id_2,value_id_3,value_id_4,value_id_5 FROM product_detail WHERE id = '$product_id[$i]' ";
			$res = mysql_query($sql,$_mysql_link_);
			$arr = mysql_fetch_object($res);
			$parts_id = $arr->parts_id;
			$value_id = array();
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
			parts_id  	= '$parts_id',
			format  	= '$format',
			total   	= '$total[$i]',
			price 		= '$price[$i]',
			content 	= '$content[$i]' ";
			mysql_query($sql,$_mysql_link_);
		}
	}
	header('location:/purchase/purchase_list.php');
}
$main['business_date'] = date("Y-m-d");
$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");
