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

//---- 返回格式 ----
$format		= ($_REQUEST['format'] == "xml") ? "xml" : "json";
//---- 搜索关键词 ----
$keyword	= replace_safe($_REQUEST['keyword']);
//---- 返回数量 ----
$limit		= intval($_REQUEST['limit']);
if($limit < 1 || $limit > 100)
{
	//---- 太多或者太少 ----
	$limit	= 10;
}

if(!empty($keyword))
{
	//---- 关键词非空, 进行搜索 ----
	$JsonData	= array();

	//---- 当前公司有哪些单位 ----
	$PartsInfo	= array();
	$sql		= "SELECT id, name FROM product_parts_name WHERE company_id='".$_SESSION['_application_info_']['company_id']."'";
	$result		= mysql_query($sql, $_mysql_link_);
	while($dbRow = mysql_fetch_object($result))
	{
		$PartsInfo[$dbRow->id]	= $dbRow->name;
	}

	//---- 当前公司有哪些规格 ----
	$FormatInfo	= array();
	$sql		= "SELECT id, body FROM product_format_value WHERE company_id='".$_SESSION['_application_info_']['company_id']."'";
	$result		= mysql_query($sql, $_mysql_link_);
	while($dbRow = mysql_fetch_object($result))
	{
		$FormatInfo[$dbRow->id]	= $dbRow->body;
	}
	//---- 根据关键词查询 ----
	$sql		= "SELECT i.id, i.name, i.cost, i.image, d.parts_id, d.weight, d.volume, d.value_id_1, d.value_id_2, d.value_id_3, d.value_id_4, d.value_id_5 FROM product_info AS i LEFT JOIN product_detail AS d ON i.id=d.id WHERE i.company_id='".$_SESSION['_application_info_']['company_id']."' AND i.is_delete='N' AND INSTR(i.name, '$keyword') LIMIT ".$limit;
	$result		= mysql_query($sql, $_mysql_link_);
	while($dbRow = mysql_fetch_object($result))
	{
		$list_product	= array();
		$list_product['id']			= $dbRow->id;
		$list_product['name']		= $dbRow->name;
		$list_product['price']		= $dbRow->cost;
		$list_product['image']		= $dbRow->image;
		$list_product['weight']		= $dbRow->weight;
		$list_product['volume']		= $dbRow->volume;
		$list_product['unit']		= $PartsInfo[$dbRow->parts_id];
		$list_product['format_1']	= $FormatInfo[$dbRow->value_id_1];
		$list_product['format_2']	= $FormatInfo[$dbRow->value_id_2];
		$list_product['format_3']	= $FormatInfo[$dbRow->value_id_3];
		$list_product['format_4']	= $FormatInfo[$dbRow->value_id_4];
		$list_product['format_5']	= $FormatInfo[$dbRow->value_id_5];
		$xtpl->assign("list_product", $list_product);
		$xtpl->parse("main.list_product");
		$JsonData[]	= $list_product;
	}
}
$main['time']	= time();

if($format == "json")
{
	header("Content-Type: application/json; charset=UTF-8");
	echo json_encode($JsonData);
	exit;
}

$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");
