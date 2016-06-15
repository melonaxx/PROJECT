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
if($_POST["vol_fee"] && $_POST['express_id']){
	$express_id = intval($_POST['express_id']);
	// var_dump($_POST);die;	
	// 删除原来的规则
	// $sql 	 = "DELETE FROM company_express_store WHERE company_id='$company_id' AND express_id='$express_id'";
	// $result = mysql_query($sql,$_mysql_link_);
	
	$sql1 	 = "DELETE FROM company_express_vol_price WHERE company_id='$company_id' AND express_id='$express_id'";
	$result1 = mysql_query($sql1,$_mysql_link_);
	
	foreach($_POST['vol_fee'] as $v) {
		$area_list 		= replace_safe($v['area_id']);
		$store_list 	= replace_safe($v['store_id']);	
		$per_price 		= floatval($v['price']);	
		$least_price 	= floatval($v['least']);	
		$extra_price 	= floatval($v['extra']);	
		 
		$sql2 = "INSERT INTO company_express_vol_price SET
			company_id 	  		= '$company_id',
			express_id	  		= '$express_id',
			area_list       	= '$area_list',
			store_list			= '$store_list',
			per_price			= '$per_price',
			least_price			= '$least_price',
			extra_price			= '$extra_price'";

		mysql_query($sql2,$_mysql_link_);
		$price_id = mysql_insert_id($_mysql_link_);
		
	}
	
	$id = mysql_insert_id($_mysql_link_);

	if($id){
		echo "ok";		
	} 
}
