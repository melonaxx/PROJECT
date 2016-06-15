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

$company_id 	= $_SESSION['_application_info_']['company_id'];
$staff_id 		= $_SESSION['_application_info_']['staff_id'];
$main['date']	=	date("Y-m-d H:i");
// if(!empty($_GET['value'])){
// 	$value 			= intval($_GET['value']);
// 	$main['value'] 	= $value;
// 	$sql = "SELECT name FROM store_info WHERE store_status <> 'Delete' AND company_id = '$company_id' AND id = '$value'";
// 	$result 		= mysql_query($sql,$_mysql_link_);
// 	$store_info 	= mysql_result($result,0,0);
// 	$main['name'] 	= $store_info;
// }
if(!empty($_GET['total'])){
	$store_id 			= intval($_GET['value']);
	$piece 				= explode(",",$_GET['total']);
	$condition 			= explode(",",$_GET['condition']);
	$main['store_id'] 	= $store_id;
	$sql = "SELECT name FROM store_info WHERE company_id = '$company_id' AND store_status <> 'Delete' AND  id = '$store_id'";
	$result 		= mysql_query($sql,$_mysql_link_);
	$store_info 	= mysql_result($result,0,0);
	$main['name'] 	= $store_info;
	$main['bill_number'] 	= time();
	for($i=0;$i<count($condition);$i++){
		$sql="SELECT id,body FROM product_format_value";
		$result=mysql_query($sql, $_mysql_link_);
		while ($x = mysql_fetch_object($result)) {
			$FormatValue[$x->id]	= $x->body;
		}
		$sql = "SELECT i.image,i.name,i.number,p.total_real,d.value_id_1,d.value_id_2,d.value_id_3,d.value_id_4,d.value_id_5 FROM store_product AS p LEFT JOIN product_info AS i ON p.product_id = i.id LEFT JOIN product_detail AS d ON p.product_id = d.id WHERE p.company_id = '$company_id' AND p.store_id = '$store_id' AND p.product_id = '{$condition[$i]}'";
		$result = mysql_query($sql,$_mysql_link_);
		while($productinfo = mysql_fetch_object($result)){
			$product_info = array();
			$product_info['num']		= $i+1;
			$product_info['piece'] 	 	= $piece[$i];
			$product_info['product_id'] = $condition[$i];
			$product_info['name'] 		= $productinfo->name;
			$product_info['image']		= $productinfo->image;
			$product_info['number']		= $productinfo->number;
			$product_info['total_real'] = $productinfo->total_real;
			$product_info['loss']   	= intval($piece[$i]) - intval($productinfo->total_real);
			$value_1 					= $FormatValue[$productinfo->value_id_1];
			$value_2 					= $FormatValue[$productinfo->value_id_2];
			$value_3 					= $FormatValue[$productinfo->value_id_3];
			$value_4 					= $FormatValue[$productinfo->value_id_4];
			$value_5 					= $FormatValue[$productinfo->value_id_5];
			$format = $value_1.','.$value_2.','.$value_3.','.$value_4.','.$value_5;
			$product_info['format'] 	= rtrim($format,',');
			$xtpl->assign("product_info", $product_info);
			$xtpl->parse("main.product_info");
		}
	}
}
if(!empty($_POST['send'])){
	$action_date 	= date("Y-m-d H:i:s");
	$store_id 		= intval($_POST['store_id']);
	$bill_number 	= replace_safe($_POST['bill_number']);
	for($i=0;$i<count($_POST['product_id']);$i++){
		//在盘点单中增加一条记录
		$sql = "INSERT INTO store_inventory_list SET company_id = '$company_id',store_id = $store_id,product_id = '{$_POST['product_id'][$i]}',staff_id = '$staff_id',bill_number = '$bill_number',total = '{$_POST['loss'][$i]}',old_total='{$_POST['total_real'][$i]}',new_total='{$_POST['piece'][$i]}',action_date = '$action_date',body='{$_POST['body'][$i]}'";
		mysql_query($sql,$_mysql_link_);
		//修改商品表中的数量
		$sql_update = "UPDATE  product_info SET total = '{$_POST['loss'][$i]}'+total WHERE id = '{$_POST['product_id'][$i]}'";
		mysql_query($sql_update,$_mysql_link_);
		//修改store_product中的数量
		$sql_update_product = "UPDATE store_product SET total_real = '{$_POST['piece'][$i]}',total_available='{$_POST['loss'][$i]}'+total_available WHERE company_id = '$company_id' AND store_id = '$store_id' AND product_id = '{$_POST['product_id'][$i]}'";
		mysql_query($sql_update_product,$_mysql_link_);
		//修改store_related表中的实际数量可用数量
		$sql_update_related = "UPDATE store_related SET real_total = '{$_POST['piece'][$i]}',available_total='{$_POST['loss'][$i]}'+available_total WHERE company_id = '$company_id' AND store_id = '$store_id' AND product_id = '{$_POST['product_id'][$i]}'";
		mysql_query($sql_update_related,$_mysql_link_);
	}
	echo "<script>\n";
	echo "parent.$('#MessageBox').modal('hide');\n";
	echo "parent.location.replace(parent.location.href);";
	echo "</script>\n";
	echo "<center><br/><br/><br/><br/>盘点完成！<br/><br/><br/><br/></center>";
	exit;
}
$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");