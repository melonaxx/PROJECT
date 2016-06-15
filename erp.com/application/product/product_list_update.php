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
	$sql= "SELECT id,name,company_id FROM product_info WHERE company_id='$company_id' AND is_delete='N' AND INSTR(name,'$seachText')";
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
if(!empty($_GET['id'])){
	$id = intval($_GET['id']);
	$sql = "SELECT a.attrib_id,d.price_display,d.format_id_1,d.format_id_2,d.format_id_3,d.format_id_4,d.format_id_5,d.value_id_1,d.value_id_2,d.value_id_3,d.value_id_4,d.value_id_5,i.id,i.number,i.name,i.brand_id,i.category_id,i.bar_code,i.product_type,i.product_quality,d.price_purchase,d.parts_id,d.volume,d.weight,d.content FROM product_info AS i LEFT JOIN product_detail AS d ON d.id = i.id LEFT JOIN product_attrib_list as a ON a.product_id = i.id WHERE i.id = '$id' AND is_delete = 'N'";
	$result = mysql_query($sql,$_mysql_link_);
	if(mysql_num_rows($result) < 1){
		illegal_operation();
	}
	$product 				= 	mysql_fetch_object($result);
	$main['attrib_id']		=	$product->attrib_id;
	$main['price_display']	=	$product->price_display;
	$main['format_1']		=	$product->format_id_1;
	$main['format_2']		=	$product->format_id_2;
	$main['format_3']		=	$product->format_id_3;
	$main['format_4']		=	$product->format_id_4;
	$main['format_5']		=	$product->format_id_5;
	$main['value_id_1']		=	$product->value_id_1;
	$main['value_id_2']		=	$product->value_id_2;
	$main['value_id_3']		=	$product->value_id_3;
	$main['value_id_4']		=	$product->value_id_4;
	$main['value_id_5']		=	$product->value_id_5;
	$main['price_purchase']	=	$product->price_purchase;
	$main['unit']			=	$product->parts_id;
	$main['volume']			=	$product->volume;
	$main['weight']			=	$product->weight;
	$main['content']		=	$product->content;
	$main['id']				=	$product->id;
	$main['number']			=	$product->number;
	$main['name']			=	$product->name;
	$main['brand_id']		=	$product->brand_id;
	$main['category_id']	=	$product->category_id;
	$main['bar_code']		=	$product->bar_code;
	$main['product_type']	=	$product->product_type;
	$main['product_quality']=	$product->product_quality;
	
	$sql = "SELECT id,accessory_id,total FROM product_accessory WHERE is_delete = 'N' AND product_id = '$id'";
	$result = mysql_query($sql,$_mysql_link_);
	$num = 1;
	while($store_array = mysql_fetch_object($result)){
		$accessory = array();
		$sql_2 = "SELECT name FROM product_info WHERE id = '{$store_array->accessory_id}' AND is_delete = 'N'";
		$result_2 = mysql_query($sql_2,$_mysql_link_);
		while($pice = mysql_fetch_object($result_2)){
			$accessory['accessory_id'] = $pice->name;
		}
		$accessory['id']			=	$store_array->accessory_id;
		$accessory['num']			=	$num++;
		$accessory['total']			=	$store_array->total;
		$xtpl->assign("accessory", $accessory);
		$xtpl->parse("main.accessory");
	}
	$num = 1;
	$sql = "SELECT attrib_id,value_id FROM product_attrib_list WHERE product_id = '$id' AND company_id = '$company_id'";
	$result = mysql_query($sql,$_mysql_link_);
	while($pike = mysql_fetch_object($result)){
		$likun = array();
		$likun['num']		= $num++;
		$likun['attrib_id'] = $pike->attrib_id;
		$likun['value_id']	= $pike->value_id;
		$sql_2 = "SELECT id,name FROM product_attrib_name WHERE id = '{$pike->value_id}'";
		$result_2 = mysql_query($sql_2,$_mysql_link_);
		while($pank = mysql_fetch_object($result_2)){
			$likun['id']	=	$pank->id;
		}
		$xtpl->assign("likun", $likun);
		$xtpl->parse("main.likun");
	}
}
if(!empty($_POST['send'])){
	$num = count($_POST['product']);
	$sql = "DELETE FROM product_accessory WHERE product_id = '{$_POST['id']}'";
	mysql_query($sql,$_mysql_link_);
	for($i=0;$i<$num;$i++){
	
	$sql = "INSERT INTO product_accessory SET product_id = '{$_POST['id']}',total = '{$_POST['total'][$i]}',accessory_id = '{$_POST['product'][$i]}'";
	mysql_query($sql,$_mysql_link_);
	}
	$count = count($_POST['attrib_id']);
	$sql = "DELETE FROM product_attrib_list WHERE product_id = '{$_POST['id']}'";
	mysql_query($sql,$_mysql_link_);
	for($i=0;$i<$count;$i++){
		if($_POST['attrib_id'][$i] == '0'){
				continue;
		}
	$sql = "INSERT INTO product_attrib_list SET company_id = '$company_id',attrib_id = '{$_POST['attrib_id'][$i]}',product_id = '{$_POST['id']}',value_id = '{$_POST['value_id'][$i]}'";
	mysql_query($sql,$_mysql_link_);
	}
	$number 		= replace_safe($_POST['number']);//商品编码
	$name 			= replace_safe($_POST['name']);//商品名称
	$brand			= intval($_POST['brand']);//商品品牌
	$fenlei			= intval($_POST['fenlei']);//商品分类
	$bar_code		= replace_safe($_POST['bar_code']);//商品条码
	$product_type	= replace_safe($_POST['product_type']);//产品类型
	$product_quality= replace_safe($_POST['product_quality']);//是否二手
	$price_purchase = floatval($_POST['price_purchase']);//进价
	$unit			= replace_safe($_POST['unit']);//单位
	$volume			= floatval($_POST['volume']);//体积
	$weight			= floatval($_POST['weight']);//重量
	$price_display	= floatval($_POST['price_display']);//零售价
	$content		= replace_safe($_POST['content']);//备注
	$format_name_1	= intval($_POST['format_name_1']);
	$format_name_2	= intval($_POST['format_name_2']);
	$format_name_3	= intval($_POST['format_name_3']);
	$format_name_4	= intval($_POST['format_name_4']);
	$format_name_5	= intval($_POST['format_name_5']);
	$format_value_1 = intval($_POST['format_value_1']);
	$format_value_2	= intval($_POST['format_value_2']);
	$format_value_3 = intval($_POST['format_value_3']);
	$format_value_4	= intval($_POST['format_value_4']);
	$format_value_5	= intval($_POST['format_value_5']);
	$id 			= intval($_POST['id']);
	if(empty($number))
	{
		//---- 如果没有编码则，自动生成供应商的编码 ----
		$number	= insert_company_number($_SESSION['_application_info_']["company_id"], "product");
	}
	if(empty($bar_code)){
		//---- 如果没有条码，自动生成供应商的编码 ----
		$bar_code = $number;
	}
	$sql = "UPDATE product_info SET company_id = '$company_id',number = '$number',name = '$name',category_id = '$fenlei',brand_id = '$brand',bar_code = '$bar_code',product_type = '$product_type',product_quality = '$product_quality' WHERE id = '$id'";
	mysql_query($sql,$_mysql_link_);
	$sql_detail = "UPDATE product_detail SET price_purchase = '$price_purchase',price_display = '$price_display',volume = '$volume',parts_id = '$unit',content = '$content',format_id_1 = '$format_name_1',format_id_2 = '$format_name_2',format_id_3 = '$format_name_3',format_id_4='$format_name_4',format_id_5 = '$format_name_5',value_id_1 = '$format_value_1',value_id_2 = '$format_value_2',value_id_3 = '$format_value_3',value_id_4 = '$format_value_4',value_id_5 = '$format_value_5' WHERE id = '$id'";
	mysql_query($sql_detail,$_mysql_link_);
	header('Location: /product/product_list.php');
	var_dump($sql_detail);
	exit;
}
$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");
