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

if($_GET['action'] == 'add'){

	$sql= "SELECT i.id,i.express_id,i.image,i.paper_width,i.paper_height,i.paper_left,i.paper_top FROM  main_express_template_info AS i WHERE i.id={$_GET['id']}";	
	$msql = mysql_query($sql, $_mysql_link_);
		while($expressInfo = mysql_fetch_object($msql)){
			$value	= array();
			$value['name']			=   $expressInfo->id;
			$value['express_id']	=	$expressInfo->express_id;
			$value['image']			=	$expressInfo->image;
			$value['paper_width']	=	$expressInfo->paper_width;
			$value['paper_height']	=	$expressInfo->paper_height;
			$value['paper_left']	=	$expressInfo->paper_left;
			$value['paper_top']		=	$expressInfo->paper_top;
		}	
	$time 	= date('Y-m-d H:i:s',time());
	$name = '快递模板'.rand(10,99);
	$sql2 = "INSERT into company_express_template_info SET 
			company_id = '$company_id',
			express_id = '{$value['express_id']}',
			name = '{$name}',
			image = '{$value['image']}',
			paper_width = '{$value['paper_width']}',
			paper_height = '{$value['paper_height']}',
			paper_left = '{$value['paper_left']}',
			paper_top = '{$value['paper_top']}',
			pub_date = '$time'";
	$msql2 = mysql_query($sql2, $_mysql_link_);
	
	$I = mysql_insert_id($_mysql_link_);

	$sql3 = "SELECT * FROM main_express_template_position WHERE template_id={$_GET['id']}";
	$msq3 = mysql_query($sql3, $_mysql_link_);
		while($expressPosition = mysql_fetch_object($msq3)){
			$position	= array();
			$position['express_id']		=	$expressPosition->express_id;
			$position['template_id']	=	$expressPosition->template_id;
			$position['item_id']		=	$expressPosition->item_id;
			$position['item_width']		=	$expressPosition->item_width;
			$position['item_height']	=	$expressPosition->item_height;
			$position['item_left']		=	$expressPosition->item_left;
			$position['item_top']		=	$expressPosition->item_top;
			$position['item_font_size']	=	$expressPosition->item_font_size;
		
		$sql4[] = "INSERT into company_express_template_position SET 
			company_id = '$company_id',
			express_id = '{$position['express_id']}',
			template_id = '{$I}',
			item_id = '{$position['item_id']}',
			item_width = '{$position['item_width']}',
			item_height = '{$position['item_height']}',
			item_left = '{$position['item_left']}',
			item_top = '{$position['item_top']}',
			item_font_size = '{$position['item_font_size']}'";
		}

		$P = 0;
		for($i = 0; $i < count($sql4); $i++) {
			$msql4 = mysql_query($sql4[$i], $_mysql_link_);
			$a = mysql_insert_id($_mysql_link_);
			$P++;
		}

	if($I && $P) {
		header("Location: /setting/setting_express_template.php");
		exit;
	}else {
		header("Location: /setting/setting_express_template.php");
		exit;
	}

exit;	
}

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
	$addon	= "WHERE $addon";
}
	
$sql	= "SELECT COUNT(*) as total FROM main_express_template_info AS i LEFT JOIN main_express_info AS e ON i.express_id=e.id $addon";

$result	= mysql_query($sql, $_mysql_link_);
$main['total']		= mysql_result($result, 0, 'total');
$page_param			= array();
$main['page_info']	= erp_page_info($main['total'], $page, $page_param);
$limit	= ($page - 1) * $_SESSION["_application_info_"]["page_size"].", ".$_SESSION["_application_info_"]["page_size"];

$sqll= "SELECT i.id,i.express_id,i.name,i.image,i.paper_width,i.paper_height,i.paper_left,i.paper_top,e.name as express_name FROM  main_express_template_info AS i LEFT JOIN main_express_info AS e ON i.express_id=e.id $addon ORDER BY i.id DESC LIMIT ".$limit;	
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
			
			$xtpl->assign("expressList", $value);
			$xtpl->parse("main.expressList");
		
	}



$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");
