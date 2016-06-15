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

$company_id = $_SESSION['_application_info_']['company_id'];

	if(!empty($_GET['after_id'])){
		$after_id = intval($_GET['after_id']);
			$num = 1;
		$sql = "SELECT order_id, store_id, product_id, shop_id, total_finish, total_wait, action_date, body FROM after_sale_input WHERE after_sale_id='$after_id' AND company_id='$company_id'";
		$result = mysql_query($sql,$_mysql_link_);
		while($res = mysql_fetch_object($result)){
			$arr = array();
			$arr['after_id']     = $after_id;
			$arr['order_id']     = $res->order_id;
			$arr['date']         = $res->action_date;
			$arr['body']         = $res->body;
			$arr['total_finish'] = $res->total_finish;
			$arr['total_wait']   = $res->total_wait;
			$arr['product_id']   = $res->product_id;
			//---- 订单编号 ----
			$o_sql = "SELECT bind_number FROM order_source WHERE id=$res->order_id AND company_id=$company_id";
			$o_res = mysql_query($o_sql,$_mysql_link_);
			$o_data = mysql_fetch_object($o_res);
			$arr['bind_number'] = $o_data->bind_number;

			//店铺名称
			$sql1 = "SELECT shop_name FROM user_register_info WHERE id='$res->shop_id'";
			$query = mysql_query($sql1,$_mysql_link_);
			while($res1 = mysql_fetch_object($query)){
				$arr['shop_name'] = $res1->shop_name;
			}
			//---- 发货仓库 ----
			$sql = "SELECT name FROM store_info where store_status ='Normal' AND company_id='$company_id' AND id='$res->store_id'";
			$result3	= mysql_query($sql, $_mysql_link_);
			while($res3 = mysql_fetch_object($result3))
			{
				$arr['store_name']			= $res3->name;
			}

			//---- 商品信息 ----
			$sql = "SELECT product_info.name,
			product_detail.value_id_1,product_detail.value_id_2,product_detail.value_id_3,product_detail.value_id_4,product_detail.value_id_5
			FROM product_info
			LEFT JOIN product_detail on product_info.id = product_detail.id
			WHERE product_info.is_delete='N' AND product_info.company_id = '$company_id' AND product_info.id ='{$arr['product_id']}'";
			$result2 = mysql_query($sql,$_mysql_link_);
			$array = array();
			$format = "";
			while($StoreInfo = mysql_fetch_object($result2)){

				$array = array(
				'name' 		     => $StoreInfo->name,
				'value_id_1'     => $StoreInfo->value_id_1,
				'value_id_2'     => $StoreInfo->value_id_2,
				'value_id_3'     => $StoreInfo->value_id_3,
				'value_id_4'     => $StoreInfo->value_id_4,
				'value_id_5'     => $StoreInfo->value_id_5
				);
				for($j=1;$j<=5;$j++){
					$format_id = $array['value_id_'.$j];
					$sql = "SELECT body FROM product_format_value WHERE company_id = '$company_id' AND id = '$format_id'";
					$result1 = mysql_query($sql,$_mysql_link_);
					while($re = mysql_fetch_object($result1)){
						$format .= $re->body.",";
					}

				}
			}

			$product = array(
				'total_finish'   => $arr['total_finish'],
				'total_wait' 	 => $arr['total_wait'],
				'name'           => $array['name'],
				'format'         => rtrim($format,","),
				'num'            => $num++,
				);
			$xtpl->assign("product", $product);
			$xtpl->parse("main.product");




		}
		$xtpl->assign("arr", $arr);
		$xtpl->parse("main.arr");
	}


$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");