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
//---- 该公司创建的 规格名称 ----
$FormatName			= array();
$main['format_1']	= 0;
$main['format_2']	= 0;
$main['format_3']	= 0;
$main['format_4']	= 0;
$main['format_5']	= 0;
$sql		= "SELECT id, name FROM product_format_name WHERE company_id='$company_id' AND is_delete='N'";
$result		= mysql_query($sql, $_mysql_link_);
while($dbRow = mysql_fetch_object($result))
{
	$format_name	= array();
	$format_name['id']		= $dbRow->id;
	$format_name['name']	= $dbRow->name;
	$xtpl->assign("format_name", $format_name);
	$xtpl->parse("main.format_name");
	$FormatName[$dbRow->id]	= $dbRow->name;
}
//---- 该公司创建的 规格值 ----
$FormatValue		= array();
$sql	= "SELECT id, format_id, body FROM product_format_value WHERE company_id='$company_id' AND is_delete='N'";
$result	= mysql_query($sql, $_mysql_link_);
while($dbRow = mysql_fetch_object($result))
{
	$format_value	= array();
	$format_value['id']			= $dbRow->id;
	$format_value['name']		= $dbRow->body;
	$format_value['name_id']	= $dbRow->format_id;
	$xtpl->assign("format_value", $format_value);
	$xtpl->parse("main.format_value");
	$FormatValue[$dbRow->format_id][$dbRow->id]	= $dbRow->body;
}
//---- 该公司创建的 属性名称 ----
$AttribName			= array();
$sql		= "SELECT id, name FROM product_attrib_name WHERE company_id='$company_id' AND is_delete='N'";
$result		= mysql_query($sql, $_mysql_link_);
while($dbRow = mysql_fetch_object($result))
{
	$attrib_name	= array();
	$attrib_name['id']		= $dbRow->id;
	$attrib_name['name']	= $dbRow->name;
	$xtpl->assign("attrib_name", $attrib_name);
	$xtpl->parse("main.attrib_name");
	$AttribName[$dbRow->id]	= $dbRow->name;
}
//---- 该公司创建的 属性值 ----
$AttribValue	= array();
$sql	= "SELECT id, attrib_id, body FROM product_attrib_value WHERE company_id='$company_id' AND is_delete='N'";
$result	= mysql_query($sql, $_mysql_link_);
while($dbRow = mysql_fetch_object($result))
{
	$attrib_value	= array();
	$attrib_value['attrib_id']	= $dbRow->attrib_id;
	$attrib_value['body']		= $dbRow->body;
	$attrib_value['value_id']	= $dbRow->id;
	$xtpl->assign("attrib_value", $attrib_value);
	$xtpl->parse("main.attrib_value");
	$AttribValue[$dbRow->attrib_id][$dbRow->id]	= $dbRow->body;
}
//---- 该公司创建的 商品品牌 ----
$BrandName	= array();
$sql		= "SELECT id, name FROM product_brand WHERE company_id='$company_id' AND is_delete='N'";
$result		= mysql_query($sql, $_mysql_link_);
while($dbRow = mysql_fetch_object($result))
{
	$list_brand		= array();
	$list_brand['id']		= $dbRow->id;
	$list_brand['name']		= $dbRow->name;
	$list_brand['current']	= "N";
	$xtpl->assign("list_brand", $list_brand);
	$xtpl->parse("main.list_brand");
	$BrandName[$dbRow->id]	= $dbRow->name;
}
//---- 该公司创建的 商品分类 ----
$sql		= "SELECT id, name, parent_id FROM product_category WHERE company_id='$company_id' AND is_delete='N'";
$result		= mysql_query($sql, $_mysql_link_);
$ProductCategory	= array();
$CategoryInfo		= array();
while ($rows = mysql_fetch_object($result))
{
	$ProductCategory[$rows->parent_id][$rows->id]	= $rows->name;
	$CategoryInfo[$rows->id]	= $rows->name;
}
$ProductCategory	= get_sort_by_array($ProductCategory);
if(count($ProductCategory))
{
	foreach ($ProductCategory as $key => $list_category)
	{
		$list_category['name'] = str_repeat("　", $list_category['level'] - 1).$list_category['name'];
		$list_category['current']	= "N";
		$xtpl->assign("list_category", $list_category);
		$xtpl->parse("main.list_category");
	}
}
//---- 该公司拥有的店铺 ----
$ShopInfo	= array();
$sql	= "SELECT r.user_id, i.shop_name FROM company_related AS r LEFT JOIN user_register_info AS i ON r.user_id=i.id WHERE r.company_id='$company_id'";
$result	= mysql_query($sql, $_mysql_link_);
while($dbRow = mysql_fetch_object($result))
{
	$list_shop	= array();
	$list_shop['id']	= $dbRow->user_id;
	$list_shop['name']	= $dbRow->shop_name;
	$xtpl->assign("list_shop", $list_shop);
	$xtpl->parse("main.list_shop");
	$ShopInfo[$dbRow->user_id]	= $dbRow->shop_name;
}
//---- 该公司创建的 单位名称 ----
$PartsInfo	= array();
$sql	= "SELECT id, name FROM product_parts_name WHERE company_id='$company_id'";
$result	= mysql_query($sql, $_mysql_link_);
while($dbRow = mysql_fetch_object($result))
{
	$list_parts	= array();
	$list_parts['id']	= $dbRow->id;
	$list_parts['name']	= $dbRow->name;
	$xtpl->assign("list_parts", $list_parts);
	$xtpl->parse("main.list_parts");
	$PartsInfo[$dbRow->id]	= $dbRow->name;
}

