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

$StoreLocation	= array();
$StoreLocation['Store']		= "仓库";
$StoreLocation['Area']		= "库区";
$StoreLocation['Shelves']	= "货架";
$StoreLocation['Location']	= "货位";

$location	= $_REQUEST['location'];
$store_id	= intval($_REQUEST['store_id']);
$area_id	= intval($_REQUEST['area_id']);
$shelves_id	= intval($_REQUEST['shelves_id']);
$company_id	= $_SESSION['_application_info_']['company_id'];

if(!isset($StoreLocation[$location]))
{
	//---- 没有选择仓库类型 ----
	illegal_operation();
}

$main['store_id']		= 0;
$main['area_id']		= 0;
$main['shelves_id']		= 0;
$main['location_type']	= $location;
$main['location_name']	= $StoreLocation[$location];

//---- 列出当前公司所有仓库 ----
$StoreList	= array();
$sql	= "SELECT id, number, name FROM store_info WHERE company_id='$company_id' AND store_status != 'Delete' ORDER BY id";
$result	= mysql_query($sql, $_mysql_link_);
while($StoreInfo = mysql_fetch_object($result))
{
	$list_store	= array();
	$list_store['id']			= $StoreInfo->id;
	$list_store['number']		= $StoreInfo->number;
	$list_store['name']			= $StoreInfo->name;
	$list_store['selected']		= "N";
	if($store_id == $StoreInfo->id)
	{
		//---- 选中了该仓库 ----
		$list_store['selected']	= "Y";
		$main['store_id']	= $store_id;
	}
	$xtpl->assign("list_store", $list_store);
	$xtpl->parse("main.list_store");
	$StoreList[$StoreInfo->id]	= $StoreInfo;
}

//---- 列出当前公司所有库位 ----
$sql	= "SELECT id, store_id, name, location_type, parent_id, body, total FROM store_location WHERE company_id='$company_id'";
$result	= mysql_query($sql, $_mysql_link_);
while($LocationInfo = mysql_fetch_object($result))
{
	$list_location	= array();
	$list_location['id']			= $LocationInfo->id;
	$list_location['store_id']		= $LocationInfo->store_id;
	$list_location['name']			= $LocationInfo->name;
	$list_location['type']			= $LocationInfo->location_type;
	$list_location['parent']		= $LocationInfo->parent_id;
	$list_location['body']			= $LocationInfo->body;
	$list_location['total']			= $LocationInfo->total;
	//---- 当前页面是“货架”列表，本循环类型为“库区”，库区id存在时 ----
	if($location == "Shelves" && $LocationInfo->location_type == "Area" && $area_id == $LocationInfo->id)
	{
		//---- 认定货架列表页，所属库区是真实的 ----
		$main['area_id']	= $LocationInfo->id;
	}
	//---- 当前页面是“货位”列表，本循环类型为“货架”，货架id存在时 ----
	if($location == "Location" && $LocationInfo->location_type == "Shelves" && $shelves_id == $LocationInfo->id)
	{
		//---- 认定货位列表页，所属货架是真实的 ----
		$main['shelves_id']	= $LocationInfo->id;
		$main['area_id']	= $LocationInfo->parent_id;
	}
	$xtpl->assign("list_location", $list_location);
	$xtpl->parse("main.list_location");
}

$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");
