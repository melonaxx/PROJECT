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


if (isset($_POST['send'])) {
	$name = replace_safe($_POST['name'], 20);
	$is_delete = 'N';
	
	if ($_POST['type'] == 'this_sort') {
		$parent_id = intval($_POST['parent_id']);
	} elseif ($_POST['type'] == 'child_sort') {
		$parent_id = intval($_POST['id']);
	}
	$sql = "INSERT INTO product_category SET
			company_id='$company_id',
			name='{$name}',
			parent_id='{$parent_id}',
			is_delete='{$is_delete}'
	";
	
	mysql_query($sql, $_mysql_link_);
	
	echo "<script>\n";
	echo "parent.$('#MessageBox').modal('hide');\n";
	echo "parent.location.reload();";
	echo "</script>";
	exit;
	
}

$main['id'] = $_GET['id'];
$main['parent_id'] = $_GET['parent_id'];
$main['type'] = $_GET['type'];

$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");

