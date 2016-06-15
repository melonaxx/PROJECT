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
	//订单ID
	$orderId = replace_safe($_GET['id']);

	//----- 快递公司 ----
	$sql = "SELECT express_id,name FROM company_express_info WHERE status = 'Y' AND company_id='$company_id'";
	$result	= mysql_query($sql, $_mysql_link_);
	while($StoreInfo = mysql_fetch_object($result))
	{
		$list_deliver	= array();
		$list_deliver['name']			= $StoreInfo->name;
		$list_deliver['express_id']     = $StoreInfo->express_id;
		$xtpl->assign("list_deliver", $list_deliver);
		$xtpl->parse("main.list_deliver");

	}

	//---- 到账账户 ----
	$sql = "SELECT b.name,b.id
			FROM finance_bank AS b
			LEFT JOIN finance_order AS f ON b.id=f.bank_id
			WHERE f.order_id=$orderId";
	// $sql = "SELECT id,name,is_default FROM finance_bank where status='Y' AND company_id='$company_id' AND ";
	$result	= mysql_query($sql,$_mysql_link_);
	while($StoreInfo = mysql_fetch_object($result))
	{
		$list_account = array();
		$list_account['id']			= $StoreInfo->id;
		$list_account['name']	    = $StoreInfo->name;
		// $list_account['is_default']	= $StoreInfo->is_default;
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
		$nsql = "SELECT is_delete FROM order_info WHERE id='$id' AND company_id='$company_id'";
		$nres = mysql_query($nsql,$_mysql_link_);
		$ndata = mysql_fetch_assoc($nres);
		$isDelete = $ndata['is_delete'];
	}
	$sql2 = "SELECT product_id,store_id,total,price,discount,payment,content FROM order_product where company_id='$company_id' AND order_id='$id'";
	$result2 = mysql_query($sql2, $_mysql_link_);
	$num = 1;
	while($Store = mysql_fetch_object($result2))
	{
		//判断是否是已关闭的订单
		if($isDelete == 'Y'){
			$sql = "SELECT product_info.name,product_info.image,
			product_detail.parts_id,product_detail.value_id_1,product_detail.value_id_2,product_detail.value_id_3,product_detail.value_id_4,product_detail.value_id_5,product_detail.content
			FROM product_info
			LEFT JOIN product_detail on product_info.id = product_detail.id
			WHERE product_info.is_delete='Y' AND product_info.company_id = '$company_id' AND product_info.id ='$Store->product_id'";
		}elseif($isDelete == 'N'){
			$sql = "SELECT product_info.name,product_info.image,
			product_detail.parts_id,product_detail.value_id_1,product_detail.value_id_2,product_detail.value_id_3,product_detail.value_id_4,product_detail.value_id_5,product_detail.content
			FROM product_info
			LEFT JOIN product_detail on product_info.id = product_detail.id
			WHERE product_info.is_delete='N' AND product_info.company_id = '$company_id' AND product_info.id ='$Store->product_id'";
		}

		$result3 = mysql_query($sql,$_mysql_link_);
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
		$sql = "SELECT name FROM store_info where store_status ='Normal' AND company_id='$company_id' AND id='$Store->store_id'";
		$sto = mysql_query($sql,$_mysql_link_);
		while($res = mysql_fetch_object($sto)){
			$main['store'] = $res->name;
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


		$xtpl->assign("product", $product);
		$xtpl->parse("main.product");
	}

	//判断是否是已关闭订单
	if($isDelete == 'Y'){
		$sql = "SELECT i.is_audit,i.order_date,
				s.bind_type,s.user_id,s.bind_number,s.related_order,s.order_text,s.customer_text,
				r.name,r.phone,r.mobile,r.state_id,r.city_id,r.district_id,r.post_code,r.address,r.company_name,r.need_invoice,r.tax_number,r.tax_title,r.tax_bank_name,r.tax_bank_number,r.tax_text,
				o.bank_id,o.discount,o.theory_amount,o.real_amount,o.payment_status,o.arrears
				FROM order_info AS i
				LEFT JOIN order_source AS s ON i.id = s.id
				LEFT JOIN order_receiver AS r ON i.id = r.id
				LEFT JOIN finance_order AS o ON i.id = o.order_id
				WHERE i.company_id = '$company_id' AND i.id = '$id' AND i.is_delete='Y'";
	}elseif($isDelete == 'N'){
		$sql = "SELECT i.is_audit,i.order_date,
				s.bind_type,s.user_id,s.bind_number,s.related_order,s.order_text,s.customer_text,
				r.name,r.phone,r.mobile,r.state_id,r.city_id,r.district_id,r.post_code,r.address,r.company_name,r.need_invoice,r.tax_number,r.tax_title,r.tax_bank_name,r.tax_bank_number,r.tax_text,
				o.bank_id,o.discount,o.theory_amount,o.real_amount,o.payment_status,o.arrears
				FROM order_info AS i
				LEFT JOIN order_source AS s ON i.id = s.id
				LEFT JOIN order_receiver AS r ON i.id = r.id
				LEFT JOIN finance_order AS o ON i.id = o.order_id
				WHERE i.company_id = '$company_id' AND i.id = '$id' AND i.is_delete='N'";
	}

	$result = mysql_query($sql,$_mysql_link_);
	while($Detail = mysql_fetch_object($result)){
		$arr = array();

		$sql = "SELECT SUM(total) AS total FROM order_product WHERE order_id='$id' AND company_id='$company_id'";
		$this = mysql_query($sql,$_mysql_link_);
		while($res = mysql_fetch_object($this)){
			$arr['pro_total'] = $res->total;
		}

		$sql = "SELECT name FROM finance_bank where status='Y' AND company_id='$company_id' AND id='$Detail->bank_id'";
		$result1 = mysql_query($sql, $_mysql_link_);
		while($StoreInfo = mysql_fetch_object($result1))
		{
			$arr['bank_name']			= $StoreInfo->name;
		}

		$sql2 = "SELECT shop_name FROM user_register_info WHERE bind_id = '$Detail->user_id'";
		$result2 = mysql_query($sql2,$_mysql_link_);
		while($store = mysql_fetch_assoc($result2)){
			$arr['shop_name']           = $store['shop_name'];
		}

		$sql = "SELECT express_id,freight_buyer,freight_seller,number,weight FROM order_express_paper where order_id='$id' AND company_id='$company_id'";
		$result3 = mysql_query($sql, $_mysql_link_);
		while($StoreInfo = mysql_fetch_object($result3))
		{
			$arr['post_fee']      = $StoreInfo->freight_seller;
			$arr['number']        = $StoreInfo->number;
			$arr['weight'] 	 	  = $StoreInfo->weight;
			$arr['freight_buyer'] = $StoreInfo->freight_buyer;
			$sql = "SELECT name FROM company_express_info WHERE status = 'Y' AND company_id='$company_id' AND express_id='$StoreInfo->express_id'";
			$resul = mysql_query($sql,$_mysql_link_);
			while($res = mysql_fetch_object($resul)){
				$arr['express_name'] = $res->name;
			}

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
		$arr['discount']				= number_format($Detail->discount,2);
		// $arr['store_id']				= $Detail->store_id;
		$arr['order_date'] 				= $Detail->order_date;
		$arr['bind_number'] 			= $Detail->bind_number;
		$arr['type'] 				    = $Detail->bind_type;
		if($arr['type'] == "System"){
			$arr['bind_type'] = "系统默认";
		}elseif($arr['type'] == "360buy"){
			$arr['bind_type'] = "京东商城";
		}else{
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
		$arr['related_order'] 			= $Detail->related_order;
		$arr['order_id']				= $id;
		$arr['bank_id']					= $Detail->bank_id;
		$arr['need_invoice']			= $Detail->need_invoice;
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

		$arr['theory_amount']			= number_format($Detail->theory_amount,2); //应收金额
		$arr['real_amount']				= number_format($Detail->real_amount,2);  // 实收金额
		$arr['arrears']					= number_format($Detail->arrears,2);	//欠款尾款
		$arr['payment_status']			= $Detail->payment_status;
		$arr['total_amount']            = $Detail->theory_amount-$arr['freight_buyer'];//商品总金额=实付-运费

		$arr['tax_number']              = $Detail->tax_number;
		$arr['tax_title']              	= $Detail->tax_title;
		$arr['tax_bank_name']           = $Detail->tax_bank_name;
		$arr['tax_bank_number']         = $Detail->tax_bank_number;
		$arr['tax_text']                = $Detail->tax_text;
		$arr['zong']                    = $Detail->theory_amount - $arr['freight_buyer'];
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
	//---- 表单的提交 ----
	if(isset($_POST['submit'])){
		//finance_order
		$real_amount       = $_POST['real_amount'];      				//实付金额
		$remain_amount     = $_POST['remain_amount'];           		//顾客欠款或者尾款
		$payment_status    = replace_safe($_POST['payment_status']);    //付款状态
		$order_id	   	   = intval($_POST['order_id']);	     		//订单ID
		$bankId	   	   	   = intval($_POST['bankId']);					//银行ID
		$afterAmount 	   = replace_safe($_POST['afterAmount']);		//增加前的实付金额
		$beforAmount 	   = replace_safe($_POST['beforAmount']);		//增加后的实付金额
		$sql = "UPDATE finance_order SET
		real_amount='$real_amount',
		payment_status='$payment_status',
		arrears='$remain_amount'
		WHERE order_id='$order_id' AND company_id='$company_id'";
		mysql_query($sql,$_mysql_link_);

		if (floatval($beforAmount) < floatval($afterAmount)) {
			//新增的金额
			$bRemain = floatval($afterAmount)-floatval($beforAmount);
			$bSql = "UPDATE finance_bank
					SET balance=balance+$bRemain
					WHERE id=$bankId AND company_id=$company_id";
			$bRes = mysql_query($bSql,$_mysql_link_);

			//向记录finance_cash_logs表中记录数据
			$amount_date = date('Y-m-d H:i:s',time());
			$logSql = "INSERT INTO finance_cash_logs(company_id,info_id,amount_date,company_type,money,bank_id)
				VALUES($company_id,$order_id,'$amount_date','Custom',$bRemain,$bankId)";
			$logRes = mysql_query($logSql,$_mysql_link_);
		}
		//跳转页面
		$url = $_SERVER["HTTP_REFERER"];
		header("Location: ".$url);exit;
	}

$xtpl->assign("main", $main);
$xtpl->parse("main");
$xtpl->out("main");

