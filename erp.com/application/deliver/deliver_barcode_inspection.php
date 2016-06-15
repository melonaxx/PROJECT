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

	$sql = "SELECT count(*) AS total  FROM order_info AS i
			LEFT JOIN order_delivery AS d ON i.id=d.id WHERE i.company_id='$company_id' AND i.status='F' AND i.is_delete='N' AND i.is_audit='Y' AND d.delivery_status='Picking' AND i.unusual_id='0'";
	$result	= mysql_query($sql, $_mysql_link_);
	$main['total']	= mysql_result($result, 0, 'total');

	//配货员
	$sql 	= "SELECT id FROM company_staff_group WHERE company_id='$company_id' AND name='配货员'";
	$res 	= mysql_query($sql,$_mysql_link_);
	$resf  	= mysql_fetch_object($res);
	if($resf){
		$sql 	= "SELECT id,nick FROM company_staff_info WHERE company_id='$company_id' AND group_id='$resf->id' AND is_valid='Y' AND is_admin='N'";
		$result = mysql_query($sql,$_mysql_link_);
		while($res = mysql_fetch_object($result)){
			$arr = array();
			$arr['number']    = $res->id;
			$arr['nick']      = $res->nick;

			$xtpl->assign("arr", $arr);
			$xtpl->parse("main.arr");
		}
	}

	//打单配货
	if(!empty($_GET['order_id'])){
		// var_dump($_GET['dis_name']);exit;
		$order_id = intval($_GET['order_id']);
		$dis_name = replace_safe($_GET['dis_name']);
		$sql = "SELECT unusual_id FROM order_info WHERE company_id='$company_id' AND id='$order_id'";
		$result = mysql_query($sql,$_mysql_link_);
		$res = mysql_fetch_object($result);
		$unusual_id = $res->unusual_id;
		if($unusual_id == '0'){
			$sql = "UPDATE order_info SET status='I' WHERE company_id='$company_id' AND id='$order_id'";
			mysql_query($sql,$_mysql_link_);
			$id = mysql_affected_rows($_mysql_link_);
			if($id){
				echo json_encode(1);
			}else{
				echo json_encode(0);
			}
			// $sql = "UPDATE order_operation SET distribution_name='$dis_name' WHERE company_id='$company_id' AND id='$order_id'";
			// mysql_query($sql,$_mysql_link_);
			exit;
		}else{
			$sql = "SELECT name FROM company_unusual WHERE id='$unusual_id' AND company_id='$company_id'";
			$query = mysql_query($sql,$_mysql_link_);
			$re = mysql_fetch_object($query);
			echo json_encode($re->name);
			exit;
		}
	}
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

	//子商品
	if(!empty($_GET['product_id'])){
		$product_id = intval($_GET['product_id']);
		$number        = intval($_GET['num']);
		$sql    = "SELECT sub_id,total FROM product_combination WHERE product_id = '$product_id'";
		$result = mysql_query($sql,$_mysql_link_);
		while($res = mysql_fetch_object($result)){
			$sql = "SELECT product_info.name,product_info.bar_code,
				product_detail.parts_id,product_detail.value_id_1,product_detail.value_id_2,product_detail.value_id_3,product_detail.value_id_4,product_detail.value_id_5,product_detail.content
				FROM product_info
				LEFT JOIN product_detail on product_info.id = product_detail.id
				WHERE product_info.is_delete='N' AND product_info.company_id = '$company_id' AND product_info.id ='$res->sub_id'";
			$result3 = mysql_query($sql,$_mysql_link_);
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
					'product_id' => $product_id,
					'bar_code'   => $StoreInfo->bar_code,
					'name'       => $StoreInfo->name,
					'format'     => rtrim($b,","),
					'total'      => $res->total*$number,
				);
			}
		}
		echo json_encode($bind);
		exit;
	}

	if(!empty($_POST['val'])){
		$val  = replace_safe($_POST['val']);
		$tiao = replace_safe($_POST['tiao']);
		$num = 1;
		if($tiao == "D"){
			$sql = "SELECT s.id,s.order_text,s.customer_text,r.name,r.mobile FROM order_source AS s
			LEFT JOIN order_info AS i ON s.id=i.id
			LEFT JOIN order_delivery AS d ON s.id=d.id
			LEFT JOIN order_receiver AS r ON s.id=r.id WHERE s.company_id='$company_id' AND s.bind_number='$val' AND i.status='F' AND i.is_delete='N' AND i.is_audit='Y' AND d.delivery_status='Picking'";
			$result = mysql_query($sql,$_mysql_link_);
			while($res = mysql_fetch_object($result)){
				$bind = array();

				$sql = "SELECT express_id,number FROM order_express_paper WHERE order_id='$res->id' AND company_id='$company_id'";
				$query = mysql_query($sql,$_mysql_link_);
				while($a = mysql_fetch_object($query)){
					$bind['number'] = $a->number;

					$sql = "SELECT name FROM company_express_info WHERE status = 'Y' AND express_id='$a->express_id' AND company_id='$company_id'";
					$this = mysql_query($sql,$_mysql_link_);
					while($re = mysql_fetch_object($this)){
						$bind['express_name']   = $re->name;
					}
				}
				$bind['bind_number']    = $val;
				$bind['id']             = $res->id; //订单ID
				$bind['order_text']     = $res->order_text;
				$bind['customer_text']  = $res->customer_text;
				$bind['express_id']     = $res->express_id;
				$bind['name']           = $res->name;
				$bind['mobile']         = $res->mobile;

				$sql5 = "SELECT SUM(total) AS zong FROM order_product WHERE order_id='$res->id' AND company_id='$company_id'";
				$result5 = mysql_query($sql5,$_mysql_link_);
				while($ca = mysql_fetch_object($result5)){
					$bind['zong'] = $ca->zong;
				}

 				$sql = "SELECT product_id,total FROM order_product WHERE order_id='$res->id' AND company_id='$company_id'";
				$result1 = mysql_query($sql,$_mysql_link_);
				while($re = mysql_fetch_object($result1)){
					$sql = "SELECT product_info.name,product_info.bar_code,
						product_detail.parts_id,product_detail.value_id_1,product_detail.value_id_2,product_detail.value_id_3,product_detail.value_id_4,product_detail.value_id_5,product_detail.content
						FROM product_info
						LEFT JOIN product_detail on product_info.id = product_detail.id
						WHERE product_info.is_delete='N' AND product_info.company_id = '$company_id' AND product_info.id ='$re->product_id'";
					$result3 = mysql_query($sql,$_mysql_link_);
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
							'product_id' => $StoreInfo->product_id,
							'bar_code'   => $StoreInfo->bar_code,
							'name'       => $StoreInfo->name,
							'format'     => rtrim($b,","),
							'total'      => $re->total,
							'num'        => $num++,
							'product_id' => $re->product_id,
						);
					}

				}
			}
		}elseif($tiao == "K"){

			$sql = "SELECT e.order_id,e.express_id,s.id,s.order_text,s.customer_text,s.bind_number,r.name,r.mobile
			FROM order_express_paper AS e LEFT JOIN order_source AS s ON e.order_id=s.id
			LEFT JOIN order_info AS i ON e.order_id=i.id
			LEFT JOIN order_delivery AS d ON e.order_id=d.id
			LEFT JOIN order_receiver AS r ON e.order_id=r.id WHERE i.company_id='$company_id' AND e.number='$val' AND i.status='F' AND i.is_audit='Y' AND i.is_delete='N' AND d.delivery_status='Picking'";
			$result = mysql_query($sql,$_mysql_link_);
			while($res = mysql_fetch_object($result)){
				$bind = array();

				$sql = "SELECT express_id,name FROM company_express_info WHERE status = 'Y' AND express_id='$res->express_id' AND company_id='$company_id'";
				$this = mysql_query($sql,$_mysql_link_);
				$re = mysql_fetch_object($this);
				$bind['express_name']   = $re->name;
				$bind['number']         = $val;
				$bind['bind_number']    = $res->bind_number;
				$bind['id']             = $res->order_id; //订单ID
				$bind['order_text']     = $res->order_text;
				$bind['customer_text']  = $res->customer_text;
				$bind['express_id']     = $res->express_id;
				$bind['name']           = $res->name;
				$bind['mobile']         = $res->mobile;

				$sql5 = "SELECT SUM(total) AS zong FROM order_product WHERE order_id='$res->order_id' AND company_id='$company_id'";
				$result5 = mysql_query($sql5,$_mysql_link_);
				while($ca = mysql_fetch_object($result5)){
					$bind['zong'] = $ca->zong;
				}

 				$sql = "SELECT product_id,total FROM order_product WHERE order_id='$res->order_id' AND company_id='$company_id'";
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