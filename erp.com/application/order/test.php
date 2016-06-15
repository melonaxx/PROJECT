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

//获取当前会话用户出售中的商品列表
$params				= array();
$params['method']	= 'taobao.trades.sold.get';
$params['method']	= 'taobao.trade.fullinfo.get';
$params['tid']		= '1683885950417028';
$params['fields']	= 'seller_memo,seller_nick,tid,buyer_memo,receiver_city,receiver_district,tid,receiver_name,receiver_state,receiver_address,receiver_zip,receiver_mobile,receiver_phone';
// $params['type']	= 'fixed';
// $params['num_iid']	= '520374765635';

$params['nick']		= $_SESSION['_application_info_']['nick'];
$site_str			= get_taobao_response($params, $_application_info_['key'], $_application_info_['secret'], $_application_info_['session'], 'json');
$json_data			= json_decode($site_str);
	// print_r($site_str);
	// die();
 header("Content-Type: text/html; charset=UTF-8");
 print_r($json_data);

// $total_results = $json_data->trades_sold_get_response->total_results;
// $trade = $json_data->trades_sold_get_response->trades->trade;
print_r($trade);
echo 'ok';
 exit;
// $pro			= array();
// 	//获取单个商品详细信息
// 	$pro['method']	= 'taobao.item.seller.get';
// 	$pro['fields']	= ' num, pic_url';
// 	$pro['num_iid'] = '520374765635';

// 	$pro['nick']	= $_SESSION['_application_info_']['nick'];
// 	$aa				= get_taobao_response($pro, $_application_info_['key'], $_application_info_['secret'], $_application_info_['session'], 'json');
// 	$bb				= json_decode($aa);

//   header("Content-Type: text/html; charset=UTF-8");
//   print_r($bb);
//  exit;