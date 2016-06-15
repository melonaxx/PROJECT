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

$_default_photo_dir_	= "/upload/photo/";

$company_id = $_SESSION['_application_info_']['company_id'];
$id			= intval($_REQUEST['id']);

//查询是否为线上商品
$sql = "SELECT info_id FROM product_related_info WHERE  product_id='$id' AND company_id='$company_id'";
$res = mysql_query($sql, $_mysql_link_);
$nas = mysql_num_rows($res);
if($nas>0){
	while($rows = mysql_fetch_object($res)){
		$info_id = $rows->info_id;
	}
	$main['info_id'] = $info_id;
}else{
	$main['info_id'] = "";
}
//---- 商品信息 ----
$sql		= "SELECT number, parent_id, name, cost, total, category_id, brand_id, is_store, is_delete, have_sku, have_combination, bar_code, serial_number, product_type, product_quality, image FROM product_info WHERE id='$id' AND company_id='$company_id'";
$result		= mysql_query($sql, $_mysql_link_);
if(mysql_num_rows($result) < 1)
{
	header("Location: /product/product_list.php");
	exit;
}
$ProductInfo	= mysql_fetch_object($result);

//---- 商品规格 ----
$sql		= "SELECT price_tag, price_purchase, price_display, price_total, weight, volume, parts_id, format_id_1, format_id_2, format_id_3, format_id_4, format_id_5, value_id_1, value_id_2, value_id_3, value_id_4, value_id_5, content FROM product_detail WHERE id='$id'";
$result		= mysql_query($sql, $_mysql_link_);
if(mysql_num_rows($result))
{
	$ProductDetail	= mysql_fetch_object($result);
	$ProductInfo->price_tag			= $ProductDetail->price_tag;
	$ProductInfo->price_purchase	= $ProductDetail->price_purchase;
	$ProductInfo->price_display		= $ProductDetail->price_display;
	$ProductInfo->price_total		= $ProductDetail->price_total;
	$ProductInfo->weight			= $ProductDetail->weight;
	$ProductInfo->volume			= $ProductDetail->volume;
	$ProductInfo->parts_id			= $ProductDetail->parts_id;
	$ProductInfo->format_id_1		= $ProductDetail->format_id_1;
	$ProductInfo->format_id_2		= $ProductDetail->format_id_2;
	$ProductInfo->format_id_3		= $ProductDetail->format_id_3;
	$ProductInfo->format_id_4		= $ProductDetail->format_id_4;
	$ProductInfo->format_id_5		= $ProductDetail->format_id_5;
	$ProductInfo->value_id_1		= $ProductDetail->value_id_1;
	$ProductInfo->value_id_2		= $ProductDetail->value_id_2;
	$ProductInfo->value_id_3		= $ProductDetail->value_id_3;
	$ProductInfo->value_id_4		= $ProductDetail->value_id_4;
	$ProductInfo->value_id_5		= $ProductDetail->value_id_5;
	$ProductInfo->content			= $ProductDetail->content;
}
else
{
	$sql		= "INSERT INTO product_detail SET id='$id'";
	mysql_query($sql, $_mysql_link_);
	$ProductInfo->price_tag			= "0.00";
	$ProductInfo->price_purchase	= "0.00";
	$ProductInfo->price_display		= "0.00";
	$ProductInfo->price_total		= "0.00";
	$ProductInfo->weight			= 0;
	$ProductInfo->volume			= 0;
	$ProductInfo->parts_id			= 0;
	$ProductInfo->format_id_1		= 0;
	$ProductInfo->format_id_2		= 0;
	$ProductInfo->format_id_3		= 0;
	$ProductInfo->format_id_4		= 0;
	$ProductInfo->format_id_5		= 0;
	$ProductInfo->value_id_1		= 0;
	$ProductInfo->value_id_2		= 0;
	$ProductInfo->value_id_3		= 0;
	$ProductInfo->value_id_4		= 0;
	$ProductInfo->value_id_5		= 0;
	$ProductInfo->content			= "";
}

