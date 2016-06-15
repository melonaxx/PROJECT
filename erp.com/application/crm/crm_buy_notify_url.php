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

$notify_time = $_POST['notify_time']; //通知时间
$out_trade_no = $_POST['out_trade_no'];//商户订单号
$gmt_payment = $_POST['gmt_payment'];//付款时间
$buyer_email = $_POST['buyer_email'];//买家账号
$total_fee = $_POST['total_fee'];//交易金额
$trade_no = $_POST['trade_no'];//支付宝交易号
$trade_status = $_POST['trade_status'];//交易状态

if ($_POST['trade_status'] == 'TRADE_SUCCESS') {
    $sql    = "SELECT price_id,company_id FROM temp_message_order WHERE number='$out_trade_no'";
    $result = mysql_query($sql,$_mysql_link_);
    $res    = mysql_fetch_object($result);

    $company_id = intval($res->company_id);
    $price_id   = intval($res->price_id);

    $sql   = "SELECT total FROM main_message_price WHERE id='$price_id'";
    $query = mysql_query($sql,$_mysql_link_);
    $re    = mysql_fetch_object($query);

    $total = intval($re->total);

    $sql = "INSERT INTO company_message_order (id,company_id,price_id,alipay_name,alipay_no,payment,action_date,number)
    VALUES('','$company_id','$price_id','$buyer_email','$trade_no','$total_fee','$gmt_payment','$out_trade_no')";
    mysql_query($sql,$_mysql_link_);

    $sql = "UPDATE company_name SET message_remain=message_remain+'$total' WHERE id ='$company_id'";
    mysql_query($sql,$_mysql_link_);

}
echo "success";




$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");