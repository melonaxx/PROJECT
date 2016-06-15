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

// 库存状况  搜索条件仓库下拉
$sql = "SELECT name,id FROM store_info WHERE company_id = '$company_id' AND store_status <> 'Delete' ORDER BY id DESC";
$result = mysql_query($sql,$_mysql_link_);
while($StoreInfo = mysql_fetch_object($result)){
	$store_info = array();
	$store_info['name']	=	$StoreInfo->name;
	$store_info['id']	=	$StoreInfo->id;
	$xtpl->assign("store_info", $store_info);
	$xtpl->parse("main.store_info");
}
// 商品状况  搜索条件类别下拉
$sql = "SELECT name,id FROM product_category WHERE company_id = '$company_id' AND is_delete = 'N'";
$result = mysql_query($sql,$_mysql_link_);
while($productCategory  = mysql_fetch_object($result)){
	$product_category  			= array();
	$product_category['name']	=	$productCategory->name;
	$product_category['id']		=	$productCategory->id;
	$xtpl->assign("product_category", $product_category);
	$xtpl->parse("main.product_category");
}
// 商品状况  搜索条件品牌下拉
$sql = "SELECT id, name, parent_id FROM product_brand WHERE company_id='{$company_id}' AND is_delete='N'";
$result	= mysql_query($sql, $_mysql_link_);
$GroupList = array();
while ($rows = mysql_fetch_object($result))
{
	$GroupList[$rows->parent_id][$rows->id]	= $rows->name;
}
$GroupList	= get_sort_by_array($GroupList);

$main['brand_total'] = count($GroupList);
if (!empty($GroupList)) {
	foreach ($GroupList as $key=>$value) {
		$value['name'] = str_repeat("　", $value['level'] - 1).$value['name'];
		$xtpl->assign("brand", $value);
		$xtpl->parse("main.brand");
	}
}

//---- 数量 ----
//---- 设置查询条件 ----
$addon 		= 	array();
$addon[]	=	"r.company_id= '$company_id'";

// if(!empty($_REQUEST['find']))
// {
// 	$find = replace_safe($_REQUEST['find']);
// 	if(!empty($find)){
// 		// ---- 设置查询条件：只允许查商品名和编码 ----
// 		$addon[]		= "( INSTR(i.name, '$find') OR INSTR(i.number, '$find') )";
// 		$main['find']	= $find;
// 		$page_param		= array();
// 		$page_param['find']		= replace_safe($_REQUEST['find'], 20, false, false);
// 	}
// }
// //根据传过来的id查询
// if(!empty($_REQUEST['id'])){
// 	$id = intval($_REQUEST['id']);
// 	if(!empty($id)){
// 		$addon[] = "r.product_id = '$id'";
// 		$page_param		= array();
// 		$page_param['id']		= replace_safe($_REQUEST['id'], 20, false, false);
// 	}
// }
//  if(!empty($_REQUEST['brand'])){
// 	$brand = replace_safe($_REQUEST['brand']);
// 	if(!empty($brand)){
// 		// ---- 设置查询条件：只允许查询品牌 ----
// 		$addon[]	=	"i.brand_id='$brand'";
// 		$main['brand']	= $brand;
// 		$page_param		= array();
// 		$page_param['brand']		= replace_safe($_REQUEST['brand'], 20, false, false);
// 	}
// }
if(!empty($_REQUEST['ware'])){
	$ware = replace_safe($_REQUEST['ware']);
	if(!empty($ware)){
		// ---- 设置查询条件：只允许查询仓库 ----
		$addon[]	=	"r.store_id = '$ware'";
		$main['ware'] = $ware;
		$page_param		= array();
		$page_param['ware']		= replace_safe($_REQUEST['ware'], 20, false, false);
	}
}else{
	$sql = "SELECT id FROM store_info WHERE company_id = '$company_id' AND store_status <> 'Delete' ORDER BY id DESC LIMIT 1 ";
	$result_info = mysql_query($sql,$_mysql_link_);
	if(mysql_num_rows($result_info)==1)
	{
		$storeinfo = mysql_result($result_info,0,0);
		$addon[] = "r.store_id = '".$storeinfo."'";
	}
}
// if(!empty($_REQUEST['classification'])){
// 	$classification = replace_safe($_REQUEST['classification']);
// 	if(!empty($classification)){
// 		// ---- 设置查询条件：只允许查询分类 ----
// 		$addon[]	=	"i.category_id='$classification'";
// 		$main['classification']	= $classification;
// 		$page_param		= array();
// 		$page_param['classification']		= replace_safe($_REQUEST['classification'], 20, false, false);
// 	}
// }