//---- 商品销量信息 ----
$sql		= "SELECT sales_status, staff_id FROM product_sales WHERE id='$id'";
$result		= mysql_query($sql, $_mysql_link_);
if(mysql_num_rows($result))
{
	$ProductSale	= mysql_fetch_object($result);
	$ProductInfo->sell_state		    = $ProductSale->sales_status;
	$ProductInfo->product_manager_num	= $ProductSale->staff_id;
	
	//根据id查产品经理
	$sql = "SELECT nick FROM company_staff_info WHERE id='{$ProductSale->staff_id}'";
	$res = mysql_query($sql, $_mysql_link_);
	$rows = mysql_fetch_object($res);
	$ProductInfo->product_manager		= $rows->nick;
}else{
	$sql		= "INSERT INTO product_sales SET id='$id'";
	$ll = mysql_query($sql, $_mysql_link_);
}

//---- 该公司创建的 规格名称 ----
$FormatName			= array();
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
	if($ProductInfo->brand_id == $dbRow->id)
	{
		$list_brand['current']	= "Y";
	}
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
		if($ProductInfo->category_id == $list_category['id'])
		{
			$list_category['current']	= "Y";
		}
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
	$list_shop['current']	= "N";
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
	$list_parts['current']	= "N";
	if($ProductInfo->parts_id == $dbRow->id)
	{
		$list_parts['current']	= "Y";
	}
	$xtpl->assign("list_parts", $list_parts);
	$xtpl->parse("main.list_parts");
	$PartsInfo[$dbRow->id]	= $dbRow->name;
}
//---- 该商品所在的库 ----
$sql = "SELECT i.id,i.name,p.total_real from store_info AS i LEFT JOIN store_product AS p On p.store_id=i.id WHERE p.company_id='$company_id' AND p.product_id='$id' AND p.is_frozen='N'";
$result	= mysql_query($sql, $_mysql_link_);
$total_num = 0;
while($dbRow = mysql_fetch_object($result))
{
	$total_num += $dbRow->total_real;
	$shop_store = array();
	$shop_store['id'] = $dbRow->id;
	$shop_store['name'] = $dbRow->name;
	$xtpl->assign("shop_store", $shop_store);
	$xtpl->parse("main.shop_store");
}
$main['total_num'] = $total_num;
$ProductType	= array();
$ProductType['Real']		= "实体产品";
$ProductType['Virtual']		= "虚拟产品";
$ProductType['Packaged']	= "套装产品";
$ProductType['Materials']	= "原材料";

$ProductQuality			= array();
$ProductQuality['New']	= "全新";
$ProductQuality['Used']	= "二手";

$ProductSale 		= array();
$ProductSale['Onsale']     = "在售";
$ProductSale['Soldout']    = "下架";
$ProductSale['Stop']       = "停产";
$ProductSale['Stockout']   = "缺货";



//---- 商品属性 ----
$AttribList	= array();
$sql		= "SELECT attrib_id, value_id FROM product_attrib_list WHERE company_id='$company_id' AND product_id='$id'";
$result		= mysql_query($sql, $_mysql_link_);
while($dbRow = mysql_fetch_object($result))
{
	$list_attrib	= array();
	$list_attrib['attrib_id']	= $dbRow->attrib_id;
	$list_attrib['value_id']	= $dbRow->value_id;
	$xtpl->assign("list_attrib", $list_attrib);
	$xtpl->parse("main.list_attrib");
	$AttribList[$dbRow->attrib_id]	= $dbRow->value_id;
}
//---- 商品配件 ----
$AccessoryList	= array();
$sql		= "SELECT accessory_id, total FROM product_accessory WHERE product_id='$id'";
$result		= mysql_query($sql, $_mysql_link_);
while($dbRow = mysql_fetch_object($result))
{
	$AccessoryList[$dbRow->accessory_id]	= $dbRow->total;
}

