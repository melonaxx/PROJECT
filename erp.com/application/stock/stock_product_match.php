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
if(!empty($_GET['id']))
{
	$id = intval($_GET['id']);
	$main['location_id'] = $id;
	//通过库位id获取库位所在仓库id和货架id和货位编码(货位名)
	$sql = "SELECT store_id,parent_id,name FROM store_location WHERE company_id='$company_id' AND id='$id' ";
	$result   = mysql_query($sql,$_mysql_link_);
	$store_id = mysql_result($result,0,0);
	$main['store_id'] = $store_id;
	$shelves_id = mysql_result($result,0,1);
	$main['location_name'] = mysql_result($result,0,2);
	//通过货架id获取货架名和库区id
	$sql = "SELECT name,parent_id FROM store_location WHERE company_id='$company_id' AND id='$shelves_id' ";
	$result = mysql_query($sql,$_mysql_link_);
	$main['shelves_name'] = mysql_result($result,0,0);
	$area_id = mysql_result($result,0,1);
	//通过库区id获取库区名
	$sql = "SELECT name FROM store_location WHERE company_id='$company_id' AND id='$area_id' ";
	$result = mysql_query($sql,$_mysql_link_);
	$main['area_name'] = mysql_result($result,0,0);
	//获取仓库名
	$sql = "SELECT name FROM store_info WHERE company_id='$company_id' AND id='$store_id' ";
	$result = mysql_query($sql,$_mysql_link_);
	$main['store_name'] = mysql_result($result,0,0);

	//查询规格值
	$sql = "SELECT id,body FROM product_format_value WHERE company_id='$company_id' ";
	$result = mysql_query($sql,$_mysql_link_);
	$value = array();
	while($dbRow = mysql_fetch_object($result))
	{
		$value[$dbRow->id] = $dbRow->body;
	}
	//查询该库位的商品
	$sql = "SELECT r.store_id,r.location_id,r.product_id,i.name,i.image,d.parts_id,d.value_id_1,d.value_id_2,d.value_id_3,d.value_id_4,d.value_id_5 FROM store_related AS r LEFT JOIN product_info AS i ON r.product_id=i.id LEFT JOIN product_detail AS d ON r.product_id=d.id WHERE r.company_id='$company_id' AND r.store_id='$store_id' AND r.location_id='$id' ";
	$result = mysql_query($sql,$_mysql_link_);
	$no = 1;//定义序号
	while($dbRow = mysql_fetch_object($result))
	{
		$product 					= array();
		//根据单位id获取单位名称
		$sql = "SELECT name FROM product_parts_name WHERE company_id='$company_id' AND id='{$dbRow->parts_id}' ";
		$res = mysql_query($sql,$_mysql_link_);
		while($parts = mysql_fetch_object($res))
		{
			$product['parts_id'] 	= $parts->name;
		}

		$product['no']				= $no++;
		$product['name']			= $dbRow->name;
		$product['image']			= $dbRow->image;
		$product['store_id']		= $dbRow->store_id;
		$product['product_id']		= $dbRow->product_id;
		$product['location_id']		= $dbRow->location_id;
		$value_id_1					= $value[$dbRow->value_id_1];
		$value_id_2					= $value[$dbRow->value_id_2];
		$value_id_3					= $value[$dbRow->value_id_3];
		$value_id_4					= $value[$dbRow->value_id_4];
		$value_id_5					= $value[$dbRow->value_id_5];
		$format	= $value_id_1.','.$value_id_2.','.$value_id_3.','.$value_id_4.','.$value_id_5;
		$product['format']			= rtrim($format,',');

		$xtpl->assign('product',$product);
		$xtpl->parse('main.product');
	}
}

$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");