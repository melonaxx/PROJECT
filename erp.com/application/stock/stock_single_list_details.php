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
	$id = $_GET['id'];
	$sql = "SELECT total,product_id,action_date,output_staff_id,output_store_id,input_store_id,body FROM store_move_logs WHERE id = '$id' AND company_id = '$company_id'";
	$result = mysql_query($sql,$_mysql_link_);
	$store_move_logs = mysql_fetch_object($result);
	$main['action_date'] 		= $store_move_logs->action_date;
	//--- 查询操作人 ---
	$sql = "SELECT nick FROM company_staff_info WHERE id = '{$store_move_logs->output_staff_id}' AND is_valid = 'Y'";
	$result_staff = mysql_query($sql,$_mysql_link_);
	$staff_info = mysql_fetch_object($result_staff);
	$main['id']					= $id;
	$main['output_staff_id'] 	= $staff_info->nick;
	$main['body'] 				= $store_move_logs->body;
	$main['total']				= $store_move_logs->total;

	// ----- 查询仓库 -----
	$sql = "SELECT name FROM store_info WHERE id = '{$store_move_logs->output_store_id}' AND company_id = '$company_id' AND store_status <> 'Delete'";
	$result_info = mysql_query($sql,$_mysql_link_);
	$store_info = mysql_fetch_object($result_info);
	$main['output_store_id']	= $store_info->name;

	$sql = "SELECT name FROM store_info WHERE id = '{$store_move_logs->input_store_id}' AND company_id = '$company_id' AND store_status <> 'Delete'";
	$result_ware_info = mysql_query($sql,$_mysql_link_);
	$store_ware_info = mysql_fetch_object($result_ware_info);
	$main['input_store_id']		= $store_ware_info->name;

	// ---- 查询商品 ----
	$sql = "SELECT name FROM product_info WHERE id = '{$store_move_logs->product_id}' AND company_id = '$company_id' AND is_delete = 'N'";
	$result_info = mysql_query($sql,$_mysql_link_);
	$product_info = mysql_fetch_object($result_info);
	$main['product_id']	= $product_info->name;

	// ---查询商品规格---
	$sql_format = "SELECT id,body FROM product_format_value WHERE company_id = '$company_id' ";
	$result = mysql_query($sql_format,$_mysql_link_);
	$format_value = array();
	while($dbRow = mysql_fetch_object($result)){
		$format_value[$dbRow->id] = $dbRow->body;
	}
	$sql_value = "SELECT value_id_1,value_id_2,value_id_3,value_id_4,value_id_5 FROM product_detail WHERE id = '{$store_move_logs->product_id}' ";
	$result_value = mysql_query($sql_value,$_mysql_link_);
	$sql_result = mysql_fetch_object($result_value);
	$value_1 = $format_value[$sql_result->value_id_1];
	$value_2 = $format_value[$sql_result->value_id_2];
	$value_3 = $format_value[$sql_result->value_id_3];
	$value_4 = $format_value[$sql_result->value_id_4];
	$value_5 = $format_value[$sql_result->value_id_5];
	$format = $value_1.",".$value_2.",".$value_3.",".$value_4.",".$value_5;
	$main['format'] = rtrim($format,',');

}

//修改备注
if(!empty($_POST['body']))
{
	$id = intval($_POST['id']);
	$body = replace_safe($_POST['body']);
	if(!empty($body))
	{
		$sql = "UPDATE store_move_logs SET body='$body' WHERE company_id='$company_id' AND id='$id' ";
		mysql_query($sql,$_mysql_link_);
	}
	exit;
}

if($_GET['action'] == 'print'){
	$arr['action_date'] 	= $main['action_date'];
	$arr['output_staff_id'] = $main['output_staff_id'];
	$arr['body'] 			= $main['body'];
	$arr['total'] 			= $main['total'];
	$arr['output_store_id'] = $main['output_store_id'];
	$arr['input_store_id'] 	= $main['input_store_id'];
	$arr['product_id'] 		= $main['product_id'];
	$arr['format'] 			= $main['format'];
	echo json_encode($arr);
	exit;
}
$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");