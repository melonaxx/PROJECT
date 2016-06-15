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
$express_id = $_GET['express_id'];
$main['express_id'] = $express_id;
$sql = "SELECT id,area_list,store_list,per_price,least_price,extra_price FROM company_express_vol_price WHERE company_id='$company_id' AND express_id='$express_id' order by id";

$result = mysql_query($sql,$_mysql_link_);
$i=1;
while($rows = mysql_fetch_object($result)){
	$vol_fee['num'] 				= $i++;
	$vol_fee['per_fee']   	= $rows->per_price;
	$vol_fee['least_fee'] 	= $rows->least_price;
	$vol_fee['extra_fee'] 	= $rows->extra_price;

	// 地区
	$vol_fee['area_id'] 			= $rows -> area_list;
	$vol_fee['area_name'] 		= '';
	if($rows -> area_list != ""){
		$area_list 					= rtrim($rows -> area_list,',');
		// $sql2 		= "SELECT IF(parent in ($area_list),'',name) as area_name FROM main_identity_card WHERE number in ($area_list)";
		$sql2 		= "SELECT name  as area_name FROM main_identity_card WHERE number in ($area_list) AND parent not in ($area_list)";

		$result2 	= mysql_query($sql2,$_mysql_link_);
		while($rows2 = mysql_fetch_object($result2)){

			if($rows2 -> area_name != ''){
				$area_name .=  $rows2 -> area_name.', ';
			}

			$vol_fee['area_name'] = rtrim($area_name,', ');
		}
	}
	
	$vol_fee['store_id'] 			= $rows -> store_list;
	$vol_fee['store_name'] 		= '';
	if($rows -> store_list != ""){
		$store_list 					= rtrim($rows -> store_list,',');
		
		$sql2 		= "SELECT name  as store_name FROM store_info WHERE id in ($store_list)";

		$result2 	= mysql_query($sql2,$_mysql_link_);
		while($rows2 = mysql_fetch_object($result2)){
			if($rows2 -> store_name != ''){
				$store_name .=  $rows2 -> store_name.', ';
			}
			$vol_fee['store_name'] = rtrim($store_name,', ');
		}
	}

	if($rows -> area_list == '' && $rows->store_list ==''){
		$main['default_fee'] 	= $rows->per_price;
		$main['least_fee'] 		= $rows->least_price;
		$main['extra_fee'] 		= $rows->extra_price;
	}

	$xtpl -> assign("vol_fee",$vol_fee);
	$xtpl -> parse("main.vol_fee");
}

$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");