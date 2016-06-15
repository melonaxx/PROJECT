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

if (!isset($_GET['type']) || $_GET['type'] == 'assemble') {
	$type = 'assemble';
} else {
	$type = 'list';
}
$main['type'] = $type;

//ajax获取搜索的商品
if (isset($_GET['type']) && $_GET['type'] == 'search_good') {
	header("Content-Type: text/html; charset=UTF-8");

	$searchText = replace_safe($_POST['searchText']);
	//查找商品
	$sql = "SELECT id, name FROM product_info WHERE company_id='$company_id' AND is_delete='N' AND INSTR(name, '$searchText')";
	$result = mysql_query($sql, $_mysql_link_);

	$data = array();
	while ($rows = mysql_fetch_array($result, MYSQL_ASSOC))
	{
		$data[]	= $rows;
	}
	//查找详细
	foreach ($data as $key=>$value) {
		$sql = "SELECT id, unit, price_display, format_id_1, format_id_2, format_id_3, format_id_4, format_id_5, value_id_1, value_id_2, value_id_3, value_id_4, value_id_5 FROM product_detail WHERE id='{$value['id']}'";
		//$sql = "SELECT id FROM product_detail WHERE id='{$value['id']}'";
		$result = mysql_query($sql, $_mysql_link_);
		$detailData = mysql_fetch_array($result, MYSQL_ASSOC);

		$format_id_arr = array();
		$value_id_arr = array();
		if (!empty($detailData['format_id_1']) && !empty($detailData['value_id_1'])) {
			$format_id_arr[] = $detailData['format_id_1'];
			$value_id_arr[] = $detailData['value_id_1'];
		}
		if (!empty($detailData['format_id_2']) && !empty($detailData['value_id_2'])) {
			$format_id_arr[] = $detailData['format_id_2'];
			$value_id_arr[] = $detailData['value_id_2'];
		}
		if (!empty($detailData['format_id_3']) && !empty($detailData['value_id_3'])) {
			$format_id_arr[] = $detailData['format_id_3'];
			$value_id_arr[] = $detailData['value_id_3'];
		}
		if (!empty($detailData['format_id_4']) && !empty($detailData['value_id_4'])) {
			$format_id_arr[] = $detailData['format_id_4'];
			$value_id_arr[] = $detailData['value_id_4'];
		}
		if (!empty($detailData['format_id_5']) && !empty($detailData['value_id_5'])) {
			$format_id_arr[] = $detailData['format_id_5'];
			$value_id_arr[] = $detailData['value_id_5'];
		}

		//print_r($format_id_arr);
		//print_r($value_id_arr);
		if (!empty($format_id_arr)) {
			$format_id_str = implode(',', $format_id_arr);
			$value_id_str = implode(',', $value_id_arr);
			//echo $format_id_str.'|';
			//echo $value_id_str;

			$sql = "SELECT name FROM product_format_name WHERE id IN ($format_id_str)";
			//$sql = "SELECT id FROM product_detail WHERE id='{$value['id']}'";
			$result = mysql_query($sql, $_mysql_link_);
			while ($rows = mysql_fetch_array($result, MYSQL_ASSOC))
			{
				$formatNameData[]	= $rows;
			}
			print_r($formatNameData);
		}





		//$data[$key]['name'] =
	}
	print_r($data);
	exit;
}




//组合商品
if ($type == 'assemble') {
	//获取分类
	$sql = "SELECT id, name, parent_id FROM product_category WHERE company_id='{$company_id}' AND is_delete='N'";
	$result	= mysql_query($sql, $_mysql_link_);
	$GroupList = array();
	while ($rows = mysql_fetch_object($result))
	{
		$GroupList[$rows->parent_id][$rows->id]	= $rows->name;
	}
	$GroupList	= get_sort_by_array($GroupList);
	//print_r($GroupList);


	$main['sort_total'] = count($GroupList);
	if (!empty($GroupList)) {
		foreach ($GroupList as $key=>$value) {
			$value['name'] = str_repeat("　", $value['level'] - 1).$value['name'];
			$xtpl->assign("category", $value);
			$xtpl->parse("main.category");
		}
	}


	//获取品牌
	$sql = "SELECT id, name, parent_id FROM product_brand WHERE company_id='{$company_id}' AND is_delete='N'";
	$result	= mysql_query($sql, $_mysql_link_);
	$GroupList = array();
	while ($rows = mysql_fetch_object($result))
	{
		$GroupList[$rows->parent_id][$rows->id]	= $rows->name;
	}
	$GroupList	= get_sort_by_array($GroupList);
	//print_r($GroupList);

	$main['brand_total'] = count($GroupList);
	if (!empty($GroupList)) {
		foreach ($GroupList as $key=>$value) {
			$value['name'] = str_repeat("　", $value['level'] - 1).$value['name'];
			$xtpl->assign("brand", $value);
			$xtpl->parse("main.brand");
		}
	}

}

