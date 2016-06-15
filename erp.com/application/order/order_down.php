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
include "../bind_type.php";

$company_id = $_SESSION['_application_info_']['company_id'];
// 获取当前会话用户出售中的商品列表
$params				= array();
$params['method']	= 'taobao.trades.sold.get';
$params['fields']   = 'seller_memo,buyer_memo,receiver_city,receiver_district,tid,receiver_name,receiver_state,receiver_address,receiver_zip,receiver_mobile,receiver_phone';

$params['nick']		= $_SESSION['_application_info_']['nick'];
$site_str			= get_taobao_response($params, $_application_info_['key'], $_application_info_['secret'], $_application_info_['session'], 'json');
$json_data			= json_decode($site_str);

header("Content-Type: text/html; charset=UTF-8");
$total_results = $json_data->trades_sold_get_response->total_results;
$trade = $json_data->trades_sold_get_response->trades->trade;

for($i=0;$i<count($trade);$i++){
	// 获取单笔交易信息
	$order				= array();
	$order['method']	= 'taobao.trade.get';
	$order['fields']	= 'created, orders.sku_properties_name,orders.sku_properties,orders.sku_properties_name,post_fee,received_payment,buyer_message,seller_memo,buyer_nick,orders.title,orders.price,orders.num_iid,orders.total_fee,orders.payment,orders.discount_fee,orders.store_code,orders.num';
	$order['tid']   	= $trade[$i]->tid;

	$order['nick']		= $_SESSION['_application_info_']['nick'];
	$site_str			= get_taobao_response($order, $_application_info_['key'], $_application_info_['secret'], $_application_info_['session'], 'json');
	$res				= json_decode($site_str);


	$name 				= $trade[$i]->receiver_name;
	$receiver_city 		= $trade[$i]->receiver_city;
	$receiver_district 	= $trade[$i]->receiver_district;
	$receiver_address 	= $trade[$i]->receiver_address;    //详细地址
	$tid                = $trade[$i]->tid;
	$receiver_mobile 	= $trade[$i]->receiver_mobile;
	$receiver_phone 	= $trade[$i]->receiver_phone;
	$receiver_zip 		= $trade[$i]->receiver_zip;
	$buyer_memo         = $trade[$i]->buyer_memo;           //买家备注
	$seller_memo        = $trade[$i]->seller_memo;			//卖家备注

	$result   = $res->trade_get_response->trade;
	$buyer    = $result->buyer_nick;       //昵称
	$created  = $result->created;          //交易创建时间
	$post_fee = $result->post_fee;         //运费

	$total_pay = 0;
	$total_yh  = 0;
	$real_pay  = 0;

	for($m=0;$m<count($result->orders->order);$m++){
		$payment       = $result->orders->order[$m]->payment;       //实付金额
		$discount_fee  = $result->orders->order[$m]->discount_fee;  //子订单级订单优惠金额。
		$total_fee     = $result->orders->order[$m]->total_fee;     //应付金额
		$total_pay 	  += $total_fee;
		$total_yh     += $discount_fee;
		$real_pay     += $payment;
	}
	$real_pay  += $post_fee;
	$total_pay += $post_fee;
	$sql = "INSERT INTO order_info SET order_date='$created', company_id='$company_id'";
	mysql_query($sql,$_mysql_link_);
	$row = mysql_affected_rows($_mysql_link_);
	$id = mysql_insert_id($_mysql_link_);
	if($row){
		$a = 0;
		for($m=0;$m<count($result->orders->order);$m++){
			$a++;
			$discount_fee  = $result->orders->order[$m]->discount_fee;  //子订单级订单优惠金额。
			$num  		   = $result->orders->order[$m]->num;			//数量
			$num_iid  	   = $result->orders->order[$m]->num_iid;
			$payment       = $result->orders->order[$m]->payment;
			$price         = $result->orders->order[$m]->price;
			$title         = $result->orders->order[$m]->title;
			$total_fee     = $result->orders->order[$m]->total_fee;  //应付金额
			$properties    = $result->orders->order[$m]->sku_properties_name;
			$prop = explode(":",str_replace(";", ":", $properties));
			// 获取单个商品的交易信息
			$pro			= array();
			$pro['method']	= 'taobao.item.seller.get';
			$pro['fields']	= 'sku.properties_name';
			$pro['num_iid'] = $num_iid;
			$pro['nick']	= $_SESSION['_application_info_']['nick'];
			$site_str		= get_taobao_response($pro, $_application_info_['key'], $_application_info_['secret'], $_application_info_['session'], 'json');
			$data			= json_decode($site_str);
			$sku = $data->item_seller_get_response->item->skus->sku;
			if(is_array($sku)){
				for($n=0;$n<count($sku);$n++){
					$prop_name = explode(":",str_replace(";", ":", $sku[$n]->properties_name));
					if($prop[1] == $prop_name[3] && $prop[3] == $prop_name[7]){
						$format_list = $num_iid.$prop_name[1].$prop_name[5];
						$sql    = "SELECT product_id FROM product_related_info WHERE company_id='$company_id' AND format_list='$format_list' AND info_id='$num_iid'";
						$result = mysql_query($sql,$_mysql_link_);
						$dbRes  = mysql_fetch_object($result);
						if($dbRes){
							$sql = "INSERT INTO order_product SET company_id='$company_id',
							order_id='$id', product_id='$dbRes->product_id', total='$num',
							price='$price', discount='$discount_fee', payment='$total_fee'";
							mysql_query($sql,$_mysql_link_);
						}
					}
				}
			}else{
				$format_list = $num_iid;
				$sql    = "SELECT product_id FROM product_related_info WHERE company_id='$company_id' AND format_list='$format_list' AND info_id='$num_iid'";
				$result1 = mysql_query($sql,$_mysql_link_);
				$dbRow  = mysql_fetch_object($result1);
				if($dbRow){
					$sql = "INSERT INTO order_product SET company_id='$company_id',
					order_id='$id', product_id='$dbRow->product_id', total='$num',
					price='$price', discount='$discount_fee', payment='$total_fee'";
					mysql_query($sql,$_mysql_link_);
				}
			}
		}
		echo $a;
		$sql = "SELECT name,crm_user_id FROM crm_user_related WHERE company_id='$company_id' AND telphone='$receiver_mobile'";
		$result = mysql_query($sql,$_mysql_link_);
		$res = mysql_fetch_object($result);
		$crm_user_id = intval($res->crm_user_id);
		if(!$res){
			$sql = "INSERT INTO crm_user_known(id,name) VALUES ('',$receiver_name)";
			mysql_query($sql,$_mysql_link_);
			$rows = mysql_affected_rows($_mysql_link_);
			if($rows){
				$bind_id = mysql_insert_id($_mysql_link_);
				$sql = "INSERT INTO crm_user_info(id,bind_type,bind_id,nick_name)VALUES('','Unknown','$bind_id','$nick_name')";
				mysql_query($sql,$_mysql_link_);
				$crm_id = mysql_insert_id($_mysql_link_);

				$sql = "INSERT INTO crm_user_related(
				id,
				company_id,
				crm_user_id,
				post_code,
				address,
				name,
				telphone,
				mobile
				)VALUES(
				'',
				'$company_id',
				'$crm_id',
				'$receiver_zip',
				'$receiver_address',
				'$receiver_name',
				'$receiver_phone',
				'$receiver_mobile'
				)";
				mysql_query($sql,$_mysql_link_);
				$sql = "INSERT INTO order_source (
				id,
				company_id,
				bind_number,
				crm_user_id,
				order_text,
				customer_text
				) VALUES (
				'$id',
				'$company_id',
				'$tid',
				'$crm_id',
				'$buyer_memo',
				'$seller_memo')";
				mysql_query($sql,$_mysql_link_);
			}
		}else{
			$sql = "INSERT INTO order_source (
			id,
			company_id,
			bind_number,
			crm_user_id,
			order_text,
			customer_text
			) VALUES (
			'$id',
			'$company_id',
			'$tid',
			'$crm_user_id',
			'$buyer_memo',
			'$seller_memo')";
			mysql_query($sql,$_mysql_link_);
		}

		$sql = "INSERT INTO order_receiver(
		id,
		company_id,
		name,
		phone,
		mobile,
		post_code,
		address
		)VALUES(
		'$id',
		'$company_id',
		'$name',
		'$receiver_phone',
		'$receiver_mobile',
		'$receiver_zip',
		'$receiver_address'
		)";
		mysql_query($sql,$_mysql_link_);
		$sql = "INSERT INTO order_express_paper(
		id,
		company_id,
		order_id,
		freight_buyer
		)VALUES(
		'',
		'$company_id',
		'$id',
		'$post_fee'
		)";
		mysql_query($sql,$_mysql_link_);

		$sql = "INSERT INTO order_deliver_paper(
			id,
			company_id,
			order_id
			)VALUES(
			'',
			'$company_id',
			'$id'
			)";
		mysql_query($sql,$_mysql_link_);

		$sql = "INSERT INTO order_list_paper(
			id,
			company_id,
			order_id
			)VALUES(
			'',
			'$company_id',
			'$id'
			)";
		mysql_query($sql,$_mysql_link_);

		$sql = "INSERT INTO order_delivery(
			id,
			company_id
			)VALUES(
			'$id',
			'$company_id'
		)";
		mysql_query($sql,$_mysql_link_);

		$remain_amount = $total_pay-$real_pay;
		if($remain_amount == 0){
			$payment_status = "Y";
		}elseif($real_pay == 0){
			$payment_status = "N";
		}else{
			$payment_status = "P";
		}
		$sql    = "SELECT id FROM finance_bank WHERE company_id='$company_id' AND is_default='Y' AND status='Y'";
		$query  = mysql_query($sql,$_mysql_link_);
		$dbRows = mysql_fetch_object($query);
		$sql = "INSERT INTO finance_order(
		id,
		company_id,
		order_id,
		bank_id,
		discount,
		theory_amount,
		real_amount,
		payment_status,
		arrears
		)VALUES(
		'',
		'$company_id',
		'$id',
		'$dbRows->id',
		'$total_yh',
		'$total_pay',
		'$real_pay',
		'$payment_status',
		'$remain_amount'
		)";
		mysql_query($sql,$_mysql_link_);

	}
}
header("Location: order_list_audit.php");
exit;


$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");

