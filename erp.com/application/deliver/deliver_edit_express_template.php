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

// 修改模板信息
if(!empty($_POST['template_info']) && !empty($_POST['template_position'])){

	$info 				= str_replace("'",'"',$_POST['template_info']);
	$position 			= str_replace("'",'"',$_POST['template_position']);

	$template_info 		= json_decode($info);
	$template_position  = json_decode($position);

	$name 				= replace_safe($template_info->template_name);
	$template_id 		= intval($template_info->template_id);
	$express_id 		= intval($template_info->express_id);
	$page_width 		= intval($template_info->page_width);
	$page_height		= intval($template_info->page_height);
	$page_top			= intval($template_info->page_top);
	$page_left			= intval($template_info->page_left);
	$template_image		= replace_safe($template_info->template_image);
	$time 				= date('Y-m-d H:i:s',time());

	// 修改数据 
	$sql 	 = "UPDATE company_express_template_info 
			SET 
				name 		 = '$name',
				paper_width	 = '$page_width',
				paper_height = '$page_height',
				paper_top	 = '$page_top',
				paper_left	 = '$page_left',
				image		 = '$template_image'
			WHERE id='$template_id'";
	$result1 = mysql_query($sql,$_mysql_link_);
	$row1 	 = mysql_affected_rows($_mysql_link_);
	
	$sql3 = "SELECT i.item_id i.item_width i.item_height i.item_top i.item_left i.item_font_size FROM company_express_template_position AS i WHERE company_id='$company_id' AND template_id='$template_id'";
	$_result = mysql_query($sql3,$_mysql_link_);

	$flag = true;
	if( $position != "]" ) {
		foreach ($template_position as $key => $value) {
			$item_id 		= intval($value->item_id );
			$item_width 	= intval($value->item_width );
			$item_height 	= intval($value->item_height );
			$item_top 		= intval($value->item_top );
			$item_left 		= intval($value->item_left );
			$item_font_size = intval($value->item_font_size );
		}
		if($_result['item_id'] !== $item_id){
			$flag = false;
		}else if($_result['item_width'] !== $item_width){
			$flag = false;
		}else if($_result['item_height'] !== $item_height){
			$flag = false;
		}else if($_result['item_top'] !== $item_top){
			$flag = false;
		}else if($_result['item_left'] !== $item_left){
			$flag = false;
		}else if($_result['item_font_size'] !== $item_font_size){
			$flag = false;
		}else {
			$flag = true;
		}
	}

	if($flag == false) {
		// 删除原来的数据
		$sql0 = "DELETE FROM company_express_template_position WHERE company_id='$company_id' AND template_id='$template_id'";
		mysql_query($sql0,$_mysql_link_);
		
		$sql  = "";
		if( $position != "]" ) {
			foreach ($template_position as $key => $value) {
				$item_id 		= intval($value->item_id );
				$item_width 	= intval($value->item_width );
				$item_height 	= intval($value->item_height );
				$item_top 		= intval($value->item_top );
				$item_left 		= intval($value->item_left );
				$item_font_size = intval($value->item_font_size );

				$sql2 	 = "INSERT INTO  company_express_template_position 
						SET 
							company_id		='$company_id',
							template_id 	= '$template_id',
							express_id  	= '$express_id',
							item_width 		= '$item_width ',
							item_height	 	= '$item_height',
							item_top 		= '$item_top',
							item_left		= '$item_left',
							item_font_size 	= '$item_font_size',
							item_id 		= '$item_id';";
				$result2 = mysql_query($sql2,$_mysql_link_);
			}

			$row2 = mysql_affected_rows($_mysql_link_);
		}
	}
	

	if($row1){
		echo "ok";
	}else{
		echo "no";
	}
		
	exit;
	
}

if( $_POST['action'] == "edit_image" ) {
	$template_image  = replace_safe($_POST['template_image']);
	$template_id     = intval($_POST['template_id']);
	
	$sql 	 = "UPDATE company_express_template_info SET image = '$template_image'WHERE company_id='$company_id' AND id='$template_id'";
	$result1 = mysql_query($sql,$_mysql_link_);
	$row1 	 = mysql_affected_rows($_mysql_link_);

	if($row1){
		echo "image_ok";
	}else{
		echo "no";
	}
	exit;
}

if(!empty($_FILES['uploadFile']))
{
	$UploadInfo		= upload_photo('uploadFile');
	$template_id    = intval($_POST['template_id']);
	if($UploadInfo['error'] == "")
	{
		// 删除原来的模板图片
		$sql0 = "SELECT image FROM company_express_template_info WHERE company_id='$company_id' AND id='$template_id'";
		$result0 = mysql_query($sql0,$_mysql_link_);
		while($rows = mysql_fetch_object($result0)){
			$image = $rows->image;
			unlink('../../'.$image);
		}
		
		$image_url		= $UploadInfo['url'];
		$sql 	 = "UPDATE company_express_template_info SET image = '$image_url'WHERE company_id='$company_id' AND id='$template_id'";
		$result = mysql_query($sql,$_mysql_link_);
	}

	header('Location: /deliver/deliver_design_express.php?template_id='.$template_id);
	exit;

}