$ProductType	= array();
$ProductType['Real']		= "实体产品";
$ProductType['Virtual']		= "虚拟产品";
$ProductType['Packaged']	= "套装产品";
$ProductType['Materials']	= "原材料";

$ProductQuality			= array();
$ProductQuality['New']	= "全新";
$ProductQuality['Used']	= "二手";

$ProductSale    = array();
$ProductSale['Onsale']    = "在售";
$ProductSale['Soldout']   = "下架";
$ProductSale['Stop']      = "停产";
$ProductSale['Stockout']  = "缺货";

if(!empty($_POST['name']))
{
	// var_dump($_POST['manager_num']);exit;
	$ProductInput	= array();
	$ProductInput['name']				= replace_safe($_POST['name']);
	$ProductInput['number']				= replace_safe($_POST['number']);
	$ProductInput['total']				= intval($_POST['total']);
	$ProductInput['brand_id']			= intval($_POST['brand_id']);
	$ProductInput['user_id']			= replace_safe($_POST['shop_user_id']);
	$ProductInput['category_id']		= intval($_POST['category_id']);
	$ProductInput['serial_number']		= replace_safe($_POST['serial_number']);
	$ProductInput['bar_code']			= replace_safe($_POST['bar_code']);
	$ProductInput['parts_id']			= intval($_POST['parts_id']);
	$ProductInput['price_tag']			= floatval($_POST['price_tag']);
	$ProductInput['price_purchase']		= floatval($_POST['price_purchase']);
	$ProductInput['price_display']		= floatval($_POST['price_display']);
	$ProductInput['price_total']		= floatval($_POST['price_total']);
	$ProductInput['volume']				= floatval($_POST['volume']);
	$ProductInput['weight']				= floatval($_POST['weight']);
	$ProductInput['content']			= replace_safe($_POST['content']);
	$ProductInput['format_id_1']		= intval($_POST['format_name_1']);
	$ProductInput['format_id_2']		= intval($_POST['format_name_2']);
	$ProductInput['format_id_3']		= intval($_POST['format_name_3']);
	$ProductInput['format_id_4']		= intval($_POST['format_name_4']);
	$ProductInput['format_id_5']		= intval($_POST['format_name_5']);
	$ProductInput['format_value_1']		= intval($_POST['format_value_1']);
	$ProductInput['format_value_2']		= intval($_POST['format_value_2']);
	$ProductInput['format_value_3']		= intval($_POST['format_value_3']);
	$ProductInput['format_value_4']		= intval($_POST['format_value_4']);
	$ProductInput['format_value_5']		= intval($_POST['format_value_5']);
	$product_image_1	= intval($_REQUEST['product_image_1']);
	$product_image_2	= intval($_REQUEST['product_image_2']);
	$product_image_3	= intval($_REQUEST['product_image_3']);
	$product_image_4	= intval($_REQUEST['product_image_4']);

	$tmp	= $_POST['product_type'];
	if(!isset($ProductType[$tmp]))
	{
		$_POST['product_type']			= "Real";
	}

	$tmp	= $_POST['product_quality'];
	if(!isset($ProductQuality[$tmp]))
	{
		$_POST['product_quality']		= "New";
	}

	$tmp    = $_POST['product_sale'];
	if(!isset($ProductSale[$tmp]))
	{
		$_POST['product_sale']          = "Onsale";
	}

	$ProductInput['product_type']		= $_POST['product_type'];
	$ProductInput['product_quality']	= $_POST['product_quality'];
	$ProductInput['product_sale']	    = $_POST['product_sale'];

	$tmp	= $ProductInput['brand_id'];
	if(!isset($BrandName[$tmp]))
	{
		//---- 品牌ID 不存在/不属于当前公司 ----
		$ProductInput['brand_id']		= 0;
	}

	$tmp	= $ProductInput['category_id'];
	if(!isset($CategoryInfo[$tmp]))
	{
		//---- 分类ID 不存在/不属于当前公司 ----
		$ProductInput['category_id']	= 0;
	}

	$tmp	= $ProductInput['parts_id'];
	if(!isset($PartsInfo[$tmp]))
	{
		//---- 单位ID 不存在/不属于当前公司 ----
		$ProductInput['parts_id']	= 0;
	}

	$tmp	= $ProductInput['user_id'];
	if(!isset($ShopInfo[$tmp]))
	{
		//---- 店铺ID 不存在/不属于当前公司 ----
		$ProductInput['user_id']	= 0;
	}
	//---- 判断规格是否属于当前公司 ----
	$vid	= $ProductInput['format_value_1'];
	$fid	= $ProductInput['format_id_1'];
	if(!isset($FormatValue[$fid][$vid]))
	{
		$ProductInput['format_id_1']	= 0;
		$ProductInput['format_value_1']	= 0;
	}

	$vid	= $ProductInput['format_value_2'];
	$fid	= $ProductInput['format_id_2'];
	if(!isset($FormatValue[$fid][$vid]))
	{
		$ProductInput['format_id_2']	= 0;
		$ProductInput['format_value_2']	= 0;
	}

	$vid	= $ProductInput['format_value_3'];
	$fid	= $ProductInput['format_id_3'];
	if(!isset($FormatValue[$fid][$vid]))
	{
		$ProductInput['format_id_3']	= 0;
		$ProductInput['format_value_3']	= 0;
	}

	$vid	= $ProductInput['format_value_4'];
	$fid	= $ProductInput['format_id_4'];
	if(!isset($FormatValue[$fid][$vid]))
	{
		$ProductInput['format_id_4']	= 0;
		$ProductInput['format_value_4']	= 0;
	}

	$vid	= $ProductInput['format_value_5'];
	$fid	= $ProductInput['format_id_5'];
	if(!isset($FormatValue[$fid][$vid]))
	{
		$ProductInput['format_id_5']	= 0;
		$ProductInput['format_value_5']	= 0;
	}
	if($ProductInput['number'] == "")
	{
		$ProductInput['number']	= insert_company_number($company_id, "product");
	}
	if($ProductInput['bar_code'] == "")
	{
		$ProductInput['bar_code'] = $ProductInput['number'];
	}

	$sql	= "INSERT INTO product_info SET company_id='$company_id'
			, name='".$ProductInput['name']."'
			, number='".$ProductInput['number']."'
			, total='".$ProductInput['total']."'
			, brand_id='".$ProductInput['brand_id']."'
			, cost='".$ProductInput['price_display']."'
			, category_id='".$ProductInput['category_id']."'
			, bar_code='".$ProductInput['bar_code']."'
			, serial_number='".$ProductInput['serial_number']."'
			, product_type='".$ProductInput['product_type']."'
			, product_quality='".$ProductInput['product_quality']."'
			";
	mysql_query($sql, $_mysql_link_);
	if(mysql_affected_rows($_mysql_link_) == 1)
	{
		$product_id	= mysql_insert_id($_mysql_link_);
		$sql	= "INSERT INTO product_detail SET id='$product_id'
			, parts_id='".$ProductInput['parts_id']."'
			, price_tag='".$ProductInput['price_tag']."'
			, price_purchase='".$ProductInput['price_purchase']."'
			, price_display='".$ProductInput['price_display']."'
			, price_total='".$ProductInput['price_total']."'
			, volume='".$ProductInput['volume']."'
			, weight='".$ProductInput['weight']."'
			, content='".$ProductInput['content']."'
			, format_id_1='".$ProductInput['format_id_1']."'
			, format_id_2='".$ProductInput['format_id_2']."'
			, format_id_3='".$ProductInput['format_id_3']."'
			, format_id_4='".$ProductInput['format_id_4']."'
			, format_id_5='".$ProductInput['format_id_5']."'
			, value_id_1='".$ProductInput['format_value_1']."'
			, value_id_2='".$ProductInput['format_value_2']."'
			, value_id_3='".$ProductInput['format_value_3']."'
			, value_id_4='".$ProductInput['format_value_4']."'
			, value_id_5='".$ProductInput['format_value_5']."'
			";
		mysql_query($sql, $_mysql_link_);
		//产品销量表
		$manager_num= intval($_POST["manager_num"]);
		$sqll	= "INSERT INTO product_sales SET id='$product_id'
			, sales_status='".$ProductInput['product_sale']."'
			, staff_id='".$manager_num."'
			";
		mysql_query($sqll, $_mysql_link_);

		if(is_array($_POST['attrib_id']))
		{
			//---- 商品属性 ----
			foreach($_POST['attrib_id'] as $ix => $a_id)
			{
				$a_id	= intval($a_id);
				$v_id	= intval($_POST['value_id'][$ix]);
				if(isset($AttribValue[$a_id][$v_id]))
				{
					$sql	= "INSERT INTO product_attrib_list SET company_id='$company_id', product_id='$product_id', attrib_id='$a_id', value_id='$v_id'";
					mysql_query($sql, $_mysql_link_);
				}
			}
		}
		if(is_array($_POST['accessory_id']))
		{
			//---- 商品配件 ----
			$AccessoryInfo	= array();
			$p_list	= "";
			foreach($_POST['accessory_id'] as $ix => $p_id)
			{
				$p_id	= intval($p_id);
				$p_list	= $p_list.",".$p_id;
				$AccessoryInfo[$p_id]	= intval($_POST['accessory_total'][$ix]);
			}
			$p_list	= trim($p_list, ",");
			if($p_list)
			{
				//---- 该公司的商品 作为配件 ----
				$sql	= "SELECT id FROM product_info WHERE company_id='$company_id' AND id IN ($p_list)";
				$result	= mysql_query($sql, $_mysql_link_);
				while($dbRow = mysql_fetch_object($result))
				{
					$total	= $AccessoryInfo[$dbRow->id];
					$sql	= "INSERT INTO product_accessory SET product_id='$product_id', accessory_id='$dbRow->id', total='$total'";
					mysql_query($sql, $_mysql_link_);
				}
			}
		}
		// //---- 插入0库存商品 ----
		// $sql	= "SELECT id FROM store_info WHERE company_id='$company_id'";
		// $result	= mysql_query($sql, $_mysql_link_);
		// while($dbRow = mysql_fetch_object($result))
		// {
		// 	$sql	= "INSERT INTO store_related SET company_id='$company_id', store_id='$dbRow->id', product_id='$product_id'";
		// 	mysql_query($sql, $_mysql_link_);
		// 	$sql	= "INSERT INTO store_product SET company_id='$company_id', store_id='$dbRow->id', product_id='$product_id'";
		// 	mysql_query($sql, $_mysql_link_);
		// }


		//---- 选择仓库插入库存（虚拟产品不计入库存） ----
		//获取传过来的仓库id（$_POST['store_id']）
		@$array = $_POST['store_id'];
		if(!empty($array) && $_POST['product_type'] !="Virtual"){
			for($i=0;$i<count($array);$i++){
				$sql = "INSERT INTO store_related SET company_id='$company_id', store_id='".$array[$i]."', product_id='$product_id'";
				mysql_query($sql, $_mysql_link_);
				$sqll = "INSERT INTO store_product SET company_id='$company_id', store_id='".$array[$i]."', product_id='$product_id'";
				mysql_query($sqll, $_mysql_link_);
			}
		}


		$the_image	= "";
		if($product_image_1 > 0)
		{
			//---- 图片ID是否存在 ----
			$sql	= "SELECT file_name, url FROM company_photo_info WHERE id='$product_image_1' AND company_id='$company_id'";
			$result	= mysql_query($sql, $_mysql_link_);
			if(mysql_num_rows($result))
			{
				$pInfo	= mysql_fetch_object($result);
				if(strlen($pInfo->url) > 10)
				{
					$the_image	= mysql_real_escape_string($pInfo->url, $_mysql_link_);
				}
				else if(strlen($pInfo->file_name) > 10)
				{
					$the_image	= "/upload/photo/".$pInfo->file_name;
				}
				$sql	= "UPDATE product_info SET image='$the_image' WHERE id='$product_id'";
				mysql_query($sql, $_mysql_link_);

				$sql	= "INSERT INTO product_photo SET company_id='$company_id', product_id='$product_id', photo_id='".$product_image_1."', sort='1'";
				mysql_query($sql, $_mysql_link_);
			}
		}

		if($product_image_2 > 0)
		{
			//---- 图片ID是否存在 ----
			$sql	= "SELECT file_name, url FROM company_photo_info WHERE id='$product_image_2' AND company_id='$company_id'";
			$result	= mysql_query($sql, $_mysql_link_);
			if(mysql_num_rows($result))
			{
				$sql	= "INSERT INTO product_photo SET company_id='$company_id', product_id='$product_id', photo_id='".$product_image_2."', sort='2'";
				mysql_query($sql, $_mysql_link_);
			}
		}
		if($product_image_3 > 0)
		{
			//---- 图片ID是否存在 ----
			$sql	= "SELECT file_name, url FROM company_photo_info WHERE id='$product_image_3' AND company_id='$company_id'";
			$result	= mysql_query($sql, $_mysql_link_);
			if(mysql_num_rows($result))
			{
				$sql	= "INSERT INTO product_photo SET company_id='$company_id', product_id='$product_id', photo_id='".$product_image_3."', sort='3'";
				mysql_query($sql, $_mysql_link_);
			}
		}
		if($product_image_4 > 0)
		{
			//---- 图片ID是否存在 ----
			$sql	= "SELECT file_name, url FROM company_photo_info WHERE id='$product_image_4' AND company_id='$company_id'";
			$result	= mysql_query($sql, $_mysql_link_);
			if(mysql_num_rows($result))
			{
				$sql	= "INSERT INTO product_photo SET company_id='$company_id', product_id='$product_id', photo_id='".$product_image_4."', sort='4'";
				mysql_query($sql, $_mysql_link_);
			}
		}
	}
	header("Location: /product/product_list.php");
	exit;
}

$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");
