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

$company_id=$_SESSION['_application_info_']['company_id'];

	//---- 打回审核：----
	if(!empty($_GET['id'])){
		$order_id = replace_safe(rtrim(($_GET['id']),","));
		$sql = "UPDATE order_info SET is_audit = 'N' WHERE company_id = '$company_id' AND id IN ($order_id)";
		mysql_query($sql,$_mysql_link_);
		$id = mysql_affected_rows($_mysql_link_);
		if($id){
			echo json_encode(1);
		}else{
			echo json_encode(0);
		}
		exit;
	}

	//确认配货
	if(!empty($_GET['status'])){
		$status = rtrim(replace_safe($_GET['status']),",");
		$sql = "UPDATE order_delivery SET delivery_status='Picking' WHERE company_id='$company_id' AND id IN ($status)";
		mysql_query($sql,$_mysql_link_);
		$id = mysql_affected_rows($_mysql_link_);

		$sql = "UPDATE order_info SET status='F' WHERE company_id='$company_id' AND id IN ($status)";
		mysql_query($sql,$_mysql_link_);
		$iid = mysql_affected_rows($_mysql_link_);

		if($id && $iid){
			echo json_encode(1);
		}else{
			echo json_encode(0);
		}
		exit;
	}

	//---- 设置查询条件: ----
	$addon	= array();
	$addon[]	= "order_info.company_id='".$_SESSION['_application_info_']['company_id']."'";
	$addon[]	= "order_info.is_audit='Y'";
	$addon[]	= "order_info.is_delete='N'";
	$addon[]	= "order_info.status='N'";
	$addon[]	= "order_info.unusual_id='0'";


	if(!empty($_GET['find']))
	{
		$find	= replace_safe($_REQUEST['find'], 20);
		if(!empty($find))
		{
			$addon[]		= "( INSTR(order_source.bind_number,'$find') OR INSTR(order_receiver.name,'$find') )";
			$main['find']	= $find;
			$page_param		= array();
			$page_param['find']		= replace_safe($_REQUEST['find'], 20, false, false);
		}
	}

	$where  = "";
	if(count($addon) > 0)
	{
		$where	= "WHERE ".implode(" AND ", $addon);
	}

	$sql = "SELECT COUNT(*) as total FROM order_info
		LEFT JOIN order_source ON order_info.id = order_source.id
		LEFT JOIN order_receiver ON order_info.id = order_receiver.id ".$where;
	$result	= mysql_query($sql, $_mysql_link_);
	$main['total']		= mysql_result($result, 0, 'total');

	//---- 处理分页 ----
	if(!is_array($page_param))
	{
		$page_param			= array();
	}
	$main['page_info']	= erp_page_info($main['total'], $page, $page_param);
	$limit	= ($page - 1) * $_SESSION["_application_info_"]["page_size"].", ".$_SESSION["_application_info_"]["page_size"];

	//---- 数量大于0 ----
	if($main['total'] > 0)
	{
		$sql = "SELECT
		order_info.id,
		order_source.order_text,order_source.customer_text,order_source.user_id,order_source.bind_number,
		order_receiver.name,order_receiver.mobile,order_receiver.address
		FROM  order_info
		LEFT JOIN order_source ON order_info.id = order_source.id
		LEFT JOIN order_receiver ON order_info.id = order_receiver.id ".$where." ORDER BY order_info.id DESC LIMIT ".$limit;
		$result	= mysql_query($sql, $_mysql_link_);
		while($StoreInfo = mysql_fetch_object($result))
		{
			$list_audit	= array();
			$sql     = "SELECT store_id,sum(total) AS total FROM order_product WHERE company_id='$company_id' AND order_id='$StoreInfo->id'";

			$result1 = mysql_query($sql,$_mysql_link_);
			while($res = mysql_fetch_object($result1)){
				//仓库
				$sql = "SELECT name FROM store_info WHERE id = '$res->store_id' AND company_id = '$company_id'";
				// var_dump($sql);
				$result5 = mysql_query($sql,$_mysql_link_);
				while($store = mysql_fetch_assoc($result5)){
					$list_audit['store_id']		    = $store['name'];
				}
				$list_audit['total']				= $res->total;
			}


			$sql     = "SELECT express_id,number,print_status FROM order_express_paper WHERE company_id='$company_id' AND order_id='$StoreInfo->id'";
			$result2 = mysql_query($sql,$_mysql_link_);
			while($res = mysql_fetch_object($result2)){
				//快递
				$sql     = "SELECT name FROM company_express_info WHERE express_id = '$res->express_id'  AND company_id = '$company_id'";
				$result3 = mysql_query($sql,$_mysql_link_);
				while($store = mysql_fetch_assoc($result3)){
					$list_audit['express_name']	= $store['name'];
				}
				$list_audit['number'] = $res->number;
				$list_audit['express_id'] = $res->express_id;
				if($res->print_status == 'Y')
				{
					$list_audit['mark_express'] = "快 ";
				}else{
					$list_audit['mark_express'] = "";
				}
			}


			$sql     = "SELECT print_status FROM order_deliver_paper WHERE company_id='$company_id' AND order_id='$StoreInfo->id'";
			$result2 = mysql_query($sql,$_mysql_link_);
			while($res = mysql_fetch_object($result2)){
				if($res->print_status == 'Y')
				{
					$list_audit['mark_deliver'] = "发 ";
				}else{
					$list_audit['mark_deliver'] = "";
				}
			}

			$sql     = "SELECT print_status FROM order_list_paper WHERE company_id='$company_id' AND order_id='$StoreInfo->id'";
			$result2 = mysql_query($sql,$_mysql_link_);
			while($res = mysql_fetch_object($result2)){
				if($res->print_status == 'Y')
				{
					$list_audit['mark_order'] = "配 ";
				}else{
					$list_audit['mark_order'] = "";
				}
			}


			$sql4 = "SELECT shop_name FROM user_register_info WHERE id = '$StoreInfo->user_id'";
			$result1 = mysql_query($sql4,$_mysql_link_);
			while($store = mysql_fetch_assoc($result1)){
				$list_audit['shop_name']		    = $store['shop_name'];
			}

			$list_audit['id']			        = $StoreInfo->id;
			$list_audit['bind_number']			= $StoreInfo->bind_number;
			$list_audit['order_text']			= $StoreInfo->order_text;
			$list_audit['customer_text']		= $StoreInfo->customer_text;
			$list_audit['name']					= $StoreInfo->name;
			$list_audit['mobile']				= $StoreInfo->mobile;
			$list_audit['address']				= $StoreInfo->address;
			$xtpl->assign("list_audit", $list_audit);
			$xtpl->parse("main.list_audit");
		}

			
			// $sql5 = "SELECT shop_name FROM user_register_info WHERE id = '$StoreInfo->user_id'";
			// $result1 = mysql_query($sql4,$_mysql_link_);
			// while($store = mysql_fetch_assoc($result1)){
			// 	$list_audit['shop_name']		    = $store['shop_name'];
			// }

			// $list_audit['id']			        = $StoreInfo->id;
			// $list_audit['bind_number']			= $StoreInfo->bind_number;
			// $list_audit['order_text']			= $StoreInfo->order_text;
			// $list_audit['customer_text']		= $StoreInfo->customer_text;
			// $list_audit['name']					= $StoreInfo->name;
			// $list_audit['mobile']				= $StoreInfo->mobile;
			// $list_audit['address']				= $StoreInfo->address;
			// $xtpl->assign("list_audit", $list_audit);
			// $xtpl->parse("main.list_audit");


	}

	// 打印=====================================================================

		// 订单 id

	// $print = $_GET['print'];
	// if(!empty($print)){

	// 	$order_id = rtrim($_GET['order_id'],",");
	// 	$sql = "SELECT
	// 					order_info.order_date,order_info.id,
	// 					o.discount,o.theory_amount,o.real_amount,o.post_fee,
	// 					order_receiver.name,
	// 					order_source.bind_number
	// 			FROM order_info
	// 			LEFT JOIN finance_order AS o ON o.order_id=order_info.id
	// 			LEFT JOIN order_receiver ON order_receiver.id=order_info.id
	// 			LEFT JOIN order_source	ON order_source.id=order_info.id
	// 			WHERE order_info.id in({$order_id}) AND order_info.company_id={$company_id}";

	// 	$result	= mysql_query($sql, $_mysql_link_);

	// 	$i = 1;
	// 	while($order_print=mysql_fetch_object($result)){
	// 		$list_print_order = array();

	// 		$product_total_price = 0; // 总金额
	// 		$product_total_num 	= 0;  // 总数量
	// 		$list_print_order['printi']				= "print".$i;
	// 		$i++;
	// 		$orderid							= $order_print->id; //订单id
	// 		$list_print_order['id']				= $order_print->id; //订单id
	// 		$list_print_order['order_date']		= $order_print->order_date; //时间
	// 		$list_print_order['discount']		= $order_print->discount; //优惠价
	// 		$list_print_order['theory_amount']	= $order_print->theory_amount; //应付金额
	// 		$list_print_order['total_order']	= $list_print_order['discount']+$list_print_order['theory_amount']; //订购金额

	// 		$list_print_order['real_amount']	= $order_print->real_amount; //实付金额
	// 		$list_print_order['post_fee']		= $order_print->post_fee; // 邮费
	// 		$list_print_order['user_name']		= $order_print->name; // 客户姓名
	// 		$list_print_order['bind_number']	= $order_print->bind_number; // 订单编号

	// 		$sql = "SELECT p.product_id,p.total,p.price,p.discount,p.payment,i.name,i.number FROM order_product AS p INNER JOIN product_info AS i ON i.id = p.product_id WHERE p.order_id=$orderid AND p.company_id={$company_id}";
	// 		$res = mysql_query($sql,$_mysql_link_);
	// 		while($rows = mysql_fetch_object($res)){
	// 			$product_info = array();
	// 			$product_info['product_id'] = $rows->product_id; // 商品id
	// 			$product_info['total'] 		= $rows->total;		// 数量
	// 			$product_info['price'] 		= $rows->price;		// 单价
	// 			$product_info['discount'] 	= $rows->discount;	// 优惠
	// 			$product_info['payment'] 	= $rows->payment;	// 小计
	// 			$product_info['name'] 		= $rows->name;		// 商品名称
	// 			$product_info['number'] 	= $rows->number;	// 商品编号
	// 			$product_total_price += $rows->price;			// 总价
	// 			$product_total_num += $rows->total;				// 总数量


	// 			$xtpl->assign("product_info",$product_info);
	// 			$xtpl->parse("main.list_print_order.product_info");

	// 		}
	// 		$list_print_order['product_total_price'] = $product_total_price;
	// 		$list_print_order['product_total_num'] = $product_total_num;
	// 		$list_print_order['print_total_num']			= $i-1;
	// 		$xtpl->assign("list_print_order",$list_print_order);
	// 		$xtpl->parse("main.list_print_order");
	// 	}
	// }
	// =======================================================================



$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");