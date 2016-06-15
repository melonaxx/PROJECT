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

$company_id = $_SESSION['_application_info_']['company_id'];

	//打回审核
	if(!empty($_GET['id'])){
		$id = intval($_GET['id']);
		$sql = "UPDATE order_info SET is_audit='N',status='N' WHERE company_id='$company_id' AND id='$id'";
		mysql_query($sql,$_mysql_link_);
		$iid = mysql_affected_rows($_mysql_link_);

		$sql = "UPDATE order_delivery SET delivery_status='Untreated' WHERE company_id='$company_id' AND id='$id'";
		mysql_query($sql,$_mysql_link_);
		$did = mysql_affected_rows($_mysql_link_);
		if($iid && $did){
			echo json_encode(1);
		}else{
			echo json_encode(0);
		}
		exit;
	}

	//打回配货
	if(!empty($_GET['pei_id'])){
		$order_id = intval($_GET['pei_id']);
		$sql = "UPDATE order_info SET status='N' WHERE company_id='$company_id' AND id='$order_id'";
		mysql_query($sql,$_mysql_link_);
		$id = mysql_affected_rows($_mysql_link_);

		$sql = "UPDATE order_delivery SET delivery_status='Untreated' WHERE company_id='$company_id' AND id='$order_id'";
		mysql_query($sql,$_mysql_link_);
		$did = mysql_affected_rows($_mysql_link_);
		if($id && $did){
			echo json_encode(1);
		}else{
			echo json_encode(0);
		}
		exit;
	}

	//确认称重
	if(!empty($_GET['cheng_id'])){
		$order_id = intval($_GET['cheng_id']);
		$price    = replace_safe($_GET['price']);
		if($price){
			$sql = "UPDATE order_express_paper SET freight_seller='$price' WHERE company_id='$company_id' AND order_id='$order_id'";
			mysql_query($sql,$_mysql_link_);
			$iid = mysql_affected_rows($_mysql_link_);
		}

		$sql = "UPDATE order_info SET status='W' WHERE company_id='$company_id' AND id='$order_id'";
		mysql_query($sql,$_mysql_link_);
		$id = mysql_affected_rows($_mysql_link_);

		if($id){
			echo json_encode(1);
		}else{
			echo json_encode(0);
		}
		exit;
	}
	//称重计费
	if(!empty($_GET['order_id'])){
		$order_id 		= intval($_GET['order_id']);
		$express_id		= intval($_GET['express_id']);
		$state_id		= intval($_GET['state_id']);
		$store_id		= intval($_GET['store_id']);
		$weight			= replace_safe($_GET['weight']);
		$sql 	= "SELECT price_id FROM company_express_store WHERE express_id='$express_id' AND store_id='$store_id' AND company_id='$company_id' AND status='Y'";
		$query 	= mysql_query($sql,$_mysql_link_);
		$res 	= mysql_fetch_object($query);
		if($res){
			$price_id = $res->price_id;
			$sql 		= "SELECT area_list, first_weight_1, first_weight_2, first_weight_3, first_weight_4, first_weight_5, first_price_1, first_price_2, first_price_3, first_price_4, first_price_5, weight_increase, price_increase  FROM company_express_price WHERE id='$price_id' AND company_id='$company_id'";
			$query1 	= mysql_query($sql,$_mysql_link_);
			$re 		= mysql_fetch_object($query1);
			if($re){
				$area_list = explode(",",$re->area_list);
				if(in_array($state_id, $area_list)){
					if($re->first_weight_5 != "0.00"){
						if($weight >= $re->first_weight_5){
							$price = $re->first_price_5+($weight-$re->first_weight_5)/($re->weight_increase)*($re->price_increase);
						}elseif($weight >= $re->first_weight_4){
							$price = $re->first_price_5;
						}elseif($weight >= $re->first_weight_3){
							$price = $re->first_price_4;
						}elseif($weight >= $re->first_weight_2){
							$price = $re->first_price_3;
						}elseif($weight >= $re->first_weight_1){
							$price = $re->first_price_2;
						}else{
							$price = $re->first_price_1;
						}
					}elseif($re->first_weight_4 != "0.00"){
						if($weight >= $re->first_weight_4){
							$price = $re->first_price_4+($weight-$re->first_weight_4)/($re->weight_increase)*($re->price_increase);
						}elseif($weight >= $re->first_weight_3){
							$price = $re->first_price_4;
						}elseif($weight >= $re->first_weight_2){
							$price = $re->first_price_3;
						}elseif($weight >= $re->first_weight_1){
							$price = $re->first_price_2;
						}else{
							$price = $re->first_price_1;
						}
					}elseif($re->first_weight_3 != "0.00"){
						if($weight >= $re->first_weight_3){
							$price = $re->first_price_3+($weight-$re->first_weight_3)/($re->weight_increase)*($re->price_increase);
						}elseif($weight >= $re->first_weight_2){
							$price = $re->first_price_3;
						}elseif($weight >= $re->first_weight_1){
							$price = $re->first_price_2;
						}else{
							$price = $re->first_price_1;
						}

					}elseif($re->first_weight_2 != "0.00"){
						if($weight >= $re->first_weight_2){
							$price = $re->first_price_2+($weight-$re->first_weight_2)/($re->weight_increase)*($re->price_increase);
						}elseif($weight >= $re->first_weight_1){
							$price = $re->first_price_2;
						}else{
							$price = $re->first_price_1;
						}

					}elseif($re->first_weight_1 != "0.00"){
						if($weight >= $re->first_weight_1){
							$price = $re->first_price_1+($weight-$re->first_weight_1)/($re->weight_increase)*($re->price_increase);
						}else{
							$price = $re->first_price_1;
						}
					}
				}else{
					$sql 	= "SELECT price_id FROM company_express_store WHERE express_id='$express_id' AND company_id='$company_id' AND store_id='0' AND status='Y'";
					$query2 = mysql_query($sql,$_mysql_link_);
					$dbRow	= mysql_fetch_object($query2);
					$sql 	= "SELECT first_weight_1, first_weight_2, first_weight_3, first_weight_4, first_weight_5, first_price_1, first_price_2, first_price_3, first_price_4, first_price_5, weight_increase, price_increase FROM company_express_price WHERE express_id='$express_id' AND company_id='$company_id' AND area_list='' AND  id='$dbRow->price_id'";
					$query3 = mysql_query($sql,$_mysql_link_);
					$result = mysql_fetch_object($query3);
					if($result){
						if($re->first_weight_5 != "0.00"){
							if($weight >= $re->first_weight_5){
								$price = $re->first_price_5+($weight-$re->first_weight_5)/($re->weight_increase)*($re->price_increase);
							}elseif($weight >= $re->first_weight_4){
								$price = $first_price_5;
							}elseif($weight >= $re->first_weight_3){
								$price = $first_price_4;
							}elseif($weight >= $re->first_weight_2){
								$price = $first_price_3;
							}elseif($weight >= $re->first_weight_1){
								$price = $first_price_2;
							}else{
								$price = $first_price_1;
							}
						}elseif($re->first_weight_4 != "0.00"){
							if($weight >= $re->first_weight_4){
								$price = $re->first_price_4+($weight-$re->first_weight_4)/($re->weight_increase)*($re->price_increase);
							}elseif($weight >= $re->first_weight_3){
								$price = $first_price_4;
							}elseif($weight >= $re->first_weight_2){
								$price = $first_price_3;
							}elseif($weight >= $re->first_weight_1){
								$price = $first_price_2;
							}else{
								$price = $first_price_1;
							}
						}elseif($re->first_weight_3 != "0.00"){
							if($weight >= $re->first_weight_3){
								$price = $re->first_price_3+($weight-$re->first_weight_3)/($re->weight_increase)*($re->price_increase);
							}elseif($weight >= $re->first_weight_2){
								$price = $first_price_3;
							}elseif($weight >= $re->first_weight_1){
								$price = $first_price_2;
							}else{
								$price = $first_price_1;
							}
						}elseif($re->first_weight_2 != "0.00"){
							if($weight >= $re->first_weight_2){
								$price = $re->first_price_2+($weight-$re->first_weight_2)/($re->weight_increase)*($re->price_increase);
							}elseif($weight >= $re->first_weight_1){
								$price = $first_price_2;
							}else{
								$price = $first_price_1;
							}
						}elseif($re->first_weight_1 != "0.00"){
							if($weight >= $re->first_weight_1){
								$price = $re->first_price_1+($weight-$re->first_weight_1)/($re->weight_increase)*($re->price_increase);
							}else{
								$price = $first_price_1;
							}
						}

					}
				}
			}
		}else{
			$sql 	= "SELECT p.id, p.first_weight_1, p.first_weight_2, p.first_weight_3, p.first_weight_4, p.first_weight_5, p.first_price_1, p.first_price_2, p.first_price_3, p.first_price_4, p.first_price_5, p.weight_increase, p.price_increase FROM company_express_price AS p LEFT JOIN company_express_store AS s ON p.id=s.price_id WHERE p.express_id='$express_id' AND p.company_id='$company_id' AND p.area_list=''";
			$query3 = mysql_query($sql,$_mysql_link_);
			$re = mysql_fetch_object($query3);
			if($re){
				if($re->first_weight_5 != "0.00"){
					if($weight >= $re->first_weight_5){
						$price = $re->first_price_5+($weight-$re->first_weight_5)/($re->weight_increase)*($re->price_increase);
					}elseif($weight >= $re->first_weight_4){
						$price = $re->first_price_5;
					}elseif($weight >= $re->first_weight_3){
						$price = $re->first_price_4;
					}elseif($weight >= $re->first_weight_2){
						$price = $re->first_price_3;
					}elseif($weight >= $re->first_weight_1){
						$price = $re->first_price_2;
					}else{
						$price = $re->first_price_1;
					}

				}elseif($re->first_weight_4 != "0.00"){
					if($weight >= $re->first_weight_4){
						$price = $re->first_price_4+($weight-$re->first_weight_4)/($re->weight_increase)*($re->price_increase);
					}elseif($weight >= $re->first_weight_3){
						$price = $re->first_price_4;
					}elseif($weight >= $re->first_weight_2){
						$price = $re->first_price_3;
					}elseif($weight >= $re->first_weight_1){
						$price = $re->first_price_2;
					}else{
						$price = $re->first_price_1;
					}

				}elseif($re->first_weight_3 != "0.00"){
					if($weight >= $re->first_weight_3){
						$price = $re->first_price_3+($weight-$re->first_weight_3)/($re->weight_increase)*($re->price_increase);
					}elseif($weight >= $re->first_weight_2){
						$price = $re->first_price_3;
					}elseif($weight >= $re->first_weight_1){
						$price = $re->first_price_2;
					}else{
						$price = $re->first_price_1;
					}
				}elseif($re->first_weight_2 != "0.00"){
					if($weight >= $re->first_weight_2){
						$price = $re->first_price_2+($weight-$re->first_weight_2)/($re->weight_increase)*($re->price_increase);
					}elseif($weight >= $re->first_weight_1){
						$price = $re->first_price_2;
					}else{
						$price = $re->first_price_1;
					}
				}elseif($re->first_weight_1 != "0.00"){
					if($weight >= $re->first_weight_1){
						$price = $re->first_price_1+($weight-$re->first_weight_1)/($re->weight_increase)*($re->price_increase);
					}else{
						$price = $re->first_price_1;
					}
				}

			}
		}
		echo json_encode($price);
		exit;
	}

	//待称重总条数
	$sql = "SELECT count(*) AS total  FROM order_info AS i
			LEFT JOIN order_delivery AS d ON i.id=d.id WHERE i.company_id='$company_id' AND i.status='I' AND i.is_delete='N' AND i.is_audit='Y' AND d.delivery_status='Picking' AND i.unusual_id='0'";
	$result	= mysql_query($sql, $_mysql_link_);
	$main['total']	= mysql_result($result, 0, 'total');

	if(!empty($_POST['val'])){
		$val  = replace_safe($_POST['val']);
		$tiao = replace_safe($_POST['tiao']);
		$num = 1;
		if($tiao == "D"){
			$sql = "SELECT s.id,s.order_text,s.customer_text,r.name,r.mobile, r.state_id FROM order_source AS s
			LEFT JOIN order_info AS i ON s.id=i.id
			LEFT JOIN order_delivery AS d ON s.id=d.id
			LEFT JOIN order_receiver AS r ON s.id=r.id WHERE s.company_id='$company_id' AND s.bind_number='$val' AND i.status='I' AND i.is_delete='N' AND i.is_audit='Y' AND d.delivery_status='Picking'";

			$result = mysql_query($sql,$_mysql_link_);
			while($res = mysql_fetch_object($result)){
				$bind = array();
				$sql = "SELECT express_id,number,freight_seller,weight FROM order_express_paper WHERE order_id='$res->id' AND company_id='$company_id'";
				$query = mysql_query($sql,$_mysql_link_);
				while($q = mysql_fetch_object($query)){
					$bind['number'] 	= $q->number;
					$bind['express_id'] = $q->express_id;
					$bind['fee']        = $q->freight_seller;
					$bind['weight']     = $q->weight;

					$sql = "SELECT name FROM company_express_info WHERE status = 'Y' AND express_id='$q->express_id' AND company_id='$company_id'";
					$this = mysql_query($sql,$_mysql_link_);
					while($re = mysql_fetch_object($this)){
						$bind['express_name']   = $re->name;
					}
				}

				$bind['state_id'] 		= $res->state_id;
				$bind['bind_number']    = $val;
				$bind['id']             = $res->id; //订单ID
				$bind['order_text']     = $res->order_text;
				$bind['customer_text']  = $res->customer_text;
				$bind['name']           = $res->name;
				$bind['mobile']         = $res->mobile;

				$sql5 = "SELECT SUM(total) AS zong FROM order_product WHERE order_id='$res->id' AND company_id='$company_id'";
				$result5 = mysql_query($sql5,$_mysql_link_);
				while($ca = mysql_fetch_object($result5)){
					$bind['zong'] = $ca->zong;
				}

 				$sql = "SELECT product_id,total,store_id FROM order_product WHERE order_id='$res->id' AND company_id='$company_id'";
				$result1 = mysql_query($sql,$_mysql_link_);
				while($re = mysql_fetch_object($result1)){
					$sql3 = "SELECT product_info.name,product_info.bar_code,
						product_detail.parts_id,product_detail.value_id_1,product_detail.value_id_2,product_detail.value_id_3,product_detail.value_id_4,product_detail.value_id_5,product_detail.content
						FROM product_info
						LEFT JOIN product_detail on product_info.id = product_detail.id
						WHERE product_info.is_delete='N' AND product_info.company_id = '$company_id' AND product_info.id ='$re->product_id'";
					$result3 = mysql_query($sql3,$_mysql_link_);
					$a = array();
					$b = "";
					while($StoreInfo = mysql_fetch_object($result3)){
						$a = array(
						'value_id_1'     => $StoreInfo->value_id_1,
						'value_id_2'     => $StoreInfo->value_id_2,
						'value_id_3'     => $StoreInfo->value_id_3,
						'value_id_4'     => $StoreInfo->value_id_4,
						'value_id_5'     => $StoreInfo->value_id_5
						);
						for($j=1;$j<=5;$j++){
							$format_id = $a['value_id_'.$j];
							$sql1 = "SELECT body FROM product_format_value WHERE company_id = '$company_id' AND id = '$format_id'";
							$result4 = mysql_query($sql1,$_mysql_link_);
							while($resf = mysql_fetch_object($result4)){
								$b .= $resf->body.",";
							}
						}
						$bind['product'][] = array(
						'bar_code'   => $StoreInfo->bar_code,
						'name'       => $StoreInfo->name,
						'format'     => rtrim($b,","),
						'total'      => $re->total,
						'num'        => $num++,
						'product_id' => $re->product_id,
						'store_id'   => $re->store_id
						);
					}

				}
			}
		}elseif($tiao == "K"){
			$sql = "SELECT e.order_id,e.express_id,e.number,e.freight_seller,e.weight,s.id,s.order_text,s.bind_number,s.customer_text,r.name,r.mobile,r.state_id
			FROM order_express_paper AS e LEFT JOIN order_source AS s ON e.order_id=s.id
			LEFT JOIN order_info AS i ON e.order_id=i.id
			LEFT JOIN order_delivery AS d ON e.order_id=d.id
			LEFT JOIN order_receiver AS r ON e.order_id=r.id WHERE i.company_id='$company_id' AND e.number='$val' AND i.status='I' AND i.is_audit='Y' AND i.is_delete='N' AND d.delivery_status='Picking'";
			$result = mysql_query($sql,$_mysql_link_);
			while($res = mysql_fetch_object($result)){
				$bind = array();

				$sql = "SELECT express_id,name FROM company_express_info WHERE status = 'Y' AND express_id='$res->express_id' AND company_id='$company_id'";
				$this = mysql_query($sql,$_mysql_link_);
				$re = mysql_fetch_object($this);
				$bind['express_name']   = $re->name;
				$bind['number']         = $val;
				$bind['weight']         = $res->weight;
				$bind['bind_number']    = $res->bind_number;
				$bind['id']             = $res->order_id; //订单ID
				$bind['order_text']     = $res->order_text;
				$bind['customer_text']  = $res->customer_text;
				$bind['express_id']     = $res->express_id;
				$bind['name']           = $res->name;
				$bind['mobile']         = $res->mobile;
				$bind['state_id']  		= $res->state_id;
				$bind['fee']            = $res->freight_seller;

				$sql5 = "SELECT SUM(total) AS zong FROM order_product WHERE order_id='$res->order_id' AND company_id='$company_id'";
				$result5 = mysql_query($sql5,$_mysql_link_);
				while($ca = mysql_fetch_object($result5)){
					$bind['zong'] = $ca->zong;
				}

 				$sql = "SELECT product_id,total,store_id FROM order_product WHERE order_id='$res->order_id' AND company_id='$company_id'";
				$result1 = mysql_query($sql,$_mysql_link_);
				while($re = mysql_fetch_object($result1)){
					$sql3 = "SELECT product_info.name,product_info.bar_code,
						product_detail.parts_id,product_detail.value_id_1,product_detail.value_id_2,product_detail.value_id_3,product_detail.value_id_4,product_detail.value_id_5,product_detail.content
						FROM product_info
						LEFT JOIN product_detail on product_info.id = product_detail.id
						WHERE product_info.is_delete='N' AND product_info.company_id = '$company_id' AND product_info.id ='$re->product_id'";
					$result3 = mysql_query($sql3,$_mysql_link_);
					$a = array();
					$b = "";
					while($StoreInfo = mysql_fetch_object($result3)){
						$a = array(
						'value_id_1'     => $StoreInfo->value_id_1,
						'value_id_2'     => $StoreInfo->value_id_2,
						'value_id_3'     => $StoreInfo->value_id_3,
						'value_id_4'     => $StoreInfo->value_id_4,
						'value_id_5'     => $StoreInfo->value_id_5
						);
						for($j=1;$j<=5;$j++){
							$format_id = $a['value_id_'.$j];
							$sql1 = "SELECT body FROM product_format_value WHERE company_id = '$company_id' AND id = '$format_id'";
							$result4 = mysql_query($sql1,$_mysql_link_);
							while($resf = mysql_fetch_object($result4)){
								$b .= $resf->body.",";
							}

						}
						$bind['product'][] = array(
						'bar_code'   => $StoreInfo->bar_code,
						'name'       => $StoreInfo->name,
						'format'     => rtrim($b,","),
						'total'      => $re->total,
						'num'        => $num++,
						'product_id' => $re->product_id,
						'store_id' 	 => $re->store_id,
						);
					}

				}
			}
		}

		echo json_encode($bind);
		exit;
	}

$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");