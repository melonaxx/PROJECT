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
$where = "WHERE i.company_id = '$company_id'";

if(!empty($_REQUEST['find']))
{
	$find	= replace_safe($_REQUEST['find'], 20);
	if(!empty($find))
	{
		//---- 设置查询条件: 只允许查询模板名称 或 快递公司 ----
		$addon		= "(INSTR(i.name, '$find') OR INSTR(e.name,'$find'))";
		$main['find']	= $find;
		$page_param		= array();
		$page_param['find']		= replace_safe($_REQUEST['find'], 20, false, false);
	}
}
if($addon)
{
	$where	.= "AND $addon";
}
	
$sql	= "SELECT COUNT(*) as total FROM company_express_template_info AS i LEFT JOIN company_express_info AS e ON i.express_id=e.express_id AND e.company_id=i.company_id AND e.status='Y' $where";

$result	= mysql_query($sql, $_mysql_link_);
$main['total']		= mysql_result($result, 0, 'total');
$page_param			= array();
$main['page_info']	= erp_page_info($main['total'], $page, $page_param);
$limit	= ($page - 1) * $_SESSION["_application_info_"]["page_size"].", ".$_SESSION["_application_info_"]["page_size"];

$sqll= "SELECT i.id,i.express_id,i.name,i.image,i.pub_date,i.paper_width,i.paper_height,i.paper_left,i.paper_top,i.is_default,e.name as express_name FROM  company_express_template_info AS i LEFT JOIN company_express_info AS e ON  e.company_id='$company_id' AND i.express_id=e.express_id AND e.status='Y' $where ORDER BY i.id DESC LIMIT ".$limit;	
	$msql=mysql_query($sqll, $_mysql_link_);
	while($expressInfo = mysql_fetch_object($msql)){
			$value	= array();
			$value['id']			=	$expressInfo->id;
			$value['express_id']	=	$expressInfo->express_id;
			$value['name']			=	$expressInfo->name;
			$value['image']			=	$expressInfo->image;
			$value['pub_date']		=	$expressInfo->pub_date;
			$value['paper_width']	=	$expressInfo->paper_width;
			$value['paper_height']	=	$expressInfo->paper_height;
			$value['paper_left']	=	$expressInfo->paper_left;
			$value['paper_top']		=	$expressInfo->paper_top;
			$value['express_name']	=	$expressInfo->express_name;
			$value['is_default']	=	$expressInfo->is_default=="N"?"设为默认模板":"";
			
			$xtpl->assign("expressList", $value);
			$xtpl->parse("main.expressList");
		
	}


	if($_GET['default']){
		$id=intval($_GET['id']);
		$express_id=intval($_GET['express_id']);
		 
		$sql = "UPDATE company_express_template_info SET is_default='N' WHERE company_id='$company_id' AND express_id='$express_id'";
		mysql_query($sql,$_mysql_link_);
		
		$sql1 = "UPDATE company_express_template_info SET is_default='Y' WHERE company_id='$company_id' AND id='$id'";
		mysql_query($sql1,$_mysql_link_);

		header("Location: /setting/setting_express_template.php");
		exit;
	}	
$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");