if(!empty($_POST['name']))
{
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
	$ProductInput['product_sale']		= replace_safe($_POST['product_sale']);
	$ProductInput['product_manager']	= replace_safe($_POST['product_manager']);
	$ProductInput['product_manager_num']= replace_safe($_POST['product_manager_num']);
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
	
	$tmp	= $_POST['product_sale'];
	if(!isset($ProductSale[$tmp]))
	{
		$_POST['product_sale']			= "Onsale";
	}

	$ProductInput['product_type']		= $_POST['product_type'];
	$ProductInput['product_quality']	= $_POST['product_quality'];
	$ProductInput['product_sale']		= $_POST['product_sale'];

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

	$sql	= "UPDATE product_info SET name='".$ProductInput['name']."'
			, number='".$ProductInput['number']."'
			, total='".$ProductInput['total']."'
			, brand_id='".$ProductInput['brand_id']."'
			, cost='".$ProductInput['price_display']."'
			, category_id='".$ProductInput['category_id']."'
			, bar_code='".$ProductInput['bar_code']."'
			, serial_number='".$ProductInput['serial_number']."'
			, product_type='".$ProductInput['product_type']."'
			, product_quality='".$ProductInput['product_quality']."'
			WHERE id='$id'
			";
	mysql_query($sql, $_mysql_link_);

	$sql	= "UPDATE product_detail SET parts_id='".$ProductInput['parts_id']."'
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
		WHERE id='$id'
		";
	mysql_query($sql, $_mysql_link_);
	
	//修改在售状态
	$sql	= "UPDATE product_sales SET sales_status='".$ProductInput['product_sale']."'
		, staff_id='".$ProductInput['product_manager_num']."'
		WHERE id='$id'
		";
	mysql_query($sql, $_mysql_link_);

	//修改仓库
	//如果将商品改为虚拟商品则冻结商品
	if($_POST['product_type']=='Virtual')
	{
		$sql = "UPDATE store_product SET is_frozen='Y' WHERE product_id='$id' AND company_id='$company_id'";
		mysql_query($sql,$_mysql_link_);
	}
	if(!empty($_POST['store_id']) && $_POST['product_type'] !="Virtual"){
		$sql = "UPDATE store_product SET is_frozen='Y' WHERE product_id='$id' AND company_id='$company_id'";
		mysql_query($sql,$_mysql_link_);


		$arr = $_POST['store_id'];
		for($i=0;$i<count($arr);$i++){
			$sql = "SELECT is_frozen FROM store_product WHERE store_id='".$arr[$i]."' AND product_id='$id' AND company_id='$company_id'";
			$res = mysql_query($sql,$_mysql_link_);
			$nums = mysql_num_rows($res);
			var_dump($nums);
			if($nums>0){
				$sql = "UPDATE store_product SET is_frozen='N' WHERE product_id='$id' AND company_id='$company_id' AND store_id='".$arr[$i]."'";
				mysql_query($sql,$_mysql_link_);
			}else{
				$sql = "INSERT INTO store_related SET company_id='$company_id', store_id='".$arr[$i]."', product_id='$id'";
				mysql_query($sql,$_mysql_link_);
				$sqll = "INSERT INTO store_product SET company_id='$company_id', store_id='".$arr[$i]."', product_id='$id'";
				mysql_query($sqll, $_mysql_link_);
			}
		}
	
	}	

	if(is_array($_POST['attrib_id']))
	{
		//---- 商品属性 ----
		foreach($_POST['attrib_id'] as $ix => $a_id)
		{
			$a_id	= intval($a_id);
			$v_id	= intval($_POST['value_id'][$ix]);
			if(isset($AttribValue[$a_id][$v_id]))
			{
				$sql	= "INSERT INTO product_attrib_list SET company_id='$company_id', product_id='$id', attrib_id='$a_id', value_id='$v_id' ON DUPLICATE KEY UPDATE value_id='$v_id'";
				mysql_query($sql, $_mysql_link_);
				unset($AttribList[$a_id]);
			}
		}
	}
	if(count($AttribList))
	{
		//---- 删除多余的属性 ----
		foreach($AttribList as $a_id => $dat)
		{
			$sql	= "DELETE FROM product_attrib_list WHERE company_id='$company_id' AND product_id='$id' AND attrib_id='$a_id'";
			mysql_query($sql, $_mysql_link_);
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
				$sql	= "INSERT INTO product_accessory SET product_id='$id', accessory_id='$dbRow->id', total='$total' ON DUPLICATE KEY UPDATE total='$total'";
				mysql_query($sql, $_mysql_link_);
				unset($AccessoryList[$dbRow->id]);
			}
		}
	}
	if(count($AccessoryList))
	{
		//---- 删除多余的配件 ----
		foreach($AccessoryList as $a_id => $dat)
		{
			$sql	= "DELETE FROM product_accessory WHERE product_id='$id' AND accessory_id='$a_id'";
			mysql_query($sql, $_mysql_link_);
		}
	}

	$the_image	= "";
	if($product_image_1 > 0)
	{
		//---- 图片ID是否存在 ----
		$sql	= "SELECT id, file_name, url FROM company_photo_info WHERE id='$product_image_1' AND company_id='$company_id'";
		$result	= mysql_query($sql, $_mysql_link_);
		if(mysql_num_rows($result) < 1)
		{
			$product_image_1	= 0;
		}
		else
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
		}
	}

	if($product_image_2 > 0)
	{
		//---- 图片ID是否存在 ----
		$sql	= "SELECT id FROM company_photo_info WHERE id='$product_image_2' AND company_id='$company_id'";
		$result	= mysql_query($sql, $_mysql_link_);
		if(mysql_num_rows($result) < 1)
		{
			$product_image_2	= 0;
		}
	}

	if($product_image_3 > 0)
	{
		//---- 图片ID是否存在 ----
		$sql	= "SELECT id FROM company_photo_info WHERE id='$product_image_3' AND company_id='$company_id'";
		$result	= mysql_query($sql, $_mysql_link_);
		if(mysql_num_rows($result) < 1)
		{
			$product_image_3	= 0;
		}
	}

	if($product_image_4 > 0)
	{
		//---- 图片ID是否存在 ----
		$sql	= "SELECT id FROM company_photo_info WHERE id='$product_image_4' AND company_id='$company_id'";
		$result	= mysql_query($sql, $_mysql_link_);
		if(mysql_num_rows($result) < 1)
		{
			$product_image_4	= 0;
		}
	}

	if($product_image_1)
	{
		$sql	= "INSERT INTO product_photo SET company_id='$company_id', product_id='$id', photo_id='".$product_image_1."', sort='1' ON DUPLICATE KEY UPDATE photo_id='".$product_image_1."'";
		mysql_query($sql, $_mysql_link_);
	}
	else
	{
		$sql	= "DELETE FROM product_photo WHERE product_id='$id' AND sort='1'";
		mysql_query($sql, $_mysql_link_);

	}
	$sql	= "UPDATE product_info SET image='$the_image' WHERE id='$id'";
	mysql_query($sql, $_mysql_link_);

	if($product_image_2)
	{
		$sql	= "INSERT INTO product_photo SET company_id='$company_id', product_id='$id', photo_id='".$product_image_2."', sort='2' ON DUPLICATE KEY UPDATE photo_id='".$product_image_2."'";
		mysql_query($sql, $_mysql_link_);
	}
	else
	{
		$sql	= "DELETE FROM product_photo WHERE product_id='$id' AND sort='2'";
		mysql_query($sql, $_mysql_link_);
	}

	if($product_image_3)
	{
		$sql	= "INSERT INTO product_photo SET company_id='$company_id', product_id='$id', photo_id='".$product_image_3."', sort='3' ON DUPLICATE KEY UPDATE photo_id='".$product_image_3."'";
		mysql_query($sql, $_mysql_link_);
	}
	else
	{
		$sql	= "DELETE FROM product_photo WHERE product_id='$id' AND sort='3'";
		mysql_query($sql, $_mysql_link_);
	}

	if($product_image_4)
	{
		$sql	= "INSERT INTO product_photo SET company_id='$company_id', product_id='$id', photo_id='".$product_image_4."', sort='4' ON DUPLICATE KEY UPDATE photo_id='".$product_image_4."'";
		mysql_query($sql, $_mysql_link_);
	}
	else
	{
		$sql	= "DELETE FROM product_photo WHERE product_id='$id' AND sort='4'";
		mysql_query($sql, $_mysql_link_);
	}
	header("Location: /product/product_list.php");
	exit;
}

