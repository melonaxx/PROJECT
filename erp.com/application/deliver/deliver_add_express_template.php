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

if(!empty($_POST['template_info']) && !empty($_POST['template_position'])) {

	$info   			= str_replace("'",'"',$_POST['template_info']);
	$position 			= str_replace("'",'"',$_POST['template_position']);

	$template_info 		= json_decode($info);
	$template_position  = json_decode($position);

	$name 			= replace_safe($template_info->template_name);
	$express_id 	= intval($template_info->express_id);
	$page_width 	= intval($template_info->page_width);
	$page_height	= intval($template_info->page_height);
	$page_top		= intval($template_info->page_top);
	$page_left		= intval($template_info->page_left);
	$template_image	= replace_safe($template_info->template_image);
	$time 			= date('Y-m-d H:i:s',time());
	
	// 公司-快递公司id
	$company_express = array();
	$sql0 	 = "SELECT express_id FROM company_express_info WHERE company_id='$company_id'";
	$result0 = mysql_query($sql0,$_mysql_link_);

	while($rows0 = mysql_fetch_object($result0)){
		$company_express[$rows0->express_id] = true;
	}

	// 属于该公司的快递公司
	if(isset($company_express[$express_id])){
		
		$sql1 = "SELECT name FROM company_express_template_info WHERE company_id='$company_id' AND express_id='$express_id' AND name='$name'";
	 	$result1 = mysql_query($sql1,$_mysql_link_);
		$row = mysql_fetch_object($result1);
		
		if($row) {
			echo "exit";
			exit;
		}

		$sql2 =  "SELECT COUNT(*) as total FROM  company_express_template_info WHERE company_id='$company_id' AND express_id='$express_id'";
		$result2 = mysql_query($sql2,$_mysql_link_);
		$total	= mysql_result($result2, 0, 'total');
		if($total == 0){
			$is_default = 'Y';
		}else{
			$is_default = 'N';
		}

		$sql = "INSERT INTO   company_express_template_info 
				SET 
					company_id   = '$company_id',
					express_id   = '$express_id',
					name 		 = '$name',
					paper_width	 = '$page_width',
					paper_height = '$page_height',
					paper_top	 = '$page_top',
					paper_left	 = '$page_left',
					image		 = '$template_image',
					pub_date	 = '$time',
					is_default	 = '$is_default'";

		mysql_query($sql,$_mysql_link_);
	   	$template_id = mysql_insert_id($_mysql_link_);
		if($template_id && $template_position!='') {
			$sql="";
			foreach ($template_position as $key => $value) {
				$item_id 		 = intval($value->item_id );
				$item_width 	 = intval($value->item_width );
				$item_height 	 = intval($value->item_height );
				$item_top 		 = intval($value->item_top );
				$item_left 		 = intval($value->item_left );
				$item_font_size  = intval($value->item_font_size );

				$sql2="INSERT INTO  company_express_template_position 
						SET 
							company_id  	= '$company_id',
							template_id 	= '$template_id',
							express_id  	= '$express_id',
							item_width  	= '$item_width ',
							item_height 	= '$item_height',
							item_top    	= '$item_top',
							item_left   	= '$item_left',
							item_font_size  = '$item_font_size',
							item_id 		= '$item_id';";
				$result2 = mysql_query($sql2,$_mysql_link_);
			}

			$rows = mysql_affected_rows($_mysql_link_);
		}

	}

	if($rows || $template_id) {
		echo "ok";
	}
	exit;
	
}


// $xtpl->assign("main", $main);
// $xtpl->parse("main");
// $xtpl->out("main");