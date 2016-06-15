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

if($_POST["weight_fee"] && $_POST['express_id']){
	$express_id = intval($_POST['express_id']);
	// 删除原来的规则
	$sql 	 = "DELETE FROM company_express_store WHERE company_id='$company_id' AND express_id='$express_id'";
	$result = mysql_query($sql,$_mysql_link_);
	
	$sql1 	 = "DELETE FROM company_express_price WHERE company_id='$company_id' AND express_id='$express_id'";
	$result1 = mysql_query($sql1,$_mysql_link_);
	
	foreach($_POST['weight_fee'] as $v) {
		$area_ids 		= $v['area_id']  != ''  ? replace_safe($v['area_id'])     : '';
		
		$first_weight_1 = $v['first_weight_0']  ? floatval($v['first_weight_0'])  : 0;
		$first_weight_2 = $v['first_weight_1']  ? floatval($v['first_weight_1'])  : 0;
		$first_weight_3 = $v['first_weight_2']  ? floatval($v['first_weight_2'])  : 0;
		$first_weight_4 = $v['first_weight_3']  ? floatval($v['first_weight_3'])  : 0;
		$first_weight_5 = $v['first_weight_4']  ? floatval($v['first_weight_4'])  : 0;
		$first_price_1  = $v['first_price_0']   ? floatval($v['first_price_0'])   : 0; 
		$first_price_2  = $v['first_price_1']   ? floatval($v['first_price_1'])   : 0; 
		$first_price_3  = $v['first_price_2']   ? floatval($v['first_price_2'])   : 0; 
		$first_price_4  = $v['first_price_3']   ? floatval($v['first_price_3'])   : 0; 
		$first_price_5  = $v['first_price_4']   ? floatval($v['first_price_4'])   : 0;
		$added_weight   = $v['added_weight']    ? floatval($v['added_weight']) 	  : 0;
		$added_price    = $v['added_price']     ? floatval($v['added_price']) 	  : 0;

		$sql2 = "INSERT INTO company_express_price SET
			company_id 	  		= '$company_id',
			express_id	  		= '$express_id',
			area_list       	= '$area_ids',
			first_weight_1 		= '$first_weight_1',
			first_weight_2 		= '$first_weight_2',
			first_weight_3 		= '$first_weight_3',
			first_weight_4 		= '$first_weight_4',
			first_weight_5 		= '$first_weight_5',
			first_price_1  		= '$first_price_1',
			first_price_2  		= '$first_price_2',
			first_price_3  		= '$first_price_3',
			first_price_4  		= '$first_price_4',
			first_price_5  		= '$first_price_5',
			weight_increase   	= '$added_weight',
			price_increase    	= '$added_price'";

		mysql_query($sql2,$_mysql_link_);
		$price_id = mysql_insert_id($_mysql_link_);
		
		$store_list 	= $v['store_id'] != ''  ? replace_safe($v['store_id'])    : '';	
		
		$store_ids = explode(',',$store_list);

		if(count($store_ids) > 0){
			// 查询仓库id
			$store = array();
			$sql = "SELECT id FROM  store_info WHERE company_id='$company_id' order by id";
			$result = mysql_query($sql,$_mysql_link_);
			while($storeInfo = mysql_fetch_object($result)){
				$store[$storeInfo->id] = true;
			}

			foreach($store_ids as $v){
				if(isset($store[$express_id])){
				
					$sql3 = "INSERT INTO company_express_store SET 
							company_id = '$company_id',
							express_id = '$express_id',
							price_id = '$price_id',
							store_id = '$v'";
					mysql_query($sql3,$_mysql_link_);

				}
			}
		}
	}

	$id = mysql_insert_id($_mysql_link_);

	if($id){
		echo "ok";		
	} 
}
