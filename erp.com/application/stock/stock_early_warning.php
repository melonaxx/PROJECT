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

//获取仓库名称
$sql = "SELECT id,name FROM store_info WHERE company_id = '$company_id' AND store_status != 'Delete' ";
$result = mysql_query($sql,$_mysql_link_);
while($dbRow = mysql_fetch_object($result)){
	$store_id 			= array();
	$store_id['id'] 	= $dbRow->id;
	$store_id['name']	= $dbRow->name;

	$xtpl->assign('store_id',$store_id);
	$xtpl->parse('main.store_id');
}


if(!empty($_GET['id'])){
	$id = intval($_GET['id']);
	$sql = "SELECT name FROM store_info WHERE company_id='$company_id' AND id='$id' ";
	$result = mysql_query($sql,$_mysql_link_);
	$main['id']	  = $id;
	$main['name'] = mysql_result($result,0,0);
	//获取规格值
	$FormatValue	= array();
	$sql = "SELECT id, body FROM product_format_value WHERE company_id = '$company_id'";
	$result  = mysql_query($sql,$_mysql_link_);
	while($x = mysql_fetch_object($result)){
		$FormatValue[$x->id]	= $x->body;
	}

	$sql = "SELECT p.store_id,p.is_warning,p.product_id,p.total_available,p.total_real,p.total_lock,p.total_way,p.upper,p.lower,i.name,i.image,i.bar_code,d.value_id_1,d.value_id_2,d.value_id_3,d.value_id_4,d.value_id_5 FROM store_product AS p LEFT JOIN product_info AS i ON p.product_id = i.id LEFT JOIN product_detail AS d ON p.product_id = d.id WHERE p.company_id = '$company_id' AND p.store_id = '$id' AND p.is_warning = 'Y' ";
	$result = mysql_query($sql,$_mysql_link_);
	$num = mysql_affected_rows($_mysql_link_);
	if($num == 0)
	{
		$main['num'] = $num;
	}
	$no = 1;
	while($dbRow = mysql_fetch_object($result)){
		if(($dbRow->upper > 0 &&$dbRow->total_available > $dbRow->upper) or ($dbRow->lower>0 && $dbRow->total_available < $dbRow->lower)){

			$warning 					= array();
			if($dbRow->total_available > $dbRow->upper){
				$warning['warning'] 	= 'upper';
			}
			if($dbRow->total_available < $dbRow->lower){
				$warning['warning'] 	= 'lower';
			}
			$warning['no']				= $no++;
			$warning['store_id'] 		= $dbRow->store_id;
			$warning['name'] 			= $dbRow->name;
			$warning['image'] 			= $dbRow->image;
			$warning['total_way'] 		= $dbRow->total_way;
			$warning['bar_code'] 		= $dbRow->bar_code;
			$warning['product_id'] 		= $dbRow->product_id;
			$warning['total_real'] 		= $dbRow->total_real;
			$warning['total_lock'] 		= $dbRow->total_lock;
			$warning['total_available'] = $dbRow->total_available;
			$warning['value_id_1'] 		= $FormatValue[$dbRow1->value_id_1];
			$warning['value_id_2'] 		= $FormatValue[$dbRow1->value_id_2];
			$warning['value_id_3'] 		= $FormatValue[$dbRow1->value_id_3];
			$warning['value_id_4'] 		= $FormatValue[$dbRow1->value_id_4];
			$warning['value_id_5'] 		= $FormatValue[$dbRow1->value_id_5];
			if($dbRow->upper > 0){
				$warning['upper'] = $dbRow->upper;
			}else{
				$warning['upper'] = '';
			}
			if($dbRow->lower > 0){
				$warning['lower'] = $dbRow->lower;
			}else{
				$warning['lower'] = '';
			}

			$xtpl->assign('warning',$warning);
			$xtpl->parse('main.warning');
		}

	}
}




$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");
