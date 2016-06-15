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


$out_trade_no = $_GET['out_trade_no'];//商户订单号
$trade_no = $_GET['trade_no'];//支付宝交易号
$trade_status = $_GET['trade_status'];//交易状态

if($_GET['trade_status'] == 'TRADE_FINISHED' || $_GET['trade_status'] == 'TRADE_SUCCESS') {

}else {
  echo "trade_status=".$_GET['trade_status'];
}
header("location: crm_buy_logs.php");exit;
echo "验证成功<br />";


$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");