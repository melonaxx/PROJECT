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
include "../libstr.php";
include "../bind_type.php";
$company_id = $_SESSION['_application_info_']['company_id'];

//---- 获取供应商 ----
$sql = "SELECT id,name FROM purchase_supplier WHERE is_delete = 'N' AND company_id = '$company_id'";
$result = mysql_query($sql,$_mysql_link_);
$purchaseInfo 	= array();
while($purchase = mysql_fetch_object($result)){
	$purchase_add 			= array();
	$purchase_add['id']		= $purchase->id;
	$purchase_add['name']	= $purchase->name;
	$xtpl->assign("purchase_add", $purchase_add);
	$xtpl->parse("main.purchase_add");
	$purchaseInfo[$purchase->id] = $purchase->name;
}

//新建收款单
if(!empty($_POST['number'])){
	$number = intval($_POST['number']);
	$supplier = intval($_POST['supplier']);
	$money = floatval($_POST['money']);
	$body = replace_safe($_POST['body']);
	
	$sql = "SELECT id,body FROM purchase_main_info WHERE number='$number' AND status_refund != 'N' AND company_id='$company_id' AND supplier_id = '$supplier'";
	//var_dump($sql);
	$res = mysql_query($sql,$_mysql_link_);
	$mmm = mysql_num_rows($res);
	$rows = mysql_fetch_object($res); 
	$id = $rows->id;
	$bod = $rows->body;
	if($mmm>0){
		$sqll = "UPDATE finance_purchase SET payment_return=payment_return+'$money' WHERE company_id='$company_id' AND purchase_id='$id'";
		mysql_query($sqll,$_mysql_link_);
		if($body != ""){
			$xiu = $bod.";".$body;
			$sql = "UPDATE purchase_main_info SET body = '$xiu' WHERE supplier_id='$supplier' AND status_refund != 'N' AND company_id='$company_id' AND number='$number'";
			mysql_query($sql,$_mysql_link_);
		}
		echo json_encode("1");
		exit;
	}else{
		echo json_encode("0");
		exit;
	}
}

$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");