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
$staff_id   = $_SESSION['_application_info_']['staff_id'];

//搜索条件中的仓库下列列表
$sql = "SELECT name,id FROM store_info WHERE company_id = '$company_id' AND store_status <> 'Delete' ORDER BY id DESC";
$result = mysql_query($sql,$_mysql_link_);
while($StoreInfo = mysql_fetch_object($result)){
	$store_info = array();
	$store_info['id']	=	$StoreInfo->id;
	$store_info['name']	=	$StoreInfo->name;
	$xtpl->assign("store_info", $store_info);
	$xtpl->parse("main.store_info");
}

//设置查询条件
$addon 		= 	array();
$addon[]	=	"p.company_id='".$company_id."'";
// $addon[]	=	"i.is_delete = 'N'";

//根据商品名进行搜索
// if(!empty($_REQUEST['find']))
// {
// 	$find = replace_safe($_REQUEST['find']);
// 	if(!empty($find)){
// 		// ---- 设置查询条件：只允许查商品名 ----
// 		$addon[]		= "INSTR(i.name, '$find')";
// 		$main['find']	= $find;
// 		$page_param		= array();
// 		$page_param['find']		= replace_safe($_REQUEST['find'], 20, false, false);
// 	}
// }
//根据仓库查询
if(!empty($_REQUEST['ware'])){
	$ware = replace_safe($_REQUEST['ware']);
	if(!empty($ware)){
		// ---- 设置查询条件：只允许查询仓库 ----
		$addon[]	=	"p.store_id = '".$ware."'";
		$main['ware'] = $ware;
		$page_param		= array();
		$page_param['ware']		= replace_safe($_REQUEST['ware'], 20, false, false);
	}
}else{
	$sql = "SELECT id FROM store_info WHERE company_id = '$company_id' AND store_status <> 'Delete' ORDER BY id DESC LIMIT 1 ";
	$result = mysql_query($sql,$_mysql_link_);
	if(mysql_num_rows($result)==1)
	{
		$storeinfo = mysql_result($result,0,0);
		$addon[] = "p.store_id = '".$storeinfo."'";
	}
}
//根据是否有预警查
if(!empty($_REQUEST['is_yujing'])){
	$is_yujing = replace_safe($_REQUEST['is_yujing']);
	if(!empty($is_yujing)){
		$addon[]					=	"p.is_warning = '".$is_yujing."'";
		$main['is_yujing'] 			= $is_yujing;
		$page_param					= array();
		$page_param['is_yujing']	= replace_safe($_REQUEST['is_yujing'], 20, false, false);
	}
}

if(count($addon) > 0)
{
	$where	= "WHERE ".implode(" AND ", $addon);
}
// ---- 数量 ----
$sql = "SELECT COUNT(p.id) as total FROM store_product AS p ".$where;
$result			= mysql_query($sql, $_mysql_link_);
$main['total']	= mysql_result($result, 0, 'total');
//---- 处理分页 ----
if(!is_array($page_param)){
	$page_param		= array();
}
$main['page_info']	= erp_page_info($main['total'], $page, $page_param);
$limit	= ($page - 1) * $_SESSION["_application_info_"]["page_size"].", ".$_SESSION["_application_info_"]["page_size"];

//---- 数量大于0 ----
if($main['total'] > 0){
	//获取规格值
	$FormatValue	= array();
	$sql = "SELECT id, body FROM product_format_value WHERE company_id = '$company_id'";
	$result  = mysql_query($sql,$_mysql_link_);
	while($x = mysql_fetch_object($result)){
		$FormatValue[$x->id]	= $x->body;
	}
	//查询仓库商品信息
	$sql =	"SELECT i.image,i.name,i.bar_code,p.upper,p.lower,p.total_real,p.total_available,p.total_lock,p.total_way,p.product_id,d.value_id_1,d.value_id_2,d.value_id_3,d.value_id_4,d.value_id_5 FROM store_product AS p LEFT JOIN product_info AS i ON p.product_id = i.id LEFT JOIN product_detail AS d ON d.id = p.product_id ".$where." LIMIT ".$limit;
	$result = mysql_query($sql,$_mysql_link_);
	$no = 1;
	while($StoreInfo = mysql_fetch_object($result))
	{
		$list_store  = array();
		$format      = array();
		$list_store['no']				= 	$no++;
		$list_store['image']			= 	$StoreInfo->image;
		$list_store['id']				=	$StoreInfo->product_id;
		$list_store['name']				= 	$StoreInfo->name;
		$list_store['bar_code']			= 	$StoreInfo->bar_code;
		$list_store['total_way']		= 	$StoreInfo->total_way;
		$list_store['total_lock']		= 	$StoreInfo->total_lock;
		$list_store['total_real']		= 	$StoreInfo->total_real;
		$list_store['total_available']	= 	$StoreInfo->total_available;
		$value_1						= 	$FormatValue[$StoreInfo->value_id_1];
		$value_2						= 	$FormatValue[$StoreInfo->value_id_2];
		$value_3						= 	$FormatValue[$StoreInfo->value_id_3];
		$value_4						= 	$FormatValue[$StoreInfo->value_id_4];
		$value_5						= 	$FormatValue[$StoreInfo->value_id_5];
		$format = $value_1.','.$value_2.','.$value_3.','.$value_4.','.$value_5;
		$list_store['format']			=	rtrim($format, ',');
		if($StoreInfo->lower >0)
		{
			$list_store['lower'] = $StoreInfo->lower;
		}else{
			$list_store['lower'] = '';
		}
		if($StoreInfo->upper >0)
		{
			$list_store['upper'] = $StoreInfo->upper;
		}else{
			$list_store['upper'] = '';
		}
		$xtpl->assign("list_store", $list_store);
		$xtpl->parse("main.list_store");

	}

}




$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");
