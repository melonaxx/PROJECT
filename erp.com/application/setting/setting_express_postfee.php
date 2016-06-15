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

$sql = "SELECT id,area_list,first_weight_1,first_weight_2,first_weight_3,first_weight_4,first_weight_5,first_price_1,first_price_2,first_price_3,first_price_4,first_price_5,weight_increase,price_increase FROM company_express_price WHERE company_id='$company_id' AND express_id='$express_id' order by id";

$result = mysql_query($sql,$_mysql_link_);
$i=1;
while($rows = mysql_fetch_object($result)){
	$weight_fee['num'] 				= $i++;

	// 地区
	$weight_fee['area_id'] 			= $rows -> area_list;
	$weight_fee['area_name'] 		= '';
	if($rows -> area_list != ""){
		$area_list 					= rtrim($rows -> area_list,',');
		// $sql2 		= "SELECT IF(parent in ($area_list),'',name) as area_name FROM main_identity_card WHERE number in ($area_list)";
		$sql2 		= "SELECT name  as area_name FROM main_identity_card WHERE number in ($area_list) AND parent not in ($area_list)";

		$result2 	= mysql_query($sql2,$_mysql_link_);
		while($rows2 = mysql_fetch_object($result2)){
			if($rows2 -> area_name != ''){
				$area_name .=  $rows2 -> area_name.', ';
			}
			$weight_fee['area_name'] = rtrim($area_name,', ');
		}
	}

	// 仓库
	$price_id						= $rows -> id;
	$sql3 = "SELECT s.store_id,i.name FROM company_express_store AS s LEFT JOIN store_info AS i ON s.store_id = i.id WHERE s.company_id='$company_id' AND s.express_id='$express_id' AND s.price_id='$price_id'";
	$result3 = mysql_query($sql3,$_mysql_link_);
	$store_id = '';
	$store_name = '';
	while($rows3 = mysql_fetch_object($result3)){
		$store_id .= $rows3 -> store_id.',';
		$store_name .= $rows3 -> name.', ';
	}
	$weight_fee['store_id'] = rtrim($store_id,',')?rtrim($store_id,','):'';
	$weight_fee['store_name'] = rtrim($store_name,', ');

	$rule = "";
	if($rows -> first_weight_1 != 0.00 ){
		$rule .= '首重部分：从0.00kg-'.$rows -> first_weight_1.'kg,费用为￥'.$rows -> first_price_1.';';
	} 
	if($rows -> first_weight_2 != 0.00 ){
		$rule .= '从'.$rows -> first_weight_1.'kg-'.$rows -> first_weight_2.'kg,费用为￥'.$rows -> first_price_2.';';
	}
	if($rows -> first_weight_3 != 0.00 ){
		$rule .= '从'.$rows -> first_weight_2.'kg-'.$rows -> first_weight_3.'kg,费用为￥'.$rows -> first_price_3.';';
	}
	if($rows -> first_weight_4 != 0.00 ){
		$rule .= '从'.$rows -> first_weight_3.'kg-'.$rows -> first_weight_4.'kg,费用为￥'.$rows -> first_price_4.';';
	}
	if($rows -> first_weight_5 != 0.00 ){
		$rule .= '从'.$rows -> first_weight_4.'kg-'.$rows -> first_weight_5.'kg,费用为￥'.$rows -> first_price_5.';';
	}
	if($rows -> weight_increase != 0.00 && $rows -> price_increase != 0){
		$rule .= ';续重部分：重量每增加'.$rows -> weight_increase.'kg,增加费用￥'.$rows -> price_increase.';';
	}
	$weight_fee['rule'] 				= $rule;

	if($rows -> area_list == ''){
		$main['default_rule'] 	= $rule;
	}else{
		$main['default_rule'] 	= "";
	}


	$xtpl -> assign("weight_fee",$weight_fee);
	$xtpl -> parse("main.weight_fee");
}

$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");