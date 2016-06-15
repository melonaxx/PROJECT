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

$company_id = $_SESSION['_application_info_']['company_id'];
//查看短信剩余条数
$sql = "SELECT message_remain FROM company_name WHERE id='$company_id' ";
$res = mysql_query($sql,$_mysql_link_);
if($res)
{
	$message_remain         = mysql_result($res,0,0);
	$main['message_remain'] = $message_remain;
}


$sql= "SELECT id,total,price FROM main_message_price WHERE is_display='Y'";
$num = 1;
$result = mysql_query($sql,$_mysql_link_);
while($res = mysql_fetch_object($result)){
	$arr = array();
	$arr['id']     = $res->id;
	$arr['total']  = $res->total;
	$arr['price']  = $res->price;
	$arr['num']    = $num++;
	$arr['danjia'] = $res->price*100/$res->total;
	$xtpl->assign("arr", $arr);
	$xtpl->parse("main.arr");
}


$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");