$main['id']					= $id;
$main['number']				= $ProductInfo->number;
$main['name']				= $ProductInfo->name;
$main['cost']				= $ProductInfo->cost;
$main['total']				= $ProductInfo->total;
$main['category_id']		= $ProductInfo->category_id;
$main['brand_id']			= $ProductInfo->brand_id;
$main['sell_state']			= $ProductInfo->sales_status;
$main['product_manager']	= $ProductInfo->product_manager;
$main['product_manager_num']= $ProductInfo->product_manager_num;
$main['is_store']			= $ProductInfo->is_store;
$main['is_delete']			= $ProductInfo->is_delete;
$main['have_sku']			= $ProductInfo->have_sku;
$main['have_combination']	= $ProductInfo->have_combination;
$main['bar_code']			= $ProductInfo->bar_code;
$main['serial_number']		= $ProductInfo->serial_number;
$main['product_type']		= $ProductInfo->product_type;
$main['product_quality']	= $ProductInfo->product_quality;

//---- 商品图片 ----
$ProductPhoto	= array();
$sql	= "SELECT p.photo_id, p.sort, im.file_name, im.url FROM product_photo AS p LEFT JOIN company_photo_info AS im ON p.photo_id=im.id WHERE p.product_id='$id'";
$result	= mysql_query($sql, $_mysql_link_);
while($dbRow = mysql_fetch_object($result))
{
	$ProductPhoto[$dbRow->sort]	= $dbRow;
	if($dbRow->file_name)
	{
		$ProductPhoto[$dbRow->sort]->image	= $_default_photo_dir_.$dbRow->file_name;
	}
	if($dbRow->url)
	{
		$ProductPhoto[$dbRow->sort]->image	= $dbRow->url;
	}
}

