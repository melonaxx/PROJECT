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


$company_id	= $_SESSION['_application_info_']['company_id'];
//获取默认仓库
$sql = "SELECT id,name FROM store_info WHERE company_id='$company_id' AND store_status='Default' ";
$res = mysql_query($sql,$_mysql_link_);
if(mysql_num_rows($res)>0)
{
	$main['def_id']   = mysql_result($res,0,0);
	$main['def_name'] = mysql_result($res,0,1);
}
if(mysql_num_rows($res)==0)
{
	$main['tol'] 	  = 'no';
}
//---- 仓库列表 ----
$sql = "SELECT id,name FROM store_info WHERE company_id='$company_id' AND store_status !='Delete' AND store_status != 'Default' ORDER BY id ";
$result	= mysql_query($sql, $_mysql_link_);
while($StoreInfo = mysql_fetch_object($result))
{
	$list_store			= array();
	$list_store['id']	= $StoreInfo->id;
	$list_store['name']	= $StoreInfo->name;

	$xtpl->assign("list_store", $list_store);
	$xtpl->parse("main.list_store");
}
if(!empty($_REQUEST['Location'])){
	$location	= $_REQUEST['Location'];
	$store_id	= intval($_REQUEST['store_id']);
	$area_id	= intval($_REQUEST['area_id']);
	$shelves_id	= intval($_REQUEST['shelves_id']);
	//---- 列出当前仓库所有库位 ----
	$sql	= "SELECT id, store_id, name, location_type, parent_id FROM store_location WHERE company_id='$company_id' AND store_id ='$store_id' AND is_delete = 'N' ";
	$result	= mysql_query($sql, $_mysql_link_);
	$list_location = array();
	while($LocationInfo = mysql_fetch_object($result))
	{
		$list_location[]= array(
			'id'		=> $LocationInfo->id,
			'name'		=> $LocationInfo->name,
			'type'		=> $LocationInfo->location_type,
			'parent'	=> $LocationInfo->parent_id,
			'store_id'	=> $LocationInfo->store_id
		);
	}
	echo json_encode($list_location);
	exit;
}
$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");

