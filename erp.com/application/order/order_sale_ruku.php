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

	$rq = date("Y-m-d");
	//---- 发货仓库 ----
	$sql = "SELECT id,name FROM store_info where (store_status ='Normal' OR store_status='Default') AND company_id='$company_id'";
	$result	= mysql_query($sql, $_mysql_link_);
	while($StoreInfo = mysql_fetch_object($result))
	{
		$store	= array();
		$store['id']			= $StoreInfo->id;
		$store['name']			= $StoreInfo->name;

		$xtpl->assign("store",$store);
		$xtpl->parse("main.arr.store");
	}


	//接收传过来的数据
	if(!empty($_GET['id'])){
		//订单id
		$order_id = intval($_GET['id']);
		//售后id
		$after_id = intval($_GET['after_id']);
		// 一件商品的id
		$sql = "SELECT id,bind_number,user_id FROM order_source WHERE id='$order_id' AND company_id='$company_id'";
		$query = mysql_query($sql,$_mysql_link_);
		while($res = mysql_fetch_object($query)){
			$arr = array();
			$arr['bind_number'] = $res->bind_number;
			$arr['id'] = $res->id;
			$arr['user_id']     = $res->user_id;
			$num = 1;

			// 每个商品的总数
			$sql = "SELECT order_product.product_id,order_product.total,order_source.bind_number FROM order_product LEFT JOIN order_source ON order_product.order_id = order_source.id WHERE order_product.company_id='$company_id' AND order_product.order_id = '$order_id'";
			$result = mysql_query($sql,$_mysql_link_);
			while ($data = mysql_fetch_object($result)) {
				$all[] = array(
					'product_id' => $data->product_id,
					'total' => $data->total
				);
			}

			// 商品已入库数
			$afterruku = array();
			$sql = "SELECT total_finish,product_id FROM after_sale_input WHERE company_id='$company_id' AND order_id='$order_id'";
			$query2 = mysql_query($sql,$_mysql_link_);
			while($res = mysql_fetch_object($query2)){
				$afterruku[] = array(
					'total'      =>$res->total_finish,
					'product_id' => $res->product_id
				);
			}


			$sql = "SELECT after_sale_product.product_id,after_sale_product.total,after_sale_product.price,after_sale_info.id FROM after_sale_info LEFT JOIN after_sale_product ON after_sale_info.id=after_sale_product.after_sale_id
			WHERE after_sale_info.id='$after_id' AND after_sale_info.status='N' AND after_sale_info.company_id='$company_id'";
			$query1 = mysql_query($sql,$_mysql_link_);
			while($res = mysql_fetch_object($query1)){
				//--每条售后单的信息--
				$sql = "SELECT product_info.name,
				product_detail.value_id_1,product_detail.value_id_2,product_detail.value_id_3,product_detail.value_id_4,product_detail.value_id_5
				FROM product_info
				LEFT JOIN product_detail on product_info.id = product_detail.id
				WHERE product_info.is_delete='N' AND product_info.company_id = '$company_id' AND product_info.id ='$res->product_id'";
				$result = mysql_query($sql,$_mysql_link_);
				$array = array();
				$format = "";

				while($StoreInfo = mysql_fetch_object($result)){

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
				// 售后已入库的数量
				$sql = "SELECT total_finish FROM after_sale_input WHERE product_id='$res->product_id' AND after_sale_id='$res->id'";
				$query3 = mysql_query($sql,$_mysql_link_);
				$re = mysql_fetch_object($query3);

				$ru = $re->total_finish;
				//总数量分配
				if (count($all) > 1) {
					for ($i=0; $i < count($all); $i++) {
						if ($res->product_id == $all[$i]['product_id']) {
							$oldTotal = $all[$i]['total'];
						}
					}
				}else{
					$oldTotal = 0;
				}

				// 已入库数量分配
				$aftertotal = 0;
				if(count($afterruku) > 1){
					for($i=0;$i<count($afterruku);$i++){
						if($res->product_id == $afterruku[$i]['product_id']){
							$aftertotal += $afterruku[$i]['total'];
						}
					}
				}else{
					$aftertotal = 0;
				}

				$product = array(
					'aftertotal'   => $aftertotal,
					'oldTotal'   => $oldTotal,
					'ru'         => $ru,
					'total'      => $res->total,
					'price' 	 => $res->price,
					'name'       => $array['name'],
					'format'     => rtrim($format,","),
					'num'        => $num++,
					'product_id' => $res->product_id,
					'after_id'   => $res->id
					);
				$xtpl->assign("product", $product);
				$xtpl->parse("main.product");
			}
			//店铺名称
			$sql = "SELECT shop_name FROM user_register_info WHERE id='{$arr['user_id']}'";
			$query2 = mysql_query($sql,$_mysql_link_);
			while($res = mysql_fetch_object($query2)){
				$arr['shop_name'] = $res->shop_name;
			}
			$arr['rq']            = $rq;
			$xtpl->assign("arr", $arr);
			$xtpl->parse("main.arr");
		}

		if(isset($_POST['submit'])){
			$order_id    = replace_safe($_POST['order_id']);
			$shop_id     = intval($_POST['shop_id']);
			$time        = replace_safe($_POST['date']);
			$body        = replace_safe($_POST['body']);
			$ruku        = $_POST['ruku'];
			$product_id  = $_POST['product_id'];
			$store_id    = intval($_POST['store_id']);
			$after_id    = intval($_POST['after_id']);
			$total_wait  = $_POST['total_wait'];


			for($i=0;$i<count($ruku);$i++){
				$ruku[$i]     = intval($ruku[$i]);
				$product_id[$i] = replace_safe($product_id[$i]);
				$total_wait[$i] = intval($total_wait[$i]);

				if($ruku[$i] != 0){

					$sql = "UPDATE store_related SET real_total=real_total+'{$ruku[$i]}' WHERE store_id='$store_id' AND product_id='{$product_id[$i]}' AND company_id='$company_id'";
					mysql_query($sql,$_mysql_link_);
					$sql = "UPDATE after_sale_info SET is_store='Y' WHERE company_id='$company_id' AND order_id='$order_id' AND id='$after_id'";
					mysql_query($sql,$_mysql_link_);
					$sql = "SELECT count(*) AS total FROM after_sale_input WHERE after_sale_id='$after_id' AND company_id='$company_id' AND product_id='$product_id[$i]'";
					$query = mysql_query($sql,$_mysql_link_);
					$total = mysql_result($query, 0, 'total');
					if($total > 0){
						$sql = "UPDATE after_sale_input SET total_finish=total_finish+'{$ruku[$i]}',total_wait=total_wait+'{$total_wait[$i]}' WHERE company_id='$company_id' AND after_sale_id='$after_id' AND product_id='$product_id[$i]'";
						mysql_query($sql,$_mysql_link_);
					}else{
						$sql = "INSERT INTO after_sale_input (id,company_id,order_id,store_id,product_id,shop_id,total_finish,total_wait,after_sale_id,action_date,body) values ('','$company_id','$order_id','$store_id','{$product_id[$i]}','$shop_id','{$ruku[$i]}','{$total_wait[$i]}','$after_id','$time','$body')";
						mysql_query($sql,$_mysql_link_);
					}

					$sql = "UPDATE after_sale_product SET total='$total_wait[$i]' WHERE company_id='$company_id' AND product_id='$product_id[$i]' AND after_sale_id='$after_id'";
					mysql_query($sql,$_mysql_link_);
				}
				mysql_query($sql,$_mysql_link_);
				echo "<script>\n";
				echo "parent.$('#MessageBox').modal('hide');\n";
				echo "parent.location.replace(parent.location.href);";
				echo "</script>\n";
				echo "<center><br/><br/><br/><br/>修改完成！<br/><br/><br/><br/></center>";
			}
		}

	}

$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");