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

//---- 删除单条数据 ----
if(isset($_GET['m']) && $_GET['m'] == 'delete' && isset($_GET['id']))
{
	$id		= intval($_GET['id']);
	$sql	= "UPDATE purchase_supplier SET is_delete='Y' WHERE company_id='$company_id' AND id='$id'";
	mysql_query($sql, $_mysql_link_);
	header('Location: /crm/crm_supplier_list.php');
	exit;
}

/*
//---- 删除多条数据 ----
if(isset($_GET['m']) && $_GET['m'] == 'deleteAll' && isset($_GET['idArr']))
{
	$idArr	= replace_safe($_GET['idArr']);
	$sql	= "UPDATE purchase_supplier SET is_delete='Y' WHERE company_id='$company_id' AND id IN ($idArr)";
	mysql_query($sql, $_mysql_link_);
	header('Location: /crm/crm_supplier_list.php');
	exit;
}
*/

$name = replace_safe($_GET['name']);
$find = "AND (instr(name,'".$name."') or instr(main_business,'".$name."'))";
$main['find'] = $name;
//---- 供应商类型 ----
$SupplierType					= array();
$SupplierType['Finished']		= "成品供货商";
$SupplierType['Materials']		= "原材料供货商";

//---- 供货商级别 ----
$SupplierLevel					= array();
$SupplierLevel['Primary']		= "主选供货商";
$SupplierLevel['Alternative']	= "备选供货商";
$SupplierLevel['Eliminate']		= "淘汰供货商";

//---- 数量 ----
$sql	= "SELECT COUNT(*) as total FROM purchase_supplier WHERE company_id='$company_id' AND is_delete='N'".$find;
$result	= mysql_query($sql, $_mysql_link_);
$main['total']		= mysql_result($result, 0, 'total');

$page_param			= array();
$main['page_info']	= erp_page_info($main['total'], $page, $page_param);
$limit	= ($page - 1) * $_SESSION["_application_info_"]["page_size"].", ".$_SESSION["_application_info_"]["page_size"];

if($main['total'] > 0)
{
	//---- 供货商数量大于0 ----
	$sql		= "SELECT id, number, name, type, website, contact_name, mobile, content FROM purchase_supplier WHERE company_id='$company_id' AND is_delete='N' ".$find." ORDER BY id DESC LIMIT ".$limit;
	$result		= mysql_query($sql, $_mysql_link_);
	while($SupplierInfo = mysql_fetch_object($result))
	{
		$value	= array();
		$value['id']			= $SupplierInfo->id;
		$value['number']		= $SupplierInfo->number;
		$value['name']			= $SupplierInfo->name;
		$value['website']		= $SupplierInfo->website;
		$value['contact_name']	= $SupplierInfo->contact_name;
		$value['mobile']		= $SupplierInfo->mobile;
		$value['content']		= $SupplierInfo->content;
		$value['type']			= $SupplierType[$SupplierInfo->type];
		$xtpl->assign("supplierList", $value);
		$xtpl->parse("main.supplierList");
	}
}
$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");
