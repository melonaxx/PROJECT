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
$id = intval($_GET['id']);
$main['id']    = $id;
//商品搜索
if(!empty($_GET['name'])){
	$name = replace_safe($_GET['name']);
	$sql = "SELECT product_info.id,product_info.name,product_info.image,
	product_detail.parts_id ,product_detail.price_display,product_detail.value_id_1,product_detail.value_id_2,product_detail.value_id_3,product_detail.value_id_4,product_detail.value_id_5
	FROM product_info
	LEFT JOIN product_detail on product_info.id = product_detail.id WHERE product_info.company_id='$company_id' AND product_info.is_delete='N' AND INSTR(product_info.name,'$name') limit 15";
	$this = mysql_query($sql,$_mysql_link_);
	$arr = array();
	while($StoreInfo = mysql_fetch_object($this)){
		$sql = "SELECT name FROM product_parts_name WHERE id='$StoreInfo->parts_id' AND company_id='$company_id'";
		$result = mysql_query($sql,$_mysql_link_);
		$res = mysql_fetch_object($result);

		$arr[] = array(
		'image'          => $StoreInfo->image,
		'name' 		     => $StoreInfo->name,
		'id'   		     => $StoreInfo->id,
		'price_display'  => $StoreInfo->price_display,
		'value_id_1'     => $StoreInfo->value_id_1,
		'value_id_2'     => $StoreInfo->value_id_2,
		'value_id_3'     => $StoreInfo->value_id_3,
		'value_id_4'     => $StoreInfo->value_id_4,
		'value_id_5'     => $StoreInfo->value_id_5
		);
		for($i=0;$i<count($arr);$i++){
			for($j=1;$j<=5;$j++){
				$format_id = $arr[$i]['value_id_'.$j];
				$sql1 = "SELECT body FROM product_format_value WHERE company_id = '$company_id' AND id = '$format_id'";
				$result1 = mysql_query($sql1,$_mysql_link_);
				while($re = mysql_fetch_object($result1)){
					$arr[$i]['value_id_'.$j] = $re->body;
				}
			}
		}
	}
	echo json_encode($arr);
	exit;
}

//商品change
if(!empty($_GET['change'])){
	$find = replace_safe($_GET['change']);
	$sql = "SELECT product_info.id,product_info.name,product_info.image,product_detail.parts_id,product_detail.price_display  FROM product_info LEFT JOIN product_detail ON product_info.id = product_detail.id WHERE product_info.id= '$find' AND product_info.company_id='$company_id'";
	$result = mysql_query($sql,$_mysql_link_);
	while($StoreInfo = mysql_fetch_object($result)){
		$arr = array();
		$arr['image']	= $StoreInfo->image;
		$arr['id']		= $StoreInfo->id;
		$arr['name'] 	= $StoreInfo->name;
		$arr['detail']  = $StoreInfo->detail;
		$arr['price_display'] = $StoreInfo->price_display;
	}
	echo json_encode($arr);
	exit;
}


if(isset($_POST['made'])){
	$product_id = replace_safe($_POST['product_id']);
	$id = intval($_POST['id']);
	$sql    = "SELECT COUNT(*) AS total from product_info WHERE company_id='$company_id' AND id='$product_id' AND is_delete='N'";
	$result = mysql_query($sql,$_mysql_link_);
	$res    = mysql_fetch_object($result);
	if($res->total > 0){
		$sql = "UPDATE product_related_info  SET product_id = '$product_id' WHERE company_id = '$company_id' AND id='$id'";
		mysql_query($sql,$_mysql_link_);
	}

	echo "<script>\n";
	echo "parent.$('#MessageBox').modal('hide');\n";
	echo "parent.location.replace(parent.location.href);";
	echo "</script>\n";
	echo "<center><br/><br/><br/><br/>修改完成！<br/><br/><br/><br/></center>";
	exit;
}




$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");