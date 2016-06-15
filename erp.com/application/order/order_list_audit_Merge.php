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
//获取的选中的Id号
if(!empty($_REQUEST['id'])){
	$order = rtrim(replace_safe($_REQUEST['id']),",");
	$arr = array();
	$num = 0;
	$sql = "SELECT s.id,s.bind_number,s.user_id,
	r.name,r.district_id,r.address
	FROM order_source AS s
	LEFT JOIN order_receiver AS r ON s.id=r.id
	WHERE s.company_id='$company_id' AND s.id IN ($order)";
	$result = mysql_query($sql,$_mysql_link_);
	while($res = mysql_fetch_object($result)){
		//订单的id
		$oid = $res->id;
		//查找用户店铺时用的userid
		$userid = $res->user_id;

		$arr['order_id']       = $res->id;
		$arr['bind_number']    = $res->bind_number;
		$arr['name']    	   = $res->name;
		$arr['district_id']    = $res->district_id;
		$arr['address']        = $res->address;

		$sql = "SELECT express_id FROM order_express_paper WHERE company_id='$company_id' AND order_id='$res->id'";
		$result1 = mysql_query($sql,$_mysql_link_);
		while($res = mysql_fetch_object($result1)){
			$sql =  "SELECT name FROM company_express_info WHERE express_id='$res->express_id' AND company_id='$company_id'";
			$result2 = mysql_query($sql,$_mysql_link_);
			while($res = mysql_fetch_object($result2)){
				$arr['express_name'] = $res->name;
			}
		}

		$sql = "SELECT name FROM main_identity_card WHERE number='$arr[district_id]'";
		$result3 = mysql_query($sql,$_mysql_link_);
		$district = mysql_fetch_object($result3);
		$arr['district_name'] = $district->name;

		$sql = "SELECT store_id FROM order_product WHERE company_id='$company_id' AND order_id='$oid'";
		$result4 = mysql_query($sql,$_mysql_link_);
		while($res = mysql_fetch_object($result4)){
			$sql =  "SELECT name FROM store_info WHERE id='$res->store_id' AND company_id='$company_id'";
			$result5 = mysql_query($sql,$_mysql_link_);
			while($res = mysql_fetch_object($result5)){
				$arr['store_name'] = $res->name;
			}
		}

		$sql = "SELECT shop_name FROM user_register_info WHERE id='$userid'";
		$query = mysql_query($sql,$_mysql_link_);
		while($res = mysql_fetch_object($query)){
			$arr['shop_name'] = $res->shop_name;
		}
		$arr['num'] = ++$num;
		$xtpl->assign("arr",$arr);
		$xtpl->parse("main.arr");
	}

}

