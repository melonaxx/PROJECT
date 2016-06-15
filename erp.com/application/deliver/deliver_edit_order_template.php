<?
//---- UTF8 编码 ----
session_start();
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header("Content-Type: text/html; charset=UTF-8");
header("X-UA-Compatible: IE=EmulateIE7");

include "../../config.php";
include "../../xtpl.php";
include "../detect_permit.php";
include "../function.php";

$company_id = $_SESSION['_application_info_']['company_id'];

if(!empty($_POST['template_info']) && !empty($_POST['template_position'])){

	$info   			= str_replace("'",'"',$_POST['template_info']);
	$position 			= str_replace("'",'"',$_POST['template_position']);

	$template_info 		= json_decode($info);
	$template_position  = json_decode($position);

	$name 			= replace_safe($template_info->template_name);
	$template_id 	= intval($template_info->template_id);
	$print_orient 	= intval($template_info->print_orient);
	$page_width 	= intval($template_info->page_width);
	$page_height	= intval($template_info->page_height);
	$page_top		= intval($template_info->page_top);
	$page_left		= intval($template_info->page_left);
	$border			= intval($template_info->border);
	$time 			= date('Y-m-d H:i:s',time());
	
	if($template_id == null) {
		exit;
	}

	// 修改数据
	$sql = "UPDATE company_order_template_info 
			SET 
				name 		= '$name',
				company_id 	= '$company_id',
				print_orient= '$print_orient',
				page_width	= '$page_width',
				page_height	= '$page_height',
				page_top	= '$page_top',
				page_left	= '$page_left',
				border		= '$border'
			WHERE id='$template_id'";
	$result1 = mysql_query($sql,$_mysql_link_);
	$row1 = mysql_affected_rows($_mysql_link_);
	
	if($position != "]"){
		// 删除原来的数据
		$sql0 = "DELETE FROM company_order_template_position WHERE company_id='$company_id' AND template_id='$template_id'";
		mysql_query($sql0,$_mysql_link_);
		
			foreach ($template_position as $key => $value) {
				$item_id 		= intval($value->item_id );
				// $item_class 	= intval($value->item_class );
				$item_width 	= intval($value->item_width );
				$item_height 	= intval($value->item_height );
				$item_top 		= intval($value->item_top );
				$item_left 		= intval($value->item_left );
				$item_font_size = intval($value->item_font_size );
				$item_font_bold = intval($value->item_font_bold );
				$item_font_name = replace_safe($value->item_font_name );

				$sql2="INSERT INTO  company_order_template_position 
						SET company_id	='$company_id',
							template_id = '$template_id',
							item_width  = '$item_width ',
							item_height = '$item_height',
							item_top = '$item_top',
							item_left = '$item_left',
							item_font_size = '$item_font_size',
							item_font_bold = '$item_font_bold',
							item_id = '$item_id';";
				$result2 = mysql_query($sql2,$_mysql_link_);
			}

		$row2 = mysql_affected_rows($_mysql_link_);
	}	
	
	if($row1){
		echo "ok";
	}else{
		echo "no";
	}
	die;
}

$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");