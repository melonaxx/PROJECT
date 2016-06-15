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

$alipay_secret	= "55ciosrgwztgl33gsf3ctyubs88merkh";
$alipay_partner	= "2088911145347515";
$alipay_seller	= '2088911145347515';

$id		= intval($_REQUEST['id']);
$sql	= "SELECT name, price FROM main_message_price WHERE id='$id' AND is_display='Y'";
$result	= mysql_query($sql, $_mysql_link_);
if(mysql_num_rows($result) < 1)
{
	illegal_operation();
}
$PriceInfo	= mysql_fetch_object($result);

$params			= array();
$params["_input_charset"]	= "utf-8";
$params["service"]			= "create_direct_pay_by_user";
$params["partner"]			= $alipay_partner;
$params["seller_id"]		= $alipay_seller;
$params["payment_type"]		= "1";
$params["notify_url"]		= "https://erp.imihuan.com/crm/crm_buy_notify_url.php";
$params["return_url"]		= "http://erp.imihuan.com/crm/crm_buy_return_url.php";
$params["out_trade_no"]		= date("Ymdhis").$_SESSION['_application_info_']['company_id'].rand(100, 999);
$params["subject"]			= $PriceInfo->name;
$params["total_fee"]		= $PriceInfo->price;
$params["show_url"]			= "https://erp.imihuan.com/crm/crm_message_buy.php";
$main['sign']				= create_alipay_sign($params, $alipay_secret);

$sql	= "INSERT INTO temp_message_order SET company_id='".$_SESSION['_application_info_']['company_id']."', price_id='$id', number='".$params["out_trade_no"]."', action_date=NOW()";
mysql_query($sql, $_mysql_link_);

$main["service"]			= $params["service"];
$main["partner"]			= $params["partner"];
$main["seller_id"]			= $params["seller_id"];
$main["payment_type"]		= $params["payment_type"];
$main["notify_url"]			= $params["notify_url"];
$main["return_url"]			= $params["return_url"];
$main["out_trade_no"]		= $params["out_trade_no"];
$main["subject"]			= $params["subject"];
$main["total_fee"]			= $params["total_fee"];
$main["body"]				= $params["body"];
$main["show_url"]			= $params["show_url"];
$main["exter_invoke_ip"]	= $params["exter_invoke_ip"];
$main['company_name']		= $_SESSION['_application_info_']['nick'];

$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");

