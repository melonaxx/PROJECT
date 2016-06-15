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

// 获取所有模板信息
$sql2 		= "SELECT id,name FROM  company_deliver_template_info WHERE company_id={$company_id}";
$result2 	= mysql_query($sql2,$_mysql_link_);

while($rows2 = mysql_fetch_object($result2)){
	$ordership_template = array();
	$ordership_template['id']  	=  $rows2->id;
	$ordership_template['name'] =  $rows2->name;
	
	$xtpl->assign("ordership_template",$ordership_template);
	$xtpl->parse("main.ordership_template");
}

$template_id = $_GET['template_id']?$_GET['template_id']:10001;
$template_id = intval($template_id);
$sql1 = "SELECT name,print_orient,page_top,page_left,page_width,page_height,border FROM company_deliver_template_info WHERE company_id='$company_id' AND id='$template_id'";

$result1 = mysql_query($sql1,$_mysql_link_);

while($rows1 = mysql_fetch_object($result1)){
	$page_info['template_id'] = $template_id;
	$page_info['template_name'] = $rows1->name;
	$page_info['print_orient'] = $rows1->print_orient;
	$page_info['page_top'] = $rows1->page_top;
	$page_info['page_left'] = $rows1->page_left;
	$page_info['page_width'] = $rows1->page_width;
	$page_info['page_height'] = $rows1->page_height;
	$page_info['border'] = $rows1->border;

	$xtpl->assign("page_info",$page_info);
	$xtpl->parse("main.page_info");
}

$sql = "SELECT 
			p.id,p.item_width,p.item_height,p.item_left,p.item_top,p.item_font_size,p.item_font_bold,
			t.id AS item_id, t.name,t.english,t.type 
		FROM `company_deliver_template_position` AS p 
		RIGHT JOIN company_deliver_template_item as t 
		ON p.item_id=t.id AND p.template_id='$template_id' AND p.company_id='$company_id'";
echo $sql;
$result = mysql_query($sql,$_mysql_link_);
while($rows = mysql_fetch_object($result)){
	$ordership=array();
	$table=array();
	$title=array();
	if($rows->type =='other'){
		$ordership['id'] 				= $rows->id;
		$ordership['item_id'] 			= $rows->item_id;
		$ordership['item_top'] 			= $rows->item_top;
		$ordership['item_left']	 		= $rows->item_left;
		$ordership['item_width'] 		= $rows->item_width;
		$ordership['item_height'] 		= $rows->item_height;
		$ordership['item_font_size']	= $rows->item_font_size;
		$ordership['item_font_bold'] 	= $rows->item_font_bold;
		$ordership['name'] 				= $rows->name;
		$ordership['item_content'] 		= $rows->name;
		$ordership['english'] 			= $rows->english;
		$ordership['item_type'] 		= $rows->type;
		$xtpl->assign("ordership",$ordership);
		$xtpl->parse("main.ordership");
	}else if($rows->type =='table'){
		$table['id'] 				= $rows->id;
		$table['item_id'] 			= $rows->item_id;
		$table['item_top'] 			= $rows->item_top;
		$table['item_left']	 		= $rows->item_left;
		$table['item_width'] 		= $rows->item_width;
		$table['item_height'] 		= $rows->item_height;
		$table['item_font_size']	= $rows->item_font_size;
		$table['item_font_bold'] 	= $rows->item_font_bold;
		$table['name'] 				= $rows->name;
		$table['item_content'] 		= $rows->name;
		$table['english'] 			= $rows->english;
		$table['item_type'] 		= $rows->type;
		$xtpl->assign("table",$table);
		$xtpl->parse("main.table");
	}else if($rows->type =='code'){
		$code['id'] 				= $rows->id;
		$code['item_id'] 			= $rows->item_id;
		$code['item_top'] 			= $rows->item_top;
		$code['item_left']	 		= $rows->item_left;
		$code['item_width'] 		= $rows->item_width;
		$code['item_height'] 		= $rows->item_height;
		$code['item_font_size']		= $rows->item_font_size;
		$code['item_font_bold'] 	= $rows->item_font_bold;
		$code['name'] 				= $rows->name;
		$code['item_content'] 		= $rows->name;
		$code['english'] 			= $rows->english;
		$code['item_type'] 		= $rows->type;
		$xtpl->assign("code",$code);
		$xtpl->parse("main.code");
	}else{
		$title['id'] 				= $rows->id;
		$title['item_id'] 			= $rows->item_id;
		$title['item_top'] 			= $rows->item_top;
		$title['item_left']	 		= $rows->item_left;
		$title['item_width'] 		= $rows->item_width;
		$title['item_height'] 		= $rows->item_height;
		$title['item_font_size']	= $rows->item_font_size;
		$title['item_font_bold'] 	= $rows->item_font_bold;
		$title['name'] 				= $rows->name;
		$title['item_content'] 		= $rows->name;
		$title['english'] 			= $rows->english;
		$title['item_type'] 		= $rows->type;
		$xtpl->assign("title",$title);
		$xtpl->parse("main.title");
	}

}

$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");