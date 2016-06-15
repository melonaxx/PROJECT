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
include "../libstr.php";

$xtpl = new xtpl();
$xtpl->set_file("../../tpl/product_class.html");

get_site_application_menu($_SESSION['_application_info_']['site_menu'], "/product/product_class.php", $xtpl, $main);

if($_SESSION["_application_info_"]['user_id'] < 1)
{
	$xtpl->assign("main", $main);
	$xtpl->parse("main");
	$xtpl->out("main");
	exit;
}

$params				= array();
$params['method']	= 'taobao.user.get';
$params['fields']	= 'user_id,nick,status,avatar,alipay_no,alipay_account,sex,seller_credit,type';
$params['nick']		= $_SESSION['_application_info_']['nick'];
$site_str			= get_taobao_response($params, $_application_info_['key'], $_application_info_['secret'], $_application_info_['session'], 'json');
$json_data			= json_decode($site_str);

$user = $json_data;
$nick = $user->user_get_response->user->nick;

$main['user_info']	= $site_str;
/*
header("Content-Type: text/html; charset=UTF-8");
print_r($json_data);
exit;
*/
$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");
