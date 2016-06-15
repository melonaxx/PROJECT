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

$ShopInfo	= array();
$sql = "SELECT s.name, s.user_id, i.nick_name, t.taobao_session
		FROM company_shop AS s
		LEFT JOIN user_register_info AS i ON s.user_id=i.id
		LEFT JOIN user_register_taobao AS t ON s.user_id=t.user_id
		WHERE s.company_id='$company_id' AND s.bind_type='Taobao'";
$result = mysql_query($sql,$_mysql_link_);
while($res = mysql_fetch_object($result)){
	$arr 			= array();
	$arr['name']	= $res->name;
	$arr['user_id'] = $res->user_id;
	$ShopInfo[$res->user_id]	= $res;
	$xtpl->assign("arr", $arr);
	$xtpl->parse("main.arr");
}

if(isset($_POST['submit'])){
	$user_id = intval($_POST['user_id']);
	if(empty($ShopInfo[$user_id]))
	{
		die("没有选择店铺!");
	}

	$params				= array();
	$params['method']	= 'taobao.trades.sold.get';
	$params['fields']   = 'created,tid,total_results,seller_nick,receiver_city,receiver_district,receiver_name,receiver_state,receiver_address,receiver_zip,receiver_mobile,buyer_nick,status';
	// $params['fields']   = 'receiver_address,receiver_zip';

	$params['nick']		= $ShopInfo[$user_id]->nick_name;
	$site_str			= get_taobao_response($params, $_application_info_['key'], $_application_info_['secret'], $ShopInfo[$user_id]->taobao_session, 'json');
	$json_data			= json_decode($site_str);
	// $x      = '{"b":"c","d":"f"}';
	// $y      = json_decode($x);
	// print_r($y);
	// echo $x.'<br/>';
	echo '<pre>';
	print_r($json_data);
	echo $site_str;
	die();

	header("Content-Type: text/html; charset=UTF-8");
	// 交易信息的总数
	$total_results = $json_data->trades_sold_get_response->total_results;
	//获取交易信息
	$trade = $json_data->trades_sold_get_response->trades->trade;
	$array_tid = array();
	for($i=0;$i<count($trade);$i++){
		// if ($trade[$i]->status == 'WAIT_SELLER_SEND_GOODS' OR $trade[$i]->status == 'WAIT_BUYER_PAY') {
			$tid = replace_safe($trade[$i]->tid);
			$array_tid[] = $tid;
			//查询有没有已存在的线上订单
			$sql   = "SELECT count(*) AS total FROM order_source WHERE company_id='$company_id' AND bind_number='$tid' AND bind_type='Taobao'";

			$query = mysql_query($sql,$_mysql_link_);
			$resf  = mysql_fetch_object($query);
			//没有已经存在的线上订单
			if($resf->total == 0){
				// 获取单笔交易信息
				$order				= array();
				$order['method']	= 'taobao.trade.get';
				$order['tid']   	= $trade[$i]->tid;
				$order['fields']	= 'created, status, consign_time, orders.sku_properties_name,orders.sku_properties,orders.sku_properties_name,post_fee,received_payment, buyer_message,seller_memo,buyer_nick,orders.title,orders.price,orders.num_iid,orders.total_fee,orders.payment,orders.discount_fee,orders.store_code,orders.num';

				$order['nick']		= $ShopInfo[$user_id]->nick_name;
				$site_str			= get_taobao_response($order, $_application_info_['key'], $_application_info_['secret'], $ShopInfo[$user_id]->taobao_session, 'json');
				$res				= json_decode($site_str);
				$name 				= replace_safe($trade[$i]->receiver_name);
				$receiver_state		= replace_safe($trade[$i]->receiver_state);
				$receiver_city 		= replace_safe($trade[$i]->receiver_city);
				$receiver_district 	= replace_safe($trade[$i]->receiver_district);
				$receiver_address 	= replace_safe($trade[$i]->receiver_address);    //详细地址
				$tid                = replace_safe($trade[$i]->tid);
				$receiver_mobile 	= replace_safe($trade[$i]->receiver_mobile);
				$receiver_phone 	= replace_safe($trade[$i]->receiver_phone);
				$receiver_zip 		= replace_safe($trade[$i]->receiver_zip);
				$seller_memo        = replace_safe($trade[$i]->seller_memo);		  //卖家备注

				$buyer_nick  = replace_safe($res->trade_get_response->trade->buyer_nick); //昵称
				$created  = replace_safe($res->trade_get_response->trade->created);  			//交易创建时间
				$post_fee = floatval($res->trade_get_response->trade->post_fee);         	 //运费
				$status   = replace_safe($res->trade_get_response->trade->status);		     //交易状态
				$consign_time = replace_safe($res->trade_get_response->trade->consign_time); //卖家发货时间
				$buyer_memo = replace_safe($res->trade_get_response->trade->buyer_message);           					//买家备注
				//取省ID
				$sql 	  = "SELECT number FROM main_identity_card WHERE name='$receiver_state' AND level='1'";
				$this1    = mysql_query($sql,$_mysql_link_);
				$resu1    = mysql_fetch_object($this1);
				$state_id = intval($resu1->number);
				//取城市ID
				$sql 	 = "SELECT number FROM main_identity_card WHERE name='$receiver_city' AND level='2'";
				$this2   = mysql_query($sql,$_mysql_link_);
				$resu2   = mysql_fetch_object($this2);
				$city_id = intval($resu2->number);
				//取县ID
				$sql 	 = "SELECT number FROM main_identity_card WHERE name='$receiver_district' AND level='3'";
				$this3   = mysql_query($sql,$_mysql_link_);
				$resu3   = mysql_fetch_object($this3);
				$district_id = intval($resu3->number);
				//获取所在城市的仓库id(不存在的就用默认的)
				$sql 	= "SELECT store_id FROM store_address WHERE company_id='$company_id' AND city_id='$city_id'";
				$this4  = mysql_query($sql,$_mysql_link_);
				$resu4  = mysql_fetch_object($this4);
				if($resu4->store_id){
					$store_id = intval($resu4->store_id);
				}else{
					$sql   = "SELECT id FROM store_info WHERE company_id='$company_id' AND store_status='Default'";
					$this5 = mysql_query($sql,$_mysql_link_);
					$resu5 = mysql_fetch_object($this5);
					$store_id = intval($resu5->id);
				}

				$total_pay = 0;	//应付金额
				$total_yh  = 0;	//子订单级订单优惠金额
				$real_pay  = 0; //实付金额

				$resul   = $res->trade_get_response->trade->orders->order;
				for($m=0;$m<count($resul);$m++){
					$payment       = floatval($resul[$m]->received_payment); //实付金额
					$discount_fee  = floatval($resul[$m]->discount_fee);  //子订单级订单优惠金额。
					$total_fee     = floatval($resul[$m]->total_fee);     //应付金额
					$total_pay 	  += $total_fee;
					$total_yh     += $discount_fee;
					$real_pay     += $payment;
				}
				// $real_pay  += $post_fee;
				$total_pay += $post_fee; //应付金额

				//卖家已发货
				if($status == " WAIT_BUYER_CONFIRM_GOODS"){
					$sql = "INSERT INTO order_info SET order_date='$created', company_id='$company_id', status='S'";
					mysql_query($sql,$_mysql_link_);
					$row = mysql_affected_rows($_mysql_link_);
					$id = mysql_insert_id($_mysql_link_);
				}
				//添加交易的创建时间
				$sql = "INSERT INTO order_info SET order_date='$created', company_id='$company_id'";
				mysql_query($sql,$_mysql_link_);
				$row = mysql_affected_rows($_mysql_link_);
				$id = mysql_insert_id($_mysql_link_);
				if($row){
					for($m=0;$m<count($resul);$m++){
						$discount_fee  = $resul[$m]->discount_fee;  //子订单级订单优惠金额。
						$num  		   = $resul[$m]->num;			//数量
						$num_iid  	   = $resul[$m]->num_iid;
						$payment       = $resul[$m]->payment;
						$price         = $resul[$m]->price;
						$title         = $resul[$m]->title;
						$total_fee     = $resul[$m]->total_fee;  //应付金额
						$properties    = $resul[$m]->sku_properties_name;
						$prop = explode(":",str_replace(";", ":", $properties));

						// 获取单个商品的交易信息
						$pro			= array();
						$pro['method']	= 'taobao.item.seller.get';
						$pro['fields']	= 'sku.properties_name';
						$pro['num_iid'] = $num_iid;
						$pro['nick']	= $ShopInfo[$user_id]->nick_name;
						$site		= get_taobao_response($pro, $_application_info_['key'], $_application_info_['secret'], $ShopInfo[$user_id]->taobao_session, 'json');
						$data			= json_decode($site);
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
										price='$price', discount='$discount_fee', payment='$total_fee',
										store_id='$store_id'";
										mysql_query($sql,$_mysql_link_);

										$sql = "UPDATE store_related SET
										available_total = available_total-'$num',
										lock_total = lock_total+'$num'
										WHERE company_id='$company_id' AND product_id='$dbRes->product_id' AND store_id='$store_id'";
										mysql_query($sql,$_mysql_link_);

										$sql = "UPDATE store_product SET
										total_available = total_available-'$num',
										total_lock = total_lock+'$num'
										WHERE company_id='$company_id' AND product_id='$dbRes->product_id' AND store_id='$store_id'";
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

					$sql = "SELECT name,crm_user_id FROM crm_user_related WHERE company_id='$company_id' AND telphone='$receiver_mobile'";
					$result = mysql_query($sql,$_mysql_link_);
					$res = mysql_fetch_object($result);
					$crm_user_id = intval($res->crm_user_id);
					if(!$res){
						$sql = "INSERT INTO crm_user_known(id,name) VALUES ('','$name')";
						mysql_query($sql,$_mysql_link_);
						$rows = mysql_affected_rows($_mysql_link_);
						if($rows){
							$bind_id = mysql_insert_id($_mysql_link_);
							$sql = "INSERT INTO crm_user_info(id,nick_name)VALUES('','$buyer_nick')";
							mysql_query($sql,$_mysql_link_);
							$crm_id = mysql_insert_id($_mysql_link_);

							$sql = "INSERT INTO crm_user_related(
							id,
							company_id,
							crm_user_id,
							state_id,
							city_id,
							district_id,
							post_code,
							address,
							name,
							telphone,
							mobile
							)VALUES(
							'',
							'$company_id',
							'$crm_id',
							'$state_id',
							'$city_id',
							'$district_id',
							'$receiver_zip',
							'$receiver_address',
							'$name',
							'$receiver_phone',
							'$receiver_mobile'
							)";
							mysql_query($sql,$_mysql_link_);
							$sql = "INSERT INTO order_source (
							id,
							company_id,
							user_id,
							bind_type,
							bind_number,
							crm_user_id,
							order_text,
							customer_text
							) VALUES (
							'$id',
							'$company_id',
							'$user_id',
							'Taobao',
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
						bind_type,
						bind_number,
						crm_user_id,
						order_text,
						customer_text
						) VALUES (
						'$id',
						'$company_id',
						'Taobao',
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
					state_id,
					city_id,
					district_id,
					post_code,
					address
					)VALUES(
					'$id',
					'$company_id',
					'$name',
					'$receiver_phone',
					'$receiver_mobile',
					'$state_id',
					'$city_id',
					'$district_id',
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
					if($status == " WAIT_BUYER_CONFIRM_GOODS"){
						$sql = "INSERT INTO order_delivery(
							id,
							company_id,
							delivery_status,
							action_date
							)VALUES(
							'$id',
							'$company_id',
							'Finish',
							'$consign_time'
						)";
						mysql_query($sql,$_mysql_link_);
					}

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
		// }
	}


	//订单访问日志
	for($i=0;$i<count($array_tid);$i++){
		$ord_tid .= $array_tid[$i].",";
	}
	$ord_tid = trim($ord_tid,",");
	$time = time();
	$arr = array("userId"=>"{$_SESSION['_application_info_']['user_id']}","userIp"=>"{$_SERVER['REMOTE_ADDR']}","ati"=>"{$_COOKIE['_ati']}","topAppKey"=>"23283073","appName"=>"米欢ERP_订单管理_店铺管理","url"=>"http://gw.ose.aliyun.com/event/order","tradeIds"=>"{$ord_tid}","operation"=>"download","time"=>"{$time}","appKey"=>"68756630");
	$sign = createSign("vtZH273YXbBKXBiZpzzL",$arr);
	$url = "appKey=".urlencode(68756630)."&appName=".urlencode('米欢ERP_订单管理_店铺管理')."&ati=".urlencode($_COOKIE['_ati'])."&operation=".urlencode('download')."&time=".urlencode($time)."&topAppKey=".urlencode(23283073)."&tradeIds=".urlencode($ord_tid)."&url=".urlencode('http://gw.ose.aliyun.com/event/order')."&userId=".urlencode($_SESSION['_application_info_']['user_id'])."&userIp=".urlencode($_SERVER['REMOTE_ADDR'])."&sign=".$sign;

	//初始化
    $curl = curl_init();
     //设置抓取的url
     curl_setopt($curl, CURLOPT_URL, 'http://gw.ose.aliyun.com/event/order?'.$url);
     //设置头文件的信息作为数据流输出
    curl_setopt($curl, CURLOPT_HEADER, 1);
     //设置获取的信息以文件流的形式返回，而不是直接输出。
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
     //设置post方式提交
    curl_setopt($curl, CURLOPT_POST, 1);
     //设置post数据
    $post_data = array("userId"=>"{$_SESSION['_application_info_']['user_id']}","userIp"=>"{$_SERVER['REMOTE_ADDR']}","ati"=>"{$_COOKIE['_ati']}","topAppKey"=>"23283073","appName"=>"米欢ERP_订单管理_店铺管理","url"=>"http://gw.ose.aliyun.com/event/order","tradeIds"=>"{$ord_tid}","operation"=>"download","time"=>"{$time}","appKey"=>"68756630","sign"=>"{$sign}");
     curl_setopt($curl, CURLOPT_POSTFIELDS, $post_data);
     //执行命令
    $data = curl_exec($curl);
     //关闭URL请求
    curl_close($curl);
     //显示获得的数据
	echo "<script>\n";
	echo "parent.$('#MessageBox').modal('hide');\n";
	echo "parent.location.replace('order_list_audit.php');";
	echo "</script>\n";
	echo "<center><br/><br/><br/><br/>商品下载完成!<br/><br/><br/><br/></center>";
}

//进行签名
function createSign($appSecret, $paramArr) {
	$data = $appSecret;
	ksort($paramArr);

	foreach ($paramArr as $key => $val) {
		$data .= $key . $val;
	}
	$data .= $appSecret;

	$sign = md5($data);

	return $sign;
}

$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");