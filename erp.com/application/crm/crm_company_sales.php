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
	$id = $_GET['id'];
	$sql = "DELETE FROM  company_sales WHERE id = '$id'";
	mysql_query($sql,$_mysql_link_);
	header('Location: /crm/crm_company_sales.php');
	exit;
}

//处理分页
$sql = "SELECT COUNT(*) as total FROM company_sales WHERE company_id = '$company_id'";
$result = mysql_query($sql,$_mysql_link_);
$main['total'] = mysql_result($result,0,'total');

//处理分页
if(!is_array($page_param)){
	$page_param = array();
}
$main['page_info']	= erp_page_info($main['total'], $page, $page_param);
$limit	= ($page - 1) * $_SESSION["_application_info_"]["page_size"].", ".$_SESSION["_application_info_"]["page_size"];
if($main['total'] > 0){
	$sqll= "SELECT id,name FROM company_sales WHERE company_id='$company_id' ORDER BY id  LIMIT ".$limit;
	$msql=mysql_query($sqll, $_mysql_link_);
		while($SupplierInfo = mysql_fetch_object($msql)){
		$value	= array();
		$value['id']			= $SupplierInfo->id;
		$value['name']			= $SupplierInfo->name;

		// 查询订单总数，订单总额
		$sql2 = "SELECT count(*) AS order_total,sum(o.theory_amount) AS money_total FROM finance_order AS o 
			RIGHT JOIN order_operation AS s ON s.id=o.order_id 
			LEFT JOIN order_info AS i ON i.id=o.order_id WHERE i.status='S' AND s.purchase_id='$SupplierInfo->id'";

		$result2 = mysql_query($sql2,$_mysql_link_);
		while($rows = mysql_fetch_object($result2)){
			$value['order_total'] = $rows->order_total;
			$value['money_total'] = $rows->money_total;
		}
		$xtpl->assign("sales", $value);
		$xtpl->parse("main.sales");
	}
}

$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");