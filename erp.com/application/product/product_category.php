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

if (!isset($_GET['type']) || $_GET['type'] == 'sort') {
	$type = 'sort';
} else {
	$type = 'brand';
}
$main['type'] = $type;


//删除分类
if (isset($_GET['m']) && isset($_GET['sort_id'])) {
	header("Content-Type: text/html; charset=UTF-8");
	$id = intval($_GET['sort_id']);

	//判断是否有子分类或有商品
	$sql = "SELECT COUNT(*) as total FROM product_category WHERE company_id='{$company_id}' AND parent_id='{$id}' AND is_delete='N'";
	$result	= mysql_query($sql, $_mysql_link_);
	$total = mysql_result($result, 0, 'total');
	if ($total > 0) {
		//不能删
		echo 'false';
	} else {
		//删
		$sql = "UPDATE product_category SET is_delete='Y' WHERE company_id='{$company_id}' AND id='{$id}'";
		mysql_query($sql, $_mysql_link_);
		echo 'true';
	}
	exit;
}

//删除品牌
if (isset($_GET['m']) && isset($_GET['brand_id'])) {
	header("Content-Type: text/html; charset=UTF-8");
	$id = intval($_GET['brand_id']);

	//判断是否有子分类或有商品
	$sql = "SELECT COUNT(*) as total FROM product_brand WHERE company_id='{$company_id}' AND parent_id='{$id}' AND is_delete='N'";
	$result	= mysql_query($sql, $_mysql_link_);
	$total = mysql_result($result, 0, 'total');
	if ($total > 0) {
		//不能删
		echo 'false';
	} else {
		//删
		$sql = "UPDATE product_brand SET is_delete='Y' WHERE company_id='{$company_id}' AND id='{$id}'";
		mysql_query($sql, $_mysql_link_);
		echo 'true';
	}
	exit;
}


if ($type == 'sort') {
	//获取分类
	$GroupList	= array();

	$sql = "SELECT id, name, parent_id FROM product_category WHERE company_id='{$company_id}' AND is_delete='N'";
	$result	= mysql_query($sql, $_mysql_link_);
	while ($rows = mysql_fetch_object($result))
	{

		$GroupList[$rows->parent_id][$rows->id]	= $rows->name;
	}
	$GroupList	= get_sort_by_array($GroupList);
	//print_r($GroupList);

	$main['sort_total'] = count($GroupList);

	foreach ($GroupList as $key=>$value) {
		$value['name'] = str_repeat("　　", $value['level'] - 1).$value['name'];
		$xtpl->assign("category", $value);
		$xtpl->parse("main.category");
	}
}



if ($type == 'brand') {
	//获取品牌
	$GroupList	= array();
	$sql = "SELECT id, name, parent_id FROM product_brand WHERE company_id='{$company_id}' AND is_delete='N'";
	$result	= mysql_query($sql, $_mysql_link_);
	while ($rows = mysql_fetch_object($result))
	{
		$GroupList[$rows->parent_id][$rows->id]	= $rows->name;
	}
	$GroupList	= get_sort_by_array($GroupList);
	//print_r($GroupList);

	$main['brand_total'] = count($GroupList);


	foreach ($GroupList as $key=>$value) {
		$value['name'] = str_repeat("　　", $value['level'] - 1).$value['name'];
		$xtpl->assign("brand", $value);
		$xtpl->parse("main.brand");
	}
}




$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");
