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

//搜索条件中的仓库
$sql = "SELECT name,id FROM store_info WHERE company_id = '$company_id' AND store_status <> 'Delete'";
$result = mysql_query($sql,$_mysql_link_);
while($StoreInfo = mysql_fetch_object($result)){
	$store_info = array();
	$store_info['name']	=	$StoreInfo->name;
	$store_info['id']	=	$StoreInfo->id;
	$xtpl->assign("store_info", $store_info);
	$xtpl->parse("main.store_info");
}
//搜索条件中的分类
$sql = "SELECT name,id FROM product_category WHERE company_id = '$company_id' AND is_delete = 'N'";
$result = mysql_query($sql,$_mysql_link_);
while($productCategory  = mysql_fetch_object($result)){
	$product_category  = array();
	$product_category['name']	=	$productCategory->name;
	$product_category['id']		=	$productCategory->id;
	$xtpl->assign("product_category", $product_category);
	$xtpl->parse("main.product_category");
}
//搜索条件中的品牌
$sql = "SELECT id, name, parent_id FROM product_brand WHERE company_id='{$company_id}' AND is_delete='N'";
$result	= mysql_query($sql, $_mysql_link_);
$GroupList = array();
while ($rows = mysql_fetch_object($result))
{
	$GroupList[$rows->parent_id][$rows->id]	= $rows->name;
}
$GroupList	= get_sort_by_array($GroupList);
//print_r($GroupList);

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
$addon 	= 	array();
$addon[]	=	"i.company_id='".$company_id."'";
$addon[]	=	"i.is_delete = 'N'";
if(!empty($_REQUEST['find']))
{
	$find = replace_safe($_REQUEST['find']);
	if(!empty($find)){
		// ---- 设置查询条件：只允许查商品名和编码 ----
		$addon[]		= "( INSTR(i.name, '$find') OR INSTR(i.number, '$find') )";
		$main['find']	= $find;
		$page_param		= array();
		$page_param['find']		= replace_safe($_REQUEST['find'], 20, false, false);
	}
}
 if(!empty($_REQUEST['brand'])){
	$brand = replace_safe($_REQUEST['brand']);
	if(!empty($brand)){
		// ---- 设置查询条件：只允许查询品牌 ----
		$addon[]	=	"i.brand_id='".$brand."'";
		$main['brand']	= $brand;
		$page_param		= array();
		$page_param['brand']		= replace_safe($_REQUEST['brand'], 20, false, false);
	}
}
if(!empty($_REQUEST['classification'])){
	$classification = replace_safe($_REQUEST['classification']);
	if(!empty($classification)){
		// ---- 设置查询条件：只允许查询分类 ----
		$addon[]	=	"i.category_id='".$classification."'";
		$main['classification']	= $classification;
		$page_param		= array();
		$page_param['classification']		= replace_safe($_REQUEST['classification'], 20, false, false);
	}
}
if(count($addon) > 0)
{
	$where	= "WHERE ".implode(" AND ", $addon);
}

	// $total_zong = 0;//定义总的商品数量
	// $price_zong = 0;//定义总的价值
	// $sql =	"SELECT i.total,i.cost FROM product_info AS i ".$where;
	// $result = mysql_query($sql,$_mysql_link_);
	// while($dbRow = mysql_fetch_object($result)){
	// 	$total_zong += $dbRow->total;
	// 	$price_zong += $dbRow->cost * $dbRow->total;
	// }
	// $main['total_zong'] = $total_zong;
	// $main['price_zong'] = $price_zong;

// ---- 数量 ----
$sql = "SELECT COUNT(*) AS total FROM product_info AS i ".$where;
$result	= mysql_query($sql, $_mysql_link_);
$main['total']	= mysql_result($result, 0, 'total');
//---- 处理分页 ----
if(!is_array($page_param)){
	$page_param	=	array();
}
$main['page_info']	= erp_page_info($main['total'], $page, $page_param);
$limit	= ($page - 1) * $_SESSION["_application_info_"]["page_size"].", ".$_SESSION["_application_info_"]["page_size"];

if(!empty($_REQUEST['sales_status'])){
	if($_REQUEST['sales_status']=="All"){
		$where = $where;
	}else{
		$where = $where."AND s.sales_status = '".$_REQUEST['sales_status']."'";
	}
}else{
	$where = $where."AND s.sales_status ='Onsale'";
}
$main['sales_status'] = $_REQUEST['sales_status'];
//---- 数量大于0 ----
if($main['total']>0){


	$FormatValue	= array();
	$sql =	"SELECT id, body FROM product_format_value WHERE company_id = '$company_id'";
	$result = mysql_query($sql,$_mysql_link_);
	while($x = mysql_fetch_object($result)){
		$FormatValue[$x->id]	= $x->body;
	}
	$sql =	"SELECT i.id,i.image,i.name,i.number,i.total,d.price_purchase,d.value_id_1,d.value_id_2,d.value_id_3,d.value_id_4,d.value_id_5 FROM product_info AS i LEFT JOIN product_detail AS d ON i.id = d.id LEFT JOIN product_sales AS s ON i.id=s.id ".$where." LIMIT ".$limit;
	$result = mysql_query($sql,$_mysql_link_);
	$num = 1;
	while($StoreInfo = mysql_fetch_object($result))
	{
		$list_store = array();
		$list_store['num']				= $num++;
		$list_store['id']				= $StoreInfo->id;
		$list_store['name']				= $StoreInfo->name;
		$list_store['image']			= $StoreInfo->image;
		$list_store['total']			= $StoreInfo->total;
		$list_store['number']			= $StoreInfo->number;
		$list_store['price_purchase'] 	= $StoreInfo->price_purchase;
		$list_store['format_1']			= $FormatValue[$StoreInfo->value_id_1];
		$list_store['format_2']			= $FormatValue[$StoreInfo->value_id_2];
		$list_store['format_3']			= $FormatValue[$StoreInfo->value_id_3];
		$list_store['format_4']			= $FormatValue[$StoreInfo->value_id_4];
		$list_store['format_5']			= $FormatValue[$StoreInfo->value_id_5];
		$list_store['price_total']  	= $StoreInfo->price_purchase * $StoreInfo->total;

		$xtpl->assign("list_store", $list_store);
		$xtpl->parse("main.list_store");
	}
}



$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");
