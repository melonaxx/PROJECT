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
$addon[] = "i.company_id = '$company_id'";
$addon[] = "i.is_delete = 'N'";
$addon[] = "i.have_combination = 'Y'";

if(!empty($_REQUEST['name'])){
	$name = replace_safe($_REQUEST['name'],20);
	if(!empty($name)){
		$addon[] = "INSTR(i.name,'$name')";
		$main['name']	= $name;
		$page_param		= array();
		$page_param['name']		= replace_safe($_REQUEST['name'], 20, false, false);
	}
}
if(!empty($_REQUEST['classification'])){
	$classification = intval($_REQUEST['classification']);
	if(!empty($classification)){
		$addon[] = "i.category_id = '$classification'";
		$main['classification']	= $classification;
		$page_param		= array();
		$page_param['classification']		= intval($_REQUEST['classification']);
	}
}
$where  = "";
if(count($addon) > 0)
{
	$where	= "WHERE ".implode(" AND ", $addon);
}

//-- 查询数量 --
$sql = "SELECT COUNT(*) AS total FROM product_info AS i $where";

// echo $sql;
// exit;
$result	= mysql_query($sql, $_mysql_link_);
$main['total']		= mysql_result($result, 0, 'total');

//---- 处理分页 ----
if(!is_array($page_param))
{
	$page_param			= array();
}
$main['page_info']	= erp_page_info($main['total'], $page, $page_param);
$limit	= ($page - 1) * $_SESSION["_application_info_"]["page_size"].", ".$_SESSION["_application_info_"]["page_size"];

if($main['total'] > 0)
{
	$sql = "SELECT i.id,i.name,d.price_display,i.category_id,i.brand_id,d.content FROM product_info AS i LEFT JOIN product_detail AS d ON d.id = i.id $where ORDER BY i.id DESC LIMIT ".$limit;
	$result = mysql_query($sql,$_mysql_link_);
	while($productinfo = mysql_fetch_object($result)){
		$product_info = array();
		$product_info['id']				= $productinfo->id;
		$product_info['name'] 			= $productinfo->name;
		$product_info['price_display']	= $productinfo->price_display;
		//--- 查询分类 ---
		$sql = "SELECT name FROM product_category WHERE company_id = '$company_id' AND is_delete = 'N' AND id = '{$productinfo->category_id}'";
		$result_category = mysql_query($sql,$_mysql_link_);
		$productcategory = mysql_fetch_object($result_category);
		$product_info['category_id'] = $productcategory->name;
		//--- 查询品牌 ---
		$sql = "SELECT name FROM product_brand WHERE company_id = '$company_id' AND is_delete = 'N' AND id = '{$productinfo->brand_id}'";
		$result_brand = mysql_query($sql,$_mysql_link_);
		$productbrand = mysql_fetch_object($result_brand);
		$product_info['brand_id'] = $productbrand->name;

		$product_info['content']		= $productinfo->content;
		$xtpl->assign("product_info", $product_info);
		$xtpl->parse("main.product_info");
	}
}

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
$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");