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
	//order是checkbox选中的订单的id数组
	if(!empty($_POST['order'])){
		$order = replace_safe($_POST['order']);
		$order = explode(",",rtrim($order,","));
		$arr = array();
		for($i=0;$i<count($order);$i++){
			// 查找要拆分的订单的数量是否大于1
			$sql = "SELECT sum(total) AS sum FROM order_product WHERE company_id='$company_id' AND order_id='$order[$i]'";
			$result = mysql_query($sql,$_mysql_link_);
			while($res = mysql_fetch_object($result)){
				$arr[] = $res->sum;
			}
		}
		$sum = 0;
		for($i=0;$i<count($arr);$i++){
			$sum += $arr[$i];
		}
		if($sum >= 2){
			echo 1;
			exit;
		}else{
			echo 0;
			exit;
		}
	}
	$num = 1;
	$count = 1;
	// 选中要拆分的订单号
	$order = rtrim(replace_safe($_REQUEST['id']),",");
		$sql = "SELECT order_product.product_id,order_product.payment,order_product.total,order_source.bind_number FROM order_product LEFT JOIN order_source ON order_product.order_id = order_source.id WHERE order_product.company_id='$company_id' AND order_product.order_id = '$order'";


		$result = mysql_query($sql,$_mysql_link_);
		while($res = mysql_fetch_object($result)){
			// 查找商品的名称和规格
			$sql3 = "SELECT product_info.id,product_info.name,
			product_detail.value_id_1,product_detail.value_id_2,product_detail.value_id_3,product_detail.value_id_4,product_detail.value_id_5
			FROM product_info
			LEFT JOIN product_detail on product_info.id = product_detail.id
			WHERE product_info.is_delete='N' AND product_info.company_id = '$company_id' AND product_info.id ='$res->product_id'";
			$result3 = mysql_query($sql3,$_mysql_link_);
			$arr = array();
			$format = "";

			while($StoreInfo = mysql_fetch_object($result3)){

				$arr = array(
				'name' 		     => $StoreInfo->name,
				'value_id_1'     => $StoreInfo->value_id_1,
				'value_id_2'     => $StoreInfo->value_id_2,
				'value_id_3'     => $StoreInfo->value_id_3,
				'value_id_4'     => $StoreInfo->value_id_4,
				'value_id_5'     => $StoreInfo->value_id_5
				);
				// 得出规格可选值$format 一个字符串
				for($j=1;$j<=5;$j++){
					$format_id = $arr['value_id_'.$j];
					$sql1 = "SELECT body FROM product_format_value WHERE company_id = '$company_id' AND id = '$format_id'";
					$result1 = mysql_query($sql1,$_mysql_link_);
					while($re = mysql_fetch_object($result1)){
						$format .= $re->body.",";
					}

				}
			}
			// 拆前的
			$product = array(
				'total'      => $res->total,
				'name'       => $arr['name'],
				'format'     => rtrim($format,","),
				'num'        => $num++,
				'bind_number'=> $res->bind_number,
				'order_id'   => $order,
				'product_id' => $res->product_id
				);
			// 拆后的
			$pro = array(
				'total'      => $res->total,
				'name'       => $arr['name'],
				'format'     => rtrim($format,","),
				'num'        => $count++,
				'bind_number'=> $res->bind_number,
				'order_id'   => $order,
				'product_id' => $res->product_id
				);
			$xtpl->assign("product", $product);
			$xtpl->parse("main.product");

			$xtpl->assign("pro", $pro);
			$xtpl->parse("main.pro");
		}


	// 拆分提交
	if(isset($_POST['submit'])){
		//订单id
		$order_id   = intval($_POST['order_id']);
		// 商品id
		$product_id = $_POST['product_id'];

		$old_total  = $_POST['old_total'];
		// 拆分后的数量
		$split      = $_POST['split'];
		// 商品id
		$new_id     = replace_safe($_POST['new_id']);
		// 拆分出来的商品的数量
		$new_total  = replace_safe($_POST['new_total']);
		$pro = array();
		$arr = array();

		$sql2 = "SELECT i.is_audit,i.order_date,e.express_id,
				s.bind_type,s.bind_number,s.related_order,s.order_text,s.customer_text,s.user_id,
				r.name,r.phone,r.mobile,r.state_id,r.city_id,r.district_id,r.post_code,r.address,r.company_name,r.need_invoice,r.tax_title,r.tax_number,r.tax_bank_name,r.tax_bank_number,r.tax_text,
				f.discount,f.theory_amount,f.real_amount,f.payment_status,f.arrears,f.bank_id
				FROM order_info AS i
				LEFT JOIN order_source AS s ON i.id=s.id
				LEFT JOIN order_receiver AS r ON i.id=r.id
				LEFT JOIN order_express_paper AS e ON i.id=e.order_id
				LEFT JOIN finance_order AS f ON i.id=f.order_id WHERE i.company_id='$company_id' AND i.id='$order_id' AND i.is_delete='N'";

		$result2 = mysql_query($sql2,$_mysql_link_);
		while($res = mysql_fetch_object($result2)){
			$arr['total_dis']  = $res->discount;      	//优惠
			$arr['theory']     = $res->theory_amount;
			$arr['real']       = $res->real_amount;
			$arr['status']     = $res->payment_status;
			$arr['arrears']    = $res->arrears;
			$arr['bank_id']    = $res->bank_id;
			$arr['is_audit']   = $res->is_audit;
			$arr['order_date'] = $res->order_date;
			$arr['bind_type']  = $res->bind_type;
			$arr['bind_number']  = $res->bind_number;

			$arr['user_id']        = $res->user_id;
			$arr['related_order']  = $res->related_order;
			$arr['order_text']     = $res->order_text;
			$arr['customer_text']  = $res->customer_text;

			$arr['name']           = $res->name;
			$arr['phone']          = $res->phone;
			$arr['mobile']         = $res->mobile;
			$arr['state_id']       = $res->state_id;
			$arr['city_id']        = $res->city_id;
			$arr['district_id']    = $res->district_id;
			$arr['post_code']      = $res->post_code;
			$arr['address']        = $res->address;
			$arr['company_name']   = $res->company_name;
			$arr['need_invoice']   = $res->need_invoice;
			$arr['tax_title']      = $res->tax_title;
			$arr['tax_number']     = $res->tax_number;
			$arr['tax_bank_number']= $res->tax_bank_number;
			$arr['tax_bank_name']  = $res->tax_bank_name;
			$arr['tax_text']       = $res->tax_text;
			$arr['express_id']     = $res->express_id;
		}

		//拆单前的原始订单号
		$old_bind = $arr['bind_number'];
		//查询这个订单有没有被拆分
		$sql8 = "SELECT order_id,target_id FROM order_split WHERE target_id='$order_id' AND company_id='$company_id' AND split_status='Split'";
		$res8 = mysql_query($sql8,$_mysql_link_);
		$num8 = mysql_num_rows($res8);

		if($num8){
			$data8 = mysql_fetch_assoc($res8);
			//存在父订单
			$pid = $data8['order_id'];
			//父订单编号
			$sqln = "SELECT bind_number FROM order_source WHERE id='$pid' AND company_id='$company_id'";
			$resn = mysql_query($sqln,$_mysql_link_);
			$datan = mysql_fetch_assoc($resn);
			$b_number = $datan['bind_number'];
			//已经拆分了几个
			$sqlc = "SELECT order_id,target_id FROM order_split WHERE order_id = '$order_id'";
			$resc = mysql_query($sqlc,$_mysql_link_);
			$numc = mysql_num_rows($resc)+1;
			//新的订单编号
			$bind_number = $old_bind.$numc;
		}else{
			//父订单编号
			$sqln = "SELECT bind_number FROM order_source WHERE id='$order_id' AND company_id='$company_id'";
			$resn = mysql_query($sqln,$_mysql_link_);
			$datan = mysql_fetch_assoc($resn);
			$b_number = $datan['bind_number'];
			//已经拆分了几个
			$sqlc = "SELECT order_id,target_id FROM order_split WHERE order_id = '$order_id'";
			$resc = mysql_query($sqlc,$_mysql_link_);
			$numc = mysql_num_rows($resc)+1;

			//新订单编号
			if($numc){
				$bind_number =$old_bind.$numc;
			}else{
				$bind_number = $old_bind.'1';
			}

		}

		// $bind_number 	= replace_safe(insert_company_number($company_id, "order"));
		// $bind_number    = date("Ymd").$bind_number; //自动生成订单编号

		$sql = "INSERT INTO order_info (id,company_id,is_audit,order_date)VALUES('','$company_id','{$arr['is_audit']}','{$arr['order_date']}')";
		mysql_query($sql,$_mysql_link_);
		$row = mysql_affected_rows($_mysql_link_);
		$n_order = mysql_insert_id($_mysql_link_);
		if($row){
			$order = mysql_insert_id($_mysql_link_);
			$sql = "INSERT INTO order_source (id,company_id,user_id,bind_type,bind_number,related_order,order_text,customer_text) VALUES ('$order','$company_id','$arr[user_id]','$arr[bind_type]','$bind_number','$arr[related_order]','$arr[order_text]','$arr[customer_text]')";
			mysql_query($sql,$_mysql_link_);
			for($j=0;$j<count($split);$j++){
				$split[$j] = intval($split[$j]);
				$product_id[$j] = intval($product_id[$j]);
				$old_total[$j]  = intval($old_total[$j]);
				if($split[$j] != 0){
					$sql = "SELECT total,price,discount,payment,store_id,content FROM order_product WHERE company_id='$company_id' AND order_id='$order_id' AND product_id='$product_id[$j]'";
					$result = mysql_query($sql,$_mysql_link_);
					while($res = mysql_fetch_object($result)){

						$pro['payment']  = $res->payment;
						$pro['discount'] = $res->discount;
						$pro['total']    = $res->total;
						$pro['price']    = $res->price;
						$pro['store_id'] = $res->store_id;
						$pro['content']  = $res->content;

					}

					$youhui = $pro['discount']*($split[$j]/$pro['total']);
					$pay = $split[$j]*$pro['price']-$youhui;
					$sql = "INSERT INTO order_product(
					id,
					company_id,
					order_id,
					store_id,
					product_id,
					price,
					total,
					discount,
					payment,
					content
					)VALUES(
					'',
					'$company_id',
					'$order',
					'$pro[store_id]',
					'{$product_id[$j]}',
					'$pro[price]',
					'{$split[$j]}',
					'$youhui',
					'$pay',
					'$pro[content]'
					)";
					mysql_query($sql,$_mysql_link_);

					$bili       = $pay/$arr['theory'];
					$old_theory = $arr['theory'] - $pay;
					$old_real   = $arr['real']*(1-$bili);
					$arrears    = $arr['arrears']*(1-$bili);
					if($old_total[$j] == 0){
						$sql = "DELETE FROM order_product WHERE order_id='$order_id' AND company_id='$company_id' AND product_id='$product_id[$j]'";
						mysql_query($sql,$_mysql_link_);
					}else{
						$old_disco = $pro['discount']*($old_total[$j]/$pro['total']);

						$pays = $pro['price']*$old_total[$j]-$old_disco;
						$sql = "UPDATE order_product SET total='$old_total[$j]',payment='$pays',discount='$old_disco' WHERE order_id='$order_id' AND company_id='$company_id' AND product_id='$product_id[$j]'";
						mysql_query($sql,$_mysql_link_);
					}



				}

			}
			$sql = "SELECT sum(payment) AS new_pay,sum(discount) AS new_dis FROM order_product WHERE company_id='$company_id' AND order_id='$order_id'";
			$this = mysql_query($sql,$_mysql_link_);
			$old = mysql_fetch_object($this);
			$old_pay = $old->new_pay;
			$old_dis = $old->new_dis;

			$sql = "SELECT sum(payment) AS new_pay,sum(discount) AS new_dis FROM order_product WHERE company_id='$company_id' AND order_id='$order'";
			$resu = mysql_query($sql,$_mysql_link_);
			$p = mysql_fetch_object($resu);
			$new_pay = $p->new_pay;
			$new_dis = $p->new_dis;
			$proportion = $new_pay/$arr['theory'];
			$real = $arr['real']*$proportion;
			$arrears = $arr['arrears']*$proportion;

			$old_realp = $arr['real']-$real;
			$old_arrears = $arr['arrears']-$arrears;
			$sql = "UPDATE finance_order SET theory_amount='$old_pay',discount='$old_dis',real_amount='$old_realp',arrears='$old_arrears' WHERE order_id='$order_id' AND company_id='$company_id'";
			mysql_query($sql,$_mysql_link_);

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
			'$order',
			'$arr[bank_id]',
			'$new_dis',
			'$new_pay',
			'$real',
			'$arr[status]',
			'$arrears'
			)";
			mysql_query($sql,$_mysql_link_);
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
			address,
			company_name,
			need_invoice,
			tax_number,
			tax_bank_name,
			tax_bank_number,
			tax_title,
			tax_text
			)VALUES(
			'$order',
			'$company_id',
			'$arr[name]',
			'$arr[phone]',
			'$arr[mobile]',
			'$arr[state_id]',
			'$arr[city_id]',
			'$arr[district_id]',
			'$arr[post_code]',
			'$arr[address]',
			'$arr[company_name]',
			'$arr[need_invoice]',
			'$arr[tax_number]',
			'$arr[tax_bank_name]',
			'$arr[tax_bank_number]',
			'$arr[tax_title]',
			'$arr[tax_text]'
			)";
			mysql_query($sql,$_mysql_link_);
			$sql = "INSERT INTO order_express_paper(
			id,
			company_id,
			order_id,
			express_id
			)VALUES(
			'',
			'$company_id',
			'$order',
			'$arr[express_id]'
			)";

			mysql_query($sql,$_mysql_link_);


		}

		// 把新的订单信息进行保存
		// $n_order = mysql_insert_id($_mysql_link_);
		$csql = "INSERT INTO order_split(id,company_id,order_id,target_id,split_status)VALUES('','$company_id','$order_id','$n_order','Split')";
		$cres = mysql_query($csql,$_mysql_link_);

		echo "<script>\n";
		echo "parent.$('#MessageBox').modal('hide');\n";
		echo "parent.location.replace('/order/order_list_audit.php');";
		echo "</script>\n";
		echo "<center><br/><br/><br/><br/>添加完成！<br/><br/><br/><br/></center>";
		exit;
	}

$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");
