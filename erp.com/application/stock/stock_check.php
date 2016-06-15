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


//库存盘点搜索条件中的仓库
$sql_ware = "SELECT name,id FROM store_info WHERE company_id = '$company_id' AND store_status != 'Delete' ORDER BY id DESC ";
$result_ware = mysql_query($sql_ware,$_mysql_link_);
while($ware = mysql_fetch_object($result_ware)){
	$store_ware = array();
	$store_ware['name']	=	$ware->name;
	$store_ware['id']	=	$ware->id;
	$xtpl->assign("store_ware",$store_ware);
	$xtpl->parse("main.store_ware");
}
//库存盘点搜索条件中的分类
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

// ---- 设置查询条件 ----
$addon = array();
$addon[] = "p.company_id = '".$company_id."'";
// $addon[] = "i.is_delete = 'N'";

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
if(!empty($_REQUEST['ware'])){
	$ware = replace_safe($_REQUEST['ware']);
	if(!empty($ware)){
		//---- 设置查询条件：只允许查询仓库 ----
		$addon[]	=	"p.store_id = '".$ware."'";
		$main['warehouse'] = $ware;
		$page_param		= array();
		$page_param['ware']		= replace_safe($_REQUEST['ware'], 20, false, false);
	}
}else{
	$sql = "SELECT id FROM store_info WHERE company_id = '$company_id' AND store_status <> 'Delete' ORDER BY id DESC ";
	$result_info = mysql_query($sql,$_mysql_link_);
	if(mysql_fetch_row($result_info)>0)
	{
		$storeinfo = mysql_result($result_info,0,0);
		$addon[] = "p.store_id = '".$storeinfo."'";
		$main['ware'] = $storeinfo->name;
	}
}
if(!empty($_REQUEST['classification'])){
	$classification = replace_safe($_REQUEST['classification']);
	if(!empty($classification)){
		// ---- 设置查询条件：只允许查询分类 ----
		$addon[]	=	"i.category_id='$classification'";
		$main['classification']	= $classification;
		$page_param		= array();
		$page_param['classification']		= replace_safe($_REQUEST['classification'], 20, false, false);
	}
}
if(!empty($_REQUEST['brand'])){
	$brand = replace_safe($_REQUEST['brand']);
	if(!empty($brand)){
		// ---- 设置查询条件：只允许查询品牌 ----
		$addon[]	=	"i.brand_id='$brand'";
		$main['brand']	= $brand;
		$page_param		= array();
		$page_param['brand']		= replace_safe($_REQUEST['brand'], 20, false, false);
	}
}
$where = '';
if(count($addon) > 0){
	$where = "WHERE ".implode(" AND ", $addon);
}
//---- 数量 ----
$sql = "SELECT count(*) AS total FROM store_product AS p ".$where;
$result	= mysql_query($sql, $_mysql_link_);
$main['total']	= mysql_result($result, 0, 'total');
//---- 处理分页 ----
if(!is_array($page_param)){
	$page_param	=	array();
}
$main['page_info']	= erp_page_info($main['total'], $page, $page_param);
$limit	= ($page - 1) * $_SESSION["_application_info_"]["page_size"].", ".$_SESSION["_application_info_"]["page_size"];
//---- 数量大于0 ----
if($main['total']>0){
	$FormatValue	= array();
	$sql =	"SELECT id, body FROM product_format_value WHERE company_id = '$company_id'";
	$result = mysql_query($sql,$_mysql_link_);
	while($x = mysql_fetch_object($result))
	{
		$FormatValue[$x->id]	= $x->body;
	}
	$sql =	"SELECT i.id,i.name,i.image,i.number,p.total_real,d.value_id_1,d.value_id_2,d.value_id_3,d.value_id_4,d.value_id_5 FROM store_product AS p INNER JOIN product_info AS i ON p.product_id = i.id LEFT JOIN product_detail AS d ON p.product_id = d.id ".$where." LIMIT ".$limit;
	$result = mysql_query($sql,$_mysql_link_);
	$num = 1;
	while($StoreInfo = mysql_fetch_object($result)){
		$list_store = array();
		$list_store['num']		= 	$num++;
		$list_store['id']		=	$StoreInfo->id;
		$list_store['name']		= 	$StoreInfo->name;
		$list_store['image']	= 	$StoreInfo->image;
		$list_store['total']	=	$StoreInfo->total_real;
		$list_store['number']	= 	$StoreInfo->number;
		$value_1				= 	$FormatValue[$StoreInfo->value_id_1];
		$value_2				= 	$FormatValue[$StoreInfo->value_id_2];
		$value_3				= 	$FormatValue[$StoreInfo->value_id_3];
		$value_4				= 	$FormatValue[$StoreInfo->value_id_4];
		$value_5				= 	$FormatValue[$StoreInfo->value_id_5];
		$format = $value_1.','.$value_2.','.$value_3.','.$value_4.','.$value_5;
		$list_store['format'] 	= 	rtrim($format,',');
		$xtpl->assign("list_store", $list_store);
		$xtpl->parse("main.list_store");
	}
}




$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");