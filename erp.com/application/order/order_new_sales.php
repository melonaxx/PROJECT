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
	$staff_id   = $_SESSION['_application_info_']['staff_id'];

	//快递公司
	if(!empty($_POST['sdata'])){
		$sname = $_POST['sdata'];
		$psql = "SELECT name,id FROM main_express_info WHERE name LIKE '%$sname%'";
		$pres = mysql_query($psql,$_mysql_link_);
		$snum = array();
		while ($pdata = mysql_fetch_object($pres)) {
			$snum[] = array(
				'name'=>$pdata->name,
				'id'=>$pdata->id
			);
		}
		echo json_encode($snum);
		exit;
	}
	//---- 售后分类 ----
	$sql = "SELECT id,name FROM after_sale_topic WHERE company_id='$company_id' AND is_delete='N'";
	$result = mysql_query($sql,$_mysql_link_);
	while($res = mysql_fetch_object($result)){
		$arr = array();
		$arr['id']   = $res->id;
		$arr['name'] = $res->name;

		$xtpl->assign("arr", $arr);
		$xtpl->parse("main.arr");
	}

	//----- 快递公司 ----
	// $sql = "SELECT express_id,name FROM company_express_info WHERE status = 'Y' AND company_id='$company_id'";
	// $result	= mysql_query($sql, $_mysql_link_);
	// while($StoreInfo = mysql_fetch_object($result))
	// {
	// 	$list_deliver	= array();
	// 	$list_deliver['express_id']     = $StoreInfo->express_id;
	// 	$list_deliver['name']			= $StoreInfo->name;
	// 	$xtpl->assign("list_deliver", $list_deliver);
	// 	$xtpl->parse("main.list_deliver");
	// }
	//---- 获取订单ID ----
	if(!empty($_GET['id'])){
		$id = intval($_GET['id']);
		//---- 实付金额 ----
		$rsql = "SELECT o.real_amount AS zong_pay,o.bank_id
				FROM order_product AS p
				LEFT JOIN finance_order AS o ON p.order_id=o.order_id
				WHERE p.company_id='$company_id' AND p.order_id=$id";
		$rres = mysql_query($rsql,$_mysql_link_);
		$rdata = mysql_fetch_object($rres);
		$main['zong_pay'] = $rdata->zong_pay;

		//---- 到账账户 ----
		$bankId = $rdata->bank_id;
		$bSql = "SELECT name
				FROM finance_bank
				WHERE company_id=$company_id AND status='Y' AND id=$bankId";
		$bRes = mysql_query($bSql,$_mysql_link_);
		$bData = mysql_fetch_object($bRes);
		$main['bankName'] = $bData->name;
		$main['bankId'] = $bankId;

		//---- 商品的总数量 ----
		$sql = "SELECT sum(order_product.payment) AS zong_pay,order_product.total,sum(order_product.total) AS zong
			FROM order_product
			LEFT JOIN order_source ON order_product.order_id = order_source.id
			WHERE order_product.company_id='$company_id' AND order_product.order_id = '$id'";
		$query = mysql_query($sql,$_mysql_link_);
		$this = mysql_fetch_object($query);
		$main['zong']     = $this->zong; //总的数量
		// $main['zong_pay'] = $this->zong_pay;  //总的钱数
		$ruku = array();
		$sql = "SELECT total_finish,product_id FROM after_sale_input WHERE company_id='$company_id' AND order_id='$id'";
		$query2 = mysql_query($sql,$_mysql_link_);
		while($res = mysql_fetch_object($query2)){
			$ruku[] = array(
				'total'      =>$res->total_finish,
				'product_id' => $res->product_id
			);
		}
		$sql = "SELECT order_product.product_id,order_product.payment,order_product.total,order_product.price,order_source.bind_number FROM order_product LEFT JOIN order_source ON order_product.order_id = order_source.id WHERE order_product.company_id='$company_id' AND order_product.order_id = '$id'";
		$result = mysql_query($sql,$_mysql_link_);

		//序号
		$numbers = 0;
		while($res = mysql_fetch_object($result)){

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
				for($j=1;$j<=5;$j++){
					$format_id = $arr['value_id_'.$j];
					$sql1 = "SELECT body FROM product_format_value WHERE company_id = '$company_id' AND id = '$format_id'";
					$result1 = mysql_query($sql1,$_mysql_link_);
					while($re = mysql_fetch_object($result1)){
						$format .= $re->body.",";
					}

				}
			}
			$ru = 0;
			if(count($ruku) > 1){
				for($i=0;$i<count($ruku);$i++){
					if($res->product_id == $ruku[$i]['product_id']){
						$ru += $ruku[$i]['total'];
					}
				}
			}else{
				$ru = 0;
			}
			// 序号
			$numbers++;
			$product = array(
				'total'      => $res->total,
				'ru'         => $res->total-$ru,
				'name'       => $arr['name'],
				'format'     => rtrim($format,","),
				'payment'    => $res->payment,
				'bind_number'=> $res->bind_number,
				'order_id'   => $id,
				'price'      => $res->price,
				'product_id' => $res->product_id,
				'numbers' 	 => $numbers
				);
			$xtpl->assign("product", $product);
			$xtpl->parse("main.product");
		}

	}
	//---- 新建售后单的提交 ----
	if(isset($_POST['send'])){
		$order_id        = intval($_GET['id']);
		$sale_type       = replace_safe($_POST['sale_type']);     //售后类型
		$bind_number     = intval($_POST['bind_number']);		  //订单编号
		$sale_id         = replace_safe($_POST['sale_id']);       //售后分类
		$back_money      = replace_safe($_POST['back_money']);    //退回金额
		$sale_text       = replace_safe($_POST['sale_text']);     //售后描述
		$express_id    	 = intval($_POST['express_id']);  		  //退回快递
		$back_number     = intval($_POST['back_number']);         //退回单号
		$back_money      = replace_safe($_POST['back_money']);    //售后分类

		$fee_type        = replace_safe($_POST['fee_type']);      //运费承担
		$post_fee        = replace_safe($_POST['post_fee']);      //运费金额

		$product_id      = $_POST['product_id'];				   //商品ID
		$back_num        = $_POST['back_num'];			           //退回数量
		$price           = $_POST['price'];			               //商品价格
		$action_date     = date("Y-m-d H:i:s");
		$ip              = replace_safe($_SERVER['REMOTE_ADDR']);   //IP地址
		//添加售后单信息
		$sql = "INSERT INTO  after_sale_info(
			id,
			company_id,
			order_id,
			service_type,
			topic_id,
			payment,
			content,
			action_date,
			express_id,
			number,
			freight,
			staff_id,
			fee
			)VALUES(
			'',
			'$company_id',
			'$order_id',
			'$sale_type',
			'$sale_id',
			'$back_money',
			'$sale_text',
			'$action_date',
			'$express_id',
			'$back_number',
			'$fee_type',
			'$staff_id',
			'$post_fee'
			)";
		mysql_query($sql,$_mysql_link_);
		$iid = mysql_affected_rows($_mysql_link_);

		if($iid){
			$mid = mysql_insert_id($_mysql_link_);
			// if($sale_type == "Return"){
				for($i=0;$i<count($back_num);$i++){
					$product_id[$i] = intval($product_id[$i]);
					$back_num[$i]   = intval($back_num[$i]);
					$price[$i]      = replace_safe($price[$i]);
					$sql = "INSERT into after_sale_product(
					id,company_id,after_sale_id,product_id,total,price,staff_id,action_date,ip
					)VALUES('','$company_id','$mid','$product_id[$i]','$back_num[$i]','$price[$i]','$staff_id','$action_date','$ip')";
					mysql_query($sql,$_mysql_link_);
				}
			// }
			$sql = "SELECT state_id,city_id,district_id,address,name,mobile FROM order_receiver WHERE company_id='$company_id' AND id='$order_id'";
			$result = mysql_query($sql,$_mysql_link_);
			$res = mysql_fetch_object($result);
			$state_id     = intval($res->state_id);
			$city_id      = intval($res->city_id);
			$district_id  = intval($res->district_id);
			$address      = replace_safe($res->address);
			$name         = replace_safe($res->name);
			$phone        = intval($res->mobile);

			$sql = "INSERT INTO  after_sale_address(
			id,
			company_id,
			after_sale_id,
			action_date,
			state_id,
			city_id,
			district_id,
			address,
			name,
			phone
			)VALUES(
			'',
			'$company_id',
			'$mid',
			'$action_date',
			'$state_id',
			'$city_id',
			'$district_id',
			'$address',
			'$name',
			'$phone'
			)";
			mysql_query($sql,$_mysql_link_);
			$sql = "INSERT INTO  after_sale_logs(
			id,
			company_id,
			after_sale_id,
			staff_id,
			action_date,
			ip
			)VALUES(
			'',
			'$company_id',
			'$mid',
			'$staff_id',
			'$action_date',
			'$ip'
			)";
			mysql_query($sql,$_mysql_link_);

			$sql = "UPDATE order_info SET customer_time = customer_time+1 WHERE id='$order_id' AND company_id='$company_id'";
			mysql_query($sql,$_mysql_link_);
		}

			echo "<script>\n";
			echo "parent.$('#MessageBox').modal('hide');\n";
			echo "parent.location.replace('/order/order_sale_service.php');";
			echo "</script>\n";
			echo "<center><br/><br/><br/><br/>修改完成！<br/><br/><br/><br/></center>";
	}




$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");


