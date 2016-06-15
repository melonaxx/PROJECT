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
$staff_id 	= $_SESSION['_application_info_']['staff_id'];

$where 		= "WHERE company_id='$company_id'";
// 订单 id 
$order_id = replace_safe(rtrim($_REQUEST['order_id'],","));
if(!empty($_POST['template_id'])){
	$template_id = intval($_POST['template_id']);
	// 查询打印模板信息

	$sql = "SELECT 
 				i.paper_width,i.paper_height,i.paper_left,i.paper_top,i.image,
				p.item_id,p.item_width,p.item_height,p.item_left,p.item_top,p.item_font_size,
				t.name,t.english
 			FROM  company_express_template_position AS p
 			LEFT JOIN company_express_template_info AS i ON i.id=p.template_id 
 			LEFT JOIN main_express_template_item AS t ON t.id=p.item_id 
 			WHERE p.company_id='$company_id' AND p.template_id='$template_id'";

	$result = mysql_query($sql,$_mysql_link_);
	$tempalte_head=array();
	$template_table = array();
	$i=0;
	while($row = mysql_fetch_assoc($result)){
		$template_table['page_width'] 		= $row['paper_width'];
		$template_table['page_height']		= $row['paper_height'];
		$template_table['page_left'] 		= $row['paper_left'];
		$template_table['page_top'] 		= $row['paper_top'];
		$template_table['image'] 			= $row['image'];
		$template_table[$i]['id']			= $row['item_id'];
		$template_table[$i]['english']		= $row['english'];
		$template_table[$i]['name']			= $row['name'];
		$template_table[$i]['top']			= $row['item_top'];
		$template_table[$i]['left']			= $row['item_left'];
		$template_table[$i]['width']		= $row['item_width'];
		$template_table[$i]['height']		= $row['item_height'];
		$template_table[$i]['font_size']	= $row['item_font_size'];
		$i++;
	}
	if($template_table){
		echo json_encode($template_table);
	}else{
		echo json_encode("empty");
	}	
		

	// 保存打印结果
	if($_POST['action'] == "doprint"){
		$num = intval($_POST['num']);
		$template_id = intval($_POST['template_id']);
		$time = date("Y-m_d H:i:s",time());
		
		$sql4="UPDATE order_express_paper 
			SET template_id='$template_id',
				action_date='$time',
				staff_id='$staff_id',
				print_status='Y',
				print_total=print_total+'$num' 
			$where AND order_id in ($order_id)";

		mysql_query($sql4,$_mysql_link_);
	}	
		 	
	exit;
}

if($_GET['express_id']){
	$express_id = intval($_GET['express_id']);
	$main['express_id'] = $express_id;
	$where .= " AND express_id='$express_id'";
}

$sql1 	 = "SELECT id,name,is_default FROM  company_express_template_info $where";
$result1 = mysql_query($sql1,$_mysql_link_);
while($rows = mysql_fetch_object($result1)){
	$express_template = array();
	$express_template['id']  		=  $rows->id;
	$express_template['name'] 		=  $rows->name;
	
	// 默认模板id
	if($rows->is_default=="Y"){ 
		$template_default_id = $rows->id;

		$xtpl->assign("template_default_id",$template_default_id);
		$xtpl->parse("main.template_default_id"); 
	}

	$xtpl->assign("express_template",$express_template);
	$xtpl->parse("main.express_template");
}

$sql2 = "SELECT 
				order_info.order_date,order_info.id,
				o.discount,o.theory_amount,o.real_amount,o.post_fee,
				r.name,r.phone,r.mobile,r.post_code,r.address,r.company_name,r.state_id,r.city_id,r.district_id,
				order_source.bind_number,order_source.order_text,order_source.customer_text
		FROM order_info
		LEFT JOIN finance_order AS o ON o.order_id=order_info.id
		LEFT JOIN order_receiver AS r ON r.id=order_info.id
		LEFT JOIN order_source	ON order_source.id=order_info.id
		WHERE  order_info.company_id='$company_id' AND order_info.id in({$order_id})";

$result2	= mysql_query($sql2, $_mysql_link_);

