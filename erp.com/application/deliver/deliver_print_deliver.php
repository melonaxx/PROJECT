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

$company_id 	= $_SESSION['_application_info_']['company_id'];
$staff_id 		= $_SESSION['_application_info_']['staff_id'];
$order_id 		= replace_safe(rtrim($_REQUEST['order_id'],","));
$template_id 	= intval($_POST['template_id']);

if(!empty($template_id)){
	// 查询打印模板信息
	$sql = "SELECT 
 				i.print_orient,i.page_width,i.page_height,i.page_left,i.page_top,i.border,
				p.item_id,p.item_width,p.item_height,p.item_left,p.item_top,p.item_font_size,p.item_font_bold,
				t.name,t.english,t.type
 			FROM  company_deliver_template_position AS p
 			LEFT JOIN company_deliver_template_info AS i ON i.id=p.template_id 
 			LEFT JOIN company_deliver_template_item AS t ON t.id=p.item_id 
 			WHERE p.company_id={$company_id} AND p.template_id={$template_id}";//echo $sql;

	$result 		= mysql_query($sql,$_mysql_link_);
	$tempalte_head	= array();
	$tempalte_table = array();
	$i=0;
	while($row = mysql_fetch_assoc($result)){
		$template_table['print_orient'] 	= $row['print_orient'];
		$template_table['page_width'] 		= $row['page_width'];
		$template_table['page_height']		= $row['page_height'];
		$template_table['page_left'] 		= $row['page_left'];
		$template_table['page_top'] 		= $row['page_top'];
		$template_table['border'] 			= $row['border'];
		$template_table[$i]['type']			= $row['type'];
		$template_table[$i]['id']			= $row['item_id'];
		$template_table[$i]['english']		= $row['english'];
		$template_table[$i]['name']			= $row['name'];
		$template_table[$i]['top']			= $row['item_top'];
		$template_table[$i]['left']			= $row['item_left'];
		$template_table[$i]['width']		= $row['item_width'];
		$template_table[$i]['height']		= $row['item_height'];
		$template_table[$i]['font_size']	= $row['item_font_size'];
		$template_table[$i]['font_bold']	= $row['item_font_bold'];
		$template_table[$i]['content']	= $row['name'];
		$i++;
	}

	// 保存打印结果
	if($_POST['action']=="doprint"){
		$num 		 = intval($_POST['num']);
		$template_id = intval($_POST['template_id']);
		$time 		 = date("Y-m_d H:i:s",time());

		$sql4="UPDATE order_deliver_paper 
			SET template_id='$template_id',
				action_date='$time',
				staff_id='$staff_id',
				print_status='Y',
				print_total=print_total+'$num' 
			WHERE company_id='$company_id' AND order_id in ($order_id)";

		mysql_query($sql4,$_mysql_link_);
	}	
		 	
	if($template_table){
		echo json_encode($template_table);
	}else{
		echo "empty";
	}
	exit;
}

$sql1 	 = "SELECT id,name,is_default FROM  company_deliver_template_info WHERE company_id='$company_id'";
$result1 = mysql_query($sql1,$_mysql_link_);
while($rows = mysql_fetch_object($result1)){
	$deliver_template = array();
	$deliver_template['id']  	=  $rows->id;
	$deliver_template['name']   =  $rows->name;
	// 默认模板id
	if($rows->is_default == "Y"){
		$template_default_id 	= $rows->id;
		
		$xtpl->assign("template_default_id",$template_default_id);
		$xtpl->parse("main.template_default_id"); 
	}
	$xtpl->assign("deliver_template",$deliver_template);
	$xtpl->parse("main.deliver_template");
}


$sql2 = "SELECT 
				order_info.order_date,order_info.id,
				o.discount,o.theory_amount,o.real_amount,o.post_fee,
				order_receiver.name,order_receiver.mobile,order_receiver.phone,
				order_source.bind_number,order_source.user_id
		FROM order_info
		LEFT JOIN finance_order AS o ON o.order_id=order_info.id
		LEFT JOIN order_receiver ON order_receiver.id=order_info.id
		LEFT JOIN order_source	ON order_source.id=order_info.id
		WHERE order_info.company_id='$company_id' AND order_info.id in({$order_id})";

$result2	= mysql_query($sql2, $_mysql_link_);

$i = 1;
while($order_print=mysql_fetch_object($result2)){
	$list_print_order = array();
	$product_total_price 				= 0; // 总金额
	$product_total_num 					= 0; // 总数量
	$list_print_order['printi']			= "print".$i;
	$i++;
	$orderid							= $order_print->id; //订单id
	$list_print_order['id']				= $order_print->id; 
	$list_print_order['order_date']		= $order_print->order_date; //时间
	$list_print_order['discount']		= $order_print->discount; //优惠价 
	$list_print_order['theory_amount']	= $order_print->theory_amount; //应付金额
	$list_print_order['total_order']	= $list_print_order['discount']+$list_print_order['theory_amount']; //订购金额
	$list_print_order['real_amount']	= $order_print->real_amount; //实付金额
	$list_print_order['post_fee']		= $order_print->post_fee; // 邮费
	$list_print_order['user_name']		= $order_print->name; // 客户姓名
	$list_print_order['user_mobile']	= $order_print->mobile; // 客户手机
	$list_print_order['user_phone']		= $order_print->phone; // 客户固话
	$list_print_order['bind_number']	= $order_print->bind_number; // 订单编号

	$sql4 = "SELECT shop_name FROM user_register_info WHERE id = '$order_print->user_id'";
	$result1 = mysql_query($sql4,$_mysql_link_);
	while($store = mysql_fetch_assoc($result1)){
		$list_print_order['shop_name']	= $store['shop_name'];
	}



	$sql3 = "SELECT p.product_id,p.total,p.price,p.discount,p.payment,i.name,i.number FROM order_product AS p INNER JOIN product_info AS i ON i.id = p.product_id WHERE p.company_id='$company_id' AND  p.order_id='$orderid' ";
	$result3 = mysql_query($sql3,$_mysql_link_);

	while($rows2 = mysql_fetch_object($result3)){
		$product_info = array();
		$product_info['product_id'] 		= $rows2->product_id; // 商品id
		$product_info['total'] 				= $rows2->total;	// 数量
		$product_info['price'] 				= $rows2->price;	// 单价
		$product_info['discount'] 			= $rows2->discount;	// 优惠
		$product_info['payment'] 			= $rows2->payment;	// 小计
		$product_info['product_name'] 		= $rows2->name;		// 商品名称
		$product_info['product_number'] 	= $rows2->number;	// 商品编号
		$product_info['bar_code'] 			= $rows2->bar_code;	// 商品编号
		$product_total_price 				+= $rows2->price;	// 总价
		$product_total_num 					+= $rows2->total;	// 总数量

		$xtpl->assign("product_info",$product_info);
		$xtpl->parse("main.list_print_order.product_info");
	}

	$list_print_order['product_total_price'] = $product_total_price;
	$list_print_order['product_total_num'] 	 = $product_total_num;
	$list_print_order['print_total_num']	 = $i-1;

	$xtpl->assign("list_print_order",$list_print_order);
	$xtpl->parse("main.list_print_order");
}

$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");