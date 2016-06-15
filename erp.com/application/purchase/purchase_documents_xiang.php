<?
//---- UTF8 编码 ----
session_start();
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Content-Type: text/html; charset=UTF-8");
header("X-UA-Compatible: IE=EmulateIE7");

include "../../config.php";
include "../../xtpl.php";
include "../detect_permit.php";
include "../function.php";


$company_id = $_SESSION['_application_info_']['company_id'];

//获取所选单据信息
if(!empty($_GET['id'])){
	$id = intval($_GET['id']);
	$sql = "SELECT id,`number`,supplier_id,purchase_id,store_id,input_staff_id,action_date,total,price FROM store_input_info WHERE company_id= '$company_id' AND id = '$id' ";
	$result = mysql_query($sql,$_mysql_link_);
	while($dbRow = mysql_fetch_object($result)){
		$info = array();
		$info['id'] = $dbRow->id;
		$info['number'] = $dbRow->number;
		$info['purchase_id'] = $dbRow->purchase_id;
		$sql = "SELECT name FROM store_info WHERE company_id = '$company_id' AND store_status <> 'Delete' AND id = '{$dbRow->store_id}'";
		$result_store = mysql_query($sql,$_mysql_link_);
		while($store_info = mysql_fetch_object($result_store)){
			$info['store_id']	= $store_info->name;
		}
		$sql = "SELECT nick FROM company_staff_info WHERE company_id = '$company_id' AND is_valid = 'Y' AND id = '{$dbRow->input_staff_id}'";
		$result_staff = mysql_query($sql,$_mysql_link_);
		while($company_staff = mysql_fetch_object($result_staff)){
			$info['input_staff_id'] = $company_staff->nick;
		}
		$sql = "SELECT name FROM purchase_supplier WHERE is_delete = 'N' AND company_id = '$company_id' AND id = '{$dbRow->supplier_id}'";
		$result_supplier = mysql_query($sql,$_mysql_link_);
		while($supplier = mysql_fetch_object($result_supplier)){
			$info['supplier_id'] = $supplier->name;
		}
		$info['action_date'] = $dbRow->action_date;
		$info['total'] = $dbRow->total;
		$info['price'] = $dbRow->price;

		$xtpl->assign('info',$info);
		$xtpl->parse('main.info');
	}
	$sql = "SELECT product_id,format,parts_id,total,price,payment,body FROM store_input_product WHERE info_id = '$id' ";
	$result = mysql_query($sql,$_mysql_link_);
	$no = 1;

	$product_info = array();
	while($dbRow = mysql_fetch_object($result)){
		$product  = array();
		$sql = "SELECT name FROM product_info WHERE company_id = '$company_id' AND id = '$dbRow->product_id' ";
		$re = mysql_query($sql,$_mysql_link_);
		while($pro = mysql_fetch_object($re)){
			$product['product_name'] = $pro->name;
		}
		if($dbRow->parts_id){
			$sql = "SELECT name FROM product_parts_name WHERE company_id = '$company_id' AND id = '$dbRow->parts_id' ";
			$re = mysql_query($sql,$_mysql_link_);
			while($parts = mysql_fetch_object($re)){
				$product['parts_id'] = $parts->name;
			}
		}else{
			$product['parts_id'] = '';
		}
		$product['product_id'] 	= $dbRow->product_id;
		$product['format'] 		= $dbRow->format;
		$product['total'] 		= $dbRow->total;
		$product['price'] 		= $dbRow->price;
		$product['payment'] 	= $dbRow->payment;
		$product['body'] 		= $dbRow->body;
		$product['no'] 			= $no++;

		$xtpl->assign('product',$product);
		$xtpl->parse('main.product');
		$product_info[] = $product;
	}
}
//修改备注
if(!empty($_POST['body']))
{
	$body = replace_safe($_POST['body']);
	$info_id = intval($_POST['info_id']);
	$product_id = intval($_POST['product_id']);
	$sql = "UPDATE store_input_product SET body='$body' WHERE info_id='$info_id' AND product_id='$product_id' ";
	mysql_query($sql,$_mysql_link_);
}

if($_GET['action'] == 'print'){
	$arr['info'] = $info;
	$arr['product_info'] = $product_info;
	echo json_encode($arr);
	exit;
}

$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");