//ajax登场
if(!empty($_POST['value'])){
	$sql="SELECT id,body FROM product_format_value";
	$result=mysql_query($sql, $_mysql_link_);
	while ($x = mysql_fetch_object($result)) {
		$FormatValue[$x->id]	= $x->body;
	}
	$name = replace_safe($_POST['value']);
	$sql = "SELECT d.price_purchase,d.parts_id,d.price_display,i.id,i.name,d.value_id_1,d.value_id_2,d.value_id_3,d.value_id_4,value_id_5 FROM product_info AS i LEFT JOIN product_detail AS d ON d.id = i.id WHERE INSTR(i.name,'$name') AND i.company_id = '$company_id' AND i.is_delete = 'N'  AND i.have_combination = 'N' LIMIT 15";
	$result = mysql_query($sql,$_mysql_link_);
	while($mysql_object = mysql_fetch_object($result)){
		$sql = "SELECT name FROM product_parts_name WHERE company_id = '$company_id' AND id = '{$mysql_object->parts_id}'";
		$result_parts = mysql_query($sql,$_mysql_link_);
		$product_parts = mysql_fetch_object($result_parts);
		for($i=1;$i<=5;$i++){
			$v = "value_id_".$i;
			if(!$FormatValue[$mysql_object->$v]){
				$FormatValue[$mysql_object->$v] = "0";
			}
		}

		$date[] = array(
			'name' 				=> $mysql_object->name,
			'id'				=> $mysql_object->id,
			'price_display'		=> $mysql_object->price_display,
			'parts_id' 			=> $product_parts->name,
			'value_id_1'		=> $FormatValue[$mysql_object->value_id_1],
			'value_id_2'		=> $FormatValue[$mysql_object->value_id_2],
			'value_id_3'		=> $FormatValue[$mysql_object->value_id_3],
			'value_id_4'		=> $FormatValue[$mysql_object->value_id_4],
			'value_id_5'		=> $FormatValue[$mysql_object->value_id_5]
		);
	}
	echo json_encode($date);
	exit;
}
if(!empty($_POST['select_value'])){
	$value = $_POST['select_value'];
	$sql = "SELECT price_display,parts_id FROM product_detail WHERE id = '$value'";
	$result = mysql_query($sql,$_mysql_link_);
	$productdetail = mysql_fetch_object($result);
	$sql = "SELECT name FROM product_parts_name WHERE company_id = '$company_id' AND id = '{$productdetail->parts_id}'";
	$result_parts = mysql_query($sql,$_mysql_link_);
	$product_parts = mysql_fetch_object($result_parts);
	$data = array(
		'parts_id'=>$product_parts->name,
		'price_display'=>$productdetail->price_display
	);
	echo json_encode($data);
	exit;
}
if(!empty($_POST['send'])){
		$number = replace_safe($_POST['number']);
		if(empty($number)){
			$number	= insert_company_number($_SESSION['_application_info_']["company_id"], "product");
		}
		//添加组合完成的商品
		$name	= replace_safe($_POST['name']);
		$content  = replace_safe($_POST['content']);
		$classification = intval($_POST['classification']);
		$brand	= intval($_POST['brand']);
		$price_display = replace_safe($_POST['price_display']);
		$sql = "INSERT INTO product_info SET company_id = '$company_id',number = '$number',name = '$name',category_id = '$classification',brand_id = '$brand',is_store = 'Y',have_combination = 'Y'";
		mysql_query($sql,$_mysql_link_);
		if(mysql_affected_rows($_mysql_link_) == 1){
			$id = mysql_insert_id($_mysql_link_);
			$sql_detail = "INSERT INTO product_detail SET id = '$id',price_display = '$price_display',content='$content'";
			mysql_query($sql_detail,$_mysql_link_);
			//---- 组合商品不写入仓库 ----
			// //把添加的每个商品都放到仓库里面
			// $sql = "SELECT id FROM store_info WHERE company_id = '$company_id' AND store_status <> 'Delete'";
			// $result_store = mysql_query($sql,$_mysql_link_);
			// while($storeInfo = mysql_fetch_object($result_store)){
			// 	$sql_insert_store = "INSERT INTO store_related SET company_id = '$company_id',store_id = '{$storeInfo->id}',product_id = '$id',real_total = 0";
			// 	mysql_query($sql_insert_store,$_mysql_link_);
			// }

			//修改商品的组合单价
			// $product_id = $_POST['product']; 
			// $prj_pr = $_POST['price_group'];
			// for($i=0;$i<count($product_id);$i++){
			// 	$sql = "UPDATE product_detail set price_combination='$prj_pr[$i]' WHERE id='$product_id[$i]'";
			// 	mysql_query($sql,$_mysql_link_);

			// }
			


			for($i=0;$i<count($_POST['product']);$i++){
				$sql_product = "INSERT INTO product_combination SET product_id = '$id',sub_id = '{$_POST['product'][$i]}',total = '{$_POST['total'][$i]}'";
				mysql_query($sql_product,$_mysql_link_);
				//判断是否有product_sales
				$sql = "SELECT id FROM product_sales WHERE id='{$_POST['product'][$i]}'";
				$resu = mysql_query($sql,$_mysql_link_);
				if(mysql_num_rows($resu)<=0){
					$sql		= "INSERT INTO product_sales SET id='{$_POST['product'][$i]}'";
					mysql_query($sql, $_mysql_link_);
				}
			}
		}
}
$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");