$i = 1;
while($order_print=mysql_fetch_object($result2)){
	$list_print_order = array();
	$product_total_price = 0; // 总金额
	$product_total_num 	= 0;  // 总数量
	$list_print_order['printi']				= "print".$i;
	$i++;
	$orderid								= $order_print->id; //订单id
	$list_print_order['id']					= $order_print->id; //订单id
	$list_print_order['post_fee']			= $order_print->post_fee; // 邮费
	$list_print_order['order_text']			= $order_print->order_text; // 订单备注
	$list_print_order['customer_text']		= $order_print->customer_text; // 买家留言
	$list_print_order['bind_number']		= $order_print->bind_number; // 订单编号
	$list_print_order['receiver_name']		= $order_print->name; // 收件人姓名
	$list_print_order['receiver_mobile']	= $order_print->mobile; // 收件人手机
	$list_print_order['receiver_post_code']	= $order_print->post_code; // 收件人邮编
	$list_print_order['receiver_address']	= $order_print->address; // 收件人详细地址
	$list_print_order['receiver_company_name']	= $order_print->company_name; // 收件人公司名称
	
		// 查询收件人地址信息	
		//   省
		$sql_state = "SELECT name FROM main_identity_card WHERE number={$order_print->state_id}";
		$res_state   	= mysql_query($sql_state, $_mysql_link_);
		$receiver_state = mysql_fetch_object($res_state);
		$list_print_order['receiver_address_p'] = $receiver_state->name;
		// 	 市	
		$sql_city = "SELECT name FROM main_identity_card WHERE number={$order_print->city_id}";
		$res_city   	= mysql_query($sql_city, $_mysql_link_);
		$receiver_city 	= mysql_fetch_object($res_city);
		$list_print_order['receiver_address_p'] .= " ".$receiver_city->name;

		//   县
		$sql_district = "SELECT name FROM main_identity_card WHERE number={$order_print->district_id}";
		$res_district  		= mysql_query($sql_district, $_mysql_link_);
		$receiver_district	= mysql_fetch_object($res_district);
		$list_print_order['receiver_address_p'] .= " ".$receiver_district->name;

	$sql3 		= "SELECT p.product_id,p.total,p.price,p.discount,p.payment,p.store_id,i.name,i.number FROM order_product AS p INNER JOIN product_info AS i ON i.id = p.product_id WHERE p.company_id={$company_id} AND p.order_id=$orderid ";
	$result3 	= mysql_query($sql3,$_mysql_link_);
	
	while($rows2 = mysql_fetch_object($result3)){
		$product_info = array();
		$product_info['product_id'] 		= $rows2->product_id; // 商品id
		$product_info['total'] 				= $rows2->total;	// 数量
		$product_info['price'] 				= $rows2->price;	// 单价
		$product_info['discount'] 			= $rows2->discount;	// 优惠
		$product_info['payment'] 			= $rows2->payment;	// 小计
		$product_info['product_name'] 		= $rows2->name;		// 商品名称
		$product_info['product_number'] 	= $rows2->number;	// 商品编号
		$product_total_price 				+= $rows2->price;	// 总价
		$product_total_num 					+= $rows2->total;	// 总数量
		
		//寄件人信息
		$sql4 = "SELECT name,contact_name,mobile,telphone,state_id,city_id,district_id FROM store_info WHERE company_id = '$company_id' AND id = '$rows2->store_id' AND store_status = 'Normal'";
		//$sql4 = "SELECT name,contact_name,mobile,telphone,state_id,city_id,district_id FROM store_info WHERE id = 10000018 ";	
		$result4 = mysql_query($sql4,$_mysql_link_);
		while($store = mysql_fetch_object($result4)){
			$list_print_order['store_name']		   		= $store->name;
			$list_print_order['sender_contact_name']	= $store->contact_name;
			$list_print_order['sender_mobile']		    = $store->mobile;
			$list_print_order['sender_telphone']		= $store->telphone;
			$list_print_order['sender_address']		    = $store->address;

				// 查询寄件人地址信息	
				//   省
				$sql_state = "SELECT name FROM main_identity_card WHERE number=$store->state_id";
				$res_state       = mysql_query($sql_state, $_mysql_link_);
				$sender_state    = mysql_fetch_object($res_state);
				$list_print_order['sender_address_p']= $sender_state->name;
					
				// 	 市	
				$sql_city = "SELECT name FROM main_identity_card WHERE number=$store->city_id";
				$res_city  	 	 = mysql_query($sql_city, $_mysql_link_);
				$sender_city 	 = mysql_fetch_object($res_city);
				$list_print_order['sender_address_p'].= " ".$sender_city->name;

				//   县
				$sql_district = "SELECT name FROM main_identity_card WHERE number=$store->district_id";
				$res_district  	 = mysql_query($sql_district, $_mysql_link_);
				$sender_district = mysql_fetch_object($res_district);
				$list_print_order['sender_address_p'].= " ".$sender_district->name;

		}
		
		$sql5="SELECT name FROM company_name WHERE id='$company_id'";
		$result5 = mysql_query($sql5,$_mysql_link_);
		while($company = mysql_fetch_assoc($result5)){
			$list_print_order['sender_company_name']		   		= $company['name'];
		}

		$xtpl->assign("product_info",$product_info);
		$xtpl->parse("main.list_print_order.product_info");
	}
	$list_print_order['product_total_price'] 	= $product_total_price;
	$list_print_order['product_total_num'] 		= $product_total_num;
	$list_print_order['print_total_num']		= $i-1;

	$xtpl->assign("list_print_order",$list_print_order);
	$xtpl->parse("main.list_print_order");
}



$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");