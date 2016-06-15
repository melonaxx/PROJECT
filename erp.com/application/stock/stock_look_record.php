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

$type['Input']	=	"出库";
$type['Output']	=	"入库";

$company_id = $_SESSION['_application_info_']['company_id'];
if(!empty($_GET['id'])){
	$id = $_GET['id'];
	$sql = "SELECT total,type,action_date,staff_id,product_id,body,store_id,area_id,shelves_id,location_id FROM store_operation_logs WHERE id = '$id' AND company_id = '$company_id'";
	$result = mysql_query($sql,$_mysql_link_);
	$store_opeartion = mysql_fetch_object($result);
	$main['id']			= $id;
	$main['type'] 		= $type[$store_opeartion->type];
	$main['total']		= $store_opeartion->total;
	$main['action_date']= $store_opeartion->action_date;
	$main['body']		= $store_opeartion->body;
	// --- 查询操作人 ---
	$sql_staff = "SELECT nick FROM company_staff_info WHERE id = '{$store_opeartion->staff_id}'";
	$result_staff = mysql_query($sql_staff,$_mysql_link_);
	$sql_result = mysql_fetch_object($result_staff);
	$main['staff_id']	= $sql_result->nick;

	//--- 查询商品名称 ---
	$sql_product = "SELECT name FROM product_info WHERE id = '{$store_opeartion->product_id}' AND is_delete = 'N'";
	$result_product = mysql_query($sql_product,$_mysql_link_);
	$sql_result = mysql_fetch_object($result_product);
	$main['product_id']	= $sql_result->name;
	// ---查询商品规格---
	$sql_format = "SELECT id,body FROM product_format_value WHERE company_id = '$company_id' ";
	$result = mysql_query($sql_format,$_mysql_link_);
	$format_value = array();
	while($dbRow = mysql_fetch_object($result)){
		$format_value[$dbRow->id] = $dbRow->body;
	}
	$sql_value = "SELECT value_id_1,value_id_2,value_id_3,value_id_4,value_id_5 FROM product_detail WHERE id = '{$store_opeartion->product_id}' ";
	$result_value = mysql_query($sql_value,$_mysql_link_);
	$sql_result = mysql_fetch_object($result_value);
	$value_1 = $format_value[$sql_result->value_id_1];
	$value_2 = $format_value[$sql_result->value_id_2];
	$value_3 = $format_value[$sql_result->value_id_3];
	$value_4 = $format_value[$sql_result->value_id_4];
	$value_5 = $format_value[$sql_result->value_id_5];
	$format = $value_1.",".$value_2.",".$value_3.",".$value_4.",".$value_5;
	$main['format'] = rtrim($format,',');

	//---- 查询仓库 ----
	$sql_store = "SELECT name FROM store_info WHERE id = '{$store_opeartion->store_id}' AND store_status <> 'Delete' AND company_id = '$company_id'";
	$result_store = mysql_query($sql_store,$_mysql_link_);
	$sql_result = mysql_fetch_object($result_store);
	$main['store_id']	= $sql_result->name;

	// //---- 查询库区 ----
	// $sql_area = "SELECT name FROM store_location WHERE id = '{$store_opeartion->area_id}' AND location_type = 'Area' AND company_id = '$company_id'";
	// $result_store = mysql_query($sql_area,$_mysql_link_);
	// $sql_result = mysql_fetch_object($result_store);
	// $main['area_id']	= $sql_result->name;

	// //--- 查询货位 ----
	// $sql_shelves = "SELECT name FROM store_location WHERE id = '{$store_opeartion->shelves_id}' AND location_type = 'Shelves' AND company_id = '$company_id'";
	// $result_shelves = mysql_query($sql_shelves,$_mysql_link_);
	// $sql_result = mysql_fetch_object($result_shelves);
	// $main['shelves_id']	= $sql_result->name;

	// // ---- 查询货架 ----
	// $sql_location = "SELECT name FROM store_location WHERE id = '{$store_opeartion->location_id}' AND location_type = 'Location' AND company_id = '$company_id'";
	// $result_location = mysql_query($sql_location,$_mysql_link_);
	// $sql_result = mysql_fetch_object($result_location);
	// $main['location_id'] = $sql_result->name;
}

//修改备注
if(!empty($_POST['body']))
{
	$id = intval($_POST['id']);
	$body = replace_safe($_POST['body']);
	if(!empty($body))
	{
		$sql = "UPDATE store_operation_logs SET body='$body' WHERE company_id='$company_id' AND id='$id' ";
		mysql_query($sql,$_mysql_link_);
	}
	exit;
}

$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");