$where = '';
if(count($addon) > 0)
{
	$where	= "WHERE ".implode(" AND ", $addon);
}

// $price_zong = 0;//定义仓库商品总值
// $total_zong = 0;//定义仓库商品总数
// $sql = "SELECT r.real_total,r.cost FROM store_related AS r ".$where;
// $result = mysql_query($sql,$_mysql_link_);

// while($dbRow = mysql_fetch_object($result)){
// 	$arr = array();
// 	$arr['real_total'] = $dbRow->real_total;
// 	$arr['cost'] = $dbRow->cost;
// 	$total_zong += intval($arr['real_total']);
// 	$price_zong += intval($arr['real_total'])*floatval($arr['cost']);
// }
// $main['price_zong'] = $price_zong;
// $main['total_zong'] = $total_zong;

// ---- 数量 ----
$sql = "SELECT COUNT(*) as total FROM store_product AS r ".$where;
$result	= mysql_query($sql, $_mysql_link_);
$main['total']	= mysql_result($result, 0, 'total');
//---- 处理分页 ----
if(!is_array($page_param)){
	$page_param	=	array();
}
$main['page_info']	= erp_page_info($main['total'], $page, $page_param);
$limit	= ($page - 1) * $_SESSION["_application_info_"]["page_size"].", ".$_SESSION["_application_info_"]["page_size"];
//---- 数量大于0 ----
if(!empty($_REQUEST['sales_status'])){
	if($_REQUEST['sales_status']=="All"){
		$where = $where;
	}else{
		$where = $where."AND s.sales_status = '".$_REQUEST['sales_status']."'";
	}

}else{
	$where = $where."AND s.sales_status = 'Onsale'";
}
$main['sales_status'] = $_REQUEST['sales_status'];

if($main['total']>0){
	$FormatValue	= array();
	$sql =	"SELECT id, body FROM product_format_value WHERE company_id = '$company_id'";
	$result = mysql_query($sql,$_mysql_link_);
	while($x = mysql_fetch_object($result)){
		$FormatValue[$x->id]	= $x->body;
	}
	$sql =	"SELECT i.image,i.name,i.number,r.total_real,r.total_available,r.total_lock,r.total_way,r.product_id,d.price_purchase,d.value_id_1,d.value_id_2,d.value_id_3,d.value_id_4,d.value_id_5 FROM store_product AS r LEFT JOIN  product_info AS i ON r.product_id = i.id LEFT JOIN product_detail AS d ON r.product_id = d.id LEFT JOIN product_sales AS s ON r.product_id=s.id ".$where." LIMIT ".$limit;
	$result = mysql_query($sql,$_mysql_link_);
	$num = 1;//定义序号
	while($StoreInfo = mysql_fetch_object($result))
	{
		$list_store = array();
		$list_store['num']				= $num++;
		$list_store['name']				= $StoreInfo->name;
		$list_store['image']			= $StoreInfo->image;
		$list_store['total_way']		= $StoreInfo->total_way;
		$list_store['id']				= $StoreInfo->product_id;
		$list_store['total_real']		= $StoreInfo->total_real;
		$list_store['total_lock']		= $StoreInfo->total_lock;
		$list_store['price_purchase']	= $StoreInfo->price_purchase;
		$list_store['total_available']	= $StoreInfo->total_available;
		$list_store['number']			= $StoreInfo->number;
		$value_1						= $FormatValue[$StoreInfo->value_id_1];
		$value_2						= $FormatValue[$StoreInfo->value_id_2];
		$value_3						= $FormatValue[$StoreInfo->value_id_3];
		$value_4						= $FormatValue[$StoreInfo->value_id_4];
		$value_5						= $FormatValue[$StoreInfo->value_id_5];
		$format	= $value_1.','.$value_2.','.$value_3.','.$value_4.','.$value_5;
		$list_store['format']			= rtrim($format,',');
		$list_store['available']		= $StoreInfo->total - $StoreInfo->lock_total;
		$list_store['price_total']		= floatval($list_store['price_purchase'])*intval($list_store['total_real']);
		$xtpl->assign("list_store", $list_store);
		$xtpl->parse("main.list_store");

	}
}



$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");
