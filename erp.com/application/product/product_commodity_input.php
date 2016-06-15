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


if (isset($_POST['send'])){
	$price_purchase		= floatval($_POST['price_purchase']);//进价
	$price_display		= floatval($_POST['price_display']);//零售价
	$weight				= floatval($_POST['weight']);//重量
	$volume				= floatval($_POST['volume']);//体积
	$unit				=replace_safe($_POST['unit']);
	$format_id_1		=intval($_POST['format_name_1']);
	$format_id_2		=intval($_POST['format_name_2']);
	$format_id_3		=intval($_POST['format_name_3']);
	$format_id_4		=intval($_POST['format_name_4']);
	$format_id_5		=intval($_POST['format_name_5']);
	$value_id_1			=intval($_POST['format_value_1']);
	$value_id_2			=intval($_POST['format_value_2']);
	$value_id_3			=intval($_POST['format_value_3']);
	$value_id_4			=intval($_POST['format_value_4']);
	$value_id_5			=intval($_POST['format_value_5']);
	$content			= replace_safe($_POST['content']);//商品备注
	$number				= replace_safe($_POST['number']);//商品编码
	$name				= replace_safe($_POST['name']);//商品名称	
	$category_id		=intval($_POST['fenlei']);
	$brand_id			=intval($_POST['brand']);
	$bar_code			= replace_safe($_POST['bar_code']);//商品条码
	$product_type		= replace_safe($_POST['product_type']);//产品类型
	$product_quality	= replace_safe($_POST['product_quality']);

	if(empty($number))
	{
		//---- 如果没有编码则，自动生成供应商的编码 ----
		$number	= insert_company_number($_SESSION['_application_info_']["company_id"], "product");
	}
	if(empty($bar_code)){
		//---- 如果没有条码，自动生成供应商的编码 ----
		$bar_code = $number;
	}
	 $sql = "INSERT INTO product_info SET 
	 	company_id			='$company_id',
		number				='$number',
		name				='$name',
		bar_code			='$bar_code',
		category_id			='$category_id',
		brand_id			='$brand_id',
		is_store			='Y',
		is_delete			='N',
		have_sku			='Y',
		product_type		='$product_type',
		product_quality		='$product_quality'
	 ";
	mysql_query($sql, $_mysql_link_);
	if(mysql_affected_rows($_mysql_link_) == 1){
		$product_id	= mysql_insert_id($_mysql_link_);
		$sql_store = "SELECT id FROM store_info WHERE company_id = '$company_id' AND store_status <> 'Delete'";
		$result_store = mysql_query($sql_store,$_mysql_link_);
		while($store_info = mysql_fetch_object($result_store)){
			$sql_insert_related = "INSERT INTO store_related SET company_id = '$company_id',store_id = '{$store_info->id}',product_id = '$product_id',real_total = 0";
			mysql_query($sql_insert_related,$_mysql_link_);
		}
	$sql = "INSERT INTO product_detail SET 
		id				='$product_id',
		price_purchase	='$price_purchase',
		price_display	='$price_display',
		weight			='$weight',
		volume			='$volume',
		parts_id			='$unit',
	 	format_id_1		='$format_id_1',
	 	format_id_2		='$format_id_2',
	 	format_id_3		='$format_id_3',
		format_id_4		='$format_id_4',
	 	format_id_5		='$format_id_5',
		value_id_1		='$value_id_1',
	 	value_id_2		='$value_id_2',	
		value_id_3		='$value_id_3',
		value_id_4		='$value_id_4',
		value_id_5		='$value_id_5',
		content			='$content'
	 ";
	mysql_query($sql, $_mysql_link_);
	//添加到商品配件表
	$number = count($_POST['product']);
	for($i=0;$i<$number;$i++){
		$sql_accessory = "INSERT INTO product_accessory SET product_id = '$product_id',accessory_id = '{$_POST['product'][$i]}',total = '{$_POST['total'][$i]}'";
		mysql_query($sql_accessory,$_mysql_link_);
	}
		$num = count($_POST['attrib_id']);
		for($i=0;$i<$num;++$i){
			if($_POST['attrib_id'][$i] == '0'){
				continue;
			}
			$sql = "INSERT INTO product_attrib_list SET company_id = '$company_id',attrib_id = '{$_POST['attrib_id'][$i]}',product_id = '$product_id',value_id = '{$_POST['value_id'][$i]}'";
			mysql_query($sql,$_mysql_link_);
		}
	}
	header('Location: /product/product_list.php');
	exit;
}