$main['image_url_1']	= $ProductPhoto[1]->image;
$main['image_url_2']	= $ProductPhoto[2]->image;
$main['image_url_3']	= $ProductPhoto[3]->image;
$main['image_url_4']	= $ProductPhoto[4]->image;

$main['photo_id_1']		= intval($ProductPhoto[1]->photo_id);
$main['photo_id_2']		= intval($ProductPhoto[2]->photo_id);
$main['photo_id_3']		= intval($ProductPhoto[3]->photo_id);
$main['photo_id_4']		= intval($ProductPhoto[4]->photo_id);

if(strlen($ProductInfo->image) > 10 && $main['photo_id_1'] < 1000)
{
	$md5	= md5($ProductInfo->image);
	$sql	= "SELECT id FROM company_photo_info WHERE company_id='$company_id' AND file_md5='$md5'";
	$result	= mysql_query($sql, $_mysql_link_);
	if(mysql_num_rows($result))
	{
		$main['photo_id_1']	= mysql_result($result, 0, 'id');
	}
	else
	{
		$url	= mysql_real_escape_string($ProductInfo->image);
		$sql	= "INSERT INTO company_photo_info SET company_id='$company_id', url='$url', file_md5='$md5'";
		mysql_query($sql, $_mysql_link_);
		if(mysql_affected_rows($_mysql_link_) == 1)
		{
			$main['photo_id_1']	= mysql_insert_id($_mysql_link_);
		}
	}
	if($main['photo_id_1'] > 1000)
	{
		$sql	= "INSERT INTO product_photo SET company_id='$company_id', product_id='$id', photo_id='".$main['photo_id_1']."', sort='1'";
		mysql_query($sql, $_mysql_link_);
	}
	$main['image_url_1']	= $ProductInfo->image;
}

