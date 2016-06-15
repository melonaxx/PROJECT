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
if(!empty($_GET['id'])){
	$id = intval($_GET['id']);
	$main['id'] = $id;
	$sql = "SELECT i.number,i.name,i.category_id,i.brand_id,d.price_display,d.content FROM product_info AS i LEFT JOIN product_detail AS d ON d.id = i.id WHERE i.id = '$id' AND i.company_id = '$company_id' AND i.is_delete = 'N'";
	$result = mysql_query($sql,$_mysql_link_);
	$product_info = mysql_fetch_object($result);
	$main['number'] 		= $product_info->number;
	$main['name'] 			= $product_info->name;
	$main['price_display']	= $product_info->price_display;
	$main['brand_id']		= $product_info->brand_id;
	$main['category_id']	= $product_info->category_id;
	$main['content']        = $product_info->content;
	$sql="SELECT id,body FROM product_format_value";
	$result=mysql_query($sql, $_mysql_link_);
	while ($x = mysql_fetch_object($result)) {
		$FormatValue[$x->id]	=$x->body;
	}
	$sj="";
	$sql = "SELECT sub_id,total FROM product_combination WHERE product_id = '$id'";
	$result_combination = mysql_query($sql,$_mysql_link_);
	while($product_combination = mysql_fetch_object($result_combination)){
		$productcombination = array();
		$productcombination['total'] = $product_combination->total;
		$productcombination['subid'] = $product_combination->sub_id;
		$sql_info= "SELECT i.id,i.name,d.price_display,d.parts_id,d.value_id_1,d.value_id_2,d.value_id_3,d.value_id_4,d.value_id_5,d.content FROM product_info AS i LEFT JOIN product_detail AS d ON i.id = d.id WHERE i.id = '{$product_combination->sub_id}' AND i.is_delete = 'N' AND i.company_id = '$company_id'";
		$result_info = mysql_query($sql_info,$_mysql_link_);
		while($product_info = mysql_fetch_object($result_info)){
			$productcombination['sub_id'] 			= $product_info->name;
			$productcombination['price_display']	= $product_info->price_display;
			$productcombination['id'] 				= $product_info->id;
			$productcombination['content']          = $product_info->content;
			$productcombination['value_id_1']		= $FormatValue[$product_info->value_id_1];
			$productcombination['value_id_2']		= $FormatValue[$product_info->value_id_2];
			$productcombination['value_id_3']		= $FormatValue[$product_info->value_id_3];
			$productcombination['value_id_4']		= $FormatValue[$product_info->value_id_4];
			$productcombination['value_id_5']		= $FormatValue[$product_info->value_id_5];
			$sql_parts = "SELECT name FROM product_parts_name WHERE id = '{$product_info->parts_id}' AND company_id = '$company_id'";
			$result_parts = mysql_query($sql_parts,$_mysql_link_);
			while($product_parts = mysql_fetch_object($result_parts)){
				$productcombination['name'] = $product_parts->name;
			}

			$sj += $product_combination->total*$product_info->price_display;
		}
		$main['sj'] = $sj;
		$xtpl->assign("productcombination", $productcombination);
		$xtpl->parse("main.productcombination");
	}
}
//查询分类
$sql = "SELECT id,name FROM product_category WHERE company_id = '$company_id' AND is_delete = 'N'";
$result_category = mysql_query($sql,$_mysql_link_);
while($productcategory = mysql_fetch_object($result_category)){
	$product_category = array();
	$product_category['id'] 	= $productcategory->id;
	$product_category['name']	= $productcategory->name;
	$xtpl->assign("product_category", $product_category);
	$xtpl->parse("main.product_category");
}
//商品品牌
$sql = "SELECT id,name FROM product_brand WHERE company_id = '$company_id' AND is_delete = 'N'";
$result_brand = mysql_query($sql,$_mysql_link_);
while($productbrand = mysql_fetch_object($result_brand)){
	$product_brand = array();
	$product_brand['id'] 	= $productbrand->id;
	$product_brand['name']	= $productbrand->name;
	$xtpl->assign("product_brand",$product_brand);
	$xtpl->parse("main.product_brand");
}
if(!empty($_POST['send'])){
	$id = intval($_POST['id']);
	$number 		= replace_safe($_POST['number']);
	if(empty($number)){
		$number	= insert_company_number($_SESSION['_application_info_']["company_id"], "product");
	}
	$name  			= replace_safe($_POST['name']);
	$category_id	= intval($_POST['classification']);
	$brand_id		= intval($_POST['brand']);
	$price_display	= floatval($_POST['price_display']);
	$content        = replace_safe($_POST['content']);
	// echo $content;
	$sql = "UPDATE product_info SET number = '$number',name = '$name',category_id = '$category_id',brand_id = '$brand_id' WHERE id = '$id'";
	mysql_query($sql,$_mysql_link_);
	$sql = "UPDATE product_detail SET price_display = '$price_display',content='$content' WHERE id = '$id'";
	mysql_query($sql,$_mysql_link_);

	//修改商品的组合单价
	// $product_id = $_POST['product']; 
	// $prj_pr = $_POST['price_group'];
	// for($i=0;$i<count($product_id);$i++){
	// 	$mkd = intval($product_id[$i]);
	// 	$sql = "UPDATE product_detail set price_combination='$prj_pr[$i]' WHERE id='$mkd'";
	// 	mysql_query($sql,$_mysql_link_);

	// }

	// 改变子商品
	$sql = "DELETE FROM product_combination WHERE product_id ='$id'";
	mysql_query($sql,$_mysql_link_);
	for($i = 0;$i < count($_POST['product']);$i++){
		$sql = "INSERT INTO product_combination SET product_id = '$id',sub_id = '{$_POST['product'][$i]}',total = '{$_POST['total'][$i]}'";
		mysql_query($sql,$_mysql_link_);
	}
	header("location: /product/product_commodity_assembly_list.php");
	exit;
}
$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");