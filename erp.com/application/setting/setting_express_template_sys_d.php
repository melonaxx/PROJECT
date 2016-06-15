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
	
	$_GET['id'] = intval($_GET['id']);
	if($_GET['id'] < 0) { 
		die;
	}
	
	$sql= "SELECT i.id,i.name,i.image,i.print_orient,i.paper_width,i.paper_height,i.paper_left,i.paper_top,i.border FROM main_deliver_template_info AS i WHERE i.id={$_GET['id']}";

	$msql = mysql_query($sql, $_mysql_link_);
		while($expressInfo = mysql_fetch_object($msql)){
			$value	= array();
			$value['name']			=	$expressInfo->name;
			$value['image']			=	$expressInfo->image;
			$value['print_orient']	=	$expressInfo->print_orient;
			$value['paper_width']	=	$expressInfo->paper_width;
			$value['paper_height']	=	$expressInfo->paper_height;
			$value['paper_left']	=	$expressInfo->paper_left;
			$value['paper_top']		=	$expressInfo->paper_top;
			$value['border']		=	$expressInfo->border;
		}	
	$time 	= date('Y-m-d H:i:s',time());
	$name = '发货模板'.rand(10,99);
	$sql2 = "INSERT into company_deliver_template_info SET 
			company_id = '$company_id',
			name = '{$name}',
			image = '{$value['image']}',
			print_orient = '{$value['print_orient']}',
			page_width = '{$value['paper_width']}',
			page_height = '{$value['paper_height']}',
			page_left = '{$value['paper_left']}',
			page_top = '{$value['paper_top']}',			
			border = '{$value['border']}',
			pub_date = '$time'";
	$msql2 = mysql_query($sql2, $_mysql_link_);
	
	$I = mysql_insert_id($_mysql_link_);

	$sql3 = "SELECT i.template_id,i.item_id,i.item_width,i.item_height,i.item_left,i.item_top,i.item_font_size,i.item_font_bold FROM main_deliver_template_position as i WHERE i.template_id={$_GET['id']}";
	$msq3 = mysql_query($sql3, $_mysql_link_);//echo $sql3;
		while($expressPosition = mysql_fetch_object($msq3)){

			$template_id	=	$expressPosition->template_id;
			$item_id		=	$expressPosition->item_id;
			$item_width		=	$expressPosition->item_width;
			$item_height	=	$expressPosition->item_height;
			$item_left		=	$expressPosition->item_left;
			$item_top		=	$expressPosition->item_top;
			$item_font_size	=	$expressPosition->item_font_size;
			$item_font_bold	=	$expressPosition->item_font_bold;

			$sql4[] = "INSERT into company_deliver_template_position SET 
			company_id = '$company_id',
			template_id = '{$I}',
			item_id = '{$item_id}',
			item_width = '{$item_width}',
			item_height = '{$item_height}',
			item_left = '{$item_left}',
			item_top = '{$item_top}',
			item_font_bold = '{$item_font_bold}',
			item_font_size = '{$item_font_size}'";
			
		}
		$P = 0;
		for($i = 0; $i < count($sql4); $i++) {
			$msql4 = mysql_query($sql4[$i], $_mysql_link_);
			mysql_insert_id($_mysql_link_);
			$P++;
		}

	if($I && $P) {
		header("Location: /setting/setting_deliver_template.php");
		exit;
	}else {
		header("Location: /setting/setting_deliver_template.php");
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
	
$sql	= "SELECT COUNT(*) as total FROM main_deliver_template_info $addon";

$result	= mysql_query($sql, $_mysql_link_);
$main['total']		= mysql_result($result, 0, 'total');
$page_param			= array();
$main['page_info']	= erp_page_info($main['total'], $page, $page_param);
$limit	= ($page - 1) * $_SESSION["_application_info_"]["page_size"].", ".$_SESSION["_application_info_"]["page_size"];

$sqll= "SELECT i.id,i.name,i.image,i.pub_date,i.print_orient,i.paper_width,i.paper_height,i.paper_left,i.paper_top,i.border FROM  main_deliver_template_info as i order by i.id DESC LIMIT ".$limit;	
	$msql=mysql_query($sqll, $_mysql_link_);
	while($expressInfo = mysql_fetch_object($msql)){
			$value	= array();
			$value['id']			=	$expressInfo->id;
			$value['name']			=	$expressInfo->name;
			$value['image']			=	$expressInfo->image;
			$value['pub_date']		=	$expressInfo->pub_date;
			$value['print_orient']	=	$expressInfo->print_orient;
			$value['paper_width']	=	$expressInfo->paper_width;
			$value['paper_height']	=	$expressInfo->paper_height;
			$value['paper_left']	=	$expressInfo->paper_left;
			$value['paper_top']		=	$expressInfo->paper_top;
			$value['border']		=	$expressInfo->border==1?"有":"无";

			if($expressInfo->print_orient==1){
				$value['print_orient']	= "纵向";
			}else if($expressInfo->print_orient==2){
				$value['print_orient']	= "横向";
			}else{
				$value['print_orient']	= "按内容";
			}	

			$xtpl->assign("expressList", $value);
			$xtpl->parse("main.expressList");
		
	}



$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");