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

	//订单日期
	$rq = date("Y-m-d");
	$main['rq'] = $rq;
	//公司ID
	$company_id = $_SESSION['_application_info_']['company_id'];

	//客户识别
	if(!empty($_POST['recog'])){
		$recog = replace_safe($_POST['recog']);
		$sql = "SELECT i.nick_name,r.name,r.state_id,r.city_id,r.district_id,r.post_code,r.address,r.company_name,r.mobile,r.telphone FROM crm_user_related AS r LEFT JOIN crm_user_info AS i ON r.crm_user_id=i.id WHERE INSTR(r.name,'$recog') OR r.mobile='$recog'";
		$result = mysql_query($sql,$_mysql_link_);
		$arr = array();
		while($res = mysql_fetch_object($result)){
			$arr[] = array(
				'nick_name'     => $res->nick_name,
				'name'          => $res->name,
				'state_id'      => $res->state_id,
				'city_id'       => $res->city_id,
				'district_id'   => $res->district_id,
				'post_code'     => $res->post_code,
				'address'       => $res->address,
				'company_name'  => $res->company_name,
				'phone'         => $res->phone,
				'mobile'        => $res->mobile
				);
		}
		echo json_encode($arr);
		exit;
	}

	if(!empty($_POST['change'])){
		$change = replace_safe($_POST['change']);
		$sql = "SELECT i.nick_name,r.name,r.state_id,r.city_id,r.district_id,r.post_code,r.address,r.company_name,r.mobile,r.telphone FROM crm_user_related AS r LEFT JOIN crm_user_info AS i ON r.crm_user_id=i.id WHERE INSTR(r.name,'$change')";
		$result = mysql_query($sql,$_mysql_link_);

		while($res = mysql_fetch_object($result)){
			$arr = array();
			$arr = array(
				'nick_name'     => $res->nick_name,
				'name'          => $res->name,
				'state_id'      => $res->state_id,
				'city_id'       => $res->city_id,
				'district_id'   => $res->district_id,
				'post_code'     => $res->post_code,
				'address'       => $res->address,
				'company_name'  => $res->company_name,
				'phone'         => $res->phone,
				'mobile'        => $res->mobile
				);
		}
		echo json_encode($arr);
		exit;
	}


	//----- 快递公司 ----
	$ListDeliver 	= array();
	$sql = "SELECT express_id,name FROM company_express_info WHERE status = 'Y' AND company_id='$company_id'";
	$result	= mysql_query($sql, $_mysql_link_);
	while($StoreInfo = mysql_fetch_object($result))
	{
		$list_deliver	= array();
		$list_deliver['express_id']          = $StoreInfo->express_id;
		$list_deliver['name']			     = $StoreInfo->name;
		$ListDeliver[$StoreInfo->express_id] = $StoreInfo->name;
		$xtpl->assign("list_deliver", $list_deliver);
		$xtpl->parse("main.list_deliver");
	}

	//---- 到账账户 ----
	$sql = "SELECT id,name,is_default FROM finance_bank where status='Y' AND company_id='$company_id'";
	$result	= mysql_query($sql,$_mysql_link_);
	while($StoreInfo = mysql_fetch_object($result))
	{
		$list_account = array();
		$list_account['id']			= $StoreInfo->id;
		$list_account['name']	    = $StoreInfo->name;
		$list_account['is_default']	= $StoreInfo->is_default;
		$xtpl->assign("list_account", $list_account);
		$xtpl->parse("main.list_account");
	}

	//---- 销售渠道 ----
	$sql = "SELECT id,name FROM company_sales where company_id='$company_id'";
	$result	= mysql_query($sql, $_mysql_link_);
	while($StoreInfo = mysql_fetch_object($result))
	{
		$list_qudao = array();
		$list_qudao['id']	    = $StoreInfo->id;
		$list_qudao['name']	    = $StoreInfo->name;
		$xtpl->assign("list_qudao", $list_qudao);
		$xtpl->parse("main.list_qudao");
	}

		//---- 店铺名称 ----
	$sql = "SELECT user_id FROM  company_related WHERE company_id='$company_id'";
	$result	= mysql_query($sql, $_mysql_link_);
	while($StoreInfo = mysql_fetch_object($result))
	{
		$list_shop['user_id'] = $StoreInfo->user_id;
		$sql = "SELECT shop_name FROM user_register_info WHERE id='$StoreInfo->user_id'";
		$query = mysql_query($sql,$_mysql_link_);
		while($res = mysql_fetch_object($query)){
			$list_shop['shop_name'] = $res->shop_name;
			$xtpl->assign("list_shop", $list_shop);
			$xtpl->parse("main.list_shop");
		}
	}

	//---- 发货仓库 ----
	$sql = "SELECT id,name FROM store_info where store_status ='Normal' AND company_id='$company_id'";
	$result	= mysql_query($sql, $_mysql_link_);
	while($StoreInfo = mysql_fetch_object($result))
	{
		$list_store	= array();
		$list_store['id']			= $StoreInfo->id;
		$list_store['name']			= $StoreInfo->name;
		$xtpl->assign("list_store", $list_store);
		$xtpl->parse("main.list_store");
	}


	if(!empty($_GET['id'])){
		$id = $_GET['id'];
	}
	$sql2 = "SELECT product_id,store_id,total,price,discount,payment,content FROM order_product where company_id='$company_id' AND order_id='$id'";
	$result2 = mysql_query($sql2, $_mysql_link_);
	$num = 1;
	$to = array();
	while($Store = mysql_fetch_object($result2))
	{

		$sql = "SELECT product_info.name,product_info.image,
			product_detail.parts_id,product_detail.value_id_1,product_detail.value_id_2,product_detail.value_id_3,product_detail.value_id_4,product_detail.value_id_5,product_detail.content
			FROM product_info
			LEFT JOIN product_detail on product_info.id = product_detail.id
			WHERE product_info.is_delete='N' AND product_info.company_id = '$company_id' AND product_info.id ='$Store->product_id'";
		$result3 = mysql_query($sql,$_mysql_link_);
		$a = array();
		$b = "";
		while($StoreInfo = mysql_fetch_object($result3)){
			$sql = "SELECT name FROM product_parts_name WHERE id='$StoreInfo->parts_id' AND company_id='$company_id'";
			$result = mysql_query($sql,$_mysql_link_);
			$res = mysql_fetch_object($result);

			$a = array(
			'image'          => $StoreInfo->image,
			'part_name'	     => $res->name,
			'name' 		     => $StoreInfo->name,
			'value_id_1'     => $StoreInfo->value_id_1,
			'value_id_2'     => $StoreInfo->value_id_2,
			'value_id_3'     => $StoreInfo->value_id_3,
			'value_id_4'     => $StoreInfo->value_id_4,
			'value_id_5'     => $StoreInfo->value_id_5
			);
			for($j=1;$j<=5;$j++){
				$format_id = $a['value_id_'.$j];
				$sql1 = "SELECT body FROM product_format_value WHERE company_id = '$company_id' AND id = '$format_id'";
				$result1 = mysql_query($sql1,$_mysql_link_);
				while($re = mysql_fetch_object($result1)){
					$b .= $re->body.",";
				}

			}
		}

		$product = array(
			'image'      => $a['image'],
			'unit'		 => $a['unit'],
			'name'       => $a['name'],
			'format'     => rtrim($b,","),
			'num'        => $num++,
			'product_id' => $Store->product_id,
			'total'      => $Store->total,
			'price'      => number_format($Store->price,2),
			'discount'   => number_format($Store->discount,2),
			'payment'    => number_format($Store->payment,2),
			'content'    => $Store->content,
			'store_id'   => $Store->store_id
			);
		$to[] = $Store->total;
		$xtpl->assign("product", $product);
		$xtpl->parse("main.product");
	}

	$tt = 0;
	for($i=0;$i<count($to);$i++){
		$tt +=$to[$i];
	}

	$sql = "SELECT i.is_audit,i.order_date,
			s.bind_type,s.user_id,s.bind_number,s.related_order,s.order_text,s.customer_text,
			r.name,r.phone,r.mobile,r.state_id,r.city_id,r.district_id,r.post_code,r.address,r.company_name,r.need_invoice,r.tax_type,r.tax_number,r.tax_title,r.tax_bank_name,r.tax_bank_number,r.tax_text,
			o.bank_id,o.discount,o.theory_amount,o.real_amount,o.payment_status,o.arrears
			FROM order_info AS i
			LEFT JOIN order_source AS s ON i.id = s.id
			LEFT JOIN order_receiver AS r ON i.id = r.id
			LEFT JOIN finance_order AS o ON i.id = o.order_id
			WHERE i.company_id = '$company_id' AND i.id = '$id' AND i.is_delete='N'";
	$result = mysql_query($sql,$_mysql_link_);
	while($Detail = mysql_fetch_object($result)){
		$arr = array();
		$sql2 = "SELECT shop_name FROM user_register_info WHERE bind_id = '$Detail->user_id'";
		$result2 = mysql_query($sql2,$_mysql_link_);
		while($store = mysql_fetch_assoc($result2)){
			$arr['shop_name']           = $store['shop_name'];
		}

		$sql = "SELECT express_id,freight_buyer,freight_seller,number,weight FROM order_express_paper where order_id='$id' AND company_id='$company_id'";
		$result3 = mysql_query($sql, $_mysql_link_);
		while($StoreInfo = mysql_fetch_object($result3))
		{
			$arr['express_id']			= $StoreInfo->express_id;
			$arr['post_fee']			= number_format($StoreInfo->freight_seller,2);
			$arr['freight_buyer']       = $StoreInfo->freight_buyer;
			$arr['number']      		= $StoreInfo->number;
			$arr['weight']      		= $StoreInfo->weight;
		}

		$sql = "SELECT body,total,status FROM order_custom where order_id='$id' AND company_id='$company_id'";
		$result4 = mysql_query($sql, $_mysql_link_);
		while($StoreInfo = mysql_fetch_object($result4))
		{
			$arr['body']			= $StoreInfo->body;
			$arr['total']			= $StoreInfo->total;
			$arr['status']			= $StoreInfo->status;
		}
		if($arr['status'] == "Y"){
			$arr['status_name'] = "是";
		}else{
			$arr['status_name'] = "否";
		}
		$arr['user_id']                 = $Detail->user_id;
		$arr['tt']					    = $tt;   //商品总数
		$arr['discount']				= number_format($Detail->discount,2);
		// $arr['store_id']				= $Detail->store_id;
		$arr['order_date'] 				= $Detail->order_date;
		$arr['bind_number'] 			= $Detail->bind_number;
		$arr['type'] 				    = $Detail->bind_type;

		if($arr['type'] == "System"){
			$arr['bind_type'] = "系统默认";
		}elseif($arr['type'] == "360buy"){
			$arr['bind_type'] = "京东商城";
		}elseif($arr['type'] == "Taobao"){
			$arr['bind_type'] = "淘宝网";
		}

		$arr['is_audit']                = $Detail->is_audit;
		if($arr['is_audit'] =="Y"){
			$arr['audit'] = "已审核";
		}elseif($arr['is_audit'] =="N"){
			$arr['audit'] = "未审核";
		}else{
			$arr['audit'] = "异常";
		}
		$arr['bank_id'] 			    = $Detail->bank_id;
		$arr['related_order'] 			= $Detail->related_order;
		$arr['order_id']				= $id;
		$arr['need_invoice']			= $Detail->need_invoice;
		$arr['tax_type']                = $Detail->tax_type;
		$arr['name']					= $Detail->name;
		$arr['mobile']					= $Detail->mobile;
		$arr['phone']					= $Detail->phone;
		$arr['company_name']			= $Detail->company_name;
		$arr['post_code']				= $Detail->post_code;
		$arr['order_text']				= $Detail->order_text;
		$arr['customer_text']			= $Detail->customer_text;
		$arr['address']					= $Detail->address;
		$arr['state_id']                = $Detail->state_id;
		$arr['city_id']                 = $Detail->city_id;
		$arr['district_id']             = $Detail->district_id;
		$arr['theory_amount']			= number_format($Detail->theory_amount,2);
		$arr['real_amount']				= number_format($Detail->real_amount,2);
		$arr['arrears']					= number_format($Detail->arrears,2);
		$arr['payment_status']			= $Detail->payment_status;
		$arr['total_amount']            = number_format($Detail->theory_amount-$arr['freight_buyer'],2);
		$arr['tax_number']              = $Detail->tax_number;
		$arr['tax_title']              	= $Detail->tax_title;
		$arr['tax_bank_name']           = $Detail->tax_bank_name;
		$arr['tax_bank_number']         = $Detail->tax_bank_number;
		$arr['tax_text']                = $Detail->tax_text;
		$arr['zong']                    = number_format($Detail->theory_amount - $arr['freight_buyer'],2);

		if($arr['payment_status'] == "N"){
			$arr['pay_status'] = "未付款";
		}elseif($arr['payment_status'] == "Y"){
			$arr['pay_status'] = "已付款";
		}elseif($arr['payment_status'] == "P"){
			$arr['pay_status'] ="部分付款";
		}
		$xtpl->assign("arr", $arr);
		$xtpl->parse("main.arr");

	}
	if(isset($_POST['submit'])){
		//order_receiver
		$name 			   = replace_safe($_POST['name']);		        //收件人
		$mobile			   = replace_safe($_POST['mobile']);			//手机号码
		$phone			   = replace_safe($_POST['phone']);			    //固定电话
		$state_id		   = intval($_POST['state_id']);                //省份
		$city_id           = intval($_POST['city_id']);                 //城市
		$district_id       = intval($_POST['district_id']);             //县
		$address           = replace_safe($_POST['address']);           //详细地址
		$post_code		   = intval($_POST['post_code']);	            //邮政编码
		$need_invoice	   = replace_safe($_POST['need_invoice']);	    //发票收据
		$company_name	   = replace_safe($_POST['company_name']);	    //公司名称
		$tax_number	       = replace_safe($_POST['tax_number']);	    //税号
		$tax_type          = replace_safe($_POST['tax_type']);          //发票类型
		$tax_text	       = replace_safe($_POST['tax_text']);	    	//发票明细
		$tax_title	       = replace_safe($_POST['tax_title']);	    	//发票抬头
		$tax_bank_name	   = replace_safe($_POST['tax_bank_name']);	    //开户银行名称
		$tax_bank_number   = replace_safe($_POST['tax_bank_number']);	//发票银行帐号
		$tax_number   	   = replace_safe($_POST['tax_number']);	    //税号
		//order_product
		$store_id		   = intval($_POST['store_id']);	            //发货仓库
		//finance_order
		$real_amount       = $_POST['real_amount'];      				//实付金额
		$remain_amount     = $_POST['remain_amount'];           		//顾客欠款或者尾款
		$payment_status    = replace_safe($_POST['payment_status']);    //付款状态
		// order_express_paper
		$express_id	   	   = intval($_POST['express_id']);	    		//快递公司
		//order_custom
		$body              = replace_safe($_POST['body']);              //定制内容
		$total             = intval($_POST['total']);					//定制数量
		$sure              = replace_safe($_POST['status']);            //确认情况
		$need_dz           = replace_safe($_POST['need_dz']);
		$now = date('Y-m-d');

		$order_id	   	   = intval($_POST['order_id']);	     				//订单ID

		if(!isset($ListDeliver[$express_id])){
			$express_id    = 0;
		}

		if($sure == "N"){
			$status = "N";
		}elseif($sure == "Y"){
			$status = "R";
		}else{
			$status = "N";
		}

		$sql = "UPDATE order_receiver SET
		name='$name',
		phone='$phone',
		mobile='$mobile',
		state_id='$state_id',
		city_id='$city_id',
		district_id='$district_id',
		post_code='$post_code',
		address='$address',
		company_name='$company_name',
		need_invoice='$need_invoice',
		tax_type='$tax_type',
		tax_number='$tax_number',
		tax_bank_name='$tax_bank_name',
		tax_bank_number='$tax_bank_number',
		tax_title='$tax_title',
		tax_text='$tax_text'
		WHERE id='$order_id' AND company_id='$company_id'";
		mysql_query($sql,$_mysql_link_);


		$sql = "UPDATE finance_order SET
		real_amount='$real_amount',
		payment_status='$payment_status',
		arrears='$remain_amount'
		WHERE order_id='$order_id' AND company_id='$company_id'";
		mysql_query($sql,$_mysql_link_);

		if($need_dz == "Y"){
			$sql = "INSERT INTO order_custom(
			body='$body',
			total='$total',
			status='$status'
			WHERE order_id='$order_id' AND company_id='$company_id";
			mysql_query($sql,$_mysql_link_);
		}

		$sql = "UPDATE order_express_paper SET express_id = '$express_id' WHERE order_id ='$order_id' AND company_id='$company_id'";
		mysql_query($sql,$_mysql_link_);
		header("Location: deliver_express_printing.php");
		exit;
	}


$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");