//合并订单后提交
if(isset($_POST['submit'])){
	//订单号是一个数组
	$order_id = $_POST['order_id'];
	//父订单号
	$select   = intval($_POST['select_one']);
	$array = array();
	$imp = array();
	// 查询商品的信息
	$sql = "SELECT product_id,store_id,total,discount,payment FROM order_product WHERE company_id='$company_id' AND order_id='$select'";
	$this = mysql_query($sql,$_mysql_link_);
	while($res = mysql_fetch_object($this)){
		$imp[] = array(
			'product_id'    => $res->product_id,
			'store_id'      => $res->store_id,
			'total'         => $res->total,
			'discount'      => $res->discount,
			'payment'       => $res->payment
			);

	}
	// 商品的id号组成的数组
	$arr = array();
	for($j=0;$j<count($imp);$j++){
		array_push($arr,$imp[$j]['product_id']);
	}
	// 进行财务信息的查询
	for($i=0;$i<count($order_id);$i++){
		$order_id[$i] = intval($order_id[$i]);
		if($select != $order_id[$i]){
			$sql2 = "SELECT post_fee,theory_amount,real_amount,payment_status,arrears FROM finance_order WHERE company_id='$company_id' AND order_id='$select'";
			$result2 = mysql_query($sql2,$_mysql_link_);
			while($res = mysql_fetch_object($result2)){
				$old['post_fee']      = $res->post_fee;
				$old['theory_amount'] = $res->theory_amount;
				$old['real_amount']   = $res->real_amount;
				$old['payment_status']= $res->payment_status;
				$old['arrears']       = $res->arrears;
			}
			//查询合并时非父订单的财务信息
			$sql = "SELECT discount,post_fee,theory_amount,real_amount,payment_status,arrears FROM finance_order WHERE company_id='$company_id' AND order_id='$order_id[$i]'";
			$result = mysql_query($sql,$_mysql_link_);
			while($res = mysql_fetch_object($result)){
				$array['discount']      = $res->discount;
				$array['post_fee']      = $res->post_fee;
				$array['theory_amount'] = $res->theory_amount;
				$array['real_amount']   = $res->real_amount;
				$array['payment_status']= $res->payment_status;
				$array['arrears']       = $res->arrears;
			}
			$pro = array();
			// 非父订单的商品信息
			$sql = "SELECT product_id,store_id,total,price,discount,payment,content FROM order_product WHERE company_id='$company_id' AND order_id='$order_id[$i]'";
			$result1 = mysql_query($sql,$_mysql_link_);
			while($res = mysql_fetch_object($result1)){
					$pro[] = array(
					'product_id'    => $res->product_id,
					'store_id'      => $res->store_id,
					'total'         => $res->total,
					'discount'      => $res->discount,
					'payment'       => $res->payment,
					'price'         => $res->price,
					'content'       => $res->content
					);
			}
			for($m=0;$m<count($pro);$m++){

				if(in_array($pro[$m]['product_id'], $arr)){
					for($n=0;$n<count($imp);$n++){
						if($pro[$m]['product_id'] == $imp[$n]['product_id']){
							$new_total = $pro[$m]['total']+$imp[$n]['total'];
							$new_discount = $pro[$m]['discount']+$imp[$n]['discount'];
							$new_payment = $pro[$m]['payment']+$imp[$n]['payment'];
							$sql = "UPDATE order_product SET total='$new_total',discount='$new_discount',payment='$new_payment' WHERE company_id='$company_id' AND order_id='$select' AND product_id='{$imp[$n]['product_id']}'";
							mysql_query($sql,$_mysql_link_);
						}
					}
				}else{
					$sql = "INSERT INTO order_product (id,company_id,order_id,product_id,store_id,total,price,discount,payment,content)
					VALUES ('','$company_id',$select,'{$pro[$m]['product_id']}','{$pro[$m]['store_id']}','{$pro[$m]['total']}','{$pro[$m]['price']}','{$pro[$m]['discount']}','{$pro[$m]['payment']}','{$pro[$m]['content']}')";
					mysql_query($sql,$_mysql_link_);
				}

			}
			$sql = "SELECT sum(discount) AS total_dis FROM order_product WHERE company_id='$company_id' AND order_id='$select'";
			$tot = mysql_query($sql,$_mysql_link_);
			$re = mysql_fetch_object($tot);
			$total_dis = $re->total_dis;

			$new_theory = $old['theory_amount']+$array['theory_amount'];
			$new_real = $old['real_amount']+$array['real_amount'];
			$new_fee = $old['post_fee']+$array['post_fee'];
			$new_arrears = $new_theory-$new_real;
			if($new_arrears == 0){
				$new_status = "Y";
			}elseif($new_arrears > 0 && $new_arrears<$new_theory){
				$new_status = "P";
			}else{
				$new_status = "N";
			}
			$sql = "UPDATE finance_order SET discount='$total_dis',theory_amount='$new_theory',real_amount='$new_real',payment_status='$new_status',arrears='$new_arrears',post_fee='$new_fee' WHERE order_id='$select' AND company_id='$company_id'";
			mysql_query($sql,$_mysql_link_);

			$sql = "UPDATE order_info SET is_delete='Y' WHERE company_id='$company_id' AND id='$order_id[$i]'";
			mysql_query($sql,$_mysql_link_);

			//把合并的订单信息添加到order_split表中
			$oldId = $order_id[$i];
			$hsql = "INSERT INTO order_split(id,company_id,order_id,target_id,split_status)VALUES('','$company_id','$select','$oldId','Merge')";
			$hres = mysql_query($hsql,$_mysql_link_);

		}
		echo "<script>\n";
		echo "parent.$('#MessageBox').modal('hide');\n";
		echo "parent.location.replace('order/order_list_audit.php');";
		echo "</script>\n";
		echo "<center><br/><br/><br/><br/>合单完成！<br/><br/><br/><br/></center>";
	}

}

$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");


