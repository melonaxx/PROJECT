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


	// 获取公司所有模板信息
	$sql2 	 = "SELECT id,name,express_id FROM  company_express_template_info WHERE company_id='$company_id'";
	$result2 = mysql_query($sql2,$_mysql_link_);
	while($rows2 = mysql_fetch_object($result2)){
		$express_template = array();
		$express_template['id']  		=  $rows2->id;
		$express_template['name'] 		=  $rows2->name;
		$express_template['express_id'] =  $rows2->express_id;
	
		$xtpl->assign("express_template",$express_template);
		$xtpl->parse("main.express_template");
	}
	
	$template_id = intval($_GET['template_id']);
	$sql1 		 = "SELECT id,name,image,paper_top,paper_left,paper_width,paper_height,express_id FROM 	company_express_template_info WHERE company_id='$company_id' AND id='$template_id'";
	
	$result1 	 = mysql_query($sql1,$_mysql_link_);
	
	while($rows1 = mysql_fetch_object($result1)){

		$page_info['template_id'] 	 = $template_id;
		$page_info['id']  			 = $rows1->id;
		$page_info['template_name']  = $rows1->name;
		$page_info['express_id']  	 = $rows1->express_id;
		$page_info['template_image'] = $rows1->image;
		$page_info['page_top'] 		 = $rows1->paper_top;
		$page_info['page_left'] 	 = $rows1->paper_left;
		$page_info['page_width'] 	 = $rows1->paper_width;
		$page_info['page_height']	 = $rows1->paper_height;
	
		$xtpl->assign("page_info",$page_info);
		$xtpl->parse("main.page_info");
	}
	
	$sql 	= "SELECT 
				p.id,p.item_width,p.item_height,p.item_left,p.item_top,p.item_font_size,
				t.id AS item_id, t.name,t.english
			FROM company_express_template_position AS p 
			RIGHT JOIN main_express_template_item AS t 
			ON p.item_id=t.id AND p.company_id='$company_id' AND p.express_id='{$page_info['express_id']}' AND p.template_id={$page_info['id']}";
	
	$result = mysql_query($sql,$_mysql_link_);
	while($rows = mysql_fetch_object($result)){
		$express=array();
		$express['id'] 					= $rows->id;
		$express['item_id'] 			= $rows->item_id;
		$express['item_top'] 			= $rows->item_top;
		$express['item_left']	 		= $rows->item_left;
		$express['item_width'] 			= $rows->item_width;
		$express['item_height'] 		= $rows->item_height;
		$express['item_font_size']		= $rows->item_font_size;
		$express['name'] 				= $rows->name;
		$express['english'] 			= $rows->english;
		$xtpl->assign("express",$express);
		$xtpl->parse("main.express");
		
	}


$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");