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
$where = "WHERE company_id = '$company_id'";

if(!empty($_REQUEST['find']))
{
	$find	= replace_safe($_REQUEST['find'], 20);
	if(!empty($find))
	{
		//---- 设置查询条件: 只允许查询模板名称 ----
		$addon		= "INSTR(name, '$find')";
		$main['find']	= $find;
		$page_param		= array();
		$page_param['find']		= replace_safe($_REQUEST['find'], 20, false, false);
	}
}
if($addon)
{
	$where	.= " AND $addon";
}

$sql	= "SELECT COUNT(*) as total FROM  company_order_template_info  $where";
$result	= mysql_query($sql, $_mysql_link_);
$main['total']		= mysql_result($result, 0, 'total');
$page_param			= array();
$main['page_info']	= erp_page_info($main['total'], $page, $page_param);
$limit	= ($page - 1) * $_SESSION["_application_info_"]["page_size"].", ".$_SESSION["_application_info_"]["page_size"];

$sqll= "SELECT id,name,pub_date,print_orient,page_width,page_height,page_left,page_top,border,is_default FROM  company_order_template_info $where ORDER BY id DESC LIMIT ".$limit;	
	$msql=mysql_query($sqll, $_mysql_link_);
	while($orderInfo = mysql_fetch_object($msql)){
			$value	= array();
			$value['id']			=	$orderInfo->id;
			$value['name']			=	$orderInfo->name;
			$value['image']			= 	$orderInfo->image;
			$value['pub_date']		=	$orderInfo->pub_date;
			$value['paper_width']	=	$orderInfo->page_width;
			$value['paper_height']	=	$orderInfo->page_height;
			$value['paper_left']	=	$orderInfo->page_left;
			$value['paper_top']		=	$orderInfo->page_top;
			$value['border']		=	$orderInfo->border==1?"有":"无";
			$value['is_default']	=	$orderInfo->is_default=="N"?"设为默认模板":"";
			if($orderInfo->print_orient==1){
				$value['print_orient']	= "纵向";
			}else if($orderInfo->print_orient==2){
				$value['print_orient']	= "横向";
			}else{
				$value['print_orient']	= "自适应";
			}	
				
			$xtpl->assign("orderList", $value);
			$xtpl->parse("main.orderList");
		
	}

	if($_GET['default']){
		$id=intval($_GET['id']);
		$sql = "UPDATE company_order_template_info SET is_default='N' WHERE company_id='$company_id'";
		mysql_query($sql,$_mysql_link_);
	
		$sql1 = "UPDATE company_order_template_info SET is_default='Y' WHERE company_id='$company_id' AND id='$id'";
		mysql_query($sql1,$_mysql_link_);

		header("Location: /setting/setting_order_template.php");
		exit;
	}	
$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");