$main['format_1']			= $ProductInfo->format_id_1;
$main['format_2']			= $ProductInfo->format_id_2;
$main['format_3']			= $ProductInfo->format_id_3;
$main['format_4']			= $ProductInfo->format_id_4;
$main['format_5']			= $ProductInfo->format_id_5;
$main['value_id_1']			= $ProductInfo->value_id_1;
$main['value_id_2']			= $ProductInfo->value_id_2;
$main['value_id_3']			= $ProductInfo->value_id_3;
$main['value_id_4']			= $ProductInfo->value_id_4;
$main['value_id_5']			= $ProductInfo->value_id_5;
$main['price_tag']			= $ProductInfo->price_tag;
$main['price_purchase']		= $ProductInfo->price_purchase;
$main['price_display']		= $ProductInfo->price_display;
$main['price_total']		= $ProductInfo->price_total;
$main['weight']				= $ProductInfo->weight;
$main['volume']				= $ProductInfo->volume;
$main['parts_id']			= $ProductInfo->parts_id;
$main['content']			= $ProductInfo->content;

foreach($ProductType as $ix => $dat)
{
	$list_type	= array();
	$list_type['id']		= $ix;
	$list_type['name']		= $dat;
	$list_type['current']	= "N";
	if($ix == $ProductInfo->product_type)
	{
		$list_type['current']	= "Y";
	}
	$xtpl->assign("list_type", $list_type);
	$xtpl->parse("main.list_type");
}
foreach($ProductQuality as $ix => $dat)
{
	$list_quality	= array();
	$list_quality['id']			= $ix;
	$list_quality['name']		= $dat;
	$list_quality['current']	= "N";
	if($ix == $ProductInfo->product_quality)
	{
		$list_quality['current']	= "Y";
	}
	$xtpl->assign("list_quality", $list_quality);
	$xtpl->parse("main.list_quality");
}

foreach($ProductSale as $ix => $dat)
{
	$sell_state	= array();
	$sell_state['id']		= $ix;
	$sell_state['name']		= $dat;
	$sell_state['current']	= "N";
	if($ix == $ProductInfo->sell_state)
	{
		$sell_state['current']	= "Y";
	}
	$xtpl->assign("sell_state", $sell_state);
	$xtpl->parse("main.sell_state");
}
//---- 商品配件 ----
$AccessoryProduct	= array();
if(count($AccessoryList))
{
	$p_list	= "";
	foreach($AccessoryList as $a_id => $total)
	{
		$p_list	= $p_list.",".$a_id;
	}
	$p_list	= trim($p_list, ",");
	$sql	= "SELECT i.id, i.name, i.image, i.cost, d.parts_id FROM product_info AS i LEFT JOIN product_detail AS d ON i.id=d.id WHERE i.id IN ($p_list)";
	$result	= mysql_query($sql, $_mysql_link_);
	while($dbRow = mysql_fetch_object($result))
	{
		$AccessoryProduct[$dbRow->id]	= $dbRow;
	}

	foreach($AccessoryList as $a_id => $total)
	{
		$unit	= $AccessoryProduct[$a_id]->parts_id;
		$list_accessory				= array();
		$list_accessory['id']		= $a_id;
		$list_accessory['total']	= $total;
		$list_accessory['name']		= $AccessoryProduct[$a_id]->name;
		$list_accessory['image']	= $AccessoryProduct[$a_id]->image;
		$list_accessory['unit']		= $PartsInfo[$unit];
		$list_accessory['price']	= $AccessoryProduct[$a_id]->cost;
		$xtpl->assign("list_accessory", $list_accessory);
		$xtpl->parse("main.list_accessory");
	}
}

$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");
