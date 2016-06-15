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
$company_id = $_SESSION['_application_info_']['company_id'];
if(!empty($_GET['addon'])){
	$main['id'] = $_GET['addon'];
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

if(!empty($_POST['send'])){
	$category_id = intval($_POST['category']);
	$num = explode(",",$_POST['id']);
	$addon = array();
	for($i=0;$i<count($num);$i++){
		$addon[] = intval($num[$i]);	
	}
	for($i=0;$i<count($addon);$i++){
	$sql = "UPDATE product_info SET brand_id = '$category_id' WHERE company_id = '$company_id' AND id = '{$addon[$i]}' AND have_combination = 'Y'";
		mysql_query($sql,$_mysql_link_);
	}
	echo "<script>\n";
	echo "parent.$('#MessageBox').modal('hide');\n";
	echo "parent.location.replace(parent.location.href);";
	echo "</script>\n";
	echo "<center><br/><br/><br/><br/>修改完成！<br/><br/><br/><br/></center>";
	exit;
}
$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");