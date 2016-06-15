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
	$company_id=$_SESSION['_application_info_']['company_id'];



	$order_id = rtrim(replace_safe($_REQUEST['id']),",");
	//---- 提交修改 ----
	if(!empty($_POST['level'])){
		//仓库的id号
		$level = intval($_POST['level']);
		$orderId = explode(',',$_POST['orderId']);
		$parr = array();
		foreach ($orderId as $k => $v) {
			//获取商品的订单编号
			$orSql = "SELECT bind_number FROM order_source WHERE id=$v AND company_id=$company_id";
			$orres = mysql_query($orSql,$_mysql_link_);
			$ordata = mysql_fetch_object($orres);
			$bind_number = $ordata->bind_number;

			//订单的商品Id store_id total
			$sql	= "SELECT product_id, store_id, total FROM order_product WHERE order_id = $v";
			$result = mysql_query($sql,$_mysql_link_);
			while($res = mysql_fetch_object($result)){
				$total = $res->total;
				$store_sql = "SELECT product_id FROM store_related WHERE store_id=$level AND product_id=$res->product_id";
				$store_res = mysql_query($store_sql,$_mysql_link_);
				$store_flag = mysql_num_rows($store_res);
				//获取商品的名称
				$proSql = "SELECT name FROM product_info WHERE id=$res->product_id AND company_id=$company_id";
				$prores = mysql_query($proSql,$_mysql_link_);
				$prodata = mysql_fetch_object($prores);
				$proName = $prodata->name;

				if ($store_flag <= 0) {
					$parr[$bind_number][] = $proName;
				}
				if (count($parr[$bind_number]) == 0) {
					//去除原来仓库的锁定商品数量
					$sql	= "UPDATE store_related SET lock_total = lock_total-$total,available_total=available_total+$total WHERE product_id='$res->product_id' AND store_id='$res->store_id'";
					mysql_query($sql,$_mysql_link_);
					// //添加锁定商品数量
					$sql = "UPDATE store_related SET lock_total = lock_total+$total,available_total=available_total-$total WHERE product_id='$res->product_id' AND store_id='$level'";
					mysql_query($sql,$_mysql_link_);
					// //修改订单中的仓库ID
					$sql = "UPDATE order_product SET store_id = '$level' WHERE order_id = $v AND company_id = '$company_id'";
					mysql_query($sql,$_mysql_link_);
				}
			}
		}
		if(count($parr) > 0) {
			foreach ($parr as $key => $value) {
				if(count($value) > 0) {
					echo json_encode($parr);
					return false;
				}
			}
		}else {
			echo json_encode(1);
			return false;
		}


		// echo "<script>\n";
		// echo "parent.$('#MessageBox').modal('hide');\n";
		// echo "parent.location.replace('/order/order_list_audit.php');";
		// echo "</script>\n";
		// echo "<center><br/><br/><br/><br/>修改完成！<br/><br/><br/><br/></center>";

	}

	//---- 发货仓库 ----
	$sql = "SELECT id,name FROM store_info where (store_status = 'Normal' OR store_status = 'Default') AND company_id='$company_id'";
	$result	= mysql_query($sql, $_mysql_link_);
	while($StoreInfo = mysql_fetch_object($result))
	{
		$list_store	= array();
		$list_store['orderId']		= $order_id;
		$list_store['id']			= $StoreInfo->id;
		$list_store['name']			= $StoreInfo->name;
		$xtpl->assign("list_store", $list_store);
		$xtpl->parse("main.list_store");
	}



$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");


