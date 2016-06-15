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
$staff_id	= $_SESSION['_application_info_']['staff_id'];

$sql = "SELECT id,name FROM store_info WHERE company_id = '$company_id' AND store_status <> 'Delete' ORDER BY id DESC";
$result    = mysql_query($sql,$_mysql_link_);
$storeInfo = array();
while($row = mysql_fetch_object($result)){
	$store 					= array();
	$store['id'] 			= $row->id;
	$store['name'] 			= $row->name;
	$xtpl->assign("store", $store);
	$xtpl->parse("main.store");
	$storeInfo[$row->id] 	= $row->name;
}
if(!empty($_POST['value'])){
	$value = replace_safe($_POST['value']);
	$rows = intval($_POST['rows']);
	$array = explode(",",replace_safe($_POST['arr']));
	$addon	= array();
	$addon[]	= "i.company_id = '$company_id'";
	$addon[]	= "i.is_delete='N'";
	$addon[]	= "i.have_combination='N'";
	$addon[]	= "INSTR(i.name,'$value')";
	if($rows > 2){
		for($j=0;$j<count($array);$j++){
			if($array[$j]){
				$addon[] = "i.id <>".$array[$j];
			}
		}
	}
	$where  = "";
	if(count($addon)>0){
		$where .= "WHERE ".implode(" AND ", $addon);
	}

	$sql="SELECT id,body FROM product_format_value";
	$result=mysql_query($sql, $_mysql_link_);
	while ($x = mysql_fetch_object($result)) {
		$FormatValue[$x->id]	=$x->body;

	}
	$sql = "SELECT i.id,i.name,d.value_id_1,d.value_id_2,d.value_id_3,d.value_id_4,d.value_id_5 FROM product_info AS i LEFT JOIN product_detail AS d ON i.id = d.id ".$where." LIMIT 15";
	$result = mysql_query($sql,$_mysql_link_);
	while($cake = mysql_fetch_object($result)){
		$value_1 = $FormatValue[$cake->value_id_1];
		$value_2 = $FormatValue[$cake->value_id_2];
		$value_3 = $FormatValue[$cake->value_id_3];
		$value_4 = $FormatValue[$cake->value_id_4];
		$value_5 = $FormatValue[$cake->value_id_5];
		$format  = ','.$value_1.','.$value_2.','.$value_3.','.$value_4.','.$value_5;
		$data[] = array(
			'format' 	 => rtrim($format,','),
			'name' 		 => $cake->name,
			'id'   		 => $cake->id
		);
	}
	echo json_encode($data);
	exit;
}
$typeInfo 			= array();
$typeInfo['Input']  = '入库';
$typeInfo['Output'] = '出库';
if(!empty($_POST['tijiao'])){
	$type 			= replace_safe($_POST['type']);
	$store_id 		= intval($_POST['store_id']);
	$action_date	= date("Y-m-d H:i:s");
	if(!isset($typeInfo[$type]))
	{
		$type = '';
	}
	if(!isset($storeInfo[$store_id]))
	{
		$store_id = 0;
	}
	if(count($_POST['product_id']) >0){
		$length = count($_POST['product_id']);
		if($_POST['type'] == 'Input'){
			for($i=0;$i<$length;$i++){
				$product_id = intval($_POST['product_id'][$i]);
				$total = intval($_POST['total'][$i]);
				//修改store_product表中数据
				$sql = "SELECT id FROM store_product WHERE company_id='$company_id' AND store_id = '$store_id' AND product_id = '$product_id'";
				$result = mysql_query($sql,$_mysql_link_);
				if(mysql_num_rows($result) == 0){
					$sql = "INSERT INTO store_product SET company_id = '$company_id',store_id = '$store_id',product_id = '$product_id',total_real = '$total',total_available = '$total'";
					mysql_query($sql,$_mysql_link_);
				}else{
					$sql = "UPDATE store_product SET total_real = total_real+'$total',total_available=total_available+'$total' WHERE company_id='$company_id' AND store_id = '$store_id' AND product_id = '$product_id'";
					mysql_query($sql,$_mysql_link_);
				}
				// 修改store_related表中数据
				$sql = "SELECT id FROM store_related WHERE company_id='$company_id' AND store_id = '$store_id' AND product_id = '$product_id'";
				$result = mysql_query($sql,$_mysql_link_);
				if(mysql_num_rows($result) == 0){
					$sql = "INSERT INTO store_related SET company_id = '$company_id',store_id = '$store_id',product_id = '$product_id',real_total = '$total',available_total = '$total'";
					mysql_query($sql,$_mysql_link_);
				}else{
					$real_total = intval($_POST['total'][$i]);
					$available_total = intval($_POST['total'][$i]);
					$sql = "UPDATE store_related SET real_total = real_total+'$total',available_total=available_total+'$total' WHERE company_id='$company_id' AND store_id = '$store_id' AND product_id = '$product_id'";
					mysql_query($sql,$_mysql_link_);
				}
				//修改商品表中数据
				$sql_update = "UPDATE  product_info SET total = total+'$total' WHERE company_id='$company_id' AND id = '$product_id'";
				mysql_query($sql_update,$_mysql_link_);
			}
		}else if($_POST['type'] == 'Output'){
			$number = count($_POST['product_id']);
			for($i=0;$i<$number;$i++){
				$product_id = intval($_POST['product_id'][$i]);
				$total = intval($_POST['total'][$i]);
				//修改store_product表中数据
				$sql = "SELECT id FROM store_product WHERE company_id='$company_id' AND store_id = '$store_id' AND product_id = '$product_id'";
				$result = mysql_query($sql,$_mysql_link_);
				if(mysql_num_rows($result) == 0){
					$sql = "INSERT INTO store_product SET company_id = '$company_id',store_id = '$store_id',product_id = '$product_id',total_real = -'$total',total_available = -'$total'";
					mysql_query($sql,$_mysql_link_);
				}else{
					$sql_related = "UPDATE store_product SET total_real = total_real-'$total',total_available=total_available-'$total' WHERE company_id='$company_id' AND store_id = '$store_id' AND product_id = '$product_id'";
					mysql_query($sql_related,$_mysql_link_);
				}
				// 修改store_related表中数据
				$sql = "SELECT id FROM store_related WHERE company_id='$company_id' AND store_id = '$store_id' AND product_id = '$product_id'";
				$result = mysql_query($sql,$_mysql_link_);
				if(mysql_num_rows($result)==0)
				{
					$sql = "INSERT INTO store_related SET company_id = '$company_id',store_id = '$store_id',product_id = '$product_id',real_total = -'$total',available_total = -'$total'";
					mysql_query($sql,$_mysql_link_);
				}else{
					$sql = "UPDATE store_related SET real_total = real_total-'$total',available_total=available_total-'$total' WHERE company_id='$company_id' AND store_id = '$store_id' AND product_id = '$product_id'";
					mysql_query($sql,$_mysql_link_);
				}
				//修改商品表中数据
				$sql_update = "UPDATE  product_info SET total = total-'$total' WHERE company_id='$company_id' AND id = '$product_id'";
				mysql_query($sql_update,$_mysql_link_);
			}
		}

		for($i=0;$i<$length;$i++){
			$sql = "INSERT INTO store_operation_logs SET staff_id = '$staff_id',store_id = '$store_id',product_id = '{$_POST['product_id'][$i]}',type = '$type',total = '{$_POST['total'][$i]}',action_date = '$action_date',body = '{$_POST['body'][$i]}',company_id = '{$company_id}'";
			mysql_query($sql,$_mysql_link_);
		}
		header("Location:/stock/stock_manual_delivery_of_storage.php");
	}
}

$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");