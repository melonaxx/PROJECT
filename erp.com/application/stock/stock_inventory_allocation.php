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
$staff_id 	= $_SESSION['_application_info_']['staff_id'];
if(!empty($_POST['name'])){
	$name = $_POST['name'];
	$sql = "SELECT id,name FROM store_info WHERE company_id = '$company_id' AND store_status <> 'Delete' AND id <> '$name'";
	$result = mysql_query($sql,$_mysql_link_);
	while($sting = mysql_fetch_object($result)){
		$data[] = array(
			'name' => $sting->name,
			'id'   => $sting->id
		);
	}
	if(count($data)>0){
		echo json_encode($data);
		exit;
	}else{
		echo json_encode(0);
		exit;
	}
}
if(!empty($_POST['text'])){
	$text = replace_safe($_POST['text']);
	$rows = intval($_POST['rows']);
	$array = explode(",",replace_safe($_POST['arr']));
	$addon	= array();
	$addon[]	= "i.company_id = '$company_id'";
	$addon[]	= "i.is_delete='N'";
	$addon[]	= "INSTR(i.name,'$text')";
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
		$format  = $value_1.",".$value_2.",".$value_3.",".$value_4.",".$value_5;
		$data[] = array(
			'format' 	 => rtrim($format,','),
			'name' 		 => $cake->name,
			'id'   		 => $cake->id
		);
	}
	echo json_encode($data);
	exit;
}
//调拨单查询发货仓库
$sql = "SELECT id,name FROM store_info WHERE company_id = '$company_id' AND store_status <> 'Delete'";
$result 	 = mysql_query($sql,$_mysql_link_);
$storeInfo   = array();
while($Store = mysql_fetch_object($result)){
	$store_info 			= array();
	$store_info['id'] 		= $Store->id;
	$store_info['name']		= $Store->name;
	$xtpl->assign("store_info", $store_info);
	$xtpl->parse("main.store_info");
	$storeInfo[$Store->id] 	= $Store->name;
}
//调拨单查询收货仓库
$sql_ware = "SELECT id,name FROM store_info WHERE company_id = '$company_id' AND store_status <> 'Delete'";
$result_ware = mysql_query($sql_ware,$_mysql_link_);
while($store_ware = mysql_fetch_object($result_ware)){
	$ware = array();
	$ware['id']		= $store_ware->id;
	$ware['name']	= $store_ware->name;
	$xtpl->assign("ware",$ware);
	$xtpl->parse("main.ware");
}
$main['date'] = date("Y-m-d H:i:s");
if(!empty($_POST['send'])){
	if(count($_POST['product_id']) > 0){
		$store_out_id = intval($_POST['delivery_warehouse']);
		$store_in_id  = intval($_POST['warehouse_receipt']);
		if(!isset($storeInfo[$store_out_id]) || !isset($storeInfo[$store_in_id]))
		{
			$store_out_id = 0;
			$store_in_id  = 0;
		}
		if($store_out_id == $store_in_id)
		{
			header('Content-Type: text/html; Charset=UTF-8');
			echo "<script>alert('调出仓库与调入仓库不能为同一仓库!');window.location.href='/stock/stock_inventory_allocation.php';</script>";
			exit;
		}
		$length = count($_POST['product_id']);
		for($i=0;$i<$length;$i++){
			$total 		= intval($_POST['total'][$i]);
			$body 		= replace_safe($_POST['body'][$i]);
			$product_id = intval($_POST['product_id'][$i]);
			//修改store_product 表中数据
			$sql = "SELECT id FROM store_product WHERE company_id='$company_id' AND store_id = '$store_out_id' AND product_id = '$product_id'";
			$result=mysql_query($sql,$_mysql_link_);
			if(mysql_num_rows($result)==0)
			{
				$sql = "INSERT INTO store_product SET company_id='$company_id',store_id='$store_out_id',product_id='$product_id',total_real=-'$total',total_available=-'$total' ";
				mysql_query($sql,$_mysql_link_);
			}else{
				$sql = "UPDATE store_product SET total_real = total_real-'$total',total_available=total_available-'$total' WHERE company_id='$company_id' AND store_id = '$store_out_id' AND product_id = '$product_id'";
				mysql_query($sql,$_mysql_link_);
				if(mysql_affected_rows($_mysql_link_) != 0)
				{
					$sql = "SELECT id,total_real,total_available FROM store_product WHERE company_id = '$company_id' AND store_id = '$store_in_id' AND product_id = '$product_id'";
					$result = mysql_query($sql,$_mysql_link_);
					if(mysql_num_rows($result) == 0){
						$sql = "INSERT INTO store_product SET company_id = '$company_id',store_id = '$store_in_id',product_id = 'product_id',total_real = '$total',total_available = '$total' ";
						mysql_query($sql,$_mysql_link_);
					}else{
						while($storerelated = mysql_fetch_object($result)){
							$sql = "UPDATE store_product SET total_real = total_real+'$total',total_available=total_available+'$total' WHERE company_id='$company_id' AND store_id='$store_in_id' AND product_id='$product_id' ";
							mysql_query($sql,$_mysql_link_);
						}
					}
				}
			}

			//修改store_related表中数据
			$sql = "SELECT id FROM store_related WHERE company_id='$company_id' AND store_id = '$store_out_id' AND product_id = '$product_id'";
			$result=mysql_query($sql,$_mysql_link_);
			if(mysql_num_rows($result)==0)
			{
				$sql = "INSERT INTO store_related SET company_id='$company_id',store_id='$store_out_id',product_id='$product_id',real_total=-'$total',available_total=-'$total' ";
				mysql_query($sql,$_mysql_link_);
			}else{
				$sql = "UPDATE store_related SET real_total = real_total-'$total',available_total=available_total-'$total' WHERE company_id='$company_id' AND store_id = '$store_out_id' AND product_id = '$product_id'";
				mysql_query($sql,$_mysql_link_);
				if(mysql_affected_rows($_mysql_link_) != 0)
				{
					$sql = "SELECT id,real_total,available_total FROM store_related WHERE company_id = '$company_id' AND store_id = '$store_in_id' AND product_id = '$product_id'";
					$result = mysql_query($sql,$_mysql_link_);
					if(mysql_num_rows($result) == 0){
						$sql = "INSERT INTO store_related SET company_id = '$company_id',store_id = '$store_in_id',product_id = 'product_id',real_total = '$total',available_total = '$total' ";
						mysql_query($sql,$_mysql_link_);
					}else{
						while($storerelated = mysql_fetch_object($result)){
							$sql = "UPDATE store_related SET real_total = real_total+'$total',available_total=available_total+'$total' WHERE company_id='$company_id' AND store_id='$store_in_id' AND product_id='$product_id' ";
							mysql_query($sql,$_mysql_link_);
						}
					}
				}
			}

			if(!empty($_POST['date'])){
				$date = $_POST['date'];
			}else{
				$date = date("Y-m-d H:i:s");
			}
			$sql_insert = "INSERT INTO store_move_logs SET action_date = '$date',company_id = '$company_id',product_id = '$product_id',output_store_id = '$store_out_id',output_staff_id = $staff_id,input_store_id = '$store_in_id',input_staff_id = $staff_id,total = '$total',body = '$body'";
			mysql_query($sql_insert,$_mysql_link_);
			header("location:/stock/stock_allocation_list.php");
		}
	}
}
$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");
