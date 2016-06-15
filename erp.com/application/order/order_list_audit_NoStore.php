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
$company_id=$_SESSION['_application_info_']['company_id'];
$arr = array();
if (!empty($_GET['id'])) {
	$orderId = replace_safe($_REQUEST['id']);
	$orderId = explode(',',$orderId);
	$serial = 0;
	foreach ($orderId as $k => $v) {
		//查询订单编号
		$order_sql = "SELECT bind_number FROM order_source WHERE id=$v AND company_id=$company_id";
		$order_res = mysql_query($order_sql,$_mysql_link_);
		$order_data = mysql_fetch_object($order_res);
		$bind_number = $order_data->bind_number;

		$serial++;
		$arr['bind_number'] = $bind_number;
		$arr['serial'] = $serial;
		$arr['oid'] = $v;

		$xtpl->assign("arr", $arr);
		$xtpl->parse("main.arr");
	}}

// echo "<script>\n";
// echo "parent.$('#MessageBox').modal('hide');\n";
// echo "parent.location.replace('/order/order_list_audit.php');";
// echo "</script>\n";
// echo "<center><br/><br/><br/><br/>修改完成！<br/><br/><br/><br/></center>";

$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");


