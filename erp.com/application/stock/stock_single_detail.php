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
if(!empty($_GET['id'])){
	$id = intval($_GET['id']);
	$sql = "SELECT action_date,store_id,body,product_id,bill_number,total,old_total,new_total FROM store_inventory_list WHERE id = '$id' AND company_id = '$company_id'";
	$result = mysql_query($sql,$_mysql_link_);
	$store_inventory_list 	= mysql_fetch_object($result);
	$main['id']				= $id;
	$main['action_date'] 	= $store_inventory_list->action_date;
	$main['old_total'] 		= $store_inventory_list->old_total;
	$main['new_total'] 		= $store_inventory_list->new_total;
	//--- 查询仓库 ---
	$sql = "SELECT name FROM store_info WHERE company_id = '$company_id' AND store_status <> 'Delete' AND id = '{$store_inventory_list->store_id}'";
	$result_info = mysql_query($sql,$_mysql_link_);
	$store_info = mysql_fetch_object($result_info);
	$main['store_id']		= $store_info->name;

	$sql="SELECT id,body FROM product_format_value";
	$result=mysql_query($sql, $_mysql_link_);
	while ($x = mysql_fetch_object($result)) {
		$FormatValue[$x->id]	=$x->body;
	}

	//--- 查询商品名称 ---
	$sql = "SELECT i.image,i.name,d.value_id_1,d.value_id_2,d.value_id_3,d.value_id_4,d.value_id_5 FROM product_info AS i LEFT JOIN product_detail AS d ON d.id = i.id WHERE i.company_id = '$company_id' AND i.is_delete = 'N' AND i.id = '{$store_inventory_list->product_id}'";
	$result_product = mysql_query($sql,$_mysql_link_);
	$store_product = mysql_fetch_object($result_product);
	$main['image']			= $store_product->image;
	$value_1 				= $FormatValue[$store_product->value_id_1];
	$value_1  				= $FormatValue[$store_product->value_id_2];
	$value_1  				= $FormatValue[$store_product->value_id_3];
	$value_1 				= $FormatValue[$store_product->value_id_4];
	$value_1  				= $FormatValue[$store_product->value_id_5];
	$format = $value_1.",".$value_2.",".$value_3.",".$value_4.",".$value_5;
	$main['format']			= rtrim($format,',');
	$main['product_id']		= $store_product->name;
	$main['bill_number']	= $store_inventory_list->bill_number;
	$main['total']			= $store_inventory_list->total;
	$main['body']			= $store_inventory_list->body;
}
//修改备注
if(!empty($_POST['body']))
{
	$id = intval($_POST['id']);
	$body = replace_safe($_POST['body']);
	if(!empty($body))
	{
		$sql = "UPDATE store_inventory_list SET body='$body' WHERE company_id='$company_id' AND id='$id' ";
		mysql_query($sql,$_mysql_link_);
	}
}
//打印
if($_GET['action'] == 'print'){
	$arr['action_date'] 	= $main['action_date'];
	$arr['store_id'] 		= $main['store_id'];
	$arr['image'] 			= $main['image'];
	$arr['format'] 			= $main['format'];
	$arr['total'] 			= $main['total'];
	$arr['product_id'] 		= $main['product_id'];
	$arr['bill_number'] 	= $main['bill_number'];
	$arr['body'] 			= $main['body'];
	$arr['format'] 			= $main['format'];
	echo json_encode($arr);
	exit;
}
$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");