$sqll= "SELECT id,name FROM product_parts_name WHERE company_id='$company_id'";
$msql=mysql_query($sqll, $_mysql_link_);
	while($SupplierInfo = mysql_fetch_object($msql)){
		$value	= array();
		$value['id']			= $SupplierInfo->id;
		$value['name']			= $SupplierInfo->name;
		$xtpl->assign("supplierList", $value);
		$xtpl->parse("main.supplierList");	
	}

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
	
	// 获取品牌
	$sql = "SELECT id, name, parent_id FROM product_brand WHERE company_id='{$company_id}' AND is_delete='N'";
	$result	= mysql_query($sql, $_mysql_link_);
	$GroupList = array();
	while ($rows = mysql_fetch_object($result))
	{
		$GroupList[$rows->parent_id][$rows->id]	= $rows->name;
	}
	$GroupList	= get_sort_by_array($GroupList);
	// print_r($GroupList);
	
	$main['brand_total'] = count($GroupList);
	if (!empty($GroupList)) {
		foreach ($GroupList as $key=>$value) {
			$value['name'] = str_repeat("　", $value['level'] - 1).$value['name'];
			$xtpl->assign("brand", $value);
			$xtpl->parse("main.brand");
		}
	}
	

	//获取属性名
	$sql_product_attrib_name = "SELECT name,id FROM product_attrib_name WHERE company_id = '$company_id'";
	$result = mysql_query($sql_product_attrib_name,$_mysql_link_);
	while($product_attrib = mysql_fetch_object($result)){
		$product = array();
		$product['name'] =	$product_attrib->name;
		$product['id']	 =  $product_attrib->id;
		$xtpl->assign("product", $product);
		$xtpl->parse("main.product");
	}
	if(!empty($_POST['value'])){
		$value = $_POST['value'];
		$sql = "SELECT body,id FROM  product_attrib_value WHERE company_id = '$company_id' AND attrib_id = '$value'";
		$result = mysql_query($sql,$_mysql_link_);
		while($array_value = mysql_fetch_object($result)){
			$data[] = array(
				'body' => $array_value->body,
				'id'   => $array_value->id
			);
		}
		echo json_encode($data);
		exit;
	}


$main['format_1']	= 0;
$main['format_2']	= 0;
$main['format_3']	= 0;
$main['format_4']	= 0;
$main['format_5']	= 0;
$v	= 1;
$sqll	= "SELECT id, name FROM product_format_name WHERE company_id='$company_id'";
$msql	= mysql_query($sqll, $_mysql_link_);
	while($SupplierInfo = mysql_fetch_object($msql)){
		$tmp		= "format_".$v;
		$main[$tmp]	= $SupplierInfo->id;
		$v++;
		$format_name	= array();
		$format_name['id']		= $SupplierInfo->id;
		$format_name['name']	= $SupplierInfo->name;
		$xtpl->assign("format_name", $format_name);
		$xtpl->parse("main.format_name");
	}
$main['format_1']	= 0;
$main['format_2']	= 0;
$main['format_3']	= 0;
$main['format_4']	= 0;
$main['format_5']	= 0;

$sqll= "SELECT id, format_id, body FROM product_format_value WHERE company_id='$company_id'";
$msql=mysql_query($sqll, $_mysql_link_);
	while($SupplierInfo = mysql_fetch_object($msql)){
		$format_value	= array();
		$format_value['id']			= $SupplierInfo->id;
		$format_value['name']		= $SupplierInfo->body;
		$format_value['name_id']	= $SupplierInfo->format_id;
		$xtpl->assign("format_value", $format_value);
		$xtpl->parse("main.format_value");
	}

$sqll= "SELECT id, name FROM product_attrib_name WHERE company_id='$company_id'";
	$msql=mysql_query($sqll, $_mysql_link_);
		while($SupplierInfo = mysql_fetch_object($msql)){
			$attrib_name	= array();
			$attrib_name['id']		= $SupplierInfo->id;
			$attrib_name['name']	= $SupplierInfo->name;
			$xtpl->assign("attrib_name", $attrib_name);
			$xtpl->parse("main.attrib_name");
		}


$sqll= "SELECT id, body,attrib_id FROM product_attrib_value WHERE company_id='$company_id'";
$msql=mysql_query($sqll, $_mysql_link_);
	while($SupplierInfo = mysql_fetch_object($msql)){
		$attrib_value	= array();
		$attrib_value['value_id']		= $SupplierInfo->id;
		$attrib_value['body']			= $SupplierInfo->body;
		$attrib_value['attrib_id']		= $SupplierInfo->attrib_id;
		$xtpl->assign("attrib_value", $attrib_value);
		$xtpl->parse("main.attrib_value");
	}
//店铺
$sql_taobao = "SELECT id,shop_title FROM user_register_taobao";
$result = mysql_query($sql_taobao,$_mysql_link_);
while($tao = mysql_fetch_object($result)){
	$bao = array();
	$bao['id']	=	$tao->id;
	$bao['shop_title']= $tao->shop_title;
	$xtpl->assign("bao", $bao);
	$xtpl->parse("main.bao");
}

if(isset($_POST['seachText'])){
	header("Content-Type: text/html; charset=UTF-8");
	$seachText	=replace_safe($_POST['seachText']);
	$sql= "SELECT id,name,company_id FROM product_info WHERE company_id='$company_id' AND is_delete='N' AND name LIKE '%$seachText%'";
	$msql=mysql_query($sql, $_mysql_link_);
	while($SupplierInfo = mysql_fetch_object($msql)){
		$arrays[]= array(
		'id'	=> $SupplierInfo->id,
		'name'	=> $SupplierInfo->name
			);
	}
	echo json_encode($arrays);
	exit;
}
$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");
